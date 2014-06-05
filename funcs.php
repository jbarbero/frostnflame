<?php
require_once("html.php");
require_once("lib/mercfuncs.php");
require_once("lib/news-funcs.php");
require_once("lib/turnuse.php");
require_once("lib/crons.php");
require_once("lib/status.php");
require_once("external/nbbc.inc");

$hide_fatal_errors = false;

$closed = 0;
$open = 0;

function debuglog($message) {
    global $debugdb;
    $sqlbug = @db_safe_query("INSERT INTO $debugdb (date,message) VALUES(now(),'".addslashes($message)."')") or die(errorlog("Error: ".mysqli_error($GLOBALS["db_link"])));
}

function errorlog($message) {
    global $debugdb;
    $sqlerror = @db_safe_query("INSERT INTO $debugdb (date,message) VALUES(now(),'".addslashes($message)."')") or die("Error: ".mysqli_error($GLOBALS["db_link"]));
}

define("ONHTML", 1);
define("ONTXT", 2);
function on_disp($type, $online) {
    global $config;
    if(!$online)
        return "";
    else if($type == ONHTML)
        return $config['online_html'];
    else if($type == ONTXT)
        return $config['online_txt'];
}

function escape_javascript($str) {
    return strtr($str, array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n'));
}

function escape_quotes($str) {
    return preg_replace("%(?<!\\\\)'%", "\\'", $str);
}

function cnum($amt) {
    $pos = "+";
    $neg = "-";
    if ($nosign)
        $neg = $pos = "";
    $str = "<span class=";
    if ($amt < 0)
        $str .= '"cbad">'.$neg.$prefix;
    elseif ($amt > 0) 
        $str .= '"cgood">'.$pos.$prefix;
    else    $str .= '"cneutral">';
    $str .= commas(abs($amt));
    $str .= "</span>";
    return $str;
}

function template_display($file) {
    global $config;
    if(!isset($config['tpldir'])) {
        $config['tpldir'] = 'prom';
    }
    include("templates/" . $config['tpldir'] . "/" . $file);
}

$onhtml = $config['online_html'];
$ontxt = $config['online_txt'];


function is_on_vacation(&$user) {
    global $config;
    return ($user['vacation'] > 0) && ($user['vacation'] < ($config['minvacation'] + $config['vacationdelay']));
}

function vac_hours_left(&$user) {
    global $config;
    return ($config['minvacation'] + $config['vacationdelay'] - $user['vacation']);
}


function gamefactor($num) {
    global $config;
    return floor(normalizeNum($num) * $config['game_factor']);
}

function invfactor($num) {
    global $config;
    return floor(normalizeNum($num) / $config['game_factor']);
}


/* Generate a new nonce */
function rand_nonce($oldval) {
    $newval = $oldval;
    while($newval == $oldval)
        $newval = rand(16384, 32768);
    return $newval;
}


/* Just an alias to show what code has been audited for security. */
function db_safe_query($query) {
    global $db_link;
    $ret = mysqli_query($db_link, $query);    /* SAFE -- root call */
    if(!$ret) {
        echo "<pre>";
        echo "Query: $query\n\n";
        echo "Database error: " . mysqli_error($db_link) . "\n\n";
        debug_print_backtrace();
        echo "\n";
        die();
    } else {
        return $ret;
    }
}

// evaluate an SQL query, return first cell of first row
// useful for "SELECT count(*) ..." queries
function db_firstval($query) {        /* SAFE -- root declaration */
    /* Safe because it's up to the caller to do proper checking */
    $data = mysqli_fetch_row(db_safe_query($query));
    return $data[0];
}

/* Just an alias to show what code has been audited for security. */
function db_safe_firstval($query) {
    return db_firstval($query);        /* SAFE -- root call */
}

function replace($string) { 
    // print("-------" . $string . "----------<br />");
    if (!stristr($string, "&false=1")) {
        $string .= "&false=1";
    } 
    // print("$string");
    return $string . '"';
} 

// Handle minit quotes
function open_tag($string) {
    global $open; 
    // print("open...." . $open);
    $open++;
    if(!empty($string))
        $string = "($string)";
    $replace_with = "<table border='0' align='center' width='95%' cellpadding='3' cellspacing='1'><tr><td><b><tt>QUOTE</tt></b> $string </td></tr><tr><td id='QUOTE'><tt>";
    return $replace_with;
} 

function close_tag() {
    global $closed, $open; 
    // print("....close..." . $closed);
    if ($closed < $open) {
        // print("Needs to close!");
        $replace_with = "</tt></td></tr></table>";
        $closed++;
    } 
    return $replace_with;
} 


function loadSuid($su) {
    global $users, $urace, $uera, $uclan, $suid;
    if($su == 0)
        return;
    $users = loadUser($su);
    if(!$users[num])
        TheEnd("No such user!");
    $suid = $su;
    $urace = loadRace($users['race'], $users['era']);
    $uera = loadEra($users['era'], $users['race']);
    $uclan = loadClan($users[clan]);
    return true;
}


function makeAuthCode($num, $hash, $su, $srv, $cookie, $rsalt=0) {
    global $prefix, $auth, $authcode, $authstr;
    $nhash = md5($hash.$rsalt);
    $authcode = base64_encode("$num\n$nhash\n$su\n$srv");
    if($cookie) {
        setcookie($prefix.'_auth', $authcode);
        $authstr = '&amp;srv=' . SERVER;
    } else {
        $authstr = '&amp;auth=' . $authcode;
        $auth = $authcode;
    }
}

function auth_user($loose = false) {
    global $users, $prefix, $cookie, $auth, $tpl, $config, $authstr, $authcode, $root, $suid, $admin, $usecookie;
    
    if($root != 0) {
        if($suid != 0)
            loadSuid($suid);
        else
            loadSuid($root);
        return true;
    }

    if(isset($cookie['auth'])) {
        $auth = $cookie['auth'];
        $usecookie = true;
    } else if(isset($_GET['auth'])) {
        $auth = $_GET['auth'];
        $usecookie = false;
    } else if($loose) {
        return false;
    } else {
        include("login.php");
        exit;
    }
    $auth_a = explode("\n", base64_decode($auth));
    $num = $auth_a[0];
    $hash = $auth_a[1];
    $su = $auth_a[2];
    fixInputNum($num);
    fixInputNum($su);

    $users = loadUser($num);
    $chash = md5($users[password].$users[rsalt]);
    if ($chash == $hash) {
        if(!$usecookie || stristr($_SERVER['HTTP_REFERER'],substr($config['sitedir'],0,-1))) {
            $root = $users[num];
            if($users[disabled] != 2) {
                loadSuid($root);
                makeAuthCode($num, $users[password], 0, SERVER, $usecookie, $users[rsalt]);
                return true;
            } else if($users[disabled] == 2) {
                $admin = true;
                $hash = $users[password];
                $salt = $users[rsalt];
                if($su)
                    loadSuid($su);
                else
                    loadSuid($users[num]);
                makeAuthCode($num, $hash, $su, SERVER, $usecookie, $salt);
                return true;
            }
        }
    }

    if ($loose == false) {
        include("login.php");
        exit;
    } 
} 

function auth_global($new_nonce=false) {
    global $global, $config, $db_link;

    if($GLOBALS["db_link"] == NULL) {
        return false;
    }

    $auth_a = explode("\n", base64_decode($_COOKIE['global_auth']));
    $num = $auth_a[0];
    $hash = $auth_a[1];

    fixInputNum($num);

    $global = mysqli_fetch_array(db_safe_query("SELECT * FROM global_users WHERE num=$num;"), MYSQL_ASSOC);
    $chash = md5($global[password].$global[rsalt]);
    if ($chash == $hash) {
        $global = mysqli_fetch_array(db_safe_query("SELECT * FROM global_users WHERE num=$num;"), MYSQL_ASSOC);
        if($new_nonce) {
            $nonce = rand_nonce($global[nonce]);
            db_safe_query("UPDATE global_users SET rsalt='$nonce' WHERE num=$num;");
            $_COOKIE['global_auth'] =  base64_encode($num . "\n" . md5($global[password].$nonce));
            setcookie('global_auth', $_COOKIE['global_auth']);
        }

        return true;
    } else {
        return false;
    }
} 


function ban_ip($ip) {
//    fixInputNum($ip);
//    db_safe_query("UPDATE $playerdb SET disabled=3 WHERE IP LIKE '$ip';");
//    db_safe_query("INSERT INTO $prefix" . "_banned (banip) VALUES ('$ip');");
} 


function swear_filter($str) {
    //consider carefully: scrap, crass, etc.
    $swear_literals        = 'asshole|penis|fuck|shit|dick|cunt|fuk|bitch|bastard|vagina|piss';
                    //Retto is corrupting me!111
    $swear_at_beginning    = 'crap|sex';
    $swear_separate_word    = 'ass|arse|tit';
    $swear_regex = "/( ((\w*)($swear_literals)(\w*)) | ((\b|\A)($swear_at_beginning)(\w*)) | ((\b|\A)($swear_separate_word)(\b|\Z)) )/xi";
    return preg_replace($swear_regex, "*****", $str);
}


$bbcode = new BBCode;
$bbcode->SetDetectURLs(true);
$bbcode->SetSmileyURL("img/smilies/nbbc");

# overriding builtin: boggle.gif  boggle.gif  o.O  O.o
$bbcode->AddSmiley(':o', 'boggle.gif');
$bbcode->AddSmiley(':0', 'boggle.gif');
$bbcode->AddSmiley(':blink:', 'boggle.gif');
$bbcode->AddSmiley(':huh:', 'boggle.gif');

# overriding builtin: cool.gif    cool.gif    B)  B-)
$bbcode->RemoveSmiley('B)');

# overriding builtin: bigeyes.gif bigeyes.gif 8)  8-)
$bbcode->RemoveSmiley('8)');

# overriding builtin: anime.gif   anime.gif   ^_^
$bbcode->AddSmiley('^_^', 'anime.gif');
$bbcode->AddSmiley('^^', 'anime.gif');
$bbcode->AddSmiley('^.^', 'anime.gif');

# overriding builtin: sleepy.gif  sleepy.gif  :zzz:
$bbcode->AddSmiley('-_-', 'sleepy.gif');
$bbcode->AddSmiley('-.-', 'sleepy.gif');

# overriding builtin: laugh.gif   laugh.gif   XD  X-D
$bbcode->AddSmiley(':lol:', 'laugh.gif');

# overriding builtin: confuse.gif confuse.gif :?  :-?  =?  =-?
$bbcode->AddSmiley(':unsure:', 'confuse.gif');

# overriding builtin: angry.gif   angry.gif   >:(  >:-(  >=(  >=-(  D:  D-:  D=  D-=
$bbcode->AddSmiley(':angry:', 'angry.gif');

# overriding builtin: worry.gif   worry.gif   :s  :-S  =s  =-S
$bbcode->AddSmiley(':fear:', 'worry.gif');

# others
#$bbcode->AddSmiley(':rolleyes:', 'rolleyes.gif');


function bbcode_parse($str){
    global $open, $closed, $config, $bbcode;

    $str = swear_filter($str);
    $str = $bbcode->Parse($str);

    return $str;
} 

function aim_notify($num, $message) {} 

// adds commas to a number
function commas ($str) {
    return number_format($str, 0, ".", ",");
} 

function sqlQuotes (&$str) {
    global $db_link;
    $str = mysqli_real_escape_string($GLOBALS["db_link"], $str);
} 

// remove commas, make integer
function fixInputNum (&$num) {
    $num = round(str_replace(",", "", $num));
    $num = round(abs($num));
} 

function normalizeNum ($num) {
    return round(str_replace(",", "", $num));
} 

function fixInputNegativeNum (&$num) {
    $num = round(str_replace(",", "", $num));
} 

// randomize the mt_rand() function
function randomize() {
    mt_srand((double)microtime() * 1000000);
} 
// pluralize a string
function plural($num, $plur, $sing) {
    if ($num != 1)
        return $plur;
    else return $sing;
} 

function returnCNum($amt, $prefix, $nosign) {
    $ret = '';
    $pos = "+";
    $neg = "-";
    if ($nosign)
        $neg = $pos = "";
    $ret .= '<span class=';

    if ($amt < 0)
        $ret .= '"cbad">' . $neg . $prefix;
    elseif ($amt > 0)
        $ret .= '"cgood">' . $pos . $prefix;
    else $ret .= '"cneutral">';
    $ret .= commas(abs($amt));
    $ret .= '</span>';
    return $ret;
} 

// prints a number, colored according to its positivity/negativity.
// Set nosign=1 to omit the +/-
function printCNum ($amt, $prefix, $nosign) {
    $pos = "+";
    $neg = "-";
    if ($nosign)
        $neg = $pos = "";
    print '<span class=';
    if ($amt < 0)
        print '"cbad">' . $neg . $prefix;
    elseif ($amt > 0)
        print '"cgood">' . $pos . $prefix;
    else print '"cneutral">';
    print commas(abs($amt)) . '</span>';
} 

// returns the requested networth
function getNetworth (&$user) {
    global $config;
    $net = 0;

    if($config['alt_networth'] == 0) {    //default formula
        foreach($config[troop] as $num => $mktcost) {
            $net += ($user[troop][$num] * $mktcost / $config[troop][0]);
            $net += ($config['market_nw'] * $user[pubmarket][$num] * $mktcost / $config[troop][0]);
        }
        $net +=    ($user[wizards] * 2)
            + ($user[peasants] * 3) +
            + (
                ($user[cash] + $user[savings] / 2 - $user[loan] * 2 
                    // stocks
                    ) /
                (5 * $config[troop][0])
                )
            + ($user[land] * 500) + ($user[freeland] * 100)
            + ($user[food] * $config[food] / ($config[troop][0] * 3))
            + ($config['market_nw'] * $user[pubmarket_food] * $config[food] / ($config[troop][0] * 3))
            ;
    } else {                //alternative formula
        foreach($config[troop] as $num => $mktcost) {
            $net += ($user[troop][$num] * $mktcost / $config[troop][0]);
            $net += ($config['market_nw'] * $user[pubmarket][$num] * $mktcost / $config[troop][0]);
        }
        $net +=    ($user[wizards] * 2) + ($user[peasants] * 3);
        $cashn = $user[cash];        //needed cash
        $cash = min($user[cash], $cashn) + $user[savings] / 2 - $user[loan] * 2;
        $net += ($cash / (2 * $config[troop][0]));

        $foodn = $user[food];        //needed food
        $food = min($user[food], $foodn);
        $net += ($food * $config[food] / $config[troop][0] * 2);

        $mktfoodn = $user[pubmarket_food];        //needed food
        $mktfood = min($user[pubmarket_food], $mktfoodn);
        $net += ($config['market_nw'] * $mktfood * $config[food] / $config[troop][0] * 2);

        $net +=    ($user[land] * 1000) - ($user[freeland] * 500);
    }
    $net = floor($net);
    if ($net > 0) return $net;
    else return 1;
} 

function pci ($user, $race) {
    if($user[land] == 0)
        return 0;
    return round(25 * (1 + $user[shops] / $user[land]) * $race[pci]);
} 

// loads the information for the specified user number
function loadUser ($num) {
    global $config, $playerdb, $time, $turnsper, $perminutes;
    fixInputNum($num);
    $user = @mysqli_fetch_array(db_safe_query("SELECT * FROM $playerdb WHERE num=$num;"), MYSQL_ASSOC);
    if(!$user[num])
        return;
    $user[troop] = explode("|", $user[troops]);
    $user[ind] = explode("|", $user[production]);
    $user[pmkt] = explode("|", $user[pvmarket]);
    $user[bmper] = explode("|", $user[bmper]);
    $user[troop_res] = explode("|", $user[troops_res]);
    $user[pubmarket] = explode("|", $user[pubmarket]);
    foreach($user[troop] as $num => $amt){
        // This floatval call may have caused problems
        //$user[troop][$num] = floatval($amt);
        $user[troop][$num] = intval($amt);
    }

    if (($user[disabled] == 0 || $user[disabled] == 2) && $user[vacation] == 0)
    {
        $times = floor(($time-$user['turns_last'])/(60*$config['bankperminutes']));
        if($times > 0) {
            // added for turn bank
            $turnbanktimes = floor(($time-$user['turnbank_last'])/(60*$config['bankperminutes']));
            $user[turnbank] += $turnbanktimes*$config['turnbankper'];
            if($user[turnbank] > $config[maxturnbank]) {
                $user[turnbank] = $config[maxturnbank];
            }
            // end turnbank mod
        }
    
        //Turns, Forces, Attacks
        if(PERMINUTES == 0)
            $times = $config[maxturns];
        else
            $times = floor(($time-$user['turns_last'])/(60*$perminutes));
        if($times > 0) {
            $user[turns] += $times*$turnsper;
            if($user[turnsstored] > 0) {
                $out = min($times, $user[turnsstored]);        
                $user[turns] += $out;
                $user[turnsstored] -= $out;
            }
            if($user[turns] > $config[maxturns]) {
                $user[turnsstored] += ($user[turns]-$config[maxturns]);
                $user[turns]  = $config[maxturns];
            }
            if($user[turnsstored] > $config[maxstoredturns])
                $user[turnsstored] = $config[maxstoredturns];
            
            if($user[forces] < 11 && $user[forces] > 0) {
                $user[forces] -= $times;
                if($user[forces] < 1)
                    $user[forces] = 1;
                fixInputNum($user[forces]);
            }
    
            $last = $time - $time%(60*$perminutes);
            $lastturnbank = $time - $time%(60*$config['bankperminutes']);
            fixInputNum($user[turns]);
            fixInputNum($user[turnsstored]);
            fixInputNum($user[turnbank]);
            fixInputNum($last);
            fixInputNum($user[forces]);
            $q = "UPDATE $playerdb SET turns=$user[turns],turnsstored=$user[turnsstored],turnbank=$user[turnbank],turnbank_last=$lastturnbank,turns_last=$last,forces=$user[forces] WHERE num=$user[num];";
            //$q = "UPDATE $playerdb SET turns=$user[turns],turnsstored=$user[turnsstored],turns_last=$last,forces=$user[forces] WHERE num=$user[num];";
            db_safe_query($q);
            $str = mysqli_error($GLOBALS["db_link"]);
            if($str)
                echo "<b>PLEASE REPORT IMMEDIATELY: '$q': $str</b><br>";
        }
    }

    //$times = floor(($time-$user['hour_last'])/3600);
    $times = floor(($time-$user['hour_last'])/1800);  // remove attacks at a rate of -1 every 30 minutes
    if($times > 0) {
        if($user[attacks] > 0) {
            $user[attacks] -= $times;
            if($user[attacks] < 0)
                $user[attacks] = 0;
        }
        //$last = $time - $time%3600;
        $last = $time - $time%1800;  // don't make them safe for as long
        fixInputNum($last);
        fixInputNum($user[attacks]);
        $q = "UPDATE $playerdb SET hour_last=$last,attacks=$user[attacks] WHERE num=$user[num];";
        db_safe_query($q);
        $str = mysqli_error($GLOBALS["db_link"]);
        if($str)
            echo "<b>PLEASE REPORT IMMEDIATELY: '$q': $str</b><br>";
    }
    
    //include("lib/pvtsellcron.php");
    //include("lib/pvtbuycron.php");
    
    return $user;
} 
// loads the information for the specified race number
function loadRace ($race, $era) {
    global $config;
    $num = 100*$era+$race;
    $urace = $config['er'][$num];
    $urace['name'] = $urace['rname'];
    $urace['id'] = $race;
    return $urace;
} 
// loads the information for the specified era number
function loadEra ($era, $race = 1) {
    global $config;
    $num = 100*$era+$race;
    $uera = $config['er'][$num];
    $uera['name'] = $uera['ename'];
    $uera['id'] = $era;
    $uera['food'] = $uera['nfood'];
    $uera['food_lc'] = strtolower($uera['nfood']);
    $uera['runes'] = $uera['nrunes'];
    $uera['farms'] = $uera['nfarms'];
    $uera['peasantsC'] = $uera['peasants'];
    $uera['peasants'] = strtolower($uera['peasants']);
    $uera['empireC'] = ucfirst($uera['empire']);
    $uera['empire'] = strtolower($uera['empire']);
    return $uera;
} 
// loads the information for the specified clan number
function loadClan ($num) {
    global $clandb, $perminutes, $playerdb, $time;
    fixInputNum($num);
    $clan = @mysqli_fetch_array(db_safe_query("SELECT * FROM $clandb WHERE num=$num;"), MYSQL_ASSOC);
    if(!$clan[num])
        return;

    $times = floor(($time-$clan['cron_last'])/(60*$perminutes));
    if($times > 0) {
        # Members
        $clan[members] = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE clan=$clan[num] AND land>0 AND disabled<2;");

        # Delete?
        if($clan[members] == 0) {
            db_safe_query("UPDATE $clandb SET ally1=0 WHERE ally1=$clan[num];");
            db_safe_query("UPDATE $clandb SET ally2=0 WHERE ally2=$clan[num];");
            db_safe_query("UPDATE $clandb SET ally3=0 WHERE ally3=$clan[num];");
            db_safe_query("UPDATE $clandb SET ally4=0 WHERE ally4=$clan[num];");
            db_safe_query("UPDATE $clandb SET ally5=0 WHERE ally5=$clan[num];");
            db_safe_query("UPDATE $clandb SET  war1=0 WHERE  war1=$clan[num];");
            db_safe_query("UPDATE $clandb SET  war2=0 WHERE  war2=$clan[num];");
            db_safe_query("UPDATE $clandb SET  war3=0 WHERE  war3=$clan[num];");
            db_safe_query("UPDATE $clandb SET  war4=0 WHERE  war4=$clan[num];");
            db_safe_query("UPDATE $clandb SET  war5=0 WHERE  war5=$clan[num];");
            db_safe_query("UPDATE $clandb SET members=-1 WHERE num=$clan[num];");
            return $clan;
        }

        # Founder
        $founder = loadUser($clan[founder]);
        if($founder[clan] != $clan[num] || empty($founder) || $founder[land] == 0 || $founder[disabled] > 1) {
                $founder = $clan[founder];
            if($newf = db_safe_firstval(db_safe_query("SELECT asst FROM $clandb WHERE num=$clan[num];")))
                $founder = $newf;
            else if($newf = db_safe_firstval(db_safe_query("SELECT fa1 FROM $clandb WHERE num=$clan[num];")))
                $founder = $newf;
            else if($newf = db_safe_firstval(db_safe_query("SELECT fa2 FROM $clandb WHERE num=$clan[num];")))
                $founder = $newf;
            else if($newf = db_safe_firstval("SELECT num FROM $playerdb WHERE clan=$clan[num] AND land>0 AND disabled!=2 AND disabled!=3 ORDER BY networth DESC;"))
                $founder = $newf;
            $clan[founder] = $founder;

            $newf = loadUser($newf);
            addNews(116, array(id1=>$newf[num], clan1=>$newf[clan]));
        }
        saveClanData($clan, "members founder");
    }

    return $clan;
} 

// Loads all clan tags into an associative array
function loadClanTags () {
    global $clandb;
    $clans = db_safe_query("SELECT num,tag FROM $clandb;");
    while ($clan = mysqli_fetch_array($clans))
    $ctags["$clan[num]"] = $clan[tag];
    $ctags["0"] = "None";
    return $ctags;
} 

function makeMsg($time, $src, $dst, $body, $title) {
    global $messagedb;

    fixInputNum($time);
    fixInputNum($src);
    fixInputNum($dst);
    sqlQuotes($body);
    sqlQuotes($title);

    db_safe_query("INSERT INTO $messagedb (time,src,dest,msg,title) VALUES ($time, $src, $dst, '$body', '$title');");
}

// Example: saveUserData($users,"cash networth land food etc");
function saveUserData ($user, $data, $overrideDBlock = false) {
    global $playerdb, $config, $marketdb, $lockdb, $hide_fatal_errors;
    if ($lockdb && !$overrideDBlock)
        return;

    $items = explode(" ", $data);
    $update = "";
    $i = 0;
    while (isset($items[$i])) {
        $tmp = $items[$i];
        $i += 1;
        if($tmp == 'pubmarket') {
            $mkt_counts = array();
            foreach($config[troop] as $trpnum => $mktcost)
                $mkt_counts[] = db_safe_firstval("select sum(amount) from $marketdb where seller=$user[num] and type='troop$trpnum';");
            $data = implode("|", $mkt_counts);
        } else if($tmp == 'pubmarket_food') {
            $data = db_safe_firstval("select sum(amount) from $marketdb where seller=$user[num] and type='food';");
        } else if($tmp == 'pubmarket_runes') {
            $data = db_safe_firstval("select sum(amount) from $marketdb where seller=$user[num] and type='runes';");
        } else if($tmp == 'troops') {
            $data = implode("|", $user[troop]);
        } else if($tmp == 'production') {
            $data = implode("|", $user[ind]);
        } else if($tmp == 'pvmarket') {
            $data = implode("|", $user[pmkt]);
        } else if($tmp == 'bmper') {
            $data = implode("|", $user[bmper]);
        } else if($tmp == 'troops_res') {
            $data = implode("|", $user[troop_res]);
        } else if($tmp == 'networth') {
            $data = getNetworth($user);
        } else {
            $data = $user[$tmp];
        }

        if (is_numeric($data)) {
            if ($data < 0 && strtolower($tmp) != 'ip')
                $data = 0;
            $update .= "$tmp=$data";
        } else {
            sqlQuotes($data);
            $update .= "$tmp='$data'";
        } 
        if (isset($items[$i])) $update .= ",";
    } 
    if (!db_safe_query("UPDATE $playerdb SET $update WHERE num=$user[num];") && !$hide_fatal_errors)
        print "FATAL ERROR: Failed to update player data $update for user #$user[num]!<!--prob1--><BR>\n";
} 

function HallOfFame($user) {
    global $nolimitdb;
    sqlQuotes($user[name]);
    sqlQuotes($user[empire]);
    db_safe_query("INSERT INTO $nolimitdb (num, name, empire, land, networth, race, location, off, offsucc, def, defsucc, kills) VALUES 
        ($user[num], '$user[name]', '$user[empire]', $user[land], $user[networth], $user[race], $user[era], $user[offtotal], $user[offsucc], 
        $user[deftotal], $user[defsucc], $user[kills]);");
}

// Example: saveClanData($uclan,"name motd ally1 war3");
function saveClanData (&$clan, $data, $overrideDBlock = false) {
    global $clandb, $lockdb, $hide_fatal_errors;
    if ($lockdb && !$overrideDBlock)
        return;
    $items = explode(" ", $data);
    $update = "";
    $i = 0;
    while ($tmp = $items[$i++]) {
        $data = $clan[$tmp];
        if (is_numeric($data)) {
            if ($data < 0)
                $data = 0;
            $update .= "$tmp=$data";
        } else {
            sqlQuotes($data);
            $update .= "$tmp='$data'";
        } 
        if ($items[$i]) $update .= ",";
    } 
    if (!db_safe_query("update $clandb set $update where num=$clan[num];") && !$hide_fatal_errors)
        print "FATAL ERROR: Failed to update clan data $update for clan #$clan[num]!<BR>\n";
} 

// Example: saveClanData($uclan,"name motd ally1 war3");
function saveGlobalData (&$global, $data) {
    global $hide_fatal_errors;

    $items = explode(" ", $data);
    $update = "";
    $i = 0;
    while ($tmp = $items[$i++]) {
        $data = $global[$tmp];
        if (is_numeric($data)) {
            if ($data < 0)
                $data = 0;
            $update .= "$tmp=$data";
        } else {
            sqlQuotes($data);
            $update .= "$tmp='$data'";
        } 
        if ($items[$i]) $update .= ",";
    } 
    if (!db_safe_query("update global_users set $update where num=$global[num];") && !$hide_fatal_errors)
        print "FATAL ERROR: Failed to update clan data $update for account #$global[num]!<BR>\n";
} 

// function to return amount of land
function gimmeLand($currland, $bonus, $era) {
    global $config;
    if ($era == 1)
        $multip = 1;
    elseif ($era == 2)
        $multip = 1.4;
    elseif ($era == 3)
        $multip = 1.8;
    else
        $multip = 1;
    return ceil((1 / ($currland * .00022 + .25)) * 20 * $bonus * $multip * $config[landmult]);
} 

function calcSizeBonus($networth) {
    if ($networth <= 100000)
        $size = 0.524;
    elseif ($networth <= 500000)
        $size = 0.887;
    elseif ($networth <= 1000000)
        $size = 1.145;
    elseif ($networth <= 10000000)
        $size = 1.294;
    elseif ($networth <= 100000000)
        $size = 1.454;
    else $size = 1.674;
    return $size;
} 

// Take a specified number of turns performing the given action
// Valid actions (so far): cash, land, war; others will be added as necessary
function takeTurns ($numturns, $action, $noutput = false, $displayturns=-1) {
    $losspercent = .97;
    if ($users['hero_special'] == 3) {// Hebe?
        $losspercent = .99;
        }
    global $tpl, $config, $time, $turnoutput, $hide_turns, $cnd, $taken;
    global $users, $urace, $uera, $landgained, $cashgained, $runesgained, $foodgained, $warflag;
    $taken = 0;
    $turnoutput = "";
    $hideturns = $hide_turns;
    $lackof = 1;
    $cnd = '';
    if ($hide_turns) {
        $users['condense'] = 1;
        $cnd = ' checked';
    } else {
        $users['condense'] = 0;}
    global $condense;
    $condense = $cnd;

    global $net_income, $net_expenses, $net_wartax, $net_loanpayed, $net_money, $net_foodpro, $net_foodcon, $net_food, $net_peasants, $net_wizards, $net_runes, $net_troop;
    $runesgained;
    $urace = loadRace($users[race], $users[era]);
    if ($users[era] == 1) {
        $urace[ind] *= .95;
        $urace[runes] *= 1.2;} 
    if ($users[era] == 3){
        $urace[ind] *= 1.15;}

    $oldrunes = $urace[runes];
    $oldind = $urace[ind];
    $olduser = $users;

    if ($numturns > $users[turns]){
        TheEnd('<span class="cbad">FATAL ERROR</span>: attempted to use more turns than available!');}

    if (($action == 'cash') || ($action == 'land') || ($action == 'farm') || ($action == 'runes') || ($action == 'heal') || ($action == 'industry')){ // Actions which can be aborted
        $nonstop = 0;}
    else {
        $nonstop = 1;}

    while ($taken < $numturns) { // use up specified number of turns
            $taken++;
            $users[networth] = getNetworth($users);
            if ($action == 'land') { // exploring?
                $tmp = gimmeLand($users[land], $urace[expl], $users[era]);
                $users[land] += $tmp;
                $users[freeland] += $tmp;
                $landgained += $tmp; 
            } 
            $size = calcSizeBonus($users[networth]); // size bonus/penalty
            $loanrate = $config[loanbase] + $size; // update savings and loan
            $saverate = $config[savebase] - $size;
            $users[loan] *= 1 + ($loanrate / 52 / 100);
            $users[loan] = round($users[loan]);
            if ($users[savings] <= ($users[networth] * $config['maxsave'])) {
                if ($users[turnsused] > $config[protection]) // no savings interest while under protection
                    $users[savings] *= 1 + ($saverate / 52 / 100);
                $users[savings] = round($users[savings]);
            }

            /*
               if ($users[savings] > ($users[networth] * $config['maxsave'])) {

            //$minuscash = min(($users[savings] * ($config['savingsper']) / 100),($users[savings] - ($users[networth] * $config['maxsave']))); // removes excess savings
            $minuscash = ($users[savings] - ($users[networth] * $config['maxsave'])); // removes excess savings
            $users[savings] -= $minuscash;
            $users[cash] += $minuscash;
            }
             */

            // money
            $income = calcIncome();
            $expenses = calcExpenses();
            $loanpayed = calcLoanPay();
            if($action == 'cash') {//Cashing? (Looting... etc)
                $income = round(1.25 * $income * $config[cashmult]);
            }
            $money = $income - $expenses;
            $cashgained += $money;
            $users[loan] -= $loanpayed;
            $users[cash] += $money; 

            // build extra units
            $troop = array();
            foreach($config[troop] as $num => $mktcost) {
                $troop[$num] = floor(($users[industry] * ($users[ind][$num] / 100)) * (600 / $mktcost) * $urace[ind] * $config[indc]);

                if ($action == 'industry'){//Recruiting? FIXME: That's probably not spelled right... 
                    $troop[$num] *= 1.25;
                    $troop[$num] *= $config[indmult];
                }

                if($config['strat_balance'])
                    $troop[$num] *= 1.50;

                $users[troop][$num] += $troop[$num];
            }

            // update food
            $foodpro = calcFoodPro();
            $foodcon = calcFoodCon();
            if ($action == 'farm') // farming?
                $foodpro = round(1.25 * $foodpro * $config[foodmult]);
            $food = $foodpro - $foodcon;
            $users[food] += $food;
            $foodgained += $food; 

            // health
            if (($users[health] < (100 - (($users[tax] - 10) / 2))) && ($users[health] < 100))
                    $users[health]++;
            if ($users[health] < 100 && $action == 'heal')// healing?
                    $users[health]++;
            if ($users[health] < 100 && $users['hero_special'] == 1) // Asclepius?
                $users[health]++; 
            
            // taxes
            $taxrate = $users[tax] / 100;
            if ($users[tax] > 40)
                $taxpenalty = ($taxrate - 0.40) / 2;
            if ($users[tax] < 20)
                $taxpenalty = ($taxrate - 0.20) / 2; 
            
            // update population
            $popbase = round((($users[land] * 2) + ($users[freeland] * 5) + ($users[homes] * 60)) / (0.95 + $taxrate + $taxpenalty));
            if($config['strat_balance'])
                $popbase = round((($users[land] * 2) + ($users[freeland] * 5) + ($users[homes] * 300)) / (0.95 + $taxrate + $taxpenalty));
            else
                $popbase = round((($users[land] * 2) + ($users[freeland] * 5) + ($users[homes] * 60)) / (0.95 + $taxrate + $taxpenalty));

            if ($users['hero_peace'] == 3) // Hestia?
                $popbase *= 5;

            $popbase *= $config['popbase_capacity'];

            if ($users[peasants] != $popbase)
                $peasants = ($popbase - $users[peasants]) / 20;
            if ($peasants > 0)
                $peasmult = (4 / (($users[tax] + 15) / 20)) - (7 / 9);
            if ($peasants < 0)
            {
                $peasmult = 1 / ((4 / (($users[tax] + 15) / 20)) - (7 / 9));
                $peasmult *= $config['popbase_loss_rate'];
            }
            $peasants = round($peasants * $peasmult * $peasmult);
            $users[peasants] += $peasants; 
            
            // gain magic energy
            $runes = 0;
            if (($users[labs] / $users[land]) > .15)
                    $runes = mt_rand(round($users[labs] * 1.1), round($users[labs] * 1.5));
            else $runes = round($users[labs] * 1.1);
            $runes = round($runes * $urace[runes]);
            if ($users['hero_peace'] == 2) // Dionysus?
                    $runes *= 1.5;
            if ($action == 'runes') { // training?
                    $runes *= 1.25;
                    $runes *= $config[runesmult];
            }

            if($config['strat_balance'])
                    $runes *= 1.10;

            $users[runes] += $runes;
            $runesgained += $runes;
            $wizards = 0;
            $wplmod = $config['wpl'] * 0.01;
            // These values in the midst of adjustment
            if ($users[wizards] < ($users[labs] * 25 * $wplmod))
                    $wizards = round($users[labs] * 0.25 * $wplmod);
            elseif ($users[wizards] < ($users[labs] * 50 * $wplmod))
                    $wizards = round($users[labs] * 0.20 * $wplmod);
            elseif ($users[wizards] < ($users[labs] * 90 * $wplmod))
                    $wizards = round($users[labs] * 0.10 * $wplmod);
            elseif ($users[wizards] < ($users[labs] * 100 * $wplmod))
                    $wizards = 0;
            elseif ($users[wizards] < ($users[labs] * 175 * $wplmod))
                    $wizards = 0;
            elseif ($users[wizards] > ($users[labs] * 175 * $wplmod))
                    $wizards = round($users[wizards] * - .05);
            $users[wizards] += $wizards;
            // save status report
            $urace[runes] = $oldrunes;
            $urace[ind] = $oldind;
            $net_income += $income;
            $net_expenses += $expenses;
            $net_wartax += $wartax;
            $net_loanpayed += $loanpayed;
            $net_money += $money;
            $net_foodpro += $foodpro;
            $net_foodcon += $foodcon;
            $net_food += $food;
            $net_peasants += $peasants;
            $net_wizards += $wizards;
            $net_runes += $runes;
            foreach($config[troop] as $num => $mktcost) {
                    $net_troop[$num] += $troop[$num];
            }

            // ran out of money/food? lose 3% of all units
            if (($users[food] < 0) || ($users[cash] < 0)) {
                    $users[peasants] = round($users[peasants] * $losspercent);
                    foreach($users[troop] as $num => $amt) {
                            $users[troop][$num] = round($amt * $losspercent);
                    }
                    $users[wizards] = round($users[wizards] * $losspercent);
                    if ($users[food] < 0) $users[food] = 0;
                    if ($users[cash] < 0) $users[cash] = 0;
                    $lackof *= $losspercent;
            } 

            if (!$hideturns) {
                if ($users[food] == 0) {
                    $loss = "food";
                    if ($users[cash] == 0)
                        $loss = " and cash";
                } else $loss = "cash";

                $percent = round((1 - $losspercent) * 100); //don't cumulate a display thingy
                $lackmessage = '';
                if ($lackof != 1) {
                        $lackmessage .= "<span class='cbad'>Due to lack of $loss, $percent% of your ".$uera[peasants]." and troops have left!";
                        if (!$nonstop)
                                $lackmessage .= ' Turns were stopped!</span><br>';
                        else
                                $lackmessage .= '</span><br>';
                } 

                $turnoutput .= '<table class="empstatus">
                        <tr><td style="vertical-align:top"><table>
                        <tr class="inputtable"><th colspan="2">Economic Status</th></tr>
                        <tr><th>Income:</th>
                        <td>$' . commas(gamefactor($income)) . '</td></tr>
                        <tr><th>Expenses:</th>
                        <td>$' . commas(gamefactor($expenses)) . '</td></tr>
                        <tr><th>War Tax:</th>
                        <td>$' . commas(gamefactor($wartax)) . '</td></tr>
                        <tr><th>Loan Pay:</th>
                        <td>$' . commas(gamefactor($loanpayed)) . '</td></tr>
                        <tr><th>Net:</th>
                        <td>' . returnCNum(gamefactor($money), "$", 0) . '</td></tr>
                        </table></td>
                        <td style="vertical-align:top"><table>
                        <tr class="inputtable"><th colspan="2">Agricultural Status</th></tr>
                        <tr><th>Produced:</th>
                        <td>' . commas(gamefactor($foodpro)) . '</td></tr>
                        <tr><th>Consumed:</th>
                        <td>' . commas(gamefactor($foodcon)) . '</td></tr>
                        <tr><th>Net:</th>
                        <td>' . returnCNum(gamefactor($food), "", 0) . '</th></tr>
                        </table></td>
                        <td style="vertical-align:top"><table>
                        <tr class="inputtable"><th colspan="2">Population & Military Status</th></tr>
                        <tr><th>' . $uera[peasantsC] . ':</th>
                        <td>' . returnCNum($peasants, "", 0) . '</td></tr>
                        <tr><th>' . $uera[wizards] . ':</th>
                        <td>' . returnCNum(gamefactor($wizards), "", 0) . '</td></tr>
                        <tr><th>' . $uera[runes] . ':</th>
                        <td>' . returnCNum(gamefactor($runes), "", 0) . '</td></tr>';
                foreach($config[troop] as $num => $mktcost) {
                        $turnoutput .= 
                                '<tr><th>' . $uera["troop$num"] . ':</th>
                                <td>' . returnCNum(gamefactor($troop[$num]), "", 0) . '</td></tr>';
                }
                $turnoutput .= '
                    </table></td></tr>';

                if ($net_peasants > 0)
                    $turnoutput .= '
                        <tr><td colspan="3"><center><span class="cgood">Your low tax rate is encouraging ' . $uera[peasants] . ' to join your ' . $uera[empire] . '!</center></td></tr>';
            
            
                $turnoutput .= '
                    <tr><td colspan="3"><center><hr width="80%">' . $lackmessage . '</center></td></tr>
                    <table>';
            } 

            $users[turnsused]++;
            $users[turns]--;

            if ($users[food] <= 0 || $users[cash] <= 0) {
                    if (!$nonstop)
                            break;
            } 
    } 

    // print report
    $turnsused = $olduser[turns] - $users[turns];

    if ($users[food] == 0) {
            $loss = "food";
            if ($users[cash] == 0)
                    $loss = " and cash";
    } else $loss = "cash";

    $percent = round((1 - $lackof) * 100);

    $lackmessage = '';
    if ($lackof != 1) {
            $lackmessage .= "<span class='cbad'>Due to lack of $loss, $percent% of your ".$uera[peasants]." and troops have left!";
            if (!$nonstop)
                    $lackmessage .= ' Turns were stopped!</span><br>';
            else
                    $lackmessage .= '</span><br>';
    } 

    if ($hideturns) {
            $turnoutput = '<table class="empstatus">
                    <tr class="inputtable"><th colspan="3" valign="top" align="middle">
                    <b>Turns Used: ' . ($displayturns == -1 ? $taken : $displayturns) . '</b></th></tr>
                    <tr><td style="vertical-align:top"><table>
                    <tr class="inputtable"><th colspan="2">Economic Status</th></tr>
                    <tr><th>Income:</th>
                    <td>$' . commas(gamefactor($net_income)) . '</td></tr>
                    <tr><th>Expenses:</th>
                    <td>$' . commas(gamefactor($net_expenses)) . '</td></tr>
                    <tr><th>War Tax:</th>
                    <td>$' . commas(gamefactor($net_wartax)) . '</td></tr>
                    <tr><th>Loan Pay:</th>
                    <td>$' . commas(gamefactor($net_loanpayed)) . '</td></tr>
                    <tr><th>Net:</th>
                    <td>' . returnCNum(gamefactor($net_money), "$", 0) . '</td></tr>
                    </table></td>
                    <td style="vertical-align:top"><table>
                    <tr class="inputtable"><th colspan="2">Agricultural Status</th></tr>
                    <tr><th>Produced:</th>
                    <td>' . commas(gamefactor($net_foodpro)) . '</td></tr>
                    <tr><th>Consumed:</th>
                    <td>' . commas(gamefactor($net_foodcon)) . '</td></tr>
                    <tr><th>Net:</th>
                    <td>' . returnCNum(gamefactor($net_food), "", 0) . '</th></tr>
                    </table></td>
                    <td style="vertical-align:top"><table>
                    <tr class="inputtable"><th colspan="2">Population & Military Status</th></tr>
                    <tr><th>' . $uera[peasantsC] . ':</th>
                    <td>' . returnCNum($net_peasants, "", 0) . '</td></tr>
                    <tr><th>' . $uera[wizards] . ':</th>
                    <td>' . returnCNum(gamefactor($net_wizards), "", 0) . '</td></tr>
                    <tr><th>' . $uera[runes] . ':</th>
                    <td>' . returnCNum(gamefactor($net_runes), "", 0) . '</td></tr>';

            foreach($config[troop] as $num => $mktcost) {
                $turnoutput .= 
                    '<tr><th>' . $uera["troop$num"] . ':</th>
                    <td>' . returnCNum(gamefactor($net_troop[$num]), "", 0) . '</td></tr>';
            }

            $turnoutput .= '
                </table></td></tr>';

            if ($net_peasants > 0)
                $turnoutput .= '
                <tr><td colspan="3"><center><span class="cgood">Your low tax rate is encouraging ' . $uera[peasants] . ' to join your ' . $uera[empire] . '!</center></td></tr>';


            $turnoutput .= '
                <tr><td colspan="3"><center><hr width="80%">' . $lackmessage . '</center></td></tr>
                <table>';
    } 
    // end print report
    saveUserData($users, "networth land freeland savings loan cash troops food health peasants runes wizards turnsused turns idle condense");
    global $turnoutput;

    if($config['nolimit_mode']) {
            if($users[turns] == 0) {
                    //bye bye my friend
                    $users[password] = 'locked';
                    saveUserData($users, "password");
                    HallOfFame($users);
            }
    }

    return $taken;
} 

function printSearchHeader ($color, $clan=true) {
    global $uera;
    ?>
<tr class="era<?=$color?>">
    <th style="width:5%" class="aright">Rank</th>
    <th style="width:25%"><?=$uera[empireC]?></th>
    <th style="width:10%" class="aright">Land</th>
    <th style="width:15%" class="aright">Networth</th><? if($clan) { ?>
    <th style="width:10%">Clan</th>
<? } ?><th style="width:10%">Race</th>
    <th style="width:5%">Location</th>
    <th style="width:8%">O</th>
    <th style="width:8%">D</th>
    <th style="width:4%">K</th></tr>
<?php
} 

function printSearchLine ($clan=true, $era=true) {
    global $users, $enemy, $ctags, $rtags, $etags, $racedb, $eradb, $config, $authstr;
    $color = "normal";
    $mclan = loadClan($users[num]);
    if ($enemy[num] == $users[num])
        $color = "self";
    elseif ($enemy[land] == 0)
        $color = "dead";
    elseif ($enemy[disabled] == 2)
        $color = "admin";
    elseif ($enemy[disabled] == 3)
        $color = "disabled";
    elseif (($enemy[turnsused] <= $config[protection]) || ($enemy[vacation] > $config[vacationdelay]))
        $color = "protected";
    elseif (($users[clan]) && ($enemy[clan] == $users[clan]))
        $color = "ally";
    $captdet = loadClan($enemy[clan]);
    $leader = "";
    if ($captdet[founder] == $enemy[num]) $leader = "*";
        $ccolor = "mnormal";
        if (($enemy[clan] == $mclan[ally1]) || ($enemy[clan] == $mclan[ally2]) || ($enemy[clan] == $mclan[ally3]) || ($enemy[clan] == $mclan[ally4]) || ($enemy[clan] == $mclan[ally5])) {
                $ccolor = "mally";
        } else if (($enemy[clan] == $mclan[war1]) || ($enemy[clan] == $mclan[war2]) || ($enemy[clan] == $mclan[war3]) || ($enemy[clan] == $mclan[war4]) || ($enemy[clan] == $mclan[war5])) {
                $ccolor = "mdead";
        }

        $racecolor_s = '<a href="?guide2&amp;section=races'.$authstr.'" class="m'.$color.'">';
        $racecolor_e = '</a>';

    if($enemy[clan])
        $clname = "$leader<a class=\"$ccolor\" href=\"?clancrier&amp;sclan=$enemy[clan]$authstr\">".$ctags["$enemy[clan]"]."</a>$leader";
    else
        $clname = 'None';
    ?>
<tr class="m<?=$color?>">
    <td class="aright"><?php echo on_disp(ONHTML, $enemy[online]);

    ?><?=$enemy[rank]?></td>
    <td class="acenter"><?=$enemy[empire]?> <a class=proflink href="?profiles&num=<?=$enemy[num]?><?=$authstr?>">(#<?=$enemy[num]?>)</a></td>
    <td class="aright"><?=commas($enemy[land])?></td>
    <td class="aright">$<?=commas($enemy[networth])?></td><? if($clan) { ?>
    <td class="acenter"><?=$clname?></td>
<? } ?><td class="acenter"><?=$racecolor_s?><?=$rtags["$enemy[race]"]?><?=$racecolor_e?></td><? if($era) { ?>
    <td class="acenter"><?=$etags["$enemy[era]"]?></td>
<? } ?><td class="acenter"><?=$enemy[offtotal]?> (<?php if ($enemy[offtotal]) echo round($enemy[offsucc] / $enemy[offtotal] * 100);
    else echo 0;

    ?>%)</td>
    <td class="acenter"><?=$enemy[deftotal]?> (<?php if ($enemy[deftotal]) echo round($enemy[defsucc] / $enemy[deftotal] * 100);
    else echo 0;
    ?>%)</td>
    <td class="acenter"><?=$enemy[kills]?></td></tr>
<?php
} 

function intelMainStats ($user, $race, $era, $esp=false, $other=null){
    global $config, $prefix, $users, $time;
    $land = db_safe_firstval("SELECT SUM(land) FROM $playerdb WHERE turnsused>$config[protection] AND disabled != 2 AND disabled != 3;");
    $count = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE turnsused>$config[protection] AND disabled != 2 AND disabled != 3;");
    if($count == 0)
        $avg = 0;
    else
        $avg = $land/$count;
    $offexp = calcoffexp($users);
    $defexp = calcdefexp($users);
    $experience = floor($offexp*1000)+floor($defexp*1000);

    $spyarray = array();
    $spyarray[] = $user[era];
    $spyarray[] = $user[empire];
    $spyarray[] = $user[num];
    $spyarray[] = $user[turns];
    $spyarray[] = $user[turnsstored];
    $spyarray[] = $user[rank];
    $spyarray[] = $user[peasants];
    $spyarray[] = $user[land];
    $spyarray[] = gamefactor($user[cash]);
    $spyarray[] = gamefactor($user[food]);
    $spyarray[] = gamefactor($user[runes]);
    $spyarray[] = $user[networth];
    $spyarray[] = $experience;
    $spyarray[] = $era[name];
    $spyarray[] = $race[name];
    $spyarray[] = $user[health];
    $spyarray[] = $user[tax];

    foreach($config[troop] as $num => $mktcost) {
        $trooparray[] = gamefactor($user[troop][$num]);
    }
    $trooparray = implode(":", $trooparray);
    $spyarray[] = $trooparray;
    $spyarray[] = gamefactor($user[wizards]);
    if($esp) {
        global $spratio, $spfuncs, $spname, $sptype, $uratio, $eratio, $lratio;
        $enemy = $user;
        $users = $other;
        $erace = loadRace($enemy[race], $enemy[era]);
        $urace = loadRace($users[race], $users[era]);
        getRatios();
        foreach($spratio as $id => $sratio) {
            if($id == 1)
                continue;
            if($sptype[$id] != 'o')
                continue;
            if($spfuncs[$id] == 'missionfight')
                $sratio = 2.2;
            if($uratio > $eratio * $sratio)
            {
                if($spfuncs[$id] == 'missionfight')
                {
                    if ($lratio > $spratio[$id])
                        $spellarray[] = $spname[$id];
                }
                else
                    $spellarray[] = $spname[$id];
                
            }
        }
    }
    $spellarray = @implode(":", $spellarray);
    $spyarray[] = $spellarray;
    $spyinfo = addslashes(implode("|", $spyarray));
    $spydb = $prefix."_intel"; 

    db_safe_query("INSERT INTO $spydb SET num='$users[num]', spyinfo='$spyinfo', spytime='$time'") or die("Error: ".mysqli_error($GLOBALS["db_link"]));
} 

function printMainStats ($user, $race, $era, $esp=false, $other=null){
    global $config, $authstr, $playerdb, $users;
    $land = db_safe_firstval("SELECT SUM(land) FROM $playerdb WHERE turnsused>$config[protection] AND disabled != 2 AND disabled != 3;");
    $count = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE turnsused>$config[protection] AND disabled != 2 AND disabled != 3;");
    if($count == 0)
        $avg = 0;
    else
        $avg = $land/$count;
    $offexp = calcoffexp($users);
    $defexp = calcdefexp($users);
    $experience = floor($offexp*1000)+floor($defexp*1000);
    if($users[land] < $avg)
        $land_color = "cnormal";
    if($users[land] > $avg)
        $land_color = "cgood";
    if($users[rank] < 11)
        $rank_color = "cgood"; 
    else
        $rank_color = "cnormal"; 
    if($users[health] > 74)
        $health_color = "cgood";
    if($users[health] < 51)
        $health_color = "cwarn";
    if($users[health] < 26)
        $health_color = "cbad";

    // titles: Peasant,Barbarian,Chieftain,Warlord,Noble,Prince,Monarch,Emperor,Immortal,Deity
    if($experience > 1500) $title = 'Deity'; 
    if($experience < 1350) $title = 'Immortal'; 
    if($experience < 1200) $title = 'Emperor'; 
    if($experience < 1050) $title = 'Monarch'; 
    if($experience < 900) $title = 'Prince'; 
    if($experience < 750) $title = 'Noble'; 
    if($experience < 600) $title = 'Chieftain'; 
    if($experience < 450) $title = 'Barbarian';
    if($experience < 300) $title = 'Leader';
    if($experience < 150) $title = 'Peasant';
    ?>
    <table style="width:75%">
    <tr class="era<?=$user[era]?>"><th colspan="3"><?=$user[empire]?> <a class=proflink href=?profiles&num=<?=$user[num]?><?=$authstr?>>(#<?=$user[num]?>)</a> - <?=$title?></th></tr>
    <tr><td valign="top" style="width:40%">
    <table class="empstatus" style="width:100%">
    <tr><th>Turns</th><td><?=$user[turns]?> (max <?=$config[maxturns]?>)</td></tr>
    <tr><th>Turns Stored</th><td><?=$user[turnsstored]?> (max <?=$config[maxstoredturns]?>)</td></tr>
    <tr><th>Rank</th><td><span class="<?=$rank_color?>">#<?=$user[rank]?></span></td></tr>
    <tr><th><?=$era[peasantsC]?></th><td><?=commas($user[peasants])?></td></tr>
    <tr><th>Land Acres</th><td><span class="<?=$land_color?>"><?=commas($user[land])?></span></td></tr>
    <tr><th>Gold</th><td><b>$<?=commas(gamefactor($user[cash]))?></b></td></tr>
    <tr><th><?=$era[food]?></th><td><b><?=commas(gamefactor($user[food]))?></b><?=($user[pubmarket_food] ? ' (' . commas(gamefactor($user[pubmarket_food])). ')' : '')?></td></tr>
    <tr><th><?=$era[runes]?></th><td><b><?=commas(gamefactor($user[runes]))?></b><?=($user[pubmarket_runes] ? ' (' . commas(gamefactor($user[pubmarket_runes])). ')' : '')?></td></tr>
    <tr><th>Networth</th><td>$<?=commas($user[networth])?></td></tr>
    <!--<tr><th>Experience</th><td><?=commas($experience)?></td></tr>-->
    </table></td>
    <td style="width:20%"></td>
    <td style="width:40%">
    <table class="empstatus" style="width:100%">
    <tr><th>Era</th><td><?=$era[name]?></td></tr>
    <tr><th>Race</th><td><?=$race[name]?></td></tr>
    <tr><th>Health</th><td><span class="<?=$health_color?>"><?=$user[health]?>%</span></td></tr>
    <tr><th>Tax Rate</th><td><?=$user[tax]?>%</td></tr><?
    foreach($config[troop] as $num => $mktcost) {
        $tstr = '<tr><th>'.$era["troop$num"].'</th><td><b>'.commas(gamefactor($user[troop][$num])) . '</b>';
        if($user[pubmarket][$num])
            $tstr .= ' (' . commas(gamefactor($user[pubmarket][$num])). ')';
        $tstr .= '</td></tr>';
        echo $tstr;
    }
    ?>
    <tr><th><?=$era[wizards]?></th><td><?=commas(gamefactor($user[wizards]))?></td></tr>
    </table>
    <? if($esp) {
        global $spratio, $spfuncs, $spname, $sptype, $uratio, $eratio, $lratio;
        $enemy = $user;
        $users = $other;
        $erace = loadRace($enemy[race], $enemy[era]);
        $urace = loadRace($users[race], $users[era]);
        getRatios();
        echo '<tr><td colspan="3" style="text-align: center">';
        echo $config['missionspy'];
        foreach($spratio as $id => $sratio) {
            if($id == 1)
                continue;
            if($sptype[$id] != 'o')
                continue;
            if($spfuncs[$id] == 'missionfight')
                $sratio = 2.2;
            if($uratio > $eratio * $sratio)
            {
                if($spfuncs[$id] == 'missionfight')
                {
                    if ($lratio > $spratio[$id])
                        echo ', '.$spname[$id];
                }
                else
                    echo ', '.$spname[$id];
                
            }
        }
        echo '</td></tr>';
    } ?>
</td></tr>
<tr class="era<?=$user[era]?>"><th colspan="3">&nbsp;</th></tr>
</table>
<?php
} 


function printMainStats_tpl ($user, $race, $era, $esp=false, $other=null) {
    global $config, $authstr, $playerdb, $users;
    $land = db_safe_firstval("SELECT SUM(land) FROM $playerdb WHERE turnsused>$config[protection] AND disabled != 2 AND disabled != 3;");
    $count = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE turnsused>$config[protection] AND disabled != 2 AND disabled != 3;");

    if($count == 0)
        $avg = 0;
    else
        $avg = $land/$count;

    $offexp = calcoffexp($users);
    $defexp = calcdefexp($users);
    $experience = floor($offexp*1000)+floor($defexp*1000);

    if($users[land] < $avg)
        $land_color = "cnormal";
    if($users[land] > $avg)
        $land_color = "cgood";
    if($users[rank] < 11)
        $rank_color = "cgood";
    else
        $rank_color = "cnormal";
    if($users[health] > 74)
        $health_color = "cgood";
    if($users[health] < 51)
        $health_color = "cwarn";
    if($users[health] < 26)
        $health_color = "cbad";

    $possible_spells = "";
    if($esp) {
        global $spratio, $spfuncs, $spname, $sptype, $uratio, $eratio;
        $enemy = $user;
        $users = $other;
        $erace = loadRace($enemy[race], $enemy[era]);
        $urace = loadRace($users[race], $users[era]);
        getRatios();
        $possible_spells .= $config['missionspy'];
        foreach($spratio as $id => $sratio) {
            if($id == 1)
                continue;
            if($sptype[$id] != 'o')
                continue;
            if($spfuncs[$id] == 'missionfight')
                $sratio = 2.2;
            if($uratio > $eratio * $sratio)
                $possible_spells .= ', '.$spname[$id];
        }
    }

    $usertrps = array();
    foreach($config[troop] as $num => $mktcost)
    $usertrps[] = array('name' => $era["troop$num"],
                        'amt'  => $user[troop][$num]);
    $user['expdisp'] = $experience;
    global $showmainstats;
    $showmainstats = true;
} 

function numNewMessages () {
    global $messagedb, $users;
    return db_safe_firstval("SELECT COUNT(*) FROM $messagedb WHERE dest=$users[num] AND readd=0 AND deleted=0;");
} 

function numTotalMessages() {
    global $messagedb, $users;
    return db_safe_firstval("SELECT COUNT(*) FROM $messagedb WHERE dest=$users[num] AND deleted=0;");
} 

function addNews($code, $args, $overrideDBlock = false, $settime=-1) {
    global $newsdb, $playerdb, $lockdb, $time, $perminutes;

    if($lockdb && !$overrideDBlock)
        return;
    if($settime != -1)
        $time = $settime;

    $id1 = $args[id1];        fixInputNegativeNum($id1);
    $clan1 = $args[clan1];        fixInputNegativeNum($clan1);
    $land1 = $args[land1];        fixInputNegativeNum($land1);
    $cash1 = $args[cash1];        fixInputNegativeNum($cash1);
    $wizards1 = $args[wizards1];    fixInputNegativeNum($wizards1);
    $food1 = $args[food1];        fixInputNegativeNum($food1);
    $runes1 = $args[runes1];    fixInputNegativeNum($runes1);
    $id2 = $args[id2];        fixInputNegativeNum($id2);
    $clan2 = $args[clan2];        fixInputNegativeNum($clan2);
    $land2 = $args[land2];        fixInputNegativeNum($land2);
    $cash2 = $args[cash2];        fixInputNegativeNum($cash2);
    $wizards2 = $args[wizards2];    fixInputNegativeNum($wizards2);
    $food2 = $args[food2];        fixInputNegativeNum($food2);
    $runes2 = $args[runes2];    fixInputNegativeNum($runes2);
    $id3 = $args[id3];        fixInputNegativeNum($id3);
    $clan3 = $args[clan3];        fixInputNegativeNum($clan3);
    $shielded = $args[shielded];    fixInputNegativeNum($shielded);

    $troops1 = explode('|', $args[troops1]);
    $troops2 = explode('|', $args[troops2]);

    $online = db_safe_firstval("SELECT online FROM $playerdb WHERE num=$id1;");
    fixInputNum($online);

    $limit = time() - $perminutes*2*60;

    $new = false;
    $last_q = db_safe_query("SELECT * FROM $newsdb WHERE time>$limit ORDER BY id DESC LIMIT 0,1;");
    if(mysqli_num_rows($last_q) == 0) {
        $new = true;
    } else {
        $old = mysqli_fetch_array($last_q);

        //what must agree: code, id1, clan1, id2, clan2, id3, clan3, shielded, online
        if( $code == $old[code] && $id1 == $old[id1] && $clan1 == $old[clan1] &&
            $id2 == $old[id2] && $clan2 == $old[clan2] && $id3 == $old[id3] &&
            $clan3 == $old[clan3] && $shielded == $old[shielded]) {
            $old[land1] += $land1;
            $old[cash1] += $cash1;
            $old[wizards1] += $wizards1;
            $old[food1] += $food1;
            $old[runes1] += $runes1;
            $old[land2] += $land2;
            $old[wizards2] += $wizards2;
            $st1 = explode('|', $old[troops1]);
            $st2 = explode('|', $old[troops2]);

            foreach($st1 as $key => $val) {
                $troops1[$key] += $st1[$key];
                $troops2[$key] += $st2[$key];
            }
            $old[num]++;

            //saving and resetting old
            $old[troops1] = implode('|', $troops1);
            $old[troops2] = implode('|', $troops2);

            db_safe_query("UPDATE $newsdb SET num=$old[num], land1=$old[land1], cash1=$old[cash1], wizards1=$old[wizards1], food1=$old[food1], 
                    runes1=$old[runes1], land2=$old[land2], wizards2=$old[wizards2], troops1='$old[troops1]', troops2='$old[troops2]' WHERE id=$old[id];");
        } else {
            $new = true;
        }
    }

    if($new) {
        $troops1 = implode('|', $troops1);
        $troops2 = implode('|', $troops2);
        $query = "INSERT INTO $newsdb (time, code, id1, clan1, land1, cash1, troops1, wizards1, food1, runes1, id2, clan2, land2, troops2, wizards2, id3, clan3, shielded, online)
                VALUES ($time, $code, $id1, $clan1, $land1, $cash1, '$troops1', $wizards1, $food1, $runes1, $id2, $clan2, $land2, '$troops2', $wizards2, $id3, $clan3, $shielded, $online);";
        db_safe_query($query);
        if(mysqli_error($GLOBALS["db_link"]))
            echo "Please show this to the administrators:<br><b>$query<br>".mysqli_error($GLOBALS["db_link"])."</b><br>";
    }

    $time = time();
}


function doForumNews() {
    global $config;
    if($config['news_type'] == 'ipb') {
        include("lib/ipb.php");
        return intForumNews();
    } else if($config['news_type'] == 'phpbb') {
        include("lib/phpbb.php");
        return intForumNews();
    } else {
        return array();
    }
}


function doStatusBar($show) {
    require_once("lib/status.php");
    global $starttime, $playerdb, $config, $users, $uera, $tpl, $config;
    if (!auth_user(true)) {
        global $generated;
        $generated = getmicrotime() - $starttime;
        template_display('footer.html');
        return;
    }
    global $newmail, $turnsleft, $food_color, $cash_color, $land_color, $health_color, $main_url, $authstr, $endbar;
    $foodnet = calcFoodPro() - calcFoodCon();
    $netincome = calcIncome() - calcExpenses();
//    $land = db_safe_firstval("SELECT SUM(land) FROM $playerdb WHERE turnsused>$config[turnsused] AND disabled != 2 AND disabled != 3;");
//    $count = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE turnsused>$config[turnsused] AND disabled != 2 AND disabled != 3;");
    $land = db_safe_firstval("SELECT SUM(land) FROM $playerdb WHERE turnsused>$config[protection] AND disabled != 2 AND disabled != 3;");
    $count = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE turnsused>$config[protection] AND disabled != 2 AND disabled != 3;");
    if($count == 0)
        $avg = 0;
    else
        $avg = $land/$count;
        $offexp = calcoffexp($users);
        $defexp = calcdefexp($users);
    $experience = floor($offexp*1000)+floor($defexp*1000);

    if ($users[land] < $avg)
        $land_color = "cnormal";
    if ($users[land] > $avg)
        $land_color = "cgood";
    if ($users[rank] < 11)
        $rank_color = "cgood";
    else
        $rank_color = "cnormal";
    if ($users[health] > 74)
        $health_color = "cgood";
    if ($users[health] < 51)
        $health_color = "cwarn";
    if ($users[health] < 26)
        $health_color = "cbad";

    if($netincome > 0)
        $cash_color = "cgood";
    else if($netincome < 0)
        $cash_color = "cwarn";
    if($users[cash] + ($netincome * 5) < 0)
        $cash_color = "cbad";

    if($foodnet > 0)
        $food_color = "cgood";
    else if($foodnet < 0)
        $food_color = "cwarn";
    if($users[food] + ($foodnet * 5) < 0)
        $food_color = "cbad";

    global $endbar, $newmail, $turnsleft, $generated;
    $endbar = $show;
    if(numNewMessages() > 0)
        $newmail = true;
    else
        $newmail = false;
    $turnsleft = $config[protection]-$users[turnsused]+1;

    if($show) {
        $generated = getmicrotime() - $starttime;
        template_display('footer.html');
    }
}
// EXPERIENCE ALGORITHIM Anton:030313
//Calculates the offencive experience multiplier 
function calcoffexp ($users)
{
    $succpoint = 3;
    $failpoint = 1;
    $expcap = 0.5; //The maxium effect of experience
    $offexp = (($users[offsucc] * $succpoint) +  (($users[offtotal] - $users[offsucc]) * $failpoint)) 
    / (5000);
    //Previously this was devided by $sucesspoint * 1000
    if ($offexp < $expcap)
    {
        return $offexp;
    }
    else
    {
        return $expcap;
    }
}

//Calculates toe defencive experience mutplier
function calcdefexp ($users)
{
    $succpoint = 6;
    $failpoint = 2;
    $expcap = 0.5;
    $defexp = (($users[defsucc] * $succpoint) + (($users[deftotal] - $users[defsucc]) * $failpoint)) 
    / (5000);
    //previously this was diveded by $sucesspoint * 1000
    if ($defexp < $expcap)
    {
        return $defexp;
    }
    else
    {
        return $expcap;
    }
    //

    return $defexp;
}



?>

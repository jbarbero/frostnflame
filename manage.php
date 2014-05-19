<?
include("header.php");
// killUnits(unit,0.10,0.30,3) will destroy 30%-90% of unit
// use multiple rolls to get numbers closer to the middle
function killUnits ($type, $minpc, $maxpc, $rolls, $troopnum = false) {
    global $users;
    $losspct = 0;
    $min = round(1000000 * $minpc);
    $max = round(1000000 * $maxpc);
    for ($i = 0; $i < $rolls; $i++)
        $losspct += mt_rand($min,$max);
    $losspct /= 1000000;
    if($type == 'troop') {
        $loss = round($users[troop][$troopnum] * $losspct);
        $users[troop][$troopnum] -= $loss;
    }
    else {
        $loss = round($users[$type] * $losspct);
        $users[$type] -= $loss;
    }
    return $loss;
}

if($do_notes) {
    $users['notes'] = $upd_notes;
    saveUserData($users, "notes", true);
    echo "Notepad updated!<hr>";
}

if($reset_basehref) {
    $users['basehref'] = $config['sitedir'];
    saveUserData($users, 'basehref');
    TheEnd('Style directory reset!');
}

if($leave_protection) {
    if(! $config['early_exit'])
        TheEnd("Sorry, you can not leave protection early on this server!");

    $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    $users[email] = mysqli_real_escape_string($GLOBALS["db_link"], $users[email]);
    $old_accounts = db_safe_query("SELECT * FROM $playerdb WHERE ((email='$users[email]' OR IP='$REMOTE_ADDR') AND disabled!=2 AND num!=$users[num]) ORDER BY idle DESC;");
    
    $test = mysqli_fetch_array($old_accounts);

    if($test[idle] > (time() - 24 * 60 * 60))
        TheEnd("A previous account with your IP or Email was too recently logged in for you to leave protection.");

    if($users['turnsused'] > $config['protection'])
        TheEnd("You are already out of protection...");
    $users['turnsused'] = $config['protection'] + 1;
    saveUserData($users, 'turnsused');
    TheEnd("You have left protection early, and now cannot return!");
}

if($do_profile) {
    $stuff = 'aim msn profile email';
    $stuffarr = explode(" ", $stuff);

    if(!(eregi("^[_+A-Za-z0-9-]+(\\.[_+A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9-]+)*$",$email,$matches)))
        TheEnd("Invalid email address!");

    foreach($stuffarr as $var) {
        $users[$var] = $$var;
    }
#    $users[profile] = str_replace(' ', chr(26), $users[profile]);
#    $users[profile] = wordwrap($users[profile], 75, "\n", 1);
#    $users[profile] = str_replace(chr(26), ' ', $users[profile]);
#    $users[profile] = preg_replace('([\S]{75,})', '', $users[profile]);
    $users[profile] = preg_replace('([\w]{75,})', '', $users[profile]);
    saveUserData($users, $stuff, true);
}

if (($do_polymorph) && ($yes_polymorph)) {
    if ($users[turns] < $config[initturns])
        TheEnd("You don't have enough turns!");
    if ($users[health] < 75)
        TheEnd("You don't have enough health!");
    if ($users[wizards] < $users[land]*3)
        TheEnd("You don't have enough $uera[wizards]!");
    if ($new_race != $users[race] && !empty($new_race)) {
        $users[health] -= 50;
        $users[turns] -= $config[initturns];
        $display_str = 'Your race has been changed!<br>';
        foreach($config[troop] as $num => $mktcost) {
            $display_str .= commas(killUnits(troop,0.10,0.15,1,$num)).' '.$uera["troop$num"].', ';
        }
        $display_str .= commas(killUnits(peasants,0.15,0.20,1))." $uera[peasants], and ".
                commas(killUnits(wizards,0.10,0.15,1))." $uera[wizards] died in the revolution.<br>\n";

        $buildloss = 0;
        $buildloss += killUnits(homes,0.08,0.27,2);
        $buildloss += killUnits(shops,0.08,0.27,2);
        $buildloss += killUnits(industry,0.08,0.27,2);
        $buildloss += killUnits(barracks,0.08,0.27,2);
        $buildloss += killUnits(labs,0.08,0.27,2);
        $buildloss += killUnits(farms,0.08,0.27,2);
        $buildloss += killUnits(towers,0.08,0.27,2);
        $users[freeland] += $buildloss;
        $size = calcSizeBonus($users[networth]);
        $display_str .= commas($buildloss)." structures, ".
                commas(killUnits(food,0.05*$size,0.15*$size,3))." $uera[food], ".
                commas(killUnits(runes,0.05*$size,0.15*$size,3))." $uera[runes], and $".
                commas(killUnits(cash,0.05*$size,0.15*$size,3))." were lost during the chaos.\n";
        $users[race] = $new_race;
        $urace = loadRace($users[race], $users[era]);
        $users[networth] = getNetworth($users);
        $printmessage = $display_str;
        saveUserData($users,"networth troops wizards homes shops industry barracks labs farms towers freeland food runes cash turns health race");
    }
}
if ($do_changetax) {
    fixInputNum($new_tax);
    if ($new_tax < 5)
        TheEnd("Cannot set your tax that low!");
    if ($new_tax > 65)
        TheEnd("Cannot set your tax that high!");
    $users[tax] = $new_tax;
    saveUserData($users,"tax");
    $printmessage = 'Tax rate updated!';
}
if ($do_changestyle) {
    $new_style = array_search($color_setting, $styles);
    $users[style] = $new_style;
    saveUserData($users,"style");
    header(str_replace('&amp;', '&', "Location: ?manage$authstr"));
    die();
}
if ($do_changeindustry) {
    $total = 0;
    foreach($config[troop] as $num => $mktcost) {
        $ind[$num] = $_POST["ind_troop$num"];
        fixInputNum($ind[$num]);
        $total += $ind[$num];
        $users[ind][$num] = $ind[$num];
    }

    // they are all positive, right? [fixinputnum ;)]
    // also, if any exceed 100, total will exceed 100
    // thus...
    if($total > 100)
        TheEnd("Cannot set training that high!");

    // if we got here okay, we can save
    saveUserData($users,"production");
    $printmessage = 'Training settings updated!';
}

if (($do_setvacation) && ($yes_vacation)) {
    if ($lastweek)
        TheEnd("Vacation is disabled during the last week of the game!");
    $users[vacation] = 1;
    SaveUserData($users,"vacation");
    TheEnd("Vacation setting saved; your account is now locked. Your empire will be frozen in $config[vacationdelay] hours.");
}

if($lastweek)
    $lastweek = true;
if($users['turnsused'] < $config['protection'])
    $protection = true;

$base = '';
$base = str_replace("file:///", "", $users['basehref']);
$base = str_replace("//", "\\", $base);
$base = str_replace("/", "\\", $base);

$troopnames = array();
$uind = array();
$numbers = array();
foreach($config[troop] as $num => $mktcost) {
    $an = $num;
    $troopnames[$an] = $uera["troop$num"];
    $uind[$an] = $users[ind][$num];
    $numbers[$an] = $num;
}

foreach($rtags as $id => $race)
    $racearray[] = array('id' => $id, 'name' => $race);

$stylearray = array();
foreach($stylenames as $i => $name)
    if(empty($adminstyles[$i]) || $users[disabled] == 2)
        $stylearray[] = array('id' => $styles[$i], 'name' => $name);
template_display('manage.html');
TheEnd("");
?>

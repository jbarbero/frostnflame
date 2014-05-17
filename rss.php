<?php
require_once("conf-proc.php");
require_once("funcs.php");

Header("Content-type: application/rss+xml");


//http://us4.php.net/manual/en/function.html-entity-decode.php
//php dot net at c dash ovidiu dot tk
//18-Mar-2005 01:37
//Quick & dirty code that translates numeric entities to UTF-8.

   function replace_num_entity($ord)
   {
       $ord = $ord[1];
       if (preg_match('/^x([0-9a-f]+)$/i', $ord, $match))
       {
           $ord = hexdec($match[1]);
       }
       else
       {
           $ord = intval($ord);
       }
      
       $no_bytes = 0;
       $byte = array();

       if ($ord < 128)
       {
           return chr($ord);
       }
       elseif ($ord < 2048)
       {
           $no_bytes = 2;
       }
       elseif ($ord < 65536)
       {
           $no_bytes = 3;
       }
       elseif ($ord < 1114112)
       {
           $no_bytes = 4;
       }
       else
       {
           return;
       }

       switch($no_bytes)
       {
           case 2:
           {
               $prefix = array(31, 192);
               break;
           }
           case 3:
           {
               $prefix = array(15, 224);
               break;
           }
           case 4:
           {
               $prefix = array(7, 240);
           }
       }

       for ($i = 0; $i < $no_bytes; $i++)
       {
           $byte[$no_bytes - $i - 1] = (($ord & (63 * pow(2, 6 * $i))) / pow(2, 6 * $i)) & 63 | 128;
       }

       $byte[0] = ($byte[0] & $prefix[0]) | $prefix[1];

       $ret = '';
       for ($i = 0; $i < $no_bytes; $i++)
       {
           $ret .= chr($byte[$i]);
       }

       return $ret;
   }


$defaultAuthor = $config['gamename_short'].' :: '.$config['servname'];
$defaultDate = date('Y-m-d');

function formatCorrect($str) {
    $str = preg_replace_callback('/&#([0-9a-fx]+);/mi', 'replace_num_entity', $str);
    return htmlspecialchars($str);
}


function rss_start($title, $link, $desc) {
    $title = formatCorrect($title);
    $desc = formatCorrect($desc);
    
    echo '<?xml version="1.0"?>'; echo "\n";
    echo '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">';
    echo "\n<channel>";
    echo "\n<title>$title</title>";
    echo "\n<link>$link</link>";
    echo "\n<description>$desc</description>\n";
    echo '<language>en-us</language>';
}


function rss_item($title, $link, $desc, $author=false, $date=false) {
    global $defaultAuthor, $defaultDate;

    if(!$author)    
        $author = $defaultAuthor;
    else
        $author = formatCorrect($author);
    
    if(!$date)
        $date = $defaultDate;
    else if(is_string($date));
    else
        $date = date('Y-m-d', $date);

    $title = formatCorrect($title);
    $desc = formatCorrect($desc);

    echo "\n<item>";
    echo "\n<title>$title</title>";
    echo "\n<link>$link</link>";
    echo "\n<description>$desc</description>";
    echo "\n<dc:creator>$author</dc:creator>";
    echo "\n<dc:date>$date</dc:date>\n";
    echo '</item>';
}


function rss_end() {
    echo "\n</channel>\n";
    echo '</rss>';
}


function newsFeed() {
    global $config;

    $news = doForumNews();
    rss_start($config['gamename_full'], $config['home'], 'Forum News Feed');
    foreach($news as $item) {
        rss_item($item[title], $item[link], $item[desc], $item[author], $item[time]);
    }

    rss_end();
}


function top10Feed() {
    global $config, $defaultAuthor, $playerdb;

    rss_start($defaultAuthor, $config['sitedir'].'?top10%26srv%3D'.SERVER, 'Server Top 10');
    rss_item("-----Players-----", $config['sitedir'].'?top10'.'%26srv%3D'.SERVER, "Top 10 Players");

    $top10 = db_safe_query("SELECT rank,empire,online,num FROM $playerdb WHERE disabled != 2 AND disabled != 3 AND land>0 AND vacation<$config[vacationdelay] ORDER BY rank LIMIT 10;");

    while($player = mysqli_fetch_array($top10)) {
        $online = on_disp(ONTXT, $player[online]);
        rss_item($player[rank].' :: '.$online.$player[empire].' (#'.$player[num].')', $config['sitedir'].'?profiles%26num%3D'.$player[num].'%26srv%3D'.SERVER, $player[empire]);
    }

    $totalnet = array();
    $names = array();
    $count = array();
    $allusers = db_safe_query("SELECT clan,networth FROM $playerdb WHERE land>0 AND disabled != 2 AND disabled != 3;");
    while ($users = mysqli_fetch_array($allusers)) {
        if($users[clan] != 0) {
            $clan = loadClan($users[clan]);
            $num = $clan[num];
            $count[$num]++;
            $names[$num] = $clan[name].' ('.$clan[tag].')';
            $totalnet[$num] += $users[networth];
        }
    }

    foreach($count as $id => $ct) {
        if($ct < 3) {
            unset($count[$id]);
            unset($totalnet[$id]);
        }
    }

    if(count($count) == 0) {
        rss_end();
        return;
    }

    rss_item("-----Clans-----", $config['sitedir'].'?clancrier'.'%26srv%3D'.SERVER, "Top 10 Players");

    arsort($totalnet);
    foreach($totalnet as $id => $clan) {
        $name = $names[$id];
        rss_item($name, $config['sitedir'].'?clancrier'.'%26sclan%3D'.$id.'%26srv%3D'.SERVER, "Clan Crier");
    }

    rss_end();
}


$feed = $_GET['feed'];
if(empty($feed))
    $feed = 'news';

$srv = SERVER;

switch($feed) {
    case 'news':    newsFeed();    break;
    case 'top10':    top10Feed();    break;
    default:    newsFeed();    break;
}
?>

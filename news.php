<?php 
if(!$crier)
    require_once("header.php");
require_once("magicfun.php");

global $authstr;

$where = "(code > 201 AND code < 400) ";

$newslimit = 100;
fixInputNum($search_limit);
if($search_limit > 9)
    $newslimit = $search_limit;

if (!empty($search_num)) {
    fixInputNum($search_num);
    if ($search_by == 'Attacker')
        $where .=  " AND id2=$search_num";
    elseif ($search_by == 'Defender')
        $where .=  " AND id1=$search_num";
    else
        $where .=  " AND (id1=$search_num OR id2=$search_num)";
#        $where .=  " AND id1=$search_num OR id2=$search_num";
} else if(!empty($search_clan)) {
    fixInputNum($search_clan);
    if ($search_by == 'Attacker')
        $where .=  " AND clan2=$search_clan";
    elseif ($search_by == 'Defender')
        $where .=  " AND clan1=$search_clan";
    else
        $where .=  " AND (clan1=$search_clan OR clan2=$search_clan)";
#        $where .=  " AND clan1=$search_clan OR clan2=$search_clan";
}

$query = "SELECT * FROM $newsdb WHERE $where ORDER BY id DESC LIMIT 0,$newslimit;";
$news = db_safe_query($query);
$count = @mysqli_num_rows($news);

$newdisp = array();
while ($new = mysqli_fetch_array($news)) {
    $time = $new[time];
    $code = $new[code];
    $id1 = $new[id1];
    $id2 = $new[id2];
    $id3 = $new[id3];
    $name1 = db_safe_firstval("SELECT empire FROM $playerdb WHERE num=$id1;").on_disp(ONHTML, $new[online]).' <a class=proflink href=?profiles&num='.$id1.$authstr.'>(#'.$id1.')</a>';
    $name2 = db_safe_firstval("SELECT empire FROM $playerdb WHERE num=$id2;").' <a class=proflink href=?profiles&num='.$id2.$authstr.'>(#'.$id2.')</a>';
    $name3 = db_safe_firstval("SELECT empire FROM $playerdb WHERE num=$id3;").' <a class=proflink href=?profiles&num='.$id3.$authstr.'>(#'.$id3.')</a>';
    $clan1 = $new[clan1];
    $clan2 = $new[clan2];
    $clan3 = $new[clan3];
    if($clan1 != 0)        $clname1 = db_safe_firstval("SELECT name FROM $clandb WHERE num=$clan1;").' <a class=proflink href=?clancrier&sclan='.
                    $clan1.$authstr.'>('.db_safe_firstval("SELECT tag FROM $clandb WHERE num=$clan1;").')</a>';
    else            $clname1 = "None";
    if($clan2 != 0)        $clname2 = db_safe_firstval("SELECT name FROM $clandb WHERE num=$clan2;").' <a class=proflink href=?clancrier&sclan='.
                    $clan2.$authstr.'>('.db_safe_firstval("SELECT tag FROM $clandb WHERE num=$clan2;").')</a>';
    else            $clname2 = "None";
    if($clan3 != 0)        $clname3 = db_safe_firstval("SELECT name FROM $clandb WHERE num=$clan3;").' <a class=proflink href=?clancrier&sclan='.
                    $clan3.$authstr.'>('.db_safe_firstval("SELECT tag FROM $clandb WHERE num=$clan3;").')</a>';
    else            $clname3 = "None";
    $shielded = $new[shielded];
    $failed = 0;

    $troops1 = explode('|', $new[troops1]);
    $troops2 = explode('|', $new[troops2]);
    foreach($troops1 as $id => $key)
        $troops1[$id] = gamefactor($key);
    foreach($troops2 as $id => $key)
        $troops2[$id] = gamefactor($key);

    $factors = array('cash1', 'cash2', 'food1', 'food2', 'runes1', 'runes2', 'wizards1', 'wizards2');
    foreach($factors as $key)
        $new[$key] = gamefactor($new[$key]);

    $troops1sum = array_sum($troops1);
    $troops2sum = array_sum($troops2);

    if($shielded == -1)
            $failed = 1;
    
    $date = date($dateformat, $time);

    $outcome = "";
    $what_for = "";
    $name = "";

    if(floor($code/100) == 3) {
        $type = $code - 300;
        if($type == 99) {
            $name = "<b>Succesful Kill</b>";
            $outcome = "";
        } else if($type != 0) {
            $name = $atknames[$type];
            $failed = true;
            $shielded = $shielded%10;
            switch($shielded) {
                case 1:
                    $what_for = " (land)";
                    if($new[land1] == 0)    $outcome = "Defense Held";
                    else            $outcome = commas($new[land1])." acres";
                    break;
                case 2:
                    $what_for = " (cash)";
                    if($new[cash1] == 0)    $outcome = "Defense Held";
                    else            $outcome = '$'.commas($new[cash1]);
                    break;
                case 3:
                    $what_for = " (food)";
                    if($new[food1] == 0)    $outcome = "Defense Held";
                    else            $outcome = commas($new[food1])." food";
                    break;
            }
        }
    } else if(round($code/100) == 2) {
        $type = $code - 200;
        $name = "Mission: ".$spname[$spnews[$code]];
        if($type == 11) {
            switch($shielded) {
                case -1:    $outcome = "Foiled";                break;
                case  0:    $outcome = commas($new[land1])." acres";    break;
                case  1:    $outcome = "Defense Held";            break;
            }
        } else {
            switch($shielded) {
                case -1:    $outcome = "Foiled";        break;
                case  0:    $outcome = "Successful";    break;
                case  1:    $outcome = "Shielded";        break;
            }
        }
    }

    $news_disp[] = array(    'date'        => $date,
                'name1'        => $name1,
                'name2'        => $name2,
                'clname1'    => $clname1,
                'clname2'    => $clname2,
                'name'        => $name,
                'whatfor'    => $what_for,
                'outcome'    => $outcome,
                'times'        => $new['num'],
                );
}

$liveclans = array();
$deadclans = array();
$list = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members>0 ORDER BY num;");
while($clan = mysqli_fetch_array($list))
    $liveclans[] = $clan;

$list = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members=-1 ORDER BY num;");
while($clan = mysqli_fetch_array($list))
    $deadclans[] = $clan;

$tpl->assign('crier', $crier);
$tpl->assign('events', $count);
$tpl->assign('news', $news_disp);
$tpl->assign('newslimit', $newslimit);
$tpl->assign('liveclans', $liveclans);
$tpl->assign('deadclans', $deadclans);

$tpl->display('news.html');

if(!$crier)
    TheEnd();
?>

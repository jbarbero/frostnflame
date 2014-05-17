<?php
include("header.php");

$scores1 = array();
$scores2 = array();


$which = $_GET['page'];
switch($_GET['page']) {
    case 'graveyard':    $tpl->assign('listtype', 'Graveyard');
                $query = 'land = 0 AND disabled !=2 AND disabled != 3';
        break;
    case 'shame':        $tpl->assign('listtype', 'Hall of Shame');
                $query = 'disabled = 3';
        break;
    case 'admins':        $tpl->assign('listtype', 'Administrators');
                $query = 'disabled = 2';
        break;
    case 'scores':
    default:
        $which = 'scores';
        $tpl->assign('listtype', 'Scores Listing');
        $query = 'land > 0 AND disabled !=2 AND disabled !=3';
        break;
}

$tpl->assign('link', $which);


$ssort = 'rank ASC';
switch($_GET['sortby']) {
    case 1:        $ssort = 'empire ASC';            break;
    case 2:        $ssort = 'land DESC';            break;
    case 3:        $ssort = 'networth DESC';        break;
    case 4:        $ssort = 'clan ASC';            break;
    case 5:        $ssort = 'race ASC';            break;
    case 6:        $ssort = 'era ASC';            break;
    case 7:        $ssort = 'kills DESC';            break;
    default:    $ssort = 'rank ASC';            break;
}


function printScoreLine (&$scores) {
    global $users, $enemy, $ctags, $rtags, $etags, $racedb, $eradb, $config, $authstr, $urace, $prefix;
    $mclan = loadClan($users[clan]);
    $color = "normal";
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

    $ccolor = "mnormal";
    if (($enemy[clan] == $mclan[ally1]) || ($enemy[clan] == $mclan[ally2]) || ($enemy[clan] == $mclan[ally3]) || ($enemy[clan] == $mclan[ally4]) || ($enemy[clan] == $mclan[ally5])) {
        $ccolor = "mally";
    } else if (($enemy[clan] == $mclan[war1]) || ($enemy[clan] == $mclan[war2]) || ($enemy[clan] == $mclan[war3]) || ($enemy[clan] == $mclan[war4]) || ($enemy[clan] == $mclan[war5])) {
        $ccolor = "mdead";
    }
    if($enemy[clan] != 0 && $users[clan] == $enemy[clan])
        $ccolor = "mally"; 

    $yip = $mclan[war1];
    $leader = "";
    if ($captdet[founder] == $enemy[num]) $leader = "*";

    $online = on_disp(ONHTML, $enemy[online]);

    $racecolor_s = '<a href="?guide2&amp;section=races'.$authstr.'" class="m'.$color.'">';
    $racecolor_e = '</a>';

    $clan = 'None';
    if($enemy[clan])
        $clan = "<a class='$ccolor' href='?clancrier&sclan=$enemy[clan]$authstr'>$leader".$ctags["$enemy[clan]"]."$leader</a>";


    //for top stat icons
    $tland = db_safe_firstval("SELECT topland FROM ".$prefix."_system");
    if ($enemy[num] == $tland)
    $topland = "<img src='img/game_icons/small/land.png' align=absmiddle>";
 
    $toff = db_safe_firstval("SELECT topoff FROM ".$prefix."_system");
    if ($enemy[num] == $toff)
    $topoff = "<img src='img/game_icons/small/offense.png' align=absmiddle>";
 
    $tdef = db_safe_firstval("SELECT topdef FROM ".$prefix."_system");
    if ($enemy[num] == $tdef)
    $topdef = "<img src='img/game_icons/small/defense.png' align=absmiddle>";
 
    $tkills = db_safe_firstval("SELECT topkills FROM ".$prefix."_system");
    if ($enemy[num] == $tkills)
    $topkills = "<img src='img/game_icons/small/kills.png' align=absmiddle>";
 
    $tnet1 = db_safe_firstval("SELECT topnet1 FROM ".$prefix."_system");
    if ($enemy[num] == $tnet1)
    $topnet1 = "<img src='img/game_icons/small/net1.png' align=absmiddle>";
 
    $tnet2 = db_safe_firstval("SELECT topnet2 FROM ".$prefix."_system");
    if ($enemy[num] == $tnet2)
    $topnet2 = "<img src='img/game_icons/small/net2.png' align=absmiddle>";
 
    $tnet3 = db_safe_firstval("SELECT topnet3 FROM ".$prefix."_system");
    if ($enemy[num] == $tnet3)
    $topnet3 = "<img src='img/game_icons/small/net3.png' align=absmiddle>";
        
        
        $offense = $enemy[offtotal];
        if ($enemy[offtotal])
        $offpercent = round($enemy[offsucc] / $enemy[offtotal] * 100);
        
        $defense = $enemy[deftotal];
        if ($enemy[deftotal])
        $defpercent = round($enemy[defsucc] / $enemy[deftotal] * 100);


    $scores[] = array(    mcolor    => "m$color",
                online    => $online,
                empire    => $enemy[empire],
                num    => $enemy[num],
                rank    => $enemy[rank],
                land    => commas($enemy[land]),
                networth=> commas($enemy[networth]),
                clan    => $clan,
                rcs    => $racecolor_s,
                race    => $rtags["$enemy[race]"],
                rce    => $racecolor_e,
                era    => $etags["$enemy[era]"],
                kills    => $enemy[kills],
                topland=> $topland,
                topoff => $topoff,
                topdef => $topdef,
                topkills=> $topkills,
                topnet1=> $topnet1,
                topnet2=> $topnet2,
                topnet3=> $topnet3,
                offense=> $offense,
                offper    => $offpercent,
                defense=> $defense,
                defper    => $defpercent
              );
}



if (!isset($_GET['view']))
    $view = 1;
else
    $view = $_GET['view'];
if ($view <= 0) {
    $view = 1;
} 
if (!isset($restr))
    $restr = "false";

$tpl->assign('servname', $config['servname']);
$tpl->assign('view', $view);
$tpl->assign('restr', $restr);
$tpl->assign('datetime', $datetime);

$total = db_safe_firstval("SELECT COUNT(*) FROM $playerdb;");
$online = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE online=1 OR hide=1;");
$killed = db_safe_firstval("SELECT SUM(kills) FROM $playerdb;");
$dead = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE land=0;");
$disabled = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE disabled=3;");
$active = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE disabled=0 AND land>0");
$abandoned = $dead - $killed;
$abandoned = ($abandoned < 0) ? 0 : $abandoned;

$tpl->assign('total', $total);
$tpl->assign('online', $online);
$tpl->assign('killed', $killed);
$tpl->assign('abandoned', $abandoned);
$tpl->assign('disabled', $disabled);
$tpl->assign('active', $active);
$tpl->assign('empire', $uera['empire']);
$tpl->assign('empireC', $uera['empireC']);

$start = $users[rank] - (15 * $view);
if ($start < 10)
    $start = 10;
$end = 30;
$end *= $view;

if(isset($_GET['show']))
    $show = $_GET['show'];
else
    $show = '';
if ($show == 'all') {
    $start = 10;
    $end = 90000;
} 

if ($restr == "true")
    $clause = "turnsused > $config[protection] AND vacation = 0 AND";
else
    $clause = "";


function getScores($start, $end, $array) {
    global $playerdb, $$array, $enemy, $ssort, $clause, $query;

    $limit = "$start,$end";
    $scores = db_safe_query("SELECT rank,empire,num,land,networth,clan,race,era,online,disabled,turnsused,vacation,kills,offtotal,deftotal,offsucc,defsucc FROM 
        $playerdb WHERE $clause $query ORDER BY $ssort LIMIT $limit;");

    if (@mysqli_num_rows($scores) != 0) {
        while ($enemy = mysqli_fetch_array($scores)) {
            printScoreLine($$array);
        }
    } 
}

getScores(0, 10, 'scores1');
getScores($start, $end, 'scores2');

$tpl->assign('era', $users['era']);
$tpl->assign('scores1', $scores1);
$tpl->assign('scores2', $scores2);
$sc1e = 0; $sc2e = 0;
if(empty($scores1))
    $sc1e = 1;
if(empty($scores2))
    $sc2e = 1;
$tpl->assign('sc1e', $sc1e);
$tpl->assign('sc2e', $sc2e);
$tpl->assign('show', $show);
$tpl->display('scores.html');

TheEnd("");
?>

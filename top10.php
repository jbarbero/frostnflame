<?
if(!defined("PROMISANCE"))
	die(" ");
function printTop10Header ()
{
	global $config, $uera, $authstr;
?>
<tr class="era0">
    <th style="width:5%" class="aright"><a href="?top10&amp;sort=rank<?=$authstr?>">Rank</a></th>
    
  <th style="width:25%"><?=$uera[empireC]?></th>
    <th style="width:10%" class="aright">Land</th>
    <th style="width:15%" class="aright">Networth</th>
    <th style="width:10%">Clan</th>
    <th style="width:10%">Race</th>
    <th style="width:5%">Era</th>
    <th style="width:8%"><a href="?top10&amp;sort=offtotal<?=$authstr?>">O</a></th>
    <th style="width:8%"><a href="?top10&amp;sort=deftotal<?=$authstr?>">D</a></th>
    <th style="width:4%"><a href="?top10&amp;sort=kills<?=$authstr?>">K</a></th></tr>
<?
}

$ctags = loadClanTags();
HTMLbegincompact("Top 100");
$uera = loadEra(1,1);

switch ($sort) {
	case "rank":		$sort = "rank ASC";			break;
	case "offtotal":	$sort = "offtotal DESC, rank ASC";	break;
	case "deftotal":	$sort = "deftotal DESC, rank ASC";	break;
	case "kills":		$sort = "kills DESC, rank ASC";		break;
	default:		$sort = "rank ASC";			break;
}

$killed = @db_safe_firstval("SELECT SUM(kills) FROM $playerdb;");
$deleted = @db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE land=0;") - $killed;
if($deleted < 0)
	$deleted = 0;
$disabled = @db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE disabled=3;");
?>
<b><?=$config[servname]?> Top Player Listing</b><br>
Current Game Time: <?=$datetime?><br><br>
<b><?=db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE online=1;")?></b> of the <b><?=db_safe_firstval("SELECT COUNT(*) FROM $playerdb;")?></b> players in the game are currently online.<br>
<b> 
<?=$killed?>
</b> <?=$uera[empire]?>s have been destroyed, and <b> 
<?=$deleted?>
</b> <?=$uera[empire]?>s have been abandoned.<br>
<b><?=$disabled?></b> accounts have been disabled by Administration.<br><br>
<b>Rankings are updated every <?=$perminutes?> minute<?=plural($perminutes,"s","")?>.</b><br><br>
Color Key: <span class="mprotected">Protected/Vacation</span>, <span class="mdead">Dead</span>, <span class="mdisabled">Disabled</span>, <span class="madmin">Administrator</span><br>
Stats Key: O = Offensive Actions (success%), D = Defenses (success%), K = Number 
of <?=$uera[empire]?>s killed<br>
<?=$uera[empireC]?>s whose ranks are prefixed with a * are currently online.<br><br>
<table width="40%">
<tr><td><center><img src='img/game_icons/scores/net1.png'></center><td><center><img src='img/game_icons/scores/offense.png'></center><td><center><img src='img/game_icons/scores/defense.png'></center><td><center><img src='img/game_icons/scores/land.png'></center><td><center><img src='img/game_icons/scores/kills.png'></center></tr>
<tr><td><center>Top Net</center><td><center>Most Offenses</center><td><center>Most Defenses</center><td><center>Most Land</center><td><center>Most Kills</center></tr>
</table>


<table class="scorestable">
<?
printTop10Header();
$users[num] = 0;	// so we can use printScoreLine() and not worry
$users[clan] = 0;	// about it using the ingame-specific colors
//$top10 = db_safe_query("SELECT rank,empire,num,land,networth,clan,online,disabled,turnsused,vacation,race,era,offsucc,offtotal,defsucc,deftotal,kills FROM $playerdb 
//		 WHERE disabled != 2 AND disabled != 3 AND land>0 AND vacation<$config[vacationdelay] ORDER BY $sort LIMIT 100;");
$top10 = db_safe_query("SELECT rank,empire,num,land,networth,clan,online,disabled,turnsused,vacation,race,era,offsucc,offtotal,defsucc,deftotal,kills FROM $playerdb 
		 WHERE disabled != 2 AND disabled != 3 AND land>0 ORDER BY $sort LIMIT 100;");

while ($enemy = mysql_fetch_array($top10))
	printSearchLine();
echo mysql_error();
printTop10Header();
?>
</table>
<?
HTMLendcompact();
?>

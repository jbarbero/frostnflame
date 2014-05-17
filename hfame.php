<?
function printTop10Header ()
{
    global $config, $uera, $authstr;
?>
<tr class="era0">
    <th style="width:5%" class="aright"><a href="?top10&amp;sort=rank<?=$authstr?>">Rank</a></th>
    
  <th style="width:25%"><?=$uera[empireC]?></th>
    <th style="width:10%" class="aright">Ending Land</th>
    <th style="width:15%" class="aright">Ending Networth</th>
    <th style="width:10%">Race</th>
    <th style="width:8%"><a href="?top10&amp;sort=offtotal<?=$authstr?>">Offensives</a></th>
    <th style="width:8%"><a href="?top10&amp;sort=deftotal<?=$authstr?>">Defenses</a></th>
    <th style="width:4%"><a href="?top10&amp;sort=kills<?=$authstr?>">Kills</a></th></tr>
<?
}

if(!defined("PROMISANCE"))
    die(" ");
$ctags = loadClanTags();
HTMLbegincompact("Hall Of Fame - The Challenge");
$uera = loadEra(1,1);

switch ($sort)
{
    case "rank":        $sort = "rank ASC";            break;
    case "offtotal":    $sort = "offtotal DESC, rank ASC";    break;
    case "deftotal":    $sort = "deftotal DESC, rank ASC";    break;
    case "kills":        $sort = "kills DESC, rank ASC";        break;
    default:        $sort = "rank ASC";            break;
}
?>
<b><?=$config[servname]?> :: Hall Of Fame</b><br>
Current Game Time: <?=$datetime?><br><br>
<table class="scorestable">
<?
printTop10Header();
$ctags = loadClanTags();
$users[num] = 0;    // so we can use printScoreLine() and not worry
$users[clan] = 0;    // about it using the ingame-specific colors

$top10 = db_safe_query("SELECT * FROM $nolimitdb ORDER BY networth DESC LIMIT 0,10;");
$rank = 1;
while ($enemy = mysqli_fetch_array($top10)) {
    $enemy[turnsused] = 1;
    $enemy[rank] = $rank;
    $enemy[offtotal] = $enemy[off];
    $enemy[deftotal] = $enemy[def];
    $rank++;
    printSearchLine(false, false);
}
printTop10Header();
?>
</table>
<?
HTMLendcompact();
?>

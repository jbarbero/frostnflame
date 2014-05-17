<?php
require_once("funcs.php");
if (auth_user(true))
    include("header.php");
else {
    htmlbegincompact("Clan Criers");
    $users[era] = 1;
}

if(!$users[num]) {
    $users[clan] = 0;
    $uera = loadEra(1,1);
    $urace = loadRace(1,1);
    $ctags = loadClanTags();
}

?>
<form method="post" action="?clancrier<?=$authstr?>" name="selectform">
<select name="sclan" size="1" onChange="selectform.submit();">
<option value="0"<?php if ($uclan[$item] == 0) {
    print " selected";
}

?>>None</option>
<?php
$clannumbers = array();
$count = 0;
$list = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members>0;");
echo "count: " .@mysqli_num_rows($list) . " yes";
while ($clan = @mysqli_fetch_array($list)) {
    $clannumbers[$count] = $clan[num];
    $count++;
    
    ?>
    <option value="<?=$clan[num]?>"<?php if ($clan[num] == $uclan[$item]) {
        print " selected";
    }
    
    ?>><?=$clan[tag]?>: <?=$clan[name]?></option>
    <?php
}

?>
</select>
</form>
<?php

if ((isset($sclan)) && ($sclan > 0)) {
    $num = $sclan;
} else {
    echo "Please select a clan from the form above to see their latest news bulletins.";
    $numrows = @mysqli_num_rows($list);
    $rn = rand(0, count($clannumbers)-1);
    
    $num = $clannumbers[$rn];
}

$tclan = loadClan($num);
if ($tclan[criernews] == "") {
    $tclan[criernews] == "No News";
}
// CRIER NEWS
?>
<table style="width:100%" align="center" class="inputtable">
<tr><th width="25%">Clan Contacts</th><th width="75%"><b><?=$tclan[name]?></b></th></tr>
<tr>
<td width="25%" style="text-align: left;">
<?php
$contacts = db_safe_query("SELECT empire, num FROM $playerdb WHERE num = $tclan[founder] OR num = $tclan[asst] OR num = $tclan[fa1] OR num = $tclan[fa2];");
while ($contact = @mysqli_fetch_array($contacts)) {
    $ccontacts["$contact[num]"] = $contact[empire];
}

?>
<nobr><b>Leader:</b>
<?=$ccontacts["$tclan[founder]"]?> <a class=proflink href=?profiles&num=<?=$tclan[founder]?><?=$authstr?>>(#<?=$tclan[founder]?>)</a></nobr><BR>
<?php if ($tclan[asst] > 0) {
    
    ?>
    <nobr><b>Assistant:</b>
    <?=$ccontacts["$tclan[asst]"]?> <a class=proflink href=?profiles&num=<?=$tclan[asst]?><?=$authstr?>>(#<?=$tclan[asst]?>)</a></nobr><BR>
<?php }
if ($tclan[fa1] > 0) {
    
    ?>
    <nobr><b>Primary Diplomat:</b>
    <?=$ccontacts["$tclan[fa1]"]?> <a class=proflink href=?profiles&num=<?=$tclan[fa1]?><?=$authstr?>>(#<?=$tclan[fa1]?>)</a></nobr><BR>
<?php }
if ($tclan[fa2] > 0) {
    
    ?>
    <nobr><b>Secondary Diplomat:</b>
    <?=$ccontacts["$tclan[fa2]"]?> <a class=proflink href=?profiles&num=<?=$tclan[fa2]?><?=$authstr?>>(#<?=$tclan[fa2]?>)</a></nobr>
<?php }

?>
</td>
<td width="75%">
<?=bbcode_parse($tclan[criernews])?>
</td></tr>
</table>
<BR><BR>
<?php
// CLAN MEMBERS
$dbstr = db_safe_query("SELECT rank, empire, num, land, networth, clan, race, era, online, disabled, turnsused, vacation, offsucc, offtotal, defsucc, deftotal, kills FROM $playerdb WHERE clan = $tclan[num] ORDER BY rank ASC;");

if ($numrows =@mysqli_num_rows($dbstr)) {
    
    ?>
    Color Key: <span class="mprotected">Protected/Vacation</span>, <span class="mdead">Dead</span>, <span class="mally">Ally</span>, <span class="mdisabled">Disabled</span>, <span class="madmin">Administrator</span>, <span class="mself">You</span><br>
    Stats Key: O = Offensive Actions(success%), D = Defenses(success%), K = Number of empires killed<br>
    <table class="scorestable">
    <tr>
    <th colspan=10>
    <b><font size="+1">Clan Members</font></b>
    </th>
    </tr>
    <?php
    printSearchHeader($users[era]);
    while ($stuff = @mysqli_fetch_array($dbstr)) {
        global $enemy;
        $enemy = $stuff;
        printSearchLine();
    }
    printSearchHeader($users[era]);
    
    ?>
    </table>
    <?php
    
}
// RECENT NEWS
$search_limit = 20;
$search_clan = $tclan['num'];
$crier = true;
require_once("news.php");

TheEnd("");

?>

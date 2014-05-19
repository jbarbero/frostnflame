<?php
include("header.php");

if ($do_search) {
    $query = "";

    $search_string = preg_replace('/[^a-zA-Z0-9 ]/', '', $search_string);
    fixInputNum($search_num);
    fixInputNum($search_clan);
    if ($search_type == "string")
        $query .= " empire LIKE '%$search_string%'";
    elseif ($search_type == "num")
        if ($search_num)
            $query .= " num=$search_num";
        else
            TheEnd("No ".$uera[empire]." number specified!");
    elseif ($search_type == "clan")
        $query .= " clan=$search_clan";
    elseif ($search_type == "online")
        $query .= " online=1";
    else
        TheEnd("No search type specified!");

    fixInputNum($search_era);
    if ($search_era > 0)
        $query .= " AND era=$search_era";

    fixInputNum($search_race);
    if ($search_race > 0)
        $query .= " and race=$search_race";

    fixInputNum($search_max_nw);
    if ($search_max_nw > 0)
        $query .= " and networth<=$search_max_nw";

    fixInputNum($search_min_nw);
    if ($search_min_nw > 0)
        $query .= " and networth>=$search_min_nw";

    if ($search_dead)
        $query .= " and land!=0";


    $order_by = preg_replace('/[^a-z]/', '', $order_by);
    $valid_orders = array('networth', 'num', 'empire', 'clan', 'rank');
    if(!in_array($order_by, $valid_orders))
        $order_by = 'rank';

    fixInputNum($searchlimit);
    if ($searchlimit == 0)
        $searchlimit = 25;

    $dbstr = db_safe_query("SELECT rank,empire,num,land,networth,clan,race,era,online,disabled,turnsused,vacation,offsucc,offtotal,defsucc,deftotal,kills 
                FROM $playerdb WHERE $query ORDER BY $order_by LIMIT $searchlimit;");


    if ($numrows = @mysqli_num_rows($dbstr)) {

        ?>
Color Key: <span class="mprotected">Protected/Vacation</span>, <span class="mdead">Dead</span>, <span class="mally">Ally</span>, <span class="mdisabled">Disabled</span>, <span class="madmin">Administrator</span>, <span class="mself">You</span><br>
Stats Key: O = Offensive Actions (success%), D = Defenses (success%), K = Number of <?=$uera[empire]?>s killed<br>
<table class="scorestable">
<?php
        printSearchHeader($users[era]);
        while ($enemy = mysqli_fetch_array($dbstr))
        printSearchLine();
        printSearchHeader($users[era]);

        ?>
</table>
<?php
        if ($numrows > $searchlimit)
            print "Search limit reached.<br>\n";
        else print "Found $numrows ".$uera[empire]."s matching your criteria.<br>\n";
    } else print "No empires found.<br>\n";
} 

?>
<form method="post" action="?search<?=$authstr?>">
<table class="inputtable">
<tr><td>
    <table class="inputtable">
    <tr><th class="aleft"><label>In Era:</label></th>
        <td><select name="search_era" size="1">
        <option value="">Any</option>
<?php 
foreach($etags as $id => $era) {

    ?>
        <option value="<?=$id?>"><?=$era?></option>
<?php
} 

?>
        </select></td></tr>
    <tr><th class="aleft"><label>Race:</label></th>
        <td><select name="search_race" size="1">
            <option value="" selected>Any</option>
<?php 
foreach($rtags as $id => $race) {

    ?>
            <option value="<?=$id?>"><?=$race?></option>
<?php
} 

?>
            </select></td></tr>
    <tr><th class="aleft"><label>Maximum Networth:</label><input type="checkbox" name="search_nw_max" <?php if ($search_nw_max) print" checked";

?>)></th>
        <td>$<input type="text" name="search_max_nw" size="9" value="<?php if ($search_max_nw) echo $search_max_nw;
else echo commas(10 * $users[networth]);

?>"></td></tr>
    <tr><th class="aleft"><label>Minimum Networth:</label><input type="checkbox" name="search_nw_min" <?php if ($search_nw_min) print" checked";

?>></th>
        <td>$<input type="text" name="search_min_nw" size="9" value="<?php if ($search_min_nw) echo $search_min_nw;
else echo commas($users[networth] / 10);

?>"></td></tr>
    <tr><th class="aleft">Order by:</th>
        <td><input type="radio" name="order_by" value="rank" checked>Networth</td></tr>
    <tr><th></th>
        <td><input type="radio" name="order_by" value="num"><?=ucfirst($uera[empireC])?> Number</td></tr>
    <tr><th></th>
        <td><input type="radio" name="order_by" value="empire"><?=ucfirst($uera[empireC])?> Name</td></tr>
    <tr><th></th>
        <td><input type="radio" name="order_by" value="clan">Clan</td></tr>
    <tr>
            <th>Exclude dead <?=$uera[empire]?>s:</th>
        <td><input type="checkbox" name="search_dead"></td></tr>
    <tr><th class="aleft"><label>Maximum Results:</label></th>
        <td><input type="text" name="searchlimit" size="4" value="<?php if ($searchlimit) echo $searchlimit;
else echo 25;

?>"></td></tr>
    </table></td>
    <td><table class="inputtable">
        <tr><th class="aleft"><label><input type="radio" name="search_type" value="num">
              <?=ucfirst($uera[empireC])?> Number:</label></th>
            <td><input type="text" name="search_num" size="4"></td></tr>
        <tr><th class="aleft"><label><input type="radio" name="search_type" value="string" checked> String Search:<br>(Only letters allowed.)</label></th>
            <td><input type="text" name="search_string" size="15"></td></tr>
        <tr><th class="aleft"><label><input type="radio" name="search_type" value="clan"> Clan Tag Search:</label></th>
            <td><select name="search_clan" size="1">
                <option value="0">None - Unallied Empires</option>
<?php
$clanlist = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members>0 ORDER BY num;");
while ($clan = mysqli_fetch_array($clanlist)) {

    ?>
                <option value="<?=$clan[num]?>"><?=$clan[tag]?> - <?=$clan[name]?></option>
<?php
} 

?>
            </select></td></tr>
        <tr><th class="aleft"><label><input type="radio" name="search_type" value="online"> Online Search</label></th>
            <td></td></tr>
        </table>
    </td></tr>
<tr><td colspan="2" class="acenter"><input type="submit" name="do_search" value="Search"></td></tr>
</table>
</form>
<?php
TheEnd("");

?>

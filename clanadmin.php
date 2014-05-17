<?php


include("header.php");
require("lib/libclan.php");

if ($users[disabled] != 2)
	TheEnd("You are not an administrator!");
// this function generates the drop down box for ally and war lists
function listopt ($item)
{
	global $clandb, $uclan;

	?>
<select name="<?=$item?>" size="1">
<option value="0"<?php if ($uclan[$item] == 0) print " selected";

	?>>None</option>
<?php $list = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members>0 ORDER BY num DESC;");
	while ($clan = mysqli_fetch_array($list)) {

		?>
<option value="<?=$clan[num]?>"<?php if ($clan[num] == $uclan[$item]) print " selected";

		?>><?=$clan[tag]?>: <?=$clan[name]?></option>
<?php
	} 

	?>
</select>
<?php
} 

        if ($do_createclan)
        {
        $create_founder=$_POST['create_founder'];
        mkclan();
                        
        }

?>
<form method="post" action="?clanadmin<?=$authstr?>">
<div>
Clan: <select name="adminclan" size="1">
<?php
$clanlist = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members>0 ORDER BY num DESC;");
while ($clan = mysqli_fetch_array($clanlist)) {

	?>
<option value="<?=$clan[num]?>"<?php if ($clan[num] == $adminclan) print " selected";

	?>><?=$clan[tag]?>: <?=$clan[name]?></option>

<?php
} 

?>
</select>
<input type="submit" value="Refresh">
</div>
</form>
<hr>
<table class="inputtable">
<form action="?clanadmin<?=$authstr?>" method="post">
<tr><th colspan="2">Create a Clan</th></tr>
<tr><td class="aright">Clan Tag:</td>
    <td><input type="text" name="create_tag" size="8" maxlength="8"></td></tr>
<tr><td class="aright">Password:</td>
    <td><input type="password" name="create_pass" size="8" maxlength="16"></td></tr>
<tr><td class="aright">Founder (User Number, puts user in clan as founder):</td>
    <td><input type="text" name="create_founder" size="8" maxlength="5"></td></tr>
<tr><td class="aright">Clan Name:</td>
    <td><input type="text" name="create_name" size="16" maxlength="32"></td></tr>
<tr><td class="aright">Flag URL:</td>
    <td><input type="text" name="create_flag" size="25"></td></tr>
<tr><td class="aright">Site URL:</td>
    <td><input type="text" name="create_url" size="25"></td></tr>
<tr><td colspan="2" class="acenter"><input type="submit" name="do_createclan" value="Create Clan"><br><br>
<i>Please remember to prefix all URLs with "http://".</i>
</td></tr>
</form>	
<?php
if (!$GLOBALS[adminclan])
	TheEnd("");
$users[clan] = $GLOBALS[adminclan];
$uclan = loadClan($users[clan]);

if ($do_removeempire) {
	$enemy = loadUser($modify_empire);
	$enemy[clan] = 0;
	saveUserData($enemy, "clan");
	addNews(114, array(id1=>$enemy[num], clan1=>$users[clan], id2=>$users[num]));
	$uclan[members]--;
	saveClanData($uclan, "members");
	TheEnd("<b>$enemy[empire] <a class=proflink href=?profiles&num=$enemy[num]$authstr>(#$enemy[num])</a></b> has been removed from $uclan[name].");
} 
if ($do_changepass) {
	$uclan[password] = md5($new_password);
	saveClanData($uclan, "password");
	TheEnd("Clan password changed.");
} 
if ($do_changeflag) {
	$uclan[pic] = $new_flag;
	saveClanData($uclan, "pic");
	TheEnd("Clan flag changed.");
} 
if ($do_changename) {
	if (!$new_name)
		TheEnd("No new name specified!");
	$uclan[name] = $new_name;
	saveClanData($uclan, "name");
	TheEnd("Clan name changed.");
} 
if ($do_changeurl) {
	$uclan[url] = $new_url;
	saveClanData($uclan, "url");
	TheEnd("Clan URL changed.");
} 
if ($do_changemotd) {
	$uclan[motd] = $new_motd;
	$uclan[criernews] = $new_crier;
	saveClanData($uclan, "motd criernews");
	TheEnd("Clan MOTD changed.");
} 
if ($do_makefounder) {
	$newfounder = loadUser($modify_empire);
	$uclan[founder] = $newfounder[num];
	saveClanData($uclan, "founder");
	addNews(115, array(id2=>$newfounder[num], clan1=>$users[clan], id2=>$users[num]));
	TheEnd("<b>$newfounder[empire] <a class=proflink href=?profiles&num=$newfounder[num]$authstr>(#$newfounder[num])</a></b> is now the leader of <b>$uclan[name]</b>.");
} 
if ($do_changerelations) {
	$uclan[ally1] = $ally1;
	$uclan[ally2] = $ally2;
	$uclan[ally3] = $ally3;
	$uclan[war1] = $war1;
	$uclan[war2] = $war2;
	$uclan[war3] = $war3;
	saveClanData($uclan, "ally1 ally2 ally3 war1 war2 war3");
	TheEnd("Clan relations changed.");
} 
/*
if ($do_createclan) {
?> THIS IS A TEST <?PHP
	mkclan()		;
		
} 
*/
if(!empty($_POST['do_createclan'])) {
?> THIS IS A TEST <?PHP
	mkclan()		;
		
} 
?>
<br>
<form method="post" action="?clanadmin<?=$authstr?>">
<div>
<input type="hidden" name="adminclan" value=<?=$adminclan?>>
<h3><?=$uclan[name]?> Relations</h3>
<table class="inputtable">
<tr><th><span class="cgood">Ally</span><br>Cannot attack</th>
    <th><span class="cbad">War</span><br>Infinite attacks</th></tr>
<tr><td><?php listopt(ally1);

?></td>
    <td><?php listopt(war1);

?></td></tr>
<tr><td><?php listopt(ally2);

?></td>
    <td><?php listopt(war2);

?></td></tr>
<tr><td><?php listopt(ally3);

?></td>
    <td><?php listopt(war3);

?></td></tr>
<tr><td colspan="2" class="acenter"><input type="submit" name="do_changerelations" value="Change Relations"></td></tr>
</table><br>
<table class="inputtable">
<caption><b>Empire List</b></caption>
<tr><th>Modify?</th>
    <th>Empire</th></tr>
<?php
$dblist = db_safe_query("SELECT empire,num FROM $playerdb WHERE clan=$uclan[num];");
while ($listclan = mysqli_fetch_array($dblist)) {

	?>
<tr><td class="acenter"><input type="radio" name="modify_empire" value="<?=$listclan[num]?>"<?php if ($listclan[num] == $uclan[founder]) print " CHECKED";

	?>></td>
    <td class="acenter"><?=$listclan[empire]?> <a class=proflink href=?profiles&num=<?=$listclan[num]?><?=$authstr?>>(#<?=$listclan[num]?>)</a></td></tr>
<?php
} 

?>
<tr><th><input type="submit" name="do_makefounder" value="Leader"></th>
    <th><input type="submit" name="do_removeempire" value="Remove"></th></tr>
</table><br>
<table class="inputtable">
<tr><th>Change Password:</th>
    <td class="acenter"><input type="password" name="new_password" size="8"></td>
    <td class="acenter"><input type="submit" name="do_changepass" value="Change Password"></td></tr>
<tr><th>Clan Name:</th>
    <td class="acenter"><input type="text" name="new_name" value="<?=$uclan[name]?>" size="32"></td>
    <td class="acenter"><input type="submit" name="do_changename" value="Change Name"></td></tr>
<tr><th>Flag URL:</th>
    <td class="acenter"><input type="text" name="new_flag" value="<?=$uclan[pic]?>" size="32"></td>
    <td class="acenter"><input type="submit" name="do_changeflag" value="Change Flag"></td></tr>
<tr><th>Site URL:</th>
    <td class="acenter"><input type="text" name="new_url" value="<?=$uclan[url]?>" size="32"></td>
    <td class="acenter"><input type="submit" name="do_changeurl" value="Change URL"></td></tr>
</table><br>
Clan MOTD (Message of the Day, all members see on Main Menu, <b>HTML ALLOWED</b>):<br>
<textarea rows="10" cols="60" name="new_motd"><?=$uclan[motd]?></textarea><br>
Clan Crier News (Viewable by everyone):<br>
<textarea rows="10" cols="60" name="new_crier"><?=$uclan[criernews]?></textarea><br>
<input type="submit" name="do_changemotd" value="Change MOTD">
</div>
</form>
<?php
TheEnd("");

?>

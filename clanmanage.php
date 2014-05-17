<?php
include("header.php");

// this function generates the drop down box for ally and war lists
function listopt($item) {
	global $clandb, $uclan;
?>
<select name="<?=$item?>" size="1">
<option value="0"<?if ($uclan[$item] == 0) print " selected";?>>None</option>
<?
	$list = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members>0 ORDER BY num DESC;");
	while ($clan = mysqli_fetch_array($list))
	{
?>
<option value="<?=$clan['num']?>"<?if ($clan['num'] == $uclan[$item]) print " selected";?>><?=$clan['tag']?>: <?=$clan['name']?></option>
<?
	}
?>
</select>
<?
}

function clanids() {
	global $uclan;
?>
<table class="inputtable">
<tr><th>Change Password:</th>
    <td class="acenter">New password: <input type="password" name="new_password" size="8"><br>
                        Verify password: <input type="password" name="new_password_verify" size="8"></td>
    <td class="acenter"><input type="submit" name="do_changepass" value="Change Password"></td></tr>
<tr><th>Clan Name:</th>
    <td class="acenter"><input type="text" name="new_name" value="<?=$uclan['name']?>" size="32"></td>
    <td class="acenter"><input type="submit" name="do_changename" value="Change Name"></td></tr>
<tr><th>Flag URL:</th>
    <td class="acenter"><input type="text" name="new_flag" value="<?=$uclan['pic']?>" size="32"></td>
    <td class="acenter"><input type="submit" name="do_changeflag" value="Change Flag"></td></tr>
<tr><th>Site URL:</th>
    <td class="acenter"><input type="text" name="new_url" value="<?=$uclan['url']?>" size="32"></td>
    <td class="acenter"><input type="submit" name="do_changeurl" value="Change URL"></td></tr>
</table><br>
<?
}

function motd() {
	global $uclan;
?>
Clan MOTD (Message of the Day, all members see on Main Menu, displayed exactly as seen below, NO HTML):<br>
<textarea rows="10" cols="60" name="new_motd"><?=$uclan['motd']?></textarea><br>
Clan Crier News (Viewable by everyone):<br>
<textarea rows="10" cols="60" name="new_crier"><?=$uclan['criernews']?></textarea><br>
<input type="submit" name="do_changemotd" value="Change MOTD">
<?
}

function playeropts() {
	global $uclan, $playerdb, $users, $authstr, $uera;
?>
<?=$uclan['name']?> currently has <?=$uclan['members'].plural($uclan['members']," member"," members")?>.<br><br>
<table class="inputtable">
<caption><b><?=$uera['empireC']?> Member List</b></caption>
<tr class="inputtable2"><th width="6%">Modify?</th>
    <th width="16%"><?=$uera['empireC']?></th>
    <th width="8%">Clan Rank</th>
    <th width="15%">Networth</th>
    <th width="4%">Rank</th>
    <th width="8%">Shared Forces</th>
    <th width="8%">Treasury Access</th></tr>
<?
	$dblist = db_safe_query("SELECT empire,num,clan_tres,forces,rank,networth FROM $playerdb WHERE clan=$uclan[num];");
	while ($listclan = mysqli_fetch_array($dblist)) {
?>
<tr><td class="acenter"><input type="radio" name="modify_empire" value="<?=$listclan['num']?>"<?if ($listclan['num'] == $uclan['founder']) print " CHECKED";?>></td>
    <td class="acenter"><?=$listclan['empire']?> <a class=proflink href=?profiles&num=<?=$listclan['num']?><?=$authstr?>>(#<?=$listclan['num']?>)</a></td>
    <td class="acenter"><span class=<? if($uclan['founder'] == $listclan['num']) print '"cgood">Leader'; elseif($uclan['asst'] == $listclan['num']) print '"cgood">Co Leader'; elseif($uclan['fa1'] == $listclan['num']) print '"cgood">Diplomat #1'; elseif($uclan['fa2'] == $listclan['num']) print '"cgood">Diplomat #2'; else print '"cgood">Member'; ?></span></td>
    <td class="acenter">$<?=commas($listclan['networth'])?></td>
    <td class="acenter">#<?=$listclan['rank']?></td>
    <td class="acenter"><span class=<?if ($listclan['forces']) print '"cgood">YES'; else print '"cbad">NO';?></span></td>
    <td class="acenter"><span class=<?if ($listclan['clan_tres']==1 || $listclan['num'] == $uclan['founder']) print '"cgood">YES'; else print '"cbad">NO';?></span></td></tr>
<?
	}
?>
</table><table width="100%" border="0"><tr><td><hr></td></tr></table>
<table><?
	if ($users['num'] == $uclan['founder']) {
?>
<tr><td><input type="submit" name="do_makefounder" value="Leader"></td>
<td><input type="submit" name="do_addtres" value="Grant Treasury Access"></td>
<td><input type="submit" name="do_remtres" value="Deny Treasury Access"></td>
<td><input type="submit" name="do_removeempire" value="Remove"></td></tr>
<tr><td><input type="submit" name="do_remasst" value="Remove Assistant"></td>
<td><input type="submit" name="do_makeasst" value="Make Assistant"></td>
<?
	}
?>
    <td><input type="submit" name="do_remfa1" value="Remove Diplomat(1)"></td>
    <td><input type="submit" name="do_makefa1" value="Make Diplomat(1)"></td></tr>
    <tr><td><input type="submit" name="do_remfa2" value="Remove Diplomat(2)"></td>
    <td><input type="submit" name="do_makefa2" value="Make Diplomat(2)"></td></tr>
    
</table><br>
<?
}

function relations() {
?>
<h3><?=$uclan['name']?> Relations</h3>
<table class="inputtable">
<tr><th><span class="cgood">Ally</span><br>Cannot attack</th>
    <th><span class="cbad">War</span><br>Infinite attacks</th></tr>
<tr><td><?listopt(ally1);?></td>
    <td><?listopt(war1);?></td></tr>
<tr><td><?listopt(ally2);?></td>
    <td><?listopt(war2);?></td></tr>
<tr><td><?listopt(ally3);?></td>
    <td><?listopt(war3);?></td></tr>
<tr><td><?listopt(ally4);?></td>
    <td><?listopt(war4);?></td></tr>
<tr><td><?listopt(ally5);?></td>
    <td><?listopt(war5);?></td></tr>
<tr><td colspan="2" class="acenter"><input type="submit" name="do_changerelations" value="Change Relations"></td></tr>
</table><br>
<?
}

if ($users['clan'] == 0)
	TheEnd("You are not in a clan!");

$uclan = loadClan($users['clan']);

if (($uclan['founder'] != $users['num']) && ($uclan['fa1'] != $users['num']) && ($uclan['fa2'] != $users['num']) && ($uclan['asst'] != $users['num']))
	TheEnd("You do not have administrative authority in your clan!");

if ($do_removeempire) {
$enemy = loadUser($modify_empire);
	if ($enemy['clan'] != $uclan['num'])
		TheEnd("That ".$uera['empire']." is not in your clan!");
	if ($enemy['num'] == $uclan['founder'])
		TheEnd("The leader must formally disband the clan.");
	$enemy['clan'] = 0;
	$enemy['clan_tres'] = 0;
    // removing from clan if member has been in clan less that 24 hours
	if($enemy['allytime'] < $time + 86400) {
		$enemy['no_limit_time'] = $time + 86400;  // set 24 hr unlimited attack timer
		$enemy['unlimited'] = 1;  // set unlimited attacks flag
		saveUserData($enemy, "no_limit_time unlimited");
	}
    // Remove from any positions of power.
    $posarray = array('asst','fa1','fa2');
    foreach($posarray as $pos) {
        if ($uclan[$pos] == $enemy['num'])
           	$uclan[$pos] = 0;
    }
	saveUserData($enemy,"clan clan_tres");
	addNews(114, array(id1=>$enemy[num], clan1=>$uclan[num], id2=>$users[num]));
	$uclan['members']--;
	saveClanData($uclan,"members asst fa1 fa2");
	$removedate = date($dateformat, $time);
	//debuglog("Game Server: $prefix - Action: Remove from Clan: $uclan[name] - User Info: $enemy[num] - $enemy[name] - $enemy[empire]");
	TheEnd("You have removed <b>$enemy[empire] <a class=proflink href=?profiles&num=$enemy[num]$authstr>(#$enemy[num])</a></b> from your clan.");
}

if($do_clanopen) {
	if($uclan['open'])
		$uclan['open'] = 0;
	else
		$uclan['open'] = 1;
	saveClanData($uclan, "open", true);
}

if ($do_changepass) {
	if ($new_password == $new_password_verify) 	{
		$uclan['password'] = md5($new_password);
		saveClanData($uclan,"password", true);
		TheEnd("Clan password changed.");
	}
	else	TheEnd("Passwords don't match!");
}
if ($do_changeflag) {
	$uclan[pic] = HTMLEntities($new_flag, ENT_QUOTES);;
	saveClanData($uclan,"pic", true);
	TheEnd("Clan flag changed.");
}
if ($do_changename) {
	if (!$new_name)
		TheEnd("No new name specified!");
	$uclan['name'] = trim(HTMLEntities($new_name));
	saveClanData($uclan,"name", true);
	TheEnd("Clan name changed.");
}
if ($do_changeurl) {
	$uclan['url'] = HTMLEntities($new_url, ENT_QUOTES);
	saveClanData($uclan,"url", true);
	TheEnd("Clan URL changed.");
}
if ($do_changemotd) {
	//include("lib/swear_filter.php");
	$uclan['motd'] = swear_filter($new_motd);
	$uclan['criernews'] = swear_filter($new_crier);
	saveClanData($uclan,"motd criernews", true);
	TheEnd("Clan news changed.");
}
if ($do_makefounder) {
	if ($users['num'] != $uclan['founder'])
		theEnd("Only the clan leader can change the leader.");
	$newfounder = loadUser($modify_empire);
	if ($newfounder['clan'] == $users['clan']) 	{
		$uclan['founder'] = $newfounder['num'];
		saveClanData($uclan,"founder", true);
		addNews(115, array(id1=>$newfounder[num], clan1=>$uclan[num], id2=>$users[num]), true);
		TheEnd("<b>$newfounder[empire] <a class=proflink href=?profiles&num=$newfounder[num]$authstr>(#$newfounder[num])</a></b> is now the leader of <b>$uclan[name]</b>.");
	}
	else	TheEnd("That ".$uera['empire']." is not a member of your clan!");
}
function rempos($pos) {
	global $uclan, $users;
	$oldpos = loadUser($uclan[$pos]);
	if ($oldpos['num']) {
		$uclan[$pos] = 0;
		saveClanData($uclan,"$pos", true);
		addNews(119, array(id1=>$oldpos[num], clan1=>$uclan[num], id2=>$users[num]), true);
		return "<b>$oldpos[empire] <a class=proflink href=?profiles&num=$oldpos[num]$authstr>(#$oldpos[num])</a></b> has been removed from authority for <b>$uclan[name]</b>.";
	}
	else	return "That position is already empty!";
}
function changepos($pos) {
	global $modify_empire, $users, $uclan;
	$newpos = loadUser($modify_empire);
	if (($newpos['num'] == $uclan['fa1']) || ($newpos['num'] == $uclan['fa2']) || ($newpos['num'] == $uclan['asst']))
		TheEnd("That ".$uera['empire']." already has a position of authority.");
	if ($newpos['clan'] == $users['clan']) {
		rempos($pos);
		$uclan[$pos] = $newpos['num'];
		saveClanData($uclan,"$pos", true);
		addNews(118, array(id1=>$newpos[num], clan1=>$uclan[num], id2=>$users[num]), true);
		if ($pos == "asst")
			TheEnd("<b>$newpos[empire] <a class=proflink href=?profiles&num=$newpos[num]$authstr>(#$newpos[num])</a></b> is now the Assistant Leader for <b>$uclan[name]</b>.");
		else
			TheEnd("<b>$newpos[empire] <a class=proflink href=?profiles&num=$newpos[num]$authstr>(#$newpos[num])</a></b> is now a Minister of Foreign Affairs for <b>$uclan[name]</b>.");
	}
	else	TheEnd("That ".$uera['empire']." is not a member of your clan!");
}
if ($do_makeasst)
	changepos(asst);
if ($do_makefa1)
	changepos(fa1);
if ($do_makefa2)
	changepos(fa2);
if ($do_remasst)
	theEnd(rempos(asst));
if ($do_makeasst)
	changepos(asst);
if ($do_remfa1)
	theEnd(rempos(fa1));
if ($do_remfa2)
	theEnd(rempos(fa2));
	
if($do_addtres) {
    $enemy = loadUser($modify_empire);
    if($enemy['num'] == $uclan['founder']) {
        TheEnd("Clan leaders have access to the treasury by default.");	
    } else {
    	$enemy['clan_tres'] = 1;
    	saveUserData($enemy ,"clan_tres");
    	addNews(450, array(id1=>$enemy[num], id2=>$users[num], clan1=>$enemy[clan], clan2=>$users[clan]));
    }
}

if($do_remtres) {
    $enemy = loadUser($modify_empire);
    if($enemy['num'] == $uclan['founder']) {
        TheEnd("Clan leaders must have access to the treasury by default.");	
    } else {
    	$enemy['clan_tres'] = 0;
    	//db_safe_query("UPDATE $playerdb SET clan_tres='0' WHERE num='".$enemy[num]."'") or die("Error: ".mysqli_error($GLOBALS["db_link"]));
    	SaveUserData($enemy ,"clan_tres");
    	addNews(451, array(id1=>$enemy[num], id2=>$users[num], clan1=>$enemy[clan], clan2=>$users[clan]));    	
    }    
}
	
if ($do_changerelations) {
	if($ally1 == $uclan['num'] || $ally2 == $uclan['num'] || $ally3 == $uclan['num'] || $ally4 == $uclan['num'] || $ally5 == $uclan['num'])
		TheEnd("Can't ally yourself to your own clan!");
	if($war1 == $uclan['num'] || $war2 == $uclan['num'] || $war3 == $uclan['num'] || $war4 == $uclan['num'] || $war5 == $uclan['num'])
		TheEnd("Can't set your own clan to war!");

	$uclan['ally1'] = $ally1;
	$uclan['ally2'] = $ally2;
	$uclan['ally3'] = $ally3;
	$uclan['ally4'] = $ally4;
	$uclan['ally5'] = $ally5;
	$uclan['war1'] = $war1;
	$uclan['war2'] = $war2;
	$uclan['war3'] = $war3;
	$uclan['war4'] = $war4;
	$uclan['war5'] = $war5;
	saveClanData($uclan,"ally1 ally2 ally3 ally4 ally5 war1 war2 war3 war4 war5");
	TheEnd("You have changed the relations for your clan.");
}

if ($uclan['url']) {
?><a href="<?=$uclan['url']?>" target="_blank"><?
}
if ($uclan['pic']) {
?><img src="<?=$uclan['pic']?>" style="border:0" alt="<?=$uclan['name']?>'s Home Page"><?
} elseif ($uclan['url']) {
?><?=$uclan['name']?>'s Home Page<?
}
if ($uclan['url']) {
?></a><?
}
?>
<br>
<table style="background-color:#1F1F1F">
<tr><th class="era<?=$users[era]?>">Clan Administration for <i><?=$uclan['tag']?></i></th></tr>
<tr><td class="acenter"><a href="?clanstats&amp;sort_type=avgnet<?=$authstr?>">Top Clans by Average Networth</a></td></tr>
<tr><td class="acenter"><a href="?clanstats&amp;sort_type=members<?=$authstr?>">Top Clans by Membership</a></td></tr>
<tr><td class="acenter"><a href="?clanstats&amp;sort_type=totalnet<?=$authstr?>">Top Clans by Total Networth</a></td></tr>
</table>
<form method="post" action="?clanmanage<?=$authstr?>">
<div>
<?relations();?>
<?if (($uclan['founder'] == $users['num']) || ($uclan['asst'] == $users['num'])) playeropts();?>
<?if (($uclan['founder'] == $users['num']) || ($uclan['asst'] == $users['num'])) clanids();?>
<?motd();?>
</div>
</form>

<form method='post'>
<?
if($uclan['open'])
	echo '<input type="submit" name="do_clanopen" value="Make Clan Closed">';
else
	echo '<input type="submit" name="do_clanopen" value="Make Clan Open">';
?>
</form>

<?
TheEnd("");
?>

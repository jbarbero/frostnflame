<?php
include("header.php");
require("lib/libclan.php");

if ($users['disabled'] == 2)	// are they admin?
	TheEnd("Can not join clans as an admin!");

if ($users['clan']) {
	$uclan = loadClan($users['clan']);
	if ($do_removeself) {
		if ($users['allytime'] > ($time - 3600*48))
			TheEnd("Sorry, we cannot leave our Clan until we've been a part of it for at least 48 hours.");
		if ($uclan['founder'] == $users['num']) {
			$remuser = db_safe_query("SELECT num,clan,forces,allytime FROM $playerdb WHERE clan=$uclan[num] AND num!=$uclan[founder];");
			while ($enemy = mysql_fetch_array($remuser)){
				$enemy['clan'] = $enemy['forces'] = 0;
				saveUserData($enemy,"clan forces");
				addNews(117, array(id1=>$enemy[num], clan1=>$uclan[num], id2=>$uclan[founder]));
			}
			addNews(111, array(id1=>$users[num], clan1=>$uclan[num]));
			$uclan[members] = 0;
			saveClanData($uclan,"members");

			$founder = loadUser($uclan['founder']);
			$founder['clan'] = 0;
			$founder['forces'] = 0;
			$founder['clan_tres'] = 0;
			saveUserData($founder, "clan forces clan_tres");
			TheEnd("All members have been removed from <b>$uclan[name]</b>.  The clan will be deleted shortly.");
			debuglog("Game Server: $prefix - Action: Disband Clan: $uclan[name] - User Info: $users[num] - $users[name] - $users[empire]");
		} else {
			$uclan['members']--;
            // Remove from any positions of power.
            $posarray = array('asst','fa1','fa2');
            foreach($posarray as $pos) {
            if ($uclan[$pos] == $users['num'])
              	$uclan[$pos] = 0;
            }
			$users['clan'] = 0;
			$users['clan_tres'] = 0;
			addNews(113, array(id1=>$uclan[founder], id2=>$users[clan]));
			saveUserData($users,"clan clan_tres");
			saveClanData($uclan,"members asst fa1 fa2");
			debuglog("Game Server: $prefix - Action: Remove from Clan: $uclan[name] - User Info: $users[num] - $users[name] - $users[empire]");
			TheEnd("You have been removed from <b>$uclan[name]</b>.");
		}
	}
	if ($do_useforces){
		$users['forces'] = 11;
		saveUserData($users,"forces");
		print "You are now using your forces to help fellow clan members.<br>\n";
	}
	if ($do_notuseforces){
		$users['forces'] = 10;
		saveUserData($users,"forces");
		print "Your forces will be available for your use in 2 hours.<br>\n";
	}
	if ($uclan['url']){
?><a href="<?=$uclan['url']?>" target="_blank"><?
	}
	if ($uclan['pic']){
?><img src="<?=$uclan['pic']?>" style="border:0" alt="$uclan[name]'s Home Page"><?
	}
	elseif ($uclan['url']){
?><?=$uclan['name']?>'s Home Page<?
	}
	if ($uclan['url']){
?></a><?
	}
?>
<br>
<table>
<tr><th class="era<?=$users['era']?>">Clan Info for <i><?=$uclan['tag']?></i></th></tr>
<tr><td class="acenter"><a href="?clanstats&amp;sort_type=avgnet<?=$authstr?>">Top Clans by Average Networth</a></th></tr>
<tr><td class="acenter"><a href="?clanstats&amp;sort_type=members<?=$authstr?>">Top Clans by Membership</a></th></tr>
<tr><td class="acenter"><a href="?clanstats&amp;sort_type=totalnet<?=$authstr?>">Top Clans by Total Networth</a></th></tr>
</table>
<br>
<form method="post" action="?clan<?=$authstr?>">
<div>
<h3><?=$uclan['name']?> Relations</h3>
<table class="inputtable">
<tr class="inputtable2"><th style="width:50%"><span class="cgood">Ally</span><br>Cannot attack<br>Unlimited aid</th>
    <th style="width:50%"><span class="cbad">War</span><br>Unlimited attacks<br>Cannot send aid</th></tr>
<tr><td class="acenter"><?=$ctags["$uclan[ally1]"]?></td>
    <td class="acenter"><?=$ctags["$uclan[war1]"]?></td></tr>
<tr><td class="acenter"><?=$ctags["$uclan[ally2]"]?></td>
    <td class="acenter"><?=$ctags["$uclan[war2]"]?></td></tr>
<tr><td class="acenter"><?=$ctags["$uclan[ally3]"]?></td>
    <td class="acenter"><?=$ctags["$uclan[war3]"]?></td></tr>
<tr><td class="acenter"><?=$ctags["$uclan[ally4]"]?></td>
    <td class="acenter"><?=$ctags["$uclan[war4]"]?></td></tr>
<tr><td class="acenter"><?=$ctags["$uclan[ally5]"]?></td>
    <td class="acenter"><?=$ctags["$uclan[war5]"]?></td></tr>
</table><br>
<?	if ($users['forces'] > 10){ ?>
<table class="inputtable">
<tr><td colspan="2"><h3><b>Forces Used for Ally Defense</b></h3></td></tr>
<?
	foreach($users['troop'] as $num => $have) {
		echo '<tr><th>'.$uera["troop$num"].'</th>';
		echo '<td class="aright">'.commas(round(gamefactor($have*0.1))).'</td></tr>';
	}
?>
</table>
<input type="submit" name="do_notuseforces" value="Don't Use my Forces for Ally Defense"><br>
<?
	} else {
		if($users['forces'] > 0) { ?>
<table class="inputtable">
<tr><td colspan="2"><h3><b>Forces Used for Ally Defense (for <?=($users['forces'] * 10)?> minutes longer.)</b></h3></td></tr>
<?
	foreach($users['troop'] as $num => $have) {
		echo '<tr><th>'.$uera["troop$num"].'</th>';
		echo '<td class="aright">'.commas(round(gamefactor($have*0.1))).'</td></tr>';
	}
?>
</table> <?
		}
?>
<input type="submit" name="do_useforces" value="Use my Forces for Ally Defense"><br>
<?
	}
?>

<?=$uclan['name']?> currently has <?=$uclan['members']?> members.<br><br>
<table class="inputtable">
<tr><td colspan="5"><center><h3><b><?=$uera['empireC']?> List</b></h3></center></td></tr>
<tr class="inputtable2"><th><?=$uera['empireC']?></th>
    <th>Networth</th>
    <th>Rank</th>
    <th>Sharing</th>
    <th>Treasury Access</th>
    <th>Time in Clan</th></tr>
<?
	$list = db_safe_query("SELECT empire,num,forces,rank,networth,allytime,clan_tres FROM $playerdb WHERE clan=$uclan[num];");
	while ($listclan = mysql_fetch_array($list)) {
                $hours = ($time - $listclan['allytime']) / 3600;
		$allytime = "";
                if ($hours > 24) {
                        $days = floor($hours / 24);
                        $allytime .= $days . " days, ";
                        $hours -= $days * 24;
                }
		$allytime .= round($hours, 0) . " hours";
?>
<tr><td class="acenter"><?=$listclan['empire']?> <a class=proflink href=?profiles&num=<?=$listclan['num']?><?=$authstr?>>(#<?=$listclan['num']?>)</a></td>
    <td class="aright">$<?=commas($listclan['networth'])?></td>
    <td class="aright">#<?=$listclan['rank']?></td>
    <td class="acenter"><span class=<?if ($listclan['forces']) print '"cgood">YES'; else print '"cbad">NO';?></span></td>
    <td class="acenter"><span class=<?if ($listclan['clan_tres']) print '"cgood">YES'; else print '"cbad">NO';?></span></td>
    <td class="aright"><?=$allytime?></td></tr>
<?
	}
?>
</table>
<input type="submit" name="do_removeself" value="<?if ($users['num'] == $uclan['founder']) print "Disband"; else print "Leave";?> Clan">

</div>
</form>
<?
} else {
	if ($do_createclan)	{ 
 	   $create_founder = $users['num'];
	   mkclan();
	} 
?>
<table class="inputtable">
<form action="?clan<?=$authstr?>" method="post">
<tr><th colspan="2">Create a Clan</th></tr>
<tr><td class="aright">Clan Tag:</td>
    <td><input type="text" name="create_tag" size="8" maxlength="8"></td></tr>
<tr><td class="aright">Password:</td>
    <td><input type="password" name="create_pass" size="8" maxlength="16"></td></tr>
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

<tr><td><br></td></tr>

<form action="?clanjoin<?=$authstr?>" method="post">
<tr><th colspan="2">Resurrect Dead Clan</th></tr>
<tr><td class="aright">Clan:</td>
<?
$clans = db_safe_query("SELECT num,tag,name FROM $clandb WHERE members<0;");
if(@mysql_num_rows($clans) == 0)
        echo '<td><b>No Dead Clans (yet)</b></td></tr>';
else {
        echo '<td><select name="join_num" size="1"><option value="0">Select</option>';
      
        while ($clan = mysql_fetch_array($clans)) {
                echo "<option value='$clan[num]'>$clan[tag] - $clan[name]</option>";
	}
        echo '</select></td></tr>';
}   
?>
<tr><td class="aright">Password:</td>
    <td><input type="password" name="join_pass" size="8"></td></tr>
<tr><td colspan="2" class="acenter"><input type="submit" name="do_joinclan" value="Re-Form Clan"></td></tr>

</form>
</table>
<br>
<a href="?clanstats&amp;sort_type=avgnet<?=$authstr?>">Top Clans by Average Networth</a><br>
<a href="?clanstats&amp;sort_type=totalnet<?=$authstr?>">Top Clans by Total Networth</a><br>
<a href="?clanstats&amp;sort_type=members<?=$authstr?>">Top Clans by Membership</a><br>
<?
}
TheEnd("");
?>

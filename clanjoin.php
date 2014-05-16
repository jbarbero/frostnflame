<?
include("header.php");

if ($users[disabled] == 2)	// are they admin?
	TheEnd("Cannot join clans as an admin!");

if ($users[clan])
	TheEnd("You are already in a clan!");

$memlimit = ceil(5 + (db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE land>0 AND disabled!=2 AND disabled!=3;")/20));

if ($do_joinopenclan) {
	if($signup_clan != 0) {
		$sclan = loadClan($signup_clan);
		if($sclan[open] == 0)
			TheEnd("That clan is closed!");
		if ($sclan[members] >= $memlimit)
			TheEnd("That clan is currently full. When more players join the game or clan members leave the clan, you may join.");

		$users[clan] = $signup_clan;
		$users[forces] = 0;
		$users[allytime] = $time;
		$sclan[members]++;
		saveClanData($sclan, "members");
		saveUserData($users, "clan forces allytime");
		addNews(112, array(id1=>$sclan[founder], clan1=>$sclan[num], id2=>$users[num]));
		TheEnd("You are now a member of $sclan[name]!");
        }
}
if ($do_joinclan) {
	$uclan = loadClan($join_num);
	$password = md5($join_pass);
	if ($password == $uclan[password]) {
		if ($uclan[members] >= $memlimit)
			TheEnd("That clan is currently full. When more players join the game or clan members leave the clan, you may join.");
		$users[clan] = $uclan[num];
		$users[forces] = 0;
		$users[allytime] = $time;
		saveUserData($users,"clan forces allytime");
		if($uclan[members] == -1) {
			$uclan[members]++;
			$uclan[founder] = $users[num];
		}
		$uclan[members]++;
		saveClanData($uclan,"members founder");
		addNews(112, array(id1=>$uclan[founder], clan1=>$uclan[num], id2=>$users[num]));
		$joined_date = date($dateformat, $users[allytime]);
		debuglog("Game Server: $prefix - Action: Join Clan: $uclan[name] - User Info: $users[num] - $users[name] - $users[empire]");
		TheEnd("You are now a member of $uclan[name]!");
	} else {
		TheEnd("Incorrect password!");
	}
}


$ccs = array();
$clanlist = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members>0 ORDER BY num;");
$tpl->assign('numcs', mysql_num_rows($clanlist));
while ($clan = mysql_fetch_array($clanlist))
	$ccs[] = $clan;
$tpl->assign('ccs', $ccs);

$ocs = array();
$clanlist = db_safe_query("SELECT num,name,tag FROM $clandb WHERE open=1 AND members>0 ORDER BY num;");
$tpl->assign('numocs', mysql_num_rows($clanlist));
while ($clan = mysql_fetch_array($clanlist))
	$ocs[] = $clan;
$tpl->assign('ocs', $ocs);


$tpl->display('clanjoin.html');
TheEnd();
?>

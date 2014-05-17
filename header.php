<?
if(!defined("PROMISANCE")) 
	die(" ");
require_once("funcs.php");
require_once("lib/status.php");

randomize();

$basehref = $config[sitedir];
$root = 0;
$suid = 0;
$admin = false;
auth_user();

$tpl->assign('authcode', $authcode);
$tpl->assign('authstr', $authstr);
$tpl->assign('sitedir', $config['sitedir']);
$tpl->assign('unum', $users['num']);
$tpl->assign('root', $root);

$tpl->assign('gamename', $gamename);
$tpl->assign('gamename_full', $gamename_full);

global $starttime;
$starttime = getmicrotime();
Header("Pragma: no-cache");
Header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

if ($admin) {
	if (isset($_POST['do_updatenet'])) {
		$userlist = db_safe_query("SELECT num FROM $playerdb;");
		while ($user = mysqli_fetch_array($userlist)) {
			$users = loadUser($user[num]);
			saveUserData($users, "pubmarket pubmarket_food pubmarket_runes");
			$users = loadUser($user[num]);
			db_safe_query("UPDATE $playerdb SET networth=".getNetworth($users)." WHERE num=$users[num];");
		}
	}
	if (isset($_POST['do_updateranks'])) {
		$i = 1;
		$userlist = db_safe_query("SELECT num FROM $playerdb WHERE disabled != 2 AND disabled != 3 AND land > 0 ORDER BY networth DESC;");
		while ($user = mysqli_fetch_array($userlist))
			db_safe_query("UPDATE $playerdb SET rank=".$i++." WHERE num=$user[num];");
	}

global $playerdb, $prefix;
// Retrieve all the data from the table and store the record of the table into $topland
$topland = db_safe_firstval("SELECT num,land FROM $playerdb WHERE land > 0 AND disabled!=2 AND disabled!=3 ORDER BY land DESC LIMIT 1");
db_safe_query("UPDATE ".$prefix."_system SET topland=".$topland); 

$topoffense = db_safe_firstval("SELECT num FROM $playerdb WHERE land > 0 AND offsucc > 0 AND disabled!=2 AND disabled!=3 ORDER BY offsucc DESC LIMIT 1");
db_safe_query("UPDATE ".$prefix."_system SET topoff=".$topoffense); 

$topdefense = db_safe_firstval("SELECT num FROM $playerdb WHERE land > 0 AND defsucc > 0 AND disabled!=2 AND disabled!=3 ORDER BY defsucc DESC LIMIT 1"); 
db_safe_query("UPDATE ".$prefix."_system SET topdef=".$topdefense);

// Retrieve all the data from the table and store the record of the table into $topland
$topkills = db_safe_firstval("SELECT num FROM $playerdb WHERE land > 0 AND kills > 0 AND disabled!=2 AND disabled!=3 ORDER BY kills DESC LIMIT 1");
db_safe_query("UPDATE ".$prefix."_system SET topkills=".$topkills);

$topnet = db_safe_query("SELECT num FROM $playerdb WHERE land > 0 AND disabled != 2 AND disabled != 3 ORDER BY networth DESC LIMIT 3") or die(mysqli_error($db_link));
$z = 1;
while($row = mysql_fetch_assoc($topnet)) {
db_safe_query("UPDATE ".$prefix."_system SET topnet".$z."=".$row['num']);
$z++;
}

	if (isset($_POST['do_setuser'])) {
		if($whichuser == "")
			$su = 1;
		else
			$su = $whichuser;

		loadSuid($su);
		$superuser = loadUser($root);
		makeAuthCode($root, $superuser[password], $su, SERVER, $usecookie, $superuser[rsalt]);
	}
	$admin = 1;
	$tpl->assign('adminpanel', 'true');
}

if($users['basehref'] != '')
	$basehref = $users['basehref'];
$tpl->assign('basehref', $basehref);
$tpl->assign('stylename', getstyle());
set_incl_path(getTplDir());

$cnd = '';
if($users['condense'])
	$cnd = ' checked';

$tpl->assign('condense', $cnd);

$tpl->assign('main', $config['main']); 
//$tpl->assign('action', $action);

$tpl->assign('uera', $uera);
$tpl->assign('troops', $config[troop]);
$ctags = loadClanTags();

$tpl->assign('servname', $config['servname']);
$tpl->assign('empire', $users[empire]);
$tpl->assign('num', $users[num]);
$tpl->assign('news', $config['news']);


$online_limit = $time - $config['online_warn'] * 60;
if($users[newstime] > $online_limit)
	$online_limit = $users[newstime];
$onlid2 = db_safe_firstval("SELECT id2 FROM $newsdb WHERE ((code>201 AND code<300) OR (code>300 AND code<400)) AND time>$online_limit AND id1=$users[num] ORDER by time DESC LIMIT 0,1;");
if($onlid2) {
	$onlined = true;
	$name2 = db_safe_firstval("SELECT empire FROM $playerdb WHERE num=$onlid2;").' <a class=proflink href=?profiles&num='.$onlid2.$authstr.'>(#'.$onlid2.')</a>';
	$tpl->assign('onl_e', $name2);
} else {
	$onlined = false;
}

$tpl->assign('onlined', $onlined);

doStatusBar(false);
include("menus.php");

if (($action != "delete")) {
	switch ($users[disabled]) {
	case 0:	if ($users[land] == 0) {
			if ($action == "messages" || $action == "scores" || $action == "sentmail" || $action == "news")
				break;
			$tpl->display('header.html');
			$users[idle] = $time;
			$users[disabled] = 1;
			if (!$suid)
				saveUserData($users,"idle disabled");
			echo 	'You arrive at your '.$uera[empire].', only to find it is in ruins.<br>' .
				'A messenger staggers toward you and tells you what happened...<br><br>';
			printNews($users);
			echo "<a href='".$config['main']."?delete$authstr'>Delete Account</a>";
			TheEnd("");
		}
		break;
	case 1:	if ($users[land] == 0) {
			if ($action == "messages" || $action == "scores" || $action == "sentmail" || $action == "news")
				break;
			$tpl->display('header.html');
			echo 	'Your '.$uera[empire].' has been destroyed.<br>' .
				'There is nothing more to do here except recall the events that led to your destruction...<br><br>';
			printNews($users);
			echo "<a href='".$config['main']."?delete$authstr'>Delete Account</a>";
			TheEnd("");
		}
		break;
	case 2:	break;
	case 3:	$tpl->display('header.html');
		if ($users[ismulti])
			print "This account has been disabled due to usage of multiple accounts.<br>\n";
		else	print "This account has been disabled due to cheating.<br>\n";
		TheEnd("Please contact $config[adminemail] to explain your actions and possibly regain control of your account.");
		break;
	case 4:	$tpl->display('header.html');
		TheEnd("Your account has been marked for deletion and will be erased shortly. Thanks for playing!");
		break;
	}
	if($root == $suid) {
		if($users[hide])
			$users[online] = 0;
		else
			$users[online] = 1;
		$users[IP] = realip();
		$users[idle] = $time;
		saveUserData($users,"online IP idle", true);
	}
}

if(in_array($action, $config['disabled_pages'])) {
	$tpl->display('header.html');
	TheEnd("Sorry, that page is not accessible on this server.");
}

if($admin && in_array($action, $config['admin_restr'])) {
	$tpl->display('header.html');
	TheEnd("Sorry, Administrators may not access that page on this server.");
}

if($users[turnsused] < $config[protection] && in_array($action, $config['prot_restr'])) {
	$tpl->display('header.html');
	TheEnd("Sorry, you are not allowed to access that page while you are under protection on this server.");
}


if (is_on_vacation($users)) {
	$msg = "This account is in vacation mode and cannot be played for another ". vac_hours_left($users) . " hours.";

	if(!in_array($GAME_ACTION, $config['vacation_pages'])) {
		$tpl->display('header.html');
		TheEnd($msg);
	} else {
		$tpl->display('header.html');
		echo "$msg<br><br>";
	}
} else {
	$users[vacation] = 0;
	$users[idle] = $time;
	saveUserData($users, "vacation idle");
	$tpl->display('header.html');
}

if($users[passchanged] == 0) {
	include("changepass.php");
	ThEnd("");
}

?>

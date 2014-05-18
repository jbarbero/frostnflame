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

    include("lib/ranks.php");

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
    $adminpanel = true;
}

if($users['basehref'] != '')
    $basehref = $users['basehref'];
$stylename = getstyle();
set_incl_path(getTplDir());

$cnd = '';
if($users['condense'])
    $cnd = ' checked';

$condense = $cnd;

$main = $config['main'];
$troops = $config['troop'];
$ctags = loadClanTags();

$servname = $config['servname'];
$empire = $users['empire'];
$num = $users['num'];
$news = $config['news'];


$online_limit = $time - $config['online_warn'] * 60;
if($users[newstime] > $online_limit)
    $online_limit = $users[newstime];
$onlid2 = db_safe_firstval("SELECT id2 FROM $newsdb WHERE ((code>201 AND code<300) OR (code>300 AND code<400)) AND time>$online_limit AND id1=$users[num] ORDER by time DESC LIMIT 0,1;");
if($onlid2) {
    $onlined = true;
    $name2 = db_safe_firstval("SELECT empire FROM $playerdb WHERE num=$onlid2;").' <a class=proflink href=?profiles&num='.$onlid2.$authstr.'>(#'.$onlid2.')</a>';
    $onl_e = $name2;
} else {
    $onlined = false;
}


doStatusBar(false);
include("menus.php");

if (($action != "delete")) {
    switch ($users[disabled]) {
    case 0:    if ($users[land] == 0) {
            if ($action == "messages" || $action == "scores" || $action == "sentmail" || $action == "news")
                break;
            template_display('header.html');
            $users[idle] = $time;
            $users[disabled] = 1;
            if (!$suid)
                saveUserData($users,"idle disabled");
            echo     'You arrive at your '.$uera[empire].', only to find it is in ruins.<br>' .
                'A messenger staggers toward you and tells you what happened...<br><br>';
            printNews($users);
            echo "<a href='".$config['main']."?delete$authstr'>Delete Account</a>";
            TheEnd("");
        }
        break;
    case 1:    if ($users[land] == 0) {
            if ($action == "messages" || $action == "scores" || $action == "sentmail" || $action == "news")
                break;
            template_display('header.html');
            echo     'Your '.$uera[empire].' has been destroyed.<br>' .
                'There is nothing more to do here except recall the events that led to your destruction...<br><br>';
            printNews($users);
            echo "<a href='".$config['main']."?delete$authstr'>Delete Account</a>";
            TheEnd("");
        }
        break;
    case 2:    break;
    case 3:    template_display('header.html');
        if ($users[ismulti])
            print "This account has been disabled due to usage of multiple accounts.<br>\n";
        else    print "This account has been disabled due to cheating.<br>\n";
        TheEnd("Please contact $config[adminemail] to explain your actions and possibly regain control of your account.");
        break;
    case 4:    template_display('header.html');
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
    template_display('header.html');
    TheEnd("Sorry, that page is not accessible on this server.");
}

if($admin && in_array($action, $config['admin_restr'])) {
    template_display('header.html');
    TheEnd("Sorry, Administrators may not access that page on this server.");
}

if($users[turnsused] < $config[protection] && in_array($action, $config['prot_restr'])) {
    template_display('header.html');
    TheEnd("Sorry, you are not allowed to access that page while you are under protection on this server.");
}


if (is_on_vacation($users)) {
    $msg = "This account is in vacation mode and cannot be played for another ". vac_hours_left($users) . " hours.";

    if(!in_array($GAME_ACTION, $config['vacation_pages'])) {
        template_display('header.html');
        TheEnd($msg);
    } else {
        template_display('header.html');
        echo "$msg<br><br>";
    }
} else {
    $users[vacation] = 0;
    $users[idle] = $time;
    saveUserData($users, "vacation idle");
    template_display('header.html');
}

if($users[passchanged] == 0) {
    include("changepass.php");
    ThEnd("");
}

?>

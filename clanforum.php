<?php
include("header.php");

if ($users[clan] == 0)
    TheEnd("You are not in a clan!");

$uclan = loadClan($users[clan]);


function set_var($var, $value)
{
    global $_GET, $_POST;
    $_GET[$var] = $value;
    $_POST[$var] = $value;
    global $$var;
    $$var = $value;
}

$cookiename=$prefix.'_forum';

$newtime = $time + 200000;
$_COOKIE[$cookiename] = $users[igname].'|'.$users[password].'|'.$newtime;

$clForums = array();
$roForums = array();
$poForums = array();
$userRanks = array();
$regUsrForums = array();
$mods = array($uclan[founder], $uclan[asst], $uclan[fa1], $uclan[fa2]);

$DB='mysql';

$DBhost=$dbhost;
$DBname=$dbname;
$DBusr=$dbuser;
$DBpwd=$dbpass;

$Tf=$prefix.'_forums';
$Tp=$prefix.'_posts';
$Tt=$prefix.'_topics';
$Tu=$prefix.'_users';
$Ts=$prefix.'_send_mails';
$Tb=$prefix.'_banned';
$Tpq=$prefix.'_poll_questions';
$Tplog=$prefix.'_poll_log';
$Tpd=$prefix.'_poll_data';

$admin_usr=$dbuser;
$admin_pwd=$dbpass;
$admin_email='spam@spam.com';

$bb_admin='bb_admin.php';

set_var('forum', $users[clan]);
if ($_SERVER['REQUEST_METHOD'] == 'GET' && empty($_GET['action']) && empty($_POST['action']))
    set_var('action', 'vtopic');

$indexphp = "?clanforum" . htmlspecialchars_decode($authstr) . "&";

include(MINIBB_PATH."minibb.php");

TheEnd("");
?>

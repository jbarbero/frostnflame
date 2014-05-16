<?php
include("header.php");

if ($users[clan] == 0)
	TheEnd("You are not in a clan!");

$tpl_prom = $tpl;

function set_var($var, $value)
{
	global $_GET, $_POST, $HTTP_GET_VARS, $HTTP_POST_VARS;
	$_GET[$var] = $value;
	$_POST[$var] = $value;
	$HTTP_GET_VARS[$var] = $value;
	$HTTP_POST_VARS[$var] = $value;
	global $$var;
	$$var = $value;
} 

set_var('forum', $users[clan]);
if (empty($HTTP_GET_VARS['action']) && empty($HTTP_POST_VARS[action]))
	set_var('action', 'vtopic');

$cookiename=$prefix.'_forum';

$newtime = $time + 200000;
$_COOKIE[$cookiename] = $users[igname].'|'.$users[password].'|'.$newtime;

$indexphp = str_replace("&amp;", "&", "?clanforum$authstr&");

$clForums = array();
$roForums = array();
$poForums = array();
$userRanks = array();
$regUsrForums = array();

$uclan = loadClan($users[clan]);

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

include("minibb/minibb.php");

$tpl = $tpl_prom;

TheEnd("");

?>

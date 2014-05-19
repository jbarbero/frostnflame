<?php
include("header.php");
?>

<br>
<table><tr>
<td class="acenter"><a href="?motd<?=$authstr?>">Server News</a></td>

</tr></table>
<br>
<?php

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
$mods = array();

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

set_var('forum', -1);
if ($_SERVER['REQUEST_METHOD'] == 'GET' && empty($_GET['action']) && empty($_POST['action']))
    set_var('action', 'vtopic');

$indexphp = "?serverforum" . htmlspecialchars_decode($authstr) . "&";

include(MINIBB_PATH."minibb.php");

TheEnd("");
?>

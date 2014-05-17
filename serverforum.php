<?
include("header.php");
?>

<br>
<table><tr>
<td class="acenter"><a href="?motd<?=$authstr?>">Server News</a></td>

</tr></table>
<br>
<?

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

set_var('forum', -1);
if (empty($HTTP_GET_VARS['action']) && empty($HTTP_POST_VARS[action]))
    set_var('action', 'vtopic');

$cookiename=$prefix.'_forum';

$newtime = $time + 200000;
$_COOKIE[$cookiename] = $users[igname].'|'.$users[password].'|'.$newtime;

$indexphp = str_replace("&amp;", "&", "?serverforum$authstr&");

$clForums=array();
$roForums=array();
$poForums=array();
$userRanks=array();
$regUsrForums=array();
$mods=array();

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

include("minibb/minibb.php");

$tpl = $tpl_prom;

TheEnd("");
?>

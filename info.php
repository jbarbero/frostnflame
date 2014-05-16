<?php
require_once("funcs.php");
if (auth_user(true))
        include("header.php");
else
        htmlbegincompact("Game Info");

$active = "land>0 AND disabled<2";
$numplayers = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE $active;");
$totalnet = db_safe_firstval("SELECT SUM(networth) FROM $playerdb WHERE $active;");
if($totalnet != 0)
	$avgnet = round($totalnet / $numplayers);
else
	$avgnet = 0;

//$endtime = strtotime(preg_replace('/[^a-zA-Z0-9 \t]/', '', $config['roundend']));
$endtime = strtotime($config['roundend']);

if($endtime == -1)
	$enddisp = $config['roundend'];
else
	$enddisp = date($dateformat, $endtime);

$tpl->assign('etime', $endtime);
$tpl->assign('roundend', $enddisp);
$tpl->assign('config', $config);
$tpl->assign('numplayers', $numplayers);
$tpl->assign('avgnet', commas($avgnet));
$tpl->assign('lastweek', $lastweek);

$tpl->display('info.html');
TheEnd("");
?>

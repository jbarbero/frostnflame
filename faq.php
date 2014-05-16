<?php

if (auth_user(true))
	include("header.php");
else
	htmlbegincompact("Medieval Empires: Game FAQ");
//require_once("funcs.php");

randomize();

$basehref = $config['sitedir'];
//auth_user();

//$tpl->assign('authstr', $authstr);
$tpl->assign('sitedir', $config['sitedir']);
$tpl->assign('gamename', $gamename);
$tpl->assign('gamename_full', $gamename_full);

global $starttime;
$starttime = getmicrotime();
Header("Pragma: no-cache");
Header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

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
$tpl->assign('servname', $config['servname']);
$tpl->assign('empire', $users['empire']);
$tpl->assign('num', $users['num']);


//include("menus.php");
$tpl->display('faq.html');
?>

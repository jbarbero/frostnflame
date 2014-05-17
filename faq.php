<?php

if (auth_user(true))
    include("header.php");
else
    htmlbegincompact("Medieval Empires: Game FAQ");
//require_once("funcs.php");

randomize();

$basehref = $config['sitedir'];
//auth_user();

global $starttime;
$starttime = getmicrotime();
Header("Pragma: no-cache");
Header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

if($users['basehref'] != '')
    $basehref = $users['basehref'];
set_incl_path(getTplDir());

$cnd = '';
if($users['condense'])
    $cnd = ' checked';

//include("menus.php");
$template_display('faq.html');
?>

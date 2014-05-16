<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2004 Paul Puzyrev, Sergei Larionov. www.minibb.net
*/
if (!defined('INCLUDED776')) die ('Fatal error.');
$title.=$l_userIP;

$postip=(isset($_GET['postip'])?$_GET['postip']:'');

if ($logged_admin==1 or $isMod==1) {
$listUsers='';
$l_usersIPs=$l_usersIPs." ".$postip;

if ($row=db_simpleSelect(0,$Tp,'DISTINCT poster_name, poster_id','poster_ip','=',$postip,'poster_name')) {
$listUsers.="<ul>";
do {
$star=($row[1]!=0?"<a href=\"{$indexphp}action=userinfo&user={$row[1]}\">*</a>":"");
$listUsers.="<li><p>{$row[0]}{$star}";
}
while($row=db_simpleSelect(1));
$listUsers.="</ul>";
}
else $listUsers=$l_userNoIP;
}

$banLink=($logged_admin==1?"<a href=\"minibb/{$bb_admin}action=banUsr1&banip={$postip}\"><small class=warning>&raquo;&nbsp;{$l_ban}</small></a>":'');

echo load_header(); echo ParseTpl(makeUp('tools_userips')); return;
?>

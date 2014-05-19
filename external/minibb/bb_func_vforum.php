<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2004 Paul Puzyrev, Sergei Larionov. www.minibb.net
*/
if (!defined('INCLUDED776')) die ('Fatal error.');
$keyAr=0;
$list_forums='';

if($cols=db_simpleSelect(0,$Tf,'forum_id, forum_name, forum_desc, forum_icon, topics_count, posts_count','','','','forum_order')){
$i=0;
$tpl=makeUp('main_forums_cell');
do{
$forum=$cols[0];

if($user_id!=1 and isset($clForums) and in_array($forum,$clForums) and isset($clForumsUsers[$forum]) and !in_array($user_id,$clForumsUsers[$forum])) $show=FALSE; else $show=TRUE;

if($show){

if($i%2==0) $bg='tbCel1'; elseif($i%2==1) $bg='tbCel2';

$forum_title=$cols[1];
$forum_desc=$cols[2];
$forum_icon=$cols[3];
$fIcon[$forum]=$forum_icon;
$numTopics=$cols[4];
$numPosts=$cols[5];
$numPosts=$numPosts-$numTopics;

if (isset($forumGroups) and isset($forumGroupsDesc) and in_array($forum,$forumGroups)){
$forumGroupName=$forumGroupsDesc[$keyAr];
$list_forums.=ParseTpl(makeUp('main_forumgroup'));
$keyAr++;
}

$list_forums.=ParseTpl($tpl);
$i++;
}

}
while($cols=db_simpleSelect(1));
unset($result);unset($countRes);
}
$title=$sitename;
echo load_header(); echo ParseTpl(makeUp('main_forums'));
?>

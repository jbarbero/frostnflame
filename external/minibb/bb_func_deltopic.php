<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2004 Paul Puzyrev, Sergei Larionov. www.minibb.net
*/
if (!defined('INCLUDED776')) die ('Fatal error.');

if ($logged_admin==1 or $isMod==1) {

db_delete($Ts,'topic_id','=',$topic);
$topicsDel=db_delete($Tt,'topic_id','=',$topic,'forum_id','=',$forum);
$postsDel=db_delete($Tp,'topic_id','=',$topic,'forum_id','=',$forum);
$postsDel--;
db_forumReplies($forum,$Tp,$Tf);
db_forumTopics($forum,$Tt,$Tf);

if(isset($metaLocation)) { $meta_relocate="{$main_url}/{$indexphp}action=vtopic&forum={$forum}"; echo ParseTpl(makeUp($metaLocation)); exit; } else { header("Location: {$main_url}/{$indexphp}action=vtopic&forum={$forum}"); exit; }

}
else {
$errorMSG=$l_forbidden; $correctErr='';
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
?>

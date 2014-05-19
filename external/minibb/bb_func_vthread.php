<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2004 Paul Puzyrev, Sergei Larionov. www.minibb.net
*/
if (!defined('INCLUDED776')) die ('Fatal error.');

$listPosts=''; $deleteTopic='';

if(!$row=db_simpleSelect(0,$Tf,'forum_name, forum_icon, topics_count, posts_count','forum_id','=',$forum)){
$errorMSG=$l_forumnotexists; $correctErr=$backErrorLink;
$title=$title.$l_forumnotexists;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
unset($result);unset($countRes);

$forumName=$row[0]; $forumIcon=$row[1];

$topicData=db_simpleSelect(0,$Tt,'topic_title, topic_status, topic_poster, topic_poster_name, forum_id, posts_count, sticky, topic_views','topic_id','=',$topic);
unset($result);unset($countRes);

/*** CHECK ***/

if($topicData and $topicData[4]==$forum){
$topicName=$topicData[0]; if ($topicName=='') $topicName=$l_emptyTopic;
$topicStatus=$topicData[1];
$topicSticky=$topicData[6];
$topicPoster=$topicData[2];
$topicPosterName=$topicData[3];
$topic_views=$topicData[7]+1;
}
else {
$errorMSG=$l_topicnotexists; $correctErr=$backErrorLink;
$title=$title.$l_topicnotexists;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

$numRows=$topicData[5];

$topicDesc=0;
$topic_reverse='';
if(isset($themeDesc) and in_array($topic,$themeDesc)) {
$topicDesc=1;
$topic_reverse="<img src=\"{$main_url}/img/topic_reverse.gif\" align=middle border=0 alt=\"\">&nbsp;";
}

if($page==-1 and $topicDesc==0) $page=pageChk($page,$numRows,$viewmaxreplys);
elseif($page==-1 and $topicDesc==1) $page=0;

if(isset($mod_rewrite) and $mod_rewrite) $urlp="{$main_url}/{$forum}_{$topic}_"; else $urlp="{$indexphp}action=vthread&amp;forum=$forum&amp;topic=$topic&amp;page=";

$pageNav=pageNav($page,$numRows,$urlp,$viewmaxreplys,FALSE);
$makeLim=makeLim($page,$numRows,$viewmaxreplys);

$anchor=1;
$i=1;

if(isset($themeDesc) and in_array($topic,$themeDesc)) $srt='DESC'; else $srt='ASC';

if($cols=db_simpleSelect(0,$Tp,'poster_id, poster_name, post_time, post_text, poster_ip, post_status, post_id','topic_id','=',$topic,'post_id '.$srt,$makeLim)){

if($page==0 and isset($enableViews) and $enableViews) updateArray(array('topic_views'),$Tt,'topic_id',$topic);

$tpl=makeUp('main_posts_cell');

do{
if($i>0) $bg='tbCel1'; else $bg='tbCel2';

$postDate=convert_date($cols[2]);

$allowedEdit="<a href=\"{$indexphp}action=editmsg&amp;topic=$topic&amp;forum=$forum&amp;post={$cols[6]}&amp;page=$page&amp;anchor=$anchor\">$l_edit</a>";

if ($logged_admin==1 or $isMod==1) { 
$viewIP=' '.$l_sepr.' IP: '.'<a href="'.$indexphp.'action=viewipuser&amp;postip='.$cols[4].'">'.$cols[4].'</a>';
if(($i==0 and $page==0 and $topicDesc==0) or ($topicDesc==1 and $numRows==$viewmaxreplys*$page+$i+1))$deleteM='';
else $deleteM=<<<out
<a href="JavaScript:confirmDelete({$cols[6]},0)" onMouseOver="window.status='{$l_deletePost}'; return true;" onMouseOut="window.status=''; return true;">$l_deletePost</a>
out;
$allowed=$allowedEdit." ".$deleteM;
} 
else {
$cols[4]='';
if ($user_id==$cols[0] and $user_id !=0 and $cols[5]!=2 and $cols[5]!=3) {
$allowed=$allowedEdit;
}
else {
$allowed='';
}
}

# post_status: 0-clear (available for edit), 1-edited by author, 2-edited by admin (available only for admin), 3 - edited by mod
if ($cols[5]==0) {
$editedBy='';
}
else {
$editedBy=" $l_sepr $l_editedBy";
if($cols[5]==2) $we="<a href=\"{$indexphp}action=userinfo&amp;user=1\">{$l_admin}</a>";
elseif($cols[5]==1) $we=$cols[1];
elseif($cols[5]==3) $we="<a href=\"{$indexphp}action=stats#mods\">{$l_moderator}</a>";
else $we='N/A';
$editedBy.=$we;
}

if ($cols[0]!=0) {
$cc=$cols[0];
if (isset($userRanks[$cc])) $ins=$userRanks[$cc];
elseif (isset($mods) and in_array($cc,$mods)) {if (isset($modsOut) and in_array($cc.'>'.$forum,$modsOut)) $ins=$l_member; else $ins=$l_moderator;}
else { $ins=($cc==1?$l_admin:$l_member); }
if(isset($mod_rewrite) and $mod_rewrite) $viewReg="<a href=\"{$main_url}/user{$cc}.html\">{$ins}</a>"; else $viewReg="<a href=\"{$indexphp}action=userinfo&amp;user={$cc}\">$ins</a>";
}
else $viewReg='';

$posterName=$cols[1];
$posterText=$cols[3];

$listPosts.=ParseTpl($tpl);

$i=-$i;
$anchor++;
}
while($cols=db_simpleSelect(1));
unset($result);unset($countRes);

$l_messageABC=$l_sub_answer;
if ($topicStatus!=1) {
$emailCheckBox=emailCheckBox();
//OR (((isset($allForumsReg) and $allForumsReg) OR (isset($regUsrForums) and in_array($forum, $regUsrForums) and $user_id==0))
if (isset($roForums) and in_array($forum, $roForums) and $user_id!=1 and $isMod!=1){
$mainPostForm='';$mainPostArea='';
$nTop=0;
$listPosts=str_replace('getQuotation();','',$listPosts);
}else{
$mainPostForm=ParseTpl(makeUp('main_post_form'));
$mainPostArea=makeUp('main_post_area');
$nTop=1;
}
}
else {
$mainPostArea=makeUp('main_post_closed');
$listPosts=str_replace('getQuotation();','',$listPosts);
}
$mainPostArea=ParseTpl($mainPostArea);

if ($logged_admin==1 or $isMod==1) {

$deleteTopic="$l_sepr <a href=\"JavaScript:confirmDelete({$topic},1)\" onMouseOver=\"window.status='{$l_deleteTopic}'; return true;\" onMouseOut=\"window.status=''; return true;\">$l_deleteTopic</a>";

$moveTopic="$l_sepr <a href=\"{$indexphp}action=movetopic&amp;forum=$forum&amp;topic=$topic&amp;page=$page\">$l_moveTopic</a>";

if ($topicStatus==0) { $chstat=1; $cT=$l_closeTopic; }
else { $chstat=0; $cT=$l_unlockTopic; }
$closeTopic="<a href=\"{$indexphp}action=locktopic&amp;forum=$forum&amp;topic=$topic&amp;chstat=$chstat\">$cT</a>";

if ($topicSticky==0) { $chstat=1; $cT=$l_makeSticky; }
else { $chstat=0; $cT=$l_makeUnsticky; }
$stickyTopic="$l_sepr <a href=\"{$indexphp}action=unsticky&amp;forum=$forum&amp;topic=$topic&amp;chstat=$chstat\">$cT</a>";

$extra=1;
if ($logged_admin==1 and $cnt=db_simpleSelect(0,$Ts,'count(*)','topic_id','=',$topic) and $cnt[0]>0) $subsTopic="$l_sepr <a href=\"{$bb_admin}action=viewsubs&amp;topic=$topic\">$l_subscriptions</a>"; else $subsTopic='';
}

elseif (($user_id==$topicPoster and $user_id!=0 and $user_id!=1) and $topicSticky!=1) {
if ($topicStatus==0) $closeTopic="<center><a href=\"{$indexphp}action=locktopic&amp;forum=$forum&amp;topic=$topic&amp;chstat=1\">$l_closeTopic</a></center>";
elseif($topicStatus==1 and $userUnlock==1) $closeTopic="<center><a href=\"{$indexphp}action=locktopic&amp;forum=$forum&amp;topic=$topic&amp;chstat=0\">$l_unlockTopic</a></center>";
else $closeTopic='';
}

$title=$title.$topicName;

}//if posts

$st=0; $frm=$forum;
include (MINIBB_PATH.'bb_func_forums.php');

echo load_header(); echo ParseTpl(makeUp('main_posts'));
?>

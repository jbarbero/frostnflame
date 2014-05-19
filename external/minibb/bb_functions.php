<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2004 Paul Puzyrev, Sergei Larionov. www.minibb.net
*/
$cookieexptime=time()+$cookie_expires;
$version='2.0 RC1f';

function user_logged_in() {

if(isset($GLOBALS['cook'])) $minimalistBB=$GLOBALS['cook'];
elseif(isset($_COOKIE[$GLOBALS['cookiename']])) $minimalistBB=$_COOKIE[$GLOBALS['cookiename']];
elseif(isset($_SESSION['minimalistBBSession'])) $minimalistBB=$_SESSION['minimalistBBSession'];
else $minimalistBB='';

$returned=FALSE;
$resetCookie=FALSE;

if($minimalistBB!='') {
$cookievalue=explode ("|", $minimalistBB);
$username=$cookievalue[0]; $userpassword=$cookievalue[1]; $exptime=$cookievalue[2];
}
else { $returned=FALSE; return; }

$GLOBALS['user_usr']=$username;

$pasttime=$exptime-time();

if ($username==$GLOBALS['admin_usr'] and $userpassword==md5($GLOBALS['admin_pwd'])) {
$returned=TRUE;
$GLOBALS['logged_user']=0; $GLOBALS['logged_admin']=1; $GLOBALS['user_id']=1;

if($row=db_simpleSelect(0,$GLOBALS['Tu'],$GLOBALS['dbUserSheme']['user_sorttopics'][1].','.$GLOBALS['dbUserSheme']['language'][1],$GLOBALS['dbUserId'],'=',1))
$GLOBALS['user_sort']=$row[0]; $GLOBALS['langu']=$row[1];

if ($pasttime<=$GLOBALS['cookie_renew']) {
// if expiration time of cookie is less than defined in setup, we redefine it below
$cook=$GLOBALS['admin_usr'].'|'.md5($GLOBALS['admin_pwd']).'|'.$GLOBALS['cookieexptime'];
$resetCookie=TRUE;
}

} 

elseif($userpassword!='' and $row=db_simpleSelect(0,$GLOBALS['Tu'],$GLOBALS['dbUserId'].','. $GLOBALS['dbUserSheme']['user_sorttopics'][1].','. $GLOBALS['dbUserSheme']['language'][1].','. $GLOBALS['dbUserAct'],$GLOBALS['dbUserSheme']['username'][1],'=',$username,'',1, $GLOBALS['dbUserSheme']['user_password'][1],'=',$userpassword)){
$returned=TRUE;
$GLOBALS['user_id']=$row[0]; $GLOBALS['user_sort']=$row[1]; $GLOBALS['logged_user']=1; $GLOBALS['logged_admin']=0;
$GLOBALS['langu']=$row[2];
$GLOBALS['user_activity']=$row[3];

if ($pasttime<=$GLOBALS['cookie_renew']) {
$cook=$username.'|'.$userpassword.'|'.$GLOBALS['cookieexptime'];
$resetCookie=TRUE;
}

}

else{
$returned=FALSE;
if ($pasttime<=$GLOBALS['cookie_renew']) {
$cook=$username.'||'.$GLOBALS['cookieexptime'];
$resetCookie=TRUE;
}
}

if($resetCookie) {
setcookie($GLOBALS['cookiename'],'',(time() - 2592000),$GLOBALS['cookiepath'],$GLOBALS['cookiedomain'],$GLOBALS['cookiesecure']);
setcookie($GLOBALS['cookiename'], $cook, $GLOBALS['cookieexptime'], $GLOBALS['cookiepath'], $GLOBALS['cookiedomain'], $GLOBALS['cookiesecure']);
}

return $returned;
}

//--------------->
function makeUp($name,$addDir='') {
if($addDir=='') $addDir=MINIBB_PATH.'templates/';
if (substr($name,0,5)=='email') $ext='txt'; else $ext='html';
if (file_exists($addDir."{$name}.{$ext}")) { 
if (file_exists('local/'.$addDir."{$name}.{$ext}"))
    $addDir = "local/$addDir";
$tpl='';
$fd=fopen ($addDir."{$name}.{$ext}",'r');
while(!feof($fd)) $tpl.=fgets($fd,1024);
fclose ($fd);
}
else die ("TEMPLATE NOT FOUND: $name");
return $tpl;
}

//--------------->
function ParseTpl($tpl){
$qs=array();
$qv=array();
$ex=explode ('{$',$tpl);
for ($i=0; $i<=sizeof($ex); $i++){
if (!empty($ex[$i]) and substr_count($ex[$i],'}')>0) {
$xx=explode('}',$ex[$i]);
if (substr_count($xx[0],'[')>0) {
$clr=explode ('[',$xx[0]); $sp=$clr[1]+0; $clr=$clr[0];
if (!in_array($clr,$qs)) {$qs[]=$clr; }
if(isset($GLOBALS[$clr][$sp])) $to=$GLOBALS[$clr][$sp]; else $to='';
}
else { if(!in_array($xx[0], $qv)) {$qv[]=$xx[0]; }
if(isset($GLOBALS[$xx[0]])) $to=$GLOBALS[$xx[0]]; else $to='';
}
$tpl=str_replace('{$'.$xx[0].'}', $to, $tpl);
}
}
return $tpl;
}

//--------------->
function load_header() {
//we need to load this template separately, because we load page title
if(!isset($GLOBALS['adminPanel'])) $GLOBALS['adminPanel']=0;

if(strlen($GLOBALS['action'])>0||$GLOBALS['adminPanel']==1) $GLOBALS['l_menu'][0]="{$GLOBALS['l_sepr']} <a href=\"".MINIBB_PATH."{$GLOBALS['indexphp']}\">{$GLOBALS['l_menu'][0]}</a> "; else $GLOBALS['l_menu'][0]='';
if($GLOBALS['viewTopicsIfOnlyOneForum']==1 and $GLOBALS['action']=='') $GLOBALS['l_menu'][7]="{$GLOBALS['l_sepr']} <a href=\"#newtopic\">{$GLOBALS['l_menu'][7]}</a> ";
if(isset($GLOBALS['nTop'])&&$GLOBALS['nTop']==1){
if($GLOBALS['action']=='vtopic') $GLOBALS['l_menu'][7]="{$GLOBALS['l_sepr']} <a href=\"#newtopic\">{$GLOBALS['l_menu'][7]}</a> ";
elseif($GLOBALS['action']=='vthread') $GLOBALS['l_menu'][7]="{$GLOBALS['l_sepr']} <a href=\"#newreply\">{$GLOBALS['l_reply']}</a> ";
}
else $GLOBALS['l_menu'][7]='';
if($GLOBALS['action']!='stats') $GLOBALS['l_menu'][3]="{$GLOBALS['l_sepr']} <a href=\"".MINIBB_PATH."{$GLOBALS['indexphp']}action=stats\">{$GLOBALS['l_menu'][3]}</a> "; else $GLOBALS['l_menu'][3]='';
if($GLOBALS['action']!='poll') $GLOBALS['l_menu'][9] = "{$GLOBALS['l_sepr']} <a href=\"./{$GLOBALS['indexphp']}action=poll\">{$GLOBALS['l_menu'][9]}</a> "; else $GLOBALS['l_menu'][9]='';
if($GLOBALS['action']!='search') $GLOBALS['l_menu'][1]="{$GLOBALS['l_sepr']} <a href=\"".MINIBB_PATH."{$GLOBALS['indexphp']}action=search\">{$GLOBALS['l_menu'][1]}</a> "; else $GLOBALS['l_menu'][1]='';

if($GLOBALS['action']!='registernew' and $GLOBALS['user_id']==0 and $GLOBALS['adminPanel']!=1 and $GLOBALS['enableNewRegistrations']) $GLOBALS['l_menu'][2]="{$GLOBALS['l_sepr']} <a href=\"".MINIBB_PATH."{$GLOBALS['indexphp']}action=registernew\">{$GLOBALS['l_menu'][2]}</a> "; else $GLOBALS['l_menu'][2]='';

if($GLOBALS['action']!='manual') $GLOBALS['l_menu'][4]="{$GLOBALS['l_sepr']} <a href=\"{$GLOBALS['indexphp']}action=manual\">{$GLOBALS['l_menu'][4]}</a> "; else $GLOBALS['l_menu'][4]='';
if($GLOBALS['action']!='prefs'&&$GLOBALS['user_id']!=0 and $GLOBALS['enableProfileUpdate']) $GLOBALS['l_menu'][5]="{$GLOBALS['l_sepr']} <a href=\"".MINIBB_PATH."{$GLOBALS['indexphp']}action=prefs\">{$GLOBALS['l_menu'][5]}</a> "; else $GLOBALS['l_menu'][5]='';

if($GLOBALS['user_id']!=0) $GLOBALS['l_menu'][6]="{$GLOBALS['l_sepr']} <a href=\"".MINIBB_PATH."{$GLOBALS['indexphp']}mode=logout\">{$GLOBALS['l_menu'][6]}</a> "; else $GLOBALS['l_menu'][6]='';

if (!isset($GLOBALS['title']) or $GLOBALS['title']=='') $GLOBALS['title']=$GLOBALS['sitename'];
if(isset($GLOBALS['includeHeader'])) { include($GLOBALS['includeHeader']); return; }
return ParseTpl(makeUp('main_header'));
}

//--------------->
function getAccess($clForums, $clForumsUsers, $user_id){
$forb=array();
$acc='n';
if ($user_id!=1 and sizeof($clForums)>0){
foreach($clForums as $f){
if (isset($clForumsUsers[$f]) and !in_array($user_id, $clForumsUsers[$f])){
$forb[]=$f; $acc='m';
}
}
}
if ($acc=='m') return $forb; else return $acc;
}

//--------------->
function getIP(){
$ip1=getenv('REMOTE_ADDR');$ip2=getenv('HTTP_X_FORWARDED_FOR');
if ($ip2!='' and ip2long($ip2)!=-1) $finalIP=$ip2; else $finalIP=$ip1;
$finalIP=substr($finalIP,0,15);
return $finalIP;
}

//--------------->
function convert_date($dateR){
$engMon=array('January','February','March','April','May','June','July','August','September','October','November','December',' ');
$months=explode (':', $GLOBALS['l_months']);
$months[]='&nbsp;';
if(isset($GLOBALS['timeDiff']) and $GLOBALS['timeDiff']!=0) $dateR=date($GLOBALS['dateFormat'],strtotime($dateR)+$GLOBALS['timeDiff']);
else $dateR=date($GLOBALS['dateFormat'],strtotime($dateR));
$dateR=str_replace($engMon,$months,$dateR);
return $dateR;
}

//--------------->
function pageChk($page,$numRows,$viewMax){
if($numRows>0 and ($page>0 or $page==-1)){
$max=$numRows/$viewMax;
if(intval($max)==$max) $max=intval($max)-1; else $max=intval($max);
if ($page==-1) return $max;
elseif($page>$max) return $max;
else return $page;
}
else return 0;
}

//--------------->
function pageNav($page,$numRows,$url,$viewMax,$navCell){
$pageNav='';
if(isset($GLOBALS['mod_rewrite']) and $GLOBALS['mod_rewrite'] and ($GLOBALS['action']=='vtopic' or $GLOBALS['action']=='vthread' or $GLOBALS['action']=='')) $mr='.html'; else $mr='';
$page=pageChk($page,$numRows,$viewMax);
$iVal=intval(($numRows-1)/$viewMax);
if($iVal>$GLOBALS['viewpagelim']){
$iVal=$GLOBALS['viewpagelim'];
if($GLOBALS['viewpagelim']>=1) $iVal-=1;
}
if($numRows>0&&$iVal>0&&$numRows<>$viewMax){
$end=$iVal;
if(!$navCell) $start=0; else $start=1;
if($page>0&&!$navCell) $pageNav=' <a href="'.$url.($page-1).$mr.'">&lt;&lt;</a>';
if($navCell&&$end>4){ $end=3;$pageNav.=' . '; }
elseif($page<9&&$end>9){ $end=9;$pageNav.=' . '; }
elseif($page>=9&&$end>9){
$start=intval($page/9)*9-1;$end=$start+10;
if($end>$iVal) $end=$iVal;
$pageNav.=' <a href="'.$url.'0'.$mr.'">1</a> ...';
}
else $pageNav.=' . ';
for($i=$start;$i<=$end;$i++){
if($i==$page&&!$navCell) $pageNav.=' <b>'.($i+1).'</b> .';
else $pageNav.=' <a href="'.$url.$i.$mr.'">'.($i+1).'</a> .';
}
if((($navCell&&$iVal>4)||($iVal>9&&$start<$iVal-10))){
if($navCell&&$iVal<6); else $pageNav.='..';
for($n=$iVal-1;$n<=$iVal;$n++){
if($n>=$i) $pageNav.=' <a href="'.$url.$n.$mr.'">'.($n+1).'</a> .';
}
}
if($page<$iVal&&!$navCell) $pageNav.=' <a href="'.$url.($page+1).$mr.'">&gt;&gt;</a>';
return $pageNav;
}
}

//---------------------->
function sendMail($email, $subject, $msg, $from_email, $errors_email) {
// Function sends mail with return-path (if incorrect email TO specifed. Reply-To: and Errors-To: need contain equal addresses!
if (!isset($GLOBALS['genEmailDisable']) or $GLOBALS['genEmailDisable']!=1){
$msg=str_replace("\r\n", "\n", $msg);
$php_version=phpversion();
$from_email="From: $from_email\r\nReply-To: $errors_email\r\nErrors-To: $errors_email\r\nX-Mailer: PHP ver. $php_version";
mail($email, $subject, $msg, $from_email);
}
}

//---------------------->
function emailCheckBox() {

$checkEmail='';
if($GLOBALS['genEmailDisable']!=1){

$isInDb=db_simpleSelect(0,$GLOBALS['Ts'],'count(*)','topic_id','=',$GLOBALS['topic'],'','','user_id','=',$GLOBALS['user_id']);
if($isInDb[0]>0) $isInDb=TRUE; else $isInDb=FALSE;

$true0=($GLOBALS['emailusers']==1);
$true1=($GLOBALS['user_id']!=0);
$true2=($GLOBALS['action']=='vtopic' or $GLOBALS['action'] == 'vthread' or $GLOBALS['action']=='ptopic' or $GLOBALS['action']=='pthread');
$true3a=($GLOBALS['user_id']==1 and (!isset($GLOBALS['emailadmposts']) or $GLOBALS['emailadmposts']==0) and !$isInDb);
$true3b=($GLOBALS['user_id']!=1 and !$isInDb);
$true3=($true3a or $true3b);

if ($true0 and $true1 and $true2 and $true3) {
$checkEmail="<input type=checkbox name=CheckSendMail> <a href=\"{$GLOBALS['indexphp']}action=manual#emailNotifications\">{$GLOBALS['l_emailNotify']}</a>";
}
elseif($isInDb) $checkEmail="<!--U--><a href=\"{$GLOBALS['indexphp']}action=unsubscribe&topic={$GLOBALS['topic']}&usrid={$GLOBALS['user_id']}\">{$GLOBALS['l_unsubscribe']}</a>";
}
return $checkEmail;
}

//---------------------->
function makeValuedDropDown($listArray,$selectName){
$out='';
if(isset($GLOBALS[$selectName])) $curVal=$GLOBALS[$selectName]; else $curVal='';
foreach($listArray as $key=>$val){
if($curVal==$key) $sel=' selected'; else $sel='';
$out.="<option {$sel} value=\"$key\">$val</option>\n";
}
return "<select name=$selectName class=textForm>$out</select>";
}

?>

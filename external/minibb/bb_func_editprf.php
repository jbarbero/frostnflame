<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2004 Paul Puzyrev, Sergei Larionov. www.minibb.net
*/
if (!defined('INCLUDED776')) die ('Fatal error.');

if ($user_id!=0) {

if (!isset($warning)) $warning='';
$l_fillRegisterForm='';
$editable='disabled';
$userTitle=$l_editPrefs;
$l_passOnceAgain.=' (<span class=txtSm>'.$l_onlyIfChangePwd.')</span>';
$actionName='editprefs';

if ($userData=db_simpleSelect(0,$Tu,'*',$dbUserId,'=',$user_id)) {

include(MINIBB_PATH.'bb_func_inslng.php');
if(isset($_POST['user_viewemail'])) $user_viewemail=$_POST['user_viewemail']; else $user_viewemail=$userData[10];
$showemailDown=makeValuedDropDown(array(0=>$l_no,1=>$l_yes),'user_viewemail');
if(isset($_POST['user_sorttopics'])) $user_sorttopics=$_POST['user_sorttopics']; else $user_sorttopics=$userData[11];
$sorttopicsDown=makeValuedDropDown(array(0=>$l_newAnswers,1=>$l_newTopics),'user_sorttopics');
if(!isset($_POST['language'])) $language=$userData[14]; else $language=$_POST['language'];
$languageDown=makeValuedDropDown($glang,'language');

if ($step==1) {
require(MINIBB_PATH.'bb_func_usrdat.php');
${$dbUserSheme['username'][1]}=$userData[1];
${$dbUserSheme['username'][2]}=$userData[1];

$act='upd';
require(MINIBB_PATH.'bb_func_checkusr.php');

if (db_simpleSelect(0,$Tu,$dbUserId,$dbUserSheme['user_email'][1],'=',${$dbUserSheme['user_email'][1]},'','',$dbUserId,'!=',$user_id) or (${$dbUserSheme['user_email'][1]}==$admin_email and $user_id!=1)) $correct=4;

if ($correct=='ok') {
//Update db
$upda=array($dbUserSheme['user_email'][1], $dbUserSheme['user_icq'][1], $dbUserSheme['user_website'][1], $dbUserSheme['user_occ'][1], $dbUserSheme['user_from'][1], $dbUserSheme['user_interest'][1], $dbUserSheme['user_viewemail'][1], $dbUserSheme['user_sorttopics'][1], $dbUserSheme['language'][1], $dbUserSheme['user_custom1'][1], $dbUserSheme['user_custom2'][1], $dbUserSheme['user_custom3'][1]);
if($passwd!=''){
${$dbUserSheme['user_password'][1]}=md5(${$dbUserSheme['user_password'][1]});
$upda[]=$dbUserSheme['user_password'][1];
}

$upd=updateArray($upda,$Tu,$dbUserId,$user_id);
if ($upd>0) {
$title.=$l_prefsUpdated;
$warning=$l_prefsUpdated;
if (${$dbUserSheme['user_password'][2]}!='') $warning.=', '.$l_prefsPassUpdated;
}
else {
$title.=$l_editPrefs;
$warning=$l_prefsNotUpdated;
}

}
else {
if (!isset($l_userErrors[$correct])) $l_userErrors[$correct]=$l_undefined;
$warning=$l_errorUserData.": <span class=warning>{$l_userErrors[$correct]}</span>";
$title.=$l_errorUserData;
}

$tpl=makeUp('user_dataform');
if($user_id==1) $tpl=preg_replace("#<!--PASSWORD-->(.*?)<!--/PASSWORD-->#is",'',$tpl);
echo load_header(); echo ParseTpl($tpl); return;
}

else {
//step=0
foreach($dbUserSheme as $k=>$v){
$fk=$v[2];
if(isset($userData[$v[0]])) $$fk=$userData[$v[0]]; else $$fk='';
}
${$dbUserSheme['user_password'][2]}='';
$passwd2='';

$title.=$l_editPrefs;
$tpl=makeUp('user_dataform');
if($user_id==1) $tpl=preg_replace("#<!--PASSWORD-->(.*?)<!--/PASSWORD-->#is",'',$tpl);
echo load_header(); echo ParseTpl($tpl); return;
}

}
else {
$title.=$l_mysqli_error; $errorMSG=$l_mysqli_error; $correctErr='';
$tpl=makeUp('main_warning'); 
}

}
else {
$title.=$l_forbidden; $errorMSG=$l_forbidden; $correctErr='';
$tpl=makeUp('main_warning');
}

echo load_header(); echo ParseTpl($tpl); return;
?>

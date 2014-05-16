<?php 
require_once("funcs.php");

// get-post-server (avoids security issues)
foreach ($_GET as $var => $value) {
	$$var = $value;
} 
foreach ($_POST as $var => $value) {
	$$var = $value;
} 
foreach ($_SERVER as $var => $value) {
	$$var = $value;
} 

function getmicrotime ()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((double)$usec + (double)$sec);
} 

function getstyle() {
	global $styles, $users, $authstr, $skinstr, $config, $tpl, $global;
	$ret;

	if(!empty($_GET['skin'])) {
		fixInputNum($_GET['skin']);
		$skinstr = '&amp;skin=' . $_GET['skin'];

		$authstr = $skinstr;
		$ret = $styles[$_GET['skin']];
	} else {
		$ret = $styles[$users[style]];
	}

	if(auth_global()) {
		$tpl->assign('loggedin', true);
		$tpl->assign('global', $global);
	}

	if(empty($ret))
		$ret = $styles[$config['default_style']];

	return $ret;
}

function getTplDir()
{
	global $templates, $users;

	$ret = $templates[$users[style]];
	if(empty($ret))
		$ret = $templates[$config['default_style']];
	if(empty($ret))
		$ret = $templates[1];

	return $ret;
} 
// Begins a compact HTML page
function HTMLbegincompact ($title)
{
	global $authstr;
	if(!defined("PROMISANCE"))
		die(" ");

	global $tpl, $starttime, $templates, $gamename, $gamename_full, $config, $authstr, $uera, $skinstr;
	$authstr = '&amp;srv='.SERVER;
	$uera = loadEra(1,1);
	$starttime = getmicrotime();
	Header("Pragma: no-cache");
	Header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
	set_incl_path($templates[1]);
	$tpl->assign('stylename', getstyle());
	$tpl->assign('authstr', $authstr);
	$tpl->assign('skinstr', $skinstr);
	$tpl->assign('sitedir', $config['sitedir']);
	$tpl->assign('gamename', $gamename);
	$tpl->assign('gamename_full', $gamename_full);
	$tpl->assign('servname', $config[servname]);
	$tpl->assign('title', $title);

	include("menus_alt.php");
	$tpl->display('htmlbegincompact.html');
} 
// Ends a compact HTML page
function HTMLendcompact ()
{
	global $tpl, $starttime;
	$endtime = getmicrotime() - $starttime;
	$tpl->display('htmlendcompact.html');
} 

?>

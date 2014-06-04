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

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((double)$usec + (double)$sec);
} 

function getstyle() {
    global $config, $styles, $users, $authstr, $skinstr, $config, $tpl, $global;
    $ret;

    if(!empty($_GET['skin'])) {
        fixInputNum($_GET['skin']);
        $skinstr = '&amp;skin=' . $_GET['skin'];

        $authstr = $skinstr;
        $ret = $styles[$_GET['skin']];
    } else if(is_array($users)) {
        $style = $users[style];
        $ret = $styles[$style];
    } else {
        $ret = $styles[1];
    }

    if(auth_global()) {
        global $loggedin;
        $loggedin = true;
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
function HTMLbegincompact ($page_title)
{
    if(!defined("PROMISANCE"))
        die(" ");

    global $authstr, $title, $starttime, $templates, $gamename, $gamename_full, $config, $authstr, $uera, $skinstr, $menus, $config;
    $title = $page_title;
    $authstr = '&amp;srv='.SERVER;
    $uera = loadEra(1,1);
    $starttime = getmicrotime();
    Header("Pragma: no-cache");
    Header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
    set_incl_path($templates[1]);
    global $stylename;
    $stylename = getstyle();

    include("menus_alt.php");
    template_display('htmlbegincompact.html');
} 
// Ends a compact HTML page
function HTMLendcompact ()
{
    global $tpl, $starttime;
    $endtime = getmicrotime() - $starttime;
    template_display('htmlendcompact.html');
} 

?>

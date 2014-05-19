<?php
include("header.php");

template_display('admin.html');

if ($users[num] != 1)
    TheEnd("You are not the root administrator!");

session_start();

$_SESSION['root'] = 1;
$_SESSION['mysql']['host'] = $config['dbhost'];
$_SESSION['mysql']['user'] = $config['dbuser'];
$_SESSION['mysql']['pass'] = $config['dbpass'];
$_SESSION['mysql']['db'] = $config['dbname'];

define('self', str_replace("&amp;", "&", "?admin$authstr"));
include("external/minimyadmin.inc");

TheEnd("");

?>

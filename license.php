<?
if(!defined("PROMISANCE"))
	die(" ");
if (auth_user(true))
        include("header.php");
else
        htmlbegincompact("License");

$tpl->display('license.html');
TheEnd("");
?>

<?
if(!defined("PROMISANCE"))
    die(" ");
if (auth_user(true))
        include("header.php");
else
        htmlbegincompact("License");

template_display('license.html');
TheEnd("");
?>

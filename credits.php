<?php
require_once("funcs.php");
if (auth_user(true))
    include("header.php");
else
    htmlbegincompact("Credits");

$template_display('credits.html');

TheEnd("");
?>

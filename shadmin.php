<?php
include("header.php");

if ($users[num] != 1)
    TheEnd("You are not the root administrator!");

template_display('admin.html');

include("external/ajaxphpterm.inc");

TheEnd("");

?>

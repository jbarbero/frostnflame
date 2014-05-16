<?php
include("header.php");

if ($lastweek)
	theEnd("Deleting is disabled during the last week of the game.");

$tpl->assign('state', 3);
if ($do_delete) {
	$tpl->assign('state', 1);
	if ($delete_name == $users[name])
		db_safe_query("UPDATE $playerdb SET land=0,disabled=4 WHERE num=$users[num];");
	else
		$tpl->assign('state', 2);
}

$tpl->display('delete.html');
TheEnd("");
?>

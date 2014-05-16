<?php
function TheEnd ($reason="") // End current action
{
	global $users, $tpldir, $config, $version, $tpl;
	echo $reason;
	echo '<br>';

	doStatusBar(true);
	ob_end_flush();
	exit;
} 

function ThePrint($message)
{
	echo "$message\n<hr>\n";
} 

?>

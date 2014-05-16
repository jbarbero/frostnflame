<?php
function TheEnd ($reason) // End current action in turns.php for scripts
{
	echo "\n$reason\n";
	ob_end_flush();
	return;
} 

function ThePrint ($message)
{ 
	// log the message somehow, somewhere
} 

?>

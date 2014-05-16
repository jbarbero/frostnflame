<?php
if (!defined('SERVER')) {
	if (isset($_GET['srv'])) {
		$s = $_GET['srv'];
		define('SERVER', $s);
	} else if (isset($_GET['auth'])) {
		$au = explode("\n", @base64_decode($_GET['auth']));
		$s = $au[3];
		define('SERVER', $s);
	} else {
		define('SERVER', 1);
	} 
} 

if(empty($server))
	$server = SERVER;

?>

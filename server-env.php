<?
function set_incl_path($utd="") {
	global $config, $TPL_PATH;
	$localdir = "local";			// Don't touch
	if(isset($config['dev_mode']))
		$localdir = "dev";

	$IPATH = "./$localdir" . PATH_SEPARATOR . ".";
	ini_set("include_path", $IPATH);
	set_include_path($IPATH);

	$TPL_PATH = "./$localdir/templates/$utd" . PATH_SEPARATOR . "./templates/$utd";
}


Header("Content-Type: text/html; charset=iso-8859-1");
ini_set("magic_quotes_gpc", "0");
set_magic_quotes_runtime(0);
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
ignore_user_abort(TRUE);

set_incl_path();
?>

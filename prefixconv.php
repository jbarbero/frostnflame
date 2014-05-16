<?
if(!defined("PROMISANCE"))
	die(" ");
$cookie = array();
foreach($tbl as $var => $value) {
	$name = $var.'db';
	global $$name;
	$$name = $prefix.'_'.$value;
}

foreach($_COOKIE as $var => $value) {
	$len = strlen($prefix) + 1;
	if(substr($var, 0, $len) == $prefix.'_') {
		$name = substr($var, $len);
		$cookie[$name] = $value;
	}
}
?>

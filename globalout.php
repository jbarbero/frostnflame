<?
require_once("conf-proc.php");
require_once("funcs.php");

setcookie('global_auth', '');
$_COOKIE['global_auth'] = '';

Header("Location: $config[home]");
?>

<?
require_once("conf-proc.php");
require_once("funcs.php");

auth_user();

$users[rsalt] = rand_nonce($users[rsalt]);
$users[online] = 0;
$users[hide] = 0;
saveUserData($users, "online hide rsalt", true);

setcookie($prefix.'_auth', '');
Header("Location: $config[home]");
?>

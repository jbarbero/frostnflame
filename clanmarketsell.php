<?
include("header.php");

if($users[clan] == 0)
	TheEnd("You are not in a clan!");

define('CLAN', $users[clan]);
define('CLANMKT', 1);
define('SCRIPT', 'clanmarketbuy');
define('SCRIPT2', 'clanmarketsell');

include("pubmarketsell.php");
?>

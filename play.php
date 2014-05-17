<?
require_once("funcs.php");

function EndNow ($reason) {
	HTMLbegincompact("Login");
	print "$reason<br>\n";
	HTMLendcompact();
	exit;
}

if(!auth_global())
	EndNow("You are not logged in!");

$reason = "You do not have an account on that server!";

	$users = mysqli_fetch_array(db_safe_query("SELECT * FROM $playerdb WHERE global_num='$global[num]';"));
	if (!$users[num])
		EndNow($reason);
	$usecookie = false;
	if(!empty($_POST['usecookie']))
		$usecookie = true;

	$users[rsalt] = rand_nonce($users[rsalt]);
	makeAuthCode($users[num], $users[password], 0, SERVER, $usecookie, $users[rsalt]);
	if(!$usecookie)
		setcookie($prefix.'_auth', '');

	if($hideon)
		$users[hide] = 0;
	else
		$users[hide] = 1;
	saveUserData($users, "hide rsalt", true);
	
        if($users['motd'] == 1) {
       		header(str_replace('&amp;', '&', "Location: ".$config[sitedir].$config[main]."?motd$authstr"));
       	} else {
       		header(str_replace('&amp;', '&', "Location: ".$config[sitedir].$config[main]."?game$authstr"));
       	}

?>

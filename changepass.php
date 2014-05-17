<?
require_once("header.php");

if($passchanged) {
    TheEnd("Password changed!");
} else if ($do_changepass) {
    if (($new_password) && ($new_password == $new_password_verify)) {
        $users[password] = md5($new_password);
        $users[passchanged] = 1;
        db_safe_query("UPDATE $playerdb SET password='$users[password]' WHERE num=$users[num];");
        db_safe_query("UPDATE ".$prefix."_users SET user_password='$users[password]' WHERE user_id=$users[num];");
        saveUserData($users, "passchanged", true);

        if(isset($cookie['auth']))
            makeAuthCode($users[num], $users[password], 0, SERVER, true, $users[rsalt]);
        else if(isset($_GET['auth']))
            makeAuthCode($users[num], $users[password], 0, SERVER, false, $users[rsalt]);
        else
            $printmessage = "You aren't logged in!<br>\n";

        header(str_replace('&amp;', '&', "Location: ".$config[sitedir].$config[main]."?changepass&passchanged=true$authstr"));
    }
    else    $printmessage = "Error! Passwords do not match!<br>\n";
}

$template_display('changepass.html');
TheEnd("");
?>

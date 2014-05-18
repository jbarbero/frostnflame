<?
require_once("funcs.php");
HTMLbegincompact("Signup");

EndNow("Global signups are currently disabled -- please get a WOA account first. This is a temporary measure only.");

function EndNow ($reason)
{
    print "$reason<br>\n";
    HTMLendcompact();
    exit;
}

$uera = loadEra(1,1);

$lockdb = 0;                            // need to allow DB modifications for signups
if ($do_signup) {
    if($signup_igname == '')
        $signup_igname = $signup_empire;

    $chk_u = $signup_username;
    $chk_e = $signup_empire;
    $chk_i = $signup_igname;
    $chk_m = $signup_email;
    sqlQuotes($chk_u);
    sqlQuotes($chk_e);
    sqlQuotes($chk_i);
    sqlQuotes($chk_m);

    if (!strstr($signup_name," "))
        EndNow("Sorry, you cannot signup without FULL and CORRECT information.<br>
            Everybody has a last name, and I doubt you're an exception.<br>
            Why do we ask?  Prevent cheating, that's all.");
    if (stristr($signup_name,"the "))
        EndNow("Nice try, but nobody has 'The' as part of their name.");
    if ($signup_username == "")
        EndNow("You must specify a username!");
    if ($signup_password == "")
        EndNow("You must select a password!");
    if ($signup_password != $signup_password2)
        EndNow("Passwords don't match!");
    if(!(eregi("^[_+A-Za-z0-9-]+(\\.[_+A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9-]+)*$",$signup_email,$matches)))
        EndNow("Please enter a valid E-mail address.");
    if ($signup_email != $signup_email_verify)
        EndNow("Your E-mail address does not match!");

    if (db_safe_firstval("SELECT COUNT(*) FROM global_users WHERE username='$chk_u';"))
        EndNow("Sorry, that username is already being used!");
    if (db_safe_firstval("SELECT COUNT(*) FROM global_users WHERE empire='$chk_e';"))
        EndNow("Sorry, that ".$uera[empire]." default name is already being used!");
    if (db_safe_firstval("SELECT COUNT(*) FROM global_users WHERE igname='$chk_i';"))
        EndNow("Sorry, that leader name is already being used!");

    $ip = realip();

    db_safe_query("INSERT INTO global_users (num) VALUES (NULL);");    // add a new user entry (with defaults)
    $users[num] = mysqli_insert_id($db_link);

        $users[username] = $signup_username;
        $users[name] = $signup_name;
        $users[email] = $signup_email;
        $users[IP] = $ip;

        $users[signedup] = $time;
        $users[password] = md5($signup_password);
    $users[passchanged] = 1;
    $users[rsalt] = rand_nonce(0);
    $users[disabled] = 0;
    $users[style] = 1;
    $users[empire] = htmlspecialchars(swear_filter($signup_empire), ENT_QUOTES);

    saveGlobalData($users, "username name email IP signedup password passchanged rsalt disabled style empire");

    mail($signup_email,"Signup for $config[gamename_full] - $users[empire]","
<p>Thank you for signing up with $config[gamename_full]!</p>

<p>If you did not sign up for an account with us, please let us know
and delete this message with our apologies.</p>

<hr>
<p>Please visit us at:</p>
<ul><li> <a href=\"$config[sitedir]\">$config[gamename_full]</a> </li></ul>

<p>Or, paste this URL into your web browser:</p>
<ul><li> $config[sitedir] </li></ul>

<p>Have fun playing on our servers!</p>

<hr>

<p>Be sure to check out the latest creations from us at
$config[home] and tell your friends about our great
services and games!</p>

<p>Your e-mail address shall remain strictly confidential and will NOT
be given out to anyone.</p>

<p>Should you want to reply to this e-mail, please use $config[adminemail]</p>
","From: $gamename Web Game <$config[adminemail]>
X-Mailer: FAF Global Signup Script
Reply-To: $config[adminemail]
MIME-Version: 1.0
Content-type: text/html; charset=iso-8859-1");
?>
Welcome to <?=$gamename_full?>, <b><?=$users[empire]?>!</b><br>
<a href="?login<?=$authstr?>">Home</a>
<br>

<?
    EndNow("");
}
?>


<h2><i><?=$gamename_full?></i>- Signup Form</h2>
Welcome to <?=$gamename_full?>, the first step to running your own army is
to signup!<br>
The administrators reserve the right to delete any accounts not abiding by the <a href="?rules" target="ru;es">rules</a>. We suggest you start by reading them.<br><br>
<a href="?guide&section=signup&srv=<?=SERVER?>"><?=$gamename?> Playing Guide</a>
<br>
Please make sure you sign up with the correct email address, because a login link will be sent to you via email. You cannot log in if you provide an 
invalid email address. Also note that you will not be able to change your email later.<br>
If you are confused, just click on the Guide link to access the easy start guide for signup.<br>
<form method="post" action="?globalsignup">
<table class="inputtable">
<tr><th colspan="2" style="font-size:large">Personal Information</th></tr>
<tr><th class="aright">Real Name:</th>
    <td><input type="text" name="signup_name" size="24">*</td></tr>
<tr><th class="aright">E-Mail:</th>
    <td><input type="text" name="signup_email" size="24">*</td></tr>
<tr><th class="aright">Verify E-Mail:</th>
    <td><input type="text" name="signup_email_verify" size="24">*</td></tr>
<tr><td colspan="2" style="font-size:small;text-align:center">Your personal information will remain strictly confidential.</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><th colspan="2" style="font-size:large">Game Information</th></tr>
<tr><th class="aright">Username:</th>
    <td><input type="text" name="signup_username" size="8">*</td></tr>
<tr><th class="aright">Password:</th>
    <td><input type="password" name="signup_password" size="8">*</td></tr>
<tr><th class="aright">Verify Password:</th>
    <td><input type="password" name="signup_password2" size="8">*</td></tr>
<tr><th class="aright">Default <?=$uera[empireC]?> Name:</th>
    <td><input type="text" name="signup_empire" size="24" maxlength="32">*</td></tr>
<!--<tr><th class="aright">In-Game Name:</th>
    <td><input type="text" name="signup_igname" size="24" maxlength="32"></td></tr>
-->
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
   <td><input type="reset" value="Reset"></td>
   <td class="aright"><input type="submit" name="do_signup" value="Sign Up"></td>
</tr>
</table>
</form>
<br><br>
<?
HTMLendcompact();
?>

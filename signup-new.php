<?
require_once("funcs.php");
HTMLbegincompact("Signup");

function EndNow ($reason) {
	print "$reason<br>\n";
	HTMLendcompact();
	exit;
}

$uera = loadEra(1,1);

if ($signupsclosed)
	EndNow("Sorry, the game is currently not accepting new accounts.  Please check back in a few days.");
$lockdb = 0;							// need to allow DB modifications for signups
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
  if($signup_empire == '')
    EndNow("You must have an empire name to play!");
	if(!(eregi("^[_+A-Za-z0-9-]+(\\.[_+A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9-]+)*$",$signup_email,$matches)))
		EndNow("Please enter a valid E-mail address.");
	if ($signup_email != $signup_email_verify)
		EndNow("Your E-mail address does not match!");
	if (db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE username='$chk_u';"))
		EndNow("Sorry, that username is already being used!");
	if (db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE empire='$chk_e';"))
		EndNow("Sorry, that ".$uera['empire']." name is already being used!");
	if (db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE igname='$chk_i';"))
		EndNow("Sorry, that leader name is already being used!");
    if($signup_password !=  $signup_password2)
        EndNow("Sorry, your password could not be verified!");

	$ip = realip();
	$multi = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE ((email='$chk_m' OR IP='$ip') AND land>0 AND disabled<2 AND ismulti=0);");
	if($multi < $config['multi_max'])
		$multi = 0;

	db_safe_query("INSERT INTO $playerdb (num) VALUES (NULL);");	// add a new user entry (with defaults)
	$users = loadUser(mysql_insert_id());				// and load it

	$users['username'] = $signup_username;
	$users['name'] = $signup_name;
    $users['password'] = md5($signup_password);
	$users['email'] = $signup_email;
	$users['email'] = mysqli_real_escape_string($db_link, $users['email']);
	$users['IP'] = $ip;

	$users['folders'] = 'Inbox|Sent';
	$users['signedup'] = $time;
	if ($users['num'] == 1)
		$users['disabled'] = 2;
	else	$users['disabled'] = 0;
//  users choose own passwords	
//	$valcode = md5($users[username].mt_rand().$users[email]);// should be fairly unique
//	$password = substr($valcode, 0, 7);
//	$users[password] = md5($password);

	$users['idle'] = $time;
	$users['empire'] = htmlspecialchars(swear_filter($signup_empire), ENT_QUOTES);
	fixInputNum($signup_race);
	fixInputNum($config['default_era']);

	$users['race'] = $signup_race;
	$users['era'] = $config['default_era'];

	$users['rank'] = $users['num'];

	$users['igname'] = htmlspecialchars(swear_filter($signup_igname), ENT_QUOTES);
	$users['igname'] = mysqli_real_escape_string($db_link, $users[igname]);

	$users['turns'] = $config['initturns'];

	$users['msgtime'] = $time;
	$users['newstime'] = $time;

	$users['clan'] = 0;
	$users['style'] = $config['default_style'];

	//set default values for troops, industry, pmkt, and bmper
	$users['troop'] = array();
	$users['bmper'] = array();
	$users['pmkt'] = array();
	$users['ind'] = array();
	$users['stocks'] = "1000" . str_repeat("|1000", db_safe_firstval("SELECT count(*) FROM $stockdb;")-1);
	$indtotal = 0;
	$ind_each = floor(100/count($config['troop']));
	$bmper = 0;
	$pmktbase = 5000000;
	foreach($config['troop'] as $num => $mktcost) {
		$users['troop'][$num] = 0;
		$users['bmper'][$num] = $bmper;
		$users['pmkt'][$num] = round($pmktbase/$config['troop'][$num]);
		$users['ind'][$num] = $ind_each;
		$indtotal += $ind_each;
	}
	$users['ind'][$num] += 100 - $indtotal;

	if($config['duels_mode']) {
		$users['forces'] = 11;
	}

	if($signup_clan != 0) {
		$sclan = loadClan($signup_clan);
        if($sclan['open'] == 0)
            TheEnd("That clan is closed!");
        if ($sclan['members'] >= (5+((db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE land>0 AND disabled!=2 AND disabled!=3;")/20))))
            TheEnd("That clan is currently full. When more players join the game or clan members leave the clan, you may join.");
        $users['clan'] = $signup_clan;
        $users['forces'] = 0;
        $users['allytime'] = $time;
        $sclan['members']++;
        saveClanData($sclan, "members");
        saveUserData($users, "clan forces allytime");
        addNews(112, array(id1=>$sclan[founder], clan1=>$sclan[num], id2=>$users[num]));
	}

	$uclan = loadClan($users['clan']);
	$urace = loadRace($users['race'], $users['era']);

	if($multi)
		$users['disabled'] = 3;

	saveUserData($users,"igname networth username password name email IP signedup disabled idle empire race era rank turns msgtime newstime clan folders stocks troops pvmarket production bmper disabled");
	db_safe_query("UPDATE $stockdb SET total_held=total_held+1000 WHERE 1=1;");

	db_safe_query("INSERT INTO $prefix"."_users (user_id, username, user_regdate, user_password, user_email, user_interest, user_viewemail, user_sorttopics) VALUES
		 ($users[num], '$users[igname]', '0000-00-00 00:00:00', '$users[password]', '$users[email]', 'none', '0', '0');");

	mail($users['email'],"Signup for $config[servname] - $users[empire]","
Thank you for signing up for $config[servname]!

If you did not sign up for an account with us please delete this message with our apologies.

---
Here is your login information
Username: $signup_username
Password: $signup_password

You may change your password after you log in at the Manage screen.

---

Be sure to check out the latest creations from us at
$config[home] and tell your friends about our great
services and games!

Your e-mail address shall remain strictly confidential and will NOT
be given out to anyone.  We respect your rights to privacy!

Should you want to reply to this e-mail, please use $config[adminemail]
","From: $gamename Web Game <$config[adminemail]>\nX-Mailer: Medieval Empires");

//update ranks
$userslist = db_safe_query("SELECT num FROM $playerdb WHERE disabled != 2 AND disabled !=3 AND land>0 ORDER BY networth DESC;");
$urank = 0;
while ($user = mysqli_fetch_array($userslist))
{
        $urank++;
        db_safe_query("UPDATE $playerdb SET rank=$urank WHERE num=$user[num];");
}
        $urank++;
        db_safe_query("UPDATE $playerdb SET rank=$urank WHERE disabled=3 OR disabled=2 OR land=0;");


?>

Welcome to <?=$gamename?> :: <?=$config['servname']?>, <b><?=$users['empire']?>
(#
<?=$users['num']?>
)</b>!<br>
<a href="?login<?=$authstr?>">Log In</a>
<br><br>
<big><b>*** Accounts are marked DEAD after 7 days of inactivity ***</b></big>
<?
	EndNow("");
}
?>
<table class="inputtable">
<tr><th><h2>Basic Rules</h2></th></tr>
<tr><th style="font-size:large">Multiple Accounts</th></tr>
<tr><td class="acenter">
    Multiple accounts <b><font color="red">ARE NOT PERMITTED</font></b> in this game, including sharing acounts and baby-sitting.<br>
    If anyone is found using multiple accounts, all of the accounts in question will be disabled.<br>
    <b><font color="yellow">If you have a special situation (e.g. family member plays too), you must contact an <a href="mailto: dvd871@gmail.com">administrator.</a></font></b></td></tr>
<tr><th style="font-size:large">Server Abuse</th></tr>
<tr><td class="acenter">
    Foul language, hacking, porn, warez, etc. are not permitted on this server<br>offenders will be deleted and banned.</td></tr>
<tr><th style="font-size:large">Automated Bots</th></tr>
<tr><td class="acenter">
    Automated methods of login and playing are not permitted on this server<br>offenders will be deleted and banned without warning.</td></tr>
<tr><th style="font-size:large">Exploiting of Bugs</th></tr>
<tr><td class="acenter">
    Exploiting of any bugs in the games will be grounds for banning without warning.<br>Report all bugs!</td></tr>

<tr><th style="font-size:large">Technical Support</th></tr>
<tr><td class="acenter">
    If you have any problems in this game and must send e-mail to an administrator<br>
    you <b>MUST include all of the following</b> in your e-mail message:<br><br>
    1. The game in which you are having a problem (<?=$config[servname]?>).<br>
    2. The name and number of all empires involved (i.e. your empire).<br>
    3. The nature of the problem.<br><br></td></tr>
<tr><th>&nbsp;</th></tr>
</table><br>

<h2><i><?=$gamename?> :: <?=$config[servname]?></i>- Signup Form</h2>
Welcome to <?=$gamename?>, the first step to running your own army is to signup!<br>
The administrators reserve the right to delete any accounts not abiding by the <a href="?rules" target="ru;es">rules</a>. We suggest you start by reading them.<br><br>
<a href="?guide&section=signup&srv=<?=SERVER?>"><?=$gamename?> Playing Guide</a>
<br>
Please make sure you sign up with the correct email address, because your password will be sent to you via email.<br>
You cannot log in if you provide an invalid email address. Also note that you will not be able to change your email later.<br>
If you are confused, just click on the Guide link to access the easy start guide for signup.<br>
<form method="post" action="?signup&srv=<?=SERVER?>">
<table class="inputtable">
<tr><th colspan="2" style="font-size:large">Personal Information</th></tr>
<tr><th class="aright">Name:</th>
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
<tr><th class="aright"><?=$uera[empireC]?> Name:</th>
    <td><input type="text" name="signup_empire" size="24" maxlength="32">*</td></tr>
<tr><th class="aright">In-Game Name:</th>
    <td><input type="text" name="signup_igname" size="24" maxlength="32"></td></tr>
<tr><th class="aright">Password:</th>
    <td><input type="password" name="signup_password" size="10">*</td></tr>
<tr><th class="aright">Verify Password:</th>
    <td><input type="password" name="signup_password2" size="10">*</td></tr>
<tr><th class="aright">Your Race: (<a href="?guide&section=races&srv=<?=SERVER?>" target="races">Help</a>)</th>
    <td><select name="signup_race" size="1">
<?
foreach($rtags as $id => $race) {
?>
    <option value="<?=$id?>"<?if ($id == 1) print " selected";?>><?=$race?></option>
<?
}
?>
	</select></td></tr>
<tr><th class="aright">Location: (<a href="?guide&section=eras&srv=<?=SERVER?>" target="races">Help</a>)</th>
    <td><select name="signup_era" size="1">

<?
foreach($etags as $num => $era) {
?>
	<option value="<?=$num?>"<?if ($num == $config['default_era']) print "selected";?>><?=$era?></option>
<?
}
?>
    </select></td></tr>
<tr><th class="aright">Clan:</th>
<?
$clans = db_safe_query("SELECT num,tag,name FROM $clandb WHERE open=1 AND members>0;");
if(@mysqli_num_rows($clans) == 0)
	echo '<td><b>No open Clans</b></td></tr>';
else {
	echo '<td><select name="signup_clan" size="1"><option value="0">No Clan Selected Yet</option>';

	while ($clan = mysqli_fetch_array($clans)) {
		echo "<option value='$clan[num]'>$clan[tag] - $clan[name]</option>";
	}
	echo '</select></td></tr>';
}
?>
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

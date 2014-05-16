<?
include("header.php");
include("lib/msgcron.php");

$search = array("\'", '\"');
$replace = array("'", '"');

$folders = explode("|", $users['folders']);
$inbox = $folders[0];
$sent = $folders[1];

$tpl->assign('inboxname', $inbox);
$tpl->assign('sentname', $sent);

function message ($msg_body, $msg_title, $msg_dest, $msg_replyto) {
	global $time, $users, $messagedb, $playerdb, $allclan, $search, $replace, $authstr, $config;
	$msg_title = trim($msg_title);
        if($msg_title == "")
		$msg_title = "No Title"; //hmm'
        //echo "Wow we are in the function... so right anyways: $msg_body $msg_title $msg_dest $messagedb";
	$output = '';

    	// Clean up the messages.

		$msg_body= wordwrap($msg_body, 75, "\n", 1);

		if($allclan) {
				if($users[msgcred] < 5)
					TheEnd("You need at least ".$config['clan_msgcred']." message credits. Please wait a few minutes.");

				$warlords = db_safe_query("SELECT num,empire FROM $playerdb WHERE clan=$users[clan] AND num!=$users[num] AND land>0 AND disabled !=2 AND disabled != 3 ORDER by empire DESC;");
				$msg_title .= " (To Clan)";
				while ($warlord = mysql_fetch_array($warlords)) {
					makeMsg($time, $users[num], $warlord[num], $msg_body, $msg_title);
					$output .= "Your message has been sent to $warlord[empire] <a class=proflink href=?profiles&num=$warlord[num]$authstr>(#$warlord[num])</a><br>";
				}
				$users[msgcred] -= $config['clan_msgcred'];
				saveUserData($users,"msgcred");
				echo "Message sent to your clan!<br>";
		} else {

			if ($users[msgcred] == 0 && empty($msg_replyto))
				TheEnd("You have run out of credits. Please wait a few minutes.");

			if (($msg_replyto)) {
				fixInputNum($msg_replyto);
				db_safe_query("UPDATE $messagedb SET replied=1 WHERE id=$msg_replyto;");
			}

			else {
				$users[msgcred]--;
				saveUserData($users,"msgcred");
			}

			if(!$msg_dest_num) $msg_dest_num = $msg_dest;
			fixInputNum($msg_dest_num);
			$name = db_safe_firstval("SELECT empire FROM $playerdb WHERE num=$msg_dest_num;");
			makeMsg($time, $users[num], $msg_dest_num, $msg_body, $msg_title);
			$output .= "Your message has been sent to $name <a class=proflink href=?profiles&num=$msg_dest_num$authstr>(#$msg_dest_num)</a><br>";

			$aimmsg = "You have received a message from $users[igname] entitled \"$msg_title\"!";
			aim_notify($msg_dest_num, $aimmsg);
		}
	return $output;
}






if ($lockdb)
	TheEnd("Messaging is currently disabled!");

$numbers = 0;

if($do_forward)
{
	$forward_msg = str_replace("<br />", "", $msg_body);
	//$forward_msg = str_replace("\n", "\n&gt;", $forward_msg);
	$from_name = loadUser($msg_src);
	$forward_msg = "\n\n\n[quote= $from_name[empire]]\n" . $forward_msg . "\n[/quote]";
	$forward_title = "Fwd ($msg_src_name): $msg_title";
	$warquery = "SELECT num, empire, land, disabled, clan FROM $playerdb WHERE land>0 AND disabled != 3 ORDER BY empire";
	$warquery_result = @db_safe_query($warquery);
	$warquery_array = array();
	$warquery_array[] = array('empire' => 'None');
	while ($wardrop = @mysql_fetch_array($warquery_result)) {
					$color = "normal";
					if ($wardrop[num] == $users[num])
						$color = "self";
					elseif ($wardrop[land] == 0)
						$color = "dead";
					elseif ($wardrop[disabled] == 2)
						$color = "admin";
					elseif ($wardrop[disabled] == 3)
						$color = "disabled";
					elseif (($users[clan]) && ($wardrop[clan] == $users[clan]))
						$color = "ally";

			$warquery_array[] = array('num' => $wardrop['num'], 'color' => $color, 'empire' => $wardrop['empire']);
	}
	$numbers = $warquery_array;
}

if($send_forward)
{
	/* I'm checking so you can't spam someone...*/
	//echo "$forward_num1 - $forward_num2 - $forward_num3 !!!";
	if($forward_num2 == $forward_num1) $forward_num2 = -1;
	if($forward_num3 == $forward_num2 || $forward_num3 == $forward_num1) $forward_num3 = -1;

	if($forward_num1 != "" && $forward_num1 != -1) message($msg_body, $msg_title, $forward_num1, 0);
	if($forward_num2 != "" && $forward_num2 != -1) message($msg_body, $msg_title, $forward_num2, 0);
	if($forward_num3 != "" && $forward_num3 != -1) message($msg_body, $msg_title, $forward_num3, 0);
	print "Messages sent!<hr>";
}


if ($do_message)
{
	if(empty($msg_dest))
		TheEnd("Please specify the recipient of your message!");
	echo message($msg_body, $msg_title, $msg_dest, $msg_replyto);

}
/* Made into a function because it is just a heck of a lot easier for me. */



	//End Do Message (Goodness that was a nightmare to fix all the trailing/poor indentation!!!!

$replyid = 0;
if ($do_reply)
{
	$replyid = $msg_id;
	$replysrc = $msg_src;
	$replyname = loadUser($msg_src);
	$replyname = $replyname[empire];
	$replytitle = $msg_title;
	if(strtolower(substr($replytitle, 0, 3)) == "re:")
		$replytitle = trim(substr($replytitle, 3));
	$replybody = str_replace("<br />", "", $msg_body);
	$from_name = loadUser($msg_src);
	$replybody = "\n\n\n[quote= $from_name[empire]]\n" . $replybody . "\n[/quote]";

}

if ($do_delete) {
	fixInputNum($msg_id);
	db_safe_query("UPDATE $messagedb SET deleted=1 WHERE deleted=0 AND id=$msg_id AND dest=$users[num];");
	db_safe_query("UPDATE $messagedb SET deleted=3 WHERE deleted=2 AND id=$msg_id AND dest=$users[num];");
	print "Message deleted!<hr>\n";
}
if ($do_deleteall && $php_block!="No")
{
	if(!$jsenabled && $php_block!="Yes") {
		?>
		Are you sure you want to delete all messages?
		<form method="post" action="?messages<?=$authstr?>">
			<input type="submit" name="php_block" value="Yes">
			<input type="submit" name="php_block" value="No">
			<input type="hidden" name="do_deleteall" value="1">
		</form>
		<?
		TheEnd("");
	}
			
	db_safe_query("UPDATE $messagedb SET deleted=1 WHERE deleted=0 AND dest=$users[num];");
	db_safe_query("UPDATE $messagedb SET deleted=3 WHERE deleted=2 AND dest=$users[num];");
	print "All messages deleted!<hr>\n";
}

if ($do_delete_read) {
	db_safe_query("UPDATE $messagedb SET deleted=1 WHERE deleted=0 AND dest=$users[num] AND readd=1;");
	db_safe_query("UPDATE $messagedb SET deleted=3 WHERE deleted=2 AND dest=$users[num] AND readd=1;");
	print "All read messages deleted!<hr>\n";
}

if ($do_delete_selected)
{
	$i = 0;
	while($boxes[$i] != "") {
	//echo "i = $i - $boxes[$i] = boxes[i]";
		fixInputNum($boxes[$i]);
		$msg = db_safe_query("SELECT * FROM $messagedb WHERE id=$boxes[$i];");
		$message = mysql_fetch_array($msg);
		if($users[num] != $message[dest]) TheEnd("You can't delete another person's messages.");
		db_safe_query("UPDATE $messagedb SET deleted=1 WHERE deleted=0 AND dest=$users[num] AND id=$boxes[$i];");
		db_safe_query("UPDATE $messagedb SET deleted=3 WHERE deleted=2 AND dest=$users[num] AND id=$boxes[$i];");
		$i++;

	}
	print "Messages deleted.";

}

if ($view!="")
{

  fixInputNum($view);
  $msg = db_safe_query("SELECT * FROM $messagedb WHERE id=$view;");

  $vmessage = mysql_fetch_array($msg);
  if($users[num] != $vmessage[dest]) TheEnd("That's not your message.");
  $vsrc = loadUser($vmessage[src]);
  $vmessage['msg_escaped'] = swear_filter($vmessage[msg]);
  $vmessage[msg] = bbcode_parse($vmessage[msg]);
  $vmessage[title] = swear_filter(htmlentities($vmessage[title], ENT_QUOTES));
  //$vmessage[msg] = wordwrap($vmessage[msg], 75, "<br />", 1);
 // $vmessage[msg] = wordwrap($vmessage[msg], 120, "<br />", 1);
  //print_r($vmessage);
  db_safe_query("UPDATE $messagedb SET readd=1 WHERE id=$vmessage[id];");

}

if(!$asc) $asc = 0;

$title_order = 1;
$from_order = 1;
$date_order = 0;

if(!$order_by) $order_by = 'date';

if($order_by == 'title' && $asc == 1) $title_order = 0;
if($order_by == 'from' && $asc == 1) $from_order = 0;
if($order_by == 'date' && $asc == 0) $date_order = 1;

if($order_by == 'date') $order_by = 'time';
else if($order_by == 'from') $order_by = 'src';
else $order_by = 'time';


if($asc) $asc = "ASC";
if(!$asc) $asc = "DESC";




$msgs = db_safe_query("SELECT * FROM $messagedb WHERE dest=$users[num] AND (deleted=0 OR deleted=2) ORDER BY $order_by $asc;");
$num_msgs =@mysql_num_rows($msgs);
$jmessage = array();
if( @mysql_num_rows($msgs))
{
	$message_array = array();
	//echo "<h3>Messages:</h3><br><table style=\"width:90%\">";
	while ($message = mysql_fetch_array($msgs))
	{

		$enemy = loadUser($message[src]);

  		$message['msg_escaped'] = htmlentities($message[msg]);
  		$message[msg_escaped] = str_replace("\n", "", $message[msg_escaped]);

		$message[msg] = str_replace("<br>", "", $message[msg]);
		$message[msg] = bbcode_parse($message[msg]);

		$message[msg] = str_replace("\n", "", $message[msg]);
		$message[msg] = str_replace("\r", "", $message[msg]);
		//$message[msg] = wordwrap($message[msg], 75, "<br />", 1);


		$message[title] = swear_filter(htmlentities($message[title], ENT_QUOTES));
		$message[date] = date($dateformat,$message[time]);

		$message_array[] = array('msg_escaped' => $message[msg_escaped], 'msg' => $message['msg'], 'id' => $message['id'], 'read' => $message['readd'], 'title' => $message['title'], 'from_name' => $enemy['empire'], 'from_num' => $enemy['num'], 'date' => $message[date]);
	}

}
//else	print "No new messages...<hr>\n";

$users[msgtime] = $time;
saveUserData($users,"msgtime");

$warquery_array = array();


$warquery = "SELECT num, empire, land, disabled, clan FROM $playerdb WHERE land>0 AND disabled != 3 AND land>0 ORDER BY empire";
$warquery_result = @db_safe_query($warquery);
while ($wardrop = @mysql_fetch_array($warquery_result)) {
				$color = "normal";
				if ($wardrop[num] == $users[num])
					$color = "self";
				elseif ($wardrop[land] == 0)
					$color = "dead";
				elseif ($wardrop[disabled] == 2)
					$color = "admin";
				elseif ($wardrop[disabled] == 3)
					$color = "disabled";
				elseif (($users[clan]) && ($wardrop[clan] == $users[clan]))
					$color = "ally";
		$warquery_array[] = array('num' => $wardrop['num'], 'color' => $color, 'name' => $wardrop['empire']);
}
$tpl->assign('do_forward', $do_forward);
$tpl->assign('forward_title', $forward_title);
$tpl->assign('forward_msg', $forward_msg);
$tpl->assign('numbers', $numbers);
$tpl->assign('do_reply', $do_reply);
$tpl->assign('view', $view);
$tpl->assign('vsrc', $vsrc);
$tpl->assign('time', date($dateformat,$vmessage[time]));
$tpl->assign('msgcreds', $users[msgcred]);
$tpl->assign('vmessage', $vmessage);
$tpl->assign('clan', $users[clan]);
$tpl->assign('num_msg', $num_msgs);
$tpl->assign('message', $message_array);
$tpl->assign('jmessage', $message_array);
$tpl->assign('title_order', $title_order);
$tpl->assign('from_order', $from_order);
$tpl->assign('prof_target', $prof_target);
$tpl->assign('date_order', $date_order);
$tpl->assign('reply_body', $replybody);
$tpl->assign('reply_name', $replyname);
$tpl->assign('prof_target', $prof_target);
$tpl->assign('reply_id', $replyid);
$tpl->assign('reply_src', $replysrc);
$tpl->assign('color', $users[era]);
$tpl->assign('reply_title', $replytitle);
$tpl->assign('drop', $warquery_array);
$tpl->assign('sent', 0); // VITAL!
$tpl->assign('uera', $uera);

$tpl->display('messages.html');


TheEnd("");
?>


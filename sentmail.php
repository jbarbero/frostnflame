<?
include("header.php");


$search = array("\'", '\"');
$replace = array("'", '"');


$folders = explode("|", $users['folders']);
$inbox = $folders[0];
$sent = $folders[1];

if ($lockdb)
    TheEnd("Messaging is currently disabled!");

if ($do_revoke)
{
    fixInputNum($msg_id);
    db_safe_query("UPDATE $messagedb SET deleted=1 WHERE deleted=0 AND id=$msg_id AND src=$users[num];");
    print "Message revoked!<hr>\n";
}

if ($do_delete)
{
    fixInputNum($msg_id);
    db_safe_query("UPDATE $messagedb SET deleted=3 WHERE deleted=1 AND id=$msg_id AND src=$users[num];");
    db_safe_query("UPDATE $messagedb SET deleted=2 WHERE deleted=0 AND id=$msg_id AND src=$users[num];");
}
if ($do_deleteall)
{
    db_safe_query("UPDATE $messagedb SET deleted=3 WHERE deleted=1 AND src=$users[num];");
    db_safe_query("UPDATE $messagedb SET deleted=2 WHERE deleted=0 AND src=$users[num];");
    print "All messages deleted!<hr>\n";
}

if ($do_delete_selected)
{
    $i = 0;
    while($boxes[$i] != "") {
    //echo "i = $i - $boxes[$i] = boxes[i]";
        fixInputNum($boxes[$i]);
        $msg = db_safe_query("SELECT * FROM $messagedb WHERE id=$boxes[$i];");
        $message = mysqli_fetch_array($msg);
        if($users[num] != $message[src]) TheEnd("You can't delete another person's messages.");
        db_safe_query("UPDATE $messagedb SET deleted=3 WHERE deleted=1 AND src=$users[num] AND id=$boxes[$i];");
        db_safe_query("UPDATE $messagedb SET deleted=2 WHERE deleted=0 AND src=$users[num] AND id=$boxes[$i];");
        $i++;

    }
    print "Messages deleted.";

}


if ($view!="")
{
  fixInputNum($view);
  $msg = db_safe_query("SELECT * FROM $messagedb WHERE id=$view;");

  $vmessage = mysqli_fetch_array($msg);
  if($users[num] != $vmessage[src]) TheEnd("That's not your message.");
  $vsrc = loadUser($vmessage[dest]);
  $vmessage['msg_escaped'] = swear_filter($vmessage[msg]);
  $vmessage[msg] = bbcode_parse($vmessage[msg]);
  $vmessage[title] = swear_filter(htmlentities($vmessage[title], ENT_QUOTES));
}

if(!$asc) $asc = 0;

$title_order = 1;
$to_order = 1;
$date_order = 0;

if(!$order_by) $order_by = 'date';

if($order_by == 'title' && $asc == 1) $title_order = 0;
if($order_by == 'to' && $asc == 1) $to_order = 0;
if($order_by == 'date' && $asc == 0) $date_order = 1;

if($order_by == 'date') $order_by = 'time';
else if($order_by == 'to') $order_by = 'dest';
else $order_by = 'time';

if($asc) $asc = "ASC";
if(!$asc) $asc = "DESC";



$msgs = db_safe_query("SELECT * FROM $messagedb WHERE src=$users[num] AND (deleted=0 OR deleted=1) ORDER BY $order_by $asc;");
$num_msgs =@mysqli_num_rows($msgs);
if( @mysqli_num_rows($msgs))
{

        while ($message = mysqli_fetch_array($msgs))
        {
            $enemy = loadUser($message[dest]);
                    $message[msg] = bbcode_parse($message[msg]);
                    $message[title] = swear_filter(htmlentities($message[title], ENT_QUOTES));

            $message_array[] = array('id' => $message['id'], 'read' => $message['readd'], 'title' => $message['title'], 'to_name' => $enemy['empire'], 'to_num' => $enemy['num'], 'date' => date($dateformat,$message[time]), 'deleted' => $message[deleted]);

        }


}

$do_reply = 0;
$time = date($dateformat,$vmessage[time]);
$smessage = $vmessage;
$sent_message = $message_array;

$warquery_array = array();
$sent = 1; // VITAL!

template_display('messages.html');

TheEnd("");
?>

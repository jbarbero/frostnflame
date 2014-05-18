<?
include("header.php");

$state = 1;
if($send_msg) {
    $warlords = db_safe_query("SELECT * FROM $playerdb ORDER BY networth DESC;");

    while ($warlord = mysqli_fetch_array($warlords)) {
//        if ($warlord[land] > 0 && $warlord[disabled] != 3) {
            $time = date($dateformat);
            $sb = str_replace("%n",$warlord[igname],$send_body);
            $ss = str_replace("%n",$warlord[igname],$send_subj);
            $sb = str_replace("%d",$time,$sb);
            $ss = str_replace("%d",$time,$ss);

            $body = "This message was sent to all players by $users[empire].\n-----\n\n$sb";

            makeMsg(time(), $users[num], $warlord[num], $body, $ss);
//        }
    }

    // something for game message here
    $state = 2;
}

$whatsend = 'Message';
template_display('newsletter.html');
TheEnd();
?>

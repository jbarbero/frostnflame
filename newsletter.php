<?
include("header.php");

$tpl->assign('state', 1);
if($send_msg) {
	$warlords = db_safe_query("SELECT * FROM $playerdb ORDER BY networth DESC;");

	while ($warlord = mysqli_fetch_array($warlords)) {
//		if ($warlord[land] > 0 && $warlord[disabled] != 3) {
			$time = date($dateformat);
			$sb = str_replace("%n",$warlord[igname],$send_body);       
			$ss = str_replace("%n",$warlord[igname],$send_subj);       
			$sb = str_replace("%d",$time,$sb);              
			$ss = str_replace("%d",$time,$ss);              

			mail(	$warlord[email],
				$config[gamename].' :: '.$config[servname].' Newsletter: '.$ss,
				$sb,
				"From: $gamename Web Game <$config[adminemail]>\r\n"."Reply-To: $config[adminemail]\r\n"."X-Mailer: Frost And Flame Newsletter Mailer"
			);
//		}
	}

	// something for game message here
	$tpl->assign('state', 2);
}

$tpl->assign('whatsend', 'Newsletter');
$tpl->display('newsletter.html');
TheEnd();
?>

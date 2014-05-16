<?php
require_once("funcs.php");
if (auth_user(true))
	require_once("header.php");
else	{
	htmlbegincompact("Player Profiles");
	$users[era] = 1;
}

	// User details handling

	if(empty($num))		// _GET or _POST ok
		$enemy = mysql_fetch_array(db_safe_query("SELECT * FROM $playerdb WHERE land>0 AND disabled != 2 AND disabled != 3 ORDER BY rank LIMIT 0,1;"));
	else {
		fixInputNum($num);
		$enemy = loadUser($num);
	}

	if ($enemy[profile] == "") $enemy[profile] = "No profile";
	$enemy[profile] = bbcode_parse(trim($enemy[profile]));

	//$enemy[profile] = wordwrap($enemy[profile], 75, "<br />", 1); I wrap when you save so all your smilies are not belong to wrap
	$enemy[networth] = commas($enemy[networth]);
	$enemy[land] = commas($enemy[land]);
	$race = loadRace($enemy[race], $enemy[era]);
	$era = loadEra($enemy[era], $enemy[race]);

//	if(!$enemy[aim_public]) $enemy[aim] = "Not Public";
//	if(!$enemy[msn_public]) $enemy[msn] = "Not Public";
	if(!$enemy[pub_email]) $enemy[pub_email] = "Not Public";



	if ($enemy[offtotal]) {
	    $offsuccpercent = round($enemy[offsucc]/$enemy[offtotal]*100);
	} else {
	    $offsuccpercent = 0;
	}

	if ($enemy[deftotal]) {
	    $defsuccpercent = round($enemy[defsucc]/$enemy[deftotal]*100);
	} else {
	    $defsuccpercent = 0;
	}

	// Clan Handling


	// Color

		$mclan = loadClan($users[clan]);

		$ccolor = "mnormal";
		if (($enemy[clan] == $mclan[ally1]) || ($enemy[clan] == $mclan[ally2]) || ($enemy[clan] == $mclan[ally3]) || ($enemy[clan] == $mclan[ally4]) || ($enemy[clan] == $mclan[ally5])) {
			$ccolor = "mally";
		} else if (($enemy[clan] == $mclan[war1]) || ($enemy[clan] == $mclan[war2]) || ($enemy[clan] == $mclan[war3]) || ($enemy[clan] == $mclan[war4]) || ($enemy[clan] == $mclan[war5])) {
			$ccolor = "mdead";
		}
		if(!$enemy[clan]) $ccolor="mnormal";

	$ctags = loadClanTags();
	$uclan = loadClan($enemy[clan]);
	if($uclan[name]=="") {
		$uclan[name] = "No Clan";
		$uclan[num] = 0;
	}
	$tags = array($ctags["$uclan[ally1]"], $ctags["$uclan[ally2]"], $ctags["$uclan[ally3]"], $ctags["$uclan[ally4]"], $ctags["$uclan[ally5]"], $ctags["$uclan[war1]"], $ctags["$uclan[war2]"], $ctags["$uclan[war3]"], $ctags["$uclan[war4]"], $ctags["$uclan[war5]"]);

	$z = 0;
	while($z<sizeof($tags)) {
		if($tags[$z] == "") $tags[$z] = "None";
		$z++;
	}


	$enemy[signedup] = date($dateformat, $enemy[signedup]);
	
	$loggedin = false;
	if($users[num])
		$loggedin = true;
	$tpl->assign('loggedin', $loggedin);
	$tpl->assign("do_view", "true");
	$tpl->assign("enemy", $enemy);
	$tpl->assign("urace", $race);
	$tpl->assign("ccolor", $ccolor);
	$tpl->assign("uera", $era);
	$tpl->assign("clan", $uclan);
	$tpl->assign("off_percent", $offsuccpercent);
	$tpl->assign("def_percent", $defsuccpercent);
	$tpl->assign("tags", $tags);
	$tpl->assign("authstr", $authstr);


$warquery = "SELECT num, empire, land, disabled, clan FROM $playerdb WHERE disabled != 3 ORDER BY rank;";
$warquery_result = @db_safe_query($warquery);
while ($wardrop = @mysql_fetch_array($warquery_result)) {
		if (($wardrop[land] > 0) && ($wardrop[num] != 1)) {
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
}


$tpl->assign("selected", $enemy[num]);
$tpl->assign("drop", $warquery_array);
$tpl->assign("users", $users);
$tpl->assign("uera", $uera);
$tpl->assign("eera", $era);
$tpl->assign("erace", $race);
$tpl->display('profiles.html');


        $search_num = $enemy['num'];
        $search_limit = 20;
        $do_search = true;
        $crier = true;
        require_once("news.php");

	TheEnd("");

?>

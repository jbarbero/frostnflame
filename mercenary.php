<?
include("header.php");

if ($users[disabled] == 2)
	TheEnd("Administrative accounts cannot set bounties!");

$min = 0;
$view="true";
$max_mode = .20;
//$cash = 100;

// Max number of bounties
$max = 5;
//$max_edits = 5;

if($users[num_bounties] < 0) $users[num_bounties] = 0;
if($users[num_bounties] > $max) $users[num_bounties] = $max;


//Transaction fee percent
$percent = 5;
$percent /=100;

$cansend = array();
foreach($users[troop] as $num => $amt) {
	$cansend[] = $amt*0.20;
}
$cansend[food] = round($users[food]*.20);
$cansend[cash] = round($users[cash]*.20);
$cansend[runes] = round($users[runes]*.20);


$uclan = loadClan($users[clan]);


	if($users[clan])
	        $warquery = "SELECT num, empire, land, disabled, clan FROM $playerdb WHERE land>0 AND disabled != 3 AND disabled != 2 AND clan != $users[clan] ORDER BY rank";
	else
	        $warquery = "SELECT num, empire, land, disabled, clan FROM $playerdb WHERE land>0 AND disabled != 3 AND disabled != 2 ORDER BY rank";
        $warquery_result = @db_safe_query($warquery);
        $warquery_array = array();
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
	$tpl->assign('drop', $warquery_array);

if($do_recalc)
{
	recalcBounties();
	echo "Bounties recalculated.<br><hr><br>";

}


if($do_set)
{
	$btime = time();

	$land_drop = $_POST['land_drop'];
	$rank_drop = $_POST['rank_drop'];
	$net_drop = $_POST['net_drop'];

	if(!empty($_POST['land_drop'])) fixInputNum($land_drop);
	if(!empty($_POST['rank_drop'])) fixInputNum($rank_drop);
	if(!empty($_POST['net_drop'])) fixInputNum($net_drop);
	fixInputNum($targ);
	fixInputNum($all);
	fixInputNum($anon);

	fixInputNum($cash_give);
	fixInputNum($rune_give);
	fixInputNum($food_give);
	$cash_give = invfactor($cash_give);
	$food_give = invfactor($food_give);
	$rune_give = invfactor($rune_give);

	//the input should be like name="troop_give[0]" etc.
	foreach($config[troop] as $num => $mktcost) {
		fixInputNum($troop[$num]);
		$troop[$num] = invfactor($troop[$num]);
	}



	if(!isset($targ)) TheEnd("You can not set a bounty on no one.");

	$target = loadUser($_POST['targ']);

	if(!$target[num]) TheEnd("You can not set a bounty on someone who does not exist.");
	if($target[land] < 1 || $target[disabled] ==2 || $target[disabled] == 3) TheEnd("You may not set a bounty on that person.");



	$tar_clan = $target[clan];
	print "<!--" . bountyCount($target[num], $users[num]) . "-->";
	if(bountyCount($target[num], $users[num]) > 0) TheEnd("You can not have more than one bounty on a person at the same time. If you would like to add more to your bounty, please edit it");

	if($users[num_bounties] == $max) TheEnd("You have the max number of bounties set already.");

	$users[num_bounties]++;


	if ($target[clan] != 0 && $tar_clan != 0 && $users[clan]!=0 && ($uclan[ally1] == $target[clan]) || ($uclan[ally2] == $target[clan]) || ($uclan[ally3] == $target[clan]) || ($uclan[ally4] == $target[clan]) || ($uclan[ally5] == $target[clan]))
		if($target[clan]!=0)
			TheEnd("You cannot set a bounty on an ally.");

	if($cash_give > $cansend[cash] || $food_give > $cansend[food] || $rune_give > $cansend[runes])
		TheEnd("You can not set a bounty for that much!");

	if ($target[clan] == $users[clan] && $users[clan] != 0 && $target[clan]!=0)
	        TheEnd("You may not set a bounty on someone in your clan.");

	$givenothing = true;

	foreach($config[troop] as $num => $mktcost) {
		if($troop[$num] > $users[troop][$num])
			TheEnd("The mercenaries do not think it wise of you to try to pay more than you have.");
		if($troop[$num] < 0)
			TheEnd("The mercenaries do not understand why you are trying to give a negative amount.");
		if($troop[$num] > $cansend[$num])
			TheEnd("You can not set a bounty for that much!");
		if($troop[$num] > 0)
			$givenothing = false;
	}

	if($cash_give > 0 || $rune_give > 0 || $food_give > 0)
		$givenothing = false;

	if($cash_give > $users[cash] || $rune_give > $users[runes] || $food_give > $users[food])
		TheEnd("The mercenaries do not think it wise of you to try to pay more than you have.");

	if($cash_give < 0 || $rune_give < 0 || $food_give < 0)
		TheEnd("The mercenaries do not understand why you are trying to give a negative amount.");

	if($givenothing)
		TheEnd("The mercenaries do not understand why you are trying to give nothing.");


	if(empty($_POST['land_drop']) && $land_drop!="0") $land_drop = -1;
	if(empty($_POST['rank_drop'])) $rank_drop = -1;
	if(empty($_POST['net_drop']) && $net_drop!="0") $net_drop = -1;
	///echo "$land_drop - $rank_drop - $net_drop \n<br><br>";

	if($land_drop == -1 && $rank_drop == -1 && $net_drop == -1) TheEnd("You have to have some condition.");

	if($net_drop == 0) $net_drop = -1;
	if($rank_drop == 0) $rank_drop = -1;

	if($users[num] == $targ)
		TheEnd("The mercenaries laugh at the idea of setting a bounty on yourself");
    //if($cash<$min)
    	//TheEnd("The Mercenary Guild refuses to set any bounty below " . commas($min) . ".");*/


	$all = 1;
	$all_three_net = $net_drop;
	$all_three_land = $land_drop;
        $all_three_rank = $rank_drop;

        $just_one_net = $net_drop;
	$just_one_land = $land_drop;
        $just_one_rank = $rank_drop;


	  if($net_drop<0) $all_three_net = $target[networth] + 1000; // So, if the user marked they do NOT want to count net, then we will make it very huge for the all three test... on the other hand we keep it -1 so it always fails for the || test: so it does not set it off.
	  if($land_drop<0) $all_three_land = $target[land] + 1000;
	  if($rank_drop<0) $all_three_rank = $target[rank] - 1000;

	  if($net_drop<0) $just_one_net = $target[networth] - 1000; // So, if the user marked they do NOT want to count net, then we will make it very huge for the all three test... on the other hand we keep it impossible so it always fails for the || test so it does not set it off.
	  if($land_drop<0) $just_one_land = $target[land] - 1000;
	  if($rank_drop<0) $just_one_rank = $target[rank] + 1000;

	 // echo "$bounty[net] - $bounty[land] - $bounty[rank] - $all_three_net - $all_three_land - $all_three_rank";

	if(  ($all && $all_three_land > $target[land] && $all_three_rank < $target[rank] && $all_three_net > $target[networth]) || ( !$bounty[all_1] && ($just_one_land > $target[land] || $just_one_rank < $target[rank] || $just_one_net > $target[networth])))
        TheEnd("The conditions on this bounty have already been met!");



	db_safe_query("INSERT INTO $mercdb (num) VALUES (NULL);");
	$ubount = loadBount(mysql_insert_id());
	$ubount[all_1] = $all;
	//print "All: $all";
	$ubount[time] = $btime;

	$ubount[cash] = $cash_give;
	$ubount[food] = $food_give;
	$ubount[rune] = $rune_give;
	$ubount[troop] = join("|", $troop);

	$ubount[land] = $land_drop;
	$ubount[rank] = $rank_drop; // See not sure if this is possible... we will not know change in rank until turns.php right... and then who do we credit? Last attack?
	$ubount[net] = $net_drop;

	$ubount[t_num] = $target[num];
	$ubount[t_name] = $target[empire];
	

	$bounty = $ubount; // Storing to see if the bounty is already completed before we start!

	// TAXmaaan (should 5% appear too small, be thankful I don't take it all)

	$ubount[s_name] = $users[empire];
	$ubount[s_num] = $users[num];
	$ubount[anon] = $anon;
	
	if($anon==1) $percent = ($percent*100)*2/100;
	
	$cash_give = round((1+$percent) * $cash_give);
	$food_give = round((1+$percent) * $food_give);
	$rune_give = round((1+$percent) * $rune_give);
	foreach($config[troop] as $num => $mktcost)
		$troop[$num] = round((1+$percent) * $troop[$num]);

	$users[cash] -= $cash_give;
	$users[food] -= $food_give;
	$users[runes] -= $rune_give;
	foreach($config[troop] as $num => $mktcost)
		$users[troop][$num] -= $troop[$num];

	// if($users[cash] < 0 || $users[food] < 0 || $users[rune] < 0 || $users[troop0] < 0 || $users[troop1] < 0 || $users[troop2] < 0 || $users[troop3] < 0);

	saveUserData($users, "networth num_bounties cash runes food troops");
	saveBountData($ubount,"anon time cash rune food troop t_num t_name s_name s_num land rank net all_1");
	$bountyset = "You have set a bounty on $target[empire]<a class=proflink href=?profiles&num=$target[num]$authstr>(#$target[num])</a> and your deposit has been left with the Mercenary Guild";
	//print $bountyset;
	addNews(401, array(id1=>$users[num], clan1=>$users[clan], id2=>$target[num], clan2=>$target[clan]));
	if($ubount[anon])
		addNews(402, array(id2=>0, clan2=>0, id1=>$target[num], clan1=>$target[clan]));
	else 
		addNews(402, array(id2=>$users[num], clan2=>$users[clan], id1=>$target[num], clan1=>$target[clan]));
}

 $b_array = array();

if($view=="true")
{
 /*
 echo "<table class=\"scorestable\">";
 printBountyHeader($users[era]);
 */

 $x = 1;
if($_POST['edit']) {
	foreach($_POST as $key => $value) {
		if(substr($key, 0, 7) == "editid_") {
			$key = substr($key, 7);
			print("<!--attempt at editing id" . $key . "-->");
			$edit_id = $key;
		}
	}

			
			$editing = "yes";
			$edit_bounty = loadBount($edit_id);
			$edit_bounty[troops] = explode("|", $edit_bounty[troop]);
			
	 		$cansend[cash] -= $edit_bounty[cash];
	 		$cansend[food] -= $edit_bounty[food];
	 		$cansend[rune] -= $edit_bounty[rune];
			
			if($cansend[cash] < 0) $cansend[cash] = 0;
			if($cansend[food] < 0) $cansend[food] = 0;
			if($cansend[rune] < 0) $cansend[rune] = 0;

			foreach($edit_bounty[troops] as $num => $amt) {
				$cansend[$num] -= $amt;
				if($cansend[$num] < 0) $cansend[$num] = 0;
			}


			if($edit_bounty[filled] == 1 || $edit_bounty[s_num] != $users[num]) TheEnd("This bounty has already been fulfilled, or is not yours.");
			//if($edit_bounty[edits] > $max_edits-1) TheEnd("You have already edited this bounty the max amount.");

	 		if($edit_bounty[land] != -1) $edit_bounty[land] = "Land To " . commas($edit_bounty[land]) . "<br>";
	 		if($edit_bounty[rank] != -1) $edit_bounty[rank] = "Rank To " . commas($edit_bounty[rank]) . "<br>" ;
	 		if($edit_bounty[net] != -1)  $edit_bounty[net] =  "Net To " . commas($edit_bounty[net]) . "<br>";
			if($edit_bounty[land] == -1) $edit_bounty[land] = "";
			if($edit_bounty[rank] == -1) $edit_bounty[rank] = "";
			if($edit_bounty[net] == -1)  $edit_bounty[net] = "";


			$edit_bounty[time] = date($dateformat, $edit_bounty[time]);
			
			
 			foreach($edit_bounty[troops] as $key => $var) {
 				if(gamefactor($var) != 0) $edit_bounty[troops][$key] = commas(gamefactor($var)) . " " . $uera["troop$key"] . "<br>";	
 				else $edit_bounty[troops][$key] = "";
 			}
	         	$edit_bounty[cash] = commas(gamefactor($edit_bounty[cash]));
	 		if($edit_bounty[cash] != 0) $temp = "$edit_bounty[cash]<br>";
	 		if($edit_bounty[food] != 0) $edit_bounty[food] = commas(gamefactor($edit_bounty[food])) . " food<br>";
	 		if($edit_bounty[rune] != 0) $edit_bounty[rune] = commas(gamefactor($edit_bounty[rune])) . " ".$uera[runes]."<br>";
	        	$edit_bounty[cash] = $temp;




	 		if($edit_bounty[cash] == 0) $edit_bounty[cash] = "";
	 		if($edit_bounty[food] == 0) $edit_bounty[food] = "";
	 		if($edit_bounty[rune] == 0) $edit_bounty[rune] = "";



	 		for($i = 0; $i < sizeof($cansend); $i++)
	 			if($cansend[$i] < 0) $cansend[$i] = 0;

}

if($_POST['do_add']) {

		fixInputNum($cash_give);
		fixInputNum($rune_give);
		fixInputNum($food_give);
		$cash_give = invfactor($cash_give);
		$rune_give = invfactor($rune_give);
		$food_give = invfactor($food_give);

		//the input should be like name="troop_give[0]" etc.
		foreach($config[troop] as $num => $mktcost) {
			fixInputNum($troop[$num]);
			$troop[$num] = invfactor($troop[$num]);
		}

			$edit_bounty = loadBount($_POST['bounty_id']);
			$edit_bounty[troop] = explode("|", $edit_bounty[troop]);

	 		$cansend[cash] -= $edit_bounty[cash];
	 		$cansend[food] -= $edit_bounty[food];
	 		$cansend[rune] -= $edit_bounty[rune];

			if($cansend[cash] < 0) $cansend[cash] = 0;
			if($cansend[food] < 0) $cansend[food] = 0;
			if($cansend[rune] < 0) $cansend[rune] = 0;

			foreach($edit_bounty[troop] as $num => $amt) {
				$cansend[$num] = $cansend[$num] - $amt;
				if($cansend[$num] < 0) $cansend[$num] = 0;
			}

	 		 for($i = 0; $i < sizeof($cansend); $i++)
	 			if($cansend[$i] < 0) $cansend[$i] = 0;
	 			
	if($cash_give > $cansend[cash] || $food_give > $cansend[food] || $rune_give > $cansend[runes])
		TheEnd("You can not add that much!");


	$givenothing = true;

	foreach($config[troop] as $num => $mktcost) {
		if($troop[$num] < 0)
			TheEnd("The mercenaries do not understand why you are trying to give a negative amount.");
		if($troop[$num] > $cansend[$num])
			TheEnd("You can not add that much!");
		if($troop[$num] > 0)
			$givenothing = false;
	}
	 			
	 			


	if($cash_give > $users[cash] || $rune_give > $users[runes] || $food_give > $users[food] || $troop0_give > $users[troop0] || $troop1_give > $users[troop1] || $troop2_give > $users[troop2] || $troop3_give > $users[troop3])
		TheEnd("The mercenaries do not think it wise of you to try to pay more than you have.");

	if($cash_give < 0 || $rune_give < 0 || $food_give < 0 || $troop0_give < 0 || $troop1_give < 0 || $troop2_give < 0 || $troop3_give < 0)
		TheEnd("The mercenaries do not understand why you are trying to give a negative amount.");



		if($edit_bounty[filled] == 1 || $edit_bounty[s_num] != $users[num]) TheEnd("This bounty has already been fulfilled, or is not yours.");
		//if($edit_bounty[edits] > $max_edits-1) TheEnd("You have already edited this bounty the max amount.");


		$edit_bounty[cash] += $cash_give;
		$edit_bounty[food] += $food_give;
		$edit_bounty[rune] += $rune_give;
		/*$edit_bounty[troop0] += $troop0_give;
		$edit_bounty[troop1] += $troop1_give;
		$edit_bounty[troop2] += $troop2_give;
		$edit_bounty[troop3] += $troop3_give;*/
		
		//foreach($edit_bounty[troop] as $num => $amt)  can't use because then you can't add troopX if they don't
		//already have that troop type in the bounty
		foreach($config[troop] as $num => $mktcost) 
			$edit_bounty[troop][$num] += $troop[$num];
			
		$edit_bounty[troop] = implode("|", $edit_bounty[troop]);

		if($edit_bounty[anon] == 1) $percent = ($percent*100)*2/100;
		$cash_give = round((1+$percent) * $cash_give);
		$food_give = round((1+$percent) * $food_give);
		$rune_give = round((1+$percent) * $rune_give);
		foreach($config[troop] as $num => $mktcost)
			$troop[$num] = round((1+$percent) * $troop[$num]);

		$users[cash] -= $cash_give;
		$users[food] -= $food_give;
		$users[runes] -= $rune_give;
		foreach($config[troop] as $num => $mktcost)
			$users[troop][$num] -= $troop[$num];

	 	$edit_bounty[edits]++;

		saveUserData($users, "networth num_bounties cash runes food troops");
		saveBountData($edit_bounty,"time cash rune food troop t_num t_name edits s_name s_num land rank net all_1");
		$bountyset = "You have increased your bounty on $edit_bounty[t_name]<a class=\"proflink\" href=\"?profiles&num=$edit_bounty[t_num]$authstr\">(#$edit_bounty[t_num])</a>";
	
		$cansend = array();
		foreach($users[troop] as $num => $amt) {
			$cansend[] = $amt*0.20;
		}
		$cansend[food] = round($users[food]*.20);
		$cansend[cash] = round($users[cash]*.20);
		$cansend[runes] = round($users[runes]*.20);


}


 // Ok $b_array = array($b_array, ""); WILL NOT WORK!!! IT IS NOT LIKE IN PERL - You must use array_push() or array[]
 // Here I will use [] because I am only adding one element


 //$b_array = array($b_array, "<table class=\"scorestable\">");
 //$b_array[] = printBountyHeader($users[era]);

if(!isset($asc))
	$asc = 0;

$time_order = 0;
$setter_order = 0;
$target_order = 0;

if(!$order_by) $order_by = "time";

if($asc == 0 && $order_by == "time") $time_order = 1;
if($asc == 0 && $order_by == "s_num") $setter_order = 1;
if($asc == 0 && $order_by == "t_num") $target_order = 1;

if($asc == 0) $asc = "ASC";
if($asc == 1) $asc = "DESC";

fixInputNum($prof_target);
 if($view_prof == 1) $scores = db_safe_query("SELECT * FROM $mercdb WHERE t_num=$prof_target AND filled=0 ORDER BY $order_by $asc LIMIT 1000;");
 else $scores = db_safe_query("SELECT * FROM $mercdb WHERE filled=0 ORDER BY $order_by $asc LIMIT 1000;");
$x = 1;
 while ($bounty = mysql_fetch_array($scores)) {
 		$temp = "";
		$bounty['x'] = $x;

 		if($bounty[land] != -1) $bounty[land] = "Land To " . commas($bounty[land]) . "<br>";
 		if($bounty[rank] != -1) $bounty[rank] = "Rank To " . commas($bounty[rank]) . "<br>" ;
 		if($bounty[net] != -1)  $bounty[net] =  "Net To " . commas($bounty[net]) . "<br>";
		if($bounty[land] == -1) $bounty[land] = "";
		if($bounty[rank] == -1) $bounty[rank] = "";
		if($bounty[net] == -1)  $bounty[net] = "";


		$bounty[time] = date($dateformat, $bounty[time]);
		$uera = loadEra($users['era'], $users['race']);
		
		/*
 		if($bounty[troop0] != 0) $bounty[troop0] = commas($bounty[troop0]) . " $uera[troop0]<br>";
 		if($bounty[troop1] != 0) $bounty[troop1] = commas($bounty[troop1]) . " $uera[troop1]<br>";
 		if($bounty[troop2] != 0) $bounty[troop2] = commas($bounty[troop2]) . " $uera[troop2]<br>";
 		if($bounty[troop3] != 0) $bounty[troop3] = commas($bounty[troop3]) . " $uera[troop3]<br>";
 		*/
 		
 		$bounty[troops] = explode("|", $bounty[troop]);
 		foreach($bounty[troops] as $key => $var) {
# 			if($var != 0) $bounty[troops][$key] = commas($var) . " " . $uera["troop$key"] . "<br>";	
			$bounty[troops][$key] = commas(gamefactor($var)) . " " . $uera["troop$key"] . "<br>";
			if(gamefactor($var) == 0)
				$bounty[troops][$key] = "";
 		}
 			

         	$bounty[cash] = commas(gamefactor($bounty[cash]));
 		if($bounty[cash] != 0) $temp = "$bounty[cash]<br>";
 		if($bounty[food] != 0) $bounty[food] = commas(gamefactor($bounty[food])) . " food<br>";
 		if($bounty[rune] != 0) $bounty[rune] = commas(gamefactor($bounty[rune])) . " ".$uera[runes]."<br>";
        	$bounty[cash] = $temp;
        
        //print("Temp: $temp - $bounty[cash] - bounty cash");
		/*
 		if($bounty[troop0] == 0) $bounty[troop0] = "";
 		if($bounty[troop1] == 0) $bounty[troop1] = "";
 		if($bounty[troop2] == 0) $bounty[troop2] = "";
 		if($bounty[troop3] == 0) $bounty[troop3] = "";
 		*/
 		

 		if($bounty[cash] == 0) $bounty[cash] = "";
 		if($bounty[food] == 0) $bounty[food] = "";
 		if($bounty[rune] == 0) $bounty[rune] = "";



        $target = loadUser($bounty[t_num]);

		if ( $target[clan] != 0 && (($uclan[ally1] == $target[clan]) || ($uclan[ally2] == $target[clan]) || ($uclan[ally3] == $target[clan]) || ($uclan[ally4] == $target[clan]) || ($uclan[ally5] == $target[clan])))
			$bounty['color'] = "warn";

		if($target[clan] == $users[clan] && $users[clan] != 0)
			$bounty['color'] = "warn";


		if ($bounty[s_num] == $users[num]) {
			$bounty['color'] = "good";
			$bounty['editable'] = "<input type=\"hidden\" name=\"edit\" value=\"true\"><input type=\"submit\" name=\"editid_$bounty[num]\" value=\"Edit\">";
		}
        if ($bounty[t_num] == $users[num]) $bounty['color'] = "bad";

		if($bounty[anon] == 1) {
			$bounty[s_num] = "???";
			$bounty[s_name] = "Anonymous";
		} else {
			$bounty[s_name] = $bounty[s_name] . " <a class=proflink href=?profiles&num=" . $bounty[s_num] . "$authstr>(#" . $bounty[s_num] . ")</a>";
		}
 		if($bounty[all] == 0) $bounty[all] = "No";
 		else $bounty[all] = "Yes";
		$b_array[] = $bounty;
		$x++;

 }


 //$b_array[] = printBountyHeader($users[era]);
}


foreach($cansend as $key => $val) {
	$cansend[$key] = commas(round($val));
}


$gdisp = array();
foreach($config[troop] as $num => $mktcost) {
	$gdisp[] = array(name=>$uera["troop$num"], cansend=>$cansend[$num], id=>$num);
}

$tpl->assign('givetroops', $gdisp);

$tpl->assign('cansend', $cansend);
$tpl->assign('percent', $percent*100);
$tpl->assign('filler', "\t");
$tpl->assign('time_order', $time_order);
$tpl->assign('setter_order', $setter_order);
$tpl->assign('target_order', $target_order);
$tpl->assign('merc_name', "Mercenary Guild");
$tpl->assign('lst_ele', (sizeof($bounty)-1));
$tpl->assign('bounty', $b_array);
$tpl->assign('color', $users[era]);
$tpl->assign('view', $view);
$tpl->assign('max', $max);
$tpl->assign('left', ($max-$users[num_bounties]));
$tpl->assign('bountyset', $bountyset);
$tpl->assign('editing', $editing);
$tpl->assign('edit_bounty', $edit_bounty);
$tpl->assign('bounty_id', $edit_bounty[num]);
$tpl->assign('set', $set);
$tpl->assign('prof_target', $prof_target);
$tpl->assign('edit', $edit_bounty[edits]+1);
$tpl->display('mercenary.html');

TheEnd("");
?>

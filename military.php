<?php
include("header.php");
include("magicfun.php");
print("<!--Test War Set" . numWarSet($users) . "-->");

ob_start();

if($do_notes) {
	$users['notes'] = $upd_notes;
	saveUserData($users, "notes", true);
	echo "Notepad updated!<hr>";
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(!empty($_POST['get_bld']))
		$users[std_bld] = 1;
	else
		$users[std_bld] = 0;
	saveUserData($users, "std_bld");
}

if ($users['health'] < 20)
	TheEnd("Cannot perform military actions under 20% health!");
// era of troops, quantity of troops, type of troops, offense or defense
function CalcPoints($era, $quantity, $ttype, $atype) {
	$type = $atype . "_troop" . $ttype;
	return $quantity * $era[$type];
}

function numWarSet($war_user) {
	global $users, $playerdb;
	fixInputNum($war_user[num]);
	$num = db_safe_firstval("SELECT count(*) FROM $playerdb WHERE warset=$war_user[num];");
	return $num;
} 

// Need to set warflag and netmult
function ClanCheck() {
	global $warflag, $netmult, $users, $uclan, $enemy, $config;
	if ($users[clan] == $enemy[clan] && $users[clan] != 0)
		TheEnd("Cannot attack empires in your clan!");
	if (($uclan[ally1] == $enemy[clan]) || ($uclan[ally2] == $enemy[clan]) || ($uclan[ally3] == $enemy[clan]) || ($uclan[ally4] == $enemy[clan]) || ($uclan[ally5] == $enemy[clan]))
		TheEnd("Your Generals quietly ignore your orders to attack an Ally.");
	if (($uclan[war1] == $enemy[clan]) || ($uclan[war2] == $enemy[clan]) || ($uclan[war3] == $enemy[clan]) || ($uclan[war4] == $enemy[clan]) || ($uclan[war5] == $enemy[clan])) {
		$warflag = 1.2;
		$netmult = $config['clan_cutoff'];
	} 
} 

function ReadInput ($type) {
	global $users, $usent, $enemy, $esent, $sendall, $config;
	foreach($config[troop] as $troop => $mktcost) {
		$esent[$troop] = $enemy[troop][$troop];
		if ($enemy[forces] > 0) // if enemy shares forces, he can't use them for defense
			$esent[$troop] *= 0.9;
		if ($sendall) // send everything?
			$usent[$troop] = $users[troop][$troop];
		$numtroops = count($config[troop]);
		if($type == 9 && $troop != 1 && $troop != 3) {
			$usent[$troop] = 0;
			$esent[$troop] = 0;
		} else if($type == 8 && $troop != 0 && $troop != 3) {
			$usent[$troop] = 0;
			$esent[$troop] = 0;
		} else if($type >= 4 && $type < ($numtroops + 4) && $troop != ($type-4)) {
			$usent[$troop] = 0;
			$esent[$troop] = 0;
		}
		CheckQuantity($troop);
	}
} 

function CheckQuantity($type) {
	global $users, $uera, $usent;

	fixInputNum($usent[$type]);
	$esent[$type] = round($esent[$type]);
	if ($usent[$type] < 0)
		TheEnd("Cannot attack with a negative number of units!");
	if ($usent[$type] > $users[troop][$type])
		TheEnd("You do not have that many ".$uera["troop$type"]."!");
} 

function Attack($type) {
	global $users, $uera, $usent, $enemy, $eera, $esent, $playerdb, $datetime, $time, $warflag, $config;
	$uoffense = 0;
	$edefense = 0;

	foreach($config[troop] as $num => $mktcost) {
		$uoffense += CalcPoints($uera, $usent[$num], $num, o);
		$edefense += CalcPoints($eera, $esent[$num], $num, d);
	} 
	
	if ($uoffense == 0 && $type != 'Kamikaze')
		TheEnd("Must attack with something!");

	$uoffense *= $users[health] / 100;
	$edefense *= $enemy[health] / 100;

	if ($warflag)
		$uoffense *= 1.2;

	$helping = 0;

	if ($type == "Surprise") { // surprise attack?
		$offpts *= 1.25;
		$helping = 0;
		$users[health] -= 5;
	} elseif (($enemy[clan]) && ($enemy[forces] > 0)) { // enemy has allies and sharing forces?
		$dbally = db_safe_query("SELECT troops,num,era,race,clan,gate FROM $playerdb WHERE clan=$enemy[clan] AND forces>0 AND land>0;");
		$helping =@mysqli_num_rows($dbally);
	} 
	if ($helping) { // add up allies
		if (($helping-1) != 0)
			print "<span class='cwarn'>".$helping . plural($helping, ' '.$uera[empire]."s rush", ' '.$uera[empire]." rushes") . " to defend your target!</span><br>\n";
		$emaxdefense = $edefense * 2;
		while ($ally = mysqli_fetch_array($dbally)) {
			$ally[troop] = explode("|", $ally[troops]);
			unset($ally[troops]);
			$ad = 0;
			if (($enemy[gate] > $time) || ($ally[gate] > $time) || ($enemy[era] == $ally[era])) { // defense is limited to eras as well
				if ($ally[num] != $users[num]) 
					// addNews(300,$users,$ally,$enemy[num]);
					$arace = loadRace($ally[race], $ally[era]); // adjust according to ally race
				$aera = loadEra($ally[era], $ally[race]); // and era
				foreach($config[troop] as $num => $mktcost)
					$ad += allyHelp($num, $helping) * ($ally[health] / 100);

				$ad = round($ad * $arace[defense]);
				$edefense += $ad;
			} 
		} 
		if ($edefense > $emaxdefense) // limit ally defense
			$edefense = $emaxdefense;
	} 
	$tdefense = $enemy[towers] * $config[towers] * min(1, $enemy[troop][0] / (100 * $enemy[towers] + 1));	// and add in towers
	$bldgs = ($enemy[land] - $enemy[freeland] - $enemy[towers]);
	$bdefense = $bldgs * $config[blddef] * min(1, $enemy[troop][0] / (100 * $bldgs + 1)); 			// add in normal buildings
	$edefense += $tdefense;
	$edefense += $bdefense;
	if ($users['hero_war'] == 1) // Nike'?
		$uoffense *= 1.25;
	if ($enemy['hero_war'] == 2) // Hephaestus?
		$edefense *= 1.25;
	$bonus = 1;
	if ($users['hero_special'] == 2) { // Nemesis?
		$bonus = ($users['attacks'] / $config['max_attacks']) + 1;
	} 
	$uoffense *= $bonus;
	if ($warflag == 0) // war == infinite attacks
		$enemy[attacks]++;

	dobattle($uoffense, $edefense, $type, $tdefense);
} 

function AllyHelp ($type, $numallies) {
	global $enemy, $esent, $ally, $aera;
	$amt = round($ally[troop][$type] * .1);
	if ($amt > $esent[$type] / $numallies)
		$amt = $esent[$type] / $numallies;
	return CalcPoints($aera, $amt, $type, d);
} 

/**
 * dobattle(Offense_Points, Defense_Points, Attack_Type)
 * This function:
 * determines who won
 * calls detloss() to determine troop losses
 * calls dealland() if attack was successful
 */
function dobattle ($op, $dp, $type, $towp) {
	global $users, $enemy, $config;
	// EXPERIENCE ALGORITHM (Anton:030313)
	$uoffexp = calcoffexp($users); 
	$edefexp = calcdefexp($enemy); 

	$op = (1 + $uoffexp) * $op;
	$dp = (1 + $edefexp) * $dp; 
	// END EXPERIENCE ALGORITHM
	$emod = sqrt($op / ($dp + 1)); // modification to enemy losses
	$umod = sqrt(($dp - $towp) / ($op + 1)); // modification to attacker losses (towers not included)

	$loss_mod1 = array();
	$loss_mod2 = array();

	foreach($config[troop] as $num => $mktcost) {
		$loss_mod1[$num] = 0.13 - (0.0146*$mktcost*$config['troopscale'][$num])/($config[troop][0]);
		$loss_mod2[$num] = 0.0716 - (0.0065*$mktcost*$config['troopscale'][$num])/($config[troop][0]);
	}

	foreach($config[troop] as $num => $mktcost) {
		if($type == ($num+4)) {
			detloss($loss_mod1[$num], $loss_mod2[$num], $umod, $emod, $num);
		}
	}

	if($type == 8) {
		detloss($loss_mod1[0], $loss_mod2[0], $umod, $emod, 0);
		detloss($loss_mod1[3], $loss_mod2[3], $umod, $emod, 3);
	} else if($type == 9) {
		detloss($loss_mod1[1], $loss_mod2[1], $umod, $emod, 1);
		detloss($loss_mod1[3], $loss_mod2[3], $umod, $emod, 3);
	} else if($type == 3)
		$umod *= 1.2;

	if($type == 2 || $type == 3) {
		foreach($config[troop] as $num => $mktcost) {
			detloss($loss_mod1[$num], $loss_mod2[$num], $umod, $emod, $num);
		}
	}
	if ($op > $dp * 1.05) {
		dealland($type);
	} 
	printedreport();
} 

/**
 * This function determines the loss of specific types of troops
 * It handles the attacker and defender in one run through
 */
function detloss($uper, $eper, $umod, $emod, $type) {
	global $uloss, $eloss, $usent, $esent;
	if ($usent[$type] > 0) // can't lose more than you send... send none, lose none
		$uloss[$type] = min(mt_rand(0, (ceil($usent[$type] * $uper * $umod) + 1)), $usent[$type]);
	else $uloss[$type] = 0;

	$maxkill = round(.9 * $usent[$type]) + mt_rand(0, round(.2 * $usent[$type] + 1)); // max kills determination (90% - 110%)
	if ($esent[$type] > 0) // he can't lose more than he defended with, or attacker can kill
		$eloss[$type] = min(mt_rand(0, ceil($esent[$type] * $eper * $emod)), $esent[$type], $maxkill);
	else $eloss[$type] = 0; // no troops, no losses
} 

function LossCalc(&$player, &$ploss) {
	global $config;
	foreach($config[troop] as $num => $mktcost)
		$player[troop][$num] -= $ploss[$num];
} 

function DealLand($type) {
	global $landloss, $buildgain, $enemy, $users, $foodloss, $cashloss, $what_for, $config;

	if ($what_for == 'land') {
		// destroy structures
		//destroyBuildings('homes', 7, 70, $type);
		//destroyBuildings('shops', 7, 70, $type);
		//destroyBuildings('industry', 7, 50, $type);
		//destroyBuildings('barracks', 7, 70, $type);
		//destroyBuildings('labs', 7, 60, $type);
		//destroyBuildings('farms', 7, 30, $type);
		//destroyBuildings('towers', 7, 60, $type);

		destroyBuildings('homes',	7,	50,	$type);
		destroyBuildings('shops',	7,	50,	$type);
		destroyBuildings('industry',	7,	50,	$type);
		destroyBuildings('barracks',	7,	50,	$type);
		destroyBuildings('labs',	7,	50,	$type);
		destroyBuildings('farms',	7,	50,	$type);
		destroyBuildings('towers',	7,	50,	$type);


		destroyBuildings('freeland', 10, 0, $type); // 3rd argument MUST be 0 - calculate gained freeland below
		$users[freeland] += $landloss - $buildgain; 
		// update total land counts
		$users[land] += $landloss;
		$enemy[land] -= $landloss; 
		// $enemy[l_attack] = $users[num];
	} else if ($what_for == 'cash') {
		$money = round(($enemy[cash] / 100000 * mt_rand(ceil(10000), ceil(15000)))*$config['sackmodifier']);
		if($money > $enemy[cash])
         $money = $enemy[cash];
	if ($enemy[shield] > $time)
 		$money = round(($money * 2.0)/3.0);
      if($money == 0) {
			ob_end_clean();
			TheEnd("<span class=\"cwarn\">Your troops find your enemy has no gold for you to take!</span^");
		}
		$users[cash] += $money;
		$enemy[cash] -= $money;
		$landloss = $money;
	} else if ($what_for == 'food') {
		$food = round(($enemy[food] / 100000 * mt_rand(ceil(10000), ceil(15000)))*$config['sackmodifier']);
      	if ($enemy[shield] > $time)
 		$food = round(($food * 2.0)/3.0);
	if($food > $enemy[food]) 
         $food = $enemy[food];
		if($food == 0) {
			ob_end_clean();
			TheEnd("<span class=\"cwarn\">Your troops find your enemy has no food for you to take!</span>");
		}
		$users[food] += $food;
		$enemy[food] -= $food;
		$landloss = $food;
	} else {
		TheEnd("But why, what for?");
	} 
} 
// To handle destroying buildings during successful attacks
function destroyBuildings ($type, $pcloss, $pcgain, $atktype){
	global $landloss, $buildgain, $enemy, $users, $config;
	$pcloss /= 100;
	$pcgain /= 100;

	$pcloss *= $config[landloss_mult];

	if (($atktype == 'troop1') || ($atktype == 'troop2') || ($atktype == 'troop3')) { // these attacks destroy extra buildings, but fewer are gained
		if ($atktype == 'troop2') {
			$pcloss *= 1.25;
			$pcgain *= 0.72;
		} elseif (($type == 'towers') || ($type == 'labs')) {
			$pcloss *= 1.3;
			$pcgain *= 9 / 13;
		} else $pcgain *= 0.9;
	} 

	if ($enemy[$type] > 0)
		$loss = mt_rand(1, ceil($enemy[$type] * $pcloss + 2));
	if ($loss > $enemy[$type])
		$loss = $enemy[$type];
	$gain = ceil($loss * $pcgain);

	$enemy[$type] -= $loss;
	$landloss += $loss;
	if ($users[std_bld] == 1) {
		if($config[buildings_always] || $atktype == 2) { // Always gain buildings with standard attacks
			$users[$type] += $gain;
			$buildgain += $gain;
		}
	} 
} 

function printedreport(){
	global $attacktype, $users, $uloss, $uera, $enemy, $eloss, $eera, $landloss, $buildgain, $foodloss, $cashloss, $what_for, $losstype, $config;

	if (!$landloss)
		$landloss = 0;
	if ($what_for == 'land') {
		$losstype = 1;
		$ploss = commas($landloss) . ' acres';
	}
	if ($what_for == 'food') {
		$losstype = 2;
		$ploss = commas(gamefactor($landloss)) . ' food';
	} 
	if ($what_for == 'cash') {
		$losstype = 3;
		$ploss = '$' . commas(gamefactor($landloss));
	}
	if ($landloss || $cashloss || $foodloss) {
		$victorymsg = array("annihilated","destroyed","smashed","defeated","vanquished","trounced","routed","overpowered");
		$a = rand(0,7);
		print "Your army <span class='cgood'><b>".$victorymsg[$a]."</b></span> $enemy[empire]'s defenses and captures <span class='cgood'>$ploss!</span><br>In the effort, you lost:<br>\n";
	} else {
		$defeatmsg = array("repelled","routed","conquered","defeated","vanquished","trounced","thwarted","overpowered");
		$a = rand(0,7);
		print "After a failing struggle, your army is <font color='red'><b>".$defeatmsg[$a]."</b></font> by $enemy[empire]'s defenses.<br>In the attempt, you lost:<br>\n";
	} 

	foreach($config[troop] as $num => $mktcost) {
		if($uloss[$num])
			echo commas(gamefactor($uloss[$num])) .' '. $uera["troop$num"] . "<br>\n";
	}

	print "In their defense, $enemy[empire] lost:<br>\n";

	foreach($config[troop] as $num => $mktcost) {
		if($eloss[$num])
			echo commas(gamefactor($eloss[$num])) .' '. $eera["troop$num"] . "<br>\n";
	}

	if ($buildgain)
		print "You also captured <span class='cgood'>$buildgain</span> structures!<br>\n";
	if ($enemy[land] == 0) {
		?><span class="cgood"><b><?=$enemy[empire]?> <a class=proflink href=?profiles&num=<?=$enemy[num]?><?=$authstr?>>(#<?=$enemy[num]?>)</a></b> has been destroyed!</span><br>
<?php $users[kills]++;
	} else if($what_for == 'land') {
		echo "<span class='cgood'>$enemy[empire] has ".commas($enemy[land])." acres of land left.</span><br>\n";
	}
} 


function printRow ($num) {
	global $users, $uera;

	echo '<tr><td>'.$uera["troop$num"].'</td>';
	echo '<td class="aright">'.commas(gamefactor($users[troop][$num])).'</td>';
	echo '<td class="aright"><input type="text" name="usent['.$num.']" size="8" value="0"></td></tr>';
} 


// *************
// End Functions
// *************
if ($users[turnsused] <= $config[protection]) // are they under protection?
	TheEnd("Cannot use offensive actions while under protection!");
if ($users[disabled] == 2) // are they admin?
	TheEnd("Administrative accounts cannot use offensive actions!");

if ($config['warset'] && $declarewar) {
	$declareon = $declareon_w;
	fixInputNum($declareon);
	$target = $declareon;
	if (!$target) // specified target?
		TheEnd("You must specify a target!");
	if ($target == $users[num]) // attacking self?
		TheEnd("Cannot declare war on yourself!");
	if ($target == $users[peaceset])
		TheEnd("Cannot declare war on someone you are at peace with!");
	$enemy = loadUser($target);
	$uclan = loadClan($users[clan]);
	if ($users[clan] != 0 && $enemy[clan] != 0 && ($uclan[ally1] == $enemy[clan] || $uclan[ally2] == $enemy[clan] || $uclan[ally3] == $enemy[clan] || $uclan[ally4] == $enemy[clan] || $uclan[ally5] == $enemy[clan]))
		TheEnd("Cannot declare war on your clan's allies!");

	if($users[warset] != 0)
		TheEnd("You are already at war with another ".$uera[empire]."!");
	if($enemy[warset] != 0)
		TheEnd("That ".$uera[empire]." is already at war with another ".$uera[empire]."!");

	$erace = loadRace($enemy[race], $enemy[era]);
	$eera = loadEra($enemy[era], $enemy[race]); // load enemy info
	if ($config['dual_game'])
		if ($urace[type] == $erace[type])
			TheEnd("That ".$uera[empire]." is on your side!");

		if ($enemy[land] == 0)
			TheEnd("That ".$uera[empire]." has already been destroyed!");
		if ($enemy[clan] == $users[clan] && $users[clan] != 0)
			TheEnd("That ".$uera[empire]." is in your clan!");
		if ($enemy[disabled] >= 2)
			TheEnd("Cannot declare war on disabled ".$uera[empire]."s!");
		if ($enemy[turnsused] <= $config[protection])
			TheEnd("Cannot declare war on ".$uera[empire]."s under new player protection!");
		if ($users[turnsused] <= $config[warset_protection])
			TheEnd("Cannot declare personal war on ".$uera[empire]."s until you have used ".commas($config[warset_protection])." turns!");
		if ($enemy[turnsused] <= $config[warset_protection])
			TheEnd("Cannot declare personal war on ".$uera[empire]."s until they have used ".commas($config[warset_protection])." turns!");
		if ($enemy[vacation] > $config[vacationdelay])
			TheEnd("Cannot declare war on ".$uera[empire]."s on vacation!");

		$users[warset] = $declareon;
		$users[warset_time] = $time + 36 * 3600;
		$users[warset_known] = $time + 24 * 3600; 
		saveUserData($users, "warset warset_time warset_known");
		$enemy[warset] = $users[num];
		$enemy[warset_time] = $users[warset_time];
		$enemy[warset_known] = $users[warset_known];
		saveUserData($enemy, "warset warset_time warset_known");
		addNews(603, array(id1=>$users[num], id2=>$enemy[num], clan1=>$enemy[clan], clan2=>$users[clan]));
		addNews(601, array(id1=>$enemy[num], id2=>$users[num], clan1=>$enemy[clan], clan2=>$users[clan]));
        debuglog("Game Server: $prefix - Clan: $users[clan] - Action: Change War Relations"); 
        $u_warmsg = "You have declared war on $enemy[empire](#$enemy[num])!<br>The enemy empire has been notified and given 24 hours to prepare.<br>View time remaining until war in the Attacks Section.";
		// send message to user
		makeMsg(time(), $users[num], $users[num], $u_warmsg, "Game Notification: War Declared");
		// send message to enemy
		$e_warmsg = "$users[empire](#$users[num]) has declared war on you!<br>This is your notification of impending war.  You have 24 hours to prepare";
		makeMsg(time(), $users[num], $enemy[num], $e_warmsg, "Game Notification: War Declared");
		TheEnd("You have declared war on $enemy[empire] <a class=proflink href=?profiles&num=$enemy[num]$authstr>(#$enemy[num])</a>!<br>The enemy empire has been notified and given 24 hours to prepare.<br>View time remaining until war under the Military menu.");
}

if ($config['peaceset'] && $declarepeace) {
	$declareon = $declareon_p;
	fixInputNum($declareon);
	$target = $declareon;
	if (!$target) // specified target?
		TheEnd("You must specify a target!");
	if ($target == $users[num]) // attacking self?
		TheEnd("Cannot declare war on yourself!");
	if ($target == $users[warset])
		TheEnd("Cannot declare peace with someone you have declared war on!");
	$enemy = loadUser($target);
	$uclan = loadClan($users[clan]);
	if ($users[clan] != 0 && $enemy[clan] != 0 && ($uclan[war1] == $enemy[clan] || $uclan[war2] == $enemy[clan] || $uclan[war3] == $enemy[clan] || $uclan[war4] == $enemy[clan] || $uclan[war5] == $enemy[clan]))
		TheEnd("Cannot declare peace on your clan's enemies!");

	if($users[peaceset] != 0)
		TheEnd("You are already at peace with another ".$uera[empire]."!");

	$erace = loadRace($enemy[race], $enemy[era]);
	$eera = loadEra($enemy[era], $enemy[race]); // load enemy info
	if ($config['dual_game'])
		if ($urace[type] != $erace[type])
			TheEnd("That ".$uera[empire]." is against your side!");

	if ($enemy[land] == 0)
		TheEnd("That ".$uera[empire]." has already been destroyed!");
	if ($enemy[clan] == $users[clan] && $users[clan] != 0)
		TheEnd("That ".$uera[empire]." is in your clan anyways!");
	if ($enemy[disabled] >= 2)
		TheEnd("Cannot declare peace on disabled ".$uera[empire]."s!");
	if ($enemy[turnsused] <= $config[protection])
		TheEnd("Cannot declare peace on ".$uera[empire]."s under new player protection!");
	if ($enemy[vacation] > $config[vacationdelay])
		TheEnd("Cannot declare peace on ".$uera[empire]."s on vacation!");

	$users[peaceset] = $declareon;
	$users[peaceset_time] = $time + 36 * 3600;
	saveUserData($users, "peaceset peaceset_time");
	addNews(606, array(id1=>$users[num], id2=>$enemy[num], clan1=>$enemy[clan], clan2=>$users[clan]));
	addNews(605, array(id1=>$enemy[num], id2=>$users[num], clan1=>$enemy[clan], clan2=>$users[clan]));
    debuglog("Game Server: $prefix - Empire: $users[empire] - Action: Declare Peace: $enemy[empire]"); 
	TheEnd("You have declared peace on $enemy[empire] <a class=proflink href=?profiles&num=$enemy[num]$authstr>(#$enemy[num])</a>!");
}


	if ($declare_end_war) {
		$neut = (($users[warset_time] - $time) / 3600);
		if($neut > 0) TheEnd("You must wait $neut hours before declaring neutrality.");
		$enemy = loadUser($users[warset]);
		$users[warset] = 0;
		$users[warset_time] = $time;
		$users[warset_known] = 0;
		saveUserData($users, "warset warset_time warset_known");
		saveUserData($enemy, "warset warset_time warset_known");
		addNews(604, array(id1=>$users[num], id2=>$enemy[num], clan1=>$enemy[clan], clan2=>$users[clan]));
		addNews(602, array(id1=>$enemy[num], id2=>$users[num], clan1=>$enemy[clan], clan2=>$users[clan]));
		TheEnd("You have declared neutrality!");
	} 
	
	if ($declare_end_peace) {
		$neut = (($users[peaceset_time] - $time) / 3600);
		if($neut > 0) TheEnd("You must wait $neut hours before declaring neutrality.");

		$enemy = loadUser($users[peaceset]);
		$users[peaceset] = 0;
		$users[peaceset_time] = $time;
		saveUserData($users, "peaceset peaceset_time");
		addNews(604, array(id1=>$users[num], id2=>$enemy[num], clan1=>$enemy[clan], clan2=>$users[clan]));
		addNews(602, array(id1=>$enemy[num], id2=>$users[num], clan1=>$enemy[clan], clan2=>$users[clan]));
		debuglog("Game Server: $prefix - Empire: $users[empire] - Action: End Peace: $enemy[empire]"); 
		TheEnd("You have declared neutrality!");
	}

	if ($do_attack) {
		global $config;
		if ($users[turns] < 2) // enough turns?
			TheEnd("Not enough turns!");
		if (!$target) // specified target?
			TheEnd("You must specify a target!");
		if ($target == $users[num]) // attacking self?
			TheEnd("Cannot attack yourself!");
		if ($users[health] <= 1)
			TheEnd("You do not have enough health to attack.");
		
		$enemy = loadUser($target);
		$erace = loadRace($enemy[race], $enemy[era]);
		$eera = loadEra($enemy[era], $enemy[race]); // load enemy info
		if ($enemy[land] == 0)
			TheEnd("That ".$uera[empire]." has already been destroyed!");
		if ($config['force_atktype'] != 0 && $enemy[land] <= $config['force_atkland']  && $attacktype != $config['force_atktype'])
			TheEnd("Your enemy's forces are too highly concentrated to attempt a specialized attack.");
		if (($enemy[era] != $users[era]) && ($users[gate] <= $time) && ($enemy[gate] <= $time))
			TheEnd("Need to prepare troops first!");
		if ($enemy[disabled] >= 2)
			TheEnd("Cannot attack disabled empires!");
		if ($enemy[turnsused] <= $config[protection])
			TheEnd("Cannot attack ".$uera[empire]."s under new player protection!");
		if ($enemy[vacation] > $config[vacationdelay])
			TheEnd("Cannot attack ".$uera[empire]."s on vacation!");
		if ($enemy[num] == $users[peaceset])
			TheEnd("Cannot attack ".$uera[empire]."s you are allied with!");
		$warflag = 0;
		$netmult = $config['default_cutoff'];

		$prof_target = $enemy[num];	//populate fields

		$uclan = loadClan($users[clan]);

		if ($enemy[clan])
			ClanCheck();

		if ($users[warset] == $enemy[num] || $enemy[warset] == $users[num]) {
			if($users[warset_known] < $time) {
				$enemy['attacks'] = 0;  //reset attack counter just incase war notification was received durning regular attacks
				$warflag = 1.2;
				$netmult = $config['war_cutoff'];
				$warset = 1;
			}
		} 

		if ($enemy[networth] > $users[networth] * $netmult)
			TheEnd("Your Generals flatly refuse to attack such a strong opponent!");
		if ($users[networth] > $enemy[networth] * $netmult)
			TheEnd("Your Generals politely refuse your orders to attack a defenceless empire!");
		if ($enemy[networth] > ($users[networth] * $config['fear_shame_mult']) && $attacktype == "Kamikaze")
			TheEnd("Your Generals flatly refuse to attack such a strong opponent!");
		if ($users[networth] > ($enemy[networth] * $config['fear_shame_mult']) && $attacktype == "Kamikaze")
			TheEnd("Your Generals politely refuse your orders to attack a defenseless empire!");
		if ($enemy[land] <= $config['force_atkland'] && $attacktype != $config['force_atktype'])
			TheEnd("Your Generals politely refuse your orders to attack such a small empire with a non-" . $config['atknames'][$config['force_atktype']] . "!");
		if ($config['dual_game'])
			if ($urace[type] == $erace[type])
				TheEnd("That ".$uera[empire]." is on your side!");
			if ($users[turnsused] < 1000 && $attacktype == "Kamikaze") TheEnd("Your Generals refuse to sacrifice your nation before it has even had a chance to grow!");
			if ($warflag == 0) {
				if ($enemy[attacks] > $config['max_attacks'])
					TheEnd("Too many recent attacks on that empire.  Try again in one hour.");
				$revolt = 1;
				if ($users[networth] > $enemy[networth] * $config['fear_shame_mult']) {

					?><span class="cwarn">Your military is shamed by your attack on such a weak opponent. Many desert!</span><br>
<?php $revolt = 1 - $users[networth] / $enemy[networth] / 125;
				} elseif ($enemy[networth] > $users[networth] * $config['fear_shame_mult']) {

					?><span class="cwarn">Your military trembles at your attack on such a strong opponent. Many desert!</span><br>
<?php $revolt = 1 - $enemy[networth] / $users[networth] / 100;
				} 
				if ($revolt < .9)
					$revolt = .9;
				if ($users['hero_special'] == 3) // Hestia?
					$revolt = $revolt + (1 - $revolt) / 3;
				foreach($config[troop] as $num => $mktcost)
					$users[troop][$num] = round($users[troop][$num] * $revolt);
			} 
			readInput($attacktype);
			Attack($attacktype); 
			// record losses
			losscalc($users, $uloss);
			losscalc($enemy, $eloss);
			$troops1 = $eloss[0];
			$troops2 = $uloss[0];
			foreach($config[troop] as $num => $mktcost) {
				if($num == 0)	continue;
				$troops1 .= '|'.($eloss[$num]==0?'':$eloss[$num]);
				$troops2 .= '|'.($uloss[$num]==0?'':$uloss[$num]);
			}
			$code = 300 + $attacktype;
			$shielded = 0;
			$newsArray = array(	id1		=> $enemy[num],
								clan1	=> $enemy[clan],
								id2		=> $users[num],
								clan2	=> $users[clan],
								troops1	=> $troops1,
								troops2	=> $troops2
								);
			switch($what_for) {
				case 'land':	$newsArray[land1] = $landloss;	$shielded = 1;	break;
				case 'cash':	$newsArray[cash1] = $landloss;	$shielded = 2;	break;
				case 'food':	$newsArray[food1] = $landloss;	$shielded = 3;	break;
			}
			if($landloss == 0)
				$shielded += 10;
			$newsArray[shielded] = $shielded;
			addNews($code, $newsArray);
			if ($enemy[land] == 0) {
				addNews(399, $newsArray);
			} 
			bountyScan($users, $enemy); //We're scanning everytime now.
			$users[attacks] -= $config['attack_decr'];  // changed from -2 per attack;
			if ($users[attacks] < 0)
				$users[attacks] = 0;
			$users[offtotal]++;
			if ($landloss) {
				$users[offsucc]++;
				$enemy[l_attack] = $users[num];
			} else $enemy[defsucc]++;
			$enemy[deftotal]++;
			$users[health] -= 8;
			saveUserData($users, "networth troops land homes shops industry barracks labs farms towers freeland offsucc offtotal attacks health kills food cash");
			saveUserData($enemy, "l_attack networth troops land homes shops industry barracks labs farms towers freeland defsucc deftotal attacks food cash");
			db_safe_query("UPDATE $playerdb SET land=(homes+shops+industry+barracks+labs+farms+towers+freeland) WHERE num=$users[num];");
			db_safe_query("UPDATE $playerdb SET land=(homes+shops+industry+barracks+labs+farms+towers+freeland) WHERE num=$enemy[num];");

			taketurns(2, attack);
			echo $turnoutput;
		} 
		?>
<script language="JavaScript">
function updateAtkNames() {
	var msgnum = document.atksel.target.value;
 	for (i = 0; i < document.atksel.target_num.options.length; ++i) {
 		 if (document.atksel.target_num.options[i].value == msgnum)
 			document.atksel.target_num.options[i].selected = true;
	}
}

function updateAtkNums() {
 	document.atksel.target.value = document.atksel.target_num.value;
}

function updatemissionNames() {
	var msgnum = document.missionsel.target.value;
 	for (i = 0; i < document.missionsel.target_num.options.length; ++i) {
 		 if (document.missionsel.target_num.options[i].value == msgnum)
 			document.missionsel.target_num.options[i].selected = true;
	}
}

function updatemissionNums() {
 	document.missionsel.target.value = document.missionsel.target_num.value;
}

function updateAllNames() {
	updateAtkNames();
	updatemissionNames();
}

window.onload = updateAllNames;

</script>

<form method="post" action="?military<?=$authstr?>" name="atksel">
<?php
	if($config[warset]) {
		if ($users[warset]) {
			$warset = loadUser($users[warset]);
			echo "You are currently at war with <span class='cwarn'>$warset[empire]</span> <a class=proflink href=?profiles&num=$warset[num]$authstr>(#$warset[num])</a>.<br>";
			if($users['warset_known'] > 0) {
				$war_delay = (($users[warset_known] - $time) / 3600);
				echo"<span class='cwarn'><b>Enemy empire has <span class='cwarn'>".round($war_delay)."</span> hours left to prepare for war.</b></span><br><br>";
			}	
			$peace = (($users[warset_time] - $time) / 3600);
			if ($peace < 0) {
				echo "<input type='submit' name='declare_end_war' value='Declare neutrality with #$warset[num]'>";
			} else {
				echo "You can declare neutrality in <span class='cwarn'>" . round($peace) . "</span> hours.<br>";
			} 
		} else {
			echo "You are currently not at war.<br>";
			echo "<input type='submit' name='declarewar' value='Declare War'> on <input type='text' name='declareon_w' length='3'><br>";
		}
		echo '<br><br>';
	}

	if($config['peaceset']) {
		if ($users[peaceset]) {
			$peaceset = loadUser($users[peaceset]);
			echo "You are currently at peace with $peaceset[empire] <a class=proflink href=?profiles&num=$peaceset[num]$authstr>(#$peaceset[num])</a>.<br>";
			$peace = (($users[peaceset_time] - $time) / 3600);
			if ($peace < 0) {
				echo "<input type='submit' name='declare_end_peace' value='Declare neutrality with #$peaceset[num]'>";
			} else {
				echo "You can declare neutrality in " . round($peace) . " hours.<br>";
			} 
		} else {
			echo "You are currently not at peace with anyone.<br>";
			echo "<input type='submit' name='declarepeace' value='Declare Peace'> on <input type='text' name='declareon_p' length='3'><br>";
		}
		echo '<br><br>';
	}
	?>
    <big><b>Attacking</big></b><br><br>		
    <table class="inputtable"><tr>
    <td colspan="3" class="acenter"><?=$uera[empireC]?> number to attack?
    <input type="text" name="target" value="<?=$prof_target?>" size="5" maxlength="4" onChange="updateAtkNames()">
    <select name="target_num" onClick="updateAtkNums()" class="dkbg">
    <?php
		$uclan = loadClan($users[clan]);
		$warquery = "SELECT * FROM $playerdb WHERE land > 0 AND disabled != 3 AND disabled != 2 AND num != $users[num] AND turnsused>$config[protection] AND vacation<=$config[vacationdelay] ORDER BY rank";
		$warquery_result = @db_safe_query($warquery);
		while ($wardrop = @mysqli_fetch_array($warquery_result)) {
			if ($wardrop[num] == 1)
				continue;
			$online = on_disp(ONTXT, $wardrop[online]);
			$color = "normal";
			$warflag = 0;
			if ($wardrop[clan] != 0 && ($uclan[war1] == $wardrop[clan] || $uclan[war2] == $wardrop[clan] || $uclan[war3] == $wardrop[clan] || $uclan[war4] == $wardrop[clan] || $uclan[war5] == $wardrop[clan]))
				$warflag = 1;
			$netmult = $config['default_cutoff'];
			if($users[warset] == $wardrop[num] || $wardrop[warset] == $users[num]) {
				if($users['warset_known'] == 1) {
					$enemy['l_attacks'] = 0;
					$netmult = $config['war_cutoff'];
					$warflag = 1;
				}
			}
			if($warflag == 1)
				$color = "good";
			if (($users[networth] > $wardrop[networth] * $config['fear_shame_mult']) && $warflag == 0)
				$color = "disabled";
			if (($wardrop[networth] > $users[networth] * $config['fear_shame_mult']) && $warflag == 0)
				$color = "disabled";
			if ($wardrop[networth] > $users[networth] * $netmult)
				$color = "dead";
			if ($users[networth] > $wardrop[networth] * $netmult)
				$color = "dead";
			if (($wardrop[era] != $users[era]) && ($users[gate] <= $time) && ($wardrop[gate] <= $time))
				$color = "protected";
			if ($wardrop[attacks] > $config['max_attacks'] && $warflag == 0)
				$color = "protected";

			if ($users[clan] == $wardrop[clan] && $users[clan] != 0)
				$color = "ally";
			if ($wardrop[clan] != 0 && (($uclan[ally1] == $wardrop[clan]) || ($uclan[ally2] == $wardrop[clan]) || ($uclan[ally3] == $wardrop[clan]) || ($uclan[ally4] == $wardrop[clan]) || ($uclan[ally5] == $wardrop[clan])))
				$color = "ally";

			echo ("$wardrop[empire]: $color\n");

			$selected = "";
			if ($wardrop[num] == $prof_target)
				$selected = "selected";
			echo "<option value=\"$wardrop[num]\" class=\"m$color\" $selected >$wardrop[num] - $wardrop[empire]$online</option>\n";
		} 
		?>
        </select><br></td></tr></table>
        <table class="inputtable"><tr><td colspan="3" class="acenter">Attack Type: <select name="attacktype" size="1">
        <?
	    foreach($atknames as $num => $name) {
		    echo '<option value="'.$num.'">'.$name.'</option>';
	    }
        ?>
        </select></td></tr>
<tr>
	<th><input type="radio" name="what_for" value="land" checked>Land</th>
	<th><input type="radio" name="what_for" value="cash">Gold</th>
	<th><input type="radio" name="what_for" value="food">Food</th>
</tr>
    <tr><th class="aleft">Unit</th>
    <th class="aright">Owned</th>
    <th class="aright">Send</th></tr>
    <?php
	foreach($config[troop] as $num => $mktcost)
		printRow($num);
	?>
    <tr><td colspan="3" class="acenter"><input type="checkbox" name="get_bld" value="1"<?=($users[std_bld]?' checked':'')?>>Try to capture buildings?</td></tr>
	<tr><td colspan="3" class="acenter"><input type="checkbox" name="sendall" value="1">Send Everything</td></tr>
	<tr><td colspan="3" class="acenter"><input type="submit" name="do_attack" value="Send Attack"></td></tr>
	<tr><td><input type="checkbox" name="hide_turns"<?=$cnd?>> Condense turns?</td></tr>
	</table></form><hr>
	<form method="post" action="?military<?=$authstr?>" name="missionsel">
	<table class="inputtable">
	<tr>
      <td class="acenter"><?=$uera[empireC]?> to perform mission on?
        <input type="text" name="target" size="5" value="<?=$prof_target?>" maxlength="4" onChange="updatemissionNames()">
<select name="target_num" onClick="updatemissionNums()" class="dkbg">
<?php
		$uclan = loadClan($users[clan]);
		$warquery = "SELECT * FROM $playerdb WHERE land > 0 AND disabled != 3 AND disabled != 2 AND num != $users[num] AND turnsused>$config[protection] AND vacation<=$config[vacationdelay] ORDER BY rank";
		$warquery_result = @db_safe_query($warquery);
		while ($wardrop = @mysqli_fetch_array($warquery_result)) {
			if ($wardrop[num] == 1)
				continue;
			$online = on_disp(ONTXT, $wardrop[online]);

			$color = "normal";
			$warflag = 0;
			if ($wardrop[clan] != 0 && ($uclan[war1] == $wardrop[clan] || $uclan[war2] == $wardrop[clan] || $uclan[war3] == $wardrop[clan] || $uclan[war4] == $wardrop[clan] || $uclan[war5] == $wardrop[clan]))
				$warflag = 1;
			$netmult = $config['default_cutoff'];
			if($users[warset] == $wardrop[num] || $wardrop[warset] == $users[num]) {
				$netmult = $config['war_cutoff'];
				$warflag = 1;
			}

			if($warflag == 1)
				$color = "good";

			if (($users[networth] > $wardrop[networth] * $config['fear_shame_mult']) && $warflag == 0)
				$color = "disabled";
			if (($wardrop[networth] > $users[networth] * $config['fear_shame_mult']) && $warflag == 0)
				$color = "disabled";
			if ($wardrop[networth] > $users[networth] * $netmult)
				$color = "dead";
			if ($users[networth] > $wardrop[networth] * $netmult)
				$color = "dead";

			if (($wardrop[era] != $users[era]) && ($users[gate] <= $time) && ($wardrop[gate] <= $time))
				$color = "protected";
			if ($wardrop[attacks] > $config['max_attacks'] && $warflag == 0)
				$color = "protected";

			if ($users[clan] == $wardrop[clan] && $users[clan] != 0)
				$color = "ally";
			if ($wardrop[clan] != 0 && (($uclan[ally1] == $wardrop[clan]) || ($uclan[ally2] == $wardrop[clan]) || ($uclan[ally3] == $wardrop[clan]) || ($uclan[ally4] == $wardrop[clan]) || ($uclan[ally5] == $wardrop[clan])))
				$color = "ally";

			echo ("$wardrop[empire]: $color\n");


			$selected = "";
			if ($wardrop[num] == $prof_target)
				$selected = "selected";
			echo "<option value=\"$wardrop[num]\" class=\"m$color\" $selected >$wardrop[num] - $wardrop[empire]$online</option>\n";
		}
		?>
        </select></td></tr></table>
        <table class="inputtable"><tr><td><select name="mission_num" size="1" class="dkbg">
        <option value="0">Select a Mission</option>
        <?php
		for ($i = 1; $i <= $missions; $i++)
		if ($sptype[$i] == 'o')
			printMRow($i);
		?>
        </select></td></tr>
        <tr><td class="acenter"><input type="submit" name="do_mission" value="Send <?=$uera[wizards]?>"></td></tr>
        <tr><td><input type="checkbox" name="hide_turns"<?=$cnd?>> Condense turns?</td></tr>
        </table></form>
        <?php
        if ($users[shield] > $time)
            print "<i>We currently have a patrol around our ".$uera[empire].", which will last for ".round(($users[shield]-$time)/3600,1)." more hours.</i><br>\n";
        if ($users[gate] > $time)
            print "<i>We currently have troops prepared for attack which will last for ".round(($users[gate]-$time)/3600,1)." more hours.</i><br>\n";
TheEnd("");
?>

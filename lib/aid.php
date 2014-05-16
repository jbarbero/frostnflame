<?
function sendUnits ($type) {
	global $users, $uera, $enemy, $eera, $cansend, $send;

	$amount = $send[$type];

	if ($amount == 0)
		return;
	elseif ($amount < 0)
		TheEnd("Cannot send a negative amount of $uera[$type]!");
	elseif ($amount > $users[$type]) {
		switch($type) {
			case cash:	TheEnd("You don't have that much money!");			break;
			case food:	TheEnd("You don't have that much $uera[food]!");		break;
			case runes:	TheEnd("You don't have that many $uera[runes]!");		break;
			default:	TheEnd("You don't have that many $uera[$type]!");		break;
		}
	}
	elseif ($amount > $cansend[$type]) {
		switch($type) {
			case cash:	TheEnd("Cannot send more than 20% of your money!");		break;
			case food:	TheEnd("Cannot send more than 20% of your $uera[food]!");	break;
			case runes:	TheEnd("Cannot send more than 20% of your $uera[runes]!");	break;
			default:	TheEnd("Cannot send more than 20% of your $uera[$type]!");	break;
		}

		TheEnd("Cannot send more than 20% of your $uera[$type]!");
	}
	else {
		$users[$type] -= $amount;
		$enemy[$type] += $amount;
	}
}


function calcConvoy() {
	global $convoy, $cansend, $users, $config;
	$tn = $config[aidtroop];

	$convoy = floor((12 * $users[networth] / 10000) * $config[troop][0] / $config[troop][$tn] * (500/$config[troop][0]));
	$convoy = invfactor(gamefactor($convoy));	// ROUNDING -- IMPORTANT!

	foreach($config[troop] as $num => $mktcost) {
		$cansend["troop$num"] = round(0.20 * $users[troop][$num]);
	}
	$cansend[cash] = round($users[cash] * .20);
	$cansend[runes] = round($users[runes] * .20);
	$cansend[food] = round($users[food] * .20);
}



/**
 * expected args:
 * to, troop array, cash, food, runes
**/
function fn_aid($args) {
	global $users, $uera, $config, $all_races, $urace, $enemy, $send;
	$tn = $config[aidtroop];
	calcConvoy();
	global $convoy, $cansend;

	$users[aidcred]--;

	if ($users[turnsused] <= $config[protection])
		TheEnd("Cannot send aid while under protection!");
	if(!isset($args['to']))
		TheEnd("You must specify a target!");
	if ($users[turns] < 2)
		TheEnd("Not enough turns!");

	$dest = $args[to];
	fixInputNum($dest);

	if($dest == 0)
		TheEnd("Invalid target!");
	if ($dest == $users[num])
		TheEnd("Cannot send aid to yourself!");
	if ($users[troop][$tn] < $convoy)
		TheEnd("You don't have enough ".$uera["troop$tn"]."!");

	$send = array(	cash   => $args[cash],
			food   => $args[food],
			runes  => $args[runes]);

	foreach($args[troop] as $num => $sendamt) {
		$send["troop$num"] = $sendamt;
	}

	foreach($send as $type => $amt) {
		if($send[$type] == 'max')
			$send[$type] = $cansend[$type];
		else
			$send[$type] = invfactor($amt);
		fixInputNum($send[$type]);
	}

	if ($send["troop$tn"] < $convoy)
		TheEnd("You must send at least ".commas(gamefactor($convoy))." ".$uera["troop$tn"]."!");

	$enemy = loadUser($dest);
	$eera = loadEra($enemy[era], $enemy[race]);

	if ($enemy[num] != $dest)
		TheEnd("No such $uera[empire]!");
	if ($enemy[land] == 0)
		TheEnd("That $uera[empire] is dead!");
	if (($enemy[era] != $users[era]) && ($enemy[gate] <= $time) && ($users[gate] <= $time))
		TheEnd("Need to prepare $uera[wizards] first!");
	if ($enemy[disabled] >= 2)
		TheEnd("Cannot send aid to disabled/admin ".$uera[empire]."s!");
	if ($enemy[turnsused] <= $config[protection])
		TheEnd("Cannot send aid to someone under protection!");
	if ($enemy[vacation] > $config[vacationdelay])
		TheEnd("Cannot send aid to someone on vacation!");
	if ($enemy[networth] > $users[networth] * 3)
		TheEnd("That ".$uera[empire]." is far too large to require your aid!");
	if ($users[warset] == $enemy[num] || $enemy[warset] == $users[num])
		TheEnd("Cannot send aid to warring ".$uera[empire]."s!");

	if($config['dual_game'])
		if($all_races[$enemy[race]][type] != $urace[type])
			TheEnd("That ".$uera[empire]." is on the opposite side!");

	$uclan = loadClan($users[clan]);
	if ($enemy[clan] != 0 && $users[clan] != 0) {
		if (	
			($uclan[war1] == $enemy[clan]) ||
			($uclan[war2] == $enemy[clan]) ||
			($uclan[war3] == $enemy[clan]) ||
			($uclan[war4] == $enemy[clan]) ||
			($uclan[war5] == $enemy[clan])
			)
			TheEnd("Your Generals laugh at the idea of sending aid to your enemies.");
		if (	
			($uclan[ally1] == $enemy[clan]) ||
			($uclan[ally2] == $enemy[clan]) ||
			($uclan[ally3] == $enemy[clan]) ||
			($uclan[ally4] == $enemy[clan]) ||
			($uclan[ally5] == $enemy[clan]) ||
			$uclan[num] == $enemy[clan])
			$users[aidcred]++;	// unlimited aid to allies
	}

	if ($users[peaceset] == $enemy[num])
		$users[aidcred]++;	// unlimited aid to allies

	if ($users[aidcred] < 0)
		TheEnd("Cannot send any more foreign aid!");

	foreach($config[troop] as $num => $mktcost) {
		$users["troop$num"] = $users[troop][$num];
		$enemy["troop$num"] = $enemy[troop][$num];
	}

	foreach($config[troop] as $num => $mktcost) {
		sendUnits("troop$num");
	}
	sendUnits(cash);
	sendUnits(runes);
	sendUnits(food);

	foreach($config[troop] as $num => $mktcost) {
		$users[troop][$num] = $users["troop$num"];
		$enemy[troop][$num] = $enemy["troop$num"];
	}

	$cash = $send[cash];	unset($send[cash]);
	$runes = $send[runes];	unset($send[runes]);
	$food = $send[food];	unset($send[food]);
	$troops = join("|", $send);

	addNews(102, array(id1=>$enemy[num], clan1=>$enemy[clan], cash1=>$cash, troops1=>$troops, food1=>$food, runes1=>$runes, id2=>$users[num], clan2=>$users[clan]));
	saveUserData($users,"networth troops cash runes food aidcred");
	saveUserData($enemy,"networth troops cash runes food");
	takeTurns(2,aid);
	ThePrint(commas(gamefactor($send["troop$tn"])).' '.$uera["troop$tn"].' have departed with shipment to <b>'.$enemy[empire].' <a class=proflink href=?profiles&num='.$enemy[num].$authstr.'>(#'.$enemy[num].')</a></b>');
	calcConvoy();
}

?>

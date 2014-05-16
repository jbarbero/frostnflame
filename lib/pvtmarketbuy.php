<?
if(!defined("PROMISANCE"))
	die(" ");
function getBuyCosts ($type, $num='') {
	global $users, $config, $urace, $costs, $canbuy;
	$ts = $type.$num;
	if($type == 'troop') {
		$umktmt = $users[pmkt][$num];
	}
	else {
		$umktmt = $users["pmkt_$type"];
	}
	$costbonus = 1 - ((1-$config[mktshops])*($users[barracks] / $users[land]) + $config[mktshops]*($users[shops] / $users[land]));
	if ($type != 'troop')
		$costs[$ts] = $config[$ts."_buy"];
	else
	{
		$costs[$ts] = $config[troop][$num] * $costbonus;
		if ($costs[$ts] < $config[troop][$num] * .7)
			$costs[$ts] = $config[troop][$num] * .7;
		$costs[$ts] = round($costs[$ts] * $urace[mkt]);
	}
	$canbuy[$ts] = floor($users[cash] / $costs[$ts]);
	if ($canbuy[$ts] > $umktmt)
		$canbuy[$ts] = $umktmt;
}

function buyUnits ($type, $num='') {
	global $costs, $users, $uera, $buy, $canbuy, $msg;
	$ts = $type.$num;
	$amount = $buy[$ts];
	if($amount == 'max') {
		$amount = invfactor(gamefactor($canbuy[$ts]));
	}
	else {
		fixInputNum($amount);
		$amount = invfactor($amount);
	}
	$cost = $amount * $costs[$ts];
	$umktmt = 0;
	if($type == 'troop') {
		$umktmt = $users[pmkt][$num];
	}
	else {
		$umktmt = $users["pmkt_$ts"];
	}
	if ($amount == 0)
		return;
	elseif ($amount < 0)
		$msg .= "Cannot purchase a negative amount of $uera[$ts]!<br>\n";
	elseif ($amount > $umktmt)
		$msg .= "Not enough $uera[$ts] available!<br>\n";
	elseif ($cost > $users[cash])
		$msg .= "Not enough money to buy ".commas(gamefactor($amount))." $uera[$ts]!<br>\n";
	else
	{
		$users[cash] -= $cost;
		if($type == 'troop') {
			$users[troop][$num] += $amount;
			$users[pmkt][$num] -= $amount;
		}
		else {
			$users[$type] += $amount;
			$users["pmkt_$ts"] -= $amount;
		}
		$canbuy[$ts] -= $amount;
		$msg .= commas(gamefactor($amount))." $uera[$ts] purchased for $".commas(gamefactor($cost)).".<br>\n";
	}
}

foreach($config[troop] as $num => $mktcost) {
	getBuyCosts('troop', $num);
}
getBuyCosts("food");


/**
 * Expects args
 * troop0, troop1, ... , food, runes
**/
function bazaarbuy($args) {
	global $config, $users, $buy, $msg;
	$buy = $args;

	foreach($config[troop] as $num => $mktcost) {
		buyUnits(troop, $num);
	}
	buyUnits("food");
	buyUnits("runes");

	saveUserData($users,"networth cash troops food pvmarket pmkt_food");
	return $msg;
}

?>

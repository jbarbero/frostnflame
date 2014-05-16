<?
if(!defined("PROMISANCE"))
	die(" ");
function getSellCosts ($type, $num='') {
	global $users, $config, $urace, $costs, $cansell;
	$ts = $type.$num;
	$bmper_type = "bmper" . "$type";
	if($type == 'troop') {
		$umktmt = (($config["bmperc"] - $users[bmper][$num]) / 10000) * $users[troop][$num];
	}
	else {
		$umktmt = $users[$type];
	}
	
	$costbonus = 1 + (1 - $config[mktshop]) * ($users[barracks] / $users[land]) + $config[mktshop] * ($users[shops] / $users[land]);

	if ($type != 'troop')
		$costs[$ts] = $config[$ts."_sell"];
	else {
		$costs[$ts] = $config[troop][$num] * $costbonus;
		if ($costs[$ts] > $config[troop][$num]* .5)
			$costs[$ts] = $config[troop][$num] * .5;
		$costs[$ts] = round($costs[$ts] * $urace[mkt]);

	}

	$cansell[$ts] = round($umktmt);
	if($cansell[$ts] < 0)
		$cansell[$ts] = 0;
}

function sellUnits ($type, $num='') {
	global $costs, $users, $uera, $sell, $cansell, $msg;
	$ts = $type.$num;
	$amount = $sell[$ts];
	if($amount == 'max') {
		$amount = $cansell[$ts];
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
		$msg .= "Cannot sell a negative amount of $uera[$ts]!<br>\n";
	elseif ($amount > $cansell[$ts])
		$msg .= "You can not sell that many $uera[$ts]!<br>\n";
	else
	{
		$users[cash] += $cost;
		if($type == 'troop') {
			$users[bmper][$num] = round($users[bmper][$num] + ($amount / $users[troop][$num]) * 10000);
			$users[troop][$num] -= $amount;
		}
		else {
			$users[$type] -= $amount;
		}
		$cansell[$ts] -= $amount;
		$msg .= commas(gamefactor($amount))." $uera[$ts] sold for $".commas(gamefactor($cost)).".<br>\n";
	}
}

foreach($config[troop] as $num => $mktcost) {
	getSellCosts('troop', $num);
}
getSellCosts("food");


/**
 * Expects args
 * troop0, troop1..., food
**/
function bazaarsell($args) {
	global $config, $users, $sell, $msg;
	$sell = $args;

	foreach($config[troop] as $num => $mktcost) {
		sellUnits('troop', $num);
	}
	sellUnits("food");
	sellUnits("runes");

	saveUserData($users,"networth cash troops food bmper");
	return $msg;
}

?>

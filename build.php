<?php
include("header.php");

$dbuild = array();

function printRow ($type) {
	global $users, $uera, $canbuild, $dbuild;
	$dbuild[] = array('name' => $uera[$type], 'type' => $type, 'userAmount' => commas($users[$type]), 'canBuild' => commas($canbuild));
}

function getBuildAmounts () {
	global $users, $config, $urace, $buildcost, $buildrate, $canbuild;
	$buildcost = round($config[buildings] + ($users[land] * 0.1));
	$buildrate = $users[land] * 0.015 + 4;
	// if ($buildrate > 400)	$buildrate = 400;
	$buildrate = round($urace[bpt] * $buildrate);
	$canbuild = floor($users[cash] / $buildcost); // limit by money
	if ($canbuild > $buildrate * $users[turns]) // by turns
		$canbuild = $buildrate * $users[turns];
	if ($canbuild > $users[freeland]) // and by land
		$canbuild = $users[freeland];
}

function buildStructures ($type, $cost) {
	global $users, $build, $totalbuild, $totalspent, $max, $canbuild;
	$amount = $build[$type];
	fixInputNum($amount);
	if ($amount < 0)
		TheEnd("Cannot build a negative number of structures!");
	if (isset($max[$type]))
		$amount = $canbuild;

	$users[$type] += $amount;
	$totalbuild += $amount;
	$users[freeland] -= $amount;
	$users[cash] -= $amount * $cost;
	$totalspent += $amount * $cost;
}

getBuildAmounts();
if ($do_build) { // nothing gets saved until later; if one has invalid input, it'll get caught and will prevent the build
	$totalbuild = $totalspent = 0;
	buildStructures(homes, $buildcost);
	buildStructures(shops, $buildcost);
	buildStructures(industry, $buildcost);
	buildStructures(barracks, $buildcost);
	buildStructures(labs, $buildcost);
	buildStructures(farms, $buildcost);
	buildStructures(towers, $buildcost);
	if ($totalbuild > $canbuild) // this takes into account turns, money, AND free land all at once
		TheEnd("You can't build that many!");
	if ($users[cash] < 0) // in case we decide to add variable building costs
		TheEnd("You don't have enough money!");
	$turns = ceil($totalbuild / $buildrate);

	takeTurns($turns, build);
	echo $turnoutput;
	saveUserData($users, "homes shops industry barracks labs farms towers freeland cash");

	getBuildAmounts();
}


printRow(shops);
printRow(homes);
printRow(barracks);
printRow(industry);
printRow(labs);
printRow(farms);
printRow(towers);


$tpl->assign('cnd', $cnd);
$tpl->assign('authstr', $authstr);
$tpl->assign('do_build', do_build);
$tpl->assign('build', $dbuild);
$tpl->assign('freeland', commas($users[freeland]));
$tpl->assign('canbuild', commas($canbuild));
$tpl->assign('buildrate', commas($buildrate));
$tpl->assign('buildcost', commas($buildcost));
$tpl->assign('turns', commas($turns));
$tpl->assign('totalbuild', commas($totalbuild));
$tpl->assign('totalspent', commas($totalspent));
$tpl->display('build.html');
TheEnd("");

?>

<?php
include("header.php");
require_once("lib/status.php");

$destroyrate = round(($users[land] * 0.02) + 2);
// if ($destroyrate > 400)	$destroyrate = 400;
$destroyrate = floor($urace[bpt] * $destroyrate);
$salvage = round(($config[buildings] + ($users[land] * .1)) / 5);

$totbuildings = $users[land] - $users[freeland];

$destroy_max_labs = ($users[labs] - round($users[wizards]/175) - 1);
if($destroy_max_labs < 0)
	$destroy_max_labs = 0;

$safe = array();

function getDestroyAmount($type) {
	global $config, $users, $candestroy, $destroyrate, $safe, $urace;
	$candestroy[$type] = $destroyrate * $users[turns];
	if ($candestroy[$type] > $users[$type]) $candestroy[$type] = $users[$type];
	$candestroy[all] += $candestroy[$type];
	if ($candestroy[all] > $users[turns] * $destroyrate)
		$candestroy[all] = $users[turns] * $destroyrate;
	if($type == 'labs') {
		$safe['labs'] = ($users[labs] - round($users[wizards]/175) - 1);
	} else if($type == 'homes') {


		/*
		
		$popbase = round((($users[land] * 2) + ($users[freeland] * 5) + ($users[homes] * 60)) / (0.95 + $taxrate + $taxpenalty));
		income = round((($users[land] * 2) + ($users[freeland] * 5) + ($users[homes] * 60)) / (0.95 + $taxrate + $taxpenalty)) * pci;
		income round(((land * 2) + (freeland * 5) + (HOMES * 60)) / (0.95 + $taxrate + $taxpenalty));
		
		We must solve for HOMES = (income < expenses)
		
		popbase * (0.95 + taxrate + taxpenalty) =  $users[land] * 2 + $users[freeland * 5 + uhome * 60
		uhome = (popbase * 0.95+taxrate+taxpenalty) - land*2 - freeland*5
		
		expenses = pop_we_want * round(25 * (1 + $user[shops] / $user[land]) * $race[pci]);
		expenses / pci = pop_we_want
		
		$peasants = ($popbase - $users[peasants]) / 20;
		peasants_we_want * 20 = popbase_we_want
		if ($users['hero_peace'] == 3) // Hestia?
			$pop_base_we_want /= 5;
		x = ($pop_base_we_want * 0.95 + $taxrate + $taxpenalty) - $users[land]*2 - $users[freeland]*5);
		
		
		THERE FORE
		*/
		
		@$low_pop_base = 20 * (calcExpenses() / pci($users, $urace));
		if ($users['hero_peace'] == 3) // Hestia?
			$low_pop_base /= 5;
		
		$taxrate = $users[tax] / 100;
		if ($users[tax] > 40)
			$taxpenalty = ($taxrate - 0.40) / 2;
		if ($users[tax] < 20)
			$taxpenalty = ($taxrate - 0.20) / 2; 
		
		$safe['homes'] = round(($low_pop_base * 0.95 + $taxrate + $taxpenalty) - $users[land]*2 - $users[freeland]*5) - 1; // Lowest possible
		$safe['homes'] = $users['homes'] - $safe['homes'];
		//print("Lowest homes: $safe[homes] Because lowest pop base is $low_pop_base because of " . calcExpenses() . " expenses! with PCI of" . pci($users, $urace));

	} else if($type == 'barracks') {
		$safe['barracks'] = $users['barracks'] - round(0.3 * $users[land]);
	} else {
		$safe[$type] = $candestroy[$type];
	}
	$safe[$type] = max(0, min($users[$type], $safe[$type]));
} 

function destroyStructures ($type, $salvage) {
	global $users, $demolish, $candestroy, $totaldestroyed, $totalsalvaged, $max, $safeck, $safe, $time;
	fixInputNum($demolish[$type]);
	$amount = $demolish[$type];
	if ($amount < 0)
		TheEnd("Cannot demolish a negative number of structures!");
	if ($amount > $candestroy[$type])
		TheEnd("You cannot demolish that many!");
	if (isset($max[$type])) {
		if($type == land)  // there is no max option for land dropping :)
	    	$amount = $users[freeland];
		else
			$amount = $candestroy[$type];
	}
	if (isset($safeck[$type])) {
		if($type == land)
			$amount = 0;
		else
			$amount = $safe[$type];
	}
	$users[$type] -= $amount;
	if ($type == land) {
//	    $lastdroptime = $users['lastdroptime'] - $time + 86400;
//		if($lastdroptime > 0) {
//		   	TheEnd("You can not drop anymore land today.");
//		} else {
//		  	$users['lastdroptime'] = $time + 86400;
//		   	saveUserData($users, "lastdroptime");
            $users[freeland] -= $amount;
//		}
	} else {
		$users[freeland] += $amount;
		$totaldestroyed += $amount;
	} 
	$users[cash] += $amount * $salvage;
	$totalsalvaged += $amount * $salvage;
} 

function destroyLand($type, $salvage) {
	global $users, $demolish, $candestroy, $totaldestroyed, $totalsalvaged, $time;
	fixInputNum($demolish[$type]);
	$amount = $demolish[$type];
	if ($amount < 0)
		TheEnd("Cannot demolish a negative number of structures!");
	if ($amount > $candestroy[$type])
		TheEnd("You can not demolish that much!");
	if($amount > 0) {
		$lastdroptime = $users['lastdroptime'] - $time + 86400;
	   	if($lastdroptime > 0) {
	   		    TheEnd("You can not drop anymore land today.");
	    	} else {
	  		    $users['lastdroptime'] = $time + 86400;
	  	 	    saveUserData($users, "lastdroptime");
          		    $users[freeland] -= $amount;
	         	    $users[land] -= $amount;
	         	    saveUserData($users, "land freeland");
	        }
	}
} 

function printRow($type) {
	global $users, $uera, $candestroy, $safe, $ddemolish;
	$ddemolish[] = array('name' => $uera[$type], 'type' => $type, 'userAmount' => commas($users[$type]), 'safeDestroy' => commas($safe[$type]), 'canDestroy' => commas($candestroy[$type]));
} 

getDestroyAmount(homes);
getDestroyAmount(shops);
getDestroyAmount(industry);
getDestroyAmount(barracks);
getDestroyAmount(labs);
getDestroyAmount(farms);
getDestroyAmount(towers);
$candestroy[land] = round($users[freeland] * ($config['max_land_drop']/100));
if ($do_demolish) {
	destroyStructures(homes, $salvage);
	destroyStructures(shops, $salvage);
	destroyStructures(industry, $salvage);
	destroyStructures(barracks, $salvage);
	destroyStructures(labs, $salvage);
	destroyStructures(farms, $salvage);
	destroyStructures(towers, $salvage);
	destroyLand(land, 0);

	$turns = ceil($totaldestroyed / $destroyrate);
	if ($users[turns] < $turns)
		TheEnd("Not enough turns!");
	if ($users[land] == 0) {
		$users[land] = 1;
		$users[freeland] = 1;
	} 
	takeTurns($turns, demolish);
	echo $turnoutput;
	saveUserData($users, "homes shops industry barracks labs farms towers land freeland cash");

	getDestroyAmount(homes);
	getDestroyAmount(shops);
	getDestroyAmount(industry);
	getDestroyAmount(barracks);
	getDestroyAmount(labs);
	getDestroyAmount(farms);
	getDestroyAmount(towers);
	$candestroy[land] = round($users[freeland] * ($config['max_land_drop']/100));
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
$tpl->assign('do_demolish', $do_demolish);
$tpl->assign('freeland', commas($users[freeland]));
$tpl->assign('demolish', $ddemolish);
$tpl->assign('candestroy', commas($candestroy[land]));
$tpl->assign('allyoucandestroy', commas($candestroy[all]));
$tpl->assign('destroyrate', commas($destroyrate));
$tpl->assign('salvage', commas(gamefactor($salvage)));
$tpl->assign('totaldestroyed', commas($totaldestroyed));
$tpl->assign('turns', $turns);
$tpl->assign('totalsalvaged', commas(gamefactor($totalsalvaged)));
$tpl->assign('gamename', $gamename);
$tpl->assign('uera', $uera);
$tpl->display("demolish.html");
TheEnd("");

?>

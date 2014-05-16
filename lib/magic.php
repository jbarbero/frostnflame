<?
/**
 * current spell functions
 * args:
 * times
 * OR
 * target
**/

function missionSucceed ($msg) {
	global $uera;
	echo 'Your '.$uera[wizards].' perform the mission and it is <span class="cgood">successful!</span><br>';
	echo $msg;
	echo '<br>';
}

function missionFail ($msg='Your %wizards% are <span class="cbad">foiled!</span><br>') {
	global $users, $uera, $wizloss, $total_wizloss, $failed;
	echo str_replace('%wizards%', $uera['wizards'], $msg);
	echo commas(gamefactor($wizloss))." $uera[wizards] are killed!";
	echo '<br>';
	$users[wizards] -= $wizloss;
	$failed = 1;
}

function missionShielded () {
	global $uera;
	echo '<span class="cwarn">...though the enemy\'s '.$uera[wizards].' prevented too much damage.</span><br>';
}

function CastMission($num) {
	global $spfuncs, $called;
	$called = $spfuncs[$num];
	call_user_func($spfuncs[$num]);
}


function getRatios() {
	global $uratio, $lratio, $eratio, $users, $enemy, $urace, $erace;

	$uwiz = $users[wizards];
//	if($uwiz > $users[labs] * 175;
//		$uwiz = $users[labs] * 175;

	if($enemy) {
		$ewiz = $enemy[wizards];
//		if($ewiz > $enemy[labs] * 175;
//			$ewiz = $enemy[labs] * 175;
	}

	if ($users[labs])					// for defense, wizards/towers
		$lratio = $uwiz / $users[labs] * $urace[magic];
	else
		$lratio = 0;

	if($enemy && $enemy[land] != 0) {
		$uratio = $uwiz / (($users[land] + $enemy[land]) / 2) * $urace[magic];
		$eratio = $ewiz / $enemy[land] * 1.05 * $erace[magic];
	}
}

getRatios();


function do_magic($spellname, $args) {
	global $spnumbyname, $sptype, $spcost, $spratio, $spname, $spfuncs, $gamblemessage, $enemy, $eera, $erace;
	global $failed, $produced, $total_wizloss, $wizloss, $hide_turns, $all_t_output, $turnoutput;
	global $users, $urace, $uera, $uclan, $config, $uratio, $eratio, $lratio, $config, $time;
	$failed = 0;
	$oldhealth = $users[health];

	$mission_num = $spnumbyname[$spellname];
	$num_times = $args[times];
	if ($users[turns] < $num_times*2)
		TheEnd("Not enough turns!");
	if ($users[runes] < $spcost["$mission_num"])
		TheEnd("Not enough $uera[runes]!");
	if ($users[health] < 20)
		TheEnd("Due to their waning health, your $uera[wizards] cannot cast any missions.");
	if($num_times < 1 || $num_times > $users[turns]*2)
		$num_times = 1;

	if($spellname == 'missiongamble' && $num_times != 1)
		TheEnd("Gamble may only be performed in separate missions!");

	if ($sptype[$mission_num] == o)	{		// offense mission?
		if(empty($args[target]))
			TheEnd("You must specify a target!");
		$target = $args[target];
		if ($target == $users[num])
			TheEnd("You may not attack yourself!");
		$enemy = loadUser($target);
		if ($enemy[num] != $target)
			TheEnd("No such user!");
		$erace = loadRace($enemy[race], $enemy[era]);
		$eera = loadEra($enemy[era], $enemy[race]);

		if ($enemy[land] == 0)
			TheEnd("That ".$uera[empire]." is dead!");
		if (($users[clan] > 0) && ($users[clan] == $enemy[clan]))
			TheEnd("Cannot attack ".$uera[empire]."s in your clan!");
		if ($enemy[disabled] >= 2)
			TheEnd("Cannot attack disabled ".$uera[empire]."s!");
		if ($enemy[turnsused] <= $config[protection])
			TheEnd("Cannot attack ".$uera[empire]."s under protection!");
		if ($enemy[vacation] > $config[vacationdelay])
			TheEnd("That ".$uera[empire]." is on vacation!");
		if (($enemy[era] != $users[era]) && ($users[gate] <= $time) && ($enemy[gate] <= $time))
			TheEnd("Need to open an Attack Opportunity first!");
		if($sptype[$num] == o && $config['dual_game'])
			if($urace[type] == $erace[type])
				TheEnd("That ".$uera[empire]." is on your side!");

		$warflag = 0;
		$netmult = $config['default_cutoff'];
		if ($enemy[clan]) {
			if ($users[clan] == $enemy[clan])
				TheEnd("Cannot attack ".$uera[empire]."s in your clan!");
			if($enemy[clan] != 0 && $users[clan] != 0) {
				if (($uclan[ally1] == $enemy[clan]) || ($uclan[ally2] == $enemy[clan]) || ($uclan[ally3] == $enemy[clan]) || ($uclan[ally4] == $enemy[clan]) || ($uclan[ally5] == $enemy[clan]))
					TheEnd("Your Generals quietly ignore your orders to attack an Ally.");
				if (($uclan[war1] == $enemy[clan]) || ($uclan[war2] == $enemy[clan])|| ($uclan[war3] == $enemy[clan]) || ($uclan[war4] == $enemy[clan]) || ($uclan[war5] == $enemy[clan])) {
					$warflag = 1;
					$netmult = $config['clan_cutoff'];
				}
			}
		}

		if($users[warset] == $enemy[num])
			$warflag = 1;

		if ($mission_num != 1)
		{
			if ($enemy[networth] > $users[networth] * $netmult && $enemy[clan] != 0)
				TheEnd("Your $uera[wizards] flatly refuse to target such a strong opponent!");
			if ($users[networth] > $enemy[networth] * $netmult && $enemy[clan] != 0)
				TheEnd("Your $uera[wizards] politely refuse your orders to target a defenseless ".$uera[empire]."!");

			if ($warflag == 0)
			{
				if ($enemy[attacks] > $config['max_attacks'])
					TheEnd("Too many recent attacks on that ".$uera[empire].". Try again in one hour.");
				$revolt = 1;
				if ($users[networth] > $enemy[networth] * 2.5) {				// Shame is less powerful than fear
					echo '<span class="cwarn">Your '.$uera[wizards].' are shamed by your attack on such a weak opponent. Many desert!</span><br>';
					$revolt = 1 - $users[networth] / $enemy[networth] / 125;
				}
				elseif ($enemy[networth] > $users[networth] * 2.5) {
					echo '<span class="cwarn">Your '.$uera[wizards].' tremble at your attack on such a strong opponent. Many desert!</span><br>';
					$revolt = 1 - $users[networth] / $enemy[networth] / 100;
				}
				if ($revolt < .9)
					$revolt = .9;
				$users[wizards] = ceil($users[wizards] * $revolt);
			}
			if ($warflag == 0)
				$enemy[attacks]++;
			$users[attacks] -= $config['wizatk_decr'];
			if ($users[attacks] < 0) $users[attacks] = 0;
			$users[health] -= 4;
			$users[offtotal]++;
			$enemy[deftotal]++;
		}
		getRatios();
	}
	if ($users[labs])					// for defense, wizards/towers
		$lratio = $users[wizards] / $users[labs] * $urace[magic];
	else	$lratio = 0;

	// lose 1%-5% of your wizards if mission fails
	$wizloss = mt_rand(ceil($users[wizards] * .01),ceil($users[wizards] * .05 + 1));
	if ($wizloss > $users[wizards])
		$wizloss = $users[wizards];
		//print("Wizloss calced during do_magic reads: $wizloss with total, $total_wizloss (added after statement)");
	$total_wizloss += $wizloss;

	if($num_times*2 > $users[turns])
		TheEnd("Not enough turns!");

	ob_start();
	$i = 0;
	$oldturns = $users[turns];

	$all_t_output = '';

	global $turnoutput, $missions, $shmod, $users;
	$taken = 0;
	for($i=0; $i<$num_times; $i += 1) {
		if ((1 <= $mission_num) && ($mission_num <= $missions))
		{
			if ($enemy[shield] > $time)
				$shmod = 1/3;
			else	$shmod = 1;

			CastMission($mission_num);
		}
		if($users[runes] < $spcost[$mission_num])
			break;
		$users[runes] -= $spcost[$mission_num];
		saveUserData($users,"attacks offsucc offtotal kills");
		if ($enemy[num])
			saveUserData($enemy,"l_attack attacks defsucc deftotal");

		global $noutput;
		$noutput = true;
		$taken += 2;
		takeTurns(2, magic, $noutput, $taken);

		if(!$hide_turns)
			$all_t_output .= $turnoutput;

		if($users[cash] <= 0 || $users[food] <= 0 || $users[runes] <= 0 || $failed == 1)
			break;
	}

	if($sptype[$mission_num] == d) {
		if($failed != 1) {
			switch($spellname) {
				case 'missionshield':	missionSucceed("Your $uera[wizards] are now patrolling the perimeter of your ".$uera[empire]."!"); break;
				case 'missionfood':	missionSucceed("Your $uera[wizards] have foraged up ".commas(gamefactor($produced))." food!"); break;
				case 'missiongold':	missionSucceed("Your $uera[wizards] have looted up \$".commas(gamefactor($produced))."!"); break;
				case 'missioned':	missionSucceed("Your $uera[wizards] have scouted up ".commas($produced)." acres! However, some $uera[wizards], food, and cash were lost in the effort."); break;
				case 'missionheal':	missionSucceed("Your $uera[wizards] tend to your empire's wounds. Your health incresases by ".($users[health]-$oldhealth)."%."); break;
			        case 'missiongate':	missionSucceed("You have prepared your $uera[wizards] to carry your troops to any region, however distant!"); break;
        			case 'missionungate':	missionSucceed("You have recalled your $uera[wizards]."); break;
        			case 'missionadvance':	missionSucceed("Your $uera[wizards] have coordinated the movement of your entire army North!"); break;
	      			case 'missionsouth':	missionSucceed("Your $uera[wizards] have coordinated the movement of your entire army South!"); break;
				case 'missionkill':	missionSucceed("Your $uera[wizards] have completed the ritual suicide of your troops and workers!"); break;
				case 'missionprod':	missionSucceed("Your $uera[wizards] have 'prodded' the market..."); break;
				case 'missionpeasant':	missionSucceed("Your $uera[wizards] have recruited ".commas($produced)." new ".$uera['peasants']."!"); break;
				case 'missiongamble':	missionSucceed($gamblemessage);
			}
		}
	}

	if($all_t_output == '')
		$all_t_output = $turnoutput;

	return $all_t_output;
}


function fn_murder($args) {		do_magic('missionblast',	$args);	}
function fn_raisedefences($args) {	do_magic('missionshield',	$args);	}
function fn_poison($args) {		do_magic('missionstorm',	$args);	}
function fn_destroyrunes($args) {	do_magic('missionrunes',	$args);	}
function fn_destroystructures($args) {	do_magic('missionstruct',	$args);	}
function fn_hawkforage($args) {		do_magic('missionfood',		$args);	}
function fn_hawkloot($args) {		do_magic('missiongold',		$args);	}
function fn_hawkscout($args) {		do_magic('missioned',		$args);	}
function fn_hawkheal($args) {		do_magic('missionheal',		$args);	}
function fn_recruit($args) {		do_magic('missionpeasant',	$args);	}
function fn_prodmarket($args) {		do_magic('missionprod',		$args);	}
function fn_seppuku($args) {		do_magic('missionkill',		$args);	}
function fn_preparehawks($args) {	do_magic('missiongate',		$args);	}
function fn_recallhawks($args) {	do_magic('missionungate',	$args);	}
function fn_hawkattack($args) {		do_magic('missionfight', 	$args);	}
function fn_embezzle($args) {		do_magic('missionsteal', 	$args);	}
function fn_rob($args) {		do_magic('missionrob',		$args);	}
function fn_movenorth($args) {		do_magic('missionadvance', 	$args);	}
function fn_movesouth($args) {		do_magic('missionsouth', 	$args);	}

?>

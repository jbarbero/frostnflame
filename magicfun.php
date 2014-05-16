<?
if(!defined("PROMISANCE"))
	die(" ");
include("spells.php");
include("lib/magic.php");

function printMRow ($num) {
	global $spname, $sptype, $spcost, $spratio, $uera, $users, $lratio;
	$class = '';
	if($users['runes'] < $spcost[$num])
		$class = 'cwarn';
	if($sptype[$num] == 'd')
		if($lratio <= $spratio[$num])
			$class = 'cbad';

	echo "<option value='$num' class='$class'";
	if($num == $_POST['mission_num'])
		echo ' selected';
	echo '>';
	echo $spname[$num].' ('.commas(gamefactor($spcost[$num])).' '.$uera['runes'].')';
	if($sptype[$num] == 'd')
		echo ' (Self)';
	echo '</option>';
}

$spcounter = 1;
define('o', 'o');
define('d', 'd');

function addsp($name, $mana, $type, $ratio, $funcname, $newsnum) {
	global $users, $urace, $sptype, $spcost, $spratio, $spname, $spcounter, $missions, $spfuncs, $spnews, $spnumbyname;
	if(!$spcounter) $spcounter = 1;
	// this chunk sets the costs of missions
	// Global Spcounter was acting STRANGE...print("$name - " . $spcounter . " -- Counter<br />");
	// array was created so that it's easier to reference
	$manabase = ($users[land] * .1) + 100 + ($users[labs] * .2) * $urace[magic] * calcSizeBonus($users[networth]);
	// sptype is "o" for offense, "d" for defense
	$spname[$spcounter] = $name;
	$spnumbyname[$funcname] = $spcounter;	// easier for scripting
	$spcost[$spcounter] = ceil($manabase * $mana);
	$sptype[$spcounter] = $type;
	if($users['hero_war'] == 3)			// Hermes?
		$ratio *= 0.75;
	if($type == d)
		$spratio[$spcounter] = $ratio * $config['wpl'] / 100;
	else
		$spratio[$spcounter] = $ratio;
	$spfuncs[$spcounter] = $funcname;
	$spnews[$newsnum] = $spcounter;// Used in the news teller
	$missions = $spcounter;
	//print("$spname[$spcounter]  -- Name<br />");
	$spcounter += 1;
}

global $config;
addsp($config['missionspy'], 		1.00,	o, 1.00,	'missionspy',		201	);
addsp($config['missionblast'], 		2.50,	o, 1.15,	'missionblast',		202	);
addsp($config['missionshield'], 	4.90,	d, 15,		'missionshield',	0	);
addsp($config['missionstorm'], 		7.25,	o, 1.21,	'missionstorm',		204	);
addsp($config['missionrunes'],		9.50,	o, 1.30,	'missionrunes',		205	);
addsp($config['missionstruct'],		18.00,	o, 1.70,	'missionstruct',	206	);
addsp($config['missionfood'], 		17.00,	d, 30,		'missionfood',		0	);
addsp($config['missiongold'], 		17.50,	d, 30,		'missiongold',		0	);
addsp($config['missioned'],		18.00,	d, 40,		'missioned',		0	);
addsp($config['missionheal'],		19.00,	d, 40,		'missionheal',		0	);
addsp($config['missionpeasant'],	22.00,	d, 55,		'missionpeasant',	0	);
addsp($config['missionprod'],		23.00,	d, 60,		'missionprod',		0	);
addsp($config['missionkill'],		45.00,	d, 45,		'missionkill',		0	);
addsp($config['missiongate'],		20.00,	d, 65,		'missiongate',		0	);
addsp($config['missionungate'],		20.50,	d, 70,		'missionungate',	0	);
addsp($config['missionfight'],		22.75,	o, 50,		'missionfight',		211	);
addsp($config['missionsteal'],		25.00,	o, 1.75,	'missionsteal',		212	);
addsp($config['missionrob'],		26.00,	o, 1.75,	'missionrob',		213	);
addsp($config['missionadvance'],	37.50,	d, 80,		'missionadvance',	0	);
addsp($config['missionback'],		37.50,	d, 80,		'missionsouth',		0	);


if ($do_mission)
{	
	if ($mission_num == $spnumbyname['missionkill'] && (!$jsenabled && $php_block!="Yes")) {
		?>
		Performing Seppuku will destroy ALL of your troops and peasants as well as a portion of your <?=$uera[wizards]?>. Do you still want to perform this mission?
		<form method="post" action="?magic<?=$authstr?>">
			<input type="submit" name="php_block" value="Yes">
			<input type="submit" name="php_block" value="No">
			<input type="hidden" name="do_mission" value="yes">
			<input type="hidden" name="mission_num" value="<?=$mission_num?>">
			<input type="hidden" name="num_times" value="<?=$num_times?>">
			<input type="hidden" name="hide_turns" value="<?=$hide_turns?>">
		</form>
		<?
		TheEnd("");
	}
	if ($mission_num == 0)
		TheEnd("You must specify a mission!");
	$ret = do_magic(
		$spfuncs[$mission_num],
		array(	times => $_POST['num_times'],
			target => $_POST['target'],
			)
		);
	echo $ret;

}
?>

<?
/**
 * expects args:
 * num, type, hide
**/
function use_turns($args) {
	global $users;

	$num = $args[num];
	$type = "none";
	if(isset($args[type]))
		$type = $args[type];
	$type = $args[type];
	$hide = false;
	if(isset($args[hide]))
		if($args[hide])
			$hide = true;

	fixInputNum($num);

	if($num > $users[turns])
		TheEnd("You don't have enough turns!");
	if($num < 0)
		TheEnd("You can't use a negative amount of turns!");
	$used = takeTurns($num, $type, $hide);
	return $used;
}


function fn_forage($args) {
	$args[type] = 'farm';
	$tused = use_turns($args);

	global $foodgained;
	$foodgained = gamefactor($foodgained);
	return sprintf("You ".($foodgained>=0?"gained":"lost")." %s food in %s turns.", substr(commas($foodgained), ($foodgained>=0?0:1)), $tused);
}

function fn_cash($args) {
	$args[type] = 'cash';
	$tused = use_turns($args);

	global $cashgained;
	$cashgained = gamefactor($cashgained);
	return sprintf("You ".($cashgained>=0?"gained":"lost")." \$%s in %s turns.", substr(commas($cashgained), ($cashgained>=0?0:1)), $tused);
}

function fn_rune($args) {
	$args[type] = 'runes';
	$tused = use_turns($args);

	global $runesgained;
	$runesgained = gamefactor($runesgained);
	return sprintf("You gained %s runes in %s turns.", commas($runesgained), $tused);
}

function fn_land($args) {
	$args[type] = 'land';
	$tused = use_turns($args);

	global $landgained;
	return sprintf("You gained %s acres in %s turns.", commas($landgained), $tused);
}

function fn_heal($args) {
	$message = 'Your health increased a total of $gained%.';
	global $users;

	$args[type] = 'heal';

	$oldhealth = $users[health];
	$output = use_turns($args);
	$message = str_replace('$gained', commas($users[health]-$oldhealth), $message);
	$message = str_replace('$turns', $output, $message);

	return $message;
}

function fn_ind($args) {
	$args[type] = 'industry';
	$tused = use_turns($args);
	return sprintf("You spent %s turns gaining extra troops.", $tused);
}
?>

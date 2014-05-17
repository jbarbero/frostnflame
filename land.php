<?
include('header.php');

$button = 'Scout';
$suffix = 'scouting';
$ad = 'For each turn you spend scouting, you can get about <b>'.gimmeLand($users[land],$urace[expl],$users[era]).'</b> acres of land.';
$actiontype = $action;		// change this if you rename the file to something different than in taketurns() function

if (isset($_POST['do_use'])) {
	$msg = fn_land(array(	num  => $use_turns,
				hide => $hide_turns)
			);
}

template_display('turnuse.html');

TheEnd('');
?>

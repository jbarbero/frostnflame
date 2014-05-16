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
	$tpl->assign('message', $msg);
}

$tpl->assign('admessage', $ad);
$tpl->assign('turntype', $action);
$tpl->assign('doingwhat', $suffix);
$tpl->assign('buttontext', $button);

$tpl->display('turnuse.html');

TheEnd('');
?>

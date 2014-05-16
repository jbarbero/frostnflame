<?
include('header.php');

$button = 'Recruit';
$suffix = 'recruiting';
$ad = 'For each turn you spend recruiting, you get 25% more troops.';
$actiontype = "industry";          // change this if you rename the file to something different than in taketurns() function

if (isset($_POST['do_use'])) {
	$msg = fn_ind(array(	num  => $use_turns,
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


<?php
include("header.php");

$produce[] = "";
$produce[] = "Money";
$produce[] = "$uera[food]";
$produce[] = "$uera[runes]";
$produce[] = "Troops";

$tpl->assign('produce_array', $produce);


//money
if ((isset($_POST['do_use'])) && ($_POST['produce_type'] == $produce[1])) {
	$msg = fn_cash(array(num => $use_turns,
			hide => $hide_turns)
		);
	$tpl->assign('message', $msg);
} 

//food
if ((isset($_POST['do_use'])) && ($_POST['produce_type'] == $produce[2])) {
	$msg = fn_forage(array(	num => $use_turns,
				hide => $hide_turns)
		);
	$tpl->assign('message', $msg);
} 

//energy
if ((isset($_POST['do_use'])) && ($_POST['produce_type'] == $produce[3])) {
	$msg = fn_rune(array(	num  => $use_turns,
				hide => $hide_turns)
			);
	$tpl->assign('message', $msg);
}

//industry
if ((isset($_POST['do_use'])) && ($_POST['produce_type'] == $produce[4])) {
	$msg = fn_ind(array( num  => $use_turns,
				hide => $hide_turns)
			);
        $tpl->assign('message', $msg);
}


$tpl->assign('admessage', $ad);

$tpl->assign('food', $uera[food]);
$tpl->assign('runes', $uera[runes]);

$tpl->display('produce.html');

TheEnd('');

?>

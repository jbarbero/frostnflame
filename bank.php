<?php
include("header.php");
$size = calcSizeBonus($users[networth]);
$loanrate = $config[loanbase] + $size;
$savrate = $config[savebase] - $size;

$maxloan = $users['networth'] * $config['maxloan'];
$maxsave = $users['networth'] * $config['maxsave'];

if ($do_borrow) {
	if ($lastweek)
		TheEnd("Cannot take out loans during the last week of the game!");
	fixInputNum($borrow);
	$borrow = invfactor($borrow);
	if(!empty($_POST['borrow_max']))
		$borrow = max(0, $maxloan-$users[loan]);
	if ($borrow < 0)
		TheEnd("Cannot take out a negative loan.");
	if ($borrow + $users[loan] > $maxloan)
		TheEnd("Cannot take out a loan for that much!");
	$users[cash] += $borrow;
	$users[loan] += $borrow;
	saveUserData($users, "networth cash loan");
} 
if ($do_repay) {
	fixInputNum($repay);
	$repay = invfactor($repay);
	if(!empty($_POST['repay_max']))
		$repay = min($users[cash], $users[loan]);
	if ($repay > $users[cash])
		TheEnd("You don't have that much money!");
	if ($repay > $users[loan])
		TheEnd("You don't owe that much!");
	$users[cash] -= $repay;
	$users[loan] -= $repay;
	saveUserData($users, "networth cash loan");
} 
if ($do_deposit) {
	fixInputNum($deposit);
	$deposit = invfactor($deposit);
	if(!empty($_POST['deposit_max']))
		$deposit = min($users[cash], $maxsave-$users[savings]);
	if ($deposit > $users[cash])
		TheEnd("You don't have that much money!");
	if ($deposit < 0)
		TheEnd("You cannot deposit a negative amount of money!");
	if ($deposit + $users[savings] > $maxsave)
		TheEnd("Cannot have that much in savings!");
	$users[cash] -= $deposit;
	$users[savings] += $deposit;
	saveUserData($users, "networth cash savings");
} 
if ($do_withdraw) {
	fixInputNum($withdraw);
	$withdraw = invfactor($withdraw);
	if(!empty($_POST['withdraw_max']))
		$withdraw = $users[savings];
	if ($withdraw > $users[savings])
		TheEnd("You don't have that much in your savings account!");
	$users[cash] += $withdraw;
	$users[savings] -= $withdraw;
	saveUserData($users, "networth cash savings");
} 

$tpl->assign('borrow', commas($borrow));
$tpl->assign('repay', commas($repay));
$tpl->assign('deposit', commas($deposit));
$tpl->assign('withdraw', commas($withdraw));
$tpl->assign('maxsave',commas($maxsave));
$tpl->assign('savings',commas($users[savings]));
$tpl->assign('maxloan',commas($maxloan));
$tpl->assign('loan',commas($users[loan]));
if ($users[turnsused] < $config[protection])
    $protectnotice=1;
template_display('bank.html');

TheEnd("");

?>

<?php
include("header.php");

/*
$maxsave = 0;  // we don't allow turn deposits
if($do_deposit) {
    fixInputNum($deposit);
    $deposit = invfactor($deposit);
    if(!empty($_POST['deposit_max']))
        $deposit = min($users['turns'], $maxsave-$users['turnbank']);
    if ($deposit > $users['turns'])
        TheEnd("You don't have that many turns!");
    if ($deposit < 0)
        TheEnd("You cannot deposit a negative amount of turns!");
    if ($deposit + $users['turnbank'] > $maxsave)
        TheEnd("Cannot have that much in your turn bank!");
    $users['turns'] -= $deposit;
    $users['turnbank'] += $deposit;
    saveUserData($users, "networth turns turnbank");
}
*/ 
if($do_withdraw) {
//global $users;
//print_r($users);
    if($withdraw < 0)
        TheEnd("You cannot withdraw a negative amount of turns!");
    fixInputNum($withdraw);
    //$withdraw = invfactor($withdraw);
    if(!empty($_POST['withdraw_max']))
        $withdraw = $users['turnbank'];
    if($withdraw > $users['turnbank'])
        TheEnd("You don't have that much in your turn bank!");
    $users['turns'] += $withdraw;
    $users['turnbank'] -= $withdraw;
    saveUserData($users, "networth turns turnbank");
}

if ($users['turnsused'] < $config['protection'])
    $protectnotice = 1;

template_display('turnbank.html');
TheEnd("");
?>

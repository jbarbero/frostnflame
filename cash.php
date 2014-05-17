<?php
include("header.php");

$button = 'Trade';
$suffix = 'trading';
$ad = 'For each turn you spend trading, you can get about 25% more cash.';
$actiontype = $action; // change this if you rename the file to something different than in taketurns() function

if (isset($_POST['do_use'])) {
    $msg = fn_cash(array(num => $use_turns,
            hide => $hide_turns)
        );
} 

template_display('turnuse.html');

TheEnd('');

?>

<?php
include("header.php");

$button = 'Forage';
$suffix = 'foraging';
$ad = 'For each turn you spend foraging, you get 25% more food.';
$actiontype = $action; // change this if you rename the file to something different than in taketurns() function

if (isset($_POST['do_use'])) {
    $msg = fn_forage(array(    num => $use_turns,
                hide => $hide_turns)
        );
} 

template_display('turnuse.html');

TheEnd('');

?>


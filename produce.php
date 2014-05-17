<?php
include("header.php");

$produce[] = "";
$produce[] = "Money";
$produce[] = "$uera[food]";
$produce[] = "$uera[runes]";
$produce[] = "Troops";

//money
if ((isset($_POST['do_use'])) && ($_POST['produce_type'] == $produce[1])) {
    $msg = fn_cash(array(num => $use_turns,
            hide => $hide_turns)
        );
} 

//food
if ((isset($_POST['do_use'])) && ($_POST['produce_type'] == $produce[2])) {
    $msg = fn_forage(array(    num => $use_turns,
                hide => $hide_turns)
        );
} 

//energy
if ((isset($_POST['do_use'])) && ($_POST['produce_type'] == $produce[3])) {
    $msg = fn_rune(array(    num  => $use_turns,
                hide => $hide_turns)
            );
}

//industry
if ((isset($_POST['do_use'])) && ($_POST['produce_type'] == $produce[4])) {
    $msg = fn_ind(array( num  => $use_turns,
                hide => $hide_turns)
            );
}

template_display('produce.html');

TheEnd('');

?>

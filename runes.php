<?
include('header.php');

$button = 'Write';
$suffix = 'writing runes';
$ad = 'For each turn you spend writing, you can make 25% more runes.';
$actiontype = $action;        // change this if you rename the file to something different than in taketurns() function

if (isset($_POST['do_use'])) {
    $msg = fn_rune(array(    num  => $use_turns,
                hide => $hide_turns)
            );
}

template_display('turnuse.html');

TheEnd('');
?>

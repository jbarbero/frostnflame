<?
include('header.php');

$button = 'Heal';
$suffix = 'healing';
$ad = 'For each turn you spend healing, your health goes up 2%. After the tax rate limit, your health goes up only 1% for each turn spent healing. If your health is at 100%, further healing will have no effect, so don\'t heal too much.';
$actiontype = $action;        //' change this if you rename the file to something different than in taketurns() function

if (isset($_POST['do_use'])) {
    $msg = fn_heal(array(    num  => $use_turns,
                hide => $hide_turns)
            );
}

template_display('turnuse.html');

TheEnd('');
?>

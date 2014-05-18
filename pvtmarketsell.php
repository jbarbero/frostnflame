<?
include("header.php");
include("lib/pvtsellcron.php");
include("lib/pvtmarketsell.php");

function printRow ($type, $num='')
{
    global $users, $uera, $costs, $cansell, $disp_array;
    if($type == 'troop') {
        $umt = $users[troop][$num];
        $type = $type.$num;
    } else
        $umt = $users[$type];

    $disp_array[] = array(    name    => $uera[$type],
                amt    => commas($umt),
                cost    => commas($costs[$type]),
                cansell    => commas($cansell[$type]),
                type    => $type);
}


$msg = '';
$disp_array = array();

if ($do_sell) {
    foreach($sell as $var => $value) {
        if(isset($max[$var]))
            $sell[$var] = 'max';
    }

    $msg = bazaarsell($sell);
}

foreach($config[troop] as $num => $mktcost) {
    getSellCosts(troop, $num);
}
getSellCosts("food");

foreach($config[troop] as $num => $mktcost) {
    printRow(troop, $num);
}
printRow("food");

$types = $disp_array;
template_display('pvtmarketsell.html');

TheEnd("");

?>

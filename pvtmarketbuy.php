<?
include("header.php");
include("lib/pvtbuycron.php");
include("lib/pvtmarketbuy.php");

function printRow ($type, $num='')
{
    global $users, $uera, $costs, $canbuy, $disp_array;
    if($type == 'troop') {
        $umktmt = $users[pmkt][$num];
        $umt = $users[troop][$num];
        $type = $type.$num;
    }
    else {
        $umktmt = $users["pmkt_$type"];
        $umt = $users[$type];
    }
    $disp_array[] = array(    name    => $uera[$type],
                amt    => commas($umt),
                mkt    => commas($umktmt),
                cost    => commas($costs[$type]),
                canbuy    => commas($canbuy[$type]),
                type    => $type);
}

$msg = '';
$disp_array = array();

foreach($config[troop] as $num => $mktcost) {
    getBuyCosts('troop', $num);
}
getBuyCosts("food");

if ($do_buy) {
    foreach($buy as $var => $value) {
        if(isset($max[$var]))
            $buy[$var] = 'max';
    }

    $msg = bazaarbuy($buy);
    $tpl->assign('printmessage', $msg);
}

foreach($config[troop] as $num => $mktcost) {
    getBuyCosts(troop, $num);
}
getBuyCosts("food");

foreach($config[troop] as $num => $mktcost) {
    printRow(troop, $num);
}
printRow("food");

$tpl->assign('uera', $users[era]);
$tpl->assign('types', $disp_array);

$tpl->display('pvtmarketbuy.html');

TheEnd("");
?>

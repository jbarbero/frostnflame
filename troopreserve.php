<?
include("header.php");

function getReserveAmount ($type, $num='') {
    global $users, $config, $urace, $reserved, $canreserve;
    $ts = $type.$num;

    $reserving = ($users[troop_res][$num]);
    $reserved[$ts] = round($reserving);
    if($reserved[$ts] < 0)
        $reserved[$ts] = 0;
        
    $reservable = (($config['reserveperc'] * ($users[troop][$num] + $users[troop_res][$num])) - $users[troop_res][$num]);
    $canreserve[$ts] = round($reservable);
    if($canreserve[$ts] < 0)
        $canreserve[$ts] = 0;
}

function reserveUnits ($type, $num='') {
    global $users, $uera, $reserve, $reserved, $canreserve, $msg;
    
    $ts = $type.$num;
    $amount = $reserve[$ts];
            
    if($amount == 'max') {
        $amount = $canreserve[$ts];
    }
    else {
        fixInputNum($amount);
        $amount = invfactor($amount);
    }
    if ($amount < 0)
        $msg .= "Cannot reserve a negative amount of ".$uera["troop$ts"]."!<br>\n";
    elseif ($amount > $canreserve[$ts])
        $msg .= "You cannot reserve that many $uera[$ts]!<br>\n";
    else
    {
        $users[troop][$num] -= $amount;
        $users[troop_res][$num] += $amount;
        
        $canreserve[$ts] -= $amount;
        $reserved[$ts] += $amount;
        if ($amount > 0)
            $msg .= commas(gamefactor($amount))." $uera[$ts] reserved.<br>\n";
    }
}

foreach($config[troop] as $num => $mktcost) {
    getReserveAmount('troop', $num);
}
//getReserveAmount("food");

function troopreserve($args) {
    global $config, $users, $reserve, $msg;
    $reserve = $args;

    foreach($config[troop] as $num => $mktcost) {
        reserveUnits('troop', $num);
    }
    //reserveUnits("food");
    //reserveUnits("runes");

    saveUserData($users,"networth cash troops troops_res");
    return $msg;
}

function printRow ($type, $num='')
{
    global $users, $uera, $costs, $reserved, $canreserve, $disp_array;
    if($type == 'troop') {
        $umt = $users[troop][$num];
        $ts = $type.$num;
    } else
        $umt = $users[$ts];

    $disp_array[] = array(
                name        => $uera[$ts],
                amt            => commas($umt),
                reserved    => commas($reserved[$ts]),
                canreserve    => commas($canreserve[$ts]),
                type        => $ts);
}

$msg = '';
$disp_array = array();

if ($do_reserve) {
    foreach($reserve as $var => $value) {
        if(isset($max[$var]))
            $reserve[$var] = 'max';
    }

    $msg = troopreserve($reserve);
    $tpl->assign('printmessage', $msg);
}

foreach($config[troop] as $num => $mktcost) {
    getReserveAmount(troop, $num);
}
//getReserveAmount("food");

foreach($config[troop] as $num => $mktcost) {
    printRow(troop, $num);
}
//printRow("food");

$tpl->assign('uera', $users[era]);
$tpl->assign('types', $disp_array);

$tpl->display('troopreserve.html');

TheEnd("");

?>

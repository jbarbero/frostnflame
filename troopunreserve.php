<?
include("header.php");

function getUnreserveAmount ($type, $num='') {
    global $users, $config, $urace, $canunreserve;
    $ts = $type.$num;

    $canunreserve[$ts] = round($users[troop_res][$num]);
    if($canunreserve[$ts] < 0)
        $canunreserve[$ts] = 0;
}

function unreserveUnits ($type, $num='') {
    global $playerdb, $users, $uera, $unreserve, $canunreserve, $msg;
    
    $ts = $type.$num;
    $amount = $unreserve[$ts];
            
    if($amount == 'max') {
        $amount = $canunreserve[$ts];
    }
    else {
        fixInputNum($amount);
        $amount = invfactor($amount);
    }
    
    $reserved = mysqli_fetch_array(db_safe_query("SELECT troops_res FROM $playerdb WHERE num=$users[num];"));
        
    if (!$reserved[troops_res])
        return;
        
    $reserved = explode("|", $reserved[troops_res]);
        
    if ($amount > $reserved[$num])
        $amount = $reserved[$num];
        
    if ($amount == 0)
        return;
    if ($amount < 0)
        $msg .= "Cannot unreserve a negative amount of $uera[$ts]!<br>\n";
    else
    {
        $users[troop][$num] += $amount;
        $users[troop_res][$num] -= $amount;
        
        $canunreserve[$ts] -= $amount;
        $msg .= commas(gamefactor($amount))." $uera[$ts] unreserved.<br>\n";
    }
}


foreach($config[troop] as $num => $mktcost) {
    getunReserveAmount('troop', $num);
}
//getUnreserveAmount("food");



function troopunreserve($args) {
    global $config, $users, $reserve, $msg;
    $unreserve = $args;

    foreach($config[troop] as $num => $mktcost) {
        unreserveUnits('troop', $num);
    }
    //unreserveUnits("food");
    //unreserveUnits("runes");

    saveUserData($users,"networth cash troops troops_res");
    return $msg;
}



function printRow ($type, $num='')
{
    global $users, $uera, $costs, $canunreserve, $disp_array;
    if($type == 'troop') {
    
        //$users[troop_res][$num] = round($users[troop_res][$num] + ($amount / $users[troop][$num]) * 10000);
    
        $umt = $users[troop_res][$num];
        $ts = $type.$num;
    } else
        $umt = $users[$ts];

    $disp_array[] = array(    name    => $uera[$ts],
                amt    => commas($umt),
                cost    => commas($costs[$ts]),
                type    => $ts);
}


$msg = '';
$disp_array = array();

if ($do_unreserve) {
    foreach($unreserve as $var => $value) {
        if(isset($max[$var]))
            $unreserve[$var] = 'max';
    }

    $msg = troopunreserve($unreserve);
}

foreach($config[troop] as $num => $mktcost) {
    getUnreserveAmount(troop, $num);
}
//getUnreserveAmount("food");

foreach($config[troop] as $num => $mktcost) {
    printRow(troop, $num);
}
//printRow("food");

$types = $disp_array;

template_display('troopunreserve.html');

TheEnd("");

?>

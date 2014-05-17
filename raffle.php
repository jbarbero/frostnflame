<?
include("header.php");

if ($lockdb)
    TheEnd("Raffle is currently not available!");

$jtyps = array('cash', 'food');

$tickcost = array();
$tickcost[cash] = round($users[networth] / 2);
$tickcost[food] = $tickcost[cash] / $config[food];

$num_all_tickets = db_safe_firstval("SELECT COUNT(*) FROM $lotterydb WHERE num!=0") + 5;

foreach($jtyps as $jtyp) {
    $jackpot[$jtyp] = db_safe_firstval("SELECT amt FROM $lotterydb WHERE num=0 AND ticket=$tick_curjp AND jtyp='$jtyp';");
    $lastjackpot[$jtyp] = db_safe_firstval("SELECT amt FROM $lotterydb WHERE num=0 AND ticket=$tick_lastjp AND jtyp='$jtyp';");
    $lastnum[$jtyp] = db_safe_firstval("SELECT amt FROM $lotterydb WHERE num=0 AND ticket=$tick_lastnum AND jtyp='$jtyp';");
    $lastwin[$jtyp] = db_safe_firstval("SELECT amt FROM $lotterydb WHERE num=0 AND ticket=$tick_lastwin AND jtyp='$jtyp';");
    $jackpotgrow[$jtyp] = db_safe_firstval("SELECT amt FROM $lotterydb WHERE num=0 AND ticket=$tick_jpgrow AND jtyp='$jtyp';");
    $tickets[$jtyp] = db_safe_query("SELECT * FROM $lotterydb WHERE num=$users[num] AND jtyp='$jtyp';");
}


if ($do_ticket) {
    foreach($jtyps as $jtyp) {
        $name = "lb$jtyp";
        if(isset($$name))
            break;
    }
    if (mysqli_num_rows($tickets[$jtyp]) >= $maxtickets)
        TheEnd("You can't buy any more $jtyp tickets!");
        if ($users[$jtyp] < $tickcost[$jtyp])
        TheEnd("You don't have enough for a ticket!");
    else {
        $ticknum = $num_all_tickets+1;
        $jackpot[$jtyp] += $tickcost[$jtyp];
        sqlQuotes($jtyp);
        fixInputNum($tickcost[$jtyp]);
        fixInputNum($jackpot[$jtyp]);
        db_safe_query("INSERT INTO $lotterydb (num,ticket,amt,jtyp) VALUES ($users[num],$ticknum,$tickcost[$jtyp],'$jtyp');");
        db_safe_query("UPDATE $lotterydb SET amt=$jackpot[$jtyp] WHERE num=0 AND ticket=$tick_curjp AND jtyp='$jtyp';");
        $users[$jtyp] -= $tickcost[$jtyp];
        saveUserData($users,"networth food cash");
    }
    foreach($jtyps as $jtyp)
        $tickets[$jtyp] = db_safe_query("SELECT * FROM $lotterydb WHERE num=$users[num] AND jtyp='$jtyp';");
}


$tpl->assign('cashcost', $tickcost[cash]);
$tpl->assign('foodcost', $tickcost[food]);
$tpl->assign('cashpot', commas($jackpot[cash]));
$tpl->assign('foodpot', commas($jackpot[food]));

$tpl->assign('numcasht', db_safe_firstval("SELECT COUNT(*) FROM $lotterydb WHERE num!=0 AND jtyp='cash';"));
$tpl->assign('numfoodt', db_safe_firstval("SELECT COUNT(*) FROM $lotterydb WHERE num!=0 AND jtyp='food';"));

$tpl->assign('numuct', mysqli_num_rows($tickets['cash']));
$tpl->assign('numuft', mysqli_num_rows($tickets['food']));
$tpl->assign('maxtickets', $maxtickets);

$tpl->assign('ucash', $users['cash']);
$tpl->assign('ufood', $users['food']);


foreach($jtyps as $jtyp) {
    $enemy = loadUser($lastwin[$jtyp]);
    $tpl->assign('last_'.$jtyp.'n', $lastnum[$jtyp]);
    $tpl->assign('last_'.$jtyp.'e', "$enemy[empire] <a class=proflink href=?profiles&num=$enemy[num]$authstr>(#$enemy[num])</a>");
    $tpl->assign('last_'.$jtyp.'w', commas($lastjackpot[$jtyp]));

    $ticks = array();
    while($ticket = mysqli_fetch_array($tickets[$jtyp]))
        $ticks[] = $ticket;

    $tpl->assign($jtyp.'_ticks', $ticks);
}


$tpl->display('raffle.html');
TheEnd();
?>

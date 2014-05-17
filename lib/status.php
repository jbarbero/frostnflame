<?
if(!defined("PROMISANCE"))
    die(" ");
$size = calcSizeBonus($users[networth]);


function calcFoodCon() {
    global $users, $config, $urace; 
    
    $foodcon = 0;
    foreach($config[troop] as $num => $mktcost) {
        $foodcon += $users[troop][$num] * 25 / $mktcost;
        $foodcon += $config['market_upkeep'] * $users[pubmarket][$num] * 25 / $mktcost;
    }
    
    $foodcon += $users[wizards] * .25 * $config[wpl]/100;
    $foodcon *= $urace[food];
    
    if($users['hero_peace'] == 3)
        $foodcon += ($users[peasants] * .004);
    else
        $foodcon += ($users[peasants] * .01);
        
    return round($foodcon);
    
}


function calcFoodPro() {
    global $config, $users, $urace;

    $foodpro = round(($users[freeland] * 5) + ($users[farms] * 75) * $urace[farms]) * (10/$config[food]) * (10/$config[food]);
    
    if ($users['hero_peace'] == 1) // Demeter?
        $foodpro = round(1.5 * $foodpro);
    return round($foodpro);
}

function calcIncome() {
    global $users, $urace, $config, $size;
    $size = calcSizeBonus($users[networth]);
    $income = round(((pci($users, $urace) * ($users[tax] / 100) * ($users[health] / 100) * $users[peasants]) + ($users[shops] * 500 * $config[shopmult])) / $size);
    return round($income);
}

function calcExpenses() {
    global $users, $urace, $expbonus, $warflag, $config;
    
    $loanpayed = round($users[loan] / 200);
    $expenses = 0;
    foreach($config[troop] as $num => $mktcost) {
        $expenses += $users[troop][$num] * ($config[troop][$num] / $config[troop][0]);
        $expenses += $config['market_upkeep'] * ($users[pubmarket][$num] * ($config[troop][$num] / $config[troop][0])) / 3;
        $expenses += ($users[troop_res][$num] * ($config[troop][$num] / $config[troop][0])) / 3;
    }
        
    $expenses += ($users[land] * 8) + ($users[wizards] * .5);
    $expenses = round($expenses);
    $expbonus = round($expenses * ($urace[costs] - ($users[barracks] / ($users[land]+1))));
    if ($expbonus > $expenses / 2) // expenses bonus limit
        $expbonus = round($expenses / 2);
    $expenses -= $expbonus;
    
    $wartax = 0;
    if ($warflag) // war tax?
        $wartax = $networth / 1000; 
    return round($expenses + $loanpayed + $wartax);
}

function calcLoanPay()  {
    global $users, $urace, $expbonus, $warflag, $config;
    return round($users[loan] / 200);
}

?>

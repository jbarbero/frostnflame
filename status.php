<?
include("header.php");
include("lib/stocks.php");
require_once("lib/status.php");
$ctags = loadClanTags();

//print_r($ctags);

if ($users[offtotal]) {
    $offsuccpercent = round($users[offsucc]/$users[offtotal]*100);
} else {
    $offsuccpercent = 0;
}

if ($users[deftotal]) {
    $defsuccpercent = round($users[defsucc]/$users[deftotal]*100);
} else {
    $defsuccpercent = 0;
}

$succpoint = 5;
$failpoint = 2;
        $offexp = calcoffexp($users);
        $defexp = calcdefexp($users);


$experience = floor($offexp*1000)+floor($defexp*1000);

$offpts = 0;
$defpts = 0;
foreach($config[troop] as $num => $mktcost) {
    //print "$num -> " . ($users[troop][$num] * $uera["o_troop$num"]) . " / " . ($users[troop][$num] * $uera["d_troop$num"]) . "== " . ($users[troop][$num] * $uera["d_troop$num"] + ($users[towers] * $config[towers])) . "<br />";
    $offpts += $users[troop][$num] * $uera["o_troop$num"];
    $defpts += $users[troop][$num] * $uera["d_troop$num"];
}

$offpts = round($offpts * $urace[offense]);
$bldgs = $users[land] - $users[freeland] - $users[towers];
$defpts += $users[towers] * $config[towers] * min(1, $users[troop][0] / (100 * $users[towers] + 1));
$defpts += $bldgs         * $config[blddef] * min(1, $users[troop][0] / (100 * $bldgs         + 1));
$defpts = round($defpts * $urace[defense]);

$offpts *= (1 + $offexp);
$defpts *= (1 + $defexp);
$size = calcSizeBonus($users[networth]);


$foodpro = calcFoodPro();
$foodcon = calcFoodCon();
$income = calcIncome();
$expenses = calcExpenses();

$foodnet = $foodpro - $foodcon;
$netincome = $income - $expenses;

// All the stuff to be commas()'ed

$users[turnsused] = commas($users[turnsused]);
$users[cash] = commas(gamefactor($users[cash]));
$users[networth] = commas($users[networth]);
$users[peasants] = commas($users[peasants]);
$foodpro = commas(gamefactor($foodpro));
$foodcon = commas(gamefactor($foodcon));
$users[shops] = commas($users[shops]);
$users[homes] = commas($users[homes]);
$users[industry] = commas($users[industry]);
$users[barracks] = commas($users[barracks]);
$users[labs] = commas($users[labs]);
$users[farms] = commas($users[farms]);
$users[towers] = commas($users[towers]);
$users[freeland] = commas($users[freeland]);
$income = commas(gamefactor($income));
$expenses = commas(gamefactor($expenses));
$loanpayment = commas(gamefactor($loanpayment));
$users[savings] = commas(gamefactor($users[savings]));
$users[loan] = commas(gamefactor($users[loan]));
foreach($config[troop] as $num => $mktcost) {
    $users["troop$num"] = commas(gamefactor($users[troop][$num]));
}
$users[wizards] = commas(gamefactor($users[wizards]));

$foodnet = returnCNum(gamefactor($foodnet), "", 0);
$netincome = returnCNum(gamefactor($netincome), "$", 0);


$off_percent = 0;
$def_percent = 0;
$pci = pci($users,$urace);
$uclan = loadClan($users[clan]);

foreach($ctags as $id => $tag) {
    if($id != 0)
        $ctags[$id] = "<a class=\"proflink\" href=\"?clancrier&sclan=$id$authstr\">$tag</a>";
}

$tags = array($ctags["$users[clan]"], $ctags["$uclan[ally1]"], $ctags["$uclan[ally2]"], $ctags["$uclan[ally3]"], $ctags["$uclan[war1]"], $ctags["$uclan[war2]"], $ctags["$uclan[war3]"], $ctags["$uclan[ally4]"], $ctags["$uclan[ally5]"], $ctags["$uclan[war4]"], $ctags["$uclan[war5]"]);
$z = 0;
while($z<sizeof($tags)) {
    if($tags[$z] == "") $tags[$z] = "None";
    $z++;
}


//print_r($tags);


if($users[offtotal]) $off_percent = round($users[offsucc]/$users[offtotal]*100);
if($users[deftotal]) $def_percent = round($users[defsucc]/$users[deftotal]*100);


// Stock loading/displaying
$stocks = array();
$owned = explode("|", $users[stocks]);
$total_owned = 0;
$avg_price = 0;
for($key = 0; $key < sizeof($owned); $key++) {
    $prep_array = array();
    $prep_array['name'] = db_safe_firstval("SELECT name FROM $stockdb WHERE id=" . ($key+1));
    $prep_array['price'] = floor(db_safe_firstval("SELECT price FROM $stockdb WHERE id=" . ($key+1)) / 1000);
    $prep_array['price'] = max(1, min(500, $prep_array['price']));
    $prep_array['owned'] = $owned[$key];
    $prep_array['total_worth'] = commas($prep_array['owned'] * $prep_array['price']);

    if($prep_array['owned'] > 0) {
        $avg_price += $prep_array['owned'] * $prep_array['price'];
        $total_owned += $prep_array['owned'];
    }
    
    // Must apply commas after multiplying
    $prep_array['owned'] = commas($prep_array['owned']);
    $prep_array['price'] = commas($prep_array['price']); // I'm only doing this incase some server has huge prices
    
    

    $stocks[] = $prep_array;
}

    if($total_owned == 0)
        $total_owned = 1;

    $stocks[] = array('name' => "<b>Total</b>", 'price' => "<b>" . commas(floor($avg_price / ($total_owned))) . "</b>", 'owned' => "<b>" . commas($total_owned) . "</b>", 'total_worth' => "<b>" . commas(floor($total_owned * ($avg_price/($total_owned)))) . "</b>");




$numtroops = count($config[troop]);

$troopdisp = array();
foreach($users[troop] as $num => $have) {
    $troopdisp[] = array(name=>$uera["troop$num"], have=>commas($have), market=>$users[pubmarket][$num]);
}

$loanrate = $config[loanbase] + $size;
$savrate = $config[savebase] - $size;

$showstocks = true;
if(in_array('stocks', $config['disabled_pages']))
    $showstocks = false;


$ti = $pci*$users['peasants'];
template_display('status.html');

$users=loadUser($users[num]);
TheEnd("");
?>

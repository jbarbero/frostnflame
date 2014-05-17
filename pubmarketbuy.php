<?php
include_once("header.php");
include("lib/marketcron.php");

if ($lockdb)
    TheEnd("Public market currently disabled!");

if(!defined('CLAN'))
    define('CLAN', 0);
if(!defined('SCRIPT'))
    define('SCRIPT', 'pubmarketbuy');
if(!defined('SCRIPT2'))
    define('SCRIPT2', 'pubmarketsell');

$trooplst = array();
foreach($config[troop] as $num => $mktcost)
    $trooplst[] = "troop$num";
$trooplst[] = 'food';
$trooplst[] = 'runes';
foreach($config[troop] as $num => $mktcost)
    $users["troop$num"] = $users[troop][$num];

function buyUnits ($type) {
    global $playerdb, $marketdb, $users, $uera, $buy, $buyprice, $datetime, $time, $max, $config;
    $amount = $buy[$type];
    $price = $buyprice[$type];
    fixInputNum($price);
    fixInputNum($amount);

    $amount = invfactor($amount);

    sqlQuotes($type);
    fixInputNum($price);
    $market = mysqli_fetch_array(db_safe_query("SELECT * FROM $marketdb WHERE type='$type' AND seller!=$users[num] AND price<=$price AND time<=$time AND clan=".CLAN." 
        ORDER BY price ASC, time ASC LIMIT 1;"));
    if (!$market[amount])
        return;
    if(isset($max[$type]))
        $amount = floor($users[cash] / $market[price]);
    if ($amount > $market[amount])
        $amount = $market[amount];

    if ($amount == 0) // did I specify a value?
        return;
    if ($amount < 0)
        print "Cannot purchase a negative amount of $uera[$type]!<br>\n";
    elseif ($amount * $price > $users[cash])
        print "You don't have enough money to buy that many $uera[$type]!<br>\n";
    else {
        $enemy = loadUser($market[seller]);
        $spent = $amount * $price;
        $sales = round($spent * .95);
        $users[cash] -= $spent;
        $enemy[savings] += $sales;
        $users[$type] += $amount;
        fixInputNum($amount);
        @db_safe_query("UPDATE $marketdb SET amount=(amount-$amount) WHERE id=$market[id] AND clan=".CLAN.";");
        @db_safe_query("DELETE FROM $marketdb WHERE amount=0;");
        print commas(gamefactor($amount)) . " $uera[$type] purchased from the market for $" . commas(gamefactor($spent)) . ".<br>\n";
        if($type == 'food') {
            addNews(100, array(id1=>$enemy[num], id2=>$users[num], food1=>$amount, cash1=>$sales));
        } else if($type == 'runes') {
            addNews(100, array(id1=>$enemy[num], id2=>$users[num], runes1=>$amount, cash1=>$sales));
        } else {
            $troopsell = '';
            foreach($config[troop] as $num => $mktcost) {
                if($type == "troop$num")
                    $troopsell .= $amount.'|';
                else
                    $troopsell .= '0|';
            }
            $troopsell = substr($troopsell, 0, -1);
            addNews(100, array(id1=>$enemy[num], id2=>$users[num], troops1=>$troopsell, cash1=>$sales));
        } 
        saveUserData($enemy, "networth savings pubmarket pubmarket_food pubmarket_runes");
    } 
} 

function getCosts ($type)
{
    global $marketdb, $users, $costs, $avail, $canbuy, $time;
    sqlQuotes($type);
    $market = mysqli_fetch_array(db_safe_query("SELECT * FROM $marketdb WHERE type='$type' AND seller!=$users[num] AND time<=$time AND clan=".CLAN." 
        ORDER BY price ASC, time ASC LIMIT 1;"));
    if ($market[id]) {
        $costs[$type] = $market[price];
        $avail[$type] = $market[amount];
        $canbuy[$type] = floor($users[cash] / $market[price]);
        if ($canbuy[$type] > $market[amount])
            $canbuy[$type] = $market[amount];
    } else $costs[$type] = $avail[$type] = $canbuy[$type] = 0;
} 

function printRow ($type)
{
    global $users, $uera, $costs, $avail, $canbuy;
    ?>
<tr><td><?=$uera[$type]?></td>
    <td class="aright"><?=commas(gamefactor($users[$type]))?></td>
    <td class="aright"><?=commas(gamefactor($avail[$type]))?></td>
    <td class="aright"><input type="hidden" name="buyprice[<?=$type?>]" value="<?=$costs[$type]?>">$<?=commas($costs[$type])?></td>
    <td class="aright"><?=commas(gamefactor($canbuy[$type]))?></td>
    <td class="aright"><input type="text" name="buy[<?=$type?>]" size="6" value="0"></td>
    <td class="aright"><input type="checkbox" name="max[<?=$type?>]"></td></tr>
<?php
} 

if ($users[turnsused] <= $config[protection])
    TheEnd("Cannot trade on the public market while under protection!");

foreach($trooplst as $num => $entry)
    getCosts($trooplst[$num]);

if ($do_buy) {
    foreach($trooplst as $num => $entry)
        buyUnits($trooplst[$num]);
    foreach($config[troop] as $num => $mktcost)
        $users[troop][$num] = $users["troop$num"];
    saveUserData($users, "networth cash troops food runes");
} 
foreach($trooplst as $num => $entry)
    getCosts($trooplst[$num]);

if(defined('CLANMKT')) echo"<big><b>Clan Market - Buy</big></b><br><br>";
else echo"<big><b>Public Market - Buy</big></b><br><br>";
?>
Also see: <a href="?guide&amp;section=military&amp;era=<?=$users[era]?><?=$authstr?>"><?=$gamename?> Guide: Military</a><br>
<script language="JavaScript">
function checkAll (check){
        var path = document.pvmb;
        for (var i=0;i<path.elements.length;i++) {
           e = path.elements[i];
           checkname = "maxall";
           if(check==2) checkname = "maxall2";
           if( (e.name!=checkname)  && (e.type=="checkbox") ) {
               e.checked = path.maxall.checked;
               if(check==2) e.checked = path.maxall2.checked;
           }
    }
}
</script>

<form method="post" action="?<?=SCRIPT?><?=$authstr?>" name="pvmb">
<table class="inputtable">
<tr><td colspan="3"><a href="?<?=SCRIPT?><?=$authstr?>">Buy Goods</a></td>
    <td colspan="4" class="aright"><a href="?<?=SCRIPT2?><?=$authstr?>">Sell Goods</a></td></tr>
<tr class="inputtable2"><th class="aleft">Unit</th>
    <th class="aright">Owned</th>
    <th class="aright">Avail</th>
    <th class="aright">Cost</th>
    <th class="aright">Afford</th>
    <th class="aright">Buy</th>
    <th class="aright">Max <input type="checkbox" name="maxall" onClick="checkAll(1)"></th></tr>
<?php
foreach($trooplst as $num => $entry)
    printRow($entry);

?>
<tr><td colspan="7" class="acenter"><input type="submit" name="do_buy" value="Purchase Goods"></td></tr>
</table>
</form>
<?php
TheEnd("");

?>

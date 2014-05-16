<?
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

$commission = $config['market_flat_commission'];
/*
if($users[networth] < 10000000)
	$commission = 0.9;
else if($users[networth] < 20000000)
	$commission = 0.85;
else if($users[networth] < 50000000)
	$commission = 0.8;
else if($users[networth] < 100000000)
	$commission = 0.75;
else
	$commission = 0.7;
*/

foreach($config[troop] as $num => $mktcost)
	getCosts("troop$num", $num);
getCosts(food);
getCosts(runes);


function printRow ($type, $num=-1)
{
	global $users, $uera, $costs, $basket;
	if($num != -1) {
		$owned = $users[troop][$num];
		$type = "troop$num";
	} else
		$owned = $users[$type];
?>
<tr><td><?=$uera[$type]?></td>
    <td class="aright"><?=commas(gamefactor($owned))?></td>
    <td class="aright"><?=commas(gamefactor($basket[$type]))?></td>
    <td class="aright">$<input type="text" name="sellprice[<?=$type?>]" value="<?=$costs[$type]?>" size="5"></td>
    <td class="aright"><input type="text" name="sell[<?=$type?>]" value="0" size="8"></td>
    <td class="aright"><input type="checkbox" name="max[<?=$type?>]" value="<?=$type?>"></td></tr>
<?
}

function getCosts ($type, $num=0)
{
	global $marketdb, $config, $users, $costs, $time;
	sqlQuotes($type);
	$market = mysql_fetch_array(db_safe_query("SELECT * FROM $marketdb WHERE type='$type' AND seller!=$users[num] AND time<=$time AND clan=".CLAN." 
		ORDER BY price ASC, time ASC LIMIT 1;"));
	if ($market[price])
		$costs[$type] = $market[price];
	else {
		if(strlen($type) >= 6 && substr($type, 0, 5) == 'troop')
			$costs[$type] = $config[troop][$num];
		else
			$costs[$type] = $config[$type."_sell"];
	}
}

function calcBasket ($type, $percent, $num=0)
{
	global $marketdb, $users, $uera, $basket, $config, $time, $authstr;
	$ts = $type;
	if($type == 'troop')
		$ts = "troop$num";
	$onsale = 0;
	sqlQuotes($ts);
	$goods = db_safe_query("SELECT * FROM $marketdb WHERE type='$ts' AND seller=$users[num] ORDER BY amount DESC;");
	while ($market = mysql_fetch_array($goods))
	{
		$onsale += $market[amount];
		if($market[clan] == CLAN) {
?>
<tr><td><?=$uera[$ts]?></td>
    <td class="aright"><?=commas(gamefactor($market[amount]))?></td>
    <td class="aright">$<?=commas($market[price])?></td>
    <td class="aright"><?
		if (($market[time] -= $time) < 0)
		{
			?>On Sale for <?=round($market[time]/-3600,1)?> hour(s) - <a href="?<?=SCRIPT2?>&amp;do_removeunits=yes&amp;remove_id=<?=$market[id]?><?=$authstr?>">Remove</a><?
		}
		else
		{
			$bwidth = round($market[time]/3600,1)*10;
			if($bwidth > 50)
				$bwidth = 50;
			$gwidth = 50 - $bwidth;
			$factor = 3;
			$bwidth *= $factor; $gwidth *= $factor;
			echo "<table width='150' cellpadding='0' cellspacing='0'><tr><td>
	<img src='img/greenfade.gif' height='15' width='$gwidth' border='0' align='middle'><img src='img/redfade.gif' height='15' width='$bwidth' border='0' align='middle'>
			      </td></tr></table>";
		}
?></td></tr>
<?
		}
	}
	if($type == 'troop')
		$basket[$ts] = round(($users[troop][$num] + $onsale) * $percent) - $onsale;
	else
		$basket[$type] = round(($users[$type] + $onsale) * $percent) - $onsale;
	$type = $ts;
	if ($basket[$type] < 0)
		$basket[$type] = 0;
}

function sellUnits ($type, $num=-1) {
	global $marketdb, $users, $uera, $sell, $sellprice, $config, $basket, $time, $max;
	if($num != -1)
		$type = $type.$num;
	if($num != -1) {
		$minprice = $config[troop][$num] * 0.2;
		$maxprice = $config[troop][$num] * 2.5;
	} else {
		$minprice = $config[$type] * 0.2;
		$maxprice = $config[$type] * 2.5;
	}

	$amount = $sell[$type];
	fixInputNum($amount);
	$amount = invfactor($amount);

	if(isset($max[$type]))
		$amount = $basket[$type];

	$price = $sellprice[$type];
	fixInputNum($price);
	global $costs;
	if (($amount == 0) || ($price == 0))
		return;
	if ($amount < 0)
		print "Cannot sell a negative number of $uera[$type]!<br>\n";
	elseif ($amount > $basket[$type])
		print "Cannot sell that many $uera[$type]!<br>\n";
	elseif ($price < $minprice)
		print "Cannot sell $uera[$type] that cheap!<br>\n";
	elseif ($price > $maxprice)
		print "Cannot sell $uera[$type] for that high of a price!<br>\n";
	else
	{
		if($num != -1) {
			$users[troop][$num] -= $amount;
			$basket[$ts] -= $amount;
		} else {
			$users[$type] -= $amount;
			$basket[$type] -= $amount;
		}
		sqlQuotes($type);
		fixInputNum($amount);
		fixInputNum($price);
		db_safe_query("INSERT INTO $marketdb (type,seller,amount,price,time,clan) VALUES ('$type',$users[num],$amount,$price,$time+3600*$config[market],".CLAN.");");
?>
<tr><td><?=$uera[$type]?></td>
    <td class="aright"><?=commas(gamefactor($amount))?></td>
    <td class="aright">$<?=commas($price)?></td>
    <td class="aright"><?
			$bwidth = 50;
			$gwidth = 50 - $bwidth;
			$factor = 3;
			$bwidth *= $factor; $gwidth *= $factor;
			echo "<table width='1' cellpadding='0' cellspacing='0'><tr><td><nobr>
				<img src='img/greenfade.gif' height='15' width='$gwidth' border='0'>
				</td><td>
				<img src='img/redfade.gif' height='15' width='$bwidth' border='0'>
			      </nobr></td></tr></table>";
		?></td></tr><?
	}
}

function removeUnits ($id)
{
	global $marketdb, $users, $uera, $commission;
	fixInputNum($id);
	$market = @mysql_fetch_array(db_safe_query("SELECT * FROM $marketdb WHERE id=$id AND clan=".CLAN.";"));
	if ($market[seller] != $users[num])
		print "No such shipment!<br>\n";
	else
	{
		$amount = $market[amount];
		$type = $market[type];
		@db_safe_query("DELETE FROM $marketdb WHERE id=$id AND clan=".CLAN.";");
		$ts = $type;
		if(strlen($type) >= 6 && substr($type, 0, 5) == 'troop') {
			$type = substr($type, 5, 1);
			$users[troop][$type] += floor($market[amount] * $commission);
		}
		else
			$users[$type] += floor($market[amount] * $commission);
		print "You have removed ".commas(gamefactor($amount))." $uera[$ts] from the market.<br>\n";
		saveUserData($users,"networth troops food runes pubmarket pubmarket_food pubmarket_runes");
	}
}

if ($users[turnsused] <= $config[protection])
	TheEnd("Cannot trade on the public market while under protection!");

if ($do_removeunits)
	removeUnits($remove_id);

if(defined('CLANMKT')) echo"<big><b>Clan Market - Sell</big></b><br><br>";
else echo"<big><b>Public Market - Sell</big></b><br><br>";
?>
<table class="inputtable">
<tr><td colspan="4"><center>On the market or on the way we have:</center></td></tr>
<tr class="inputtable2"><th class="aleft">Unit</th>
    <th class="aright">Quantity</th>
    <th class="aright">Price</th>
    <th class="aright">Status</th></tr>
<?
global $config;
foreach($config[troop] as $num => $mktcost)
if(defined('CLANMKT')) calcBasket('troop', $config['cmkt_troops']/10000, $num);
else calcBasket('troop', $config['pmkt_troops']/10000, $num);
calcBasket(food, $config['pmkt_res']/10000);
calcBasket(runes, $config['pmkt_res']/10000);


if ($do_sell)
{
	foreach($config[troop] as $num => $mktcost)
		sellUnits('troop', $num);
	sellUnits('food');
	sellUnits('runes');

	saveUserData($users,"networth troops food runes pubmarket pubmarket_food pubmarket_runes");
}
?>
    <tr><td colspan="4"><hr></td></tr>
</table>

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

Also see: <a href="?guide&amp;section=military&amp;era=<?=$users[era]?><?=$authstr?>"><?=$gamename?> Guide: Military</a><br>
It will take <?=$config[market]?> hours for your goods to reach the market.<br>
<form method="post" action="?<?=SCRIPT2?><?=$authstr?>" name="pvmb">
<table class="inputtable">
<tr><td colspan="2"><a href="?<?=SCRIPT?><?=$authstr?>">Buy Goods</a></td>
    <td>&nbsp;</td>
    <td colspan="3" class="aright"><a href="?<?=SCRIPT2?><?=$authstr?>">Sell Goods</a></td></tr>
<tr class="inputtable2"><th class="aleft">Unit</th>
    <th class="aright">Owned</th>
    <th class="aright">Can Sell</th>
    <th class="aright">Price</th>
    <th class="aright">Sell</th>
    <th class="aright">Max <input type="checkbox" name="maxall" onClick="checkAll(1)"></th></tr>
<?
ob_start();
foreach($config[troop] as $num => $mktcost)
if(defined('CLANMKT')) calcBasket('troop', $config['cmkt_troops']/10000, $num);
else calcBasket('troop', $config['pmkt_troops']/10000, $num);
calcBasket(food, $config['pmkt_res']/10000);
calcBasket(runes, $config['pmkt_res']/10000);
ob_end_clean();

foreach($config[troop] as $num => $mktcost)
	printRow(troop, $num);
printRow(food);
printRow(runes);

?>
<tr><td colspan="6" class="acenter"><input type="submit" name="do_sell" value="Sell Goods"></td></tr>
</table>
</form>
<?
TheEnd("");
?>

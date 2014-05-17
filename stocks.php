<?
include("header.php");
include("lib/stocks.php");

if ($users[turnsused] <= $config[protection])
	TheEnd("Cannot trade on the stock market while under protection!");

//config & load

$height = 400;
$width = 25;
$users[stocks] = explode("|", $users[stocks]);
$stocknames = array();
$symbols = array();
$prices = array();
$days1 = array();
$days2 = array();
$days3 = array();

$q = db_safe_query("SELECT * FROM $stockdb;");
while($stock = mysqli_fetch_array($q)) {
	$id = $stock['id'];
	$stocknames[$id] = $stock['name'];
	$symbols[$id] = $stock['symbol'];
	$prices[$id] = floor($stock['price']/1000) + $stock['boost'];
	if($prices[$id] > 999)
		$prices[$id] = 999;
	if($prices[$id] < 1)
		$prices[$id] = 1;
	$days1[$id] = floor($stock['days1']/1000);
	$days2[$id] = floor($stock['days2']/1000);
	$days3[$id] = floor($stock['days3']/1000);
}

//display 1

$stocks_display = array();
foreach($stocknames as $i => $name) {
	$stocks_display[$i-1]['name'] = $stocknames[$i];
	$stocks_display[$i-1]['symbol'] = $symbols[$i];
	$okh = round(($prices[$i]/999)*$height);
	$bh = round((1-$prices[$i]/999)*$height);
	$stocks_display[$i-1]['price'] = $okh;
	$stocks_display[$i-1]['bprice'] = $bh;
	$stocks_display[$i-1]['lprice'] = $prices[$i];
	$stocks_display[$i-1]['days_1'] = $days1[$i];
	$stocks_display[$i-1]['days_2'] = $days2[$i];
	$stocks_display[$i-1]['days_3'] = $days3[$i];
}

//buy & sell

$trans = '';
if(!empty($buy)) {
	foreach($buy as $id => $amount) {
		fixInputNum($id);
		fixInputNum($amount);
		if(!empty($bmax[$id]))
			$amount = floor($users[cash]/$prices[$id]);

		if($amount <= 0)
			continue;
		$sqln = 'stocks_'.$id;
		$name = $stocknames[$id].' ('.$symbols[$id].')';
//		fixInputNum($amount);
		$cost = $prices[$id]*$amount;
		if($cost > $users[cash]) {
			$trans .= 'Not enough money to buy '.commas($amount).' shares of '.$name.'!<br>';
			continue;
		}
	
		$users[cash] -= $cost;
		$users[stocks][$id-1] += $amount;
		$trans .= 'Bought '.commas($amount).' shares of '.$name.' for $'.commas($cost).'<br>';
	}
	$users[stocks] = implode("|", $users[stocks]);
	saveUserData($users, "cash stocks networth");
	$users[stocks] = explode("|", $users[stocks]);
}

if(!empty($sell)) {
	foreach($sell as $id => $amount) {
		fixInputNum($id);
		fixInputNum($amount);
		if(!empty($smax[$id]))
			$amount = $users[stocks][$id-1];

		if($amount <= 0)
			continue;
		$name = $stocknames[$id].' ('.$symbols[$id].')';
		fixInputNum($amount);
		if($amount > $users[stocks][$id-1]) {
			$trans .= 'Can\'t sell '.commas($amount).' shares of '.$name.'!<br>';
			continue;
		}
		$cash = $prices[$id]*$amount;
		
		$users[cash] += $cash;
		$users[stocks][$id-1] -= $amount;
		$trans .= 'Sold '.commas($amount).' shares of '.$name.' for $'.commas($cash).'<br>';
	}
	$users[stocks] = implode("|", $users[stocks]);
	saveUserData($users, "cash stocks networth");
	$users[stocks] = explode("|", $users[stocks]);
}

//display 2

$stock_count = $i;
$stockbuy = array();
for($i=1; $i <= $stock_count; $i += 1) {
	$stockbuy[$i-1]['name'] = $stocknames[$i];
	$stockbuy[$i-1]['symbol'] = $symbols[$i];
	$stockbuy[$i-1]['price'] = $prices[$i];
	$stockbuy[$i-1]['owned'] = commas($users[stocks][$i-1]);
	$stockbuy[$i-1]['id'] = $i;
}

$tpl->assign('stocknames', $stocks_display);

//finish
$tpl->display('stocks.html');

TheEnd('');
?>

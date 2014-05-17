<?
include("header.php");
include("lib/stocks.php");

if ($users[disabled] != 2)
    TheEnd("You are not an administrator!");

//config & load

//nudge

if(!empty($change)) {
    $which = $_GET['change'];
    fixInputNum($which);
    $setting = $_GET['fast'];
    $amt = 5;

    fixInputNum($which);
    if(isset($_GET['boost'])) {
        db_safe_query("UPDATE $stockdb SET boost=boost+5 WHERE id=$which;");
    } else if(isset($_GET['reduce'])) {
        db_safe_query("UPDATE $stockdb SET boost=boost-5 WHERE id=$which;");
    }
    echo mysqli_error($GLOBALS["db_link"]);
}




$height = 400;
$width = 25;
$users[stocks] = explode("|", $users[stocks]);
$stocknames = array();
$symbols = array();
$prices = array();
$days1 = array();
$days2 = array();
$days3 = array();
$boost = array();

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
    $boost[$id] = $stock['boost'];
}

//display 1

$stocks_display = array();
foreach($stocknames as $i => $name) {
    $stocks_display[$i-1]['id'] = $i;
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
    $stocks_display[$i-1]['boost'] = $boost[$i];
}

$tpl->assign('stocknames', $stocks_display);

//finish
$tpl->display('stockpanel.html');

TheEnd('');
?>

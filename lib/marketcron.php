<?
if(!defined("PROMISANCE"))
    die(" ");
if(howmanytimes(lasttime('markets'), $perminutes)) {
    // condense market items:
    $mkt = db_safe_query("SELECT * FROM $marketdb WHERE time<$time;");
    while ($market = mysqli_fetch_array($mkt)) {
        $market = mysqli_fetch_array(db_safe_query("SELECT * FROM $marketdb WHERE id=$market[id];"));
        $id = $market[id];
        $amount = $market[amount];
        $price = $market[price];
        $unit = $market[type];
        $seller = $market[seller];
        $clan = $market[clan];
        $mktdoubles = db_safe_query("SELECT * FROM $marketdb WHERE time<$time AND price=$price AND type='$unit' AND seller=$seller AND clan=$clan AND id!=$id;");
        if($mktdoubles)
        while ($mktsame = mysqli_fetch_array($mktdoubles)) {
            $amount += $mktsame[amount];
            db_safe_query("DELETE FROM $marketdb WHERE id=$mktsame[id];");
        } 
        db_safe_query("UPDATE $marketdb SET amount=$amount WHERE id=$id");
    }
    justRun('markets', $perminutes);
}
?>

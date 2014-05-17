<?
if(!defined("PROMISANCE"))
    die(" ");
if(howmanytimes(lasttime('graveyard'), $perminutes)) {
    // update graveyard networths
    $deadmen = db_safe_query("SELECT * FROM $playerdb WHERE land=0 AND disabled!=2 AND disabled!=3;");
    while ($dead = mysqli_fetch_array($deadmen)) {
        $dead['networth'] = getNetWorth($dead);
        saveUserData($dead, "networth");
    }
    // update graveyard ranks
    $deadmen2 = db_safe_query("SELECT * FROM $playerdb WHERE land=0 AND disabled!=2 AND disabled!=3 ORDER BY networth DESC;");
    $rank = 0;
    while ($dead = mysqli_fetch_array($deadmen2)) {
        $rank++;
        $dead[rank] = $rank;
        saveUserData($dead, "rank"); 
    }    
    justRun('graveyard', $perminutes);
}
?>

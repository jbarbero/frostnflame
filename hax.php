<?
/*
Devari's Hackish Comprehensive Scores List
Version 0.1
*/
function commas ($str) {
    return number_format($str, 0, ".", ",");
} 
function automatic_server_url() {
    return 
        (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" .
        $_SERVER['HTTP_HOST'] .
        substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
}
function db_safe_query($query) {
    return mysql_query($query);    /* SAFE -- root call */
}

include("config.php");
$link = @mysql_connect($config[dbhost],$config[dbuser],$config[dbpass]);
mysql_select_db($config[dbname]);
$prefix = 'bfr';
$server = $prefix . '_players';
$players = db_safe_query("SELECT num,empire,networth FROM $server");

$max = array();
$max[num] = 1;
$max[empire] = 'Administrator';
$max[networth] = '0';

$player = array();

while ($player = mysqli_fetch_array($players))
{
    $troop0 = mysqli_fetch_array(db_safe_query("SELECT SUM(amount) FROM bfr_market WHERE seller=$player[num] AND type='troop0'"));
    $troop1 = mysqli_fetch_array(db_safe_query("SELECT SUM(amount) FROM bfr_market WHERE seller=$player[num] AND type='troop1'"));
    $troop2 = mysqli_fetch_array(db_safe_query("SELECT SUM(amount) FROM bfr_market WHERE seller=$player[num] AND type='troop2'"));
    $troop3 = mysqli_fetch_array(db_safe_query("SELECT SUM(amount) FROM bfr_market WHERE seller=$player[num] AND type='troop3'"));
    
    $player[networth] += ($troop0[0] * $config[troop][0] / $config[troop][0]);
    $player[networth] += ($troop1[0] * $config[troop][1] / $config[troop][0]);
    $player[networth] += ($troop2[0] * $config[troop][2] / $config[troop][0]);
    $player[networth] += ($troop3[0] * $config[troop][3] / $config[troop][0]);

    print("$player[empire] (#$player[num]) - \$" . commas($player[networth]) . "\n");
    
    if ($player[networth] > $max[networth])
    {
        $max = $player;
    }
}

print("\nTop Empire: \n");
print("$max[empire] (#$max[num]) - \$" . commas($max[networth]) . "\n");

?>

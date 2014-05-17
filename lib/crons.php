<?
//global cron stuff
function HowManyTimes($lasttime, $perminutes) {
    global $time;
    if($lasttime == 0)
        return 1;
    return floor(($time-$lasttime)/(60*$perminutes));
}


function newday($name) {
    global $time;
    $oldtime = lasttime($name);
    # This assumes that someone logs on to the game at least once every day
    # That isn't a problem, since if this isn't true, a skipped day won't matter
    return (date('d') != date('d', $oldtime));
}


function lasttime($name) {
    global $crondb, $db_link;
    $time = time();
    $name = mysqli_real_escape_string($GLOBALS["db_link"], $name);
    $q = @db_safe_query("SELECT time FROM $crondb WHERE name='$name';");
    if(!mysqli_num_rows($q)) {
        db_safe_query("INSERT INTO $crondb (name, time) VALUES ('$name', $time);");
        return $time;
    } else {
        $a = @mysqli_fetch_array($q);
        return $a[0];
    }
}


function justRun($name, $modmins) {
    global $time, $crondb;
    lasttime($name);
    $last = $time - $time%$modmins;
    $name = mysqli_real_escape_string($GLOBALS["db_link"], $name);
    @db_safe_query("UPDATE $crondb SET time=$last WHERE name='$name';");
}

?>

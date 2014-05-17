<?
if(!defined("PROMISANCE"))
    die(" ");
if($hrs = howmanytimes(lasttime('vacation'), 60)) {
    db_safe_query("UPDATE $playerdb SET vacation=(vacation+$hrs) WHERE vacation>0;");
    justRun('vacation', 60);
}


if($times = howmanytimes(lasttime('bank'), $config['savingsperminutes'])) {
        
    $bankusers = db_safe_query("SELECT * FROM $playerdb WHERE savings>(networth * ".$config['maxsave'].");");
    while ($users = mysqli_fetch_array($bankusers)) {
        for ($i=0; $i < $times; $i++) {
            if ($users[savings] > ($users[networth] * $config['maxsave'])) {
                $minuscash = min(($users[savings] * ($config['savingsper']) / 100),($users[savings] - ($users[networth] * $config['maxsave']))); // removes excess savings; percent of total savings
//                $minuscash = ($users[savings] - ($users[networth] * $config['maxsave'])) * ($config['savingsper'] / 100); // removes excess savings; percent of excess
                $users[savings] -= $minuscash;
                $users[cash] += $minuscash;
                saveUserData($users, "savings cash");
            }
        }
    }
    justRun('bank', $config['savingsperminutes']);
}

if(howmanytimes(lasttime('tidying'), $perminutes)) {
    global $config, $lockdb, $time;

    # To avoid deaths when database is locked
    if($lockdb)
        db_safe_query("UPDATE $playerdb SET idle=$time, turns_last=$time, hour_last=$time, turnbank_last=$time, pvmarket_last=$time, bmper_last=$time;");

    # Delete dead people
    if($config['deaddelay'] != 0) {
        $idletime = $config['deaddelay'] * 24 * 3600;
        $delusers = db_safe_query("SELECT * FROM $playerdb WHERE
            (disabled<=1 AND vacation=0 AND land>0 AND idle<($time-$idletime)) OR
            (land=0 AND disabled=1 AND ip!='0.0.0.0' AND idle<($time-$idletime)) OR
            (disabled=4)
                ;");
        while ($users = mysqli_fetch_array($delusers)) {
            // print "Deleting user $users[empire] <a class=proflink href=?profiles&num=$users[num]>(#$users[num])</a>...\n";
            if ($users[clan]) { // remove user from clan
                $clan = loadClan($users[clan]);
                db_safe_query("UPDATE $clandb SET members=members-1 WHERE num=$clan[num];");
            }
            db_safe_query("DELETE FROM $marketdb WHERE seller=$users[num];"); // any of the user's items on the market
            db_safe_query("DELETE FROM $lotterydb WHERE num=$users[num];"); // any lottery tickets
            $users[name] .= ".DEAD." . $time;
            $users[username] .= ".DEAD." . $time;
            $users[password] = md5($users[password]);
            $users[email] .= ".DEAD." . $time;
            $users[disabled] = 1;
            $users[land] = $users[shops] = $users[homes] = $users[industry] = $users[barracks] = $users[labs] = $users[farms] = $users[towers] = $users[freeland] = 0;
            $users[ip] = "0.0.0.0";
            $users[clan] = 0;
            $users[idle] = $time;
            // and kill the user
            saveUserData($users, "networth name username password email disabled land shops homes industry barracks labs farms towers freeland ip clan idle");
        }
    }

    # Set 'em offline after they're idle for 2 turns updates
    db_safe_query("UPDATE $playerdb SET online=0,hide=0 WHERE idle<($time-(3600 / $perminutes));");

    # Special players shouldn't idle away
    db_safe_query("UPDATE $playerdb SET idle=$time WHERE password='special';");

    # Re-calculate completed bounties
    recalcBounties();

    justRun('tidying', $perminutes);
}
?>

<?
if(!defined("PROMISANCE"))
    die(" ");
if(newday('raffle') && date("H") >= 12) {
    randomize();

    $types = array('food', 'cash');
    $num_all_tickets = db_safe_firstval("SELECT COUNT(*) FROM $lotterydb WHERE num!=0");

    foreach($types as $type) {
        sqlQuotes($type);
        $have = db_safe_query("SELECT * FROM $lotterydb WHERE num!=0 AND jtyp='$type';");
        $havenum = mysqli_num_rows($have);
        fixInputNum($havenum);
        if($havenum == 0)
            continue;

        $win = 0;
        $i = 0;
        while(true) {
            $i++;
            if($i > 5000) {
                $win = 1;
                $t = 1;
                break;
                print("\nTOO MUCH!!!\n");
            }
            $win = mt_rand(5, $num_all_tickets+5);
            $t = db_safe_firstval("SELECT jtyp FROM $lotterydb WHERE ticket=$win AND num!=0;");
            if($t == $type)
                break;
        }

        $jackpot = db_safe_firstval("SELECT amt FROM $lotterydb WHERE num=0 AND ticket=$tick_curjp AND jtyp='$type';");
        $lastjackpot = db_safe_firstval("SELECT amt FROM $lotterydb WHERE num=0 AND ticket=$tick_lastjp AND jtyp='$type';");

        db_safe_query("UPDATE $lotterydb SET amt=$win WHERE num=0 AND ticket=$tick_lastnum AND jtyp='$type';");

        if ($lastjackpot > $jackpot)
            $lastjackpot = $config[jackpot];

        db_safe_query("UPDATE $lotterydb SET amt=$jackpot WHERE num=0 AND ticket=$tick_lastjp AND jtyp='$type';");
        db_safe_query("UPDATE $lotterydb SET amt=($jackpot-$lastjackpot) WHERE num=0 AND ticket=$tick_jpgrow AND jtyp='$type';");

        $winn = db_safe_firstval("SELECT num FROM $lotterydb WHERE num>0 AND ticket=$win AND jtyp='$type';");
        $winner = loadUser($winn);

        $mod = 86400;
        $add = 43200;
        $backdate = $time - ($time % $mod) + $add;

        if($type == 'food')     addNews(101, array(id1=>$winn, food1=>$jackpot), false, $backdate);
        elseif($type == 'cash') addNews(101, array(id1=>$winn, cash1=>$jackpot), false, $backdate);
        $winner[$type] += $jackpot;
        saveUserData($winner, "networth $type");
        db_safe_query("UPDATE $lotterydb SET amt=$config[jackpot] WHERE num=0 AND ticket=$tick_curjp AND jtyp='$type';");

        db_safe_query("UPDATE $lotterydb SET amt=$winn WHERE num=0 AND ticket=$tick_lastwin AND jtyp='$type';");
        db_safe_query("DELETE FROM $lotterydb WHERE num!=0 AND jtyp='$type';");
    } 
    justRun('raffle', 60);
}
?>

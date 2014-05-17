<?
global $eraCount;
$eraCount = count($etags);

function missionSpy() {
    global $users, $uratio, $enemy, $erace, $eera, $eratio, $wizloss;
    if ($uratio > $eratio) {
        missionSucceed("You have learned the following information:");
        printMainStats($enemy,$erace,$eera,true,$users);
        intelMainStats($enemy,$erace,$eera,true,$users);
        addNews(201, array(id1=>$enemy[num], id2=>$users[num], shielded=>0, clan1=>$enemy[clan], clan2=>$users[clan]));
    } else {
        missionFail();
        addNews(201, array(id1=>$enemy[num], id2=>$users[num], shielded=>-1, clan1=>$enemy[clan], clan2=>$users[clan]));
    }
}

function missionBlast ()
{
    global $users, $uratio, $enemy, $eratio, $shmod, $time, $wizloss;
    global $spratio, $mission_num, $config;
    if ($uratio > ($eratio * $spratio[$mission_num]))
    {
        missionSucceed("You have eliminated a portion of your opponent's forces!");
        $dam = 1 - ($shmod * .03);
        foreach($config[troop] as $num => $mktcost) {
            $enemy[troop][$num] = round($enemy[troop][$num] * $dam);
        }
        $enemy[wizards] = round($enemy[wizards] * $dam);
        $enemy[l_attack] = $users[num];
        if ($enemy[shield] > $time)
        {
            missionShielded();
            addNews(202, array(id1=>$enemy[num], id2=>$users[num], shielded=>1, clan1=>$enemy[clan], clan2=>$users[clan]));
        }
        else    addNews(202, array(id1=>$enemy[num], id2=>$users[num], shielded=>0, clan1=>$enemy[clan], clan2=>$users[clan]));
        $users[offsucc]++;
    }
    else
    {
        missionFail();
        addNews(202, array(id1=>$enemy[num], id2=>$users[num], shielded=>-1, clan1=>$enemy[clan], clan2=>$users[clan]));
        $enemy[defsucc]++;
    }
    saveUserData($enemy,"networth troops wizards");
}

function missionShield ()
{
    global $users, $lratio, $spratio, $mission_num, $time;
    if ($lratio >= $spratio[$mission_num])
    {
        if ($users[shield] > $time)
        {
            if ($users[shield] < $time + 3600*3)
            {
                $users[shield] = $time + 3600*12;
            }
            else
            {
                $users[shield] += 3600*3;
            }
        }
        else
        {
            $users[shield] = $time + 3600*12;
        }
        saveUserData($users,"shield");
    }
    else    missionFail();
}

function missionStorm ()
{
    global $users, $uratio, $enemy, $eera, $eratio, $shmod, $time, $wizloss, $uera;
    global $spratio, $mission_num;
    if ($uratio > ($eratio * $spratio[$mission_num]))
    {
        $foodloss = round($enemy[food] * .0912 * $shmod);
        $cashloss = round($enemy[cash] * .1265 * $shmod);
        $enemy[food] -= $foodloss;
        $enemy[cash] -= $cashloss;
        missionSucceed("Your $uera[wizards] have destroyed ".commas(gamefactor($foodloss))." $eera[food] and $".commas(gamefactor($cashloss))."!");
        if ($enemy[shield] > $time)
        {
            missionShielded();
            addNews(204, array(id1=>$enemy[num], id2=>$users[num], cash1=>$cashloss, food1=>$foodloss, shielded=>1, clan1=>$enemy[clan], clan2=>$users[clan]));
        }
        else    addNews(204, array(id1=>$enemy[num], id2=>$users[num], cash1=>$cashloss, food1=>$foodloss, shielded=>0, clan1=>$enemy[clan], clan2=>$users[clan]));
        $users[offsucc]++;
        $enemy[l_attack] = $users[num];
    }
    else
    {
        missionFail();
        addNews(204, array(id1=>$enemy[num], id2=>$users[num], shielded=>-1, clan1=>$enemy[clan], clan2=>$users[clan]));
        $enemy[defsucc]++;
    }
    saveUserData($enemy,"networth food cash");
}

function missionRunes ()
{
    global $users, $uratio, $enemy, $eera, $eratio, $shmod, $time, $wizloss, $uera;
    global $spratio, $mission_num;
    if ($uratio > ($eratio * $spratio[$mission_num]))
    {
        $runeloss = round($enemy[runes] * .03 * $shmod);
        $enemy[runes] -= $runeloss;
        missionSucceed("Your $uera[wizards] have destroyed ".commas(gamefactor($runeloss))." of your enemy's $eera[runes]!");
        if ($enemy[shield] > $time)
        {
            missionShielded();
            addNews(205, array(id1=>$enemy[num], id2=>$users[num], runes1=>$runeloss, shielded=>1, clan1=>$enemy[clan], clan2=>$users[clan]));
        }
        else
            addNews(205, array(id1=>$enemy[num], id2=>$users[num], runes1=>$runeloss, shielded=>0, clan1=>$enemy[clan], clan2=>$users[clan]));
        $users[offsucc]++;
        $enemy[l_attack] = $users[num];
    }
    else
    {
        missionFail();
        addNews(205, array(id1=>$enemy[num], id2=>$users[num], shielded=>-1, clan1=>$enemy[clan], clan2=>$users[clan]));
        $enemy[defsucc]++;
    }
    saveUserData($enemy,"runes");
}

function missionStruct ()
{
    global $users, $uratio, $enemy, $eratio, $shmod, $time, $wizloss, $uera;
    global $spratio, $mission_num;
    if ($uratio > ($eratio * $spratio[$mission_num]))
    {
        $shmod *= 0.03;
        missionSucceed("Your $uera[wizards] have destroyed part of your enemy's structures!");
        if ($enemy[shops] >= 15 * $shmod) {    $destroyed += ceil($enemy[shops] * $shmod);    $enemy[shops] -= ceil($enemy[shops] * $shmod); }
        if ($enemy[homes] >= 15 * $shmod) {    $destroyed += ceil($enemy[homes] * $shmod);    $enemy[homes] -= ceil($enemy[homes] * $shmod); }
        if ($enemy[industry] >= 15 * $shmod) {    $destroyed += ceil($enemy[industry] * $shmod);    $enemy[industry] -= ceil($enemy[industry]* $shmod); }
        if ($enemy[barracks] >= 15 * $shmod) {    $destroyed += ceil($enemy[barracks] * $shmod);    $enemy[barracks] -= ceil($enemy[barracks] * $shmod); }
        if ($enemy[farms] >= 15 * $shmod) {    $destroyed += ceil($enemy[farms] * $shmod);    $enemy[farms] -= ceil($enemy[farms] * $shmod); }
        if ($enemy[labs] >= 15 * $shmod) {    $destroyed += ceil($enemy[labs] * $shmod);    $enemy[labs] -= ceil($enemy[labs] * $shmod); }
        if ($enemy[towers] >= 10 * $shmod) {    $destroyed += ceil($enemy[towers] * $shmod);    $enemy[towers] -= ceil($enemy[towers] * $shmod); }
        $enemy[freeland] += $destroyed;
        if ($enemy[shield] > $time)
        {
            missionShielded();
            addNews(206, array(id1=>$enemy[num], id2=>$users[num], land1=>$destroyed, shielded=>1, clan1=>$enemy[clan], clan2=>$users[clan]));
        }
        else
            addNews(206, array(id1=>$enemy[num], id2=>$users[num], land1=>$destroyed, shielded=>0, clan1=>$enemy[clan], clan2=>$users[clan]));
        $users[offsucc]++;
        $enemy[l_attack] = $users[num];
    }
    else
    {
        missionFail();
        addNews(206, array(id1=>$enemy[num], id2=>$users[num], shielded=>-1, clan1=>$enemy[clan], clan2=>$users[clan]));
        $enemy[defsucc]++;
    }
    saveUserData($enemy,"networth shops homes industry barracks farms labs towers freeland");
    db_safe_query("UPDATE $playerdb SET land=(homes+shops+industry+barracks+labs+farms+towers+freeland) WHERE num=$enemy[num];");
}

function cashandfood() {
    global $users, $urace, $lratio, $config;
    $ret = (100/$config[wpl]) * $users[wizards] * $users[health]/100 * 65 * (1 + $users[labs] / $users[land]) * $urace[magic] / (calcSizeBonus($users[networth]) * calcSizeBonus($users[networth]));
    if($config['strat_balance'])
        $ret *= 1.2;
    return $ret;
}

function missionFood ()
{
    global $users, $urace, $lratio, $config, $uera, $produced;
    global $spratio, $mission_num;
    if ($lratio >= $spratio[$mission_num])
    {
        $food = cashandfood()/(3*$config[food]);
        $users[food] += $food;
        $produced += $food;
    }
    else    missionFail();
}


function missionKill() {
    global $users, $urace, $lratio, $config, $produced;
    global $spratio, $mission_num;
    if ($lratio >= $spratio[$mission_num]) {
        foreach($config[troop] as $num => $mktcost) {
            $users[troop][$num] = 0;
        }
        $users[peasants] = 0;
        saveUserData($users, "networth troops peasants");
    }
    else    missionFail();
}


function missionED ()
{
    global $users, $urace, $lratio, $config, $produced;
    global $spratio, $mission_num;
    $edcap = 500;
    if ($lratio >= $spratio[$mission_num])
    {
        $randland = round(mt_rand(0,round($users[land] * .02 + 1)));
        if ($randland < ($users[land] * .03)) { $randland = round($users[land] * .03); }
        if ($randland > ($users[land] * .06)) { $randland = round($users[land] * .06); }

        $newland=0.1*$randland;
        if ($newland > $edcap) {$newland=$edcap;}
        if ($newland < 0) {$newland=$edcap;}
        $mageloss= (100-($newland/250))/100;
        if ($mageloss < .94) {$mageloss=.94;}
        $foodloss= (100-($newland/150))/100;
        if ($foodloss < .92) {$foodloss=.92;}
        $cashloss= (100-($newland/750))/100;
        if ($cashloss < .95) {$cashloss=.95;}

        $newland = $newland*$users[health]/100;
        if ($newland > $edcap) {$newland=$edcap;}
        if ($newland < 0) {$newland=$edcap;}

        $land = $newland;
        $land *= $config['scout_mage_mult'];
        $produced += $land;

        $users[land] = $users[land] + $land;
        $users[freeland] = $users[freeland] + $land;
        $users[food]=round($users[food]*$foodloss);
        $users[cash]=round($users[cash]*$cashloss);

        $cashloss = round($cashloss);
        $foodloss = round($foodloss);
        $mageloss = round($mageloss);
        $cashloss = commas($cashloss);
        $foodloss = commas($foodloss);
        $mageloss = commas($mageloss);

        saveUserData($users,"health land freeland cash food wizards");
    }
    else    missionFail();
}



function missionGold ()
{
    global $users, $urace, $lratio, $produced;
    global $spratio, $mission_num;
    if ($lratio >= $spratio[$mission_num])
    {
        $money = cashandfood();
        $users[cash] += $money;
        $produced += $money;
    }
    else    missionFail();
}

function missionGate ()
{
    global $users, $lratio, $time;
    global $spratio, $mission_num;
    if ($lratio >= $spratio[$mission_num])
    {
        if ($users[gate] > $time)
        {
            if ($users[gate] < $time + 3600*3)
            {
                $users[gate] = $time + 3600*12;
            }
            else
            {
                $users[gate] += 3600*3;
            }
        }
        else
        {
            $users[gate] = $time + 3600*12;
        }
        saveUserData($users,"gate");
    }
    else    missionFail();
}

function missionUngate ()
{
    global $users, $lratio, $time;
    global $spratio, $mission_num;
    if ($lratio >= $spratio[$mission_num])
    {
        $users[gate] = $time;
        saveUserData($users,"gate");
    }
    else    missionFail();
}

function wizDestroyBuildings ($type, $pcloss)
{
    global $buildloss, $config, $enemy, $users;
    $pcloss /= 100;
    $pcloss *= $config['landloss_mage_mult'];
    $loss = 0;
    if ($enemy[$type] > 0)
        $loss = mt_rand(1,ceil($enemy[$type] * $pcloss + 2));
    if ($loss > $enemy[$type])
        $loss = $enemy[$type];
    $enemy[l_attack] = $users[num];
    $enemy[$type] -= $loss;
    $buildloss += $loss;
}

function missionFight ()
{
    global $users, $uera, $uratio, $enemy, $eera, $eratio, $lratio, $buildloss, $wizloss, $config;
    global $spratio, $mission_num;
    if ($config['force_atktype'] != 0 && $enemy[land] <= $config['force_atkland'])
        TheEnd("Your enemy's forces are too highly concentrated to attempt a $config[missionfight].");

    if ($enemy[land] <= $config['force_atkland'] && $config['force_atktype'] != 2)
                   TheEnd("Your Generals politely refuse your orders to attack such a small empire with a non-" . $config['atknames'][$config['force_atktype']] . "!");

    if ($lratio >= $spratio[$mission_num])
    {
        missionSucceed("Your $uera[wizards] battle it out with $enemy[empire]'s...");
        if ($uratio > $eratio * 2.2)
        {
            print "...and you are successful in defeating your opponent's $eera[wizards]!<br>\n";
            $uloss = mt_rand(0,round($users[wizards] * 0.09 + 1));
            $eloss = mt_rand(0,round($enemy[wizards] * 0.06 + 1));
            if ($uloss > $users[wizards])    $uloss = $users[wizards];
            if ($eloss > $enemy[wizards])    $eloss = $enemy[wizards];
            $buildloss = 0;
            wizDestroyBuildings(homes,7);
            wizDestroyBuildings(shops,7);
            wizDestroyBuildings(industry,7);
            wizDestroyBuildings(barracks,7);
            wizDestroyBuildings(labs,7);
            wizDestroyBuildings(farms,7);
            wizDestroyBuildings(towers,7);
            wizDestroyBuildings(freeland,10);
            $users[land] += $buildloss;
            $users[freeland] += $buildloss;
            $enemy[land] -= $buildloss;
            print "Your $uera[wizards] penetrated $enemy[empire]'s defense and captured $buildloss acres of land!.<br>\n";
            print "You also killed ".commas(gamefactor($eloss))." $eera[wizards], losing ".commas(gamefactor($uloss))." of your $uera[wizards] in the process!<br>\n";
            if ($enemy[land] == 0)
            {
                print '<span class="cgood">'."<b>$enemy[empire] <a class=proflink href=?profiles&num=$enemy[num]<?=$authstr?>>(#$enemy[num])</a></b> has been destroyed!</span><br>\n";
                $users[kills]++;
                addNews(301, array(id1=>$enemy[num], id2=>$users[num], clan1=>$enemy[clan], clan2=>$users[clan]));
            } else {
                        echo "$enemy[empire] has ".commas($enemy[land])." acres of land left.<br>\n";
            }

            addNews(211, array(id1=>$enemy[num], id2=>$users[num], land1=>$buildloss, wizards1=>$eloss, wizards2=>$uloss, shielded=>0, clan1=>$enemy[clan], clan2=>$users[clan]));
            $users[offsucc]++;
            $enemy[l_attack] = $users[num];
        }
        else
        {
            print "...and you fail to succeed against your enemy's $eera[wizards]!<br>\n";
            $uloss = mt_rand(0,round($users[wizards] * 0.10 + 1));
            $eloss = mt_rand(0,round($enemy[wizards] * 0.05 + 1));
            if ($uloss > $users[wizards])    $uloss = $users[wizards];
            if ($eloss > $enemy[wizards])    $eloss = $enemy[wizards];
            print "You lose ".commas(gamefactor($uloss))." $uera[wizards], but you manage to kill ".commas(gamefactor($eloss))." of your enemy's $eera[wizards]!<br>\n";
            addNews(211, array(id1=>$enemy[num], id2=>$users[num], wizards1=>$eloss, wizards2=>$uloss, shielded=>1, clan1=>$enemy[clan], clan2=>$users[clan]));
            $enemy[defsucc]++;
        }
        $users[wizards] -= $uloss;
        $enemy[wizards] -= $eloss;
    }
    else
    {
        missionFail();
        addNews(211, array(id1=>$enemy[num], id2=>$users[num], shielded=>-1, clan1=>$enemy[clan], clan2=>$users[clan]));
    }
        //bountyScan($users, $enemy);
    saveUserData($enemy,"networth homes shops industry barracks labs farms towers freeland land wizards offsucc defsucc");
    saveUserData($users,"freeland land offsucc defsucc");    // wizards get saved in takeTurns()
    db_safe_query("UPDATE $playerdb SET land=(homes+shops+industry+barracks+labs+farms+towers+freeland) WHERE num=$users[num];");
    db_safe_query("UPDATE $playerdb SET land=(homes+shops+industry+barracks+labs+farms+towers+freeland) WHERE num=$enemy[num];");
}



function missionSteal ()
{
    global $users, $uratio, $enemy, $eratio, $shmod, $time, $wizloss;
    global $spratio, $mission_num;
    if ($uratio > ($eratio * $spratio[$mission_num]))
    {
        $money = round($enemy[cash]/100000 * mt_rand(ceil(10000 * $shmod), ceil(15000 * $shmod)));
        $users[cash] += $money;
        $enemy[cash] -= $money;
        missionSucceed("You have embezzled $".commas(gamefactor($money))." from your enemy's treasury!");
        if ($enemy[shield] > $time)
        {
            missionShielded();
            addNews(212, array(id1=>$enemy[num], id2=>$users[num], cash1=>$money, shielded=>1, clan1=>$enemy[clan], clan2=>$users[clan]));
        }
        else
            addNews(212, array(id1=>$enemy[num], id2=>$users[num], cash1=>$money, shielded=>0, clan1=>$enemy[clan], clan2=>$users[clan]));
        $users[offsucc]++;
        $enemy[l_attack] = $users[num];
    }
    else
    {
        missionFail();
        addNews(212, array(id1=>$enemy[num], id2=>$users[num], shielded=>-1, clan1=>$enemy[clan], clan2=>$users[clan]));
        $enemy[defsucc]++;
    }
    saveUserData($enemy,"networth cash");
}

function missionRob ()
{
    global $users, $uratio, $enemy, $eratio, $shmod, $time, $wizloss;
    global $spratio, $mission_num;
    if ($uratio > ($eratio * $spratio[$mission_num]))
    {
        $food = round($enemy[food]/100000 * mt_rand(ceil(10000 * $shmod), ceil(15000 * $shmod)));
        $users[food] += $food;
        $enemy[food] -= $food;
        missionSucceed("You have robbed your enemy's granaries, taking ".commas(gamefactor($food))." food!");
        if ($enemy[shield] > $time)
        {
            missionShielded();
            addNews(213, array(id1=>$enemy[num], id2=>$users[num], food1=>$food, shielded=>1, clan1=>$enemy[clan], clan2=>$users[clan]));
        }
        else
            addNews(213, array(id1=>$enemy[num], id2=>$users[num], food1=>$food, shielded=>0, clan1=>$enemy[clan], clan2=>$users[clan]));
        $users[offsucc]++;
        $enemy[l_attack] = $users[num];
    }
    else
    {
        missionFail();
        addNews(213, array(id1=>$enemy[num], id2=>$users[num], shielded=>-1, clan1=>$enemy[clan], clan2=>$users[clan]));
        $enemy[defsucc]++;
    }
    saveUserData($enemy,"networth food");
}




function missionAdvance () {
    global $users, $uera, $lratio, $spname, $eraCount;
    global $spratio, $mission_num, $num_times;
    if($num_times != 1)
        TheEnd("Moving can only be executed one step at a time!");
    if ($users[era] == $eraCount)
        TheEnd("There are no more lands over beyond what we know as the ends of the Earth.");
    if ($lratio >= $spratio[$mission_num])
    {
        $users[era] += 1;
        saveUserData($users,"era");
        $uera = loadEra($users[era], $users[race]);    // update era-specific stuff immediately
    }
    else    missionFail();
}

function missionSouth () {
    global $users, $uera, $lratio, $spname;
    global $spratio, $mission_num, $num_times;
    if($num_times != 1)
        TheEnd("Moving can only be executed one step at a time!");
    if ($users[era] == 1)
        TheEnd("There are no more lands over beyond what we know as the ends of the Earth.");
    if ($lratio >= $spratio[$mission_num])
    {
        $users[era] -= 1;
        saveUserData($users,"era");
        $uera = loadEra($users[era], $users[race]);    // update era-specific stuff immediately
    }
    else    missionFail();
}


function missionHeal () {
    global $users, $lratio;
    global $spratio, $mission_num;
    if ($lratio >= $spratio[$mission_num]) {
        $users[health] += 3;
        if($users['hero_special'] == 1)
            $users[health] += 5;
        if($users[health] >= 100) $users[health] = 100;
        saveUserData($users,"health");
    } else missionFail();
}


function missionProd() {
    global $users, $lratio, $playerdb, $config;
    global $spratio, $mission_num;
    if ($lratio >= $spratio[$mission_num]) {
        for($i=0; $i<5; $i++) {
            //echo "$i : Prod";
            $users[bmp] = explode("|", $users[bmper]);
            foreach($config[troop] as $num => $mktcost) {
                if($users[bmp][$num] > (100*(1+$users[shops]/$users[land])))
                        $users[bmp][$num] = $users[bmp][$num]-(100*(1+$users[shops]/$users[land]));
                if($users[bmp][$num] < (100*(1+$users[shops]/$users[land])))
                        $users[bmp][$num] = 0;
            }
            $users[bmper] = $users[bmp];
            saveUserData($users, "bmper");

            $users[bmp] = explode("|", $users[pvmarket]);
            foreach($config[troop] as $num => $mktcost) {
                if($users[bmp][$num] < (250*($users[land]+(2*$users[barracks]))))
                        $users[bmp][$num] = round(($users[bmp][$num]+((4000/$mktcost)*($users[land]+$users[barracks]))));
            }
            $users[pmkt] = $users[bmp];
            saveUserData($users, "pvmarket");
        }
    }
    else
        missionFail();
}

function missionpeasant() {
    global $users, $lratio, $playerdb, $produced;
    global $spratio, $mission_num;
    for($i=0; $i<8; $i++) {
        $popbase = round((($users[land] * 2) + ($users[freeland] * 5) + ($users[homes] * 60)) / (0.95 + $taxrate + $taxpenalty));
        if ($users[peasants] != $popbase)
        $peasants = ($popbase - $users[peasants]) / 20;
        if ($peasants > 0)    $peasmult = (4 / (($users[tax] + 15) / 20)) - (7 / 9);
        if ($peasants < 0)    $peasmult = 1 / ((4 / (($users[tax] + 15) / 20)) - (7 / 9));
        $peasants = round($peasants * $peasmult * $peasmult);
        if($peasants < 0)
            $peasants = 0;
        $users[peasants] += $peasants;
                $produced += $peasants;
    }
    saveUserData($users, "peasants");

}

function missionGamble () {
    global $users, $uera, $lratio, $playerdb, $produced, $wizloss, $gamblemessage;
    global $spratio, $mission_num;
    if ($lratio >= $spratio[$mission_num]) {
        $cashper = 1000; // This is the amount of cash per wizard.
        $gamble = mt_rand(1,2);
        if ($gamble == 1) {
            $cashgained = $users[wizards]*$cashper;
            $users[cash] += $cashgained;
            $gamblemessage = "Your ".$uera[wizards]." have succeeded in their gamble! You gained $".commas(gamefactor($cashgained)).".";
        } else {
            $wizloss += min($users[wizards], round($users[wizards] * 0.25));
            missionFail('Your gamble <span class="cbad">fails!</span><br>');
        }
        saveUserData($users, "wizards cash");
    }
    else
        missionFail();
    return $gamblemessage;
}

?>

<?
include("header.php");

function killUnits ($type, $minpc, $maxpc, $rolls, $num=0) {
        global $users, $config;
        $losspct = 0;
        $min = round(1000000 * $minpc);
        $max = round(1000000 * $maxpc);
        for ($i = 0; $i < $rolls; $i++)
                $losspct += mt_rand($min,$max);
        $losspct /= 1000000;
    if($type == 'troop') {
            $loss = round($users['troop'][$num] * $losspct);
            $users['troop'][$num] -= $loss;
    } else {
            $loss = round($users[$type] * $losspct);
            $users[$type] -= $loss;
    }
        return $loss;
}
$reqturnsused = $config['hero_reqturns'];
$reqland = $config['hero_landreq'];
$req = array();
$req_ok = array();
$reqnames = array();
$tr = array();
$tr1 = array(); // for mages, gamefactored
$tr2 = array(); // non-gamefactored array
foreach($config['troop'] as $num => $mktcost) { // troops
    $req[$num] = round((500/$config['troop'][0])*100*$config['troop'][0]*$users['land']/$mktcost);
    $req_ok[$num] = 0;
    if($users['troop'][$num] >= $req[$num])
        $req_ok[$num] = 1;
    $reqnames[$num] = $uera["troop$num"];
    $tr[] = array('name' => $uera["troop$num"], 'ok' => $req_ok[$num], 'reqd' => commas($req[$num]));
}
$req['wizards'] = ceil(($users['land']*10)*($config['wpl']/100));
$reqnames['wizards'] = $uera['wizards'];
foreach($req as $stuff => $reqd) {  // wizards/mages
    if(is_integer($stuff))
        continue;
    $req_ok[$stuff] = 0;
    if($users[$stuff] >= $reqd)
        $req_ok[$stuff] = 1;
    $tr1[] = array('name' => $reqnames[$stuff], 'ok' => $req_ok[$stuff], 'reqd' => commas($reqd));
}

$req1['land'] = $config['hero_landreq'];
$req1['health'] = $config['hero_healthreq'];
$req1['turns'] = $config['hero_turnsreq'];
$reqnames['land'] = 'acres';
$reqnames['health'] = 'health';
$reqnames['turns'] = 'turns';
foreach($req1 as $stuff => $reqd) {  // land, health, turns
    if(is_integer($stuff))
        continue;
    $req_ok[$stuff] = 0;
    if($users[$stuff] >= $reqd)
        $req_ok[$stuff] = 1;
    $tr2[] = array('name' => $reqnames[$stuff], 'ok' => $req_ok[$stuff], 'reqd' => commas($reqd));
}

$req_okall = 1;
foreach($req_ok as $stuff => $okd) {
    if($okd == 0) {
        $req_okall = 0;
        break;
    }
}

if($warh1 || $warh2 || $warh3 || $peaceh1 || $peaceh2 || $peaceh3 || $specialh1 || $specialh2 || $specialh3) {
    if($req_okall == 0 || $users['turnsused'] < $reqturnsused)
        TheEnd("You don't meet the requirements for getting a hero!");

                $users['health'] -= $req1['health'];
                $users['turns'] -= $req1['turns'];
                print "The earth shakes. Great rifts appear in the ground, and fires break out. Suddenly, you hear a noise behind you. Out of the new ruins of your tent steps a shadowy figure. Before you have time to do anything, the figure takes out his sword, and offers it to you, hilt first. \"I am at your service, Sire\".<br>";
        foreach($config['troop'] as $num => $mktcost) {
            print commas(killUnits(troop,0.40,0.5,1,$num)).' '.gamefactor($uera["troop$num"]).', ';
        }
                print    commas(killUnits(peasants,0.4,0.50,1))." ".gamefactor($uera['peasants']).", and ".
                        commas(killUnits(wizards,0.4,0.5,1))." ".gamefactor($uera['wizards'])." died in the quake.<br>\n";
                $buildloss = 0;
                $buildloss += killUnits(homes,0.4,0.5,2);
                $buildloss += killUnits(shops,0.4,0.5,2);
                $buildloss += killUnits(industry,0.4,0.5,2);
                $buildloss += killUnits(barracks,0.4,0.5,2);
                $buildloss += killUnits(labs,0.4,0.5,2);
                $buildloss += killUnits(farms,0.4,0.5,2);
                $buildloss += killUnits(towers,0.4,0.5,2);
                $users[freeland] += $buildloss;
                $size = calcSizeBonus($users[networth]);
                print commas($buildloss)." structures, ".
                        commas(killUnits(food,0.1,0.2,3))." ".gamefactor($uera['food']).", ".
                        commas(killUnits(runes,0.1,0.2,3))." ".gamefactor($uera['runes']).", and \$".
                        gamefactor(commas(killUnits(cash,0.1,0.2,3)))." were lost during the chaos.<br>\n";

     if($warh1)        $users['hero_war'] = 1;
else if($warh2)        $users['hero_war'] = 2;
else if($warh3)        $users['hero_war'] = 3;

else if($peaceh1)    $users['hero_peace'] = 1;
else if($peaceh2)    $users['hero_peace'] = 2;
else if($peaceh3)    $users['hero_peace'] = 3;

else if($specialh1)    $users['hero_special'] = 1;
else if($specialh2)    $users['hero_special'] = 2;
else if($specialh3)    $users['hero_special'] = 3;

else
    TheEnd("The hero failed to arrive. Please report this to the administrators as a bug.");

saveUserData($users,"networth troops wizards homes shops industry barracks labs farms towers freeland food runes cash turns health hero_war hero_peace hero_special");

}

$warh[1] = "Matthias";            // 25% better attack
$warh[2] = "Cregga";            // 25% better defense
$warh[3] = "Mactalon";            // better at leader missions

$peaceh[1] = "Grumm";            // good grain
$peaceh[2] = "Methuselah";        // good loyalty
$peaceh[3] = "Bella";            // You can store more workers

$specialh[1] = "Brome";            // 2 times better healing
$specialh[2] = "Martin";        // The more attacks you have, the better your offenive power
$specialh[3] = "Perigord";        // 3% losses are only 1% losses for you

$wardesc[1] = "Matthias the Warrior. With him, victory is more certain.<br>Specifically, 25% more certain.";
$wardesc[2] = "The Lady Cregga is a fearful warrior. With her, your defense is stronger.<br>Specifically, 25% stronger.";
$wardesc[3] = "Mactalon, Laird of the Skies. With him, you are better at missions.<br>Specifically, you need a 25% lower ratio to succeed.";

$peacedesc[1] = "Grumm the Molecook. With him, your fields will yield plentiful crops.<br>Specifically, 50% more plentiful.";
$peacedesc[2] = "Methuselah the Scholar. With him, you will collect more $uera[runes].<br>Specifically, 50% more $uera[runes].";
$peacedesc[3] = "Bella of Brockhall. With her, more ".$uera['peasants']." will gladly live in your tents.<br>Specifically, 5 times as many.";

$specialdesc[1] = "Brome the Healer. With him, your health shall always be good.<br>Specifically, you will heal 2 points per turn.";
$specialdesc[2] = "Martin the Warrior, the Avenging Spirit. With him, the more you are attacked, the better shall be your offense.<br>Specifically, (recent_attacks / 20)% better.";
$specialdesc[3] = "Major Perigord of the Long Patrol. With him, less troops and ".$uera['peasants']." will desert you in trouble.<br>Specifially, your losses will be 1% instead of 3%.";

$warname; $peacename; $specialname; $count = 0;

foreach($warh as $num => $name) {
    $GLOBALS["war$num"] = $name;
    $GLOBALS["ward$num"] = $wardesc[$num];
}
foreach($peaceh as $num => $name) {
    $GLOBALS["peace$num"] = $name;
    $GLOBALS["peaced$num"] = $peacedesc[$num];
}
foreach($specialh as $num => $name) {
    $GLOBALS["special$num"] = $name;
    $GLOBALS["speciald$num"] = $specialdesc[$num];
}

if($users['turnsused'] < 1000)  $req_okall = 0;

foreach($config['troop'] as $num => $mktcost)
$req["troop$num"] = gamefactor($req["troop$num"]);
$req['wizards'] = gamefactor($req['wizards']);
$req['runes'] = gamefactor($req['runes']);
$req['cash'] = gamefactor($req['cash']);
$req['food'] = gamefactor($req['food']);
foreach($req as $stuff => $reqd) {
    $req[$stuff] = commas($reqd);
}
$req[health] = $req['health'].'%';
# TODO: This display code seems half-baked. What happened?
# For instance, the display isn't gamefactored, health isn't shown with a %, also no config code sets healthreq or turnsreq -- what gives?
$req_display = array_merge($tr, $tr1, $tr2);

$who = 'The following Heroes are helping you: <br>';
if($users['hero_war']) {
    $who .= $warh[$users['hero_war']]."<br>";
}
if($users['hero_peace']) {
    $who .= $trans.$peaceh[$users['hero_peace']]."<br>";
}
if($users['hero_special']) {
    $who .= $trans2.$specialh[$users['hero_special']]."<br>";
}

$who .= '<br>';

template_display('hero.html');
TheEnd("");
?>

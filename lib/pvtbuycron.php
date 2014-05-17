<?
if(!defined("PROMISANCE"))
    die(" ");
if($times = howmanytimes($users['pvmarket_last'], $perminutes)) {
    echo "<!--";
    echo "$times\n";
    $users[bmp] = explode("|", $users[pvmarket]);
    foreach($config[troop] as $num => $mktcost) {
        if($users[bmp][$num] < (250*($users[land]+(2*$users[barracks]))))
            $users[bmp][$num] = round(($users[bmp][$num] + $times*((4000/$mktcost)*($users[land]+$users[barracks]))));
    }
    $users[pmkt] = $users[bmp];

    $users[pmkt_food] += $times*50*($users[land] + $users[farms]);
    if($users[pmkt_food] > 2000*($users[land] + $users[farms]))
        $users[pmkt_food] = 2000*($users[land] + $users[farms]);

    $users[pvmarket_last] = $time - $time%($perminutes*60);
    print_r($users[pmkt]);
    echo "-->";
    saveUserData($users, "pvmarket pvmarket_last pmkt_food");
}
?>

<?
include("header.php");

$theuser = $users;
if(empty($sort_type))
    $sort_type = 'totalnet';

$minmembers = $config['clan_minsize'];
$members = array(); $totalnet = array(); $avgnet = array();

$allusers = db_safe_query("SELECT clan,networth FROM $playerdb WHERE land>0 ORDER BY networth DESC;");
$unallied = $utotal = 0;
while ($users = mysqli_fetch_array($allusers)) {
    if ($n = $users[clan]) {
        $members[$n]++;
        $totalnet[$n] += $users[networth];
        $avgnet[$n] = round($totalnet[$n] / $members[$n]);
    }
    else    $unallied++;
    $utotal++;
}

foreach($totalnet as $id => $net) {
    $totalnet[$id] = $net;
}

if ($unallied == $utotal) {
    $users = $theuser;
    TheEnd("No clans currently exist!");
}

$sortd = '';
switch ($sort_type) {
case 'members':
    $sortd = "Total Members";
    $sortby = $members;
    break;
case 'avgnet':
    $sortd = "Average Networth";
    $sortby = $avgnet;
    break;
case 'totalnet':
    $sortd = "Total Networth";
    $sortby = $totalnet;
    break;
}
arsort($sortby);
reset($sortby);
while (list($key,$val) = each($sortby))
    $clan[] = $key;
reset($sortby);
reset($clan);

$clans = array();

$cunlisted = $ctotal = 0;
while (list(,$num) = each($clan)) {
    $uclan = loadClan($num);
    if ($uclan[members] >= $minmembers) {
        if($uclan[url])
            $uclan[name] = "<a href=\"$uclan[url]\" target=\"_blank\">$uclan[name]</a>";

        $uclan['avgnet'] = commas($avgnet[$uclan['num']]);
        $uclan['totalnet'] = commas($totalnet[$uclan['num']]);

        $clans[] = $uclan;
    }
    else {
        $cunlisted++;
    }
    $ctotal++;
}


$notmade = "$cunlisted/$ctotal (".round($cunlisted/$ctotal*100)."%)";
$indeps = "$unallied/$utotal (".round($unallied/$utotal*100)."%)";

template_display('clanstats.html');
TheEnd();
?>

<?
if(!defined("PROMISANCE"))
    die(" ");

global $config;
$permin = floor($config['msgcred_bump']/60);
if($times = howmanytimes($users['msgcred_got'], $permin)) {
    if($users['msgcred'] < 10)
        $users['msgcred'] += $times;
    if($users['msgcred'] > 10)
        $users['msgcred'] = 10;
    $users['msgcred_got'] = $time - $time%($config['msgcred_bump']);
    saveUserData($users, "msgcred msgcred_got");
}
?>

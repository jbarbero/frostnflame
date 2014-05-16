<?
if(!defined("PROMISANCE"))
	die(" ");
if($times = howmanytimes($users['aidcred_got'], 60)) {
	if($users['aidcred'] < 5)
		$users['aidcred'] += $times;
	if($users['aidcred'] > 5)
		$users['aidcred'] = 5;
	$users['aidcred_got'] = $time - $time%3600;
	saveUserData($users, "aidcred aidcred_got");
}
?>

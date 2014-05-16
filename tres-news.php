<?php
if(!defined("PROMISANCE"))
	die(" ");
$user = $users;

if($user[newssort] == 0)
	$order = 'DESC';
elseif($user[newssort] == 1)
	$order = 'ASC';

$time_limit = $time-3*24*3600;	//3 days
$cnt_limit = 15;

$unum = $user[num];
$news = db_safe_query("SELECT * FROM $newsdb WHERE (clan1=$user[clan] OR clan2=$user[clan]) AND code=502 AND time>$time_limit AND id2!=$unum 
		ORDER BY time DESC LIMIT 0,$cnt_limit;");
if (!@mysql_num_rows($news))
	return 0;

echo '<table class="inputtable" border=1>';
echo '<tr><th>Time</th>';
echo '<th colspan="2">Event</th>';
echo '<th style="width:2">Times</th></tr>';

while ($new = mysql_fetch_array($news)) {
	$time = $new[time];

	$id1 = $new[id1];
	$id2 = $new[id2];
	if($id1 == $user[num])
		$name = 'You';
	else
		$name = db_safe_firstval("SELECT empire FROM $playerdb WHERE num=$id1;").' <a class=proflink href=?profiles&num='.$id1.$authstr.'>(#'.$id1.')</a>';

	echo '<tr style="vertical-align:top"><th>';

	$hours = (time() - $new[time]) / 3600;
	if ($hours > 24) {
		$days = floor($hours / 24);
		print $days . " days, ";
		$hours -= $days * 24;
	} 
	echo round($hours, 1).' hours ago';
	echo '</th>';

	$new['cash1'] = gamefactor($new['cash1']);
	$new['food1'] = gamefactor($new['food1']);
	$new['runes1'] = gamefactor($new['runes1']);

	echo '<td colspan="2"><span class="cgood">'.$name.':';
	if($new[cash1] != 0)
		echo '<li>'.($new[cash1]>0?'gave':'took').' $'.commas(abs($new[cash1])).' '.($new[cash1]>0?'to':'from').' the clan treasury.</li>';
	if($new[food1] != 0)
		echo '<li>'.($new[food1]>0?'gave':'took').' '.commas(abs($new[food1]))." $uera[food] ".($new[food1]>0?'to':'from').' the clan granary.</li>';
	if($new[runes1] != 0)
		echo '<li>'.($new[runes1]>0?'gave':'took').' '.commas(abs($new[runes1]))." $uera[runes] ".($new[runes1]>0?'to':'from').' the clan loft.</li>';
	echo '</span></td>';

	echo '<td>'.$new[num].'</td></tr>';
}

echo '</table>';

?>

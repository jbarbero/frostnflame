<?php
include("header.php");

if ($users[disabled] != 2)
	TheEnd("You are not an administrator!");

function printmsg ($message)
{
	$maxlen = 72;
	$words = explode(" ", $message);
	$i = 0;
	for ($i = 0; $i < count($words); $i++) {
		$tmp = $words[$i];
		if (strlen($tmp) < $maxlen)
			print $tmp . " ";
		else {
			$totlen = strlen($tmp);
			$subnum = ceil($totlen / $maxlen);
			$sublen = ceil($totlen / $subnum);
			for ($j = 0; $j < $subnum; $j++) {
				print substr($tmp, $j * $sublen, min($sublen, $totlen));
				$totlen -= $sublen;
				if ($totlen > 0)
					print "- ";
			} 
		} 
	} 
} 
if (!$msgs_age1)
	$msgs_age1 = 0;
if (!$msgs_age2)
	$msgs_age2 = 24;
$minage = min($msgs_age1, $msgs_age2);
$maxage = max($msgs_age1, $msgs_age2);

$query = "";

if (($msgs_doviewfrom) && ($msgs_from))
	$query .= "WHERE src=$msgs_from";
elseif (($msgs_doviewto) && ($msgs_to))
	$query .= "WHERE dest=$msgs_to";
elseif (($msgs_doviewbetw) && ($msgs_betw1) && ($msgs_betw2))
	$query .= "WHERE ((src=$msgs_betw1 AND dest=$msgs_betw2) OR (src=$msgs_betw2 AND dest=$msgs_betw1))";
elseif (($msgs_doviewstr) && ($msgs_str)) {
	sqlQuotes($msgs_str);
	$query .= "WHERE (msg LIKE '%" . $msgs_str . "%')";
} elseif ($msgs_doviewall) ;
else $msgs_timelimit = 1;

?>
<form method="post" action="?msgadmin<?=$authstr?>">
<table class="inputtable">
<tr><th colspan="3"><input type="checkbox" name="msgs_timelimit" value="1"<?php if ($msgs_timelimit) print " checked";

?>> Display messages between <input type="text" name="msgs_age1" value="<?=$minage?>" size="3"> and <input type="text" name="msgs_age2" value="<?=$maxage?>" size="3"> hours ago</th></tr>
<tr>
      <th>View messages sent from <?=$uera[empire]?></th>
    <td>#<input type="text" name="msgs_from" size="4" value="<?=$msgs_from?>"></td>
    <th><input type="submit" name="msgs_doviewfrom" value="View"></th></tr>
<tr>
      <th>View messages sent to <?=$uera[empire]?></th>
    <td>#<input type="text" name="msgs_to" size="4" value="<?=$msgs_to?>"></td>
    <th><input type="submit" name="msgs_doviewto" value="View"></th></tr>
<tr>
      <th>View messages sent between <?=$uera[empire]?></th>
    <td>#<input type="text" name="msgs_betw1" size="4" value="<?=$msgs_betw1?>"> and #<input type="text" name="msgs_betw2" size="4" value="<?=$msgs_betw2?>"></td>
    <th><input type="submit" name="msgs_doviewbetw" value="View"></th></tr>
<tr><th>View messages containing the string</th>
    <td><input type="text" name="msgs_str" size="12" value="<?=$msgs_str?>"></td>
    <th><input type="submit" name="msgs_doviewstr" value="View"></th></tr>
<tr><th colspan="3"><input type="submit" name="msgs_doviewall" value="View All"></th></tr>
</table>
</form>
<?php

$minage *= 3600;
$maxage *= 3600;

if ($msgs_timelimit) {
	if (isset($query))
		$query .= " AND ";
	else $query = "WHERE ";
	$query .= "(($time-$maxage) < time) AND (time < ($time-$minage))";
} 

$msgs = db_safe_query("SELECT time,src as num_s,p1.empire as name_s,dest as num_d,p2.empire as name_d,msg FROM $messagedb LEFT JOIN $playerdb AS p1 ON (src=p1.num) 
	LEFT JOIN $playerdb AS p2 ON (dest=p2.num) $query ORDER BY time ASC;");

if (!@mysql_num_rows($msgs))
	TheEnd("No messages");

?>
<table class="inputtable" style="width:100%">
<tr><th style="width:10%">Time</th>
    <th style="width:15%">From</th>
    <th style="width:15%">To</th>
    <th style="width:60%">Message</th></tr>
<?php
while ($message = mysql_fetch_array($msgs)) {

	?>
<tr><td><?=date("Y/m/d H:i:s", $message[time])?></td>
    <td><?=$message[name_s]?> <a class=proflink href=?profiles&num=<?=$message[num_s]?><?=$authstr?>>(#<?=$message[num_s]?>)</a></td>
    <td><?=$message[name_d]?> <a class=proflink href=?profiles&num=<?=$message[num_d]?><?=$authstr?>>(#<?=$message[num_d]?>)</a></td>
    <td style="text-align:left"><?php printmsg(bbcode_parse($message[msg]))?></td></tr>
<tr><td colspan="4" height="1"><hr></td></tr>

<?php
} 

?>
</table>
<?php
TheEnd("");

?>

<?
include("header.php");

$clans = array();
$diplomats = array('founder', 'asst', 'fa1', 'fa2');
$clanlist = db_safe_query("SELECT num,name,tag,founder,fa1,fa2,asst FROM $clandb WHERE members>0 ORDER BY tag;");
while ($clan = mysql_fetch_array($clanlist)) {
	foreach($diplomats as $pos) {
		if($clan[$pos]) {
			$num = $clan[$pos];
			$dipl = mysql_fetch_array(db_safe_query("SELECT num,empire FROM $playerdb WHERE num=$num"));
			$clan[$pos] = $dipl['empire']." <a class=proflink href=?profiles&num=$num$authstr>(#$num)</a>";
		} else {
			$clan[$pos] = "none";
		}
	}

	$clans[] = $clan;
}

$tpl->assign('clans', $clans);
$tpl->display('contacts.html');
TheEnd();
?>

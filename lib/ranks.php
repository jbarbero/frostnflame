<?
if(!defined("PROMISANCE"))
die(" ");
if(howmanytimes(lasttime('ranks'), $perminutes)) {
$users = db_safe_query("SELECT num FROM $playerdb WHERE disabled != 2 AND disabled !=3 AND land>0 ORDER BY networth DESC;");
$urank = 0;
while ($user = mysqli_fetch_array($users)) {
 $urank++;
 db_safe_query("UPDATE $playerdb SET rank=$urank WHERE num=$user[num];");
}
$urank++;
db_safe_query("UPDATE $playerdb SET rank=$urank WHERE disabled=3 OR disabled=2 OR land=0;");
justRun('ranks', $perminutes);
}

global $playerdb, $prefix;
// Retrieve all the data from the table and store the record of the table into $topland
$topland = db_safe_firstval("SELECT num FROM $playerdb WHERE land > 0 AND disabled!=2 AND disabled!=3 ORDER BY land DESC LIMIT 1");
db_safe_query("UPDATE ".$prefix."_system SET topland=".$topland); 

$topoffense = db_safe_firstval("SELECT num FROM $playerdb WHERE land > 0 AND offsucc > 0 AND disabled!=2 AND disabled!=3 ORDER BY offsucc DESC LIMIT 1");
db_safe_query("UPDATE ".$prefix."_system SET topoff=".$topoffense); 

$topdefense = db_safe_firstval("SELECT num FROM $playerdb WHERE land > 0 AND defsucc > 0 AND disabled!=2 AND disabled!=3 ORDER BY defsucc DESC LIMIT 1"); 
db_safe_query("UPDATE ".$prefix."_system SET topdef=".$topdefense);

// Retrieve all the data from the table and store the record of the table into $topland
$topkills = db_safe_firstval("SELECT num FROM $playerdb WHERE land > 0 AND kills > 0 AND disabled!=2 AND disabled!=3 ORDER BY kills DESC LIMIT 1");
db_safe_query("UPDATE ".$prefix."_system SET topkills=".$topkills);

$topnet = db_safe_query("SELECT num FROM $playerdb WHERE land > 0 AND disabled != 2 AND disabled != 3 ORDER BY networth DESC LIMIT 3") or die(mysql_error());
$z = 1;
while($row = mysqli_fetch_assoc($topnet)) {
   db_safe_query("UPDATE ".$prefix."_system SET topnet".$z."=".$row['num']);
   $z++;
}
?>

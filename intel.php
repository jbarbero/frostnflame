<?
include("header.php");
global $config, $users, $prefix, $time;

$inteldb = $prefix."_intel";

if($do_delete) {
	$id = $_POST['id'];
    mysql_query("DELETE FROM $inteldb WHERE id='$id'") or die("Error: ".mysqli_error($GLOBALS["db_link"]));
    header(str_replace('&amp;', '&', "Location: ".$config[sitedir].$config[main]."?intel$authstr")); 
}
echo"<center><big><b>Intelligence Reports</big></b></center><br>";
$sql = mysql_query("SELECT * FROM $inteldb WHERE num=".$users[num]." ORDER BY spytime DESC") or die("Error: ".mysqli_error($GLOBALS["db_link"]));
if(mysqli_num_rows($sql) == 0) echo"<center><span class='cwarn'>No intelligence reports exist for this empire.</span></center>";
else echo"<hr>";
while($row = mysqli_fetch_array($sql)) {
   	$spyinfo = explode("|", $row[spyinfo]);

    echo"<table class='inputtable' style='width:75%'><form method='post' action='?intel$authstr' name='deletereport'>
    <tr><th colspan='3'>".$spyinfo[1]."<a class=proflink href=?profiles&num=".$spyinfo[2].">(#".$spyinfo[2].")</a> - 
    Collected: ".date($dateformat, $row['spytime'])." - <input name='id' value='$row[id]' type='hidden'><input name='do_delete' value='Delete' type='submit'>";

    echo"</th></tr></form>
    <th><td valign='top' style='width:40%'>
    <table class='empstatus' style='width:100%'>
    <tr><th>Turns</th><td>".$spyinfo[3]." (max ".$config[maxturns].")</td></tr>
    <tr><th>Turns Stored</th><td>".$spyinfo[4]." (max ".$config[maxstoredturns].")</td></tr>
    <tr><th>Rank</th><td>#".$spyinfo[5]."</td></tr>
    <tr><th>Peasants</th><td>".commas($spyinfo[6])."</td></tr>
    <tr><th>Land Acres</th><td>".commas($spyinfo[7])."</td></tr>
    <tr><th>Gold</th><td>".commas($spyinfo[8])."</td></tr>
    <tr><th>Food</th><td>".commas($spyinfo[9])."</td></tr>
    <tr><th>Runes</th><td>".commas($spyinfo[10])."</td></tr>
    <tr><th>Networth</th><td>$".commas($spyinfo[11])."</td></tr>
    <tr><th>Experience</th><td>".commas($spyinfo[12])."</td></tr>
    </table></td><td style='width:20%'></td><td style='width:40%'>
    <table class='empstatus' style='width:100%'>
    <tr><th>Era</th><td>".$spyinfo[13]."</td></tr>
    <tr><th>Race</th><td>".$spyinfo[14]."</td></tr>
    <tr><th>Health</th><td>".$spyinfo[15]."%</span></td></tr>
    <tr><th>Tax Rate</th><td>".$spyinfo[16]."%</td></tr>";

    // handle troop array, hack could be better
    $tdata = explode(":", $spyinfo[17]);
	echo"<tr><th>".$config['er'][101]['troop0']."</th><td>".commas($tdata[0])."</td></tr>";
	echo"<tr><th>".$config['er'][101]['troop1']."</th><td>".commas($tdata[1])."</td></tr>";
	echo"<tr><th>".$config['er'][101]['troop2']."</th><td>".commas($tdata[2])."</td></tr>";
	echo"<tr><th>".$config['er'][101]['troop3']."</th><td>".commas($tdata[3])."</td></tr>";
	
    echo"<tr><th>Mages</th><td>".commas($spyinfo[18])."</td></tr>
    </table></th></tr>";

    echo"</td></tr><th colspan='3'><br>Effective Spells: ";
    $sdata = explode(":", $spyinfo[19]);
    foreach($sdata as $sval) {
    	echo" ".$sval;
    }
    echo"</th></tr></table><hr><br>";
}
TheEnd("");
?>

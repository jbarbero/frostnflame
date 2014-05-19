<?
include("header.php");

?>
<br>
<table><tr>
<td class="acenter"><a href="?serverforum<?=$authstr?>">Server Forum</a></td>
</tr></table>
<br>
<?

global $prefix;

$users['motd'] = 0;  // set motd as read
saveUserData($users, "motd");

$sql = db_safe_query("SELECT * FROM ".$prefix."_motd WHERE 1=1 ORDER BY id DESC LIMIT 1") or die("Error: ".mysqli_error($GLOBALS["db_link"]));
echo"<table style='width: 80%; text-align: left; margin-left: auto; margin-right: auto;' border='0' cellpadding='3' cellspacing='0'><tbody>";
while($row = mysqli_fetch_assoc($sql)) {
    echo"<tr><td>Subject: ".$row['subject']."</td><td>Date: ".$row['date']."</td></tr>
    <tr><td colspan='2' rowspan='1'><hr style='width: 100%; height: 2px;'>".stripslashes($row['content'])."</td>
    </tr></tbody>";
}
echo"</table><br>";
TheEnd("");
?>

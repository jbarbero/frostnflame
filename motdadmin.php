<?php
include("header.php");

if ($users[disabled] != 2)
    TheEnd("You are not an administrator!");

$users['motd'] = 0;  // set motd as read
saveUserData($users, "motd");

if(isset($_POST['submit'])) {
    $subject = addslashes($_POST['subject']);
    $content = addslashes(nl2br($_POST['content']));
    $author = 'Admin';
    
    $sql = db_safe_query("INSERT INTO ".$prefix."_motd SET subject='$subject', content='$content', author='$author', date=now()") or die("Error: ".mysqli_error($GLOBALS["db_link"]));    
    $sql = db_safe_query("UPDATE ".$prefix."_players SET motd='1' WHERE 1=1") or die("Error: ".mysqli_error($GLOBALS["db_link"]));
    echo"Message Submitted.";
}

?>
<div style="text-align: center;">New Daily News<br>
</div>
<form method="post" action="?motdadmin<?=$authstr?>" name="motd">
  <table style="width: 70%; text-align: left; margin-left: auto; margin-right: auto;"
         border="0" cellpadding="3" cellspacing="0">
    <tbody>
      <tr>
        <td>Subject: <input maxlength="255" size="40" name="subject"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" rowspan="1"><textarea cols="65" rows="5" name="content"></textarea><br>
        <input name="submit" value="Submit" type="submit"></td>
      </tr>
    </tbody>
  </table>
</form>
<?php
TheEnd("");
?>

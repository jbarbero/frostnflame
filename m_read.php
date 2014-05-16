<?
require_once("funcs.php");
auth_user();
fixInputNum($id_num);

@db_safe_query("UPDATE $messagedb SET readd=1 WHERE id=$id_num AND dest=$users[num];");

?>

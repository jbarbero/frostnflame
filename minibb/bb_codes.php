<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. See COPYING file for more details. Copyright (C) 2004 Paul Puzyrev, Sergei Larionov. www.minibb.net
*/

function enCodeBB($msg,$admin) {

$msg_orig = swear_filter($msg);
$msg = addslashes(bbcode_parse(stripslashes($msg)));
$msg .= " <!-- ORIGINAL: ".base64_encode($msg_orig)." --> ";

return $msg;
}

//--------------->
function deCodeBB($msg) {

$pos = strpos($msg, "<!-- ORIGINAL: ");
$msg = substr($msg, $pos+15);
#return stripslashes(base64_decode(substr($msg, 0, -3)));
return stripslashes(base64_decode(substr($msg, 0, -4)));

return $msg;
}

?>

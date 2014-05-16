<?
$url = $_GET['url'];
header(str_replace('&amp;', '&', "Location: $url"));
?>

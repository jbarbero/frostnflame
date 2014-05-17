<?
require_once("funcs.php");
if (auth_user(true))
    include("header.php");
else
    htmlbegincompact("Game Guide");

if(isset($_GET['section'])) {
    $section = strtolower($_GET['section']);
    $section = preg_replace("/[^a-zA-Z]/", "", $section);
}
else
    $section = 'index';

if($fh = @fopen("guide/$section.txt", 'r')) {
    $tpl->assign('section', $section);
    $buf = "";
    while($b = fread($fh, 2048))
        $buf .= $b;
    fclose($fh);
    $tpl->assign('text', $buf);
} else {
    $tpl->assign('index', 'true');
}

$tpl->display('guide.html');
TheEnd('');

?>

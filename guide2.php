<?php
require_once("funcs.php");
$tpl->assign('extra_headers', "<style>
/* Wiki Styling */  
h2, h3, h4, h5, h6 {
    background: none;
    font-weight: bold;
    margin: 0;
    display: inline;
}

h2 {
    border-bottom: 1px solid #aaa;
}

</style>");

if(!defined("PROMISANCE"))
    die(" ");

if (auth_user(true)) {
    include("header.php");
    $attach = $authstr;
} else {
    htmlbegincompact("Game Guide");
    $attach = $skinstr;
}

/*
        "view"          => 3,
        "info"          => 3,
        "links"         => 3,
        "edit"          => 2,
        "calendar"      => 2,
        "upload"        => 2,
        "view/SecretPage" => 1,
        "delete"        => 1,
        "control"       => 0,
        "admin"         => 0,
        "*"             => 2,   #- anything else requires this ring level
*/

$ewiki_ring = 3;
if($users[disabled] == 2)
    $ewiki_ring = 0;


switch($_GET['section']) {
    case '':    
    case 'login':    
    case 'guide2':    
    case 'features':
        $section = 'Table of Contents';
        break;
    default:
        $section = $_GET['section'];
}

error_reporting(E_ALL);
$attach = str_replace('&amp;', '&', $attach);    /** Ewiki kludge **/
define("EWIKI_SCRIPT", "?guide2$attach&section=");
define("EWIKI_SCRIPT_BINARY", "?guide2$attach&section=");
define("EWIKI_IMAGE_MAXSIZE", 256 *1024);

if(strpos($section, "internal://") === 0) {
    $section = str_replace("internal://", "internal:/", $section);
    ob_clean();
    define('DISPLAY', 0);
} else {
    define('DISPLAY', 1);
}

include_once("lib/ewiki.php");
$faf_old_action = $action;
$content = ewiki_page($section);
$action = $faf_old_action;

if(DISPLAY) {
    echo "<table width=\"100%\"><tr><td align='left'>";
    echo "<h1><a href=\"?guide2$authstr\">Game Guide</a></h1>";
}

echo $content;

if(DISPLAY) {
    echo "</td></tr></table>";
    TheEnd("");
} else {
    ob_end_flush();
}
?>

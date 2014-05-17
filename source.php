<?
if(!defined("PROMISANCE"))
    die(" ");

$allowed = 'a-zA-Z0-9_\-\/';

if(!empty($_GET['bundle'])) {
    Header("Location: faf.tgz");
    exit;
}


if(!empty($_GET['script'])) {
    $script = $_GET['script'];
    $script = preg_replace('/[^'.$allowed.']/', '', $script);
    if(substr($script, 0, 1) == '/')
        die("Can't go there!");
    if(file_exists("$script.php"))
        displayScript("$script.php");
    else
        die("File doesn't exist!");
}
else {
    displayScript('index.php');
}

function displayScript($script, $html=false) {
    global $dbpass, $allowed;
    $pos = strrpos($script, '/');
    $dir = '';
    if($pos)
        $dir = substr($script, 0, $pos+1);
    $buf = str_replace($dbpass, '-------', highlight_file($script, true));
    if($_GET['mark'])
        $buf = str_replace($_GET['mark'], "<b style=\"color:black;background-color:#ffff66\">" . $_GET['mark'] . "</b>", $buf);
    if($_GET['lines']){
        $buf = ($ii=1) . ":\t" . $buf;
        $buf = preg_replace("/<br \/>/e","\"<br />\" . ++\$ii . \":\t\"",$buf);
    }
    echo preg_replace('/[\'"](['.$allowed.']+)(\.php)[\'"]/', '"<a href="?source&script='.$dir.'\\1">\\1.php</a>"', $buf);
}
?>

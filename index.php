<?
error_reporting(E_ALL & ~E_NOTICE);

define("PROMISANCE", true);
//ob_start("ob_gzhandler");
ob_start();

// These two must be included from our own code, so as to make the rest work
require_once("conf-proc.php");        // This below file manually tries to see if local/config.php exists, so no worries there
require_once("server-env.php");        // This makes 'local' work

// From here on they can all be local
include("ip.php");
require_once("theend.php");

require_once("funcs.php");

global $action;

$uri = urldecode($_SERVER['REQUEST_URI']);
$action = substr($uri,strpos($uri,'?')+1);
$p = strpos($action, '&');
if($p > 0) 
    $action = substr($action,0,$p);

$action = preg_replace("/[^a-z0-9_]/", "", strtolower($action));

$legal_actions = array('login', 'signup', 'guide', 'top10', 'credits', 'forums', 'source', 'profiles', 'rnews', 'features', 'hfame', 'license');

if(!stristr($config[sitedir], $_SERVER['HTTP_HOST'])) {
    Header("Location: $config[sitedir]");
    exit;
}


if($config['pconnect'])
    $db_link = @mysqli_connect("p:$dbhost",$dbuser,$dbpass);
else
    $db_link = @mysqli_connect($dbhost,$dbuser,$dbpass);

if (mysqli_connect_error()) {
    include("html.php");
    HTMLbegincompact("Database Error!");
    print "The game database is currently unavailable. Please try again later.\n";
    HTMLendcompact();
    exit;
}

mysqli_select_db($GLOBALS["db_link"], $dbname);
require_once("sql-setup.php");

if ($action == "game")
    $action = "main";
if(empty($action))
    $action = "login";

$file = $action . ".php";

if(!is_file($file) || $file == 'login.php') {
    $action = isset($_POST['the_action']) ? $_POST['the_action'] : '';
    $file2 = $action.'.php';
    if(!is_file($file2))
        $file = "login.php";
    else
        $file = $file2;
}

$action = substr($file, 0, -4);
$GAME_ACTION = $action;

include("lib/ranks.php");
include("lib/raffle.php");
include("lib/maintenance.php");

include($file);

ob_end_flush();
?>

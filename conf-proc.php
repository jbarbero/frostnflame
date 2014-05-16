<?php
function automatic_server_url() {
	return 
		(isset($_SERVER['HTTPS']) ? "https" : "http") . "://" .
		$_SERVER['HTTP_HOST'] .
		substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
}


function next_month($day=1) {
	$newdate = (floor((strtotime("next month") / (24*3600))) - date('j') + 1) * 24 * 3600;
	return date('r', $newdate);
}


function arraycopy_r(&$src, &$dest) {
	foreach($src as $key => $val) {
		if(is_array($val)) {
			$dest[$key] = array();
			arraycopy_r($val, $dest[$key]);
		} else {
			$dest[$key] = $val;
		}
	}
}


$global_config = array();
$perserver_config[] = array();
$last_perserver = -1;


function end_global_config() {
	global $global_config, $config;

	$global_config = $config;
	$config = array();
}


function server_specific_config($id) {
	global $perserver_config, $config, $last_perserver, $global_config;

	if($last_perserver != -1)
		arraycopy_r($config, $perserver_config[$last_perserver]);

	$last_perserver = $id;
	$config = array();

	if(!isset($perserver_config[$id])) {
		$perserver_config[$id] = array();
		arraycopy_r($global_config, $perserver_config[$id]);
	}

	$config = $perserver_config[$id];
}


$config = array();
if(file_exists("local/config.php"))
	include("local/config.php");
else
	include("config.php");

server_specific_config(-1);

if (!defined('SERVER')) {
	if (isset($_GET['srv'])) {
		$s = $_GET['srv'];
		define('SERVER', $s);
	} else if (isset($_GET['auth'])) {
		$au = explode("\n", @base64_decode($_GET['auth']));
		$s = $au[3];
		define('SERVER', $s);
	} else {
		foreach($config['servers'] as $id => $name)
			break;
		if(isset($id))
			define('SERVER', $id);
		else
		#	die("No servers defined!");
			define('SERVER', '');
	}
} 
$server = SERVER;

$config = array();
if(isset($perserver_config[SERVER]))
	arraycopy_r($perserver_config[SERVER], $config);

if(empty($config['game_factor']))
	$config['game_factor'] = 1;

$dateformat = $config['dateformat'];
$tpldir = $config['tpldir'];

$maxtickets = $config['maxtickets'];
$tick_curjp = 0;
$tick_lastjp = 1;
$tick_lastnum = 2;
$tick_lastwin = 3;
$tick_jpgrow = 4;

$atknames = $config['atknames'];
$config['wpl'] = 100;
$config['dual_game'] = 0;
$config['main'] = '';
$config['chat_url'] = 'http://'.$config['chathost'].':'.$config['chatport'].'/';

$config['food'] = $config['food_sell'];
$config['runes'] = $config['runes_sell'];

if(!isset($config['nolimit_mode']))
	$config['nolimit_mode'] = 0;
if(!isset($config['nolimit_table']))
	$config['nolimit_table'] = 0;

$nolimitdb = $config['nolimit_table'];

$gamename = $config['gamename_short'];
$gamename_full = $config['gamename_full'];
$config['gamename'] = $gamename;

$signupsclosed = $config['signupsclosed'];
$lockdb = $config['lockdb'];
$lastweek = $config['lastweek'];


if (($config['autolastweek'] == 1) && (strtotime($config['roundend']) != -1) && ((strtotime($config['roundend']) - time()) < 604800))
	$lastweek = 1;

$turnsper = $config['turnsper'];
if($config['perminutes'] == 0) {
	$perminutes = 10;		// For maintenance stuff
	define('PERMINUTES', 0);
} else {
	$perminutes = $config['perminutes'];
	define('PERMINUTES', $config['perminutes']);
}
$turnoffset = $config['turnoffset'];

$dbhost = $config['dbhost'];
$dbuser = $config['dbuser'];
$dbpass = $config['dbpass'];
$dbname = $config['dbname'];
$version = $config['version'];

$servers = $config['servers'];
$prefixes = $config['prefixes'];

$config['servname'] = $servers[SERVER];
$prefix = $prefixes[SERVER];  
if($prefix == "" && SERVER != "")
	die("Invalid server prefix!" . SERVER . "!");

$time = time();
$datetime = date($dateformat);               

$racep = array('rname', 'offense', 'defense', 'bpt', 'costs', 'magic', 'ind', 'pci', 'expl', 'mkt', 'food', 'runes', 'farms');
$erap = array('ename', 'peasants', 'nfood', 'nrunes', 'wizards', 'homes', 'shops', 'industry', 'barracks', 'labs', 'nfarms', 'towers', 'empire');
foreach($config['troop'] as $num => $mktcost) {
	$racep[] = "troop$num";
	$erap[] = "o_troop$num";
	$erap[] = "d_troop$num";
}

$last_pair_global = array();
$etags = array();
$rtags = array();

for($r=1; $r<=$config['races']; $r++) {
	for($e=1; $e<=$config['eras']; $e++) {
		$num = 100*$e + $r;
		if(!isset($config['er'][$num]))
			$config['er'][$num] = array();

		for($i=0; $i<2; $i++) {
			if($i == 0) {
				$const = 'race';
				$id = $r;
				$iter = $racep;
			} else {
				$const = 'era';
				$id = $e;
				$iter = $erap;
			}

			foreach($iter as $attr) {
				if(!empty($config['er'][$num][$attr])) {
					$last_pair[$const][$id][$attr] = $config['er'][$num][$attr];
					$last_pair_global[$const][$attr] = $config['er'][$num][$attr];
				} else if(!empty($last_pair[$const][$id][$attr])) {
					$config['er'][$num][$attr] = $last_pair[$const][$id][$attr];
					$last_pair_global[$const][$attr] = $last_pair[$const][$id][$attr];
				} else if(!empty($last_pair_global[$const][$attr])) {
					$config['er'][$num][$attr] = $last_pair_global[$const][$attr];
					$last_pair[$const][$id][$attr] = $last_pair_global[$const][$attr];
				}
			}

			if($i == 0)
				$rtags[$r] = $config['er'][$num]['rname'];
			else
				$etags[$e] = $config['er'][$num]['ename'];
		}
	}
}

$styles = array();
$stylenames = array();
$adminstyles = array();
$templates = array();
$styleCount = 1;
$defaultTpl = 'prom';

foreach($config['styles'] as $num => $data) {
	global $styles, $stylenames, $templates, $styleCount, $defaultTpl;
	$styleCount = $num;
	$styles[$styleCount] = $data['file'];
	$stylenames[$styleCount] = $data['name'];
	if(isset($data['admin']) && $data['admin'] == 1)
		$adminstyles[$styleCount] = 1;
	if(!empty($data['dir']))
		$templates[$styleCount] = $data['dir'];
	else
		$templates[$styleCount] = $defaultTpl;
	$styleCount += 1;
}


//get-post-server (we choose to order our globals....)
foreach ($_GET as $var => $value) { $$var = $value; }
foreach ($_POST as $var => $value) { $$var = $value; }
foreach ($_SERVER as $var => $value) { $$var = $value; }


require_once('constants.php');


$tbl = array();
$tbl['bounty']		=	'bounties';
$tbl['clan']		=	'clan';
$tbl['clanmarket']	=	'clanmarket';
$tbl['era']		=	'eras';
$tbl['race']		=	'races';
$tbl['lottery']		=	'lottery';
$tbl['market']		=	'market';
$tbl['message']		=	'messages';
$tbl['news']		=	'news2';
$tbl['player']		=	'players';
$tbl['stock']		=	'stockmarket';
$tbl['aim']		=	'aim_notls';
$tbl['script']		=	'code';
$tbl['cron']		= 	'cron';
$tbl['debug']		= 	'debuglog';

foreach($tbl as $var => $value) {
	$name = $var.'db';
	global $$name;
	$$name = $prefix.'_'.$value;
}

$cookie = array();
foreach($_COOKIE as $var => $value) {
	$len = strlen($prefix) + 1;
	if(substr($var, 0, $len) == $prefix.'_') {
		$name = substr($var, $len);
		$cookie[$name] = $value;
	}
}

//$config['chatdomain'] = $_SERVER['SERVER_NAME'];
//$config['chathost'] = $_SERVER['SERVER_NAME'];
?>

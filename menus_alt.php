<?php
if (!defined("PROMISANCE"))
	die(" ");

global $GAME_ACTION, $config, $servers, $authstr, $menus;
$menus = array();

function menu_normal_item($item, $link, $sc = SERVER, $target = false) {
	global $menus;
	$srv = '&amp;srv=' . $sc;
	if(!empty($_GET['skin'])) {
		fixInputNum($_GET['skin']);
		$srv .= '&skin=' . $_GET['skin'];
	}
	if ($target)
		$trg = " target=\"$target\"";
	$item = str_replace(" ", "&nbsp;", $item);
	$menus[] = array(	'link' => "?$link$srv",
				'trg'  => $trg,
				'item' => $item,
				'spec' => 0);
} 

function menu_header_item($item, $sc = SERVER) {
	global $menus;
	if($sc == -1) {
		$srv = '';
		$link = 'login';
	} else {
		$srv = '&amp;srv=' . $sc;
		$link = 'info';
	}

	if(!empty($_GET['skin'])) {
		fixInputNum($_GET['skin']);
		$srv .= '&skin=' . $_GET['skin'];
	}
	$item = str_replace(" ", "&nbsp;", $item);
	$menus[] = array(	'link' => "?$link$srv",
				'item' => $item,
				'spec' => 1);
}


menu_header_item('General');
menu_normal_item('<b>Game Guide</b>', "guide2&amp;section=$GAME_ACTION");
menu_normal_item('<b>Forums</b>', 'forums', SERVER, 'forums');
menu_normal_item('Player Rules', 'guide2&amp;section=Game+Rules');
#menu_normal_item('Hall Of Fame', 'hfame', SERVER);
menu_normal_item('Features', 'features');
menu_normal_item('Credits', 'credits');
menu_normal_item('Source Code', "source&amp;script=$GAME_ACTION");
menu_normal_item('Download', "source&amp;bundle=tgz");
#menu_header_item('Affiliates');
#menu_normal_item('Medieval&nbsp;Empires', '?redirect&amp;url=http://www.medievalempires.org/');

foreach($servers as $sc => $name) {
	menu_header_item($name, $sc);
	menu_normal_item('Sign Up', 'signup', $sc);
	menu_normal_item('Top 100', 'top10', $sc);
	menu_normal_item('Recent News', 'rnews', $sc);
	menu_normal_item('Server Map', 'map', $sc);
} 

?>

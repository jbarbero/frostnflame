<?php
if (!defined("PROMISANCE"))
    die(" ");
global $menus, $imgsrc;
global $authstr, $users, $GAME_ACTION;
if (!empty($_GET['menu'])) {
    $new = 0;
    if ($users['menu_lite'] == 0)
        $new = 1;
    $users['menu_lite'] = $new;
    saveUserData($users, "menu_lite");
} 

$imgsrc = 'img/';
if ($users['menu_lite'])
    $imgsrc .= 'expand.gif';
else
    $imgsrc .= 'collapse.gif';

$menus = array();

function menu_normal_item($item, $link, $show, $target = false) {
    global $config, $authstr, $users, $menus;
    if(in_array($link, $config['disabled_pages'])) 
        return;
    if ($link == false)
        $link = strtolower($item);
    if ($target)
        $trg = " target=\"$target\"";
    $item = str_replace(" ", "&nbsp;", $item);
    if ($users['menu_lite'] == 0 || $show == 1) {
        $menus[] = array(    'link' => "?$link$authstr",
                    'trg'  => $trg,
                    'item' => $item,
                    'spec' => 0);
    }
} 

function menu_header_item($item, $sqlname, $show) {
    global $config, $users, $GAME_ACTION, $authstr, $menus;
    $action = $GAME_ACTION;
    if(in_array($sqlname, $config['disabled_pages'])) 
        return;
    $item = str_replace(" ", "&nbsp;", $item);
    if ($users['menu_lite'] == 0 || $show == 1) {
        $menus[] = array(    'item' => $item,
                    'spec' => 1);
    }
}

menu_header_item('Information', 'info', 1);
menu_normal_item('Forums', 'forums', 0);
menu_normal_item('Server News', 'motd', 0);
#menu_normal_item('Shout Box', 'shout', 1);
menu_normal_item('Status', 'status', 1);
menu_normal_item('Scores', 'scores', 1);
menu_normal_item('The Map', 'map', 0);
#menu_normal_item('Map Beta', 'map2', 0);
menu_normal_item('Search', 'search', 0);
menu_normal_item('Profiles', 'profiles', 0);
menu_normal_item('News', 'news', 1);
menu_normal_item('Contacts', 'contacts', 1);
menu_normal_item('Clans', 'clancrier', 0);
menu_header_item('Turns', 'turns', 1);
menu_normal_item('Scout', 'land', 1);
#menu_normal_item('Trade', 'cash', 1);
#menu_normal_item('Farm', 'farm', 1);
#menu_normal_item('Train', 'industry', 1);
#menu_normal_item('Write', 'runes', 1);
menu_normal_item('Produce', 'produce', 1);
menu_normal_item('Heal', 'heal', 1);
menu_normal_item('Build', 'build', 1);
menu_normal_item('Demolish', 'demolish', 0);
menu_header_item('Finances', 'finances', 1);
menu_normal_item('Bazaar', 'pvtmarketbuy', 1);
menu_normal_item('Market', 'pubmarketbuy', 1);
#menu_normal_item('YeBay', 'yebay', 1);
menu_normal_item('Stocks', 'stocks', 1);
menu_normal_item('Bank', 'bank', 1);
menu_normal_item('Turn Bank', 'turnbank', 1);
menu_normal_item('Raffle', 'raffle', 0);
menu_header_item('Diplomacy', 'diplomacy', 1);
menu_normal_item('Attack', 'military', 1);
menu_normal_item('Aid', 'aid', 1);
menu_normal_item('Reserve', 'troopreserve', 1);
menu_normal_item('Bounties', 'mercenary', 1);
menu_normal_item('Intelligence', 'intel', 1);
menu_normal_item($uera[wizards], 'magic', 1);
menu_normal_item('Heroes', 'hero', 0);
menu_header_item('Clans', 'clans', 1);
if ($users[clan] == 0) {
    menu_normal_item('Top Clans', 'clanstats', 0);
    menu_normal_item('Contacts', 'contacts', 0);
    menu_normal_item('Join Clan', 'clanjoin', 1);
    menu_normal_item('Create Clan', 'clan', 1);
} else {
    $uclan = loadClan($users[clan]);
    if (($uclan[founder] == $users[num]) ||
            ($uclan[asst] == $users[num]) ||
            ($uclan[fa1] == $users[num]) ||
            ($uclan[fa2] == $users[num])) {
        menu_normal_item('Manage', 'clanmanage', 1);
    } 

    menu_normal_item('Main', 'clan', 1);
    menu_normal_item('Forum', 'clanforum', 1);
//    menu_normal_item('Chat', 'chat&clan', 0);
    menu_normal_item('Treasury', 'treasury', 1);
    menu_normal_item('Market', 'clanmarketbuy', 0);
    menu_normal_item('Top Clans', 'clanstats', 0);
    menu_normal_item('Contacts', 'contacts', 0);
} 
menu_header_item('Management', 'manage', 1);
//menu_normal_item('Game Chat', 'chat&global', 0);
//menu_normal_item('Server Chat', 'chat', 1);
menu_normal_item('Manage', 'manage', 1);
//menu_normal_item('Commands', 'script', 1);
menu_normal_item('Delete', 'delete', 0);

?>

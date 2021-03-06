<?
require_once("funcs.php");
function EndNow ($reason) {
    HTMLbegincompact("Login");
    print "$reason<br>\n";
    HTMLendcompact();
    exit;
}

if($_POST['type'] == 'global') {
    $reason = "Username or password incorrect!";

    $user = $_POST['user'];
    sqlQuotes($user);
    $pass = $_POST['pass'];

    $global = mysqli_fetch_array(db_safe_query("SELECT * FROM global_users WHERE username='$user';"));
    if(!$global[num])
        EndNow($reason);

    $_COOKIE['global_auth'] = base64_encode($global[num] . "\n" . md5(md5($pass).$global[rsalt]));
    if(!auth_global(true))
        EndNow($reason);

    login_page();

} else if (isset($do_login)) {
    $reason = "Username or password incorrect!";

    if ($login_username == "")
        EndNow("You must enter a username!");
    sqlQuotes($login_username);
    $users = mysqli_fetch_array(db_safe_query("SELECT * FROM $playerdb WHERE username='$login_username';"));
    if (!$users[num])
        EndNow($reason);
    $password = md5($login_password);
    if ($users[password] == $password) {
        $usecookie = false;
        if(!empty($_POST['usecookie']))
            $usecookie = true;

        $users[rsalt] = rand_nonce($users[rsalt]);
        makeAuthCode($users[num], $users[password], 0, SERVER, $usecookie, $users[rsalt]);
        if(!$usecookie)
            setcookie($prefix.'_auth', '');

        if($hideon)
            $users[hide] = 0;
        else
            $users[hide] = 1;
        saveUserData($users, "hide rsalt", true);
        
            if($users['motd'] == 1) {
                header(str_replace('&amp;', '&', "Location: ".$config[sitedir].$config[main]."?motd$authstr"));
            } else {
                header(str_replace('&amp;', '&', "Location: ".$config[sitedir].$config[main]."?game$authstr"));
            }
    } else    EndNow($reason);
} else {
    foreach($config['prefixes'] as $fix)
        setcookie($fix.'_auth', '');

    login_page();
}

function login_page() {
    HTMLbegincompact("Login");

    global $servers, $config, $config_global, $config_server, $prefixes, $dateformat, $global, $disp_servers, $login_news;
    $disp_servers = array();
    foreach($servers as $i => $s) {
        $n = array('num' => $i, 'name' => str_replace(' ', '&nbsp;', $s));

        $server = $i;

        # TODO: rewrite this with a php 4 compatible version
        $config_i = array();
        $config_i = $config_global;
        if(isset($config_server[$server]))
            $config_i = array_replace_recursive($config_global, $config_server[$server]);

        $playerdb = $prefixes[$i] . '_players';

        $n['news'] = $config_i['news'];
        $n['signupsclosed'] = $config_i['signupsclosed'];
        $n['perminutes'] = $config_i['perminutes'];
        $n['turnsper'] = $config_i['turnsper'];
        $n['lockdb'] = $config_i['lockdb'];
        $n['maxturns'] = $config_i['maxturns'];
        $n['lastweek'] = $config_i['lastweek'];
        $n['maxstoredturns'] = $config_i['maxstoredturns'];
        $n['protection'] = $config_i['protection'];

        $active = "land>0 AND disabled<2";
        $numplayers = db_safe_firstval("SELECT COUNT(*) FROM $playerdb WHERE $active;");
        $totalnet = db_safe_firstval("SELECT SUM(networth) FROM $playerdb WHERE $active;");
        if($totalnet != 0)
            $avgnet = round($totalnet / $numplayers);
        else
            $avgnet = 0;

#        $endtime = strtotime(preg_replace('/[^a-zA-Z0-9\- \t]/', '', $config_i['roundend']));
        $endtime = strtotime($config_i['roundend']);
        
        if($endtime == -1)
            $enddisp = $config_i['roundend'];
        else
            $enddisp = date($dateformat, $endtime);

        $n['etime'] = $endtime;
        $n['roundend'] = $enddisp;
//        $n['config_i'] = $config_i;
        $n['numplayers'] = $numplayers;
        $n['avgnet'] = commas($avgnet);
        $n['gameinfo'] = $gameinfo;

        if(isset($global[num])) {
            fixInputNum($global[num]);
            $rec = db_safe_query("SELECT * FROM $playerdb WHERE disabled!=4 AND global_num=$global[num];");
            $user = mysqli_fetch_array($rec);
            if(isset($user[num]))
                $n['empire'] = $user[empire];
            else
                $n['empire'] = "";
        }

        $disp_servers[] = $n;
    }


    $login_news = doForumNews();

    template_display('login2.html');
    HTMLendcompact();
}

?>

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

        if($config['hiddenlogin']) {
            if($nothideon)
                $users[hide] = 0;
            else
                $users[hide] = 1;
        }
        
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

    global $servers, $config, $perserver_config, $tpl, $disp_servers, $login_news;

    $disp_servers = array();
    foreach($servers as $i => $s) {
        $n = array('num' => $i, 'name' => str_replace(' ', '&nbsp;', $s));

        $server = $i;

        $n['hiddenlogin'] = $perserver_config[$i]['hiddenlogin'];

        $disp_servers[] = $n;
        }

    $login_news = doForumNews();

    template_display('login.html');
    HTMLendcompact();
}

?>

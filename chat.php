<?php
#error_log("arrival: $_SERVER[REQUEST_URI]\n", 3, "proxylog.txt");
auth_user();
$authcode = base64_encode("$users[num]\n$users[password]\n\n".SERVER);
$authstr = '&auth='.$authcode;

$s = SERVER;
if(isset($_GET['global']))
    $s = 0;

if(isset($_GET['clan']))
    $clan = $users[clan];
else
    $clan = 0;


//print_r($config);

if($config['proxy_chat']) {
    $srvurl = $config['sitedir'] . '?chat&mode=proxy&connect=1&srv=' . $s . '&psrv=' . SERVER . '&clan=' . $clan . $authstr;
    $posturl1 = $config['sitedir'] . '?chat&mode=proxy';
} else {
    $srvurl = 'http://' . $config['chathost'] . ':' . $config['chatport'] . '/?connect=1&srv=' . $s . '&psrv=' . SERVER . '&clan=' . $clan . $authstr;
    $posturl1 = 'http://' . $config['chathost'] . ':' . $config['chatport'] . '/?dummy';
}

$posturl2 = '&srv=' . $s . '&psrv=' . SERVER . '&clan=' . $clan . $authstr . '&';
#$initialurl = $config['sitedir'] . "?chat&mode=redir$authstr";
$initialurl = $srvurl;


$chatname = '';
if($s != 0)
    $chatname = $servers[$s];
else
    $chatname = $gamename;
if($s != 0 && $clan != 0) {
    $uclan = loadClan($users[clan]);
    $chatname .= " :: " . $uclan[name];
}


switch($_GET['mode']) {
    case 'gui':
    default:
        include("header.php");
        $srv = $s;

        template_display('chat.html');
        TheEnd('');

    case 'runsrv':
        auth_user();
        ob_flush();
        ignore_user_abort(TRUE);

        include("lib/chat.php");
        $daemon = new ChatDaemon($config['chathost'], $config['chatport']);
        //set options
        $daemon->start();
        break;

    case 'redir':
        error_log("rediring: $_SERVER[REQUEST_URI]\n", 3, "proxylog.txt");
        auth_user();
        ob_flush();

        if(@socket_bind($sock, $config['chathost'], $config['chatport'])) {
                        @socket_close($sock);

            $path = "$config[sitedir]?chat&mode=runsrv$authstr";
            $path = substr($path, strpos($path, '?')-1);
            $sock = @fsockopen($_SERVER['HTTP_HOST'], $_SERVER['SERVER_PORT'], $errno, $errstr);
            if($sock) {
                fwrite($sock, "GET $path HTTP/1.1\r\n");
                fwrite($sock, "Host: {$_SERVER['HTTP_HOST']}\r\n\r\n");
            }

            fclose($sock);
        } else {
            @socket_close($sock);
        }

        $srvurl = str_replace('&amp;', '&', $srvurl);

        echo "<html><body><script>function redir() { location.href = \"$srvurl\"; }\nwindow.onload = redir;\n";
        echo "</script></body></html>";
        error_log("doneredir: $_SERVER[REQUEST_URI]\n", 3, "proxylog.txt");
        ob_end_flush();
        break;

    case 'proxy':
        error_log("request: $_SERVER[REQUEST_URI]\n", 3, "proxylog.txt");
        auth_user();
        ob_flush();
        ignore_user_abort(FALSE);

        function shutdown_chat() {
            global $sock;
            error_log("closing!: $_SERVER[REQUEST_URI]\n", 3, "proxylog.txt");
            @fclose($sock);
        }
        register_shutdown_function('shutdown_chat');

        $url = $_SERVER['REQUEST_URI'];
        $url = '/?'.substr($url, strpos($url, "proxy")+6);

        error_log("proxy: $url\n", 3, "proxylog.txt");

        $sock = @fsockopen($config['chathost'], $config['chatport'], $errno, $errstr, 5);
        if(!$sock) {
            die("<span class=\"mwarn\">Connection failed!</span>");
        }

        fwrite($sock, "GET $url HTTP/1.1\r\n");
        fwrite($sock, "Host: {$_SERVER['HTTP_HOST']}\r\n\r\n");

        $body = false;

        while(!feof($sock)) {
            $line = fgets($sock);
            echo "<b>DebugAdmin:</b> Got ".trim($line)."\n<br>";

            if($line == "\r\n") {
                $body = true;
                continue;
            }

            if(!$body)
                continue;

            $len = hexdec(trim($line));
            if($len == 0) {
                echo $line;
                continue;
            }

            $data = fread($sock, $len);

            echo $data;
            flush(); ob_flush();
        }

        fclose($sock);
        break;
}
?>

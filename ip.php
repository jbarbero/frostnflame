<?

function realip()
{
   // No IP found (will be overwritten by for
   // if any IP is found behind a firewall)
   $ip = FALSE;

   // If HTTP_CLIENT_IP is set, then give it priority
   if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
       $ip = $_SERVER["HTTP_CLIENT_IP"];
   }

   // User is behind a proxy and check that we discard RFC1918 IP addresses
   // if they are behind a proxy then only figure out which IP belongs to the
   // user.  Might not need any more hackin if there is a squid reverse proxy
   // infront of apache.
   if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

       // Put the IP's into an array which we shall work with shortly.
       $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
       if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }

       for ($i = 0; $i < count($ips); $i++) {
           // Skip RFC 1918 IP's 10.0.0.0/8, 172.16.0.0/12 and
           // 192.168.0.0/16 -- jim kill me later with my regexp pattern
           // below.
           if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
               $ip = $ips[$i];
               break;
           }
       }
   }

   // Return with the found IP or the remote address
   return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

?>

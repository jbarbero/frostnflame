<?PHP
/* Clan.php
Handles some clan related functions (currently startup)
*/

function mkclan()
{
global $clandb, $prefix, $lockdb, $create_name, $create_tag, $create_pass, $create_flag, $create_url, $create_founder, $time;
if ($lockdb)
            TheEnd("Cannot create a clan until the game begins!");
        if (($create_name == "") || ($create_tag == ""))
            TheEnd("You must enter a clan name and tag!");
        if (trim($create_tag) == "None")
            TheEnd("Illegal clan tag!");
        $chk_t = $create_tag;
        sqlQuotes($chk_t);
        if (db_safe_firstval("SELECT COUNT(*) FROM $clandb WHERE tag='$chk_t';"))
            TheEnd("That clan tag has already been used in this game!");

        fixInputNum($create_founder);
        db_safe_query("INSERT INTO $clandb (founder) VALUES ($create_founder);");
        $num = mysqli_insert_id($db_link);
        $uclan = loadClan($num);

        $user =  loadUser($create_founder);
        $user[clan] = $uclan[$num];
        $user[forces] = 0;
        $user[allytime] = $time;
        saveUserData($user, "clan forces allytime");

        $uclan[founder] = $create_founder;
        $uclan[members] = 1;
        $uclan[name] = HTMLEntities(swear_filter(trim($create_name)));
        $uclan[tag] = HTMLEntities(swear_filter(trim($create_tag)));
        $uclan[password] = md5($create_pass);
        $uclan[pic] = $create_flag;
        $uclan[url] = $create_url;
        $uclan[motd] = swear_filter("Welcome to $create_name!");
        saveClanData($uclan,"founder name tag password pic url motd members");
        $ufound = loadUser($create_founder);
        $ufound[clan] = $uclan[num];
        $ufound[allytime] = $time;
        saveUserData($ufound,"clan allytime");
        addNews(110, array(id1=>$create_founder, clan1=>$num));
        $clname = mysqli_real_escape_string($db_link, $uclan[name]);
        db_safe_query("INSERT INTO $prefix"."_forums ( `forum_id` , `forum_name` , `forum_desc` , `forum_order` , `forum_icon` , `topics_count`, `posts_count`) 
            VALUES ('$num', '$clname Forums', 'Clan Private Forums', '0', 'default.gif', '0', '0');");
        echo mysqli_error($db_link);
        TheEnd("$uclan[name] has been created successfully!");
        }
?>

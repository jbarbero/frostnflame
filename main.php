<?
include("header.php");
global $config;
if ($mark_news) {
    $users[newstime] = $time;
    saveUserData($users,"newstime");
        header(str_replace('&amp;', '&', "Location: ?main$authstr"));
        die();
}

if($do_notes) {
    $users['notes'] = $upd_notes;
    saveUserData($users, "notes", true);
    echo "<span class='cgood'>Notepad updated!</span><hr>";
}

if($do_nsort_rev) {
    if($users[newssort] == 0)
        $users[newssort] = 1;
    else
        $users[newssort] = 0;
    saveUserData($users, "newssort");
}

printMainStats($users,$urace,$uera);

if($admin) {
    echo '<table class="inputtable" style="width:50%">
    <tr><td colspan="4"><table class="inputtable"><form method="post" action="'.$config['main'].'?military'.$authstr.'">
    <tr><th>Notepad</th></tr><tr><td><textarea rows="8" cols="78" name="upd_notes">'.$users['notes'].'</textarea>
    </td></tr><tr><th><input type="submit" value="Update Notes" name="do_notes"></th></tr>
    </form></table></td></tr></table>';
}

if ($users['turnsused'] <= $config['protection']) {
?>
<span class="mprotected">Under New Player Protection (<?=$config[protection]?> turns)</span><br>
<?
}
$nextturn = $perminutes - ((date("i") + $turnoffset) % $perminutes);
?>
<b><?=$datetime?></b><br>
You get <?=$turnsper?> <?=plural($turnsper,turns,turn)?> every <?=$perminutes?> <?=plural($perminutes,minutes,minute)?><br>
<?
if ($lockdb)
    print "Turns are currently stopped.<br><br>\n";
else    print "Next ".plural($turnsper,turns,turn)." in $nextturn ".plural($nextturn,minutes,minute)."<br><br>\n";
if ($users[clan])
{
    $uclan = loadClan($users[clan]);

    $latest_topics = db_safe_query("SELECT topic_poster, topic_poster_name, topic_id, topic_time, topic_title, topic_last_post_id FROM $prefix"."_topics WHERE forum_id=$uclan[num] ORDER BY topic_last_post_id DESC LIMIT 0,5;");
    if(mysqli_num_rows($latest_topics)) {
        echo '<table class="inputtable" style="width:50%">';
        echo '<tr><td colspan="4"><hr></td></tr>';
        echo '<tr><th colspan="4">Latest Clan Topics:</th></tr>';
        echo '<tr><th>Date</th>';
        echo '<th>Title</th>';
        echo '<th>Author</th>';
        echo '<th>Last Poster</th></tr>';

        $oldid = 0;
        while($topic = mysqli_fetch_array($latest_topics)) {
            if($topic[topic_id] == $oldid)
                continue;
            $oldid = $topic[id];

            $last_poster = db_safe_firstval("SELECT poster_name FROM $prefix"."_posts WHERE post_id=$topic[topic_last_post_id];");
            $poster_id = db_safe_firstval("SELECT poster_id FROM $prefix"."_posts WHERE post_id=$topic[topic_last_post_id];");
            $date = date($dateformat, strtotime($topic[topic_time]));
            echo '<tr><td>'.$date.'</td>';
            echo '<td><a href="?clanforum&'.$authstr.'&action=vthread&forum=36&topic='.$topic[topic_id].'">'.$topic[topic_title].'</a></td>';
            echo '<td>'.$topic[topic_poster_name].' <a class=proflink href=?profiles&num='.$topic[topic_poster].$authstr.'>(#'.$topic[topic_poster].')</a></td>';
            echo '<td>'.$last_poster.' <a class=proflink href=?profiles&num='.$poster_id.$authstr.'>(#'.$poster_id.')</a></td></tr>';
        }

        echo '</table>';
    }
    else {
        echo '<table class="inputtable" style="width:50%">';
        echo '<tr><td colspan="4"><hr></td></tr>';
        echo '<tr><th colspan="4">Latest Clan Topics:</th></tr>';
        echo '<tr><td colspan="4">No topics in clan forum! <a href="?clanforum'.$authstr.'">Post New Topic</a></td></tr>';
        echo '</table>';
    }
            

    if ($uclan[motd])
    {
?>
<table class="inputtable" style="width:50%">
<tr><td><hr></td></tr>
<tr><th>Clan News:</th></tr>
<tr><td><tt><?=bbcode_parse($uclan[motd])?></tt></td></tr>
<tr><td><hr></td></tr>
</table>
<?
    }
}
$newmsgs = numNewMessages();
$oldmsgs = numTotalMessages() - $newmsgs;
print "<a href=\"$config[main]?messages$authstr\"><b>";
if ($newmsgs + $oldmsgs)
    print "You have $newmsgs new ".plural($newmsgs,messages,message)." and $oldmsgs old ".plural($oldmsgs,messages,message);
else    print "Send a message";
print "</b></a><br>\n";

if($do_nsort_rev)
    print "News Sorting Reversed! &middot; ";
print "<a href=?main&do_nsort_rev=1$authstr>Reverse News Sorting</a><br>\n";

if ($all_news)
    $users[newstime] = $time - 86400*7;            // show all news under 1wk old

if($all_history)
    $users[newstime] = 0;
$hasnews = printNews($users);
if ($all_news)
{
    if (!$hasnews)
        print "<b>No archived news</b><br>\n";
}
else
{
    if ($hasnews)
        print "<a href=\"$config[main]?main&amp;mark_news=yes$authstr\">Mark News as Read</a><br>\n";
    else    print "<b>No new happenings</b><br>\n";
    print "<a href=\"$config[main]?main&amp;all_news=yes$authstr\">View News Archive (7 days)</a><br>\n";
    print "<a href=\"$config[main]?main&amp;all_history=yes$authstr\">View Complete History</a><br>\n";
}
TheEnd("");
?>

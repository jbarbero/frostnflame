<?php
if(!defined("PROMISANCE"))
	die(" ");
function intForumNews() {
        global $config, $dateformat, $dbhost, $dbuser, $dbpass, $dbname;


	if (!$link = mysql_pconnect($dbhost,$dbuser,$dbpass)) {
		return;
	}

	@mysql_select_db($dbname);

        $rets = array();
        $pr = $config['news_prefix'];
        $id = $config['news_forum'];
        $lim = $config['news_length'];
        $topics = @db_safe_query("SELECT tid,title,description,posts FROM ".$pr."_topics WHERE forum_id=$id ORDER BY start_date DESC LIMIT 0,$lim;");

	$link = $config[forums] . 'index.php?showtopic=';
	$member_link = $config[forums] . 'index.php?act=Profile&amp;CODE=03&amp;MID=';

        while($topic = @mysqli_fetch_array($topics)) {
                $id = $topic['tid'];
                $title = $topic['title'];
//		$comments = $topic['posts'] - 1;
		$comments = $topic['posts'];
		$ctext = 'No Comments';
		if($comments == 1)
			$ctext = '1 comment';
		else if($comments > 1)
			$ctext = $comments.' comments';
                $post = @mysqli_fetch_array(@db_safe_query("SELECT author_id,author_name,post_date,post FROM ".$pr."_posts WHERE topic_id=$id ORDER BY post_date ASC LIMIT 0,1;"));
                $body = $post['post'];
//		$body = str_replace('&', '&amp;', $body);
//		$body = str_replace('&amp;amp;', '&amp;', $body);
                $time = $post['post_date'];
                $author = str_replace(' ', '&nbsp;', $post['author_name']);
		$mid = $post['author_id'];
		$desc = $topic['description'];
		$descempty = 0;
		if(strlen(trim($desc)) == 0)
			$descempty = 1;
                $ret = array('title'=>$title, 'comments'=>$comments, 'desc'=>$desc, 'ctext'=>$ctext, 'post'=>$body, 'author'=>$author, 'time'=>str_replace(' ', '&nbsp;', date($dateformat, $time)), 'link'=>($link.$id), 'mlink'=>($member_link.$mid), 'descempty' => $descempty);
                $rets[] = $ret;
        }

        return $rets;
}


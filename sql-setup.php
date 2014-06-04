<?  // new sql-setup.php

db_safe_query("CREATE TABLE IF NOT EXISTS `global_users` (
  `username` tinytext NOT NULL,
  `password` varchar(32) NOT NULL default '',
  `passchanged` tinyint(3) unsigned NOT NULL default '0',
  `rsalt` int(11) NOT NULL default '0',
  `name` tinytext NOT NULL,
  `email` tinytext NOT NULL,
  `IP` tinytext NOT NULL,
  `signedup` int(11) NOT NULL default '0',
  `disabled` tinyint(3) unsigned NOT NULL default '0',
  `style` tinyint(3) unsigned NOT NULL default '1',
  `empire` tinytext NOT NULL,
  `num` mediumint(8) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`num`)
) AUTO_INCREMENT=1 ;");

if($prefix != "" && (mysqli_num_rows(db_safe_query("SHOW TABLES LIKE '$playerdb';")) == 0 || mysqli_num_rows(db_safe_query("SELECT num FROM $playerdb WHERE num=1;")) == 0)) {


db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_banned` (
  `id` int(10) NOT NULL auto_increment,
  `banip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$bountydb` (
  `cash` bigint(20) NOT NULL default '0',
  `t_name` tinytext NOT NULL,
  `s_name` tinytext NOT NULL,
  `t_num` mediumint(9) NOT NULL default '0',
  `s_num` mediumint(9) NOT NULL default '0',
  `filled` tinyint(4) NOT NULL default '0',
  `num` mediumint(8) unsigned NOT NULL auto_increment,
  `check` bigint(20) NOT NULL default '0',
  `land` int(11) NOT NULL default '0',
  `rank` mediumint(9) NOT NULL default '0',
  `net` bigint(20) NOT NULL default '0',
  `all_1` int(11) NOT NULL default '0',
  `food` bigint(20) NOT NULL default '0',
  `rune` bigint(20) NOT NULL default '0',
  `time` bigint(20) NOT NULL default '0',
  `edits` tinyint(4) NOT NULL default '0',
  `troop` varchar(210) NOT NULL default '',
  `anon` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`num`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$clandb` (
  `num` smallint(5) unsigned NOT NULL auto_increment,
  `founder` mediumint(8) unsigned NOT NULL default '0',
  `asst` mediumint(8) unsigned NOT NULL default '0',
  `fa1` mediumint(8) unsigned NOT NULL default '0',
  `fa2` mediumint(8) unsigned NOT NULL default '0',
  `ally1` smallint(5) unsigned NOT NULL default '0',
  `ally2` smallint(5) unsigned NOT NULL default '0',
  `ally3` smallint(5) unsigned NOT NULL default '0',
  `ally4` smallint(5) NOT NULL default '0',
  `ally5` smallint(5) NOT NULL default '0',
  `war1` smallint(5) unsigned NOT NULL default '0',
  `war2` smallint(5) unsigned NOT NULL default '0',
  `war3` smallint(5) unsigned NOT NULL default '0',
  `war4` smallint(5) NOT NULL default '0',
  `war5` smallint(5) NOT NULL default '0',
  `pic` tinytext NOT NULL,
  `url` tinytext NOT NULL,
  `motd` text NOT NULL,
  `members` smallint(6) NOT NULL default '1',
  `name` tinytext NOT NULL,
  `tag` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `criernews` text NOT NULL,
  `treasury` bigint(20) NOT NULL default '0',
  `granary` bigint(20) NOT NULL default '0',
  `loft` bigint(20) NOT NULL default '0',
  `open` tinyint(1) NOT NULL default '0',
  `tres_open` tinyint(2) NOT NULL default '0',
  `cron_last` int(11) NOT NULL default '0',
  PRIMARY KEY  (`num`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_cron` (
  `name` varchar(64) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`name`)
) ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_debuglog` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `actor` int(11) unsigned default NULL,
  `target` int(11) unsigned default NULL,
  `message` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `date` (`date`),
  KEY `actor` (`actor`),
  KEY `target` (`target`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_forums` (
  `forum_id` int(10) NOT NULL auto_increment,
  `forum_name` varchar(150) NOT NULL default '',
  `forum_desc` text NOT NULL,
  `forum_order` int(10) NOT NULL default '0',
  `forum_icon` varchar(255) NOT NULL default 'default.gif',
  `topics_count` int(10) NOT NULL default '0',
  `posts_count` int(10) NOT NULL default '0',
  PRIMARY KEY  (`forum_id`)
) AUTO_INCREMENT=1 ;");

//server forum
if(mysqli_num_rows(db_safe_query("SELECT * FROM $prefix"."_forums;")) == 0) {
    db_safe_query("INSERT INTO `$prefix"."_forums` VALUES ('-1', '$config[servname] Server Forum', 'Server Forum', '0', 'default.gif', '0', '0');");
}


db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_intel` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `num` int(11) unsigned NOT NULL default '0',
  `spyinfo` text NOT NULL,
  `spytime` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$lotterydb` (
  `num` int(10) unsigned NOT NULL default '0',
  `ticket` int(10) unsigned NOT NULL default '0',
  `amt` bigint(20) unsigned NOT NULL default '0',
  `jtyp` varchar(7) NOT NULL default '',
  KEY `num` (`num`),
  KEY `ticket` (`ticket`)
) ;");

if(mysqli_num_rows(db_safe_query("SELECT * FROM $lotterydb;")) == 0) {
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 0, 0, 'food');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 1, 2771732307, 'food');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 2, 42, 'food');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 3, 159, 'food');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 4, 2581381133, 'food');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 0, 0, 'cash');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 1, 17670770609, 'cash');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 2, 18, 'cash');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 3, 150, 'cash');");
    db_safe_query("INSERT INTO `$lotterydb` VALUES (0, 4, 13110673839, 'cash');");
}
echo mysqli_error($GLOBALS["db_link"]);

db_safe_query("CREATE TABLE IF NOT EXISTS `$marketdb` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` tinytext NOT NULL,
  `seller` mediumint(8) unsigned NOT NULL default '0',
  `amount` bigint(20) unsigned NOT NULL default '0',
  `price` int(10) unsigned NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `clan` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `price` (`price`),
  KEY `time` (`time`),
  FULLTEXT KEY `type` (`type`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$messagedb` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `time` int(11) NOT NULL default '0',
  `src` mediumint(8) unsigned NOT NULL default '0',
  `dest` mediumint(8) unsigned NOT NULL default '0',
  `msg` text NOT NULL,
  `replied` tinyint(3) unsigned NOT NULL default '0',
  `deleted` tinyint(3) unsigned NOT NULL default '0',
  `killed` tinyint(4) NOT NULL default '0',
  `title` tinytext NOT NULL,
  `readd` tinyint(4) NOT NULL default '0',
  `folder` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `dest` (`dest`),
  KEY `time` (`time`),
  KEY `deleted` (`deleted`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_motd` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `author` varchar(60) NOT NULL default '',
  `subject` varchar(128) NOT NULL default '',
  `content` text NOT NULL,
  `date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$newsdb` (
  `id` bigint(9) unsigned NOT NULL auto_increment,
  `time` int(11) NOT NULL default '0',
  `code` smallint(6) NOT NULL default '0',
  `id1` mediumint(9) NOT NULL default '0',
  `clan1` mediumint(9) NOT NULL default '0',
  `land1` bigint(20) NOT NULL default '0',
  `cash1` bigint(20) NOT NULL default '0',
  `troops1` varchar(210) NOT NULL default '',
  `wizards1` int(11) NOT NULL default '0',
  `food1` bigint(20) NOT NULL default '0',
  `runes1` bigint(20) NOT NULL default '0',
  `id2` mediumint(9) NOT NULL default '0',
  `clan2` mediumint(9) NOT NULL default '0',
  `land2` bigint(20) NOT NULL default '0',
  `troops2` varchar(210) NOT NULL default '',
  `wizards2` int(11) NOT NULL default '0',
  `id3` mediumint(9) NOT NULL default '0',
  `clan3` mediumint(9) NOT NULL default '0',
  `shielded` tinyint(2) NOT NULL default '0',
  `online` tinyint(2) NOT NULL default '0',
  `num` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$playerdb` (
  `global_num` int(11) NOT NULL,
  `username` tinytext NOT NULL,
  `password` varchar(32) NOT NULL default '',
  `passchanged` tinyint(3) unsigned NOT NULL default '0',
  `rsalt` int(11) NOT NULL default '0',
  `name` tinytext NOT NULL,
  `email` tinytext NOT NULL,
  `IP` tinytext NOT NULL,
  `signedup` int(11) NOT NULL default '0',
  `ismulti` tinyint(3) unsigned NOT NULL default '0',
  `disabled` tinyint(3) unsigned NOT NULL default '0',
  `online` tinyint(3) unsigned NOT NULL default '0',
  `vacation` smallint(5) unsigned NOT NULL default '0',
  `idle` int(11) NOT NULL default '0',
  `free` int(11) NOT NULL default '0',
  `style` tinyint(3) unsigned NOT NULL default '1',
  `empire` tinytext NOT NULL,
  `num` mediumint(8) unsigned NOT NULL auto_increment,
  `race` tinyint(3) unsigned NOT NULL default '1',
  `era` tinyint(3) unsigned NOT NULL default '1',
  `rank` mediumint(8) unsigned NOT NULL default '0',
  `clan` smallint(5) unsigned NOT NULL default '0',
  `clan_tres` tinyint(3) unsigned NOT NULL default '0',
  `forces` tinyint(3) unsigned NOT NULL default '0',
  `allytime` int(11) NOT NULL default '0',
  `attacks` int(10) unsigned NOT NULL default '0',
  `offsucc` int(10) unsigned NOT NULL default '0',
  `offtotal` int(10) unsigned NOT NULL default '0',
  `defsucc` int(10) unsigned NOT NULL default '0',
  `deftotal` int(10) unsigned NOT NULL default '0',
  `kills` int(10) unsigned NOT NULL default '0',
  `turns` int(10) unsigned NOT NULL default '0',
  `turns_last` int(11) NOT NULL default '0',
  `hour_last` int(11) NOT NULL default '0',
  `turnsstored` int(10) unsigned NOT NULL default '0',
  `turnsused` int(10) unsigned NOT NULL default '0',
  `networth` bigint(20) unsigned NOT NULL default '0',
  `cash` bigint(20) unsigned NOT NULL default '100000',
  `food` bigint(20) unsigned NOT NULL default '10000',
  `peasants` int(10) unsigned NOT NULL default '500',
  `troops` varchar(210) NOT NULL default '',
  `troops_res` varchar(210) NOT NULL default '',
  `health` tinyint(3) unsigned NOT NULL default '100',
  `wizards` int(10) unsigned NOT NULL default '0',
  `runes` int(10) unsigned NOT NULL default '500',
  `shield` int(11) NOT NULL default '0',
  `gate` int(11) NOT NULL default '0',
  `production` varchar(30) NOT NULL default '',
  `land` int(10) unsigned NOT NULL default '250',
  `shops` int(10) unsigned NOT NULL default '5',
  `homes` int(10) unsigned NOT NULL default '20',
  `industry` int(10) unsigned NOT NULL default '0',
  `barracks` int(10) unsigned NOT NULL default '5',
  `labs` int(10) unsigned NOT NULL default '0',
  `farms` int(10) unsigned NOT NULL default '15',
  `towers` int(10) unsigned NOT NULL default '0',
  `freeland` int(10) unsigned NOT NULL default '205',
  `tax` tinyint(3) unsigned NOT NULL default '10',
  `savings` bigint(20) unsigned NOT NULL default '0',
  `turnbank` tinyint(3) unsigned NOT NULL default '0',
  `turnbank_last` int(11) unsigned NOT NULL default '0',
  `loan` bigint(20) unsigned NOT NULL default '0',
  `pvmarket` varchar(210) NOT NULL default '',
  `pvmarket_last` int(11) NOT NULL default '0',
  `pmkt_food` bigint(20) unsigned NOT NULL default '100000',
  `bmper` varchar(60) NOT NULL default '',
  `bmper_last` int(11) NOT NULL default '0',
  `aidcred` tinyint(3) unsigned NOT NULL default '5',
  `aidcred_got` int(11) NOT NULL default '0',
  `msgcred` tinyint(3) unsigned NOT NULL default '10',
  `msgcred_got` int(11) NOT NULL default '0',
  `msgtime` int(11) NOT NULL default '0',
  `newstime` int(11) NOT NULL default '0',
  `notes` text,
  `loggedin` tinyint(2) NOT NULL default '0',
  `hero_war` tinyint(4) NOT NULL default '0',
  `hero_peace` tinyint(4) NOT NULL default '0',
  `hero_special` tinyint(4) NOT NULL default '0',
  `heroes_used` int(11) NOT NULL default '0',
  `warset` int(11) NOT NULL default '0',
  `warset_time` int(11) NOT NULL default '0',
  `warset_known` tinyint(3) NOT NULL default '0',
  `peaceset` int(11) NOT NULL default '0',
  `peaceset_time` int(11) NOT NULL default '0',
  `profile` text NOT NULL,
  `igname` text NOT NULL,
  `aim` mediumtext NOT NULL,
  `msn` mediumtext NOT NULL,
  `stocks` text NOT NULL,
  `l_attack` mediumint(9) NOT NULL default '0',
  `num_bounties` int(11) NOT NULL default '0',
  `hide` tinyint(2) NOT NULL default '0',
  `condense` tinyint(2) NOT NULL default '1',
  `atkforstruct` tinyint(2) NOT NULL default '1',
  `folders` text NOT NULL,
  `menu_lite` tinyint(2) NOT NULL default '0',
  `validated` tinyint(2) NOT NULL default '1',
  `vote` tinyint(4) NOT NULL default '0',
  `newssort` tinyint(2) NOT NULL default '0',
  `std_bld` tinyint(2) NOT NULL default '1',
  `motd` tinyint(1) unsigned NOT NULL default '0',
  `lastdroptime` int(11) unsigned NOT NULL default '0',
  `pubmarket` varchar(210) NOT NULL default '',
  `pubmarket_food` int(10) unsigned NOT NULL default 0,
  `pubmarket_runes` int(10) unsigned NOT NULL default 0,
  PRIMARY KEY  (`num`,`signedup`),
  KEY `networth` (`networth`),
  KEY `rank` (`rank`)
) AUTO_INCREMENT=1 ;");

if(mysqli_num_rows(db_safe_query("SELECT * FROM $playerdb WHERE num=1;")) == 0) {
    db_safe_query("INSERT INTO `$prefix"."_players` (global_num, username, password, passchanged, rsalt, name, email, IP, signedup, disabled, online, idle, empire, num, rank, turns, turns_last, hour_last, troops, troops_res, production, turnbank, turnbank_last, pvmarket, pvmarket_last, pmkt_food, bmper, bmper_last, msgtime, newstime, profile, igname, aim, msn, stocks, folders) VALUES ('0', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '1', '23768', 'Administrative Officer', 'jbarbero+faf@gmail.com', '127.0.0.1', '1204417010', '2', '1', '1204417105', 'Administrator', '1', '1', '150', '1204416600', '1204416000', '0|0|0|0', '', '25|25|25|25', '100', '1204416000', '8333|4167|2083|14', '0', '100000', '0|0|0|0', '0', '1204417010', '1204417010', '', 'Administrator', '', '', '1000|1000|1000|1000|1000|1000|1000|1000', 'Inbox|Sent') ;");
}
echo mysqli_error($GLOBALS["db_link"]);

db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_posts` (
  `post_id` int(11) NOT NULL auto_increment,
  `forum_id` int(10) NOT NULL default '1',
  `topic_id` int(10) NOT NULL default '1',
  `poster_id` int(10) NOT NULL default '0',
  `poster_name` varchar(40) NOT NULL default 'Anonymous',
  `post_text` text NOT NULL,
  `post_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `poster_ip` varchar(15) NOT NULL default '',
  `post_status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `post_id` (`post_id`),
  KEY `forum_id` (`forum_id`),
  KEY `topic_id` (`topic_id`),
  KEY `poster_id` (`poster_id`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_send_mails` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(10) NOT NULL default '1',
  `topic_id` int(10) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$stockdb` (
  `id` int(11) NOT NULL auto_increment,
  `symbol` mediumtext NOT NULL,
  `name` mediumtext NOT NULL,
  `price` int(11) NOT NULL default '0',
  `days1` int(11) NOT NULL default '0',
  `days2` int(11) NOT NULL default '0',
  `days3` int(11) NOT NULL default '0',
  `bender` int(11) NOT NULL default '0',
  `boost` int(11) NOT NULL default '0',
  `total_held` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) AUTO_INCREMENT=11 ;");

if(mysqli_num_rows(db_safe_query("SELECT * FROM $stockdb;")) == 0) {
    db_safe_query("INSERT INTO `$stockdb` VALUES (1, 'TRP', 'Troops', 75391, 106955, 119687, 169893, 1802, 0);");
    db_safe_query("INSERT INTO `$stockdb` VALUES (2, 'AIR', 'Aircraft', 76326, 110685, 123929, 173641, 1860, 0);");
    db_safe_query("INSERT INTO `$stockdb` VALUES (3, 'TNK', 'Tanks', 91006, 115433, 130245, 59135, 1934, 0);");
    db_safe_query("INSERT INTO `$stockdb` VALUES (4, 'NVL', 'Ships', 74335, 64065, 45281, 98711, 1952, 0);");
    db_safe_query("INSERT INTO `$stockdb` VALUES (5, 'AGT', 'Agents', 106844, 181334, 189594, 40536, 1872, 0);");
    db_safe_query("INSERT INTO `$stockdb` VALUES (6, 'LND', 'Land', 49881, 61542, 45782, 93362, 1820, 0);");
    db_safe_query("INSERT INTO `$stockdb` VALUES (7, 'FD', 'Food', 122387, 58297, 35473, 124809, 1878, 0);");
    db_safe_query("INSERT INTO `$stockdb` VALUES (8, 'NRG', 'Energy', 123095, 74644, 76824, 155084, 1842, 0);");
}

//table for score icons
db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_system` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `droppedland` bigint(20) unsigned NOT NULL default '0',
  `topland` int(11) unsigned NOT NULL default '0',
  `topoff` int(11) unsigned NOT NULL default '0',
  `topdef` int(11) unsigned NOT NULL default '0',
  `topkills` int(11) unsigned NOT NULL default '0',
  `topnet1` int(11) unsigned NOT NULL default '0',
  `topnet2` int(11) unsigned NOT NULL default '0',
  `topnet3` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1 ;");

if(mysqli_num_rows(db_safe_query("SELECT * FROM $prefix"."_system;")) == 0) {
    db_safe_query("INSERT INTO `$prefix"."_system` VALUES ('', 0, 0, 0, 0, 0, 0, 0, 0);");
}

db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_topics` (
  `topic_id` int(10) NOT NULL auto_increment,
  `topic_title` varchar(100) NOT NULL default '',
  `topic_poster` int(10) NOT NULL default '0',
  `topic_poster_name` varchar(40) NOT NULL default 'Anonymous',
  `topic_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `topic_views` int(10) NOT NULL default '0',
  `forum_id` int(10) NOT NULL default '1',
  `topic_status` tinyint(1) NOT NULL default '0',
  `topic_last_post_id` int(10) NOT NULL default '1',
  `posts_count` int(10) NOT NULL default '0',
  `sticky` int(1) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`),
  KEY `topic_id` (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `sticky` (`sticky`),
  KEY `topic_last_post_id` (`topic_last_post_id`)
) AUTO_INCREMENT=1 ;");

db_safe_query("CREATE TABLE IF NOT EXISTS `$prefix"."_users` (
  `user_id` int(10) NOT NULL auto_increment,
  `username` varchar(40) NOT NULL default '',
  `user_regdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_password` varchar(32) NOT NULL default '',
  `user_email` varchar(50) NOT NULL default '',
  `user_icq` varchar(50) NOT NULL default '',
  `user_website` varchar(100) NOT NULL default '',
  `user_occ` varchar(100) NOT NULL default '',
  `user_from` varchar(100) NOT NULL default '',
  `user_interest` varchar(150) NOT NULL default '',
  `user_viewemail` tinyint(1) NOT NULL default '0',
  `user_sorttopics` tinyint(1) NOT NULL default '1',
  `user_newpwdkey` varchar(32) NOT NULL default '',
  `user_newpasswd` varchar(32) NOT NULL default '',
  `language` char(3) NOT NULL default '',
  `activity` int(1) NOT NULL default '1',
  `user_custom1` varchar(255) NOT NULL default '',
  `user_custom2` varchar(255) NOT NULL default '',
  `user_custom3` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) AUTO_INCREMENT=1 ;");

echo mysqli_error($GLOBALS["db_link"]);
}
?>

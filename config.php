<?php
/*
* Configuration file
* Contains the database connection information, some general setup infomration
* game setup items like races and 'server' specific settings like turnrates
*
* You will note that some information appears once in the 'global config' 
* section and again in some server specific config sections. The information
* in the server specific overrides the global information. 
*/
$config['dbhost'] = ''; # MySQL server hostname
$config['dbuser'] = ''; # MySQL username
$config['dbpass'] = ''; # MySQL password
$config['dbname'] = ''; # MySQL database name
$config['pconnect'] = 1; # Whether to use persistent database connections. 
# Not all databases support this, but if yours does, use it.

$config['version'] = '3.0'; # Game version number


$config['gamename_short'] = 'FAF'; # Game acronym
$config['gamename_full'] = 'Frost And Flame';# Full game name


$config['news']	='<span class="mnormal"></span>'; # Game news

$config['sitedir'] = automatic_server_url(); # Main site URL, 
#automatic_server_url() computes this automatically
$config['home'] = automatic_server_url(); # Where we go when we logout
$config['forums'] = 'http://frostnflame.org/forum/'; # Forums
$config['adminemail'] = 'faf@frostnflame.org'; # Admin email


#Server/Game Names
$config['servers'] = array();
$config['servers'][11] = 'War Of Ages';# Full name of each server
# $config['servers'][2] = 'Battle for Redwall';
# $config['servers'][3] = 'FAF Duels';
# $config['servers'][4] = 'ME vs FAF';
# $config['servers'][5] = 'The Challenge';
# $config['servers'][6] = 'Capture The Flag!';
# $config['servers'][7] = 'Battle for Redwall -- Memorial Round';
$config['servers'][9] = 'NoLimits';
$config['servers'][10] = 'Testing (Free-for-All)';

$config['prefixes'][11]	= 'long2'; # Prefix before each server's tables in the db
# $config['prefixes'][2] = 'bfr';
# $config['prefixes'][3] = 'duels3';
# $config['prefixes'][4] = 'mvf';
# $config['prefixes'][5] = 'spec2';
# $config['prefixes'][6] = 'flag2';
# $config['prefixes'][7] = 'bfr2';
$config['prefixes'][9] = 'beta';
$config['prefixes'][10] = 'test';


#Chat configuration						
$config['chatdomain'] = 'faf.staronesw.com'; # Chat document.domain
$config['chathost'] = 'chat.faf.staronesw.com'; # Chat host
$config['chatport']	= 5405; # Port for your chat -- only use if 5405 is blocked
$config['proxy_chat'] =	0;# Only use this if you have chat issues due to a server firewall,
# as it decreases performance

#Some display configurations, such as the template directory
$config['online_html'] ='*'; # HTML to be displayed next to online users
$config['online_txt'] =	'*'; # Text to be displayed next to online users, when HTML can't be used (e.g. RSS)
$config['dateformat'] = 'M j \'y, G:i'; # Date format (PHP)
$config['tpldir'] =	'prom';# Default style directory
$config['hiddenlogin'] = 1;


$config['multi_max'] = 1; # Maximum number of accounts a player can have


#In game settings

$config['protection'] = 300; # Duration of protection
$config['initturns'] = 300; # Turns given on signup

$config['minvacation'] = 72; # Minimum vacation duration
$config['vacationdelay'] = 12; # Delay before empire is protected
$config['deaddelay'] = 0; # Days before which idle empires are marked dead

$config['max_attacks'] = 20; # Maximum attacks for unallied empires
$config['wizatk_decr'] = 2; # The number by which the "maximum attacks" count is decremented for spell attacks
$config['online_warn']=	10; # Minutes within which to warn people that they are under attack

$config['early_exit'] =	0; # Are players allowed to leave protection early?

$config['jackpot'] = 0;# Default raffle jackpot
$config['maxtickets'] = 5; # Maximum number of raffle tickets per empire


$config['maxloan']=	5;# Times networth is the max loan
$config['maxsave'] = 20;# Times networth is the max savings
$config['savingsper'] = 10;# how much to remove extra savings per increment (in percent)
$config['savingsperminutes'] = 5;# how often to remove extra savings (in minutes)

#$config['game_factor'] = 1/1000;# How to amplify values of troops, food, etc. shown in the game
$config['game_factor'] = 1;# How to amplify values of troops, food, etc. shown in the game

#Troop base prices
$config['troop'][0] = 600;
$config['troop'][1] = 1200;
$config['troop'][2] = 2400;
$config['troop'][3] = 3600;# was 360000

#Prices to buy and sell food on the private market
$config['food_buy'] = 20;
$config['food_sell'] = 7;

$config['runes_sell'] =	325; # Base price for selling runes


$config['towers'] = 400;# Defensive points provided by guard towers
$config['blddef'] = 0;# Defensive points provided by other buildings
$config['aidtroop'] = 2;

$config['force_atktype'] = 2; # Forces Standard Attacks under 500 land. Set to 0 to disable.
$config['force_atkland'] = 500;

#Strategy Balancing Variables
$config['buildings_always'] = 1; # Whether to capture buildings with all attacks (1), or only standard attacks (0)
$config['landloss_mult'] = 1.00; # Multiplier for land lost in attacks, military only
$config['landloss_mage_mult'] = 0.67; # Multiplier for land lost in mage attacks
$config['scout_mage_mult'] = 0.35; # Multiplier for land gained in mage scout
$config['popbase_capacity'] = 1.10; # Multiplier for maximum worker capacity
$config['popbase_loss_rate'] = 0.90; # Multiplier for worker loss rate


$config['alt_networth'] = 0;# Use an alternative networth-calculation formula?

#Attack Names
$config['atknames'][2] = 'Standard Attack';# General attacks
$config['atknames'][3] = 'Surprise Attack';
$config['atknames'][4] = 'Guerilla Strike';# Troop attacks
$config['atknames'][5] = 'Archer Attack';
$config['atknames'][6] = 'Frontal Assault';
$config['atknames'][7] = 'Naval Battle';
$config['atknames'][8] = 'Pincer Attack';
$config['atknames'][9] = 'Sniper Assault';
$config['sackmodifier'] = 0.2;# Cash and food attack gains are multiplied by this amount

#Bank Settings/Intrest Rates
$config['loanbase'] = 5;# Base savings rate
$config['savebase'] = 4;# Base loan rate

$config['buildings'] = 2500; # Base building cost

#Market and Reserve Settings
$config['market'] =	6;# Hours to arrive on market
$config['market_flat_commission'] = 0.90; # Percent of troops you keep when withdrawing troops from the public market
$config['market_upkeep'] = 1; # Multiplier for upkeep cost on the market vs in camp
$config['market_nw'] = 1; # Multiplier for networth contribution of market troops vs in camp
$config['bmperc'] = 7500;# Percentage of troops that can be sold on black market
# (divide by 100 for percentage)
$config['reserveperc'] = 0.25; # Percentage of troops that can be reserved
$config['pmkt_troops'] = 9000; # Percentage of troops that can be sold on the public and clan markets
$config['cmkt_troops'] = 4000; # Percentage of troops that can be sold on the clan market
$config['pmkt_res'] = 7500; # Percentage of resources (food/runes) that can be sold on the public and clan markets
$config['pmkt_runes'] = 0; # Can trade runes on public/clan markets? -- Not yet implemented
$config['mktshops'] = 0.30; # Percentage of black market cost bonus for which shops are responsible
$config['indc'] = 2.0; # Industry output multiplier
$config['default_era'] = 2;# Default era users are put into

#Clan/Team settings
$config['clan_minsize'] = 3; # Minimum number of people in a clan to be displayed in statistics
$config['clan_msgcred'] = 1; # Number of credits required to send a message to the entire clan
$config['msgcred_bump'] = 3600; # Increment message credits every X seconds


#$config['disabled_pages'][]	= 'hero';# Pages to restrict access to
$config['disabled_pages'][] = 'example';

$config['prot_restr'][] = 'aid'; # Pages players under Protection cannot access
$config['prot_restr'][] = 'military';
$config['prot_restr'][] = 'pubmarketbuy';
$config['prot_restr'][] = 'pubmarketsell';
$config['prot_restr'][] = 'stocks';
$config['prot_restr'][]	= 'turnbank';
$config['prot_restr'][] = 'treasury';

$config['admin_restr'][] = 'military';# Pages Administrators are not allowed to access
#$config['admin_restr'][] = 'aid';
#$config['admin_restr'][] = 'farm';
#$config['admin_restr'][] = 'cash';
#$config['admin_restr'][] = 'land';
#$config['admin_restr'][] = 'runes';
#$config['admin_restr'][] = 'industry'
#$config['admin_restr'][] = 'pubmarketbuy'
#$config['admin_restr'][] = 'pubmarketsell'
#$config['admin_restr'][] = 'pvtmarketbuy'
#$config['admin_restr'][] = 'pvtmarketsell'
#$config['admin_restr'][] = 'clan';
#$config['admin_restr'][] = 'build';
#$config['admin_restr'][] = 'demolish'

$config['vacation_pages'][] = 'scores'; # Pages that can be accessed during vacation
$config['vacation_pages'][] = 'main';
$config['vacation_pages'][] = 'status';
$config['vacation_pages'][] = 'messages';
$config['vacation_pages'][] = 'sentmail';
$config['vacation_pages'][] = 'clanforum';
$config['vacation_pages'][] = 'contacts';
$config['vacation_pages'][] = 'profiles';
$config['vacation_pages'][] = 'clancrier';
$config['vacation_pages'][] = 'search';
$config['vacation_pages'][] = 'map';
$config['vacation_pages'][] = 'news';

$config['forum_news']	 = 1;# Forum news? (0 or 1)
$config['news_type']	 = 'ipb';# Forum type
$config['news_prefix']	 = 'forum'; # Forum table prefix
$config['news_forum']	 = 2;# From which to take news
$config['news_length']	 = 5;# How Many posts at once?

$config['warset'] =	0; # Allow individual war declarations?
$config['peaceset']	 = 0;# Allow individual alliances?


$config['default_style'] = 1;# Default style to use
$config['styles'][1]['name'] = 'Black';# Specify styles
$config['styles'][1]['file'] =	'black.css';
$config['styles'][2]['name'] = 'Shadows';
$config['styles'][2]['file']	=	'shadows.css';

$config['styles'][11]['name'] = 'Red';
$config['styles'][11]['file'] = 'red.css';
$config['styles'][12]['name'] = 'Green';
$config['styles'][12]['file'] = 'green.css';
$config['styles'][13]['name'] = 'Blue';
$config['styles'][13]['file'] = 'blue.css';

$config['styles'][21]['name'] = 'Forest';
$config['styles'][21]['file'] = 'forest.css';
$config['styles'][22]['name'] = 'Purple';
$config['styles'][22]['file'] = 'purple.css';
$config['styles'][23]['name'] = 'Forest II';
$config['styles'][23]['file'] = 'forest2.css';

$config['styles'][31]['name'] = 'Grayscale';
$config['styles'][31]['file'] = 'gray.css';

$config['styles'][41]['name'] = 'Red Inverse';
$config['styles'][41]['file'] = 'inv-red.css';
$config['styles'][42]['name'] = 'Green Inverse';
$config['styles'][42]['file'] = 'inv-green.css';
$config['styles'][43]['name'] = 'Blue Inverse';
$config['styles'][43]['file'] = 'inv-blue.css';

$config['styles'][51]['name'] = 'Forest Inverse';
$config['styles'][51]['file'] = 'inv-forest.css';
$config['styles'][52]['name'] = 'Purple Inverse';
$config['styles'][52]['file'] = 'inv-purple.css';
$config['styles'][53]['name'] = 'Forest II Inverse';
$config['styles'][53]['file'] = 'inv-forest2.css';

$config['styles'][61]['name'] = 'Dark Grayscale';
$config['styles'][61]['file'] = 'inv-gray.css';

$config['styles'][81]['name'] = 'Magenta';
$config['styles'][81]['file'] = 'magenta.css';
$config['styles'][83]['name'] = 'Cyan';
$config['styles'][83]['file'] = 'cyan.css';
$config['styles'][84]['name'] = 'Pink';
$config['styles'][84]['file'] = 'pink.css';

$config['styles'][91]['name'] = 'Magenta Inverse';
$config['styles'][91]['file'] = 'inv-magenta.css';
$config['styles'][93]['name'] = 'Cyan Inverse';
$config['styles'][93]['file']	=	'inv-cyan.css';
$config['styles'][94]['name'] = 'Pink Inverse';
$config['styles'][94]['file'] = 'inv-pink.css';

$config['styles'][71]['name'] = 'Plain';
$config['styles'][71]['file'] = 'null.css';
$config['styles'][72]['name'] = 'Redwall Warlords';
$config['styles'][72]['file'] = 'rwl.css';
$config['styles'][73]['name'] = 'Havok';
$config['styles'][73]['file'] = 'havok.css';

$config['styles'][99]['name'] = 'Christmas';
$config['styles'][99]['file'] = 'christmas.css';
$config['styles'][99]['admin'] = 0;# For admin eyes only

$config['fear_shame_mult'] = 2.5;# Fear/shame range
$config['default_cutoff'] = 20;# Default attack range
$config['clan_cutoff'] = 50;            ;# Clanned war attack range
$config['war_cutoff']	 = 50;# At war attack range
$config['attack_decr']	 = 2;# How much to decrement recent attacks after each attack
$config['max_land_drop'] = 100;# Percentage of land a player can drop
$config['hero_turnreq']	 = 1000;# Number of turns to be taken before a hero can be obtained
$config['hero_landreq']	 = 10000;# Acres needed before a hero can be obtained

$config['atknames'][2]	 = 'Standard Attack';# General attacks
$config['atknames'][3]	 = 'Surprise Attack';
$config['atknames'][4]		=	'Militia Strike';# Troop attacks
$config['atknames'][5]	 = 'Bombardment';
$config['atknames'][6]	 = 'Frontal Assault';
$config['atknames'][7]	 = 'Naval Attack';
$config['atknames'][8]	 = 'Boarding Attack';
$config['atknames'][9]	 = 'Naval Bombardment';

#Default number of 'eras' and 'races'
$config['eras']		 = 3;# Number of eras
$config['races']	 = 9;# Number of races

#Names of troops and goods for said races and eras
$config['er'][101]['ename'] = 'Southsward';
$config['er'][101]['nfood'] = 'Food';# Set attribute/value pairs of eras & races
$config['er'][101]['nrunes'] = 'Runes';# 100 place: the era; 1 place: the race
$config['er'][101]['troop4'] = 'Ballistas';# Missing values are filled out from the last era/race filled out
$config['er'][101]['wizards'] = 'Hawks';# This allows for great flexibility, e.g. race-specific troop names
$config['er'][101]['homes'] = 'Tents';# In determining precedence, the following attributes prioritize for race:
$config['er'][101]['shops'] = 'Markets';#    rname, offense, defense, bpt, costs, magic, ind, pci, expl, mkt,
$config['er'][101]['industry'] = 'Barracks';#    food, runes, farms, troop*
$config['er'][101]['barracks'] = 'Camps';# And the following attributes prioritize for era:
$config['er'][101]['labs'] = 'Huts';#    ename, peasants, nfood, nrunes, wizards, homes, shops, industry
$config['er'][101]['nfarms'] = 'Orchards';#    barracks, labs, nfarms, towers, empire, o_troop*, d_troop*
$config['er'][101]['towers'] = 'Guards';
$config['er'][101]['o_troop0'] = 2;
$config['er'][101]['d_troop0'] = 2;
$config['er'][101]['o_troop1'] = 3;
$config['er'][101]['d_troop1'] = 5;
$config['er'][101]['o_troop2'] = 5;
$config['er'][101]['d_troop2'] = 4;
$config['er'][101]['o_troop3'] = 7;# Was 700
$config['er'][101]['d_troop3'] = 6;# Was 600
$config['er'][101]['o_troop4'] = 10;
$config['er'][101]['d_troop4'] = 5;
$config['er'][101]['empire'] = 'warband';


$config['er'][201]['ename'] = 'Mossflower';
$config['er'][301]['ename'] = 'Northlands';
$config['er'][101]['rname'] = 'Mouse';# Race name
$config['er'][101]['troop0'] = 'Mice';
$config['er'][101]['troop1'] = 'Squirrels';
$config['er'][101]['troop2'] = 'Hares';
$config['er'][101]['troop3'] = 'Otters';
$config['er'][101]['peasants'] = 'workers';
$config['er'][101]['offense'] = 0.95;
$config['er'][101]['defense'] = 1.05;
$config['er'][101]['bpt'] = 1.05;
$config['er'][101]['costs'] = 0.95;
$config['er'][101]['magic'] = 0.95;
$config['er'][101]['ind'] = 0.95;
$config['er'][101]['pci'] = 0.95;
$config['er'][101]['expl'] = 1.00;
$config['er'][101]['mkt'] = 1.00;
$config['er'][101]['food'] = 1.05;
$config['er'][101]['runes'] = 0.95;
$config['er'][101]['farms'] = 1.05;

$config['er'][102]['rname'] = 'Squirrel';
#$config['er'][102]['troop0'] = 'Spearmen';
#$config['er'][102]['troop1'] = 'Archers';
#$config['er'][102]['troop2'] = 'Chariots';
#$config['er'][102]['troop3'] = 'Triremes';
#$config['er'][102]['peasants'] = 'Slaves';
$config['er'][102]['offense']	=	0.86;
$config['er'][102]['defense']	=	0.98;
$config['er'][102]['bpt']	=	0.9;
$config['er'][102]['costs']	=	1;
$config['er'][102]['magic']	=	1.18;
$config['er'][102]['ind']	=	0.88;
$config['er'][102]['pci']	=	1.02;
$config['er'][102]['expl']	=	1.12;
$config['er'][102]['mkt']	=	1;
$config['er'][102]['food']	=	1;
$config['er'][102]['runes']	=	1.12;
$config['er'][102]['farms']	=	0.94;

$config['er'][103]['rname'] = 'Shrew';
#$config['er'][103]['troop0'] = 'Swordsmen';
#$config['er'][103]['troop1'] = 'Slingers';
#$config['er'][103]['troop2'] = 'Spearmen';
#$config['er'][103]['troop3'] = 'Triremes';
#$config['er'][103]['peasants'] = 'Citizens';
$config['er'][103]['offense']	=	1.06;
$config['er'][103]['defense']	=	1.16;
$config['er'][103]['bpt']	=	1;
$config['er'][103]['costs']	=	1.08;
$config['er'][103]['magic']	=	0.84;
$config['er'][103]['ind']	=	1.12;
$config['er'][103]['pci']	=	1;
$config['er'][103]['expl']	=	0.82;
$config['er'][103]['mkt']	=	1.06;
$config['er'][103]['food']	=	1;
$config['er'][103]['runes']	=	1;
$config['er'][103]['farms']	=	1;

$config['er'][104]['rname'] = 'Otter';
#$config['er'][104]['troop0'] = 'Warriors';
#$config['er'][104]['troop1'] = 'Slingers';
#$config['er'][104]['troop2'] = 'Berserkers';
#$config['er'][104]['troop3'] = 'Warships';
#$config['er'][104]['peasants'] = 'Workers';
$config['er'][104]['offense']	=	1.24;
$config['er'][104]['defense']	=	0.9;
$config['er'][104]['bpt']	=	1.08;
$config['er'][104]['costs']	=	1;
$config['er'][104]['magic']	=	0.88;
$config['er'][104]['ind']	=	1;
$config['er'][104]['pci']	=	1.04;
$config['er'][104]['expl']	=	1.14;
$config['er'][104]['mkt']	=	1.12;
$config['er'][104]['food']	=	1;
$config['er'][104]['runes']	=	0.92;
$config['er'][104]['farms']	=	0.92;

$config['er'][105]['rname'] = 'Hedgehog';
#$config['er'][105]['troop0'] = 'Warriors';
#$config['er'][105]['troop1'] = 'Skirmishers';
#$config['er'][105]['troop2'] = 'Berserkers';
#$config['er'][105]['troop3'] = 'Warships';
#$config['er'][105]['peasants'] = 'Workers';
$config['er'][105]['offense']	=	0.84;
$config['er'][105]['defense']	=	1.1;
$config['er'][105]['bpt']	=	1;
$config['er'][105]['costs']	=	1.06;
$config['er'][105]['magic']	=	1;
$config['er'][105]['ind']	=	0.9;
$config['er'][105]['pci']	=	1.1;
$config['er'][105]['expl']	=	1.06;
$config['er'][105]['mkt']	=	0.94;
$config['er'][105]['food']	=	1;
$config['er'][105]['runes']	=	1;
$config['er'][105]['farms']	=	1;


$config['er'][106]['rname'] = 'Mole';
#$config['er'][106]['troop0'] = 'Peltasts';
#$config['er'][106]['troop1'] = 'Toxotes';
#$config['er'][106]['troop2'] = 'Hoplites';
#$config['er'][106]['troop3'] = 'Triremes';
#$config['er'][106]['peasants'] = 'Slaves';
$config['er'][106]['offense']	=	1.1;
$config['er'][106]['defense']	=	0.94;
$config['er'][106]['bpt']	=	1;
$config['er'][106]['costs']	=	1;
$config['er'][106]['magic']	=	0.9;
$config['er'][106]['ind']	=	1.1;	
$config['er'][106]['pci']	=	0.84;
$config['er'][106]['expl']	=	1;
$config['er'][106]['mkt']	=	1.08;
$config['er'][106]['food']	=	0.98;
$config['er'][106]['runes']	=	1;
$config['er'][106]['farms']	=	1.18;

$config['er'][107]['rname'] = 'Hare';
#$config['er'][107]['troop0'] = 'Pikemen';
#$config['er'][107]['troop1'] = 'Skirmishers';
#$config['er'][107]['troop2'] = 'Cavalrymen';
#$config['er'][107]['troop3'] = 'Triremes';
#$config['er'][107]['peasants'] = 'Slaves';
$config['er'][107]['offense']	=	1.14;
$config['er'][107]['defense']	=	0.98;
$config['er'][107]['bpt']	=	1.04;
$config['er'][107]['costs']	=	1.14;
$config['er'][107]['magic']	=	0.96;
$config['er'][107]['ind']	=	1.08;
$config['er'][107]['pci']	=	0.96;
$config['er'][107]['expl']	=	1.16;
$config['er'][107]['mkt']	=	1;
$config['er'][107]['food']	=	0.96;
$config['er'][107]['runes']	=	0.86;
$config['er'][107]['farms']	=	0.92;

$config['er'][108]['rname'] = 'Badger';
#$config['er'][108]['troop0'] = 'Spearmen';
#$config['er'][108]['troop1'] = 'Archers';
#$config['er'][108]['troop2'] = 'Chariots';
#$config['er'][108]['troop3'] = 'Triremes';
#$config['er'][108]['peasants'] = 'Citizens';
$config['er'][108]['offense']	=	1.16;
$config['er'][108]['defense']	=	1.08;
$config['er'][108]['bpt']	=	1.04;
$config['er'][108]['costs']	=	1.08;
$config['er'][108]['magic']	=	0.92;
$config['er'][108]['ind']	=	1;
$config['er'][108]['pci']	=	1;
$config['er'][108]['expl']	=	0.88;
$config['er'][108]['mkt']	=	1.08;
$config['er'][108]['food']	=	0.92;
$config['er'][108]['runes']	=	1.06;
$config['er'][108]['farms']	=	0.94;

$config['er'][109]['rname'] = 'Vole';
#$config['er'][109]['troop0'] = 'Spearmen';
#$config['er'][109]['troop1'] = 'Slingers';
#$config['er'][109]['troop2'] = 'Chariots';
#$config['er'][109]['troop3'] = 'Quinqiremes';
#$config['er'][109]['peasants'] = 'Workers';
$config['er'][109]['offense']	=	0.84;
$config['er'][109]['defense']	=	0.94;
$config['er'][109]['bpt']	=	1;
$config['er'][109]['costs']	=	0.86;
$config['er'][109]['magic']	=	0.92;
$config['er'][109]['ind']	=	1.14;
$config['er'][109]['pci']	=	1;
$config['er'][109]['expl']	=	1;
$config['er'][109]['mkt']	=	1.08;
$config['er'][109]['food']	=	0.92;
$config['er'][109]['runes']	=	0.84;
$config['er'][109]['farms']	=	1.04;

#Magic/Leader Mission names
$config['missionspy']	 = 'Espionage';
$config['missionblast']	 = 'Murder';
$config['missionshield'] = 'Raise Defenses';
$config['missionstorm']	 = 'Poison Crops';
$config['missionrunes']	 = 'Destroy Runes';
$config['missionstruct'] = 'Destroy Structures';
$config['missionfood']	 = 'Forage';
$config['missiongold']	 = 'Loot';
$config['missioned']	 = 'Explore';
$config['missionheal']	 = 'Heal';
$config['missionpeasant'] = 'Recruit';
$config['missionprod']	 = 'Prod Market';
$config['missionkill']	 = 'Seppuku';
$config['missiongate']	 = 'Prepare Hawks';
$config['missionungate'] = 'Recall Hawks';
$config['missionfight']	 = 'Hawk Battle';
$config['missionsteal']	 = 'Steal Cash';
$config['missionrob']	 = 'Rob Granaries';
$config['missionadvance'] = 'Move North';
$config['missionback']	 = 'Move South';

$config['roundend']	 = "Feb. 1, 2006.";# An English representation of the time of the round's end

$config['turnsper']	 = 3;# And some defaults...
$config['perminutes']	 = 15;
$config['turnbankper']	 = 1;
$config['bankperminutes'] = 30;
$config['maxturnbank']	 = 100;
$config['turnoffset']		=	0;
$config['autolastweek'] 	=	0;

$config['landmult'] = 2.5; #Multiplier for the amount of land gained while scouting
$config['cashmult'] = 1.3; #Multiplier for the amount of cash gained while cashing
$config['foodmult'] = 1.0; #Multiplier for the amount of food generated while farming
$config['runesmult'] = 1.0; #Multiplier for the amount of ruins generated while gathering ruins.
$config['indmult'] = 1.5; #Multiplier for for the number of troops produced while recruiting
$config['shopmult'] = 1.6; #Multiplier for for the cash gained per shop (market)

#$config['micestock'] = 10000000; // Sell/buy 100,000 mice to get a 1% shift in stock price. 
#$config['sqrlstock'] = 10000000; // need an extra 00 to make it a % shift so a 1+.01 change
#$config['harestock'] = 7500000; // occurs correctly.
#$config['ottrstock'] = 6000000; // price goes down because they get more expensive
#$config['grinstock'] = 25000000; // 250,000 grain for an adjustment minimum.
#$config['runestock'] = 2500000; // 25,000 runes sold 


end_global_config();										
####################################
# END OF GLOBAL CONFIGURATION SECTON #
####################################

#######################################
# BEGIN SERVER SPECIFIC CONFIGURATION #
#######################################

#WOA
server_specific_config(11);
$config['maxturns']	 = 500;# Max accumulated turns
$config['maxstoredturns'] = 250;# Max stored turns

$config['strat_balance'] = 1;# Some changes for strat balancing.
$config['signupsclosed'] = 0;# Signups closed?
$config['lockdb']	 = 0;# Lock the database?
$config['lastweek']	 = 0;# Last week? (No loans)

$config['turnsper']	 = 3;# X turns
$config['perminutes']	 = 15;# per Y minutes
$config['turnbankper']	 = 1;# And for the turn bank 
$config['bankperminutes'] = 30; 
$config['maxturnbank']	 = 100;# Maximum size of turn bank
$config['turnoffset']	 = 0;# Correct for server lag

$config['resetvote']	 = 1;# Allow reset voting?
$config['votepercent']	 = 75;# Percent before notifying admins?

#$config['news']	= 'If you are a new player, you may find it helpful to visit our <a href="/forum">forums</a>, where you can find anything from general strategy guides to personal teachers.';
$config['news'] = '<span class="mnormal">Troops on the public and clan markets now require upkeep and contribute to your networth.</span>';
$config['roundend']	 = "Never";# An English representation of the time of the round's end
$config['disabled_pages'][] = 'stocks';
$config['forcestandard'] = 1;

#BFR
server_specific_config(2);
$config['strat_balance'] = 1;# Some changes for strategy balancing.
$config['maxturns']	 = 500;# Max accumulated turns
$config['maxstoredturns'] = 250;# Max stored turns

$config['signupsclosed'] = 0;# Signups closed?
$config['lockdb']	 = 0;# Lock the database?
$config['lastweek']	 = 0;# Last week? (No loans)
$config['autolastweek']; # Automatically set lastweek

$config['forcestandard'] = 1;

$config['turnsper']	 = 5;# X turns
$config['perminutes']	 = 10;# per Y minutes
$config['turnoffset']	 = 0;# Correct for server lag

$config['resetvote']	 = 1;# Allow reset voting?
$config['votepercent']	 = 75;# Percent before notifying admins?

$config['max_attacks']		=	21;# Maximum attacks for unallied empires

#$config['news'] = '<span class="mnormal">Battle for Redwall is a fast-paced game that resets monthly.</span>';
$config['news'] = '<span class="mnormal">Troops on the public and clan markets now require upkeep and contribute to your networth. Boats have been modified, but not broken.</span>';

#	$config['alt_networth']		=	1;# Use an alternative networth-calculation formula?
#	$config['sackmodifier']		=	0.1;# Cash and food attack gains are multiplied by this amount
#	$config['towers']		=	1000;
#	$config['blddef']		=	100;# Defensive points provided by other buildings

#$config['disabled_pages'][]	=	'stocks';
$config['roundend']		=	next_month();# An English representation of the time of the round's end

#$config['landmult'] = 2.5; #Multiplier for the amount of land gained while scouting
#$config['cashmult'] = 1.0; #Multiplier for the amount of cash gained while cashing
#$config['foodmult'] = 1.0; #Multiplier for the amount of food generated while farming
#$config['runesmult'] = 1.0; #Multiplier for the amount of ruins generated while gathering ruins.

#End BFR

#Duels
server_specific_config(3);
$config['maxturns']	 = 400;# Max accumulated turns
$config['maxstoredturns'] = 200;# Max stored turns

$config['signupsclosed'] = 1;# Signups closed?
$config['lockdb']	 = 0;# Lock the database?
$config['lastweek']	 = 1;# Last week? (No loans)

$config['turnsper']	 = 1;# X turns
$config['perminutes']	 = 10;# per Y minutes
$config['turnoffset']	 = 0;# Correct for server lag

$config['resetvote']	 = 1;# Allow reset voting?
$config['votepercent']	 = 75;# Percent before notifying admins?

$config['news'] = '<span class="mnormal">Welcome to FAF Duels!</span>';
$config['disabled_pages'][] = 'stocks';
$config['disabled_pages'][] = 'raffle';
$config['disabled_pages'][] = 'clan';
$config['disabled_pages'][] = 'clanmanage';
$config['disabled_pages'][] = 'delete';
$config['disabled_pages'][] = 'bank';
$config['disabled_pages'][] = 'raffle';
$config['disabled_pages'][] = 'clanjoin';

server_specific_config(4);
$config['maxturns']	 = 4; # Max accumulated turns
$config['maxstoredturns'] = 200;# Max stored turns

$config['signupsclosed'] = 1;# Signups closed?
$config['lockdb']	 = 1;# Lock the database?
$config['lastweek']	 = 1;# Last week? (No loans)

$config['turnsper']	 = 5;# X turns
$config['perminutes'] = 15;# per Y minutes
$config['turnoffset'] = 0;# Correct for server lag

$config['resetvote'] =	1;# Allow reset voting?
$config['votepercent']=	75;# Percent before notifying admins?


$config['news'] = '<span class="mnormal">World Cup Finals: ME vs FAF</span>';
$config['disabled_pages'][] = 'stocks';
$config['disabled_pages'][] = 'raffle';
$config['disabled_pages'][] = 'clan';
$config['disabled_pages'][] = 'clanmanage';
$config['disabled_pages'][] = 'delete';
$config['disabled_pages'][] = 'bank';
$config['disabled_pages'][] = 'raffle';
$config['disabled_pages'][] = 'clanjoin';

server_specific_config(8);
$config['protection']	 = -1;
$config['initturns']	 = 10000;
$config['max_attacks']	 = pow(2,20);

$config['maxturns']	 = 10000;# Max accumulated turns
$config['maxstoredturns'] = 0;# Max stored turns

$config['signupsclosed'] = 0;# Signups closed?
$config['lockdb']	 = 0;# Lock the database?
$config['lastweek']	 = 1;# Last week? (No loans)

$config['turnsper']	 = 0;# X turns
$config['perminutes']	 = 10;# per Y minutes
$config['turnoffset']	 = 0;# Correct for server lag

$config['resetvote']	 = 0;# Allow reset voting?

$config['disabled_pages'][] = 'clans';
$config['disabled_pages'][] = 'clan';
$config['disabled_pages'][]	=	'clanstats';
$config['disabled_pages'][] = 'contacts';
$config['disabled_pages'][] = 'clanjoin';

$config['news'] = '<span class="mnormal">After spending your initial turns completely, you will be saved to the Hall Of Fame and locked out of your account.<br>You may only sign up for a new account when your current one has died.</span>';
$config['nolimit_mode']	 = 1;
$config['nolimit_table'] = 'faf_hfame';

server_specific_config(5);
$config['maxturns']	 = 400;# Max accumulated turns
$config['maxstoredturns'] = 300;# Max stored turns

$config['signupsclosed'] = 0;# Signups closed?
$config['lockdb']	 = 0;# Lock the database?
$config['lastweek']	 = 1;# Last week? (No loans)

$config['turnsper']	 = 5;# X turns
$config['perminutes']	 = 15;# per Y minutes
$config['turnoffset']	 = 0;# Correct for server lag
$config['warset']	 = 1;# Allow individual war declarations?

$config['resetvote'] = 1; # Allow reset voting?
$config['votepercent']	=	75;# Percent before notifying admins?

$config['news'] = '<span class="mnormal">The objective is to kill the Flag players for the huge bounties.</span>';
$config['disabled_pages'][] = 'stocks';
$config['disabled_pages'][] = 'raffle';

server_specific_config(7);
$config['maxturns']	 = 5;# Max accumulated turns
$config['maxstoredturns'] = 250;# Max stored turns

$config['signupsclosed'] = 1;# Signups closed?
$config['lockdb']	 = 1;# Lock the database?
$config['lastweek']	 = 1;# Last week? (No loans)

$config['forcestandard'] = 1;

$config['turnsper']	 = 5; # X turns
$config['perminutes']	 = 10; # per Y minutes
$config['turnoffset']	 = 0; # Correct for server lag

$config['resetvote'] = 1;# Allow reset voting?
$config['votepercent'] = 75;# Percent before notifying admins?

#	$config['alt_networth']		=	1;# Use an alternative networth-calculation formula?
#	$config['sackmodifier']		=	0.1;# Cash and food attack gains are multiplied by this amount
#	$config['towers']		=	1000;
#	$config['blddef']		=	100;# Defensive points provided by other buildings
$config['disabled_pages'][] = 'stocks';

server_specific_config(9);
$config['initturns']	 = 500;
$config['maxturns']	 = 500;# Max accumulated turns
$config['maxstoredturns'] = 0;# Max stored turns
$config['max_attacks']	 = 200;

$config['signupsclosed'] = 1;# Signups closed?
$config['lockdb']	 = 1;# Lock the database?
$config['lastweek']	 = 0;# Last week? (No loans)

$config['forcestandard'] = 1;

$config['multi_max'] = 10;# Maximum number of accounts a player can have

$config['turnsper']  = 1; # X turns
$config['perminutes'] =	1; # per Y minutes
$config['turnoffset'] =	0; # Correct for server lag

$config['resetvote']	 = 1;# Allow reset voting?
$config['votepercent']	 = 75;# Percent before notifying admins?

$config['max_attacks']	 = 30;# Maximum attacks for unallied empires

$config['news'] = '<span class="mnormal">Testing server. Up to 5 accounts per player allowed.</span>';

$config['disabled_pages'][]	= 'stocks';
$config['roundend'] = next_month();# An English representation of the time of the round's end
$config['multi_max']		=	5;# Maximum number of accounts a player can have


?>

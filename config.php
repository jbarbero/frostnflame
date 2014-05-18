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
$config_global['dbhost'] = ''; # MySQL server hostname
$config_global['dbuser'] = ''; # MySQL username
$config_global['dbpass'] = ''; # MySQL password
$config_global['dbname'] = ''; # MySQL database name
$config_global['pconnect'] = 1; # Whether to use persistent database connections.
# Not all databases support this, but if yours does, use it.

$config_global['version'] = '3.0'; # Game version number

$config_global['gamename_short'] = 'FAF'; # Game acronym
$config_global['gamename_full'] = 'Frost And Flame';# Full game name

$config_global['news']    ='<span class="mnormal"></span>'; # Game news

$config_global['sitedir'] = automatic_server_url(); # Main site URL,
#automatic_server_url() computes this automatically
$config_global['home'] = automatic_server_url(); # Where we go when we logout
$config_global['forums'] = 'http://frostnflame.org/forum/'; # Forums
$config_global['adminemail'] = 'faf@frostnflame.org'; # Admin email


#Server/Game Names
$config_global['servers'] = array();
$config_global['servers'][11] = 'War Of Ages';# Full name of each server
# $config_global['servers'][2] = 'Battle for Redwall';
# $config_global['servers'][3] = 'FAF Duels';
# $config_global['servers'][4] = 'ME vs FAF';
# $config_global['servers'][5] = 'The Challenge';
# $config_global['servers'][6] = 'Capture The Flag!';
# $config_global['servers'][7] = 'Battle for Redwall -- Memorial Round';
$config_global['servers'][9] = 'NoLimits';
$config_global['servers'][10] = 'Testing (Free-for-All)';

$config_global['prefixes'][11]    = 'long2'; # Prefix before each server's tables in the db
# $config_global['prefixes'][2] = 'bfr';
# $config_global['prefixes'][3] = 'duels3';
# $config_global['prefixes'][4] = 'mvf';
# $config_global['prefixes'][5] = 'spec2';
# $config_global['prefixes'][6] = 'flag2';
# $config_global['prefixes'][7] = 'bfr2';
$config_global['prefixes'][9] = 'beta';
$config_global['prefixes'][10] = 'test';


#Chat configuration
$config_global['chatdomain'] = 'faf.staronesw.com'; # Chat document.domain
$config_global['chathost'] = 'chat.faf.staronesw.com'; # Chat host
$config_global['chatport']    = 5405; # Port for your chat -- only use if 5405 is blocked
$config_global['proxy_chat'] =    0;# Only use this if you have chat issues due to a server firewall,
# as it decreases performance

#Some display configurations, such as the template directory
$config_global['online_html'] ='*'; # HTML to be displayed next to online users
$config_global['online_txt'] =    '*'; # Text to be displayed next to online users, when HTML can't be used (e.g. RSS)
$config_global['dateformat'] = 'M j \'y, G:i'; # Date format (PHP)
$config_global['tpldir'] =    'prom';# Default style directory
$config_global['hiddenlogin'] = 1;


$config_global['multi_max'] = 1; # Maximum number of accounts a player can have


#In game settings

$config_global['protection'] = 300; # Duration of protection
$config_global['initturns'] = 300; # Turns given on signup

$config_global['minvacation'] = 72; # Minimum vacation duration
$config_global['vacationdelay'] = 12; # Delay before empire is protected
$config_global['deaddelay'] = 0; # Days before which idle empires are marked dead

$config_global['max_attacks'] = 20; # Maximum attacks for unallied empires
$config_global['wizatk_decr'] = 2; # The number by which the "maximum attacks" count is decremented for spell attacks
$config_global['online_warn']=    10; # Minutes within which to warn people that they are under attack

$config_global['early_exit'] =    0; # Are players allowed to leave protection early?

$config_global['jackpot'] = 0;# Default raffle jackpot
$config_global['maxtickets'] = 5; # Maximum number of raffle tickets per empire


$config_global['maxloan']=    5;# Times networth is the max loan
$config_global['maxsave'] = 20;# Times networth is the max savings
$config_global['savingsper'] = 10;# how much to remove extra savings per increment (in percent)
$config_global['savingsperminutes'] = 5;# how often to remove extra savings (in minutes)

#$config_global['game_factor'] = 1/1000;# How to amplify values of troops, food, etc. shown in the game
$config_global['game_factor'] = 1;# How to amplify values of troops, food, etc. shown in the game

#Troop base prices
$config_global['troop'][0] = 600;
$config_global['troop'][1] = 1200;
$config_global['troop'][2] = 2400;
$config_global['troop'][3] = 3600;# was 360000

#Prices to buy and sell food on the private market
$config_global['food_buy'] = 20;
$config_global['food_sell'] = 7;

$config_global['runes_sell'] =    325; # Base price for selling runes


$config_global['towers'] = 400;# Defensive points provided by guard towers
$config_global['blddef'] = 0;# Defensive points provided by other buildings
$config_global['aidtroop'] = 2;

$config_global['force_atktype'] = 2; # Forces Standard Attacks under 500 land. Set to 0 to disable.
$config_global['force_atkland'] = 500;

#Strategy Balancing Variables
$config_global['buildings_always'] = 1; # Whether to capture buildings with all attacks (1), or only standard attacks (0)
$config_global['landloss_mult'] = 1.00; # Multiplier for land lost in attacks, military only
$config_global['landloss_mage_mult'] = 0.67; # Multiplier for land lost in mage attacks
$config_global['scout_mage_mult'] = 0.35; # Multiplier for land gained in mage scout
$config_global['popbase_capacity'] = 1.10; # Multiplier for maximum worker capacity
$config_global['popbase_loss_rate'] = 0.90; # Multiplier for worker loss rate


$config_global['alt_networth'] = 0;# Use an alternative networth-calculation formula?

#Attack Names
$config_global['atknames'][2] = 'Standard Attack';# General attacks
$config_global['atknames'][3] = 'Surprise Attack';
$config_global['atknames'][4] = 'Guerilla Strike';# Troop attacks
$config_global['atknames'][5] = 'Archer Attack';
$config_global['atknames'][6] = 'Frontal Assault';
$config_global['atknames'][7] = 'Naval Battle';
$config_global['atknames'][8] = 'Pincer Attack';
$config_global['atknames'][9] = 'Sniper Assault';
$config_global['sackmodifier'] = 0.2;# Cash and food attack gains are multiplied by this amount

#Bank Settings/Intrest Rates
$config_global['loanbase'] = 5;# Base savings rate
$config_global['savebase'] = 4;# Base loan rate

$config_global['buildings'] = 2500; # Base building cost

#Market and Reserve Settings
$config_global['market'] =    6;# Hours to arrive on market
$config_global['market_flat_commission'] = 0.90; # Percent of troops you keep when withdrawing troops from the public market
$config_global['market_upkeep'] = 1; # Multiplier for upkeep cost on the market vs in camp
$config_global['market_nw'] = 1; # Multiplier for networth contribution of market troops vs in camp
$config_global['bmperc'] = 7500;# Percentage of troops that can be sold on black market
# (divide by 100 for percentage)
$config_global['reserveperc'] = 0.25; # Percentage of troops that can be reserved
$config_global['pmkt_troops'] = 9000; # Percentage of troops that can be sold on the public and clan markets
$config_global['cmkt_troops'] = 4000; # Percentage of troops that can be sold on the clan market
$config_global['pmkt_res'] = 7500; # Percentage of resources (food/runes) that can be sold on the public and clan markets
$config_global['pmkt_runes'] = 0; # Can trade runes on public/clan markets? -- Not yet implemented
$config_global['mktshops'] = 0.30; # Percentage of black market cost bonus for which shops are responsible
$config_global['indc'] = 2.0; # Industry output multiplier
$config_global['default_era'] = 2;# Default era users are put into

#Clan/Team settings
$config_global['clan_minsize'] = 3; # Minimum number of people in a clan to be displayed in statistics
$config_global['clan_msgcred'] = 1; # Number of credits required to send a message to the entire clan
$config_global['msgcred_bump'] = 3600; # Increment message credits every X seconds


#$config_global['disabled_pages'][]    = 'hero';# Pages to restrict access to
$config_global['disabled_pages'][] = 'example';

$config_global['prot_restr'][] = 'aid'; # Pages players under Protection cannot access
$config_global['prot_restr'][] = 'military';
$config_global['prot_restr'][] = 'pubmarketbuy';
$config_global['prot_restr'][] = 'pubmarketsell';
$config_global['prot_restr'][] = 'stocks';
$config_global['prot_restr'][]    = 'turnbank';
$config_global['prot_restr'][] = 'treasury';

$config_global['admin_restr'][] = 'military';# Pages Administrators are not allowed to access
#$config_global['admin_restr'][] = 'aid';
#$config_global['admin_restr'][] = 'farm';
#$config_global['admin_restr'][] = 'cash';
#$config_global['admin_restr'][] = 'land';
#$config_global['admin_restr'][] = 'runes';
#$config_global['admin_restr'][] = 'industry'
#$config_global['admin_restr'][] = 'pubmarketbuy'
#$config_global['admin_restr'][] = 'pubmarketsell'
#$config_global['admin_restr'][] = 'pvtmarketbuy'
#$config_global['admin_restr'][] = 'pvtmarketsell'
#$config_global['admin_restr'][] = 'clan';
#$config_global['admin_restr'][] = 'build';
#$config_global['admin_restr'][] = 'demolish'

$config_global['vacation_pages'][] = 'scores'; # Pages that can be accessed during vacation
$config_global['vacation_pages'][] = 'main';
$config_global['vacation_pages'][] = 'status';
$config_global['vacation_pages'][] = 'messages';
$config_global['vacation_pages'][] = 'sentmail';
$config_global['vacation_pages'][] = 'clanforum';
$config_global['vacation_pages'][] = 'contacts';
$config_global['vacation_pages'][] = 'profiles';
$config_global['vacation_pages'][] = 'clancrier';
$config_global['vacation_pages'][] = 'search';
$config_global['vacation_pages'][] = 'map';
$config_global['vacation_pages'][] = 'news';

$config_global['forum_news']     = 1;# Forum news? (0 or 1)
$config_global['news_type']     = 'ipb';# Forum type
$config_global['news_prefix']     = 'forum'; # Forum table prefix
$config_global['news_forum']     = 2;# From which to take news
$config_global['news_length']     = 5;# How Many posts at once?

$config_global['warset'] =    0; # Allow individual war declarations?
$config_global['peaceset']     = 0;# Allow individual alliances?


$config_global['default_style'] = 1;# Default style to use
$config_global['styles'][1]['name'] = 'Black';# Specify styles
$config_global['styles'][1]['file'] =    'black.css';
$config_global['styles'][2]['name'] = 'Shadows';
$config_global['styles'][2]['file']    =    'shadows.css';

$config_global['styles'][11]['name'] = 'Red';
$config_global['styles'][11]['file'] = 'red.css';
$config_global['styles'][12]['name'] = 'Green';
$config_global['styles'][12]['file'] = 'green.css';
$config_global['styles'][13]['name'] = 'Blue';
$config_global['styles'][13]['file'] = 'blue.css';

$config_global['styles'][21]['name'] = 'Forest';
$config_global['styles'][21]['file'] = 'forest.css';
$config_global['styles'][22]['name'] = 'Purple';
$config_global['styles'][22]['file'] = 'purple.css';
$config_global['styles'][23]['name'] = 'Forest II';
$config_global['styles'][23]['file'] = 'forest2.css';

$config_global['styles'][31]['name'] = 'Grayscale';
$config_global['styles'][31]['file'] = 'gray.css';

$config_global['styles'][41]['name'] = 'Red Inverse';
$config_global['styles'][41]['file'] = 'inv-red.css';
$config_global['styles'][42]['name'] = 'Green Inverse';
$config_global['styles'][42]['file'] = 'inv-green.css';
$config_global['styles'][43]['name'] = 'Blue Inverse';
$config_global['styles'][43]['file'] = 'inv-blue.css';

$config_global['styles'][51]['name'] = 'Forest Inverse';
$config_global['styles'][51]['file'] = 'inv-forest.css';
$config_global['styles'][52]['name'] = 'Purple Inverse';
$config_global['styles'][52]['file'] = 'inv-purple.css';
$config_global['styles'][53]['name'] = 'Forest II Inverse';
$config_global['styles'][53]['file'] = 'inv-forest2.css';

$config_global['styles'][61]['name'] = 'Dark Grayscale';
$config_global['styles'][61]['file'] = 'inv-gray.css';

$config_global['styles'][81]['name'] = 'Magenta';
$config_global['styles'][81]['file'] = 'magenta.css';
$config_global['styles'][83]['name'] = 'Cyan';
$config_global['styles'][83]['file'] = 'cyan.css';
$config_global['styles'][84]['name'] = 'Pink';
$config_global['styles'][84]['file'] = 'pink.css';

$config_global['styles'][91]['name'] = 'Magenta Inverse';
$config_global['styles'][91]['file'] = 'inv-magenta.css';
$config_global['styles'][93]['name'] = 'Cyan Inverse';
$config_global['styles'][93]['file']    =    'inv-cyan.css';
$config_global['styles'][94]['name'] = 'Pink Inverse';
$config_global['styles'][94]['file'] = 'inv-pink.css';

$config_global['styles'][71]['name'] = 'Plain';
$config_global['styles'][71]['file'] = 'null.css';
$config_global['styles'][72]['name'] = 'Redwall Warlords';
$config_global['styles'][72]['file'] = 'rwl.css';
$config_global['styles'][73]['name'] = 'Havok';
$config_global['styles'][73]['file'] = 'havok.css';

$config_global['styles'][99]['name'] = 'Christmas';
$config_global['styles'][99]['file'] = 'christmas.css';
$config_global['styles'][99]['admin'] = 0;# For admin eyes only

$config_global['fear_shame_mult'] = 2.5;# Fear/shame range
$config_global['default_cutoff'] = 20;# Default attack range
$config_global['clan_cutoff'] = 50;            ;# Clanned war attack range
$config_global['war_cutoff']     = 50;# At war attack range
$config_global['attack_decr']     = 2;# How much to decrement recent attacks after each attack
$config_global['max_land_drop'] = 100;# Percentage of land a player can drop
$config_global['hero_turnreq']     = 1000;# Number of turns to be taken before a hero can be obtained
$config_global['hero_landreq']     = 10000;# Acres needed before a hero can be obtained

$config_global['atknames'][2]     = 'Standard Attack';# General attacks
$config_global['atknames'][3]     = 'Surprise Attack';
$config_global['atknames'][4]        =    'Militia Strike';# Troop attacks
$config_global['atknames'][5]     = 'Bombardment';
$config_global['atknames'][6]     = 'Frontal Assault';
$config_global['atknames'][7]     = 'Naval Attack';
$config_global['atknames'][8]     = 'Boarding Attack';
$config_global['atknames'][9]     = 'Naval Bombardment';

#Default number of 'eras' and 'races'
$config_global['eras']         = 3;# Number of eras
$config_global['races']     = 9;# Number of races

#Names of troops and goods for said races and eras
$config_global['er'][101]['ename'] = 'Southsward';
$config_global['er'][101]['nfood'] = 'Food';# Set attribute/value pairs of eras & races
$config_global['er'][101]['nrunes'] = 'Runes';# 100 place: the era; 1 place: the race
$config_global['er'][101]['troop4'] = 'Ballistas';# Missing values are filled out from the last era/race filled out
$config_global['er'][101]['wizards'] = 'Hawks';# This allows for great flexibility, e.g. race-specific troop names
$config_global['er'][101]['homes'] = 'Tents';# In determining precedence, the following attributes prioritize for race:
$config_global['er'][101]['shops'] = 'Markets';#    rname, offense, defense, bpt, costs, magic, ind, pci, expl, mkt,
$config_global['er'][101]['industry'] = 'Barracks';#    food, runes, farms, troop*
$config_global['er'][101]['barracks'] = 'Camps';# And the following attributes prioritize for era:
$config_global['er'][101]['labs'] = 'Huts';#    ename, peasants, nfood, nrunes, wizards, homes, shops, industry
$config_global['er'][101]['nfarms'] = 'Orchards';#    barracks, labs, nfarms, towers, empire, o_troop*, d_troop*
$config_global['er'][101]['towers'] = 'Guards';
$config_global['er'][101]['o_troop0'] = 2;
$config_global['er'][101]['d_troop0'] = 2;
$config_global['er'][101]['o_troop1'] = 3;
$config_global['er'][101]['d_troop1'] = 5;
$config_global['er'][101]['o_troop2'] = 5;
$config_global['er'][101]['d_troop2'] = 4;
$config_global['er'][101]['o_troop3'] = 7;# Was 700
$config_global['er'][101]['d_troop3'] = 6;# Was 600
$config_global['er'][101]['o_troop4'] = 10;
$config_global['er'][101]['d_troop4'] = 5;
$config_global['er'][101]['empire'] = 'warband';


$config_global['er'][201]['ename'] = 'Mossflower';
$config_global['er'][301]['ename'] = 'Northlands';
$config_global['er'][101]['rname'] = 'Mouse';# Race name
$config_global['er'][101]['troop0'] = 'Mice';
$config_global['er'][101]['troop1'] = 'Squirrels';
$config_global['er'][101]['troop2'] = 'Hares';
$config_global['er'][101]['troop3'] = 'Otters';
$config_global['er'][101]['peasants'] = 'Workers';
$config_global['er'][101]['offense'] = 0.95;
$config_global['er'][101]['defense'] = 1.05;
$config_global['er'][101]['bpt'] = 1.05;
$config_global['er'][101]['costs'] = 0.95;
$config_global['er'][101]['magic'] = 0.95;
$config_global['er'][101]['ind'] = 0.95;
$config_global['er'][101]['pci'] = 0.95;
$config_global['er'][101]['expl'] = 1.00;
$config_global['er'][101]['mkt'] = 1.00;
$config_global['er'][101]['food'] = 1.05;
$config_global['er'][101]['runes'] = 0.95;
$config_global['er'][101]['farms'] = 1.05;

$config_global['er'][102]['rname'] = 'Squirrel';
#$config_global['er'][102]['troop0'] = 'Spearmen';
#$config_global['er'][102]['troop1'] = 'Archers';
#$config_global['er'][102]['troop2'] = 'Chariots';
#$config_global['er'][102]['troop3'] = 'Triremes';
#$config_global['er'][102]['peasants'] = 'Slaves';
$config_global['er'][102]['offense']    =    0.86;
$config_global['er'][102]['defense']    =    0.98;
$config_global['er'][102]['bpt']    =    0.9;
$config_global['er'][102]['costs']    =    1;
$config_global['er'][102]['magic']    =    1.18;
$config_global['er'][102]['ind']    =    0.88;
$config_global['er'][102]['pci']    =    1.02;
$config_global['er'][102]['expl']    =    1.12;
$config_global['er'][102]['mkt']    =    1;
$config_global['er'][102]['food']    =    1;
$config_global['er'][102]['runes']    =    1.12;
$config_global['er'][102]['farms']    =    0.94;

$config_global['er'][103]['rname'] = 'Shrew';
#$config_global['er'][103]['troop0'] = 'Swordsmen';
#$config_global['er'][103]['troop1'] = 'Slingers';
#$config_global['er'][103]['troop2'] = 'Spearmen';
#$config_global['er'][103]['troop3'] = 'Triremes';
#$config_global['er'][103]['peasants'] = 'Citizens';
$config_global['er'][103]['offense']    =    1.06;
$config_global['er'][103]['defense']    =    1.16;
$config_global['er'][103]['bpt']    =    1;
$config_global['er'][103]['costs']    =    1.08;
$config_global['er'][103]['magic']    =    0.84;
$config_global['er'][103]['ind']    =    1.12;
$config_global['er'][103]['pci']    =    1;
$config_global['er'][103]['expl']    =    0.82;
$config_global['er'][103]['mkt']    =    1.06;
$config_global['er'][103]['food']    =    1;
$config_global['er'][103]['runes']    =    1;
$config_global['er'][103]['farms']    =    1;

$config_global['er'][104]['rname'] = 'Otter';
#$config_global['er'][104]['troop0'] = 'Warriors';
#$config_global['er'][104]['troop1'] = 'Slingers';
#$config_global['er'][104]['troop2'] = 'Berserkers';
#$config_global['er'][104]['troop3'] = 'Warships';
#$config_global['er'][104]['peasants'] = 'Workers';
$config_global['er'][104]['offense']    =    1.24;
$config_global['er'][104]['defense']    =    0.9;
$config_global['er'][104]['bpt']    =    1.08;
$config_global['er'][104]['costs']    =    1;
$config_global['er'][104]['magic']    =    0.88;
$config_global['er'][104]['ind']    =    1;
$config_global['er'][104]['pci']    =    1.04;
$config_global['er'][104]['expl']    =    1.14;
$config_global['er'][104]['mkt']    =    1.12;
$config_global['er'][104]['food']    =    1;
$config_global['er'][104]['runes']    =    0.92;
$config_global['er'][104]['farms']    =    0.92;

$config_global['er'][105]['rname'] = 'Hedgehog';
#$config_global['er'][105]['troop0'] = 'Warriors';
#$config_global['er'][105]['troop1'] = 'Skirmishers';
#$config_global['er'][105]['troop2'] = 'Berserkers';
#$config_global['er'][105]['troop3'] = 'Warships';
#$config_global['er'][105]['peasants'] = 'Workers';
$config_global['er'][105]['offense']    =    0.84;
$config_global['er'][105]['defense']    =    1.1;
$config_global['er'][105]['bpt']    =    1;
$config_global['er'][105]['costs']    =    1.06;
$config_global['er'][105]['magic']    =    1;
$config_global['er'][105]['ind']    =    0.9;
$config_global['er'][105]['pci']    =    1.1;
$config_global['er'][105]['expl']    =    1.06;
$config_global['er'][105]['mkt']    =    0.94;
$config_global['er'][105]['food']    =    1;
$config_global['er'][105]['runes']    =    1;
$config_global['er'][105]['farms']    =    1;


$config_global['er'][106]['rname'] = 'Mole';
#$config_global['er'][106]['troop0'] = 'Peltasts';
#$config_global['er'][106]['troop1'] = 'Toxotes';
#$config_global['er'][106]['troop2'] = 'Hoplites';
#$config_global['er'][106]['troop3'] = 'Triremes';
#$config_global['er'][106]['peasants'] = 'Slaves';
$config_global['er'][106]['offense']    =    1.1;
$config_global['er'][106]['defense']    =    0.94;
$config_global['er'][106]['bpt']    =    1;
$config_global['er'][106]['costs']    =    1;
$config_global['er'][106]['magic']    =    0.9;
$config_global['er'][106]['ind']    =    1.1;
$config_global['er'][106]['pci']    =    0.84;
$config_global['er'][106]['expl']    =    1;
$config_global['er'][106]['mkt']    =    1.08;
$config_global['er'][106]['food']    =    0.98;
$config_global['er'][106]['runes']    =    1;
$config_global['er'][106]['farms']    =    1.18;

$config_global['er'][107]['rname'] = 'Hare';
#$config_global['er'][107]['troop0'] = 'Pikemen';
#$config_global['er'][107]['troop1'] = 'Skirmishers';
#$config_global['er'][107]['troop2'] = 'Cavalrymen';
#$config_global['er'][107]['troop3'] = 'Triremes';
#$config_global['er'][107]['peasants'] = 'Slaves';
$config_global['er'][107]['offense']    =    1.14;
$config_global['er'][107]['defense']    =    0.98;
$config_global['er'][107]['bpt']    =    1.04;
$config_global['er'][107]['costs']    =    1.14;
$config_global['er'][107]['magic']    =    0.96;
$config_global['er'][107]['ind']    =    1.08;
$config_global['er'][107]['pci']    =    0.96;
$config_global['er'][107]['expl']    =    1.16;
$config_global['er'][107]['mkt']    =    1;
$config_global['er'][107]['food']    =    0.96;
$config_global['er'][107]['runes']    =    0.86;
$config_global['er'][107]['farms']    =    0.92;

$config_global['er'][108]['rname'] = 'Badger';
#$config_global['er'][108]['troop0'] = 'Spearmen';
#$config_global['er'][108]['troop1'] = 'Archers';
#$config_global['er'][108]['troop2'] = 'Chariots';
#$config_global['er'][108]['troop3'] = 'Triremes';
#$config_global['er'][108]['peasants'] = 'Citizens';
$config_global['er'][108]['offense']    =    1.16;
$config_global['er'][108]['defense']    =    1.08;
$config_global['er'][108]['bpt']    =    1.04;
$config_global['er'][108]['costs']    =    1.08;
$config_global['er'][108]['magic']    =    0.92;
$config_global['er'][108]['ind']    =    1;
$config_global['er'][108]['pci']    =    1;
$config_global['er'][108]['expl']    =    0.88;
$config_global['er'][108]['mkt']    =    1.08;
$config_global['er'][108]['food']    =    0.92;
$config_global['er'][108]['runes']    =    1.06;
$config_global['er'][108]['farms']    =    0.94;

$config_global['er'][109]['rname'] = 'Vole';
#$config_global['er'][109]['troop0'] = 'Spearmen';
#$config_global['er'][109]['troop1'] = 'Slingers';
#$config_global['er'][109]['troop2'] = 'Chariots';
#$config_global['er'][109]['troop3'] = 'Quinqiremes';
#$config_global['er'][109]['peasants'] = 'Workers';
$config_global['er'][109]['offense']    =    0.84;
$config_global['er'][109]['defense']    =    0.94;
$config_global['er'][109]['bpt']    =    1;
$config_global['er'][109]['costs']    =    0.86;
$config_global['er'][109]['magic']    =    0.92;
$config_global['er'][109]['ind']    =    1.14;
$config_global['er'][109]['pci']    =    1;
$config_global['er'][109]['expl']    =    1;
$config_global['er'][109]['mkt']    =    1.08;
$config_global['er'][109]['food']    =    0.92;
$config_global['er'][109]['runes']    =    0.84;
$config_global['er'][109]['farms']    =    1.04;

#Magic/Leader Mission names
$config_global['missionspy']     = 'Espionage';
$config_global['missionblast']     = 'Murder';
$config_global['missionshield'] = 'Raise Defenses';
$config_global['missionstorm']     = 'Poison Crops';
$config_global['missionrunes']     = 'Destroy Runes';
$config_global['missionstruct'] = 'Destroy Structures';
$config_global['missionfood']     = 'Forage';
$config_global['missiongold']     = 'Loot';
$config_global['missioned']     = 'Explore';
$config_global['missionheal']     = 'Heal';
$config_global['missionpeasant'] = 'Recruit';
$config_global['missionprod']     = 'Prod Market';
$config_global['missionkill']     = 'Seppuku';
$config_global['missiongate']     = 'Prepare Hawks';
$config_global['missionungate'] = 'Recall Hawks';
$config_global['missionfight']     = 'Hawk Battle';
$config_global['missionsteal']     = 'Steal Cash';
$config_global['missionrob']     = 'Rob Granaries';
$config_global['missionadvance'] = 'Move North';
$config_global['missionback']     = 'Move South';

$config_global['roundend']     = "Feb. 1, 2006.";# An English representation of the time of the round's end

$config_global['turnsper']     = 3;# And some defaults...
$config_global['perminutes']     = 15;
$config_global['turnbankper']     = 1;
$config_global['bankperminutes'] = 30;
$config_global['maxturnbank']     = 100;
$config_global['turnoffset']        =    0;
$config_global['autolastweek']     =    0;

$config_global['landmult'] = 2.5; #Multiplier for the amount of land gained while scouting
$config_global['cashmult'] = 1.3; #Multiplier for the amount of cash gained while cashing
$config_global['foodmult'] = 1.0; #Multiplier for the amount of food generated while farming
$config_global['runesmult'] = 1.0; #Multiplier for the amount of ruins generated while gathering ruins.
$config_global['indmult'] = 1.5; #Multiplier for for the number of troops produced while recruiting
$config_global['shopmult'] = 1.6; #Multiplier for for the cash gained per shop (market)

#$config_global['micestock'] = 10000000; // Sell/buy 100,000 mice to get a 1% shift in stock price.
#$config_global['sqrlstock'] = 10000000; // need an extra 00 to make it a % shift so a 1+.01 change
#$config_global['harestock'] = 7500000; // occurs correctly.
#$config_global['ottrstock'] = 6000000; // price goes down because they get more expensive
#$config_global['grinstock'] = 25000000; // 250,000 grain for an adjustment minimum.
#$config_global['runestock'] = 2500000; // 25,000 runes sold

####################################
# END OF GLOBAL CONFIGURATION SECTON #
####################################

#######################################
# BEGIN SERVER SPECIFIC CONFIGURATION #
#######################################

#WOA
$config_server[11]['maxturns']     = 500;# Max accumulated turns
$config_server[11]['maxstoredturns'] = 250;# Max stored turns

$config_server[11]['strat_balance'] = 1;# Some changes for strat balancing.
$config_server[11]['signupsclosed'] = 0;# Signups closed?
$config_server[11]['lockdb']     = 0;# Lock the database?
$config_server[11]['lastweek']     = 0;# Last week? (No loans)

$config_server[11]['turnsper']     = 3;# X turns
$config_server[11]['perminutes']     = 15;# per Y minutes
$config_server[11]['turnbankper']     = 1;# And for the turn bank
$config_server[11]['bankperminutes'] = 30;
$config_server[11]['maxturnbank']     = 100;# Maximum size of turn bank
$config_server[11]['turnoffset']     = 0;# Correct for server lag

$config_server[11]['resetvote']     = 1;# Allow reset voting?
$config_server[11]['votepercent']     = 75;# Percent before notifying admins?

#$config_server[11]['news']    = 'If you are a new player, you may find it helpful to visit our <a href="/forum">forums</a>, where you can find anything from general strategy guides to personal teachers.';
$config_server[11]['news'] = '<span class="mnormal">Troops on the public and clan markets now require upkeep and contribute to your networth.</span>';
$config_server[11]['roundend']     = "Never";# An English representation of the time of the round's end
$config_server[11]['disabled_pages'][] = 'stocks';
$config_server[11]['forcestandard'] = 1;

#BFR
$config_server[2]['strat_balance'] = 1;# Some changes for strategy balancing.
$config_server[2]['maxturns']     = 500;# Max accumulated turns
$config_server[2]['maxstoredturns'] = 250;# Max stored turns

$config_server[2]['signupsclosed'] = 0;# Signups closed?
$config_server[2]['lockdb']     = 0;# Lock the database?
$config_server[2]['lastweek']     = 0;# Last week? (No loans)
$config_server[2]['autolastweek']; # Automatically set lastweek

$config_server[2]['forcestandard'] = 1;

$config_server[2]['turnsper']     = 5;# X turns
$config_server[2]['perminutes']     = 10;# per Y minutes
$config_server[2]['turnoffset']     = 0;# Correct for server lag

$config_server[2]['resetvote']     = 1;# Allow reset voting?
$config_server[2]['votepercent']     = 75;# Percent before notifying admins?

$config_server[2]['max_attacks']        =    21;# Maximum attacks for unallied empires

#$config_server[2]['news'] = '<span class="mnormal">Battle for Redwall is a fast-paced game that resets monthly.</span>';
$config_server[2]['news'] = '<span class="mnormal">Troops on the public and clan markets now require upkeep and contribute to your networth. Boats have been modified, but not broken.</span>';

#    $config_server[2]['alt_networth']        =    1;# Use an alternative networth-calculation formula?
#    $config_server[2]['sackmodifier']        =    0.1;# Cash and food attack gains are multiplied by this amount
#    $config_server[2]['towers']        =    1000;
#    $config_server[2]['blddef']        =    100;# Defensive points provided by other buildings

#$config_server[2]['disabled_pages'][]    =    'stocks';
$config_server[2]['roundend']        =    next_month();# An English representation of the time of the round's end

#$config_server[2]['landmult'] = 2.5; #Multiplier for the amount of land gained while scouting
#$config_server[2]['cashmult'] = 1.0; #Multiplier for the amount of cash gained while cashing
#$config_server[2]['foodmult'] = 1.0; #Multiplier for the amount of food generated while farming
#$config_server[2]['runesmult'] = 1.0; #Multiplier for the amount of ruins generated while gathering ruins.

#End BFR

#Duels
$config_server[3]['maxturns']     = 400;# Max accumulated turns
$config_server[3]['maxstoredturns'] = 200;# Max stored turns

$config_server[3]['signupsclosed'] = 1;# Signups closed?
$config_server[3]['lockdb']     = 0;# Lock the database?
$config_server[3]['lastweek']     = 1;# Last week? (No loans)

$config_server[3]['turnsper']     = 1;# X turns
$config_server[3]['perminutes']     = 10;# per Y minutes
$config_server[3]['turnoffset']     = 0;# Correct for server lag

$config_server[3]['resetvote']     = 1;# Allow reset voting?
$config_server[3]['votepercent']     = 75;# Percent before notifying admins?

$config_server[3]['news'] = '<span class="mnormal">Welcome to FAF Duels!</span>';
$config_server[3]['disabled_pages'][] = 'stocks';
$config_server[3]['disabled_pages'][] = 'raffle';
$config_server[3]['disabled_pages'][] = 'clan';
$config_server[3]['disabled_pages'][] = 'clanmanage';
$config_server[3]['disabled_pages'][] = 'delete';
$config_server[3]['disabled_pages'][] = 'bank';
$config_server[3]['disabled_pages'][] = 'raffle';
$config_server[3]['disabled_pages'][] = 'clanjoin';

# ME vs FAF
$config_server[4]['maxturns']     = 4; # Max accumulated turns
$config_server[4]['maxstoredturns'] = 200;# Max stored turns

$config_server[4]['signupsclosed'] = 1;# Signups closed?
$config_server[4]['lockdb']     = 1;# Lock the database?
$config_server[4]['lastweek']     = 1;# Last week? (No loans)

$config_server[4]['turnsper']     = 5;# X turns
$config_server[4]['perminutes'] = 15;# per Y minutes
$config_server[4]['turnoffset'] = 0;# Correct for server lag

$config_server[4]['resetvote'] =    1;# Allow reset voting?
$config_server[4]['votepercent']=    75;# Percent before notifying admins?


$config_server[4]['news'] = '<span class="mnormal">World Cup Finals: ME vs FAF</span>';
$config_server[4]['disabled_pages'][] = 'stocks';
$config_server[4]['disabled_pages'][] = 'raffle';
$config_server[4]['disabled_pages'][] = 'clan';
$config_server[4]['disabled_pages'][] = 'clanmanage';
$config_server[4]['disabled_pages'][] = 'delete';
$config_server[4]['disabled_pages'][] = 'bank';
$config_server[4]['disabled_pages'][] = 'raffle';
$config_server[4]['disabled_pages'][] = 'clanjoin';

# Hall of Fame
$config_server[8]['protection']     = -1;
$config_server[8]['initturns']     = 10000;
$config_server[8]['max_attacks']     = pow(2,20);

$config_server[8]['maxturns']     = 10000;# Max accumulated turns
$config_server[8]['maxstoredturns'] = 0;# Max stored turns

$config_server[8]['signupsclosed'] = 0;# Signups closed?
$config_server[8]['lockdb']     = 0;# Lock the database?
$config_server[8]['lastweek']     = 1;# Last week? (No loans)

$config_server[8]['turnsper']     = 0;# X turns
$config_server[8]['perminutes']     = 10;# per Y minutes
$config_server[8]['turnoffset']     = 0;# Correct for server lag

$config_server[8]['resetvote']     = 0;# Allow reset voting?

$config_server[8]['disabled_pages'][] = 'clans';
$config_server[8]['disabled_pages'][] = 'clan';
$config_server[8]['disabled_pages'][]    =    'clanstats';
$config_server[8]['disabled_pages'][] = 'contacts';
$config_server[8]['disabled_pages'][] = 'clanjoin';

$config_server[8]['news'] = '<span class="mnormal">After spending your initial turns completely, you will be saved to the Hall Of Fame and locked out of your account.<br>You may only sign up for a new account when your current one has died.</span>';
$config_server[8]['nolimit_mode']     = 1;
$config_server[8]['nolimit_table'] = 'faf_hfame';

# Capture the Flag
$config_server[5]['maxturns']     = 400;# Max accumulated turns
$config_server[5]['maxstoredturns'] = 300;# Max stored turns

$config_server[5]['signupsclosed'] = 0;# Signups closed?
$config_server[5]['lockdb']     = 0;# Lock the database?
$config_server[5]['lastweek']     = 1;# Last week? (No loans)

$config_server[5]['turnsper']     = 5;# X turns
$config_server[5]['perminutes']     = 15;# per Y minutes
$config_server[5]['turnoffset']     = 0;# Correct for server lag
$config_server[5]['warset']     = 1;# Allow individual war declarations?

$config_server[5]['resetvote'] = 1; # Allow reset voting?
$config_server[5]['votepercent']    =    75;# Percent before notifying admins?

$config_server[5]['news'] = '<span class="mnormal">The objective is to kill the Flag players for the huge bounties.</span>';
$config_server[5]['disabled_pages'][] = 'stocks';
$config_server[5]['disabled_pages'][] = 'raffle';


$config_server[7]['maxturns']     = 5;# Max accumulated turns
$config_server[7]['maxstoredturns'] = 250;# Max stored turns

$config_server[7]['signupsclosed'] = 1;# Signups closed?
$config_server[7]['lockdb']     = 1;# Lock the database?
$config_server[7]['lastweek']     = 1;# Last week? (No loans)

$config_server[7]['forcestandard'] = 1;

$config_server[7]['turnsper']     = 5; # X turns
$config_server[7]['perminutes']     = 10; # per Y minutes
$config_server[7]['turnoffset']     = 0; # Correct for server lag

$config_server[7]['resetvote'] = 1;# Allow reset voting?
$config_server[7]['votepercent'] = 75;# Percent before notifying admins?

#    $config_server[7]['alt_networth']        =    1;# Use an alternative networth-calculation formula?
#    $config_server[7]['sackmodifier']        =    0.1;# Cash and food attack gains are multiplied by this amount
#    $config_server[7]['towers']        =    1000;
#    $config_server[7]['blddef']        =    100;# Defensive points provided by other buildings
$config_server[7]['disabled_pages'][] = 'stocks';


# Testing
$config_server[9]['initturns']     = 500;
$config_server[9]['maxturns']     = 500;# Max accumulated turns
$config_server[9]['maxstoredturns'] = 0;# Max stored turns
$config_server[9]['max_attacks']     = 200;

$config_server[9]['signupsclosed'] = 1;# Signups closed?
$config_server[9]['lockdb']     = 1;# Lock the database?
$config_server[9]['lastweek']     = 0;# Last week? (No loans)

$config_server[9]['forcestandard'] = 1;

$config_server[9]['multi_max'] = 10;# Maximum number of accounts a player can have

$config_server[9]['turnsper']  = 1; # X turns
$config_server[9]['perminutes'] =    1; # per Y minutes
$config_server[9]['turnoffset'] =    0; # Correct for server lag

$config_server[9]['resetvote']     = 1;# Allow reset voting?
$config_server[9]['votepercent']     = 75;# Percent before notifying admins?

$config_server[9]['max_attacks']     = 30;# Maximum attacks for unallied empires

$config_server[9]['news'] = '<span class="mnormal">Testing server. Up to 5 accounts per player allowed.</span>';

$config_server[9]['disabled_pages'][]    = 'stocks';
$config_server[9]['roundend'] = next_month();# An English representation of the time of the round's end
$config_server[9]['multi_max']        =    5;# Maximum number of accounts a player can have


?>

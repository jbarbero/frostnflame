<?
require_once("funcs.php");
htmlbegincompact("Feature List");
?>
<table width="100%">
<tr><td style="text-align:left">

Broadly speaking, FAF is the result of an effort by 4 --mostly just 2-- programmers over a period of almost 2 years. You can imagine how many features we've added to plain vanilla Promisance, and more importantly, the number of security issues and bugs we've fixed in the original game. We have fixed at least 700 bugs, by rough count, and added 156 big and small new features.<br><br>
When developing FAF, we had in mind 4 main goals:<ul>
<li>A Balanced Game</li>
<li>Ease of Playing</li>
<li>Ease of Development</li>
<li>Eliminating Security Issues</li>
</ul><br>
In this light, our features can be divided up into four main categories: 1. features that ensure balance in the game, between various different strategies; 2. features that make it easy and non-tedious to play a precise yet fun game; 3. features that make it easy for developers to understand and modify the code or the HTML; and 4. a battery of security investigations that have fixed over 30 vulnerabilities in Promisance. So without further ado, here are the most important features:<br>

<ul>
<li>Features That Aim For A Balanced Game
<ul>
<li>Attack for Food, Cash, or Land</li>
<li>Market commission: networth under 10 mil: 10%, under 20: 15%, under 50: 20%, under 100: 25%, all above: 30%</li>
<li>More stringent cap on Scout (ED)</li>
<li>Experience factors into attack strength</li>
<li>Declare War</li>
<li>Declare Peace (Ally)</li>
<li>Use turns: Train</li>
<li>Use turns: Write</li>
<li>Value of food decreased in networth</li>
<li>Races balanced more</li>
<li>Treasury can be Open/Closed</li>
<li>Market sales go into the Bank</li>
</ul><br></li>

<li>Features That Ease The Playing Experience
<ul>
<li>Espionage: Report</li>
<li>Condense Turns option that remembers your selection</li>
<li>Execute multiple missions at once</li>
<li>Demolish: "safe" options</li>
<li>News Condensation</li>
<li>Military, Aid, Messages dropdown boxes added</li>
<li>Military dropdown boxes colorized</li>
<li>Aid, Bazaar, Market, and Bounty pages have Max/All checkboxes</li>
<li>Lite or Full Menus</li>
<li>Picking location at signup</li>
<li>News reports kills</li>
<li>Refresh link changed to javascript to resend form data</li>
<li>People can resurrect their old clan if they know the password for it</li>
<li>The statusbar's cash and food indicators turn yellow if you are losing cash/food, and red if the rate is critical</li>
<li>In-Game clan forums, integrated with main page</li>
<li>Game, Server, and Clan chats (using revolutionary low-bandwidth no-latency, pure HTML and JavaScript technology)</li>
<li>Messages, clan MOTD and crier, clan forums, all use bbcode parsing</li>
<li>New and advanced title-based PM system</li>
<li>Open Clans that can be joined on signup (clan leader can set this)</li>
<li>Login option to hide online-ness</li>
<li>Online attacks are marked by an asterisk</li>
<li>Market items are displayed using a visual bar for delay from market</li>
<li>Profile page with public information and recent news, as well as a user's private profile</li>
<li>Clan crier page with public information and recent news, as well as the clan's crier info</li>
<li>Status bar shows turns left in Protection</li>
<li>New Mail is loaded in JavaScript so you can read it fast</li>
<li>Clan Granary, Treasury, and Loft</li>
<li>Clan Market</li>
<li>Military, Aid, and Hawks auto-select your last target</li>
<li>View complete news history</li>
<li>Message Entire Clan option</li>
<li>10 message credits by default</li>
<li>No-Cookies login method</li>
<li>More clan diplomacy spots</li>
<li>pconnect() means using many turns is much faster</li>
<li>Private notepad</li>
<li>"Show All" on scores page</li>
<li>Condensation of market items</li>
<li>Option to leave protection early</li>
<li>On the scores page, clan leaders are distinguished with asterisks around their clan's tag</li>
<li>User-choosable news sorting</li>
</ul><br></li>

<li>Miscellaneous Features
<ul>
<li>The Map</li>
<li>Raffle instead of Lottery</li>
<li>Source Viewer (in accordance with our policy of openness about development)</li>
<li>Removed validation; replaced with password generation</li>
<li>Rearranged status page (was illogical before)</li>
<li>Combo attack: Pincer Move: uses Hares and Otters</li>
<li>Combo attack: Sniper Assault: uses Squirrels and Otters</li>
<li>Stock Market (extensively tested to be realistic)</li>
<li>Heroes system</li>
<li>Bounty System</li>
<li>New mission: Heal</li>
<li>New mission: Recruit</li>
<li>New mission: Seppuku</li>
<li>New mission: Prod Market</li>
<li>New mission: Scout</li>
<li>Missions dropdown box shows missions in yellow or red (yellow: not enough runes / red: would fail)
<li>News page by default shows the last 100 news items</li>
<li>Shorter URLs</li>
<li>Graveyard, Hall of Shame, Admin List</li>
<li>Top10 page skips vacation people</li>
<li>Option to change email</li>
<li>In-Game names</li>
</ul><br></li>

<li>Features That Ease Administration and Development
<ul>
<li>Transparent server installation</li>
<li>Era & race matrix (fine-grained control)</li>
<li>Arbitrary number of troop types</li>
<li>bbcode_parse function</li>
<li>swear_filter function (fast & efficient)</li>
<li>Rewrote News system to be more logical and comprehensible</li>
<li>Optional forum news syndication on main page</li>
<li>More flexible naming (for example to replace 'empire')</li>
<li>Disabled pages array (tied in with new menu system)</li>
<li>Ease of configuring and adding multiple servers</li>
<li>The login page automatically accomodates any number of servers</li>
<li>Stock Market Manipulator</li>
<li>Missions system completely rewritten for flexibility</li>
<li>Many signup variables swear filtered</li>
<li>Table and cookie prefixing</li>
</ul><br></li>

<li>Fixed Security Holes and Promisance Bugs
<ul>
<li>HTTP_REFERER bug</li>
<li>REMOTE_ADDR bug</li>
<li>HTTP_HOST login problem</li>
<li>Clan inheritance fixed</li>
<li>HTMLEntities instead of HTMLSpecialChars</li>
<li>Can't attack or execute missions under 20% health</li>
<li>Shared Forces bug fixed</li>
<li>In Promisance, "@." was a valid signup email</li>
<li>HTML could be inserted in clan image or link</li>
<li>Logout removes your online status, as it should</li>
<li>Build and Demolish no turns used bug fixed</li>
</ul><br></li>
</ul>

</td></tr>
</table>

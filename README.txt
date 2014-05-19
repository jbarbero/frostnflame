Installation is super easy:

1. Unzip on your web server

2. Copy config.php to local/ and edit it to set up your server.

Directives are documented within the file.
All you need to do to get a running game is to change the pertinent MySQL variables, but you may customize the game to your liking in detail if you wish.
You do not need to do anything in the database, servers and everything are transparently installed.
      Note: if you have MySQL troubles, double-check your connection info
      and try setting $config['pconnect'] to 0 in config.php

3. Congratulations! FAF is installed. Just browse to the game directory and log in with:
username: admin
password: password

BE SURE TO IMMEDIATELY CHANGE YOUR ADMIN PASSWORD ON ALL SERVERS!


###################################### Notes for Developers ######################################
1. If you wish to make changes to the codebase, it is recommended that you copy the files you want
   to modify into the same path in the ./local directory, and edit them there. For example, if you
   change military.php, you would copy military.php to local/military.php and change it there. It
   will be transparently included by the game. Then an upgrade is trivial as your changes will
   never be overwritten. Some other examples:
      lib/libclan.php            -> local/lib/libclan.php
      templates/prom/login.html  -> local/templates/prom/login.html
      minibb/lang/eng.php        -> local/minibb/lang/eng.php
      guide/Aid.2                -> local/guide/Aid.2
   etcetera.
2. Since FAF is released under the GPL, any modifications you make must be released to any party
   requesting them, unless doing so would conflict with the license of another project you took
   code from. This must not be construed as legal advice!
##################################################################################################


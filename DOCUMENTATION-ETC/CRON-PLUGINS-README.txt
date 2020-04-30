

##########################################################################################
CREATING CUSTOM CRON PLUGINS
##########################################################################################


Take advantage of this app's built-in functions / classes, and your config settings (alert comm channels setup, etc) to create your own cron plugins WITH MINIMAL CODING REQUIRED. Your plugin will run during normally-scheduled cron job runtimes (after the charts / price alerts / everything else runs).


STEPS TO CREATE YOUR OWN CRON PLUGIN...


1) Create a new subdirectory inside /cron-plugins/ in the primary directory of this app, and name it after your plugin name (lowercase / snake case: "my_cron_plugin")


2) Create a blank file within this new subdirectory, with the SAME EXACT NAME as the directory, plus the file extension .php

So you should have this so far (for the path to the cron plugin file):

/cron-plugins/YOUR_PLUGIN_NAME/YOUR_PLUGIN_NAME.php


3) Inside config.php (in the primary directory of this app), find the configuration section called "POWER USER SETTINGS"


4) Find the configuration variable within this section named: $app_config['power_user']['activate_cron_plugins']


5) To activate your new plugin, add 'YOUR_PLUGIN_NAME' as a new array value within $app_config['power_user']['activate_cron_plugins']


Now you are ready to write your custom plugin code in PHP, inside the new plugin file /cron-plugins/YOUR_PLUGIN_NAME/YOUR_PLUGIN_NAME.php, which will run everytime a normally-scheduled cron job runs for this app. See the example code in the included example cron plugin "hns-airdrop" inside the /cron-plugins/ directory.


IMPORTANT NOTES:

NEVER ADD A CRON PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!

ALWAYS TEST YOUR CODE, TO MAKE SURE IT DOESN'T CRASH THE CRON JOB. CUSTOM CRON PLUGINS DO RUN #LAST# WITHIN THE CRON RUNTIME THOUGH (AND THEREFORE ARE #NOT# INCLUDED IN RUNTIME STATS DATA LIKE HOW MANY SECONDS IT RAN), SO EVEN IF YOUR CUSTOM PLUGIN CRASHES, #EVERYTHING ELSE# IMPORTANT RAN BEFOREHAND ANYWAY.


##########################################################################################
CREATING CUSTOM CRON PLUGINS
##########################################################################################


Take advantage of this app's built-in functions / classes, and your config settings (alert comm channels setup, etc) to create your own cron plugins WITH MINIMAL CODING REQUIRED. Your plugin will run during normally-scheduled cron job runtimes (after the charts / price alerts / everything else runs).


STEPS TO CREATE YOUR OWN CRON PLUGIN...


1) Create a new subdirectory inside the /cron-plugins/ directory of this app, and name it after your plugin name.
(lowercase: "my-cron-plugin")


2) Inside your new cron plugin folder, create two sub-directories / sub-folders named 'app' and 'config'. 
IF YOUR APP DOES NOT NEED CONFIG SETTINGS FOR USER SETUP, YOU CAN SKIP CREATING A 'config' directory, AND SKIP STEP #4 AND #5.
(lowercase: "my-cron-plugin/app/" AND "my-cron-plugin/config/")


3) Create a blank APP file within the new subdirectory /app/, with the SAME EXACT NAME as the MAIN plugin directory, plus the suffix and extension "-app.php".
(lowercase: "my-cron-plugin/app/my-cron-plugin-app.php")


4) Create a blank CONFIG file within the new subdirectory /config/, with the SAME EXACT NAME as the MAIN plugin directory, plus the suffix and extension "-config.php".
(lowercase: "my-cron-plugin/config/my-cron-plugin-config.php")


5) All "my-cron-plugin/config/my-cron-plugin-config.php" PLUGIN CONFIG settings MUST BE INSIDE an array EXACTLY-NAMED: $app_config['cron_plugins'][$cron_plugin_name]


6) Inside THE MAIN config.php file (in the primary directory of this app), find the configuration section called "POWER USER SETTINGS"


7) Find the configuration variable within this section named: $app_config['power_user']['activate_cron_plugins']


8) To activate your new plugin, add your plugin name 'my-cron-plugin' as a new array value within $app_config['power_user']['activate_cron_plugins'], set to 'on'.


Now you are ready to write your custom plugin code in PHP, inside the new plugin files you created, which will run everytime a normally-scheduled cron job runs for this app. See the example code in the included cron plugins inside the /cron-plugins/ directory, for useful code snippets to speed up your plugin development.


IMPORTANT NOTES:

!!NEVER ADD A CRON PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!

ALWAYS TEST YOUR CODE, TO MAKE SURE IT DOESN'T CRASH THE CRON JOB. CUSTOM CRON PLUGINS DO RUN #LAST# WITHIN THE CRON RUNTIME THOUGH (AND THEREFORE ARE #NOT# INCLUDED IN RUNTIME STATS DATA LIKE HOW MANY SECONDS IT RAN / SYSTEM LOAD), SO EVEN IF YOUR CUSTOM PLUGIN CRASHES, #EVERYTHING ELSE# IMPORTANT RAN BEFOREHAND ANYWAY.




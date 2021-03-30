

##########################################################################################
CREATING CUSTOM PLUGINS
##########################################################################################


IMPORTANT NOTICE: PLUGINS *MAY REQUIRE* A CRON JOB RUNNING ON YOUR WEB SERVER (see README.txt for cron job setup information). CURRENTLY *ONLY CRON PLUGINS ARE SUPPORTED* (INTERFACE PLUGINS ARE COMING IN THE FUTURE).


Take advantage of this app's built-in functions / classes, and your config settings (alert comm channels setup, etc) to create your own custom plugins WITH MINIMAL CODING REQUIRED, to add features to this app.


STEPS TO CREATE YOUR OWN PLUGIN...


1) Create a new subdirectory inside the main /plugins/ directory of this app, and name it after your plugin name.

example: "/plugins/my-app-plugin/" (must be lowercase)



2) Create a new subdirectory inside the new plugin directory created in step #1, named "plug-lib".

example: "/plugins/my-app-plugin/plug-lib/" (must be lowercase)



3) Create a blank INIT file (plugin runtime starts here) inside the new "plug-lib" directory created in step #2, with the name "plug-init.php".

example: "/plugins/my-app-plugin/plug-lib/plug-init.php" (must be lowercase)



4) OPTIONALLY create a blank CLASS file (custom class logic goes here for auto-inclusion in a new automatically-created class), inside the new "plug-lib" directory created in step #2, with the name "plug-class.php".

example: "/plugins/my-app-plugin/plug-lib/plug-class.php" (must be lowercase)



5) All ADDED LOGIC in the "plug-class.php" file will now be AUTO-INCLUDED IN A NEW CLASS NAMED "$plug_class[$this_plug]" (NO NEED to create the class yourself).


class object examples: 

var my_var_1;

function my_function_1() {
echo 'Hello world!';
}

--

calling the class object examples (ANYWHERE FROM init.php ONWARDS):

$test_1 = $plug_class[$this_plug]->my_var_1;

$test_2 = $plug_class[$this_plug]->my_function_1();



6) Create a blank CONFIG file (plugin configs go here) inside the new plugin directory created in step #1, with the name "plug-conf.php".

example: "/plugins/my-app-plugin/plug-conf.php" (must be lowercase)



7) All "plug-conf.php" PLUGIN CONFIG settings MUST BE INSIDE THE ARRAY "$plug_conf[$this_plug]" (sub-arrays are allowed).

example: $plug_conf[$this_plug]['SETTING_NAME_HERE'] = 'mysetting';

example: $plug_conf[$this_plug]['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');



8) The "plug-conf.php" PLUGIN CONFIG SETTING 'runtime_mode' IS MANDATORY, to determine WHEN the plugin should run (during cron jobs / user interface loading / all runtimes / etc).

example: $plug_conf[$this_plug]['runtime_mode'] = 'cron'; // 'cron', 'ui', 'all' (only 'cron' supported as of 2020-10-29)


9) We are now done setting up plugin files, now we need to activate the new plugin. IN THE MAIN APP "Admin Config" POWER USER section. Locate the configuration variable named: 'activate_plugins'


10) To add / activate your new plugin, add your plugin name (example: 'my-app-plugin') as a new value within 'activate_plugins', and set to 'on'.

example: 'my-app-plugin' => 'on' 



Now you are ready to write your custom plugin code in PHP, inside the new plugin files you created, which will run everytime a normally-scheduled cron job runs for this app. See the example code in the included plugins inside the /plugins/ directory, for useful code snippets to speed up your plugin development.


IMPORTANT NOTES:

!!NEVER ADD A CRON PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!

"plug-conf.php" files are loaded on main app initiation, so they can be included in the GLOBAL cached app config (allowing the editing of these config settings in the admin interface, etc). 

"plug-init.php" files are where plugins first start loading from, so you edit these files like you would the first file containing the programming logic for your plugin. You are free to add and include more files / folders inside your plugin main folder, in the same way you would build an ordinary application. Any config settings you have in "plug-conf.php" are automatically available to use in "plug-init.php", and in any other plugin files you create that run within / after the initial "plug-init.php" logic.

CRON-DESIGNATED PLUGINS (PLUGINS FLAGGED TO RUN DURING CRON JOBS) DO RUN #LAST# WITHIN THE CRON RUNTIME (AND THEREFORE ARE #NOT# INCLUDED IN RUNTIME STATS DATA LIKE HOW MANY SECONDS IT RAN / SYSTEM LOAD), SO EVEN IF YOUR CUSTOM PLUGIN CRASHES, #EVERYTHING ELSE# IMPORTANT RAN BEFOREHAND ANYWAY.

ALWAYS TEST YOUR CODE, TO MAKE SURE IT DOESN'T CRASH THE APP.




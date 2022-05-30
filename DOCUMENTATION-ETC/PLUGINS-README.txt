

##########################################################################################
CREATING CUSTOM PLUGINS
##########################################################################################


IMPORTANT NOTICE: PLUGINS *MAY REQUIRE* A CRON JOB RUNNING ON YOUR WEB SERVER (see README.txt for cron job setup information).


Take advantage of this app's built-in functions / classes, and your config settings (alert comm channels setup, etc) to create your own custom plugins WITH MINIMAL CODING REQUIRED, to add features to this app.


STEPS TO CREATE YOUR OWN PLUGIN...


1) Create a new subdirectory inside the main /plugins/ directory of this app, and name it after your plugin name.

Example: "/plugins/my-app-plugin/" (must be lowercase)



2) Create a new subdirectory inside the new plugin directory created in step #1, named "plug-lib".

Example: "/plugins/my-app-plugin/plug-lib/" (must be lowercase)



3) OPTIONALLY, create a new subdirectory inside the new plugin directory created in step #1, named "plug-assets".

Example: "/plugins/my-app-plugin/plug-assets/" (must be lowercase)

THIS IS #REQUIRED TO BYPASS THE USUAL SECURITY# OF OTHER-NAMED DIRECTORIES, SO IMAGES / JAVASCRIPT / CSS / ETC CAN BE LOADED #ONLY FROM HERE#...OTHERWISE ANY DIFFERENT-NAMED ASSETS DIRECTORY #WILL BE DENIED ACCESS# OVER HTTP / HTTPS!



4) Create a blank INIT file (plugin runtime starts here) inside the new "plug-lib" directory created in step #2, with the name "plug-init.php".

Example: "/plugins/my-app-plugin/plug-lib/plug-init.php" (must be lowercase)



5) OPTIONALLY create a blank CLASS file (custom class logic goes here), inside the new "plug-lib" directory created in step #2, with the name "plug-class.php".

Example: "/plugins/my-app-plugin/plug-lib/plug-class.php" (must be lowercase)



6) All ADDED LOGIC in the "plug-class.php" file can be AUTO-INCLUDED IN A NEW CLASS NAMED "$plug_class[$this_plug]" USING THIS FORMAT BELOW:


// CREATE THIS PLUGIN'S CLASS OBJECT DYNAMICALLY AS:

$plug_class[$this_plug] = new class() {

var my_var_1 = 'Testing 123';
var my_var_2 = 'World';

	function my_function_1($var) {
	return ' Hello ' . $var . '! ';
	}
				
};
// END class

--

Examples of calling plugin class objects (ANYWHERE FROM WITHIN "plug-init.php" ONWARDS):

echo $plug_class[$this_plug]->my_var_1;

echo $plug_class[$this_plug]->my_function_1( $plug_class[$this_plug]->my_var_2 );

echo $plug_class[$this_plug]->my_function_1('Kitty');



7) Create a blank CONFIG file (plugin configs go here) inside the new plugin directory created in step #1, with the name "plug-conf.php".

Example: "/plugins/my-app-plugin/plug-conf.php" (must be lowercase)



8) All "plug-conf.php" PLUGIN CONFIG settings MUST BE INSIDE THE ARRAY "$plug_conf[$this_plug]" (sub-arrays are allowed).

Example: $plug_conf[$this_plug]['SETTING_NAME_HERE'] = 'mysetting';

Example: $plug_conf[$this_plug]['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');



9) The "plug-conf.php" PLUGIN CONFIG SETTING 'runtime_mode' IS MANDATORY, to determine WHEN the plugin should run (during cron jobs / user interface loading / all runtimes / etc).

Example: $plug_conf[$this_plug]['runtime_mode'] = 'cron'; // 'cron', 'ui', 'all'



10) The "plug-conf.php" PLUGIN CONFIG SETTING 'ui_location' IS OPTIONAL, to determine WHERE the plugin should run (on the tools page, in the 'more stats' section, etc...defaults to 'tools' if not set).

Example: $plug_conf[$this_plug]['ui_location'] = 'tools'; // 'tools', 'more_stats'



11) The "plug-conf.php" PLUGIN CONFIG SETTING 'ui_name' IS OPTIONAL, to determine THE NAME the plugin should show as to end-users (defaults to $this_plug if not set).

Example: $plug_conf[$this_plug]['ui_name'] = 'My Plugin Name';



12) We are now done setting up plugin files, now we need to activate the new plugin. IN THE MAIN APP "Admin Config" POWER USER section. Locate the configuration variable named: 'activate_plugins'


13) To add / activate your new plugin, add your plugin name (example: 'my-app-plugin') as a new value within 'activate_plugins', and set to 'on'...ALSO INCLUDE A COMMA AT THE END.

Example: 'my-app-plugin' => 'on',



Now you are ready to write your custom plugin code in PHP, inside the new plugin files you created. See the example code in the included plugins inside the /plugins/ directory, for useful code snippets to speed up your plugin development.


IMPORTANT NOTES:

!!NEVER ADD A PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!

"plug-conf.php" files are loaded on main app initiation, so they can be included in the GLOBAL cached app config (allowing the editing of these config settings in the admin interface, etc). 

"plug-init.php" files are where plugins first start loading from, so you edit these files like you would the first file containing the programming logic for your plugin. You are free to add and include more files / folders inside your plugin main folder, in the same way you would build an ordinary application. Any config settings you have in "plug-conf.php" are automatically available to use in "plug-init.php", and in any other plugin files you create that run within / after the initial "plug-init.php" logic.

CRON-DESIGNATED PLUGINS (PLUGINS FLAGGED TO RUN DURING CRON JOBS) DO RUN #LAST# WITHIN THE CRON RUNTIME (AND THEREFORE ARE #NOT# INCLUDED IN RUNTIME STATS DATA LIKE HOW MANY SECONDS IT RAN / SYSTEM LOAD), SO EVEN IF YOUR CUSTOM PLUGIN CRASHES, #EVERYTHING ELSE# IMPORTANT RAN BEFOREHAND ANYWAY.

ALWAYS TEST YOUR CODE, TO MAKE SURE IT DOESN'T CRASH THE APP.




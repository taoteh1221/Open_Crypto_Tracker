

##########################################################################################
CREATING CUSTOM PLUGINS
##########################################################################################


IMPORTANT NOTICE: PLUGINS *MAY REQUIRE* A CRON JOB (OR SCHEDULED TASK) RUNNING ON YOUR APP SERVER (see README.txt for cron job setup information).


Take advantage of this app's built-in functions / classes, and your config settings (alert comm channels setup, etc) to create your own custom plugins WITH MINIMAL CODING REQUIRED, to add features to this app.


STEPS TO CREATE YOUR OWN PLUGIN...


1) Create a new subdirectory inside the main /plugins/ directory of this app, and name it after your plugin name.

Example: "/plugins/my-app-plugin/" (must be lowercase)



2) Create a new subdirectory inside the new plugin directory created in step #1, named "plug-lib".

Example: "/plugins/my-app-plugin/plug-lib/" (must be lowercase)



3) Create a blank INIT file (plugin runtime starts here) inside the new "plug-lib" directory created in step #2, with the name "plug-init.php".

Example: "/plugins/my-app-plugin/plug-lib/plug-init.php" (must be lowercase)



4) OPTIONALLY create a blank CLASS file (custom class logic goes here), inside the new "plug-lib" directory created in step #2, with the name "plug-class.php".

Example: "/plugins/my-app-plugin/plug-lib/plug-class.php" (must be lowercase)



5) All ADDED LOGIC in this "plug-class.php" file is AUTO-INCLUDED IN A NEW CLASS NAMED "$plug['class'][$this_plug]" USING THIS FORMAT BELOW...


// CREATES THIS PLUGIN'S CLASS OBJECT DYNAMICALLY AS:

$plug['class'][$this_plug] = new class() {

var my_var_1 = 'Testing 123';
var my_var_2 = 'World';

	function my_function_1($var) {
	return ' Hello ' . $var . '! ';
	}
				
};
// END class

--

Examples of calling plugin class objects (ANYWHERE FROM WITHIN "plug-init.php" ONWARDS):

echo $plug['class'][$this_plug]->my_var_1;

echo $plug['class'][$this_plug]->my_function_1( $plug['class'][$this_plug]->my_var_2 );

echo $plug['class'][$this_plug]->my_function_1('Kitty');


ADDING USER-INPUT VALIDATION FOR THE PLUGIN'S ADMIN SETTINGS PAGE:

To AUTOMATICALLY INCLUDE your custom user-input validation logic for your plugin's admin settings page (created in step 14 below), add the EXACT function name "admin_input_validation" into your class file mentioned above:

$plug['class'][$this_plug] = new class() {
     
     // Validating user input in the admin interface
	function admin_input_validation() {
		 
	global $ct, $plug, $this_plug;
		
     // Logic here
     $ct['update_config_error'] = ''; // No input errors
     
     $ct['update_config_error'] = 'Input error description goes here'; // An error has ocurred
     
     return $ct['update_config_error'];
		
	}
				
};
// END class

--

If $plug['class'][$this_plug]->admin_input_validation() returns false / null / '' (set blank), then the app will consider the user-input VALIDATED. OTHERWISE, it will halt updating of your plugin's settings, and show the end-user your error message in the user interface.



6) Create a blank PLUGIN CONFIG file (plugin configs go here) inside the new plugin directory created in step #1, with the name "plug-conf.php".

Example: "/plugins/my-app-plugin/plug-conf.php" (must be lowercase)

NOTES: plug-conf.php MUST only contain STATIC VALUES (dynamic values are NOT allowed), as all plugin configs are saved to / run from cache file: /cache/secured/ct_conf_XXXXXXXXX.dat That said, you CAN create a "placeholder" (empty) configuration value / array in plug-conf.php (for clean / reviewable code), and then dynamically populate it AT THE TOP OF your plug-init.php logic (BEFORE your plugin needs to use that config setting).



7) The PLUGIN VERSION is MANDATORY (to properly handle upgrades / downgrades), and MUST be included in the PLUGIN CONFIG file you just created.

Example:

// Version number of this plugin (MANDATORY)
$ct['plug_version'][$this_plug] = '1.01.00';



8) All PLUGIN CONFIG settings MUST BE INSIDE THE ARRAY "$plug['conf'][$this_plug]" (sub-arrays are allowed).

Example: $plug['conf'][$this_plug]['SETTING_NAME_HERE'] = 'mysetting';

Example: $plug['conf'][$this_plug]['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');



9) The PLUGIN CONFIG SETTING 'runtime_mode' IS MANDATORY (plugin WILL NOT be allowed to activate if invalid / blank), to determine WHEN the plugin should run (as a webhook / during cron jobs / user interface loading / all runtimes / etc).

Example: $plug['conf'][$this_plug]['runtime_mode'] = 'cron'; // 'cron', 'webhook', 'ui', 'all'

When 'runtime_mode' is set to 'webhook', you can pass ADDITIONAL parameters (forwardslash-delimited) *AFTER* THE WEBHOOK KEY in the webhook URL:

https://mydomain.com/hook/WEBHOOK_KEY/PARAM1/PARAM2/PARAM3/ETC

These parameters are then automatically put into a PHP array named: $webhook_params

The webhook key is also available, in the auto-created variable: $webhook_key



10) The PLUGIN CONFIG SETTING 'ui_location' IS OPTIONAL, to determine WHERE the plugin should run (on the tools page, in the 'more stats' section, etc...defaults to 'tools' if not set).

Example: $plug['conf'][$this_plug]['ui_location'] = 'tools'; // 'tools', 'more_stats'



11) The PLUGIN CONFIG SETTING 'ui_name' IS OPTIONAL, to determine THE NAME the plugin should show as to end-users (defaults to $this_plug if not set).

Example: $plug['conf'][$this_plug]['ui_name'] = 'My Plugin Name';



12) ADDITIONALLY, if you wish to trigger a RESET on any particular plugin settings during config upgrades (for ACTIVATED plugins), include an array named $ct['dev']['plugin_allow_resets'][$this_plug] WITH YOUR PLUGIN CONFIG SETTINGS. You MUST include the PLUGIN VERSION NUMBER for when the reset began being needed during upgrades, for reliable upgrading / downgrading of EXISTING plugin installations.

Example: 

// FULL RESET(s) on specified settings (CAN be an arrays), ONLY IF plugin version has changed
$ct['dev']['plugin_allow_resets'][$this_plug] = array(
                                                      // key id, and plugin version number of when the reset was added
                                                      // NO DUPLICATES, REPLACE KEY'S VALUE WITH LATEST AFFECTED VERSION!
                                                      'plugin-setting-key-1' => '0.90.00',
                                                      'plugin-setting-key-2' => '1.23.45',
                                                      );

This will COMPLETELY RESET these plugin settings (ONLY IF THE PLUGIN VERSION HAS CHANGED), using the DEFAULT settings in the currently-installed version of the plugin, during upgrade checks on the cached config.



13) OPTIONALLY, create a new subdirectory inside the new plugin directory created in step #1, named "plug-assets".

Example: "/plugins/my-app-plugin/plug-assets/" (must be lowercase)

THIS IS #REQUIRED TO BYPASS THE USUAL SECURITY# OF OTHER-NAMED DIRECTORIES, SO IMAGES / JAVASCRIPT / CSS / ETC CAN BE LOADED #ONLY FROM HERE#...OTHERWISE ANY DIFFERENT-NAMED ASSETS DIRECTORY #WILL BE DENIED ACCESS# OVER HTTP / HTTPS!



14) OPTIONALLY, create a new subdirectory inside the new plugin directory created in step #1, named "plug-templates".

Example: "/plugins/my-app-plugin/plug-templates/" (must be lowercase)



15) OPTIONALLY create a blank ADMIN TEMPLATE file (admin interface settings go here), inside the new "plug-templates" directory created in step #14, with the name "plug-admin.php".

Example: "/plugins/my-app-plugin/plug-templates/plug-admin.php" (must be lowercase)

IMPORTANT NOTES: Since 'plug_version' / 'runtime_mode' / 'ui_location' / 'ui_name' (mentioned further up in steps 9, 10, and 11) are DEVELOPER settings, THEY ARE *AUTOMATICALLY* HIDDEN IN THIS ADMIN INTERFACE YOU CREATE (they are rendered as HIDDEN fields in the admin page's form data). See the bundled plugins for examples on choosing different HTML form field types to render your specific settings. All form field types are available to AUTOMATICALLY RENDER your settings for end-user updating, via this admin interface template.



16) OPTIONALLY create a blank DOCUMENTATION TEMPLATE file (usage / documentation for end-user goes here [and is automatically linked at the top of this plugin's admin page]), inside the new "plug-templates" directory created in step #14, with the name "plug-docs.php".

Example: "/plugins/my-app-plugin/plug-templates/plug-docs.php" (must be lowercase)



17) We are done setting up the plugin files / folders, so now we need to activate the new plugin. IN THE "Admin Config" PLUGINS section, locate the plugins list.



18) To add / activate your new plugin IN CONFIG.PHP (only required in high security admin mode), add your plugin MAIN FOLDER name (example: 'my-app-plugin') as a new value within the plugins list, and set to 'on'...ALSO INCLUDE A COMMA AT THE END.

Example: 'my-app-plugin' => 'on',

Otherwise, your new plugin should automatically show in the admin 'Plugins' section, defaulted to 'off'. Just enable it there.



Now you are ready to write your custom plugin code in PHP, inside the new plugin files you created. See the example code in the included plugins inside the /plugins/ directory, for useful code snippets to speed up your plugin development.


IMPORTANT NOTES:

!!NEVER ADD A PLUGIN SOMEBODY ELSE WROTE, UNLESS YOU OR SOMEONE YOU TRUST HAVE REVIEWED THE CODE AND ARE ABSOLUTELY SURE IT IS NOT MALICIOUS!!

"plug-conf.php" files are loaded on main app initiation, so they can be included in the GLOBAL cached app config (allowing the editing of these config settings in the admin interface, etc). 

"plug-init.php" files are where plugins first start loading from, so you edit these files like you would the first file containing the programming logic for your plugin. You are free to add and include more files / folders inside your plugin main folder, in the same way you would build an ordinary application. Any config settings / class functions and variables you have in "plug-conf.php" and "plug-lib/plug-class.php" are automatically available to use in "plug-init.php", and in any other plugin files you create that run within / after the initial "plug-init.php" logic.

CRON-DESIGNATED PLUGINS (PLUGINS FLAGGED TO RUN DURING CRON JOBS) DO RUN #LAST# WITHIN THE CRON RUNTIME (AND THEREFORE ARE #NOT# INCLUDED IN RUNTIME STATS DATA LIKE HOW MANY SECONDS IT RAN / SYSTEM LOAD), SO EVEN IF YOUR CUSTOM PLUGIN CRASHES, #EVERYTHING ELSE# IMPORTANT RAN BEFOREHAND ANYWAY.

ALWAYS TEST YOUR CODE, TO MAKE SURE IT DOESN'T CRASH THE APP.




<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// All "plugin-config.php" PLUGIN CONFIG settings MUST BE INSIDE THE "$plugin_config" ARRAY (sub-arrays are allowed)

// EXAMPLES...

// $plugin_config['SETTING_NAME_HERE'] = 'mysetting'; 

// $plugin_config['SETTING_NAME_HERE'] = array('mysetting1', 'mysetting2');


// What runtime modes this plugin should run during (MANDATORY)
$plugin_config['runtime_mode'] = 'cron'; // 'cron', 'ui', 'all' (only 'cron' supported as of 2020-10-29)


?>



<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


// No archive password, and we skip directories named 'system' / 'light',
// AND any assets NOT in the default config
$ct['cache']->backup_archive(
		                    'charts-bootstrapping',
		                    $ct['base_dir'] . '/cache/charts/',
		                    $ct['conf']['power']['backup_archive_frequency'],
		                    false,
		                    // Comma delimited, to exclude more than one dirname
		                    $plug['class'][$this_plug]->excluded_backup_dirs() 
		                    ); 


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
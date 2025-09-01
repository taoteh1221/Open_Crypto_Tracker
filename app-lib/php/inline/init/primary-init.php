<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// PRIMARY INIT 
//////////////////////////////////////////////////////////////////

     
// Adjust CSS for LINUX PHPDESKTOP or ALL OTHER browsers
if ( $ct['app_container'] == 'phpdesktop' && $ct['app_platform'] == 'linux' ) {
$ct['dev']['small_font_size_css_selector'] .= $ct['dev']['small_font_size_css_selector_adjusted'];
$ct['dev']['tiny_font_size_css_selector'] .= $ct['dev']['tiny_font_size_css_selector_adjusted'];
}
else {
$ct['dev']['font_size_css_selector'] .= $ct['dev']['small_font_size_css_selector_adjusted'];
}
     

// Get any detected php.ini (for informative error messages)
$php_ini_path = php_ini_loaded_file();


// PHPbrowserBox BUILDS *FROM* from a SEPERATE ini file *TO* the
// one that PHP uses (https://github.com/dhtml/phpbrowserbox/wiki/Tweaks)
// (so we want people to edit that ini file instead, which updates PHP's used ini file)
if ( $ct['app_container'] == 'phpbrowserbox' ) {
$php_ini_path = preg_replace("/php\.ini/", "php-tpl.ini", $php_ini_path);
}


// ESSENTIAL REQUIRED LIB FILES...

// Config file check (MUST RUN *BEFORE* LOADING CONFIG.PHP [TO CHECK FOR PARSE / FATAL ERRORS])
require_once($ct['base_dir'] . '/app-lib/php/inline/debugging/config-check.php');

// Load the hard-coded (default) config BEFORE #ANYTHING ELSE#
require_once("config.php");

// Basic system checks (MUST RUN *BEFORE* LOADING CLASSES [TO CHECK FOR REQUIRED PHP EXTENSIONS])
require_once($ct['base_dir'] . '/app-lib/php/inline/system/system-checks.php');

// Load app classes AFTER SYSTEM CHECKS (before loading cached conf, after config.php)
require_once($ct['base_dir'] . '/app-lib/php/classes/extended-classes-loader.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/core-classes-loader.php');

// Vars, #MUST# BE SET IMMEADIATELY AFTER loading core classes
require_once($ct['base_dir'] . '/app-lib/php/inline/vars/empty-vars.php');
require_once($ct['base_dir'] . '/app-lib/php/inline/vars/static-vars.php');

// System config VERY EARLY (after loading vars)
require_once($ct['base_dir'] . '/app-lib/php/inline/config/system-config.php');

// Setup cache directories AS EARLY AS POSSIBLE
require_once($ct['base_dir'] . '/app-lib/php/inline/other/cache-setup.php');


// ESSENTIAL VARS / ARRAYS / INITS SET #BEFORE# config-init.php...

// Set $ct['app_id'] as a global (MUST BE SET AFTER system-config.php)
// (a 10 character install ID hash, created from the base URL or base dir [if cron])
// AFTER THIS IS SET, WE CAN USE EITHER $ct['app_id'] OR $ct['sec']->id() RELIABLY / EFFICIENTLY ANYWHERE
// $ct['sec']->id() can then be used in functions WITHOUT NEEDING ANY $ct['app_id'] GLOBAL DECLARED.
$ct['app_id'] = $ct['sec']->id();

// Sessions config (MUST RUN AFTER setting $ct['app_id'])
require_once($ct['base_dir'] . '/app-lib/php/inline/init/session-init.php');


// Nonce (CSRF attack protection) for user GET links (downloads etc) / admin login session logic WHEN NOT RUNNING AS CRON
if ( $ct['runtime_mode'] != 'cron' && !isset( $_SESSION['nonce'] ) ) {
$_SESSION['nonce'] = $ct['sec']->rand_hash(32); // 32 byte
}
	
	
// Flag this as a fast runtime if it is, to skip certain logic later in the runtime
// (among other things, skips setting $ct['system_info'] / some secured cache vars, and skips doing system resource usage alerts)
if (
$is_csv_export
|| $is_charts
|| $is_logs 
|| $ct['runtime_mode'] == 'captcha' 
|| $ct['runtime_mode'] == 'qr_code' 
|| isset($_GET['mode']) && $_GET['mode'] == 'stock_overview'
) {
$ct['fast_runtime'] = true;
}


if ( !$ct['fast_runtime'] ) {

// SYSTEM INFO
$ct['system_info'] = $ct['gen']->system_info(); // MUST RUN AFTER SETTING $ct['base_dir']
    			
$system_load_all = $ct['system_info']['system_load'];

// Use 15 minute average
$system_load = $system_load_all;
$system_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $system_load);
$system_load = preg_replace("/(.*)\(5 min avg\) /i", "", $system_load); 
    		
$system_temp = preg_replace("/° Celsius/i", "", $ct['system_info']['system_temp']);

$system_free_space_mb = $ct['gen']->in_megabytes($ct['system_info']['free_partition_space'])['in_megs'];

$portfolio_cache_size_mb = $ct['gen']->in_megabytes($ct['system_info']['portfolio_cache'])['in_megs'];

$system_memory_total_mb = $ct['gen']->in_megabytes($ct['system_info']['memory_total'])['in_megs'];
    		
$system_memory_free_mb = $ct['gen']->in_megabytes($ct['system_info']['memory_free'])['in_megs'];
    		
    		
    // Percent difference (!MUST BE! absolute value)
    if ( ( abs($system_memory_total_mb) * 100 ) > 0 ) {
    $memory_percent_free = abs( ($system_memory_free_mb - $system_memory_total_mb) / abs($system_memory_total_mb) * 100 );
    $memory_percent_free = round( 100 - $memory_percent_free, 2);
    }
    else {
    $memory_percent_free = null;
    }
    		
    		
$system_load_redline = ( $ct['system_info']['cpu_threads'] > 1 ? ($ct['system_info']['cpu_threads'] * 2) : 2 );

}
else {
$ct['system_info'] = array(); // BLANK if fast runtimes
}


// STRICT curl user agent (for strict API servers list in proxy mode, etc, etc)
// MUST BE SET IMMEDIATELY AFTER system info (AS EARLY AS POSSIBLE FOR ADMIN INPUT VALIDATION)
$ct['strict_curl_user_agent'] = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $ct['system_info']['software'] . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';


if ( !$ct['fast_runtime'] ) {

// Set the array of available currencies for coingecko (set after 'strict_curl_user_agent')
$ct['coingecko_currencies'] = $ct['api']->coingecko_currencies();
     
// Development status DATA SET from github file (get data after 'strict_curl_user_agent'):
// https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/.dev-status.json
$ct['dev']['status'] = @$ct['api']->dev_status();


     // Sort the alerts by NEWEST
     if (
     $ct['runtime_mode'] == 'ui'
     && is_array($ct['dev']['status'])
     && sizeof($ct['dev']['status']) > 0
     ) {
     
     // NEWEST dev statuses first
     $ct['sort_by_nested'] = 'root=>timestamp';
     usort($ct['dev']['status'], array($ct['var'], 'usort_desc') );
     $ct['sort_by_nested'] = false; // RESET
     
     $ct['dev']['status_data_found'] = true; // Flag as data was found (for loading in interface)
     
     
     	// Timestamp, of latest important status alert
     	foreach ( $ct['dev']['status'] as $dev_alert ) {
     	
          	if ( $dev_alert['dummy_entry'] ) {
          	continue;
          	}
          	elseif ( $dev_alert['very_important'] && !isset($ct['dev']['latest_important_dev_alerts_timestamp']) ) {
          	$ct['dev']['latest_important_dev_alerts_timestamp'] = $dev_alert['timestamp'];
          	}
     	
     	}
     
     
     }


     // Mining calculator settings (DURING 'ui' ONLY, since we run the interface mining settings from here)
     if ( $ct['runtime_mode'] == 'ui' ) {
     require('dynamic-config.php');
     }


}


$fetched_feeds = 'fetched_feeds_' . $ct['runtime_mode']; // Unique feed fetch telemetry SESSION KEY (so related runtime BROWSER SESSION logic never accidentally clashes)


// If upgrade check enabled / cached var set, set the runtime var for any configured alerts
if ( file_exists($ct['base_dir'] . '/cache/vars/state-tracking/upgrade_check_latest_version.dat') ) {
$upgrade_check_latest_version = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/upgrade_check_latest_version.dat') );
}
  

// If CACHED app version set, set the runtime var, AND FLAG ANY UPGRADE FOR
// NON-HIGH SECURITY MODE'S CACHED CONFIG (IF IT DOESN'T MATCH THE CURRENT VERSION NUMBER)
if ( !$ct['fast_runtime'] && $ct['runtime_mode'] != 'ajax' && file_exists($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat') ) {
     
$ct['cached_app_version'] = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat') );


     // Check version number against cached value, Avoid running during any AJAX runtimes etc
     if ( $ct['cached_app_version'] != $ct['app_version'] ) {
                                   
     // Refresh current app version to flat file
     // (for auto-install/upgrade scripts to easily determine the currently-installed version)
     $ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat', $ct['app_version']);
     
     // Flag for UI alerts that we UPGRADED / DOWNGRADED
     // (general message about cached CSS / JS [WITHOUT VERSION NUMBERS], so shown even when NOT logged in)
     $ui_was_upgraded_alert_data = array( 'run' => 'yes', 'time' => time() );
     
     $ct['cache']->save_file($ct['base_dir'] . '/cache/events/upgrading/ui_was_upgraded_alert.dat', json_encode($ui_was_upgraded_alert_data, JSON_PRETTY_PRINT) );
     
     
          // We ALWAYS MIRROR THE ENTIRE HARD-CODED CONFIG (FULL RESET, INCLUDING PLUGINS) ON THE 
          // SLIGHTEST CHANGE IN HIGH SECURITY MODE, SO NO ADDITIONAL UPGRADE / RESET NEEDED
          if ( $ct['admin_area_sec_level'] != 'high' && !$ct['reset_config'] ) {
               
          // Developer-only configs
          $dev_only_configs_mode = 'config-init-upgrade-check'; // Flag to only run 'config-init-upgrade-check' section
               
          // setting RESET configs
          require('developer-config.php');
               
          // Process any developer-added APP DB SETTING RESETS (for RELIABLE DB upgrading)
          require($ct['base_dir'] . '/app-lib/php/inline/config/setting-reset-config.php');
     
          $config_version_compare = $ct['gen']->version_compare($ct['app_version'], $ct['cached_app_version']);
               
               
               // IF we are DOWNGRADING, warn user WE MUST RESET THE APP CONFIG FOR COMPATIBILITY!
               if ( $config_version_compare['base_diff'] < 0 ) {
                    
               $ct['reset_config'] = true;
     
               $ct['update_config_halt'] = 'The app was busy RESETTING it\'s cached config, please wait a minute and try again.';
               
               $ct['gen']->log(
                    			'notify_error',
                    			'app DOWNGRADE detected, RESETTING the ENTIRE app configuration TO ASSURE COMPATIBILITY'
                       			);
               
               // RESETS don't auto-update CACHED version, so save it now
               $ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat', $ct['app_version']);
               
               }
               // Otherwise, flag upgrading
               else {
                    
               $ct['app_upgrade_check'] = true;
                                       
               // User updates halted message (avoid any conflicts, as we are busy finishing the upgrade above)
               $ct['update_config_halt'] = 'The app was busy UPGRADING it\'s cached config, please wait a minute and try again.'; 
                    
               }
     
     
          }
     
     
     }
     
     
}
// Otherwise cache the app version for FIRST RUN ON NEW INSTALLATIONS
// (do NOT set $ct['cached_app_version'] here, as we have FIRST RUN logic seeing if the CACHED version is set!)
elseif ( !$ct['fast_runtime'] && $ct['runtime_mode'] != 'ajax' ) {
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat', $ct['app_version']);
}


// Early security logic
// #MUST# run BEFORE any heavy init logic (for good security), #AFTER# directory creation (for error logging), and AFTER system checks
require_once($ct['base_dir'] . '/app-lib/php/inline/security/early-security-logic.php');

// Load any 3RD PARTY classes WITHOUT CONFIGS (MUST run after system-config / early-security-logic)
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party-classes-loader.php');


//////////////////////////////////////////////////////////////////
// END PRIMARY INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
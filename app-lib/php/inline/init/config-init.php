<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// CONFIG INIT 
////////////////////////////////////////////////////////////////// 


// Default config, used for upgrade checks
// (#MUST# BE SET AT VERY TOP OF CONFIG-INIT.PHP, AND BEFORE LOADING CACHED CONFIG)
// WE MODIFY / RUN THIS AND UPGRADE LOGIC, WITHIN load-config-by-security-level.php
$default_ct_conf = $ct['conf']; 


// Used for quickening runtimes on app config upgrading checks
// (#MUST# BE SET AT VERY TOP OF CONFIG-INIT.PHP, AND BEFORE LOADING CACHED CONFIG)
if ( file_exists($ct['base_dir'] . '/cache/vars/state-tracking/default_ct_conf_md5.dat') ) {
$check_default_ct_conf = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/default_ct_conf_md5.dat') );
}
else {
$check_default_ct_conf = null;
}


// Flag any new upgrade, for UI alert, AND MORE IMPORTANTLY: avoiding conflicts with config reset / refresh / upgrade routines
// (!!MUST RUN *BEFORE* $ct['reset_config'], AND *BEFORE* load-config-by-security-level.php)
if (
$ct['upgraded_install']
||  $_POST['upgrade_ct_conf'] == 1 && $ct['gen']->pass_sec_check($_POST['admin_nonce'], 'upgrade_ct_conf') && $ct['gen']->valid_2fa('strict')
) {

     // We just flag as upgraded / cache NEW app version number in high security mode
     // We NEVER want to run a cached config upgrade in high security mode
     // (as we ALWAYS mirror PHP config file changes to the cached config)
     if ( $ct['admin_area_sec_level'] == 'high' ) {
     
     // Flag for UI alerts
     $ui_was_upgraded_alert_data = array( 'run' => 'yes', 'time' => time() );
     $ct['cache']->save_file($ct['base_dir'] . '/cache/events/upgrading/ui_was_upgraded_alert.dat', json_encode($ui_was_upgraded_alert_data, JSON_PRETTY_PRINT) );
                                   
     // Refresh current app version to flat file (for auto-install/upgrade scripts to easily determine the currently-installed version)
     $ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat', $ct['app_version']);
     
     }
     else {
          
     $ct['app_upgrade_check'] = true;
     
     // Developer-only configs
     $dev_only_configs_mode = 'config-init-upgrade-check'; // Flag to only run 'config-init-upgrade-check' section
     
     // RESET configs
     require('developer-config.php');
     
     // Process any developer-added DB RESETS (for RELIABLE DB upgrading)
     require($ct['base_dir'] . '/app-lib/php/inline/config/reset-config.php');
     
     }
     
}


// If a ct_conf reset from authenticated admin is verified, refresh CACHED ct_conf with the DEFAULT ct_conf
// (!!MUST RUN *AFTER* $ct['app_upgrade_check'], AN *BEFORE* load-config-by-security-level.php)
// (STRICT 2FA MODE ONLY)
if ( $_POST['reset_ct_conf'] == 1 && $ct['gen']->pass_sec_check($_POST['admin_nonce'], 'reset_ct_conf') && $ct['gen']->valid_2fa('strict') ) {

     if ( $ct['app_upgrade_check'] ) {
     $admin_reset_error = 'The CACHED config is currently in the process of UPGRADING. Please wait a minute, and then try resetting again.';
     }
     else {
     $ct['reset_config'] = true;
     $admin_reset_success = 'The app configuration was reset successfully.';
     }

}	


// Toggle to set the admin interface security level, if 'opt_admin_sec' from authenticated admin is verified
// (MUST run after primary-init, BUT BEFORE load-config-by-security-level.php)
// (CHECK 2FA UNDER ANY 2FA MODE)
if ( isset($_POST['opt_admin_sec']) && $ct['gen']->pass_sec_check($_POST['admin_nonce'], 'toggle_admin_security') && $ct['gen']->valid_2fa() ) {
     
     // We want to load configs from the hard-coded config files if we just switched to 'high' security mode,
     // so trigger a config reset to accomplish that
     if ( $_POST['opt_admin_sec'] == 'high' ) {
     $ct['reset_config'] = true;
     }
     
$ct['admin_area_sec_level'] = $_POST['opt_admin_sec'];
     
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/admin_area_sec_level.dat', $ct['admin_area_sec_level']);
     
$setup_admin_sec_success = 'Admin Security Level changed to "'.$ct['admin_area_sec_level'].'" successfully.';
          
}


// Toggle 2FA SETUP off / on / scrict, if 'opt_admin_2fa' from authenticated admin is verified, AND 2FA check passes
// (MUST run after primary-init, BUT BEFORE load-config-by-security-level.php)
// *FORCE* CHECK 2FA, SINCE WE ARE RUNNING 2FA SETUP HERE
if ( isset($_POST['opt_admin_2fa']) && $ct['gen']->pass_sec_check($_POST['admin_nonce'], 'toggle_admin_2fa') ) {
     
     
     // If valid 2FA code
     if ( $ct['gen']->valid_2fa('setup', 'force_check') ) {
     
     $ct['admin_area_2fa'] = $_POST['opt_admin_2fa'];
          
     $ct['cache']->save_file($ct['base_dir'] . '/cache/vars/admin_area_2fa.dat', $ct['admin_area_2fa']);
          
          
          if ( $_POST['opt_admin_2fa'] != 'off' ) {
                    
               if ( $_POST['opt_admin_2fa'] == 'strict' ) {
               $setup_2fa_notice_mode = ' (strict mode)';
               $setup_2fa_success_scrict = ', AND whenever you want to update ANYTHING in the admin area';
               }
               else {
               $setup_2fa_notice_mode = ' (standard mode)';
               }
                    
          $setup_2fa_success = '2FA' . $setup_2fa_notice_mode . ' has been ENABLED successfully. You will need to use your authenticator phone app whenever you login now (along with your usual password)' . $setup_2fa_success_scrict . '.';
     
          }
          else {
          $setup_2fa_success = '2FA has been DISABLED successfully.';
          }
          
     
     }
     // If NOT
     else {
                    
          // Force-show Admin 2FA setup (IF 2FA IS CURRENTLY OFF), if it failed because of an invalid 2FA code
          if ( $ct['check_2fa_error'] != null && $ct['admin_area_2fa'] == 'off' ) {
          $force_show_2fa_setup = $_POST['opt_admin_2fa'];
          }
     
     }
     

}
// END 2FA SETUP


// CURL CA certificate MONTHLY update...
// WE USE NATIVE PHP FUNCTIONS HERE AS MUCH AS POSSIBLE, AS WE HAVEN'T FULLY INITIATED THE APP CONFIG YET!

// CACHED / UPDATED CACERT FILE PATH
$cached_curl_cacert_path = $ct['base_dir'] . '/cache/other/recent-cacert.pem';

// IF update fails, we fallback to this cert (that we always include with releases)
$failsafe_curl_cacert_path = $ct['base_dir'] . '/cacert.pem';


// (43200 minutes is 30 days)
if ( $ct['cache']->update_cache($cached_curl_cacert_path, 43200) == true ) {

// SSL support for file_get_contents(), since we don't want to use CURL,
// as we are getting CURL's latest CA cert file to ASSURE IT WILL RUN AFTERWARDS
// (CURL-PHP ON WINDOWS CAN FAIL IN SOME APP SERVER SETUPS, IF WE DON'T UPDATE THE CACERT)
$file_ssl_params = array(
                         "ssl" => array(
                                        "verify_peer" => false,
                                        "verify_peer_name" => false,
                                       ),
                        );  

$file_ssl_context = stream_context_create($file_ssl_params);
        
$curl_cacert_data = file_get_contents('https://curl.se/ca/cacert.pem', false, $file_ssl_context);

     // If data was received (GREATER THAN ZERO BYTES), save it,
     // otherwise reset with touch(), to wait another week before trying again
     if ( $curl_cacert_data && strlen($curl_cacert_data) > 0 ) {
     file_put_contents($cached_curl_cacert_path, $curl_cacert_data, LOCK_EX);
     }
     else {
     touch($cached_curl_cacert_path);
     }

sleep(2); // Give time for file save, before checks below

}

     
// Run checks on curl CA certificate file(s)
if ( file_exists($cached_curl_cacert_path) && filesize($cached_curl_cacert_path) > 0 ) {
$ct['curl_cacert_path'] = $cached_curl_cacert_path;
}
else if ( file_exists($failsafe_curl_cacert_path) && filesize($failsafe_curl_cacert_path) > 0 ) {
$ct['curl_cacert_path'] = $cached_curl_cacert_path;
}
else {
$ct['curl_cacert_path'] = false;
}


// Global alert that a ticker search is running
if ( isset($_POST['add_markets_search']) && $ct['gen']->admin_logged_in() && $ct['gen']->pass_sec_check($_GET['gen_nonce'], 'general_csrf_security') ) {
$ct['ticker_markets_search'] = true;
}


// Load config type based on admin security level
require_once('app-lib/php/inline/config/load-config-by-security-level.php');

// Dynamic app config auto-adjust (MUST RUN AS EARLY AS POSSIBLE AFTER #FULL# ct_conf setup)
require_once('app-lib/php/inline/config/config-auto-adjust.php');

// VERY EARLY init BASED OFF USER CONFIGS (MUST RUN AS EARLY AS POSSIBLE AFTER app config auto-adjust)
require_once('app-lib/php/inline/init/config-early-init.php');

// Load any activated 3RD PARTY classes WITH CONFIGS (MUST RUN AS EARLY AS POSSIBLE AFTER config-early-init.php)
require_once('app-lib/php/classes/3rd-party-classes-with-configs-loader.php');


// Essential vars / arrays / inits that can only be dynamically set AFTER config-auto-adjust / 3rd-party-classes-loader...

// PHP error logging on / off, VIA END-USER CONFIG SETTING, *ONLY IF* THE HARD-CODED DEV PHP DEBUGGING IN INIT.PHP IS OFF
if ( $ct['dev']['debug_php_errors'] == 0 ) {
error_reporting($ct['conf']['power']['php_error_reporting']); 
}


// Set a max execution time (if the system lets us), TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $ct['conf']['power']['debug_mode'] != 'off' ) {
$max_exec_time = 1320; // 22 minutes in debug mode
}
elseif ( $ct['runtime_mode'] == 'ui' ) {
$max_exec_time = $ct['dev']['ui_max_exec_time'];
}
elseif ( $ct['runtime_mode'] == 'ajax' ) {
$max_exec_time = $ct['dev']['ajax_max_exec_time'];
}
elseif ( $ct['runtime_mode'] == 'cron' ) {
$max_exec_time = $ct['dev']['cron_max_exec_time'];
}
elseif ( $ct['runtime_mode'] == 'int_api' ) {
$max_exec_time = $ct['dev']['int_api_max_exec_time'];
}
elseif ( $ct['runtime_mode'] == 'webhook' ) {
$max_exec_time = $ct['dev']['webhook_max_exec_time'];
}


// If the script timeout var wasn't set properly / is not a whole number 3600 or less
if ( !$ct['var']->whole_int($max_exec_time) || $max_exec_time > 3600 ) {
$max_exec_time = 600; // 600 seconds default
}


// Maximum time script can run (may OR may not be overridden by operating system values, BUT we want this if the system allows it)
set_time_limit($max_exec_time); // Doc suggest this may be more reliable than ini_set max_exec_time?
     			
     			
// If no master webhook (AND not a fast runtime), or a webhook secret key reset from authenticated admin is verified
// (STRICT 2FA MODE ONLY)
if ( !$is_fast_runtime && !$webhook_master_key || $_POST['reset_webhook_master_key'] == 1 && $ct['gen']->pass_sec_check($_POST['admin_nonce'], 'reset_webhook_master_key') && $ct['gen']->valid_2fa('strict') ) {
     	
$secure_128bit_hash = $ct['gen']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = $ct['gen']->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
     	
     	
     // Halt the process if an issue is detected safely creating a random hash
     if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
     	
     $ct['gen']->log(
     			'security_error',
     			'Cryptographically secure pseudo-random bytes could not be generated for webhook key (in secured cache storage), webhook key creation aborted to preserve security'
     			);
     
     $admin_reset_error = 'The master webhook key reset FAILED (see error logs).';

     }
     // WE AUTOMATICALLY DELETE OUTDATED CACHE FILES SORTING BY DATE WHEN WE LOAD IT, SO NO NEED TO DELETE THE OLD ONE
     else {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/webhook_master_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
     $webhook_master_key = $secure_256bit_hash;
     $admin_reset_success = 'The master webhook key was reset successfully.';
     }
     

}
     			

// If no internal API key (AND not a fast runtime), OR an internal API key reset from authenticated admin is verified
// (STRICT 2FA MODE ONLY)
if ( !$is_fast_runtime && !$int_api_key || $_POST['reset_int_api_key'] == 1 && $ct['gen']->pass_sec_check($_POST['admin_nonce'], 'reset_int_api_key') && $ct['gen']->valid_2fa('strict') ) {
     				
$secure_128bit_hash = $ct['gen']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = $ct['gen']->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
     	
     	
     // Halt the process if an issue is detected safely creating a random hash
     if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
     		
     $ct['gen']->log(
     			'security_error',
     			'Cryptographically secure pseudo-random bytes could not be generated for internal API key (in secured cache storage), key creation aborted to preserve security'
     			);
     
     $admin_reset_error = 'The internal API key reset FAILED (see error logs).';
     	
     }
     // WE AUTOMATICALLY DELETE OUTDATED CACHE FILES SORTING BY DATE WHEN WE LOAD IT, SO NO NEED TO DELETE THE OLD ONE
     else {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/int_api_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
     $int_api_key = $secure_256bit_hash;
     $admin_reset_success = 'The internal API key was reset successfully.';
     }
     	
}


// Auto-increase time offset on daily background tasks for systems with low core counts
if ( $ct['system_info']['cpu_threads'] < 4 ) {
$ct['dev']['tasks_time_offset'] = ceil($ct['dev']['tasks_time_offset'] * 2);
}


// #GUI# PHP TIMEOUT tracking / updating (checking for changes to the config value)
$conf_php_timeout = $ct['dev']['ui_max_exec_time'];

// Update daily (1440 minutes)
if ( $ct['cache']->update_cache($ct['base_dir'] . '/cache/vars/state-tracking/php_timeout.dat', 1440) == true ) {
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/php_timeout.dat', $conf_php_timeout);
$cached_php_timeout = $conf_php_timeout;
}
else {
$cached_php_timeout = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/php_timeout.dat') );
}


// Check if we need to rebuild ROOT .htaccess / .user.ini
if ( $conf_php_timeout != $cached_php_timeout ) {

// Delete ROOT .htaccess / .user.ini
unlink($ct['base_dir'] . '/.htaccess');
unlink($ct['base_dir'] . '/.user.ini');
unlink($ct['base_dir'] . '/cache/secured/.app_htpasswd');

// Cache the new PHP timeout
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/php_timeout.dat', $conf_php_timeout);

}

// Light chart config tracking / updating (checking for changes to light chart app config, to trigger light chart rebuilds)
$conf_light_chart_struct = md5( serialize($ct['light_chart_day_intervals']) . $ct['conf']['charts_alerts']['light_chart_data_points_maximum'] );

if ( !file_exists($ct['base_dir'] . '/cache/vars/state-tracking/light_chart_struct.dat') ) {
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/light_chart_struct.dat', $conf_light_chart_struct);
$cached_light_chart_struct = $conf_light_chart_struct;
}
else {
$cached_light_chart_struct = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/light_chart_struct.dat') );
}


// Check if we need to rebuild light charts from changes to their structure,
// OR a user-requested light chart reset
if (
$conf_light_chart_struct != $cached_light_chart_struct
|| $_POST['reset_light_charts'] == 1 && $ct['gen']->pass_sec_check($_POST['admin_nonce'], 'reset_light_charts') && $ct['gen']->valid_2fa('strict')
) {

// Delete ALL light charts (this will automatically trigger a re-build)
$ct['cache']->remove_dir($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/light');
$ct['cache']->remove_dir($ct['base_dir'] . '/cache/charts/system/light');

// Cache the new light chart structure
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/light_chart_struct.dat', $conf_light_chart_struct);

$admin_reset_success = 'The Light Charts were reset successfully.';

}


// Configged font size
if ( isset($_COOKIE['font_size']) ) {
$set_font_size = $_COOKIE['font_size']; // Already 'em' scale format
}
elseif ( $ct['var']->whole_int($ct['conf']['gen']['default_font_size']) ) {
$set_font_size = round( ($ct['conf']['gen']['default_font_size'] * 0.01) , 3);
}
else {
$set_font_size = 1; // 'em' scale format
}


// Enforce min / max allowed values on the default font size
// (IN 'em' CSS-COMPATIBLE SCALING WE SWITCHED TO ABOVE)
if ( $set_font_size > $ct['dev']['max_font_resize'] ) {
$set_font_size = $ct['dev']['max_font_resize'];
}
elseif ( $set_font_size < $ct['dev']['min_font_resize'] ) {
$set_font_size = $ct['dev']['min_font_resize'];
}


$set_font_line_height = round( ($set_font_size * $ct['dev']['global_line_height_percent']) , 3);
     
$set_medium_font_size = round( ($set_font_size * $ct['dev']['medium_font_size_css_percent']) , 3);
$set_medium_font_line_height = round( ($set_medium_font_size * $ct['dev']['global_line_height_percent']) , 3);
     
$set_small_font_size = round( ($set_font_size * $ct['dev']['small_font_size_css_percent']) , 3);
$set_small_font_line_height = round( ($set_small_font_size * $ct['dev']['global_line_height_percent']) , 3); 
     
$set_tiny_font_size = round( ($set_font_size * $ct['dev']['tiny_font_size_css_percent']) , 3);
$set_tiny_font_line_height = round( ($set_tiny_font_size * $ct['dev']['global_line_height_percent']) , 3); 


// Alphabetically sort news feeds
if ( is_array($ct['conf']['news']['feeds']) ) { 
$ct['sort_alpha_assoc_multidem'] = 'title';
$usort_feeds_results = usort($ct['conf']['news']['feeds'], array($ct['gen'], 'usort_alpha') );
}
   	
   	
if ( !$usort_feeds_results ) {
$ct['gen']->log('other_error', 'RSS feeds failed to sort alphabetically');
}
      

// Set minimum CURRENCY value used in the app
$loop = 0;
$min_fiat_val_test = "0.";
while ( $loop < $ct['conf']['currency']['currency_decimals_max'] ) {
$loop = $loop + 1;
$min_fiat_val_test .= ( $loop < $ct['conf']['currency']['currency_decimals_max'] ? '0' : '1' );
}
unset($loop);
      

// Set minimum CRYPTO value used in the app (important for currency conversions on very low-value coins, like BONK etc)
$loop = 0;
$min_crypto_val_test = "0.";
while ( $loop < $ct['conf']['currency']['crypto_decimals_max'] ) {
$loop = $loop + 1;
$min_crypto_val_test .= ( $loop < $ct['conf']['currency']['crypto_decimals_max'] ? '0' : '1' );
}
unset($loop);


// Set "watch only" flag amount (sets portfolio amount one decimal MORE than allowed min value)
$watch_only_flag_val = preg_replace("/1/", "01", $min_crypto_val_test); // Set to 0.XXXXX01 instead of 0.XXXXX1
        

// Primary Bitcoin markets (MUST RUN AT END of config-init [AFTER config auto-adjust])
require_once('app-lib/php/inline/config/primary-bitcoin-markets-config.php');

// Chart sub-directory creation (if needed...MUST RUN AT END of config-init [AFTER config auto-adjust])
require_once('app-lib/php/inline/config/chart-directories-config.php');


//////////////////////////////////////////////////////////////////
// END CONFIG INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
 ?>
<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// CONFIG INIT 
//////////////////////////////////////////////////////////////////


// If a ct_conf reset from authenticated admin is verified, refresh CACHED ct_conf with the DEFAULT ct_conf
// (!!MUST RUN *BEFORE* load-config-by-security-level.php ADDS PLUGIN CONFIGS TO $default_ct_conf AND $ct['conf']!!)
if ( $_POST['reset_ct_conf'] == 1 && $ct['gen']->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_ct_conf') ) {
$reset_ct_conf = true;
}


// Load config type based on admin security level
require_once('app-lib/php/inline/config/load-config-by-security-level.php');

// Dynamic app config auto-adjust (MUST RUN AS EARLY AS POSSIBLE AFTER #FULL# ct_conf setup)
require_once('app-lib/php/inline/config/config-auto-adjust.php');

// Load any activated 3RD PARTY classes (MUST RUN AS EARLY AS POSSIBLE AFTER app config auto-adjust)
require_once('app-lib/php/classes/3rd-party-classes-loader.php');


// Essential vars / arrays / inits that can only be dynamically set AFTER config-auto-adjust / 3rd-party-classes-loader...

// PHP error logging on / off, VIA END-USER CONFIG SETTING, *ONLY IF* THE HARD-CODED DEV PHP DEBUGGING IN INIT.PHP IS OFF
if ( $dev_debug_php_errors == 0 ) {
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


// Auto-increase time offset on daily background tasks for systems with low core counts
if ( $ct['system_info']['cpu_threads'] < 4 ) {
$ct['dev']['tasks_time_offset'] = ceil($ct['dev']['tasks_time_offset'] * 2);
}


// Toggle to set the admin interface security level, if 'opt_admin_sec' from authenticated admin is verified
// (MUST run after 3rd-party-classes-loader.php)
if ( isset($_POST['opt_admin_sec']) && $ct['gen']->pass_sec_check($_POST['admin_hashed_nonce'], 'toggle_admin_security') ) {
     
     if ( $ct['gen']->valid_2fa() ) {
          
     $admin_area_sec_level = $_POST['opt_admin_sec'];
     
     $ct['cache']->save_file($ct['base_dir'] . '/cache/vars/admin_area_sec_level.dat', $admin_area_sec_level);
     
     $setup_admin_sec_success = 'Admin Security Level changed to "'.$admin_area_sec_level.'" successfully.';
          
     }
     
}


// Toggle 2FA setup on / off, if 'opt_admin_2fa' from authenticated admin is verified, AND 2FA check passes
// (MUST run after 3rd-party-classes-loader.php)
if ( isset($_POST['opt_admin_2fa']) && $ct['gen']->pass_sec_check($_POST['admin_hashed_nonce'], 'toggle_admin_2fa') ) {
     
     // FORCE CHECK IF *ENABLING* THE 2FA ADMIN SETTING HERE
     if ( $_POST['opt_admin_2fa'] == 'on' && $ct['gen']->valid_2fa('force_check') || $_POST['opt_admin_2fa'] == 'off' && $ct['gen']->valid_2fa() ) {
          
     $admin_area_2fa = $_POST['opt_admin_2fa'];
     
     $ct['cache']->save_file($ct['base_dir'] . '/cache/vars/admin_area_2fa.dat', $admin_area_2fa);
     
          if ( $_POST['opt_admin_2fa'] == 'on' ) {
          $setup_2fa_success = '2FA has been ENABLED successfully. You will need to use your authenticator phone app whenever you login now (along with your usual password).';
          }
          else {
          $setup_2fa_success = '2FA has been DISABLED successfully.';
          }
          
     }
     
}
                    

// Force-show Admin 2FA setup, if it failed becuase of an invalid 2FA code
if ( $_POST['admin_hashed_nonce'] == $ct['gen']->admin_hashed_nonce('toggle_admin_2fa') && $check_2fa_error != null ) {
$force_show_2fa_setup = ' style="display: block;"';
}


// htaccess login...SET BEFORE ui-preflight-security-checks.php
$interface_login_array = explode("||", $ct['conf']['sec']['interface_login']);
$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];


// User agent (MUST BE SET VERY EARLY [AFTER primary-init / CONFIG-AUTO-ADJUST], 
// FOR ANY CURL-BASED API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($ct['conf']['power']['override_curl_user_agent']) != '' ) {
$ct['curl_user_agent'] = $ct['conf']['power']['override_curl_user_agent'];  // Custom user agent
}
elseif ( is_array($ct['conf']['proxy']['proxy_list']) && sizeof($ct['conf']['proxy']['proxy_list']) > 0 ) {
$ct['curl_user_agent'] = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$ct['curl_user_agent'] = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $ct['system_info']['software'] . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';
}


// #GUI# PHP TIMEOUT tracking / updating (checking for changes to the config value)
$conf_php_timeout = $ct['dev']['ui_max_exec_time'];

if ( !file_exists($ct['base_dir'] . '/cache/vars/php_timeout.dat') ) {
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/php_timeout.dat', $conf_php_timeout);
$cached_php_timeout = $conf_php_timeout;
}
else {
$cached_php_timeout = trim( file_get_contents($ct['base_dir'] . '/cache/vars/php_timeout.dat') );
}


// Check if we need to rebuild ROOT .htaccess / .user.ini
if ( $conf_php_timeout != $cached_php_timeout ) {

// Delete ROOT .htaccess / .user.ini
unlink($ct['base_dir'] . '/.htaccess');
unlink($ct['base_dir'] . '/.user.ini');
unlink($ct['base_dir'] . '/cache/secured/.app_htpasswd');

// Cache the new PHP timeout
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/php_timeout.dat', $conf_php_timeout);

}


// Email TO service check
if ( isset($ct['conf']['comms']['to_email']) && $ct['gen']->valid_email($ct['conf']['comms']['to_email']) == 'valid' ) {
$valid_to_email = true;
}


// Email FROM service check
if ( isset($ct['conf']['comms']['from_email']) && $ct['gen']->valid_email($ct['conf']['comms']['from_email']) == 'valid' ) {
$valid_from_email = true;
}


// Notifyme service check
if ( isset($ct['conf']['ext_apis']['notifyme_accesscode']) && trim($ct['conf']['ext_apis']['notifyme_accesscode']) != '' ) {
$notifyme_activated = true;
}


// Texting (SMS) services check
// (if MORE THAN ONE is activated, keep ALL disabled to avoid a texting firestorm)
if ( isset($ct['conf']['ext_apis']['textbelt_apikey']) && trim($ct['conf']['ext_apis']['textbelt_apikey']) != '' ) {
$activated_sms_services[] = 'textbelt';
}


if (
isset($ct['conf']['ext_apis']['twilio_number']) && trim($ct['conf']['ext_apis']['twilio_number']) != ''
&& isset($ct['conf']['ext_apis']['twilio_sid']) && trim($ct['conf']['ext_apis']['twilio_sid']) != ''
&& isset($ct['conf']['ext_apis']['twilio_token']) && trim($ct['conf']['ext_apis']['twilio_token']) != ''
) {
$activated_sms_services[] = 'twilio';
}


// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if (
isset($ct['conf']['ext_apis']['textlocal_sender'])
&& trim($ct['conf']['ext_apis']['textlocal_sender']) != ''
&& isset($ct['conf']['ext_apis']['textlocal_apikey'])
&& $ct['conf']['ext_apis']['textlocal_apikey'] != ''
) {
$activated_sms_services[] = 'textlocal';
}


$text_email_gateway_check = explode("||", trim($ct['conf']['comms']['to_mobile_text']) );


if (
isset($text_email_gateway_check[0])
&& isset($text_email_gateway_check[1])
&& trim($text_email_gateway_check[0]) != ''
&& trim($text_email_gateway_check[1]) != ''
&& trim($text_email_gateway_check[1]) != 'skip_network_name'
&& $ct['gen']->valid_email( $ct['gen']->text_email($ct['conf']['comms']['to_mobile_text']) ) == 'valid'
) {
$activated_sms_services[] = 'email_gateway';
}


if ( sizeof($activated_sms_services) == 1 ) {
$sms_service = $activated_sms_services[0];
}
elseif ( sizeof($activated_sms_services) > 1 ) {
$ct['gen']->log( 'conf_error', 'only one SMS service is allowed, please deactivate ALL BUT ONE of the following: ' . implode(", ", $activated_sms_services) );
}


// Backup archive password protection / encryption
if ( $ct['conf']['sec']['backup_arch_pass'] != '' ) {
$backup_arch_pass = $ct['conf']['sec']['backup_arch_pass'];
}
else {
$backup_arch_pass = false;
}


// Light chart config tracking / updating (checking for changes to light chart app config, to trigger light chart rebuilds)
$conf_light_chart_struct = md5( serialize($ct['conf']['power']['light_chart_day_intervals']) . $ct['conf']['power']['light_chart_data_points_max'] );

if ( !file_exists($ct['base_dir'] . '/cache/vars/light_chart_struct.dat') ) {
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/light_chart_struct.dat', $conf_light_chart_struct);
$cached_light_chart_struct = $conf_light_chart_struct;
}
else {
$cached_light_chart_struct = trim( file_get_contents($ct['base_dir'] . '/cache/vars/light_chart_struct.dat') );
}


// Check if we need to rebuild light charts from changes to their structure,
// OR a user-requested light chart reset
if (
$conf_light_chart_struct != $cached_light_chart_struct
|| $_POST['reset_light_charts'] == 1 && $ct['gen']->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_light_charts')
) {

// Delete ALL light charts (this will automatically trigger a re-build)
$ct['cache']->remove_dir($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/light');
$ct['cache']->remove_dir($ct['base_dir'] . '/cache/charts/system/light');

// Cache the new light chart structure
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/light_chart_struct.dat', $conf_light_chart_struct);

}


// Configged google font
if ( isset($ct['conf']['gen']['google_font']) && trim($ct['conf']['gen']['google_font']) != '' ) {
          
$google_font_name = trim($ct['conf']['gen']['google_font']);
     
$font_name_url_formatting = $google_font_name;
$font_name_url_formatting = preg_replace("/  /", " ", $font_name_url_formatting);
$font_name_url_formatting = preg_replace("/  /", " ", $font_name_url_formatting);
$font_name_url_formatting = preg_replace("/ /", "+", $font_name_url_formatting);

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
$usort_feeds_results = usort($ct['conf']['news_feeds'], array($ct['gen'], 'titles_usort_alpha') );
   	
   	
if ( !$usort_feeds_results ) {
$ct['gen']->log('other_error', 'RSS feeds failed to sort alphabetically');
}
      

// Set minimum CURRENCY value used in the app
$loop = 0;
$min_fiat_val_test = "0.";
while ( $loop < $ct['conf']['gen']['currency_dec_max'] ) {
$loop = $loop + 1;
$min_fiat_val_test .= ( $loop < $ct['conf']['gen']['currency_dec_max'] ? '0' : '1' );
}
unset($loop);
      

// Set minimum CRYPTO value used in the app (important for currency conversions on very low-value coins, like BONK etc)
$loop = 0;
$min_crypto_val_test = "0.";
while ( $loop < $ct['conf']['gen']['crypto_dec_max'] ) {
$loop = $loop + 1;
$min_crypto_val_test .= ( $loop < $ct['conf']['gen']['crypto_dec_max'] ? '0' : '1' );
}
unset($loop);


// Set "watch only" flag amount (sets portfolio amount one decimal MORE than allowed min value)
$watch_only_flag_val = preg_replace("/1/", "01", $min_crypto_val_test); // Set to 0.XXXXX01 instead of 0.XXXXX1
        

// Throttled markets (MUST RUN AT END of config-init [AFTER config auto-adjust])
require_once('app-lib/php/inline/config/throttled-markets-config.php');

// Primary Bitcoin markets (MUST RUN AT END of config-init [AFTER config auto-adjust])
require_once('app-lib/php/inline/config/primary-bitcoin-markets-config.php');

// Chart sub-directory creation (if needed...MUST RUN AT END of config-init [AFTER config auto-adjust])
require_once('app-lib/php/inline/config/chart-directories-config.php');


//////////////////////////////////////////////////////////////////
// END CONFIG INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
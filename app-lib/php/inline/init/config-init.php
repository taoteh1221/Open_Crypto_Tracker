<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// CONFIG INIT 
//////////////////////////////////////////////////////////////////


// If a ct_conf reset from authenticated admin is verified, refresh CACHED ct_conf with the DEFAULT ct_conf
// (!!MUST RUN *BEFORE* load-config-by-security-level.php ADDS PLUGIN CONFIGS TO $default_ct_conf AND $ct_conf!!)
if ( $_POST['reset_ct_conf'] == 1 && $ct_gen->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_ct_conf') ) {
$reset_ct_conf = true;
}


// Load config type based on admin security level
require_once('app-lib/php/inline/config/load-config-by-security-level.php');

// Dynamic app config auto-adjust (MUST RUN AS EARLY AS POSSIBLE AFTER #FULL# ct_conf setup)
require_once('app-lib/php/inline/config/config-auto-adjust.php');

// Load any activated 3RD PARTY classes (MUST RUN AS EARLY AS POSSIBLE AFTER app config auto-adjust)
require_once('app-lib/php/classes/3rd-party-classes-loader.php');


// Essential vars / arrays / inits that can only be dynamically set AFTER config-auto-adjust...

// PHP error logging on / off, VIA END-USER CONFIG SETTING, *ONLY IF* THE HARD-CODED DEV PHP DEBUGGING IN INIT.PHP IS OFF
if ( $dev_debug_php_errors == 0 ) {
error_reporting($ct_conf['power']['php_error_reporting']); 
}


// Set a max execution time (if the system lets us), TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $ct_conf['power']['debug_mode'] != 'off' ) {
$max_exec_time = 1320; // 22 minutes in debug mode
}
elseif ( $runtime_mode == 'ui' ) {
$max_exec_time = $ct_conf['power']['ui_max_exec_time'];
}
elseif ( $runtime_mode == 'ajax' ) {
$max_exec_time = $ct_conf['power']['ajax_max_exec_time'];
}
elseif ( $runtime_mode == 'cron' ) {
$max_exec_time = $ct_conf['power']['cron_max_exec_time'];
}
elseif ( $runtime_mode == 'int_api' ) {
$max_exec_time = $ct_conf['power']['int_api_max_exec_time'];
}
elseif ( $runtime_mode == 'webhook' ) {
$max_exec_time = $ct_conf['power']['webhook_max_exec_time'];
}


// If the script timeout var wasn't set properly / is not a whole number 3600 or less
if ( !$ct_var->whole_int($max_exec_time) || $max_exec_time > 3600 ) {
$max_exec_time = 250; // 250 seconds default
}


// Maximum time script can run (may OR may not be overridden by operating system values, BUT we want this if the system allows it)
set_time_limit($max_exec_time); // Doc suggest this may be more reliable than ini_set max_exec_time?


// htaccess login...SET BEFORE ui-preflight-security-checks.php
$interface_login_array = explode("||", $ct_conf['sec']['interface_login']);
$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];


// User agent (MUST BE SET VERY EARLY [AFTER primary-init / CONFIG-AUTO-ADJUST], 
// FOR ANY CURL-BASED API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($ct_conf['power']['override_curl_user_agent']) != '' ) {
$curl_user_agent = $ct_conf['power']['override_curl_user_agent'];  // Custom user agent
}
elseif ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 ) {
$curl_user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$curl_user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $system_info['software'] . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';
}


// #GUI# PHP TIMEOUT tracking / updating (checking for changes to the config value)
$conf_php_timeout = $ct_conf['power']['ui_max_exec_time'];

if ( !file_exists($base_dir . '/cache/vars/php_timeout.dat') ) {
$ct_cache->save_file($base_dir . '/cache/vars/php_timeout.dat', $conf_php_timeout);
$cached_php_timeout = $conf_php_timeout;
}
else {
$cached_php_timeout = trim( file_get_contents($base_dir . '/cache/vars/php_timeout.dat') );
}


// Check if we need to rebuild ROOT .htaccess / .user.ini
if ( $conf_php_timeout != $cached_php_timeout ) {

// Delete ROOT .htaccess / .user.ini
unlink($base_dir . '/.htaccess');
unlink($base_dir . '/.user.ini');
unlink($base_dir . '/cache/secured/.app_htpasswd');

// Cache the new PHP timeout
$ct_cache->save_file($base_dir . '/cache/vars/php_timeout.dat', $conf_php_timeout);

}


// Email TO service check
if ( isset($ct_conf['comms']['to_email']) && $ct_gen->valid_email($ct_conf['comms']['to_email']) == 'valid' ) {
$valid_to_email = true;
}


// Email FROM service check
if ( isset($ct_conf['comms']['from_email']) && $ct_gen->valid_email($ct_conf['comms']['from_email']) == 'valid' ) {
$valid_from_email = true;
}


// Notifyme service check
if ( isset($ct_conf['comms']['notifyme_accesscode']) && trim($ct_conf['comms']['notifyme_accesscode']) != '' ) {
$notifyme_activated = true;
}


// Texting (SMS) services check
// (if MORE THAN ONE is activated, keep ALL disabled to avoid a texting firestorm)
if ( isset($ct_conf['comms']['textbelt_apikey']) && trim($ct_conf['comms']['textbelt_apikey']) != '' ) {
$activated_sms_services[] = 'textbelt';
}


if (
isset($ct_conf['comms']['twilio_number']) && trim($ct_conf['comms']['twilio_number']) != ''
&& isset($ct_conf['comms']['twilio_sid']) && trim($ct_conf['comms']['twilio_sid']) != ''
&& isset($ct_conf['comms']['twilio_token']) && trim($ct_conf['comms']['twilio_token']) != ''
) {
$activated_sms_services[] = 'twilio';
}


// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if (
isset($ct_conf['comms']['textlocal_sender'])
&& trim($ct_conf['comms']['textlocal_sender']) != ''
&& isset($ct_conf['comms']['textlocal_apikey'])
&& $ct_conf['comms']['textlocal_apikey'] != ''
) {
$activated_sms_services[] = 'textlocal';
}


$text_email_gateway_check = explode("||", trim($ct_conf['comms']['to_mobile_text']) );


if (
isset($text_email_gateway_check[0])
&& isset($text_email_gateway_check[1])
&& trim($text_email_gateway_check[0]) != ''
&& trim($text_email_gateway_check[1]) != ''
&& trim($text_email_gateway_check[1]) != 'skip_network_name'
&& $ct_gen->valid_email( $ct_gen->text_email($ct_conf['comms']['to_mobile_text']) ) == 'valid'
) {
$activated_sms_services[] = 'email_gateway';
}


if ( sizeof($activated_sms_services) == 1 ) {
$sms_service = $activated_sms_services[0];
}
elseif ( sizeof($activated_sms_services) > 1 ) {
$ct_gen->log( 'conf_error', 'only one SMS service is allowed, please deactivate ALL BUT ONE of the following: ' . implode(", ", $activated_sms_services) );
}


// Backup archive password protection / encryption
if ( $ct_conf['sec']['backup_arch_pass'] != '' ) {
$backup_arch_pass = $ct_conf['sec']['backup_arch_pass'];
}
else {
$backup_arch_pass = false;
}


// Light chart config tracking / updating (checking for changes to light chart app config, to trigger light chart rebuilds)
$conf_light_chart_struct = md5( serialize($ct_conf['power']['light_chart_day_intervals']) . $ct_conf['power']['light_chart_data_points_max'] );

if ( !file_exists($base_dir . '/cache/vars/light_chart_struct.dat') ) {
$ct_cache->save_file($base_dir . '/cache/vars/light_chart_struct.dat', $conf_light_chart_struct);
$cached_light_chart_struct = $conf_light_chart_struct;
}
else {
$cached_light_chart_struct = trim( file_get_contents($base_dir . '/cache/vars/light_chart_struct.dat') );
}


// Check if we need to rebuild light charts from changes to their structure,
// OR a user-requested light chart reset
if (
$conf_light_chart_struct != $cached_light_chart_struct
|| $_POST['reset_light_charts'] == 1 && $ct_gen->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_light_charts')
) {

// Delete ALL light charts (this will automatically trigger a re-build)
$ct_cache->remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/light');
$ct_cache->remove_dir($base_dir . '/cache/charts/system/light');

// Cache the new light chart structure
$ct_cache->save_file($base_dir . '/cache/vars/light_chart_struct.dat', $conf_light_chart_struct);

}


// Configged google font
if ( isset($ct_conf['gen']['google_font']) && trim($ct_conf['gen']['google_font']) != '' ) {
          
$google_font_name = trim($ct_conf['gen']['google_font']);
     
$font_name_url_formatting = $google_font_name;
$font_name_url_formatting = preg_replace("/  /", " ", $font_name_url_formatting);
$font_name_url_formatting = preg_replace("/  /", " ", $font_name_url_formatting);
$font_name_url_formatting = preg_replace("/ /", "+", $font_name_url_formatting);

}


// Configged font size
if ( isset($_COOKIE['font_size']) ) {
$default_font_size = $_COOKIE['font_size']; // Already 'em' scale format
}
elseif ( $ct_var->whole_int($ct_conf['gen']['default_font_size']) ) {
$default_font_size = round( ($ct_conf['gen']['default_font_size'] * 0.01) , 3);
}
else {
$default_font_size = 1; // 'em' scale format
}


// Enforce min / max allowed values on the default font size
// (IN 'em' CSS-COMPATIBLE SCALING WE SWITCHED TO ABOVE)
if ( $default_font_size > 3 ) {
$default_font_size = 3;
}
elseif ( $default_font_size < 0.3 ) {
$default_font_size = 0.3;
}


$default_font_line_height = round( ($default_font_size * 1.35) , 3); // 135% of $default_font_size
     
$default_medium_font_size = round( ($default_font_size * 0.75) , 3); // 75% of $default_font_size
$default_medium_font_line_height = round( ($default_medium_font_size * 1.35) , 3); // 135% of $default_medium_font_size
     
$default_tiny_font_size = round( ($default_font_size * 0.55) , 3); // 55% of $default_font_size
$default_tiny_font_line_height = round( ($default_tiny_font_size * 1.35) , 3); // 135% of $default_tiny_font_size


// Alphabetically sort news feeds
$usort_feeds_results = usort($ct_conf['power']['news_feed'], array($ct_gen, 'titles_usort_alpha') );
   	
   	
if ( !$usort_feeds_results ) {
$ct_gen->log('other_error', 'RSS feeds failed to sort alphabetically');
}
      

// Set minimum CURRENCY value used in the app
$loop = 0;
$min_fiat_val_test = "0.";
while ( $loop < $ct_conf['gen']['currency_dec_max'] ) {
$loop = $loop + 1;
$min_fiat_val_test .= ( $loop < $ct_conf['gen']['currency_dec_max'] ? '0' : '1' );
}
unset($loop);
      

// Set minimum CRYPTO value used in the app (important for currency conversions on very low-value coins, like BONK etc)
$loop = 0;
$min_crypto_val_test = "0.";
while ( $loop < $ct_conf['gen']['crypto_dec_max'] ) {
$loop = $loop + 1;
$min_crypto_val_test .= ( $loop < $ct_conf['gen']['crypto_dec_max'] ? '0' : '1' );
}
unset($loop);


// Set "watch only" flag amount (sets portfolio amount one decimal MORE than allowed min value)
$watch_only_flag_val = preg_replace("/1/", "01", $min_crypto_val_test); // Set to 0.XXXXX01 instead of 0.XXXXX1


// Primary Bitcoin markets (MUST RUN AFTER app config auto-adjust)
require_once('app-lib/php/inline/config/primary-bitcoin-markets-config.php');

// Chart sub-directory creation (if needed...MUST RUN AFTER app config auto-adjust)
require_once('app-lib/php/inline/config/chart-directories-config.php');


//////////////////////////////////////////////////////////////////
// END CONFIG INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
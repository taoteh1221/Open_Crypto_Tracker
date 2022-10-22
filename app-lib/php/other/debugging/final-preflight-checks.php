<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// FINAL PREFLIGHT CHECKS
//////////////////////////////////////////////////////////////////


// CHECK FOR HEADER HOSTNAME SPOOFING ATTACKS (now that we have fully processed the app config)
if ( $runtime_mode != 'cron' ) {
    
$base_url_check = $ct_gen->base_url();


    if ( isset($base_url_check['security_error']) ) {
    $log_error_message = 'Domain security check for "' . $base_url_check['checked_url'] . '" FAILED (' . $remote_ip . '). POSSIBLE hostname header spoofing attack blocked, exiting app... <br /><br />';
	$ct_gen->log('security_error', $log_error_message);
	echo $log_error_message;
	$force_exit = 1;
    }


}


// Check htaccess security (checked once every 120 minutes maximum)
if ( $ct_cache->update_cache($base_dir . '/cache/events/scan-htaccess-security.dat', 120) == true && $app_edition == 'server' && isset($base_url_check) && trim($base_url_check) != '' && !isset($base_url_check['security_error']) ) {
	
		
// HTTPS CHECK ONLY (for security if htaccess user/pass activated), don't cache API data
	
// cache check
$htaccess_cache_test_url = $base_url_check . 'cache/htaccess_security_check.dat';

$htaccess_cache_test = trim( @$ct_cache->ext_data('url', $htaccess_cache_test_url, 0) ); 
	
// plugins check
$htaccess_plugins_test_url = $base_url_check . 'plugins/htaccess_security_check.dat';

$htaccess_plugins_test = trim( @$ct_cache->ext_data('url', $htaccess_plugins_test_url, 0) ); 
	
	
	if ( preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_cache_test)
	|| preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_plugins_test) ) {
	$log_error_message = "HTTP server 'htaccess' support has NOT been enabled on this web server for the 'cache' and 'plugins' sub-directories. 'htaccess' support is required to SAFELY run this application (htaccess security checks are throttled to a maximum of once every 2 hours). <br /><br />";
	$ct_gen->log('system_error', $log_error_message);
	echo $log_error_message;
	$force_exit = 1;
	}
	
	
// Update the htaccess security scan event tracking
$ct_cache->save_file($base_dir . '/cache/events/scan-htaccess-security.dat', $ct_gen->time_date_format(false, 'pretty_date_time') );

}


// Make sure we are using FastCGI
if ( $runtime_mode != 'cron' && !stristr( php_sapi_name() , 'fcgi') && $ct_conf['dev']['ignore_php_fpm_warning'] != 'yes' ) {
$log_error_message = "{Set 'ignore_php_fpm_warning' to 'yes' in Admin Config DEVELOPER section to disable this warning} <br /><br /> PHP is currently running as '" . php_sapi_name() . "', PHP-FPM (fcgi) mode is not running. PHP-FPM v7.2 or higher is HIGHLY RECOMMENDED to avoid low power devices OR high traffic installs from crashing. If you auto-installed, you can auto-upgrade if you FULLY re-install EVERYTHING with the latest auto-install script: https://tinyurl.com/install-crypto-tracker <br /><br />";
$ct_gen->log('system_error', $log_error_message);
echo $log_error_message;
$force_exit = 1;
}


// Exit, if server / app setup requirements not met
if ( $force_exit == 1 ) {
$system_error = 'Server OR app setup issues detected (SEE LOGGED SETUP ISSUES), exiting application';
$ct_gen->log('system_error', $system_error);
echo $system_error;
// Log errors before exiting
$ct_cache->error_log();
exit;
}
//////////////////////////////////////////////////////////////////
// END FINAL PREFLIGHT CHECKS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
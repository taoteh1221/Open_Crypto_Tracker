<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Check for runtime mode
if ( !$runtime_mode )  {
$system_error = 'No runtime mode detected, running WITHOUT runtime mode set is forbidden. <br /><br />';
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// PHP v5.5 or higher required for this app
if (PHP_VERSION_ID < 50500) {
$system_error = 'PHP version 5.5 or higher is required (PHP 7.0 OR HIGHER IS ---HIGHLY RECOMMENDED--- FOR UNICODE SUPPORT). Please upgrade your PHP version to run this application. <br /><br />';
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Check for curl
if ( !function_exists('curl_version') ) {
$system_error = "Curl for PHP (version ID ".PHP_VERSION_ID.") is not installed yet. Curl is required to run this application. <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Check for mbstring
if ( !extension_loaded('mbstring') ) {
$system_error = "PHP extension 'mbstring' not installed. 'mbstring' is required to run this application. <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Check for required Apache modules (if running on Apache)

// Check for mod_rewrite
if ( is_array($apache_modules) && !in_array('mod_rewrite', $apache_modules) ) {
$system_error = "HTTP web server Apache module 'mod_rewrite' is NOT installed on this web server. 'mod_rewrite' is required to run this application ( debian install command: a2enmod rewrite;/etc/init.d/apache2 restart ). <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}

// Check for mod_ssl
if ( is_array($apache_modules) && !in_array('mod_ssl', $apache_modules) ) {
$system_error = "HTTP web server Apache module 'mod_ssl' is NOT installed on this web server. 'mod_ssl' is required to SAFELY run this application ( debian install command: a2enmod ssl;a2ensite default-ssl;/etc/init.d/apache2 restart ). <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// IF WE ARE RUNNING THE INTERFACE, detect if we are running on a secure HTTPS (SSL) connection
if ( $runtime_mode == 'ui' ) {
	
	// Apache / etc
	if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
	$is_https_secure = true;
	}
	// NGINX etc
	elseif ( !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) {
	$is_https_secure = true;
	}
	else {
	$is_https_secure = false;
	}
	
	// Schedule app exit, if we are not on a secure connection
	if ( $is_https_secure != true ) {
	$system_error = "HTTP web server secure HTTPS (SSL) connection NOT detected. A secure HTTPS (SSL) connection is required to SAFELY run this application. <br /><br />";
	app_logging('system_error', $system_error);
	echo $system_error;
	$force_exit = 1;
	}

}



// Check htaccess security (checked once every 60 minutes maximum)
if ( update_cache_file($base_dir . '/cache/events/scan_htaccess_security.dat', 60) == true ) {
	
	
	// Only run the check if the base url is set (runs every ~10 minutes, so we'll be checking again anyway, and it should set AFTER first UI run)
	if ( trim($base_url) != '' ) {
	
	// cache check
	$htaccess_cache_test_url = $base_url . 'cache/htaccess_security_check.dat';

	$htaccess_cache_test_1 = trim( @api_data('url', $htaccess_cache_test_url, 0) ); // HTTPS CHECK, Don't cache API data

	$htaccess_cache_test_2 = trim( @api_data('url', preg_replace("/https:/i", "http:", $htaccess_cache_test_url), 0) ); // HTTP CHECK, Don't cache API data
	
	// cron-plugins check
	$htaccess_plugins_test_url = $base_url . 'cron-plugins/htaccess_security_check.dat';

	$htaccess_plugins_test_1 = trim( @api_data('url', $htaccess_plugins_test_url, 0) ); // HTTPS CHECK, Don't cache API data

	$htaccess_plugins_test_2 = trim( @api_data('url', preg_replace("/https:/i", "http:", $htaccess_plugins_test_url), 0) ); // HTTP CHECK, Don't cache API data
	
	
		if ( preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_cache_test_1)
		|| preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_cache_test_2)
		|| preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_plugins_test_1)
		|| preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_plugins_test_2) ) {
		$system_error = "HTTP server 'htaccess' support has NOT been enabled on this web server for the 'cache' and 'cron-plugins' sub-directories. 'htaccess' support is required to SAFELY run this application (htaccess security checks are throttled to a maximum of once every hour). <br /><br />";
		app_logging('system_error', $system_error);
		echo $system_error;
		$force_exit = 1;
		}
	
	
	}
	
	
// Update the htaccess security scan event tracking
store_file_contents($base_dir . '/cache/events/scan_htaccess_security.dat', time_date_format(false, 'pretty_date_time') );

}



// Exit, if server / app setup requirements not met
if ( $force_exit == 1 ) {
$system_error = 'Server / app setup requirements not met (SEE LOGGED SETUP DEFICIENCIES), exiting application...';
app_logging('system_error', $system_error);
echo $system_error;
// Log errors before exiting
error_logs();
exit;
}

  
 
 ?>
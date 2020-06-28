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
if (PHP_VERSION_ID < 70200) {
$system_error = 'PHP version 7.2 or higher is required. Please upgrade your PHP version to run this application. <br /><br />';
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Make sure we are using FastCGI
if ( $runtime_mode != 'cron' && !stristr( php_sapi_name() , 'fcgi') && $app_config['developer']['ignore_php_fpm_warning'] != 'yes' ) {
$system_error = "{Set \$app_config['developer']['ignore_php_fpm_warning'] to 'yes' in config.php to disable this warning} <br /><br /> PHP is currently running as '" . php_sapi_name() . "', PHP-FPM (fcgi) mode is not running. PHP-FPM v7.2 or higher is HIGHLY RECOMMENDED to avoid low power devices OR high traffic installs from crashing. If you auto-installed, you can auto-upgrade if you FULLY re-install EVERYTHING with the latest auto-install script: https://git.io/JeWWE <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Check for curl
if ( !extension_loaded('curl') ) {
$system_error = "PHP extension 'php-curl' not installed. 'php-curl' is required to run this application. <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Check for xml
if ( !extension_loaded('xml') ) {
$system_error = "PHP extension 'php-xml' not installed. 'php-xml' is required to run this application. <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Check for gd
if ( !extension_loaded('gd') ) {
$system_error = "PHP extension 'php-gd' not installed. 'php-gd' is required to run this application. <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Check for mbstring
if ( !extension_loaded('mbstring') ) {
$system_error = "PHP extension 'php-mbstring' not installed. 'php-mbstring' is required to run this application. <br /><br />";
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// Check for zip
if ( !extension_loaded('zip') ) {
$system_error = "PHP extension 'php-zip' not installed. 'php-zip' is required to run this application. <br /><br />";
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
if ( update_cache_file($base_dir . '/cache/events/scan-htaccess-security.dat', 60) == true ) {
	
	
	// Only run the check if the base url is set (runs every ~10 minutes, so we'll be checking again anyway, and it should set AFTER first UI run)
	if ( trim($base_url) != '' ) {
		
	// HTTPS CHECK ONLY (for security if htaccess user/pass activated), don't cache API data
	
	// cache check
	$htaccess_cache_test_url = $base_url . 'cache/htaccess_security_check.dat';

	$htaccess_cache_test = trim( @external_api_data('url', $htaccess_cache_test_url, 0) ); 
	
	// cron-plugins check
	$htaccess_plugins_test_url = $base_url . 'cron-plugins/htaccess_security_check.dat';

	$htaccess_plugins_test = trim( @external_api_data('url', $htaccess_plugins_test_url, 0) ); 
	
	
		if ( preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_cache_test)
		|| preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_plugins_test) ) {
		$system_error = "HTTP server 'htaccess' support has NOT been enabled on this web server for the 'cache' and 'cron-plugins' sub-directories. 'htaccess' support is required to SAFELY run this application (htaccess security checks are throttled to a maximum of once every hour). <br /><br />";
		app_logging('system_error', $system_error);
		echo $system_error;
		$force_exit = 1;
		}
	
	
	}
	
	
// Update the htaccess security scan event tracking
store_file_contents($base_dir . '/cache/events/scan-htaccess-security.dat', time_date_format(false, 'pretty_date_time') );

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
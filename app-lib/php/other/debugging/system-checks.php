<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Create cache directories (if needed), with $http_runtime_user determined further above 
// (for cache compatibility on certain PHP setups)

// Check for cache directory path creation, create if needed...if it fails, exit and alert end-user
if ( dir_structure($base_dir . '/cache/alerts/') != true
|| dir_structure($base_dir . '/cache/apis/') != true
|| dir_structure($base_dir . '/cache/charts/spot_price_24hr_volume/archival/') != true
|| dir_structure($base_dir . '/cache/charts/spot_price_24hr_volume/lite/') != true
|| dir_structure($base_dir . '/cache/charts/system/archival/') != true
|| dir_structure($base_dir . '/cache/charts/system/lite/') != true
|| dir_structure($base_dir . '/cache/events/') != true
|| dir_structure($base_dir . '/cache/logs/debugging/api/') != true
|| dir_structure($base_dir . '/cache/logs/errors/api/') != true
|| dir_structure($base_dir . '/cache/secured/backups/') != true
|| dir_structure($base_dir . '/cache/secured/messages/') != true
|| dir_structure($base_dir . '/cache/vars/') != true ) {
echo "Cannot create cache sub-directories. Please make sure the folder '/cache/' has FULL read / write permissions (chmod 777 on unix / linux systems), so the cache sub-directories can be created automatically.";
$force_exit = 1;
}



// Directory security check (MUST run AFTER directory structure creation check, AND BEFORE htaccess check)
require_once('app-lib/php/other/security/directory.php');



// Check for runtime mode
if ( !$runtime_mode )  {
echo 'No runtime mode detected, running WITHOUT runtime mode set is forbidden. <br /><br />';
$force_exit = 1;
}



// PHP v5.5 or higher required for this app
if (PHP_VERSION_ID < 50500) {
echo 'PHP version 5.5 or higher is required (PHP 7.0 OR HIGHER IS ---HIGHLY RECOMMENDED--- FOR UNICODE SUPPORT). Please upgrade your PHP version to run this application. <br /><br />';
$force_exit = 1;
}



// Check for curl
if ( !function_exists('curl_version') ) {
echo "Curl for PHP (version ID ".PHP_VERSION_ID.") is not installed yet. Curl is required to run this application. <br /><br />";
$force_exit = 1;
}



// Check for mbstring
if ( !extension_loaded('mbstring') ) {
echo "PHP extension 'mbstring' not installed. 'mbstring' is required to run this application. <br /><br />";
$force_exit = 1;
}



// Check for required Apache modules (if running on Apache)

// Check for mod_rewrite
if ( is_array($apache_modules) && !in_array('mod_rewrite', $apache_modules) ) {
echo "HTTP web server Apache module 'mod_rewrite' is NOT installed on this web server. 'mod_rewrite' is required to run this application ( debian install command: a2enmod rewrite;/etc/init.d/apache2 restart ). <br /><br />";
$force_exit = 1;
}

// Check for mod_ssl
if ( is_array($apache_modules) && !in_array('mod_ssl', $apache_modules) ) {
echo "HTTP web server Apache module 'mod_ssl' is NOT installed on this web server. 'mod_ssl' is required to SAFELY run this application ( debian install command: a2enmod ssl;a2ensite default-ssl;/etc/init.d/apache2 restart ). <br /><br />";
$force_exit = 1;
}



// IF WE ARE RUNNING THE INTERFACE, detect if we are running on a secure HTTPS (SSL) connection
if ( $runtime_mode == 'ui' ) {

	if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
	$is_https_secure = true;
	}
	elseif ( !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) {
	$is_https_secure = true;
	}
	else {
	$is_https_secure = false;
	}
	
	// Schedule app exit, if we are not on a secure connection
	if ( $is_https_secure != true ) {
	echo "HTTP web server secure HTTPS (SSL) connection NOT detected. A secure HTTPS (SSL) connection is required to SAFELY run this application. <br /><br />";
	$force_exit = 1;
	}

}



// Check htaccess security (checked once every 5 minutes maximum)
if ( update_cache_file($base_dir . '/cache/events/scan_htaccess_security.dat', 5) == true ) {
	
	
	// If base url is not set yet, and we are ui runtime
	if ( $runtime_mode == 'ui' ) {
	$temp_base_url = ( trim($base_url) != '' ? $base_url : base_url() );
	}


$htaccess_test_url = $temp_base_url . 'cache/htaccess_security_check.dat';

$htaccess_test_1 = trim( @api_data('url', $htaccess_test_url, 0) ); // HTTPS CHECK, Don't cache API data

$htaccess_test_2 = trim( @api_data('url', preg_replace("/https:/i", "http:", $htaccess_test_url), 0) ); // HTTP CHECK, Don't cache API data
	
	
	if ( preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_test_1) || preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_test_2) ) {
	echo "HTTP server 'htaccess' support has NOT been enabled on this web server. 'htaccess' support is required to SAFELY run this application. Please wait at least five minutes AFTER FIXING THIS ISSUE before running the application again (htaccess security checks are throttled to a maximum of once every five minutes). <br /><br />";
	$force_exit = 1;
	}
	
	
// Update the htaccess security scan event tracking
store_file_contents($base_dir . '/cache/events/scan_htaccess_security.dat', time_date_format(false, 'pretty_date_time') );

}



// Exit, if server / app setup requirements not met
if ( $force_exit == 1 ) {
echo 'Server / app setup requirements not met (SEE ABOVE SETUP DEFICIENCIES), exiting application...';
exit;
}

  
 
 ?>
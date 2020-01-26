<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Check for runtime mode
if ( !$runtime_mode )  {
echo 'No runtime mode detected, running without runtime mode set is forbidden. <br /><br />';
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
else {
$curl_setup = curl_version();
define('CURL_VERSION_ID', str_replace(".", "", $curl_setup["version"]) );
}



// Check for mbstring
if ( !extension_loaded('mbstring') ) {
echo "PHP extension 'mbstring' not installed. 'mbstring' is required to run this application. <br /><br />";
$force_exit = 1;
}



// Check for required Apache modules (if on Apache)
if ( function_exists('apache_get_modules') ) {
$apache_modules = apache_get_modules(); // Minimize function calls
}

// Check for mod_rewrite
if ( is_array($apache_modules) && !in_array('mod_rewrite', $apache_modules) ) {
echo "HTTP server Apache module 'mod_rewrite' not installed. 'mod_rewrite' is required to run this application. <br /><br />";
$force_exit = 1;
}

// Check for mod_ssl
if ( is_array($apache_modules) && !in_array('mod_ssl', $apache_modules) ) {
echo "HTTP server Apache module 'mod_ssl' not installed. 'mod_ssl' is required to run this application. <br /><br />";
$force_exit = 1;
}



// Exit, if server / app setup requirements not met
if ( $force_exit == 1 ) {
echo 'Server / app setup requirements not met (SEE ABOVE SETUP DEFICIENCIES), exiting application...';
exit;
}

  
 
 ?>
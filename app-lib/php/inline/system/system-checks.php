<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


if ( PHP_OS_FAMILY == 'Windows' && $php_ini_path ) {
$ext_win_php_ini = ' (try enabling it in the file: ' . $php_ini_path . ')';
}


// Check for runtime mode
if ( !$runtime_mode )  {
$system_error = '<br />No runtime mode detected, running WITHOUT runtime mode set is forbidden. <br />';
echo $system_error;
$force_exit = 1;
}



// PHP v5.5 or higher required for this app
if (PHP_VERSION_ID < 70200) {
$system_error = '<br />PHP version 7.2 or higher is required. Please upgrade your PHP version to run this application. <br />';
echo $system_error;
$force_exit = 1;
}



// Check for xml
if ( !extension_loaded('xml') ) {
$system_error = "<br />PHP extension 'php-xml' not installed. 'php-xml' is required to run this application" . $ext_win_php_ini . ". <br />";
echo $system_error;
$force_exit = 1;
}



// Check for mbstring
if ( !extension_loaded('mbstring') ) {
$system_error = "<br />PHP extension 'php-mbstring' not installed. 'php-mbstring' is required to run this application" . $ext_win_php_ini . ". <br />";
echo $system_error;
$force_exit = 1;
}



// Check for curl
if ( !extension_loaded('curl') ) {
$system_error = "<br />PHP extension 'php-curl' not installed. 'php-curl' is required to run this application" . $ext_win_php_ini . ". <br />";
echo $system_error;
$force_exit = 1;
}


// Check for zip
if ( !extension_loaded('zip') ) {
$system_error = "<br />PHP extension 'php-zip' not installed. 'php-zip' is required to run this application" . $ext_win_php_ini . ". <br />";
echo $system_error;
$force_exit = 1;
}



// Check for gd
if ( !extension_loaded('gd') ) {
$system_error = "<br />PHP extension 'php-gd' not installed. 'php-gd' is required to run this application" . $ext_win_php_ini . ". <br />";
echo $system_error;
$force_exit = 1;
}



// Check for required Apache modules (if running on Apache)

// Check for mod_rewrite
if ( is_array($apache_modules) && !in_array('mod_rewrite', $apache_modules) ) {
$system_error = "<br />HTTP web server Apache module 'mod_rewrite' is NOT installed on this web server. 'mod_rewrite' is required to run this application ( debian install command: a2enmod rewrite;/etc/init.d/apache2 restart ). <br />";
echo $system_error;
$force_exit = 1;
}



// IF WE ARE NOT RUNNING AS CRON, detect if we are running on a secure HTTPS (SSL) connection
if ( $runtime_mode != 'cron' && $app_edition == 'server' ) {
	
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
	$system_error = "<br />A secure HTTPS (SSL) connection is required to SAFELY run this app. Try visiting https://YOUR_HOSTNAME to see if HTTPS is setup properly. <br />";
	echo $system_error;
	$force_exit = 1;
	}

}



// Exit, if server / app setup requirements not met
if ( $force_exit == 1 ) {
$system_error = '<br />Server / app setup requirements not met (SEE LOGGED SETUP DEFICIENCIES), exiting application. <br /><br />';
echo $system_error;
exit;
}

  
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
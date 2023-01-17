<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// GENERAL PREFLIGHT SECURITY CHECKS
//////////////////////////////////////////////////////////////////


// Recreate /.htaccess for optional password access restriction / mod rewrite etc
if ( !file_exists($base_dir . '/.htaccess') ) {
$ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
sleep(1);
}

// Recreate /.user.ini for optional php-fpm php.ini control
if ( !file_exists($base_dir . '/.user.ini') ) {
$ct_cache->save_file($base_dir . '/.user.ini', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-user-ini.template') ); 
sleep(1);
}


///////////////////////////////////////////


// Htaccess password-protection
$htaccess_protection_check = file_get_contents($base_dir . '/.htaccess');

// FAILSAFE, FOR ANY EXISTING CRON JOB TO BAIL US OUT IF USER DELETES CACHE DIRECTORY WHERE AN ACTIVE LINKED PASSWORD FILE IS 
// (CAUSING INTERFACE TO CRASH WITH ERROR 500)
if ( preg_match("/Require valid-user/i", $htaccess_protection_check) && !is_readable($base_dir . '/cache/secured/.app_htpasswd') ) {
// Default htaccess root file, WITH NO PASSWORD PROTECTION
$restore_default_htaccess = $ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
}


// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ) {

	// If NO SETUP password protection exists
	if ( !preg_match("/Require valid-user/i", $htaccess_protection_check) || $refresh_cached_ct_conf == 1 ) {
		
	$password_protection_enabled = $ct_cache->htaccess_dir_protection();
	
		if ( !$password_protection_enabled ) {
			
		// Default htaccess root file, WITH NO PASSWORD PROTECTION
		$restore_default_htaccess = $ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
			
			// Avoid error 500 if htaccess update fails
			if ( $restore_default_htaccess == true ) {
			@unlink($base_dir . '/cache/secured/.app_htpasswd'); 
			}
		
		}
	
	}

}
// No password protection
elseif ( $htaccess_username == '' || $htaccess_password == '' ) {

	// If ALREADY SETUP password protection exists
	if ( preg_match("/Require valid-user/i", $htaccess_protection_check) ) {
		
	// Default htaccess root file, WITH NO PASSWORD PROTECTION
	$restore_default_htaccess = $ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
	
		// Avoid error 500 if htaccess update fails
		if ( $restore_default_htaccess == true ) {
		@unlink($base_dir . '/cache/secured/.app_htpasswd');
		}
	
	}
		
}


//////////////////////////////////////////////////////////////////
// END GENERAL PREFLIGHT SECURITY CHECKS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
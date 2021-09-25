<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


$htaccess_protection_check = file_get_contents($base_dir . '/.htaccess');

// Htaccess password-protection

// FAILSAFE, FOR ANY EXISTING CRON JOB TO BAIL US OUT IF USER DELETES CACHE DIRECTORY WHERE AN ACTIVE LINKED PASSWORD FILE IS 
// (CAUSING INTERFACE TO CRASH WITH ERROR 500)
if ( preg_match("/Require valid-user/i", $htaccess_protection_check) && !is_readable($base_dir . '/cache/secured/.app_htpasswd') ) {
// Default htaccess root file, WITH NO PASSWORD PROTECTION
$restore_default_htaccess = $pt_cache->save_file($base_dir . '/.htaccess', $pt_cache->htaccess_dir_defaults() ); 
}


// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $htaccess_username != '' && $htaccess_password != '' ) {

	// If NO SETUP password protection exists
	if ( !preg_match("/Require valid-user/i", $htaccess_protection_check) || $refresh_cached_pt_conf == 1 ) {
		
	$password_protection_enabled = $pt_cache->htaccess_dir_protection();
	
		if ( !$password_protection_enabled ) {
			
		// Default htaccess root file, WITH NO PASSWORD PROTECTION
		$restore_default_htaccess = $pt_cache->save_file($base_dir . '/.htaccess', $pt_cache->htaccess_dir_defaults() ); 
			
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
	$restore_default_htaccess = $pt_cache->save_file($base_dir . '/.htaccess', $pt_cache->htaccess_dir_defaults() ); 
	
		// Avoid error 500 if htaccess update fails
		if ( $restore_default_htaccess == true ) {
		@unlink($base_dir . '/cache/secured/.app_htpasswd');
		}
	
	}
		
}


 
?>
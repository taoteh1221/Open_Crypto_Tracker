<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Htaccess password-protection
if ( trim($app_config['htaccess_username']) != '' && trim($app_config['htaccess_password']) != '' ) {

$htaccess_protection_check = file_get_contents($base_dir . '/.htaccess');

	if ( !preg_match("/Require valid-user/i", $htaccess_protection_check) || $refresh_cached_app_config == 1 ) {
		
	$password_protection_enabled = htaccess_directory_protection();
	
		if ( !$password_protection_enabled ) {
			
		// Default htaccess root file, WITH NO PASSWORD PROTECTION
		$restore_default_htaccess = store_file_contents($base_dir . '/.htaccess', file_get_contents($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
			
			// Avoid error 500 if htaccess update fails
			if ( $restore_default_htaccess == true ) {
			@unlink($base_dir . '/cache/secured/.app_htpasswd'); 
			}
		
		}
	
	}

}
elseif ( trim($app_config['htaccess_username']) == '' || trim($app_config['htaccess_password']) == '' ) {
	
// Default htaccess root file, WITH NO PASSWORD PROTECTION
$restore_default_htaccess = store_file_contents($base_dir . '/.htaccess', file_get_contents($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
	
	// Avoid error 500 if htaccess update fails
	if ( $restore_default_htaccess == true ) {
	@unlink($base_dir . '/cache/secured/.app_htpasswd');
	}
		
}


 
?>
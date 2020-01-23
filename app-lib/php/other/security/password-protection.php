<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Htaccess password-protection
if ( trim($app_config['htaccess_username']) != '' && trim($app_config['htaccess_password']) != '' ) {

$htaccess_protection_check = file_get_contents($base_dir . '/.htaccess');

	if ( !preg_match("/Require valid-user/i", $htaccess_protection_check) ) {
	htaccess_directory_protection();
	}

}
elseif ( trim($app_config['htaccess_username']) == '' && trim($app_config['htaccess_password']) == '' ) {
store_file_contents($base_dir . '/.htaccess', file_get_contents($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
unlink($base_dir . '/cache/secured/.htpasswd');
}


 
?>
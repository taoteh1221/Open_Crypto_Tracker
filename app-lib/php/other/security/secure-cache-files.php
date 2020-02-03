<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Secured cache files data
$secured_cache_files = sort_files($base_dir . '/cache/secured', 'dat', 'desc');

$app_config_check = trim( file_get_contents($base_dir . '/cache/vars/app_config_md5.dat') );


foreach( $secured_cache_files as $secured_file ) {

	if ( preg_match("/app_config_/i", $secured_file) ) {
		
		// If $cached_app_config already is set, delete any older instances (since we sort by timestamp desc here)
		if ( $cached_app_config == true ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
		$cached_app_config = json_decode( trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) , TRUE);
		}
		
	
		if ( $app_config_check == md5(serialize($original_app_config)) && $cached_app_config == true ) {
		$app_config = $cached_app_config; // Use cached app_config if it exists, seems intact, and config.php hasn't been revised since last check
		$is_cached_app_config = 1;
		}
		elseif ( $app_config_check != md5(serialize($original_app_config)) ) {
		app_logging('config_error', 'Cached app_config out of date (default app_config settings updated), deleting cached app_config (refresh will happen automatically)');
		unlink($base_dir . '/cache/secured/' . $secured_file);
		$refresh_cached_app_config = 1;
		}
		elseif ( $cached_app_config != true ) {
		app_logging('config_error', 'Cached app_config data appears corrupted, deleting cached app_config (refresh will happen automatically)');
		unlink($base_dir . '/cache/secured/' . $secured_file);
		$refresh_cached_app_config = 1;
		}
	
	}
	
	if ( preg_match("/pepper_var_/i", $secured_file) ) {
		
		// If $password_pepper already is set, delete any older instances (since we sort by timestamp desc here)
		if ( isset($password_pepper) ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
		$password_pepper = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
		}
	
	}

}




// If no password pepper
if ( !$password_pepper ) {
	
$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = random_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for suffix
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
	app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for pepper var (secured cache storage) suffix, pepper var creation aborted to preserve security');
	}
	else {
	store_file_contents($base_dir . '/cache/secured/pepper_var_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$password_pepper = $secure_256bit_hash;
	}


}




// If no valid cached_app_config, or if config.php variables have been changed
if ( $refresh_cached_app_config == 1 || $is_cached_app_config != 1 ) {
	
$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false ) {
	app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for cached app_config array (secured cache storage) suffix, cached app_config array creation aborted to preserve security');
	}
	else {
		
	$store_cached_app_config = json_encode($app_config, JSON_PRETTY_PRINT);
	
		if ( $store_cached_app_config == false ) {
		app_logging('config_error', 'app_config data could not be saved (to secured cache storage) in json format');
		}
		else {
		store_file_contents($base_dir . '/cache/secured/app_config_'.$secure_128bit_hash.'.dat',$store_cached_app_config);
		store_file_contents($base_dir . '/cache/vars/app_config_md5.dat', md5(serialize($original_app_config))); // For checking later, if config.php values are updated we save to json again
		}
	
	}


}



 
?>
<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Activating an existing admin password reset session 
// (MUST RUN #AFTER GETTING CACHED APP CONFIG)
if ( trim($_GET['new_reset_key']) != '' ) {

// Secured activation code data
$activation_files = sort_files($base_dir . '/cache/secured/activation', 'dat', 'desc');


	foreach( $activation_files as $activation_file ) {
	
		if ( preg_match("/password_reset_/i", $activation_file) ) {
		
			// If we already loaded the newest modified file, delete any stale ones
			if ( $newest_cached_password_reset == 1 ) {
			unlink($base_dir . '/cache/secured/activation/' . $activation_file);
			}
			else {
			$newest_cached_password_reset = 1;
			$stored_reset_key = trim( file_get_contents($base_dir . '/cache/secured/activation/' . $activation_file) );
			$stored_reset_key_path = $base_dir . '/cache/secured/activation/' . $activation_file; // To easily delete, if admin reset
			}
	
		}
	
	}

	
	// If reset security key checks pass and a valid admin 'to' email exists, flag as an activated reset in progress (to trigger logic later in runtime)
	
	$app_config['comms']['to_email'] = auto_correct_string($app_config['comms']['to_email'], 'lower'); // Clean / auto-correct
	
	if ( $_GET['new_reset_key'] == $stored_reset_key && validate_email($app_config['comms']['to_email']) == 'valid' ) {
	$password_reset_approved = 1;
	}
	else {
	$password_reset_denied = 1; // For reset page UI
	}
	

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Secured cache files global variables
$secured_cache_files = sort_files($base_dir . '/cache/secured', 'dat', 'desc');

$app_config_check = trim( file_get_contents($base_dir . '/cache/vars/app_config_md5.dat') );


foreach( $secured_cache_files as $secured_file ) {

	// App config
	if ( preg_match("/app_config_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $newest_cached_app_config == 1 ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
		
		$newest_cached_app_config = 1;
			
		$cached_app_config = json_decode( trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) , TRUE);
			
		
			if ( $app_config_check == md5(serialize($original_app_config)) && $cached_app_config == true ) {
			$app_config = $cached_app_config; // Use cached app_config if it exists, seems intact, and config.php hasn't been revised since last check
			$is_cached_app_config = 1;
			}
			elseif ( $app_config_check != md5(serialize($original_app_config)) ) {
			app_logging('config_error', 'CACHED app_config outdated (DEFAULT app_config updated), refreshing from DEFAULT app_config');
			unlink($base_dir . '/cache/secured/' . $secured_file);
			$refresh_cached_app_config = 1;
			}
			elseif ( $cached_app_config != true ) {
			app_logging('config_error', 'CACHED app_config appears corrupt, refreshing from DEFAULT app_config');
			unlink($base_dir . '/cache/secured/' . $secured_file);
			$refresh_cached_app_config = 1;
			}
			
			
		}
		
	
	}

	
	// Telegram user data
	elseif ( preg_match("/telegram_user_data_/i", $secured_file) ) {
		
		// If we already loaded the newest modified telegram config file
		// DON'T WORRY ABOUT REFRESHING TELEGRAM DATA WHEN APP CONFIG IS REFRESHING, AS WE CAN'T DO THAT RELIABLY IN THIS LOOP
		// AND IT'S DONE AFTER THE LOOP ANYWAY (WE JUST CLEANUP ANY STALE TELEGRAM CONFIGS IN THIS LOOP)
		if ( $newest_cached_telegram_user_data == 1 ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
		
		$newest_cached_telegram_user_data = 1;
			
			// If $cached_telegram_user_data already is set, delete any older instances (since we sort by timestamp desc here)
			if ( $cached_telegram_user_data == true ) {
			unlink($base_dir . '/cache/secured/' . $secured_file);
			}
			else {
			$cached_telegram_user_data = json_decode( trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) , TRUE);
			}
			
			
			if ( $cached_telegram_user_data == true ) {
			$telegram_user_data = $cached_telegram_user_data;
			$is_cached_telegram_user_data = 1;
			}
			elseif ( $cached_telegram_user_data != true ) {
			app_logging('config_error', 'Cached telegram_user_data appears corrupted, deleting cached telegram_user_data (refresh will happen automatically)');
			unlink($base_dir . '/cache/secured/' . $secured_file);
			$refresh_cached_telegram_user_data = 1;
			}
		
		
		}
	
	
	}
	
	
	// Pepper var (for secure hashed password storage)
	elseif ( preg_match("/pepper_var_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $newest_cached_pepper_var == 1 ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
		$newest_cached_pepper_var = 1;
		$password_pepper = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
		}
	
	
	}
	
	
	// Webhook secret key (for secure webhook communications)
	elseif ( preg_match("/webhook_key_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $newest_cached_webhook_key == 1 ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
			
			// If an webhook secret key reset from authenticated admin is verified
			if ( $_POST['reset_webhook_key'] == 1 && admin_hashed_nonce('reset_webhook_key') != false && $_POST['admin_hashed_nonce'] == admin_hashed_nonce('reset_webhook_key') ) {
				
			unlink($base_dir . '/cache/secured/' . $secured_file);
			
			// Redirect to avoid quirky page reloads later on
			header("Location: admin.php");
			exit;
			
			}
			else {
			$newest_cached_webhook_key = 1;
			$webhook_key = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
			}
		
		}
	
	
	}
	
	
	// API key (for secure API communications)
	elseif ( preg_match("/api_key_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $newest_cached_api_key == 1 ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
			
			// If an API key reset from authenticated admin is verified
			if ( $_POST['reset_api_key'] == 1 && admin_hashed_nonce('reset_api_key') != false && $_POST['admin_hashed_nonce'] == admin_hashed_nonce('reset_api_key') ) {
				
			unlink($base_dir . '/cache/secured/' . $secured_file);
			
			// Redirect to avoid quirky page reloads later on
			header("Location: admin.php");
			exit;
			
			}
			else {
			$newest_cached_api_key = 1;
			$api_key = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
			}
		
		}
	
	
	}
	
	
	// Stored admin login user / hashed password (for admin login authentication)
	elseif ( preg_match("/admin_login_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $newest_cached_admin_login == 1 ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
		$newest_cached_admin_login = 1;
		$stored_admin_login = explode("||", trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) );
		$active_admin_login_path = $base_dir . '/cache/secured/' . $secured_file; // To easily delete, if we are resetting the login
		}
	
	
	}
	
	
	// Any outdated var names we no longer use are safe to delete
	else {
	unlink($base_dir . '/cache/secured/' . $secured_file);
	}
	

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


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
		store_file_contents($base_dir . '/cache/secured/app_config_'.$secure_128bit_hash.'.dat', $store_cached_app_config);
		store_file_contents($base_dir . '/cache/vars/app_config_md5.dat', md5(serialize($original_app_config))); // For checking later, if config.php values are updated we save to json again
		}
	
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If telegram messaging is activated, and there is no valid cached_telegram_user_data
// OR if cached app_config was flagged to be updated
if ( $telegram_activated == 1 && $refresh_cached_telegram_user_data == 1 
|| $telegram_activated == 1 && $is_cached_telegram_user_data != 1
|| $telegram_activated == 1 && $refresh_cached_app_config == 1 
|| $telegram_activated == 1 && $is_cached_app_config != 1 ) {
	
$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false ) {
	app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for cached telegram_user_data array (secured cache storage) suffix, cached telegram_user_data array creation aborted to preserve security');
	}
	else {
	
	$telegram_user_data = telegram_user_data('updates');
		
	$store_cached_telegram_user_data = json_encode($telegram_user_data, JSON_PRETTY_PRINT);
		
		// Need to check a few different possible results for no data found ("null" in quotes as the actual value is returned sometimes)
		if ( $store_cached_telegram_user_data == false || $store_cached_telegram_user_data == null || $store_cached_telegram_user_data == "null" ) {
		app_logging('config_error', 'UPDATED telegram_user_data could not be saved, PLEASE RE-ENTER "/start" IN THE BOT CHATROOM, IN THE TELEGRAM APP');
		}
		else {
		store_file_contents($base_dir . '/cache/secured/telegram_user_data_'.$secure_128bit_hash.'.dat', $store_cached_telegram_user_data);
		}
	
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no password pepper
if ( !$password_pepper ) {
	
$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = random_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
	app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for pepper var (in secured cache storage), pepper var creation aborted to preserve security');
	}
	else {
	store_file_contents($base_dir . '/cache/secured/pepper_var_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$password_pepper = $secure_256bit_hash;
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no webhook key
if ( !$webhook_key ) {
	
$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = random_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
	app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for webhook key (in secured cache storage), webhook key creation aborted to preserve security');
	}
	else {
	store_file_contents($base_dir . '/cache/secured/webhook_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$webhook_key = $secure_256bit_hash;
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no API key
if ( !$api_key ) {
	
$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = random_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
	app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for API key (in secured cache storage), API key creation aborted to preserve security');
	}
	else {
	store_file_contents($base_dir . '/cache/secured/api_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$api_key = $secure_256bit_hash;
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no admin login or an activated reset, valid user / pass are submitted, AND CAPTCHA MATCHES, store the new admin login
if ( $password_reset_approved || sizeof($stored_admin_login) != 2 ) {
	

	if ( valid_username( trim($_POST['set_username']) ) == 'valid' 
&& password_strength($_POST['set_password'], 12, 40) == 'valid' 
&& $_POST['set_password'] == $_POST['set_password2'] 
&& trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) == strtolower($_SESSION['captcha_code']) ) {
	
	
	$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
	$secure_password_hash = pepper_hashed_password($_POST['set_password']); // Peppered password hash
	
	
		// (random hash) Halt the process if an issue is detected safely creating a random hash
		if ( $secure_128bit_hash == false ) {
		app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for admin login (in secured cache storage), admin login creation aborted to preserve security');
		}
		// (peppered password) Halt the process if an issue is detected safely creating a random hash
		elseif ( $secure_password_hash == false ) {
		app_logging('security_error', 'A peppered password hash could not be generated for admin login, admin login creation aborted to preserve security');
		}
		else {
		store_file_contents($base_dir . '/cache/secured/admin_login_'.$secure_128bit_hash.'.dat', trim($_POST['set_username']) . '||' . $secure_password_hash);
		$stored_admin_login = array( trim($_POST['set_username']), $secure_password_hash);
		$admin_login_updated = 1;
		}

		
		
		// If the admin login update was a success, delete old data file / login / redirect
		if ( $admin_login_updated ) {
		
			// Delete any previous active admin login data file
			if ( $active_admin_login_path ) {
			unlink($active_admin_login_path);
			}
			
			// Delete any stored reset key
			if ( $stored_reset_key_path ) {
			unlink($stored_reset_key_path);
			}
			
	
		// Login now, before redirect
		$_SESSION['admin_logged_in'] = $stored_admin_login;
		
		// Redirect to avoid quirky page reloads later on, AND preset the admin login page for good UX
		header("Location: admin.php");
		exit;
		
		}
		else {
		app_logging('security_error', 'Admin login could not be updated', 'remote_address: ' . $_SERVER['REMOTE_ADDR']);
		}
	

	}
	
	
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


 
?>
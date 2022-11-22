<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Activating an existing admin password reset session 
// (MUST RUN #AFTER GETTING CACHED APP CONFIG)
if ( isset($_GET['new_reset_key']) && trim($_GET['new_reset_key']) != '' ) {

// Secured activation code data
$activation_files = $ct_gen->sort_files($base_dir . '/cache/secured/activation', 'dat', 'desc');


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
	
	$ct_conf['comms']['to_email'] = $ct_var->auto_correct_str($ct_conf['comms']['to_email'], 'lower'); // Clean / auto-correct
	
	if ( isset($stored_reset_key) && trim($stored_reset_key) != '' && $_GET['new_reset_key'] == $stored_reset_key && $ct_gen->valid_email($ct_conf['comms']['to_email']) == 'valid' ) {
	    
        // One last check for password resets
        if ( isset($_POST['new_reset_key']) && $_POST['new_reset_key'] != $_GET['new_reset_key'] ) {
        $password_reset_denied = 1;
        }
        else {
        $password_reset_approved = 1;
        }
	
	}
	else {
	$password_reset_denied = 1; // For reset page UI
	}
	

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Secured cache files
$secured_cache_files = $ct_gen->sort_files($base_dir . '/cache/secured', 'dat', 'desc');

// WE LOAD ct_conf WAY EARLIER, SO IT'S NOT INCLUDED HERE

foreach( $secured_cache_files as $secured_file ) {
	
	
	// Pepper var (for secure hashed password storage)
	if ( preg_match("/pepper_var_/i", $secured_file) ) {
		
		
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
			if ( $_POST['reset_webhook_key'] == 1 && $ct_gen->admin_hashed_nonce('reset_webhook_key') != false && $_POST['admin_hashed_nonce'] == $ct_gen->admin_hashed_nonce('reset_webhook_key') ) {
				
			unlink($base_dir . '/cache/secured/' . $secured_file);
			
			// Reload to avoid quirky page reloads later on
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit;
			
			}
			else {
			$newest_cached_webhook_key = 1;
			$webhook_key = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
			}
		
		}
	
	
	}
	
	
	// API key (for secure API communications)
	elseif ( preg_match("/int_api_key_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $newest_cached_api_key == 1 ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
			
			// If an API key reset from authenticated admin is verified
			if ( $_POST['reset_api_key'] == 1 && $ct_gen->admin_hashed_nonce('reset_api_key') != false && $_POST['admin_hashed_nonce'] == $ct_gen->admin_hashed_nonce('reset_api_key') ) {
				
			unlink($base_dir . '/cache/secured/' . $secured_file);
			
			// Reload to avoid quirky page reloads later on
			header("Location: " . $_SERVER['REQUEST_URI']);
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
		$active_admin_login_path = $base_dir . '/cache/secured/' . $secured_file; // To easily delete, if we are resetting the login
		$stored_admin_login = explode("||", trim( file_get_contents($active_admin_login_path) ) );
		}
	
	
	}
	

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no password pepper
if ( !$password_pepper ) {
	
$secure_128bit_hash = $ct_gen->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = $ct_gen->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
		
	$ct_gen->log(
				'security_error',
				'Cryptographically secure pseudo-random bytes could not be generated for pepper var (in secured cache storage), pepper var creation aborted to preserve security'
				);
	
	}
	else {
	$ct_cache->save_file($base_dir . '/cache/secured/pepper_var_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$password_pepper = $secure_256bit_hash;
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no webhook key
if ( !$webhook_key ) {
	
$secure_128bit_hash = $ct_gen->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = $ct_gen->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
		
	$ct_gen->log(
				'security_error',
				'Cryptographically secure pseudo-random bytes could not be generated for webhook key (in secured cache storage), webhook key creation aborted to preserve security'
				);
	
	}
	else {
	$ct_cache->save_file($base_dir . '/cache/secured/webhook_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$webhook_key = $secure_256bit_hash;
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no API key
if ( !$api_key ) {
	
$secure_128bit_hash = $ct_gen->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = $ct_gen->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
		
	$ct_gen->log(
				'security_error',
				'Cryptographically secure pseudo-random bytes could not be generated for API key (in secured cache storage), API key creation aborted to preserve security'
				);
	
	}
	else {
	$ct_cache->save_file($base_dir . '/cache/secured/int_api_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$api_key = $secure_256bit_hash;
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no admin login or an activated reset, valid user / pass are submitted, AND CAPTCHA MATCHES, store the new admin login
if ( $password_reset_approved || !is_array($stored_admin_login) ) {
    
    
	if (
	$ct_gen->valid_username( trim($_POST['set_username']) ) == 'valid' 
    && $ct_gen->pass_strength($_POST['set_password'], 12, 40) == 'valid' 
    && $_POST['set_password'] == $_POST['set_password2'] 
    && trim($_POST['captcha_code']) != ''
    && strtolower( trim($_POST['captcha_code']) ) == strtolower($_SESSION['captcha_code'])
    ) {
	
	
	$secure_128bit_hash = $ct_gen->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
	$secure_password_hash = $ct_gen->pepper_hashed_pass($_POST['set_password']); // Peppered password hash
	
	
		// (random hash) Halt the process if an issue is detected safely creating a random hash
		if ( $secure_128bit_hash == false ) {
			
		$ct_gen->log(
					'security_error',
					'Cryptographically secure pseudo-random bytes could not be generated for admin login (in secured cache storage), admin login creation aborted to preserve security'
					);
		
		}
		// (peppered password) Halt the process if an issue is detected safely creating a random hash
		elseif ( $secure_password_hash == false ) {
		$ct_gen->log('security_error', 'A peppered password hash could not be generated for admin login, admin login creation aborted to preserve security');
		}
		else {
		$ct_cache->save_file($base_dir . '/cache/secured/admin_login_'.$secure_128bit_hash.'.dat', trim($_POST['set_username']) . '||' . $secure_password_hash);
		$stored_admin_login = array( trim($_POST['set_username']), $secure_password_hash);
		$admin_login_updated = 1;
		}

		
		
		// If the admin login update was a success, delete old data file / login / redirect
		if ( $ct_gen->id() != false && isset($_SESSION['nonce']) && $admin_login_updated ) {
		
			// Delete any previous active admin login data file
			if ( $active_admin_login_path ) {
			unlink($active_admin_login_path);
			}
			
			// Delete any stored reset key
			if ( $stored_reset_key_path ) {
			unlink($stored_reset_key_path);
			}
			
		
		$ct_gen->do_admin_login();
		
		}
		else {
		$ct_gen->log('security_error', 'Admin login could not be updated', 'remote_address: ' . $remote_ip);
		}
	

	}
	
	
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
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
		if ( $password_pepper ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
		$password_pepper = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
		}
	
	
	}
	
	
	// MASTER webhook secret key (for secure webhook communications)
	elseif ( preg_match("/webhook_master_key_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $webhook_master_key ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
			
			// If an webhook secret key reset from authenticated admin is verified
			if ( $_POST['reset_webhook_master_key'] == 1 && $ct_gen->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_webhook_master_key') ) {
				
			unlink($base_dir . '/cache/secured/' . $secured_file);
			
			// Reload to avoid quirky page reloads later on
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit;
			
			}
			else {
			$webhook_master_key = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
			}
		
		}
	
	
	}
	
	
	// PER-SERVICE webhook secret keys (for secure webhook communications)
	elseif ( preg_match("/_webhook_key_/i", $secured_file) ) {
		
     $webhook_plug = preg_replace("/_webhook_key_(.*)/i", "", $secured_file);
     
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $int_webhooks[$webhook_plug] ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
			
			// If an webhook secret key reset from authenticated admin is verified
			if ( $_POST['reset_' . $webhook_plug . '_webhook_key'] == 1 && $ct_gen->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_' . $webhook_plug . '_webhook_key') ) {
				
			unlink($base_dir . '/cache/secured/' . $secured_file);
			
			// Reload to avoid quirky page reloads later on
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit;
			
			}
			else {
			$int_webhooks[$webhook_plug] = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
			}
		
		}
	
	
	unset($webhook_plug); 
	
	}
	
	
	// Internal API key (for secure API communications with other apps)
	elseif ( preg_match("/int_api_key_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $int_api_key ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
			
			// If an internal API key reset from authenticated admin is verified
			if ( $_POST['reset_int_api_key'] == 1 && $ct_gen->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_int_api_key') ) {
				
			unlink($base_dir . '/cache/secured/' . $secured_file);
			
			// Reload to avoid quirky page reloads later on
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit;
			
			}
			else {
			$int_api_key = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
			}
		
		}
	
	
	}
	
	
	// Stored admin login user / hashed password (for admin login authentication)
	elseif ( preg_match("/admin_login_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( is_array($stored_admin_login) ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
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


// If no MASTER webhook key
if ( !$webhook_master_key ) {
	
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
	$ct_cache->save_file($base_dir . '/cache/secured/webhook_master_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$webhook_master_key = $secure_256bit_hash;
	}


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If no internal API key
if ( !$int_api_key ) {
	
$secure_128bit_hash = $ct_gen->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
$secure_256bit_hash = $ct_gen->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
	
	
	// Halt the process if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
		
	$ct_gen->log(
				'security_error',
				'Cryptographically secure pseudo-random bytes could not be generated for internal API key (in secured cache storage), key creation aborted to preserve security'
				);
	
	}
	else {
	$ct_cache->save_file($base_dir . '/cache/secured/int_api_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
	$int_api_key = $secure_256bit_hash;
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


// Sanitize any user inputs VERY EARLY (for security / compatibility)
foreach ( $_GET as $scan_get_key => $unused ) {
$_GET[$scan_get_key] = $ct_gen->sanitize_requests('get', $scan_get_key, $_GET[$scan_get_key]);
}
foreach ( $_POST as $scan_post_key => $unused ) {
$_POST[$scan_post_key] = $ct_gen->sanitize_requests('post', $scan_post_key, $_POST[$scan_post_key]);
}


// If user is logging out (run immediately after setting PRIMARY vars, for quick runtime)
if ( $_GET['logout'] == 1 && $ct_gen->pass_sec_check($_GET['admin_hashed_nonce'], 'logout') ) {
	
// Try to avoid edge-case bug where sessions don't delete, using our hardened function logic
$ct_gen->hardy_sess_clear(); 

// Delete admin login cookie
unset($_COOKIE['admin_auth_' . $ct_gen->id()]);
$ct_gen->store_cookie('admin_auth_' . $ct_gen->id(), '', time()-3600); // Delete

header("Location: index.php");
exit;

}


// A bit of DOS attack mitigation for bogus / bot login attempts
// Speed up runtime SIGNIFICANTLY by checking EARLY for a bad / non-existent captcha code, and rendering the related form again...
// A BIT STATEMENT-INTENSIVE ON PURPOSE, AS IT KEEPS RUNTIME SPEED MUCH HIGHER
if ( $_POST['admin_submit_register'] || $_POST['admin_submit_login'] || $_POST['admin_submit_reset'] ) {


	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) != strtolower($_SESSION['captcha_code']) ) {
	
	    
	    // WE RUN SECURITY CHECKS WITHIN THE REGISTRATION PAGE, SO NOT MUCH CHECKS ARE IN THIS INIT SECTION
		if ( $_POST['admin_submit_register'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/register.php");
		exit;
		}
		elseif ( $_POST['admin_submit_login'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/login.php");
		exit;
		}
		elseif ( $_POST['admin_submit_reset'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/reset.php");
		exit;
		}
	
	
	}
	

}


// CSRF attack protection for downloads EXCEPT backup downloads (which are secured by requiring the nonce 
// in the filename already, since backup links are created during cron runtimes)
if ( $runtime_mode == 'download' && !isset($_GET['backup']) && $_GET['token'] != $ct_gen->nonce_digest('download') ) {
$ct_gen->log('security_error', 'aborted, security token mis-match/stale from ' . $_SERVER['REMOTE_ADDR'] . ', for request: ' . $_SERVER['REQUEST_URI'] . ' (try reloading the app)');
$ct_cache->error_log();
echo "Aborted, security token mis-match/stale. Try reloading the app.";
exit;
}

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Sanitize any user inputs VERY EARLY (for security / compatibility)
foreach ( $_GET as $scan_get_key => $unused ) {
$_GET[$scan_get_key] = $ct_gen->sanitize_requests('get', $scan_get_key, $_GET[$scan_get_key]);
}
foreach ( $_POST as $scan_post_key => $unused ) {
$_POST[$scan_post_key] = $ct_gen->sanitize_requests('post', $scan_post_key, $_POST[$scan_post_key]);
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


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


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// CSRF attack protection for downloads EXCEPT backup downloads (which are secured by requiring the nonce)
if ( $runtime_mode == 'download' && !isset($_GET['backup']) && $_GET['token'] != $ct_gen->nonce_digest('download') ) {
$ct_gen->log('security_error', 'aborted, security token mis-match/stale from ' . $_SERVER['REMOTE_ADDR'] . ', for request: ' . $_SERVER['REQUEST_URI'] . ' (try reloading the app)');
$ct_cache->error_log();
echo "Aborted, security token mis-match/stale, try reloading the app.";
exit;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Toggle ADMIN SECURITY LEVEL DEFAULTS (#MUST# BE SET BEFORE load-config-by-security-level.php)
// (EXCEPT IF 'opt_admin_sec' from authenticated admin is verified [that MUST be in config-init.php])

// If not updating, and cached var already exists
if ( file_exists($base_dir . '/cache/vars/admin_area_sec_level.dat') ) {
$admin_area_sec_level = trim( file_get_contents($base_dir . '/cache/vars/admin_area_sec_level.dat') );
}
// Else, default to high admin security
else {
$admin_area_sec_level = 'high';
$ct_cache->save_file($base_dir . '/cache/vars/admin_area_sec_level.dat', $admin_area_sec_level);
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Toggle 2FA DEFAULTS (#MUST# BE SET IMMEADIATELY AFTER ADMIN SECURITY LEVEL)
// (EXCEPT IF 'opt_admin_2fa' from authenticated admin is verified [that MUST be in config-init.php])

// If not updating, and cached var already exists
if ( file_exists($base_dir . '/cache/vars/admin_area_2fa.dat') ) {
$admin_area_2fa = trim( file_get_contents($base_dir . '/cache/vars/admin_area_2fa.dat') );
}
// Else, default to 2FA disabled
else {
$admin_area_2fa = 'off';
$ct_cache->save_file($base_dir . '/cache/vars/admin_area_2fa.dat', $admin_area_2fa);
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// IF NOT FAST RUNTIMES
if ( !$is_fast_runtime ) {
     
     
     // Secured cache files
     $secured_cache_files = $ct_gen->sort_files($base_dir . '/cache/secured', 'dat', 'desc');
     
     // WE LOAD ct_conf WAY EARLIER, SO IT'S NOT INCLUDED HERE
     
     foreach( $secured_cache_files as $secured_file ) {
     	
     	
     	// MIGRATE PEPPER VAR TO NEW SECRET VAR (FOR V6.00.26 BACKWARDS COMPATIBILITY)
     	if ( preg_match("/pepper_var_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( $migrate_to_auth_secret ) {
     		unlink($base_dir . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$migrate_to_auth_secret = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
     		unlink($base_dir . '/cache/secured/' . $secured_file); // DELETE BECAUSE WE ARE MIGRATING TO A NEW VAR NAME
     		}
     	
     	
     	}
     	
     	
     	// Secret var (for google authenticator etc)
     	if ( preg_match("/secret_var_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file (OR ARE MIGRATING), delete any stale ones
     		if ( $auth_secret || $migrate_to_auth_secret ) {
     		unlink($base_dir . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$auth_secret = trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) );
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
     		if ( isset($int_webhooks[$webhook_plug]) ) {
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
     
     
     // If no secret var
     if ( !$auth_secret || $migrate_to_auth_secret ) {
     
     $secure_128bit_hash = $ct_gen->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
     
     
          if ( $migrate_to_auth_secret ) {
          $secure_256bit_hash = $migrate_to_auth_secret;
          }
          else {
          $secure_256bit_hash = $ct_gen->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
          }
     	
     	
     	// Halt the process if an issue is detected safely creating a random hash
     	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
     		
     	$ct_gen->log(
     				'security_error',
     				'Cryptographically secure pseudo-random bytes could not be generated for secret var (in secured cache storage), secret var creation aborted to preserve security'
     				);
     	
     	}
     	else {
     	$ct_cache->save_file($base_dir . '/cache/secured/secret_var_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
     	$auth_secret = $secure_256bit_hash;
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
     
     
}
// END IF NOT FAST RUNTIMES

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
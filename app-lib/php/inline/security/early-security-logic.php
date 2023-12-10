<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////////////////////////////////////////////


if ( !$is_fast_runtime && $ct['runtime_mode'] == 'cron' || !$is_fast_runtime && $ct['runtime_mode'] == 'ui' )  {


     // Recreate /cache/.htaccess to restrict web snooping of cache contents, if the cache directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/.htaccess') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/.htaccess', file_get_contents($ct['base_dir'] . '/templates/back-end/deny-all-htaccess.template') );
     }
     
     // Recreate /cache/index.php to restrict web snooping of cache contents, if the cache directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/index.php') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/index.php', file_get_contents($ct['base_dir'] . '/templates/back-end/403-directory-index.template')); 
     }
     
     // Recreate /cache/htaccess_security_check.dat to test htaccess activation, if the cache directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/htaccess_security_check.dat') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/htaccess_security_check.dat', file_get_contents($ct['base_dir'] . '/templates/back-end/access_test.template')); 
     }
     
     
     ///////////////////////////////////////////
     
     
     // Recreate /cache/secured/.htaccess to restrict web snooping of cache contents, if the cache directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/.htaccess') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/.htaccess', file_get_contents($ct['base_dir'] . '/templates/back-end/deny-all-htaccess.template')); 
     }
     
     // Recreate /cache/secured/index.php to restrict web snooping of cache contents, if the cache directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/index.php') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/index.php', file_get_contents($ct['base_dir'] . '/templates/back-end/403-directory-index.template')); 
     }
     
     
     ///////////////////////////////////////////
     
     
     // Recreate /cache/secured/activation/.htaccess to restrict web snooping of cache contents, if the activation directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/activation/.htaccess') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/activation/.htaccess', file_get_contents($ct['base_dir'] . '/templates/back-end/deny-all-htaccess.template') ); 
     }
     
     // Recreate /cache/secured/activation/index.php to restrict web snooping of cache contents, if the activation directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/activation/index.php') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/activation/index.php', file_get_contents($ct['base_dir'] . '/templates/back-end/403-directory-index.template')); 
     }
     
     
     ///////////////////////////////////////////
     
     
     // Recreate /cache/secured/external_data/.htaccess to restrict web snooping of cache contents, if the apis directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/external_data/.htaccess') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/external_data/.htaccess', file_get_contents($ct['base_dir'] . '/templates/back-end/deny-all-htaccess.template') ); 
     }
     
     // Recreate /cache/secured/external_data/index.php to restrict web snooping of cache contents, if the apis directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/external_data/index.php') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/external_data/index.php', file_get_contents($ct['base_dir'] . '/templates/back-end/403-directory-index.template')); 
     }
     
     
     ///////////////////////////////////////////
     
     
     // Recreate /cache/secured/backups/.htaccess to restrict web snooping of cache contents, if the backups directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/backups/.htaccess') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/backups/.htaccess', file_get_contents($ct['base_dir'] . '/templates/back-end/deny-all-htaccess.template') ); 
     }
     
     // Recreate /cache/secured/backups/index.php to restrict web snooping of cache contents, if the backups directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/backups/index.php') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/backups/index.php', file_get_contents($ct['base_dir'] . '/templates/back-end/403-directory-index.template')); 
     }
     
     
     ///////////////////////////////////////////
     
     
     // Recreate /cache/secured/messages/.htaccess to restrict web snooping of cache contents, if the messages directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/messages/.htaccess') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/messages/.htaccess', file_get_contents($ct['base_dir'] . '/templates/back-end/deny-all-htaccess.template') );
     }
     
     // Recreate /cache/secured/messages/index.php to restrict web snooping of cache contents, if the messages directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/cache/secured/messages/index.php') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/messages/index.php', file_get_contents($ct['base_dir'] . '/templates/back-end/403-directory-index.template')); 
     }
     
     
     ///////////////////////////////////////////
     
     
     // Recreate /plugins/.htaccess to restrict web snooping of plugins contents, if the plugins directory was deleted / recreated
     // DIFFERENT FILENAME TEMPLATE (deny-all-htaccess-plugins.template) FOR SOME ACCESS EXCEPTIONS!!!
     if ( !file_exists($ct['base_dir'] . '/plugins/.htaccess') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/plugins/.htaccess', file_get_contents($ct['base_dir'] . '/templates/back-end/deny-all-htaccess-plugins.template') ); 
     }
     
     // Recreate /plugins/index.php to restrict web snooping of plugins contents, if the plugins directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/plugins/index.php') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/plugins/index.php', file_get_contents($ct['base_dir'] . '/templates/back-end/403-directory-index.template')); 
     }
     
     // Recreate /plugins/htaccess_security_check.dat to test htaccess activation, if the plugins directory was deleted / recreated
     if ( !file_exists($ct['base_dir'] . '/plugins/htaccess_security_check.dat') ) {
     $ct['cache']->save_file($ct['base_dir'] . '/plugins/htaccess_security_check.dat', file_get_contents($ct['base_dir'] . '/templates/back-end/access_test.template')); 
     }


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Sanitize any user inputs VERY EARLY (for security / compatibility)
foreach ( $_GET as $scan_get_key => $unused ) {
$_GET[$scan_get_key] = $ct['gen']->sanitize_requests('get', $scan_get_key, $_GET[$scan_get_key]);
}
foreach ( $_POST as $scan_post_key => $unused ) {
$_POST[$scan_post_key] = $ct['gen']->sanitize_requests('post', $scan_post_key, $_POST[$scan_post_key]);
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// If user is logging out (run immediately after setting PRIMARY vars, for quick runtime)
if ( $_GET['logout'] == 1 && $ct['gen']->pass_sec_check($_GET['admin_hashed_nonce'], 'logout') ) {
	
// Try to avoid edge-case bug where sessions don't delete, using our hardened function logic
$ct['gen']->hardy_sess_clear(); 

// Delete admin login cookie
unset($_COOKIE['admin_auth_' . $ct['gen']->id()]);
$ct['gen']->store_cookie('admin_auth_' . $ct['gen']->id(), '', time()-3600); // Delete

header("Location: index.php");
exit;

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// CSRF attack protection for downloads EXCEPT backup downloads (which are secured by requiring the nonce)
if ( $ct['runtime_mode'] == 'download' && !isset($_GET['backup']) && $_GET['token'] != $ct['gen']->nonce_digest('download') ) {
$ct['gen']->log('security_error', 'aborted, security token mis-match/stale from ' . $_SERVER['REMOTE_ADDR'] . ', for request: ' . $_SERVER['REQUEST_URI'] . ' (try reloading the app)');
$ct['cache']->app_log();
echo "Aborted, security token mis-match/stale, try reloading the app.";
exit;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Toggle ADMIN SECURITY LEVEL DEFAULTS (#MUST# BE SET BEFORE load-config-by-security-level.php)
// (EXCEPT IF 'opt_admin_sec' from authenticated admin is verified [that MUST be in config-init.php])

// If not updating, and cached var already exists
if ( file_exists($ct['base_dir'] . '/cache/vars/admin_area_sec_level.dat') ) {
$ct['admin_area_sec_level'] = trim( file_get_contents($ct['base_dir'] . '/cache/vars/admin_area_sec_level.dat') );

     // Backwards compatibility (upgrades from < v6.00.27)
     if ( $ct['admin_area_sec_level'] == 'enhanced' ) {
     $ct['admin_area_sec_level'] = 'medium';
     }
     
}
// Else, default to high admin security
else {
$ct['admin_area_sec_level'] = 'high';
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/admin_area_sec_level.dat', $ct['admin_area_sec_level']);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Toggle 2FA DEFAULTS (#MUST# BE SET IMMEADIATELY AFTER ADMIN SECURITY LEVEL)
// (EXCEPT IF 'opt_admin_2fa' from authenticated admin is verified [that MUST be in config-init.php])

// If not updating, and cached var already exists
if ( file_exists($ct['base_dir'] . '/cache/vars/admin_area_2fa.dat') ) {
$ct['admin_area_2fa'] = trim( file_get_contents($ct['base_dir'] . '/cache/vars/admin_area_2fa.dat') );
}
// Else, default to 2FA disabled
else {
$ct['admin_area_2fa'] = 'off';
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/admin_area_2fa.dat', $ct['admin_area_2fa']);
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////


// IF NOT FAST RUNTIMES
if ( !$is_fast_runtime ) {
     
     
     // Secured cache files
     $secured_cache_files = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured', 'dat', 'desc');
     
     // WE LOAD ct_conf WAY EARLIER, SO IT'S NOT INCLUDED HERE
     
     foreach( $secured_cache_files as $secured_file ) {
     	
     	
     	// MIGRATE PEPPER VAR TO NEW SECRET VAR (FOR V6.00.26 BACKWARDS COMPATIBILITY)
     	if ( preg_match("/pepper_var_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( $migrate_to_auth_secret ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$migrate_to_auth_secret = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file); // DELETE BECAUSE WE ARE MIGRATING TO A NEW VAR NAME
     		}
     	
     	
     	}
     	
     	
     	// Secret var (for google authenticator etc)
     	if ( preg_match("/secret_var_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file (OR ARE MIGRATING), delete any stale ones
     		if ( $auth_secret || $migrate_to_auth_secret ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$auth_secret = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		}
     	
     	
     	}
     	
     	
     	// MASTER webhook secret key (for secure webhook communications)
     	elseif ( preg_match("/webhook_master_key_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( $webhook_master_key ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
               $webhook_master_key = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		}
     	
     	
     	}
     	
     	
     	// PER-SERVICE webhook secret keys (for secure webhook communications)
     	elseif ( preg_match("/_webhook_key_/i", $secured_file) ) {
     		
          $webhook_plug = preg_replace("/_webhook_key_(.*)/i", "", $secured_file);
          
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( isset($ct['int_webhooks'][$webhook_plug]) ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     	     $ct['int_webhooks'][$webhook_plug] = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		}
     	
     	
     	unset($webhook_plug); 
     	
     	}
     	
     	
     	// Internal API key (for secure API communications with other apps)
     	elseif ( preg_match("/int_api_key_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( $int_api_key ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$int_api_key = trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) );
     		}
     	
     	
     	}
     	
     	
     	// Stored admin login user / hashed password (for admin login authentication)
     	elseif ( preg_match("/admin_login_/i", $secured_file) ) {
     		
     		
     		// If we already loaded the newest modified file, delete any stale ones
     		if ( is_array($stored_admin_login) ) {
     		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
     		}
     		else {
     		$active_admin_login_path = $ct['base_dir'] . '/cache/secured/' . $secured_file; // To easily delete, if we are resetting the login
     		$stored_admin_login = explode("||", trim( file_get_contents($active_admin_login_path) ) );
     		}
     	
     	
     	}
     	
     	
        	// Telegram user data
        	elseif ( preg_match("/telegram_user_data_/i", $secured_file) ) {
          
          // If we trigger a cached config reset later, we need to delete this telegram data with this file path
          $ct['telegram_user_data_path'] = $ct['base_dir'] . '/cache/secured/' . $secured_file;
        		
        		
        		// If we already loaded the newest modified telegram SECURED CACHE config file,
        		// or we are updating / resetting the cached config
        		if ( $newest_cached_telegram_user_data == 1 ) {
        		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
        		}
        		else {
        		
        		$newest_cached_telegram_user_data = 1;
        		
        		$cached_telegram_user_data = json_decode( trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) ) , TRUE);
        			
        			
        			// "null" in quotes as the actual value is returned sometimes
        			if ( $cached_telegram_user_data != false && $cached_telegram_user_data != null && $cached_telegram_user_data != "null" ) {
        			$ct['telegram_user_data'] = $cached_telegram_user_data;
        			}
        			else {
        			$ct['gen']->log('conf_error', 'Cached telegram_user_data non-existant or corrupted (refresh will happen automatically)');
        			unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
        			}
        		
        		
        		}
        	
        	
        	}
     	
     
     }
     
     
     //////////////////////////////////////////////////////////////////////////////////////////////////////////
     
     
     // If no secret var
     if ( !$auth_secret || $migrate_to_auth_secret ) {
     
     $secure_128bit_hash = $ct['gen']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
     
     
          if ( $migrate_to_auth_secret ) {
          $secure_256bit_hash = $migrate_to_auth_secret;
          }
          else {
          $secure_256bit_hash = $ct['gen']->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
          }
     	
     	
     	// Halt the process if an issue is detected safely creating a random hash
     	if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
     		
     	$ct['gen']->log(
     				'security_error',
     				'Cryptographically secure pseudo-random bytes could not be generated for secret var (in secured cache storage), secret var creation aborted to preserve security'
     				);
     	
     	}
     	else {
     	$ct['cache']->save_file($ct['base_dir'] . '/cache/secured/secret_var_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
     	$auth_secret = $secure_256bit_hash;
     	}
     
     
     }
     
     
}
// END IF NOT FAST RUNTIMES

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
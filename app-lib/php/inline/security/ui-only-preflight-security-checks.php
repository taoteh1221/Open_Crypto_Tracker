<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// UI-ONLY PREFLIGHT SECURITY CHECKS
//////////////////////////////////////////////////////////////////


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
     if ( isset($stored_reset_key) && trim($stored_reset_key) != '' && $_GET['new_reset_key'] == $stored_reset_key && $valid_to_email ) {
         
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
     

// Recreate /cache/.htaccess to restrict web snooping of cache contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/.htaccess') ) {
$ct_cache->save_file($base_dir . '/cache/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
sleep(1);
}

// Recreate /cache/index.php to restrict web snooping of backup contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/index.php') ) {
$ct_cache->save_file($base_dir . '/cache/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
sleep(1);
}

// Recreate /cache/htaccess_security_check.dat to test htaccess activation, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/htaccess_security_check.dat') ) {
$ct_cache->save_file($base_dir . '/cache/htaccess_security_check.dat', file_get_contents($base_dir . '/templates/back-end/access_test.template')); 
sleep(1);
}


///////////////////////////////////////////


// Recreate /cache/secured/.htaccess to restrict web snooping of backup contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/.htaccess') ) {
$ct_cache->save_file($base_dir . '/cache/secured/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template')); 
sleep(1);
}

// Recreate /cache/secured/index.php to restrict web snooping of backup contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/index.php') ) {
$ct_cache->save_file($base_dir . '/cache/secured/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
sleep(1);
}


///////////////////////////////////////////


// Recreate /cache/secured/activation/.htaccess to restrict web snooping of cache contents, if the activation directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/activation/.htaccess') ) {
$ct_cache->save_file($base_dir . '/cache/secured/activation/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
sleep(1);
}

// Recreate /cache/secured/activation/index.php to restrict web snooping of backup contents, if the activation directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/activation/index.php') ) {
$ct_cache->save_file($base_dir . '/cache/secured/activation/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
sleep(1);
}


///////////////////////////////////////////


// Recreate /cache/secured/external_data/.htaccess to restrict web snooping of cache contents, if the apis directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/external_data/.htaccess') ) {
$ct_cache->save_file($base_dir . '/cache/secured/external_data/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
sleep(1);
}

// Recreate /cache/secured/external_data/index.php to restrict web snooping of backup contents, if the apis directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/external_data/index.php') ) {
$ct_cache->save_file($base_dir . '/cache/secured/external_data/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
sleep(1);
}


///////////////////////////////////////////


// Recreate /cache/secured/backups/.htaccess to restrict web snooping of cache contents, if the backups directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/backups/.htaccess') ) {
$ct_cache->save_file($base_dir . '/cache/secured/backups/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
sleep(1);
}

// Recreate /cache/secured/backups/index.php to restrict web snooping of backup contents, if the backups directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/backups/index.php') ) {
$ct_cache->save_file($base_dir . '/cache/secured/backups/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
sleep(1);
}


///////////////////////////////////////////


// Recreate /cache/secured/messages/.htaccess to restrict web snooping of cache contents, if the messages directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/messages/.htaccess') ) {
$ct_cache->save_file($base_dir . '/cache/secured/messages/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
sleep(1);
}

// Recreate /cache/secured/messages/index.php to restrict web snooping of backup contents, if the messages directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/messages/index.php') ) {
$ct_cache->save_file($base_dir . '/cache/secured/messages/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
sleep(1);
}


///////////////////////////////////////////


// Recreate /plugins/.htaccess to restrict web snooping of plugins contents, if the plugins directory was deleted / recreated
// DIFFERENT FILENAME TEMPLATE (deny-all-htaccess-plugins.template) FOR SOME ACCESS EXCEPTIONS!!!
if ( !file_exists($base_dir . '/plugins/.htaccess') ) {
$ct_cache->save_file($base_dir . '/plugins/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess-plugins.template') ); 
sleep(1);
}

// Recreate /plugins/index.php to restrict web snooping of plugins contents, if the plugins directory was deleted / recreated
if ( !file_exists($base_dir . '/plugins/index.php') ) {
$ct_cache->save_file($base_dir . '/plugins/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
sleep(1);
}

// Recreate /plugins/htaccess_security_check.dat to test htaccess activation, if the plugins directory was deleted / recreated
if ( !file_exists($base_dir . '/plugins/htaccess_security_check.dat') ) {
$ct_cache->save_file($base_dir . '/plugins/htaccess_security_check.dat', file_get_contents($base_dir . '/templates/back-end/access_test.template')); 
sleep(1);
}


///////////////////////////////////////////


// KEEP CACHED BASE URL *SECURELY* UPDATED (CHECK FOR HEADER HOSTNAME SPOOFING ATTACKS [now that we have fully processed the app config])

// Have UI runtime mode RE-CACHE the app URL data every 24 hours, since CLI runtime cannot determine the app URL (for sending backup link emails during backups, etc)
// (ONLY DURING 'ui' RUNTIMES, TO ASSURE IT'S NEVER FROM A REWRITE [PRETTY LINK] URL LIKE /api OR /hook)
// WE FORCE A SECURITY CHECK HERE, SINCE WE ARE CACHING THE BASE URL DATA
if ( $ct_cache->update_cache('cache/vars/base_url.dat', (60 * 24) ) == true ) {
	    
$base_url_check = $ct_gen->base_url(true); 
	
	
     // If security check passes OK
     if ( !isset($base_url_check['security_error']) ) {
     $ct_cache->save_file('cache/vars/base_url.dat', $base_url_check);
     $base_url = $base_url_check; // Use any updated value immeaditely in the app
     }
     // If security check fails
     else {
             
           
         if ( isset($system_info['distro_name']) ) {
         $system_info_summary = "\n\nApp Server System Info: " . $system_info['distro_name'] . ( isset($system_info['distro_version']) ? ' ' . $system_info['distro_version'] : '' );
         }
             
                     
     // Build the different messages, configure comm methods, and send messages
         
     $log_error_message = 'Domain security check for "' . $base_url_check['checked_url'] . '" FAILED (' . $remote_ip . '). POSSIBLE hostname header spoofing attack blocked, exiting app...';
                     
     $email_msg = $log_error_message . ' ' . $system_info_summary . "\n\n" . ' Timestamp: ' . $ct_gen->time_date_format($ct_conf['gen']['loc_time_offset'], 'pretty_time') . '.';
                     
     // Were're just adding a human-readable timestamp to smart home (audio) alerts
     $notifyme_msg = $email_msg;
                     
     $text_msg = 'Security check for "' . $base_url_check['checked_url'] . '" FAILED (' . $remote_ip . '). POSSIBLE attack blocked, exiting app...';
                    
     // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                 
     // Minimize function calls
     $text_msg = $ct_gen->detect_unicode($text_msg); 
         			
     $attack_alert_send_params = array(
                                          'notifyme' => $notifyme_msg,
                                          'telegram' => $email_msg,
                                          'text' => array(
                                                   'message' => $text_msg['content'],
                                                   'charset' => $text_msg['charset']
                                                   ),
                                          'email' => array(
                                                 'subject' => 'POSSIBLE Attack Blocked From ' . $remote_ip,
                                                 'message' => $email_msg
                                                 )
                                      );
         			
     
     // Queue notifications
     @$ct_cache->queue_notify($attack_alert_send_params);
             
     $log_error_message = $log_error_message . ' <br /><br />';
         
     $ct_gen->log('security_error', $log_error_message);
     	
     echo $log_error_message;
     	
     $force_exit = 1;
     	
     }

	    
}


///////////////////////////////////////////


// Check htaccess security (checked once every 120 minutes maximum)
// (ONLY IF BASE URL VAR / CACHE FILE EXITS, AND THERE IS NO BASE URL CHECK SECURITY ERROR!)
if (
$ct_cache->update_cache($base_dir . '/cache/events/scan-htaccess-security.dat', 120) == true
&& $app_edition == 'server'
&& isset($base_url)
&& file_exists('cache/vars/base_url.dat')
&& !isset($base_url_check['security_error'])
) {
	
// HTTPS CHECK ONLY (for security if htaccess user/pass activated), don't cache API data
	
// cache check
$htaccess_cache_test_url = $base_url . 'cache/htaccess_security_check.dat';

$htaccess_cache_test = trim( @$ct_cache->ext_data('url', $htaccess_cache_test_url, 0) ); 
	
// plugins check
$htaccess_plugins_test_url = $base_url . 'plugins/htaccess_security_check.dat';

$htaccess_plugins_test = trim( @$ct_cache->ext_data('url', $htaccess_plugins_test_url, 0) ); 
	
	
	if (
	preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_cache_test)
	|| preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_plugins_test)
	) {
	     
	$log_error_message = "HTTP server 'htaccess' support has NOT been enabled on this web server for the 'cache' and 'plugins' sub-directories. 'htaccess' support is required to SAFELY run this application (htaccess security checks are throttled to a maximum of once every 2 hours). <br /><br />";
	
	$ct_gen->log('system_error', $log_error_message);
	
	echo $log_error_message;

	$force_exit = 1;

	}
	
	
// Update the htaccess security scan event tracking
$ct_cache->save_file($base_dir . '/cache/events/scan-htaccess-security.dat', $ct_gen->time_date_format(false, 'pretty_date_time') );

}


// Exit, if server / app security requirements not met
if ( $force_exit == 1 ) {
     
$system_error = 'Server OR app SECURITY issues detected (SEE LOGGED SETUP ISSUES), exiting application';

$ct_gen->log('system_error', $system_error);

echo $system_error;

// Log errors / send any notifications before exiting
$ct_cache->error_log();
$ct_cache->send_notifications();

exit;

}



//////////////////////////////////////////////////////////////////
// END UI-ONLY PREFLIGHT SECURITY CHECKS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
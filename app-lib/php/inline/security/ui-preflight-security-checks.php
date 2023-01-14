<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// FINAL PREFLIGHT SECURITY CHECKS
//////////////////////////////////////////////////////////////////


///////////////////////////////////////////

// Recreate /.htaccess for optional password access restriction / mod rewrite etc
if ( !file_exists($base_dir . '/.htaccess') ) {
$ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
sleep(1);
}

// Recreate /.user.ini for optional php-fpm php.ini control
if ( !file_exists($base_dir . '/.user.ini') ) {
$ct_cache->save_file($base_dir . '/.user.ini', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-user-ini.template') ); 
sleep(1);
}


///////////////////////////////////////////

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


$htaccess_protection_check = file_get_contents($base_dir . '/.htaccess');

// Htaccess password-protection

// FAILSAFE, FOR ANY EXISTING CRON JOB TO BAIL US OUT IF USER DELETES CACHE DIRECTORY WHERE AN ACTIVE LINKED PASSWORD FILE IS 
// (CAUSING INTERFACE TO CRASH WITH ERROR 500)
if ( preg_match("/Require valid-user/i", $htaccess_protection_check) && !is_readable($base_dir . '/cache/secured/.app_htpasswd') ) {
// Default htaccess root file, WITH NO PASSWORD PROTECTION
$restore_default_htaccess = $ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
}


// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ) {

	// If NO SETUP password protection exists
	if ( !preg_match("/Require valid-user/i", $htaccess_protection_check) || $refresh_cached_ct_conf == 1 ) {
		
	$password_protection_enabled = $ct_cache->htaccess_dir_protection();
	
		if ( !$password_protection_enabled ) {
			
		// Default htaccess root file, WITH NO PASSWORD PROTECTION
		$restore_default_htaccess = $ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
			
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
	$restore_default_htaccess = $ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
	
		// Avoid error 500 if htaccess update fails
		if ( $restore_default_htaccess == true ) {
		@unlink($base_dir . '/cache/secured/.app_htpasswd');
		}
	
	}
		
}


///////////////////////////////////////////


// CHECK FOR HEADER HOSTNAME SPOOFING ATTACKS (now that we have fully processed the app config)


// Have UI runtime mode RE-CACHE the app URL data every 24 hours, since CLI runtime cannot determine the app URL (for sending backup link emails during backups, etc)
// (ONLY DURING 'ui' RUNTIMES, TO ASSURE IT'S NEVER FROM A REWRITE [PRETTY LINK] URL LIKE /api OR /hook)
// WE FORCE A SECURITY CHECK HERE (OVERRIDES ONLY CHECKING EVERY X MINUTES), SINCE WE ARE CACHING THE BASE URL DATA
if ( $ct_cache->update_cache('cache/vars/base_url.dat', (60 * 24) ) == true ) {
	    
$base_url_check = $ct_gen->base_url('forced_sec_check'); 
	
    if ( !isset($base_url_check['security_error']) ) {
    $ct_cache->save_file('cache/vars/base_url.dat', $base_url_check);
    }
	    
}
// Otherwise, just do a regular check for security against header hostname spoofing attacks
else {
$base_url_check = $ct_gen->base_url();
}
    

if ( isset($base_url_check['security_error']) ) {
        
      
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


// Check htaccess security (checked once every 120 minutes maximum)
if ( $ct_cache->update_cache($base_dir . '/cache/events/scan-htaccess-security.dat', 120) == true && $app_edition == 'server' && isset($base_url_check) && trim($base_url_check) != '' && !isset($base_url_check['security_error']) ) {
	
		
// HTTPS CHECK ONLY (for security if htaccess user/pass activated), don't cache API data
	
// cache check
$htaccess_cache_test_url = $base_url_check . 'cache/htaccess_security_check.dat';

$htaccess_cache_test = trim( @$ct_cache->ext_data('url', $htaccess_cache_test_url, 0) ); 
	
// plugins check
$htaccess_plugins_test_url = $base_url_check . 'plugins/htaccess_security_check.dat';

$htaccess_plugins_test = trim( @$ct_cache->ext_data('url', $htaccess_plugins_test_url, 0) ); 
	
	
	if ( preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_cache_test)
	|| preg_match("/TEST_HTACCESS_SECURITY_123_TEST/i", $htaccess_plugins_test) ) {
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
// Log errors before exiting
$ct_cache->error_log();
$ct_cache->send_notifications();
exit;
}



//////////////////////////////////////////////////////////////////
// END FINAL PREFLIGHT SECURITY CHECKS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
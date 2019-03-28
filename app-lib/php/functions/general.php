<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

/////////////////////////////////////////////////////////

function update_cache_file($cache_file, $minutes) {

	if (  file_exists($cache_file) && filemtime($cache_file) > ( time() - ( 60 * $minutes ) )  ) {
	   return false; 
	} 
	else {
	   // Our cache is out-of-date
	   return true;
	}

}

//////////////////////////////////////////////////////////

function trim_array($data) {

        foreach ( $data as $key => $value ) {
        $data[$key] = trim(remove_formatting($value));
        }
        
return $data;

}

//////////////////////////////////////////////////////////

function random_proxy() {

global $proxy_list;

$proxy = array_rand($proxy_list);

return $proxy_list[$proxy];

}

//////////////////////////////////////////////////////////

function remove_formatting($data) {

$data = preg_replace("/ /i", "", $data); // Space
$data = preg_replace("/ /i", "", $data); // Tab
$data = preg_replace("/,/i", "", $data); // Comma
        
return $data;

}

///////////////////////////////////////////////////////////

function strip_price_formatting($price) {

$price = preg_replace("/ /", "", $price); // Space
$price = preg_replace("/,/", "", $price); // Comma
$price = preg_replace("/  /", "", $price); // Tab

return $price;

}

////////////////////////////////////////////////////////

function text_number($string) {

$string = explode("|",$string);

$number = $string[0];

return $number;

}

////////////////////////////////////////////////////////

function string_to_array($string) {

$string = explode("|",$string);

return $string;

}

/////////////////////////////////////////////////////////

function smtp_mail($subject, $message) {

// Using 3rd party SMTP class, initiated already as global var $smtp
global $smtp;

// Added to email in post-init.php one time...because class adds to an array each call, even if already added

$smtp->Subject($subject);
$smtp->Text($message);

return $smtp->Send();

}

/////////////////////////////////////////////////////////

function validate_email($email) {

// Trim whitespace off ends, since we do this before attempting to send anyways in our safe_mail function
$email = trim($email);

	$address = explode("@",$email);
	
	$domain = $address[1];
	
	// Validate "To" address
	if ( !$email || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/", $email) ) {
	return "Please enter a valid email address.";
	}
	elseif (function_exists("getmxrr") && !getmxrr($domain,$mxrecords)) {
	return "The email domain \"$domain\" appears incorrect.";
	}
	else {
	return "valid";
	}
			

}

/////////////////////////////////////////////////////////

function safe_mail($to, $subject, $message) {
	
global $smtp_login, $smtp_server, $from_email;

// Trim whitespace off ends
$from_email = trim($from_email);
$to = trim($to);

// Stop injection vulnerability
$from_email = str_replace("\r\n", "\n", $from_email); // windows -> unix
$from_email = str_replace("\r", "\n", $from_email);   // remaining -> unix


	// Use array for safety from header injection >= PHP 7.2 
	if ( PHP_VERSION_ID >= 70200 ) {
	
	$headers = array(
	    					'From' => $from_email
	    					//'From' => $from_email,
	    					//'Reply-To' => $from_email,
	    					//'X-Mailer' => 'PHP/' . phpversion()
							);
	
	}
	else {
	$headers = 'From: ' . $from_email;
	}
		
		
	// Validate TO email
	$email_check = validate_email($to);
	if ( $email_check != 'valid' ) {
	return $email_check;
	}
	
	
	// SMTP or PHP's built-in mail() function
	if ( $smtp_login != '' && $smtp_server != '' ) {
	return @smtp_mail($subject, $message); // Added to email in post-init.php one time...because class adds to an array each call, even if already added
	}
	else {
	return @mail($to, $subject, $message, $headers);
	}


}

/////////////////////////////////////////////////////////

function smtp_vars() {

// To preserve SMTPMailer class upgrade structure, by creating a global var to be run in classes/smtp-mailer/conf/config_smtp.php

global $smtp_login, $smtp_server;

$vars = array();

$log_file = preg_replace("/\/app-lib(.*)/i", "/cache/logs/errors.log", dirname(__FILE__) );

$smtp_login = explode("|",$smtp_login);
$smtp_server = explode(":",$smtp_server);

$smtp_user = $smtp_login[0];
$smtp_password = $smtp_login[1];

$smtp_host = $smtp_server[0];
$smtp_port = $smtp_server[1];

// Port vars over to class format (so it runs out-of-the-box as much as possible)
$vars['cfg_log_file']   = $log_file;
$vars['cfg_server']   = $smtp_host;
$vars['cfg_port']     =  $smtp_port;
$vars['cfg_secure']   = $smtp_secure;
$vars['cfg_username'] = $smtp_user;
$vars['cfg_password'] = $smtp_password;

return $vars;

}

////////////////////////////////////////////////////////

function text_email($string) {

$string = explode("|",$string);

$number = substr($string[0], -10); // USA 10 digit number without country code
$carrier = $string[1];


	if ( $carrier == 'alltel' ) {
	$domain = '@message.alltel.com';
	}
	elseif ( $carrier == 'att' ) {
	$domain = '@txt.att.net';
	}
	elseif ( $carrier == 'tmobile' ) {
	$domain = '@tmomail.net';
	}
	elseif ( $carrier == 'virgin' ) {
	$domain = '@vmobl.com';
	}
	elseif ( $carrier == 'sprint' ) {
	$domain = '@messaging.sprintpcs.com';
	}
	elseif ( $carrier == 'verizon' ) {
	$domain = '@vtext.com';
	}
	elseif ( $carrier == 'nextel' ) {
	$domain = '@messaging.nextel.com';
	}

return $number . $domain;

}

//////////////////////////////////////////////////////////

function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE) {
// WARNING: THIS ONLY WORKS WELL FOR HTTP-BASED RUNTIME, ----NOT CLI---!

	if ( isset($_SERVER['HTTP_HOST']) ) {
        	
   $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
   $hostname = $_SERVER['HTTP_HOST'];
   $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

   $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
   $core = $core[0];

   $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
   $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
   $base_url = sprintf( $tmplt, $http, $hostname, $end );
            
	}
	else $base_url = 'http://localhost/';

   if ($parse) {
   $base_url = parse_url($base_url);
   	     if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
   }


return $base_url;

}

//////////////////////////////////////////////////////////

function error_logs($error_logs=null) {

global $purge_error_logs, $mail_error_logs, $to_email;

// Combine all errors logged
$error_logs .= strip_tags($_SESSION['api_data_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($_SESSION['config_error']); // Remove any HTML formatting used in UI alerts

	foreach ( $_SESSION['repeat_error'] as $error ) {
	$error_logs .= strip_tags($error); // Remove any HTML formatting used in UI alerts
	}
	

$base_dir = preg_replace("/\/app-lib(.*)/i", "", dirname(__FILE__) );


	// If it's time to email error logs...
	if ( $mail_error_logs == 'daily' ) {
	$mail_freq = 1;
	}
	elseif ( $mail_error_logs == 'weekly' ) {
	$mail_freq = 7;
	}

	if ( $mail_freq > 0 && update_cache_file('cache/events/email-error-logs.dat', ( $mail_freq * 1440 ) ) == true ) {
		
	$emailed_logs = file_get_contents('cache/logs/errors.log');
		
	$message = " Here are the current error logs from the ".$base_dir."/cache/logs/errors.log file: \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No error logs currently.' );
	
	@safe_mail($to_email, 'DFD Cryptocoin Values ' . ucfirst($mail_error_logs) . ' Error Logs Report', $message);
	
	file_put_contents('cache/events/email-error-logs.dat', date('Y-m-d H:i:s'), LOCK_EX); // Track this emailing event, to determine next time to email logs again.
	
	}
	
	
	// Log errors...Purge old logs before storing new logs, if it's time to...otherwise just append.
	if ( $error_logs != NULL && update_cache_file('cache/events/purge-error-logs.dat', ( $purge_error_logs * 1440 ) ) == true ) {
	file_put_contents('cache/logs/errors.log', $error_logs, LOCK_EX);
	file_put_contents('cache/events/purge-error-logs.dat', date('Y-m-d H:i:s'), LOCK_EX);
	}
	elseif ( $error_logs != NULL ) {
	file_put_contents('cache/logs/errors.log', $error_logs, FILE_APPEND | LOCK_EX);
	}
	

}

//////////////////////////////////////////////////////////

function api_data($mode, $request, $ttl, $api_server=null, $post_encoding=3, $test_proxy=NULL) { // Default to JSON encoding post requests (most used)

global $user_agent, $api_timeout, $proxy_login, $proxy_list, $runtime_mode;

if ( preg_match("/etherscan/i", $request) ) {
$user_agent = NULL;
}

$cookie_jar = tempnam('/tmp','cookie');
	
// To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
$hash_check = ( $mode == 'array' ? md5(serialize($request)) : md5($request) );


	// Cache API data if set to cache...SESSION cache is only for runtime cache (deleted at end of runtime)...persistent cache is the file cache (which only reliably updates near end of a runtime session because of file locking)
	if ( update_cache_file('cache/api/'.$hash_check.'.dat', $ttl) == true && $ttl > 0 && !$_SESSION['api_cache'][$hash_check] 
	|| $ttl == 0 && !$_SESSION['api_cache'][$hash_check] ) {	
	
	$ch = curl_init( ( $mode == 'array' ? $api_server : '' ) );
	
		
		if ( sizeof($proxy_list) > 0 ) {
			
		$current_proxy = ( $mode == 'proxy-check' && $test_proxy != NULL ? $test_proxy : random_proxy() );
		
		// Check for valid proxy config
		$ip_port = explode(':', $current_proxy);

		$ip = $ip_port[0];
		$port = $ip_port[1];

			// If no ip/port detected in data string, cancel and continue runtime
			if ( !$ip || !$port ) {
			$_SESSION['api_data_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | data attempt from: server (local timeout setting ' . $api_timeout . ' seconds) | proxy: ' . $current_proxy . ' | canceling API data connection, proxy '.$current_proxy.' is not a valid proxy format (required format ip:port)' . "<br /> \n";
			return FALSE;
			}

		
		curl_setopt($ch, CURLOPT_PROXY, trim($current_proxy) );     
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);  
		
			if ( trim($proxy_login) != ''  ) {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, trim($proxy_login) );  
			}
		
		} 
		else {
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
		}
		
		if ( $mode == 'array' && $post_encoding == 1 ) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $request );
		}
		elseif ( $mode == 'array' && $post_encoding == 2 ) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS,  http_build_query($request) );
		}
		elseif ( $mode == 'array' && $post_encoding == 3 ) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($request) );
		}
		elseif ( $mode == 'url' || $mode == 'proxy-check' ) {
		curl_setopt($ch, CURLOPT_URL, $request);
		}
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $api_timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $api_timeout);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	
	$data = curl_exec($ch);
	curl_close($ch);


		if ( !$data ) {
		
		// SAFE UI ALERT VERSION (no post data with API keys etc)
		$_SESSION['api_data_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | data attempt from: server (local timeout setting ' . $api_timeout . ' seconds) | proxy: ' .( $current_proxy ? $current_proxy : 'none' ). ' | connection failed for: ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint request at ' . $request ) . "<br /> \n";
		
			if ( sizeof($proxy_list) > 0 && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops doing proxy checks

			$_SESSION['proxy_checkup'][] = array(
															'endpoint' => ( $mode == 'array' ? 'API server at ' . $api_server : 'Request URL at ' . $request ),
															'proxy' => $current_proxy
															);
															
			}
		
		}
		
		
		// Don't log cmc throttle notices, BUT nullify $data since we're getting no API data (just a throttle notice)
		if ( preg_match("/coinmarketcap/i", $request) && !preg_match("/last_updated/i", $data) ) {
		$data = NULL;
		}
	
		
		// Never cache proxy checking data
		if ( $mode != 'proxy-check' ) {
		$_SESSION['api_cache'][$hash_check] = ( $data ? $data : 'none' ); // Cache API data for this runtime session AFTER PERSISTENT FILE CACHE UPDATE, file cache doesn't reliably update until runtime session is ending because of file locking
		}
		
		// Cache data to the file cache
		if ( $ttl > 0 && $mode != 'proxy-check'  ) {
		file_put_contents('cache/api/'.$hash_check.'.dat', $_SESSION['api_cache'][$hash_check], LOCK_EX);
		}
		

	
	}
	elseif ( $ttl < 0 ) {
	// If flagged for cache file deletion with -1 as $ttl
	unlink('cache/api/'.$hash_check.'.dat'); // Delete cache if $ttl flagged to less than zero
	}
	else {
	
	
	// Use session cache if it exists. Remember file cache doesn't update until session is nearly over because of file locking, so only reliable for persisting a cache long term
	// If no API data was received, add error notices to UI / error logs
	$data = ( $_SESSION['api_cache'][$hash_check] ? $_SESSION['api_cache'][$hash_check] : file_get_contents('cache/api/'.$hash_check.'.dat') );
		
		if ( $data == 'none' ) {
		
			if ( !$_SESSION['error_duplicates'][$hash_check] ) {
			$_SESSION['error_duplicates'][$hash_check] = 1; 
			}
			else {
			$_SESSION['error_duplicates'][$hash_check] = $_SESSION['error_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
		$_SESSION['repeat_error'][$hash_check] = date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | data attempt(s) from: cache ('.$_SESSION['error_duplicates'][$hash_check].' runtime instances) | proxy: ' .( $current_proxy ? $current_proxy : 'none' ). ' | no data in cache, from connection failure for: ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint request at ' . $request ) . "<br /> \n";
			
		}
	
	
	}
	
	
return $data;


}

//////////////////////////////////////////////////////////

function test_proxy($problem_proxy_array) {

global $proxy_alerts_freq, $proxy_alerts, $proxy_alerts_runtime, $proxy_checkup_ok, $to_email, $to_text, $notifyme_accesscode, $textbelt_apikey, $textlocal_account, $runtime_mode;


// Endpoint to test proxy connectivity: https://www.myip.com/api-docs/
$proxy_test_url = 'https://api.myip.com/';


$problem_endpoint = $problem_proxy_array['endpoint'];
$problem_proxy = $problem_proxy_array['proxy'];

$ip_port = explode(':', $problem_proxy);

$ip = $ip_port[0];
$port = $ip_port[1];

	// If no ip/port detected in data string, cancel and continue runtime
	if ( !$ip || !$port ) {
	$_SESSION['api_data_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | proxy check attempt on proxy: ' . $problem_proxy . ' | canceling proxy check, proxy '.$problem_proxy.' is not a valid proxy format (required format ip:port)' . "<br /> \n";
	return FALSE;
	}

// Create cache filename / session var
$cache_filename = $problem_proxy;
$cache_filename = preg_replace("/\./", "-", $cache_filename);
$cache_filename = preg_replace("/:/", "_", $cache_filename);

	if ( $proxy_alerts_runtime == 'all' ) {
	$run_alerts = 1;
	}
	elseif ( $proxy_alerts_runtime == 'cron' && $runtime_mode == 'cron' ) {
	$run_alerts = 1;
	}
	elseif ( $proxy_alerts_runtime == 'ui' && $runtime_mode == 'ui' ) {
	$run_alerts = 1;
	}
	else {
	$run_alerts = NULL;
	}

	if ( $run_alerts == 1 && update_cache_file('cache/alerts/proxy-check-'.$cache_filename.'.dat', ( $proxy_alerts_freq * 60 ) ) == true
	&& in_array($cache_filename, $_SESSION['proxies_checked']) == false ) {
	
		
	// SESSION VAR first, to avoid duplicate alerts at runtime (and longer term cache file locked for writing further down, after logs creation)
	$_SESSION['proxies_checked'][] = $cache_filename;
		
	$jsondata = @api_data('proxy-check', $proxy_test_url, 0, '', '', $problem_proxy);
	
	$data = json_decode($jsondata, TRUE);
	
		if ( sizeof($data) > 0 ) {

			
			// Look for the IP in the response
			if ( strstr($data['ip'], $ip) == false ) {
				
			$misconfigured = 1;
			
			$notifyme_alert = 'A checkup on proxy ' . $ip . ', port ' . $port . ' detected a misconfiguration. Remote address ' . $data['ip'] . ' does not match the proxy address. Runtime mode is ' . $runtime_mode . '.';
			
			$text_alert = 'Proxy ' . $problem_proxy . ' remote address mismatch (detected as: ' . $data['ip'] . '). Runtime mode: ' . $runtime_mode;
		
			}
			
			
		$cached_logs = ( $misconfigured == 1 ? 'Runtime mode: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = MISCONFIGURED (test endpoint ' . $proxy_test_url . ' detected the incoming ip as: ' . $data['ip'] . ')' . "; \n " . 'Remote address DOES NOT match proxy address;' : 'Runtime mode: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = OK (test endpoint ' . $proxy_test_url . ' detected the incoming ip as: ' . $data['ip'] . ');' );
		
		
		}
		else {
			
		$misconfigured = 1;
		
		$notifyme_alert = 'A checkup on proxy ' . $ip . ', port ' . $port . ' resulted in a failed data request. No endpoint connection could be established. Runtime mode is ' . $runtime_mode . '.';
			
		$text_alert = 'Proxy ' . $problem_proxy . ' failed, no endpoint connection. Runtime mode: ' . $runtime_mode;
		
		$cached_logs = 'Runtime mode: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = DATA REQUEST FAILED' . "; \n " . 'No connection established at test endpoint ' . $proxy_test_url . ';';

		}


		// Cache the logs
		file_put_contents('cache/alerts/proxy-check-'.$cache_filename.'.dat', $cached_logs, LOCK_EX);
			
      
      $email_alert = " The proxy " . $problem_proxy . " recently did not receive data when accessing this endpoint: \n " . $problem_endpoint . " \n \n A check on this proxy was performed at " . $proxy_test_url . ", and results logged: \n ============================================================== \n " . $cached_logs . " \n ============================================================== \n \n ";
                    
		
		// Send out alerts
		if ( $misconfigured == 1 || $proxy_checkup_ok == 'include' ) {
                    
                      
          // Alert parameter configs for comm methods
          $notifyme_params = array(
                                  'notification' => $notifyme_alert,
                                  'accessCode' => $notifyme_accesscode
                                  );
          
          $textbelt_params = array(
                                  'phone' => text_number($to_text),
                                  'message' => $text_alert,
                                  'key' => $textbelt_apikey
                                  );
          
          $textlocal_params = array(
                                   'username' => string_to_array($textlocal_account)[0],
                                   'hash' => string_to_array($textlocal_account)[1],
                                   'numbers' => text_number($to_text),
                                   'message' => $text_alert
                                   );
      
                    
      
           if ( trim($notifyme_accesscode) != '' && $proxy_alerts == 'notifyme'
           || trim($notifyme_accesscode) != '' && $proxy_alerts == 'all' ) {
           @api_data('array', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
           }
      
           if ( trim($textbelt_apikey) != '' && trim($textlocal_account) == '' && $proxy_alerts == 'text'
           || trim($textbelt_apikey) != '' && trim($textlocal_account) == '' && $proxy_alerts == 'all' ) { // Only run if textlocal API isn't being used to avoid double texts
           @api_data('array', $textbelt_params, 0, 'https://textbelt.com/text', 2);
           }
      
           if ( trim($textlocal_account) != '' && trim($textbelt_apikey) == '' && $proxy_alerts == 'text'
           || trim($textlocal_account) != '' && trim($textbelt_apikey) == '' && $proxy_alerts == 'all' ) { // Only run if textbelt API isn't being used to avoid double texts
           @api_data('array', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
           }
           
           // SEND EMAILS LAST, IN CASE OF SMTP METHOD FAILURE AND RUNTIME EXIT
               
           if (  validate_email($to_email) == 'valid' && $proxy_alerts == 'email'
           || validate_email($to_email) == 'valid' && $proxy_alerts == 'all' ) {
           @safe_mail($to_email, 'A Proxy Was Unresponsive', $email_alert);
           }
      
           if ( validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) != '' && trim($textlocal_account) != '' && $proxy_alerts == 'text'
           || validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) != '' && trim($textlocal_account) != '' && $proxy_alerts == 'all' ) { 
           // Only use text-to-email if other text services aren't configured
           @safe_mail( text_email($to_text) , 'Unresponsive Proxy', $text_alert);
           }
           
           
       }
          
          
		
	}

}

//////////////////////////////////////////////////////////

?>
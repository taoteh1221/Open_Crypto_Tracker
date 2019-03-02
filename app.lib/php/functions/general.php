<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

/////////////////////////////////////////////////////////

function update_cache_file($cache_file, $minutes) {

	if ( file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * $minutes )) ) {
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

function smtp_mail($to, $subject, $message) {

// Using 3rd party SMTP class, initiated already as global var $smtp
global $smtp;

$smtp->addTo($to);
$smtp->Subject($subject);
$smtp->Body('<pre>'.$message.'</pre>'); // 3rd party SMTP class does as html statically, so use <pre> tags for text

return $smtp->Send();

}

/////////////////////////////////////////////////////////

function validate_email($email) {


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
	return smtp_mail($to, $subject, $message);
	}
	else {
	return mail($to, $subject, $message, $headers);
	}


}

/////////////////////////////////////////////////////////

function smtp_vars() {

// To preserve SMTPMailer class upgrade structure, by creating a global var to be run in classes/smtp-mailer/conf/config_smtp.php

global $smtp_login, $smtp_server;

$vars = array();

$smtp_login = explode("|",$smtp_login);
$smtp_server = explode(":",$smtp_server);

$smtp_user = $smtp_login[0];
$smtp_password = $smtp_login[1];

$smtp_host = $smtp_server[0];
$smtp_port = $smtp_server[1];

// Port vars over to class format (so it runs out-of-the-box)
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

function data_request($mode, $request, $ttl, $api_server=null, $post_encoding=3, $test_proxy=NULL) { // Default to JSON encoding post requests (most used)

global $user_agent, $api_timeout, $proxy_list;

$cookie_jar = tempnam('/tmp','cookie');
	
// To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
$hash_check = ( $mode == 'array' ? md5(serialize($request)) : md5($request) );


	// Cache API data if set to cache...SESSION cache is only for runtime cache (deleted at end of runtime)...persistent cache is the file cache (which only reliably updates near end of a runtime session because of file locking)
	if ( update_cache_file('cache/api/'.$hash_check.'.dat', $ttl) == true && $ttl > 0 && !$_SESSION['api_cache'][$hash_check] 
	|| $ttl == 0 && !$_SESSION['api_cache'][$hash_check] ) {	
	
	$ch = curl_init( ( $mode == 'array' ? $api_server : '' ) );
	
		
		if ( sizeof($proxy_list) > 0 ) {
		$current_proxy = ( $mode == 'proxy-check' && $test_proxy != NULL ? $test_proxy : random_proxy() );
		$ip_port = explode(':', $current_proxy);
		
		curl_setopt($ch, CURLOPT_PROXY, trim($ip_port[0]) );    
		curl_setopt($ch, CURLOPT_PROXYPORT, trim($ip_port[1]) );    
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);  
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
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
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $api_timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $api_timeout);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	
	$data = curl_exec($ch);
	curl_close($ch);


		if ( !$data ) {
		$data = 'no';
		
		// SAFE VERSION
		$_SESSION['get_data_error'] .= ' No data returned from ' . ( $mode == 'array' ? 'API server "' . $api_server : 'request "' . $request ) . '" (with timeout configuration setting of ' . $api_timeout . ' seconds). <br /> ';
		
		// DEBUGGING VERSION ONLY, CONTAINS USER DATA (API KEYS ETC)
		//$_SESSION['get_data_error'] .= ' No data returned from ' . ( $mode == 'array' ? 'API server "' . $api_server : 'request "' . $request ) . '" (with timeout configuration setting of ' . $api_timeout . ' seconds). <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
		
			if ( sizeof($proxy_list) > 0 && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops
			test_proxies($current_proxy); // Test this proxy, to make sure it's online / configured properly
			}
		
		}
		
		if ( preg_match("/coinmarketcap/i", $request) && !preg_match("/last_updated/i", $data) ) {
		$_SESSION['get_data_error'] .= '##REAL-TIME REQUEST## data error response from '.( $mode == 'array' ? $api_server : $request ).': <br /> =================================== <br />' . $data . ' <br /> =================================== <br />';
		}
	
	
		if ( $data && $ttl > 0 && $mode ) {
		//echo 'File caching data '; // DEBUGGING ONLY
		file_put_contents('cache/api/'.$hash_check.'.dat', $data, LOCK_EX);
		}
		elseif ( !$data ) {
		unlink('cache/api/'.$hash_check.'.dat'); // Delete any existing cache if empty value
		//echo 'Deleted cache file, no data. '; // DEBUGGING ONLY
		}
		
		// Never cache proxy checking data
		if ( $mode != 'proxy-check' ) {
		$_SESSION['api_cache'][$hash_check] = $data; // Cache API data for this runtime session AFTER PERSISTENT FILE CACHE UPDATE, file cache doesn't reliably update until runtime session is ending because of file locking
		}

	// DEBUGGING ONLY
	//$_SESSION['get_data_error'] .= '##REQUEST## Requested ' . ( $mode == 'array' ? 'API server "' . $api_server : 'endpoint "' . $request ) . '". <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
	
	}
	elseif ( $ttl < 0 ) {
	unlink('cache/api/'.$hash_check.'.dat'); // Delete cache if $ttl flagged to less than zero
	//echo 'Deleted cache file, flagged for deletion. '; // DEBUGGING ONLY
	}
	else {
	
	// Use session cache if it exists. Remember file cache doesn't update until session is nearly over because of file locking, so only reliable for persisting a cache long term
	$data = ( $_SESSION['api_cache'][$hash_check] ? $_SESSION['api_cache'][$hash_check] : file_get_contents('cache/api/'.$hash_check.'.dat') );
	
		if ( !$data ) {
		unlink('cache/api/'.$hash_check.'.dat'); // Delete any existing cache if empty value
		//echo 'Deleted cache file, no data. ';
		}
		else {
		//echo 'Cached data '; // DEBUGGING ONLY
		}
	
		if ( !preg_match("/coinmarketcap/i", $_SESSION['get_data_error']) && preg_match("/coinmarketcap/i", $request) && !preg_match("/last_updated/i", $data) ) {
		$_SESSION['cmc_error'] = '##CACHED REQUEST## data error response from '.( $mode == 'array' ? $api_server : $request ).': <br /> =================================== <br />' . $data . ' <br /> =================================== <br />';
		}
	
	
	// DEBUGGING ONLY
	//$_SESSION['get_data_error'] .= ' ##CACHED## request response for ' . ( $mode == 'array' ? 'API server "' . $api_server : 'endpoint "' . $request ) . '". <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
	
	
	}
	
	
return $data;


}

//////////////////////////////////////////////////////////

function test_proxies($problem_proxy) {

global $proxy_alerts_freq, $proxy_alerts_type, $to_email, $to_text, $notifyme_accesscode, $textbelt_apikey, $textlocal_account;

$ip_port = explode(':', $problem_proxy);

$ip = $ip_port[0];
$port = $ip_port[1];

// Create cache filename / session var
$cache_filename = $problem_proxy;
$cache_filename = preg_replace("/\./", "-", $cache_filename);
$cache_filename = preg_replace("/:/", "_", $cache_filename);

$proxy_test_url = 'http://httpbin.org/ip';

	if ( update_cache_file('cache/alerts/proxy-check-'.$cache_filename.'.dat', ( $proxy_alerts_freq * 60 ) ) == true
	&& in_array($cache_filename, $_SESSION['proxies_checked']) == false ) {
	
	//$jsondata = @data_request('proxy-check', 'http://httpbin.org/ip', 0, '', '', $problem_proxy);
	$jsondata = @data_request('proxy-check', $proxy_test_url, 0, '', '', $problem_proxy);
	
	$data = json_decode($jsondata, TRUE);
	
		if ( trim($data['origin']) != '' ) {

			
			// Look for the IP in the response
			if ( strstr($data['origin'], $ip) == false ) {
				
			$misconfigured = 1;
			
			$notifyme_alert = 'A checkup on proxy '.$ip.', port '.$port.' detected a misconfiguration. Remote address '.$data['origin'].' Does not match the proxy address.';
			
			$text_alert = 'Proxy '.$problem_proxy.' remote address mismatch (detected ip: '.$data['origin'].').';
		
			}
			
			
		$cached_logs = ( $misconfigured == 1 ? 'Proxy '.$problem_proxy.' checkup status = MISCONFIGURED (test endpoint '.$proxy_test_url.' detected the incoming ip as: '.$data['origin'].'); Remote address DOES NOT match proxy address;' : 'Proxy '.$problem_proxy.' checkup status = OK (test endpoint '.$proxy_test_url.' detected the incoming ip as: '.$data['origin'].');' );
		
		
		}
		else {
			
		$misconfigured = 1;
			
		$cached_logs = 'Proxy '.$problem_proxy.' checkup status = DATA REQUEST FAILED; No connection established at test endpoint '.$proxy_test_url.';';
		
		$notifyme_alert = 'A checkup on proxy '.$ip.', port '.$port.' resulted in a failed data request. No endpoint connection could be established.';
			
		$text_alert = 'Proxy '.$problem_proxy.' failed data request, no endpoint connection established.';

		}

      
      $email_alert = " The proxy ".$problem_proxy." was unresponsive recently. A check on this proxy was performed, and results logged: \n \n ============================================================== \n " . $cached_logs . " \n ============================================================== \n \n ";
                    
		
		// SESSION VAR to avoid duplicate alerts close together (while first alert still has cache file locked for writing)
		$_SESSION['proxies_checked'][] = $cache_filename;
		
		// Cache the logs
		file_put_contents('cache/alerts/proxy-check-'.$cache_filename.'.dat', $cached_logs, LOCK_EX);
		
		
		// Send out alerts
		if ( $misconfigured == 1 ) {
                    
                          
          if (  validate_email($to_email) == 'valid' && $proxy_alerts_type == 'email'
          || validate_email($to_email) == 'valid' && $proxy_alerts_type == 'all' ) {
          safe_mail($to_email, 'A Proxy Was Unresponsive', $email_alert);
          }
      
        
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
      
                    
           if ( validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) != '' && trim($textlocal_account) != '' && $proxy_alerts_type == 'text'
           || validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) != '' && trim($textlocal_account) != '' && $proxy_alerts_type == 'all' ) { // Only use text-to-email if other text services aren't configured
           safe_mail( text_email($to_text) , 'Unresponsive Proxy', $text_alert);
           }
      
           if ( trim($notifyme_accesscode) != '' && $proxy_alerts_type == 'notifyme'
           || trim($notifyme_accesscode) != '' && $proxy_alerts_type == 'all' ) {
           data_request('array', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
           }
      
           if ( trim($textbelt_apikey) != '' && trim($textlocal_account) == '' && $proxy_alerts_type == 'text'
           || trim($textbelt_apikey) != '' && trim($textlocal_account) == '' && $proxy_alerts_type == 'all' ) { // Only run if textlocal API isn't being used to avoid double texts
           data_request('array', $textbelt_params, 0, 'https://textbelt.com/text', 2);
           }
      
           if ( trim($textlocal_account) != '' && trim($textbelt_apikey) == '' && $proxy_alerts_type == 'text'
           || trim($textlocal_account) != '' && trim($textbelt_apikey) == '' && $proxy_alerts_type == 'all' ) { // Only run if textbelt API isn't being used to avoid double texts
           data_request('array', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
           }
           
           
       }
          
          
		
	}

}

//////////////////////////////////////////////////////////


?>
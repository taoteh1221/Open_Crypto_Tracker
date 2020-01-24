<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function string_to_array($string) {

$string = explode("||",$string);

return $string;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function remove_formatting($data) {

$data = preg_replace("/ /i", "", $data); // Space
$data = preg_replace("/ /i", "", $data); // Tab
$data = preg_replace("/,/i", "", $data); // Comma
        
return $data;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function strip_price_formatting($price) {

$price = preg_replace("/ /", "", $price); // Space
$price = preg_replace("/,/", "", $price); // Comma
$price = preg_replace("/  /", "", $price); // Tab

return $price;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function trim_array($data) {

   foreach ( $data as $key => $value ) {
   $data[$key] = trim(remove_formatting($value));
   }
        
return $data;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function random_proxy() {

global $app_config;

$proxy = array_rand($app_config['proxy_list']);

return $app_config['proxy_list'][$proxy];

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function text_number($string) {

$string = explode("||",$string);

$number = trim($string[0]);

return $number;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function directory_size($dir) {

$size = 0;

	foreach ( glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each ) {
   $size += ( is_file($each) ? filesize($each) : directory_size($each) );
   }
    
return $size;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function remove_number_format($text) {

$text = str_replace("    ", '', $text);
$text = str_replace(" ", '', $text);
$text = str_replace(",", "", $text);
$text = trim($text);

return float_to_string($text);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function chart_range($range) {

global $charts_update_freq;

$updates_daily = $charts_update_freq * 24;

return ($updates_daily * $range);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function update_cache_file($cache_file, $minutes) {

	if (  file_exists($cache_file) && filemtime($cache_file) > ( time() - ( 60 * $minutes ) )  ) {
	   return false; 
	} 
	else {
	   // Our cache is out-of-date
	   return true;
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function convert_to_utf8($string) {

// May be needed for charsets from different spreadsheet apps across the world, so leave in case needed

$result = iconv("ISO-8859-1", "UTF-8", $string); // ISO-8859-1 to UTF8

//$result = iconv(mb_detect_encoding($string, mb_detect_order(), true), "UTF-8", $string); // Auto-detect

return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function split_text_message($text, $char_length) {

$chunks = explode("||||", wordwrap($message, $char_length, "||||", false) );
$total = count($chunks);

	foreach($chunks as $page => $chunk) {
	$message = sprintf("(%d/%d) %s", $page+1, $total, $chunk);
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function smtp_mail($to, $subject, $message, $content_type='text', $charset=null) {

// Using 3rd party SMTP class, initiated already as global var $smtp
global $app_config, $smtp;

	if ( $charset == null ) {
	$charset = $app_config['charset_standard'];
	}

$smtp->From($app_config['from_email']); 
$smtp->singleTo($to); 
$smtp->Subject($subject);
$smtp->Charset($charset);

	if ( $content_type == 'text' ) {
	$smtp->Text($message);
	}
	elseif ( $content_type == 'html' ) {
	$smtp->Body($message);
	}

return $smtp->Send();

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function cleanup_config($string, $mode) {

	// Upper or lower case
	if ( $mode == 'lower' ) {
	$string = strtolower($string);
	}
	elseif ( $mode == 'upper' ) {
	$string = strtoupper($string);
	}

// Remove all whitespace
$string = preg_replace('/\s/', '', $string);

return $string;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function hardy_session_clearing() {

// Deleting all session data can fail on occasion, and wreak havoc.
// This helps according to one programmer on php.net
session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(),'',0,'/');
session_regenerate_id(true);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function convert_bytes($bytes, $round) {

$type = array("", "Kilo", "Mega", "Giga", "Tera", "Peta", "Exa", "Zetta", "Yotta");

  $index = 0;
  while( $bytes >= 1000 ) { // new standard (not 1024 anymore)
  $bytes/=1000; // new standard (not 1024 anymore)
  $index++;
  }
  
return("".round($bytes, $round)." ".$type[$index]."bytes");

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function create_csv_file($file, $save_as, $array) {

	if ( $file == 'temp' ) {
	$file = tempnam(sys_get_temp_dir(), 'temp');
	}

$fp = fopen($file, 'w');

	foreach($array as $fields) {
	fputcsv($fp, $fields);
	}

file_download($file, $save_as); // Download file (by default deletes after download, then exits)

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// See if $val is a whole number without decimals
function whole_int($val) {
	
$val = strval($val);
$val = str_replace('-', '', $val);

    if (ctype_digit($val)) {
    	
        if ( $val === (string)0 ) {
        return true;
        }
        elseif( ltrim($val, '0') === $val ) {
        return true;
        }
            
    }

return false;
    
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function app_logging($log_type, $log_message, $telemetry=false, $hashcheck=false, $overwrite=false) {

global $runtime_mode;

	if ( $hashcheck != false ) {
	$_SESSION[$log_type][$hashcheck] = date('Y-m-d H:i:s') . ' UTC | runtime: ' . $runtime_mode . ' | ' . $log_type . ': ' . $log_message . ( $telemetry != false ? ' | telemetry: [ '  . $telemetry . ' ]' : '' ) . " <br /> \n";
	}
	elseif ( $overwrite != false ) {
	$_SESSION[$log_type] = date('Y-m-d H:i:s') . ' UTC | runtime: ' . $runtime_mode . ' | ' . $log_type . ': ' . $log_message . ( $telemetry != false ? ' | telemetry: [ '  . $telemetry . ' ]' : '' ) . " <br /> \n";
	}
	else {
	$_SESSION[$log_type] .= date('Y-m-d H:i:s') . ' UTC | runtime: ' . $runtime_mode . ' | ' . $log_type . ': ' . $log_message . ( $telemetry != false ? ' | telemetry: [ '  . $telemetry . ' ]' : '' ) . " <br /> \n";
	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function store_cookie_contents($name, $value, $time) {

$result = setcookie($name, $value, $time);
	
	
	// Android / Safari maximum cookie size is 4093 bytes, Chrome / Firefox max is 4096
	if ( strlen($value) > 4093 ) {  
	app_logging('other_error', 'Cookie size is greater than 4093 bytes (' . strlen($value) . ' bytes). If saving portfolio as cookie data fails on your browser, try using CSV file import / export instead for large portfolios.');
	}
	
	if ( $result == FALSE ) {
	app_logging('other_error', 'Cookie creation failed for cookie "' . $name . '"');
	}
	
	
return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function text_email($string) {

global $app_config;

$string = explode("||",$string);

$phone_number = substr($string[0], -10); 
$network_name = trim( strtolower($string[1]) ); // Force lowercase lookups for reliability / consistency

	// Set text domain
	if ( trim($phone_number) != '' && isset($app_config['mobile_network_text_gateways'][$network_name]) ) {
	return trim($phone_number) . '@' . trim($app_config['mobile_network_text_gateways'][$network_name]); // Return formatted texting email address
	}
	else {
	return false;
	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// Return the TLD only (no subdomain)
function get_tld($url) {

global $app_config;

$urlData = parse_url($url);
$hostData = explode('.', $urlData['host']);
$hostData = array_reverse($hostData);


	if ( array_search($hostData[1] . '.' . $hostData[0], $app_config['top_level_domain_map']) !== FALSE ) {
   $host = $hostData[2] . '.' . $hostData[1] . '.' . $hostData[0];
	} 
	elseif ( array_search($hostData[0], $app_config['top_level_domain_map']) !== FALSE ) {
   $host = $hostData[1] . '.' . $hostData[0];
 	}


return strtolower( trim($host) );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// hex2bin requires PHP >= 5.4.0.
// If, for whatever reason, you are using a legacy version of PHP, you can implement hex2bin with this function:
 
if (!function_exists('hex2bin')) {
    function hex2bin($hexstr) {
        $n = strlen($hexstr);
        $sbin = "";
        $i = 0;
        while ($i < $n) {
            $a = substr($hexstr, $i, 2);
            $c = pack("H*", $a);
            if ($i == 0) {
                $sbin = $c;
            } else {
               $sbin .= $c;
            }
            $i += 2;
         }
         return $sbin;
    }
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function start_page($page, $href_link=false) {

	// We want to force a page reload for href links, so technically we change the URL but location remains the same
	if ( $href_link != FALSE ) {
	$index = './';
	}
	else {
	$index = 'index.php';
	}
	
	if ( $page != '' ) {
	$url = $index . ( $page != '' ? '?start_page=' . $page . '#' . $page : '' );
	}
	else {
	$url = $index;
	}
	
	
return $url;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


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
	return "The email domain \"$domain\" appears incorrect, no mail server records exist for this domain name.";
	}
	else {
	return "valid";
	}
			

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function character_unicode_to_utf8($char, $format) {
	
    if ( $format == 'decimal' ) {
    $pre = '';
    }
    elseif ( $format == 'hexadecimal' ) {
    $pre = 'x';
    }

$char = trim($char);
$char = 'PREFIX' . $char;
$char = preg_replace('/PREFIXx/', 'PREFIX', $char);
$char = preg_replace('/PREFIXu/', 'PREFIX', $char);
$char = preg_replace('/PREFIX/', '', $char);
$char = '&#' . $pre . $char . ';';

$result = html_entity_decode($char, ENT_COMPAT, 'UTF-8');

return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function sort_files($files_dir, $extension, $sort) {
	
$scan_array = scandir($files_dir);
$files = array();
  
  
  foreach($scan_array as $filename) {
    
    if ( pathinfo($filename, PATHINFO_EXTENSION) == $extension ) {
      $mod_time = filemtime($files_dir.'/'.$filename);
      $files[$mod_time . '-' . $filename] = $filename;
    }
    
  }


  if ( $sort == 'asc' ) {
  ksort($files);
  }
  elseif ( $sort == 'desc' ) {
  krsort($files);
  }


return $files;
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function random_hash($num_bytes) {

global $base_dir;

	// PHP 4 
	if ( PHP_VERSION_ID < 50000 ) {
	app_logging('security_error', 'Upgrade to PHP v5 or later to support cryptographically secure pseudo-random bytes in this application, or your application may not function properly');
	}
	// PHP 5 (V6 RELEASE WAS SKIPPED)
	elseif ( PHP_VERSION_ID < 60000 ) {
	require_once($base_dir . '/app-lib/php/other/third-party/random-compat/lib/random.php');
	$hash = random_bytes($num_bytes);
	}
	// >= PHP 7
	elseif ( PHP_VERSION_ID >= 70000 ) {
	$hash = random_bytes($num_bytes);
	}

	if ( strlen($hash) == $num_bytes ) {
	return bin2hex($hash);
	}
	else {
	return false;
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function dir_structure($path) {

global $possible_http_users, $http_runtime_user;

	if ( !is_dir($path) ) {
	
		// Run cache compatibility on certain PHP setups
		if ( !$http_runtime_user || in_array($http_runtime_user, $possible_http_users) ) {
		$oldmask = umask(0);
		$result = mkdir($path, octdec('0777'), true); // Recursively create whatever path depth desired if non-existent
		umask($oldmask);
		return $result;
		}
		else {
		return  mkdir($path, octdec('0777'), true); // Recursively create whatever path depth desired if non-existent
		}
	
	}
	else {
	return TRUE;
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function pepper_hashed_password($password) {

global $password_pepper;

	if ( !$password_pepper ) {
	app_logging('other_error', '$password_pepper not set properly');
	return false;
	}
	else {
		
	$password_pepper_hashed = hash_hmac("sha256", $password, $password_pepper);
	
		if ( $password_pepper_hashed == false ) {
		app_logging('other_error', 'hash_hmac() returned false in the pepper_hashed_password() function');
		return false;
		}
		else {
		return password_hash($password_pepper_hashed);
		}
	
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function check_pepper_hashed_password($input_password, $stored_hashed_password) {

global $password_pepper;

	if ( !$password_pepper ) {
	app_logging('other_error', '$password_pepper not set properly');
	return false;
	}
	else {
		
	$input_password_pepper_hashed = hash_hmac("sha256", $input_password, $password_pepper);
	
		if ( $password_pepper_hashed == false ) {
		app_logging('other_error', 'hash_hmac() returned false in the check_pepper_hashed_password() function');
		return false;
		}
		else {
		return password_verify($input_password_pepper_hashed, $stored_hashed_password);
		}
		
	}

}
 

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function valid_username($username) {

global $app_config;

    if ( mb_strlen($username, $app_config['charset_standard']) < 4 ) {
    $error .= "requires 4 minimum characters; ";
    }
    
    if ( mb_strlen($username, $app_config['charset_standard']) > 30 ) {
    $error .= "requires 30 maximum characters; ";
    }
    
	 if ( !preg_match("/^[a-z]([a-z0-9]+)$/", $username) ) {
    $error .= "lowercase letters / numbers only (lowercase letters first, then optionally numbers, no spaces); ";
	 }
	 
	 if ( preg_match('/\s/',$username) ) {
    $error .= "no spaces allowed; ";
	 }


    if( $error ){
    return 'valid_username_error: ' . $error;
    }
    else {
    return 'valid';
    }


}
 
 
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

 
function character_utf8_to_unicode($char, $format) {
	
    if (ord($char{0}) >=0 && ord($char{0}) <= 127)
        $result = ord($char{0});
        
    if (ord($char{0}) >= 192 && ord($char{0}) <= 223)
        $result = (ord($char{0})-192)*64 + (ord($char{1})-128);
        
    if (ord($char{0}) >= 224 && ord($char{0}) <= 239)
        $result = (ord($char{0})-224)*4096 + (ord($char{1})-128)*64 + (ord($char{2})-128);
        
    if (ord($char{0}) >= 240 && ord($char{0}) <= 247)
        $result = (ord($char{0})-240)*262144 + (ord($char{1})-128)*4096 + (ord($char{2})-128)*64 + (ord($char{3})-128);
        
    if (ord($char{0}) >= 248 && ord($char{0}) <= 251)
        $result = (ord($char{0})-248)*16777216 + (ord($char{1})-128)*262144 + (ord($char{2})-128)*4096 + (ord($char{3})-128)*64 + (ord($char{4})-128);
        
    if (ord($char{0}) >= 252 && ord($char{0}) <= 253)
        $result = (ord($char{0})-252)*1073741824 + (ord($char{1})-128)*16777216 + (ord($char{2})-128)*262144 + (ord($char{3})-128)*4096 + (ord($char{4})-128)*64 + (ord($char{5})-128);
        
    if (ord($char{0}) >= 254 && ord($char{0}) <= 255)    //  error
        $result = FALSE;
        
    if ( $format == 'decimal' ) {
    $result = $result;
    }
    elseif ( $format == 'hexadecimal' ) {
    $result = 'x'.dechex($result);
    }
    
return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function password_strength($password) {

global $app_config;

    if ( mb_strlen($password, $app_config['charset_standard']) < 13 ) {
    $error .= "requires 13 minimum characters; ";
    }
    
    if ( mb_strlen($password, $app_config['charset_standard']) > 30 ) {
    $error .= "requires 30 maximum characters; ";
    }
    
    if ( !preg_match("#[0-9]+#", $password) ) {
    $error .= "include one number; ";
    }
    
    if ( !preg_match("#[a-z]+#", $password) ) {
    $error .= "include one LOWERCASE letter; ";
    }
    
    if ( !preg_match("#[A-Z]+#", $password) ) {
    $error .= "include one UPPERCASE letter; ";
    }
    
    if ( !preg_match("#\W+#", $password) ) {
    $error .= "include one symbol; ";
    }
	 
	 if ( preg_match('/\s/',$password) ) {
    $error .= "no spaces allowed; ";
	 }
    
    
    if( $error ){
    return 'password_strength_error: ' . $error;
    }
    else {
    return 'valid';
    }


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function htaccess_directory_protection() {

global $base_dir, $app_config;

$htaccess_username = trim($app_config['htaccess_username']);

$htaccess_password = trim($app_config['htaccess_password']);


    if ( $htaccess_username == '' || $htaccess_password == '' ) {
    return false;
    }
    elseif ( valid_username($htaccess_username) != 'valid' ) {
    app_logging('security_error', 'app_config\'s "htaccess_username" value does not meet minimum valid username requirements' , valid_username($htaccess_username) );
    return false;
    }
    elseif ( password_strength($htaccess_password) != 'valid' ) {
    app_logging('security_error', 'app_config\'s "htaccess_password" value does not meet minimum password strength requirements' , password_strength($htaccess_password) );
    return false;
    }
    else {
    
    $htaccess_password = crypt( $htaccess_password, base64_encode($htaccess_password) );
    
    store_file_contents($base_dir . '/cache/secured/.app_htpasswd', $htaccess_username . ':' . $htaccess_password);
    
    $htaccess_contents = file_get_contents($base_dir . '/templates/back-end/root-app-directory-htaccess.template') . 
preg_replace("/\[BASE_DIR\]/i", $base_dir, file_get_contents($base_dir . '/templates/back-end/enable-password-htaccess.template') );
    
    store_file_contents($base_dir . '/.htaccess', $htaccess_contents);
    
    return true;
    
    }

 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function delete_old_files($directory_data, $days, $ext) {
	
	
	// Support for string OR array in the calls, for directory data
	if ( !is_array($directory_data) ) {
	$directory_data = array($directory_data);
	}
	
	
	// Process each directory
	foreach ( $directory_data as $dir ) {
	
		
	$files = glob($dir."*.".$ext);
	
	
      foreach ($files as $file) {
       
        if ( is_file($file) ) {
          
          if ( time() - filemtime($file) >= 60 * 60 * 24 * $days ) {
          	
          $result = unlink($file);
          
          	if ( $result == false ) {
          	app_logging('other_error', 'File deletion failed for file "' . $file . '" (check permissions for "' . basename($file) . '")');
          	}
          
          }
          
        }
        else {
        app_logging('other_error', 'File deletion failed, file not found: "' . $file . '"');
        }
        
      }
  
	
	}


 }


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function name_rendering($string) {


// Uppercase every word, and remove underscore between them
$string = ucwords(preg_replace("/_/i", " ", $string));


// Pretty up the individual words as needed
$words = explode(" ",$string);

	foreach($words as $key => $value) {
	
		if ( $value == 'Us' ) {
		$words[$key] = strtoupper($value); // All uppercase US
		}
	
	$pretty_string .= $words[$key] . ' ';
	}

$pretty_string = preg_replace("/btc/i", 'BTC', $pretty_string);
$pretty_string = preg_replace("/coin/i", 'Coin', $pretty_string);
$pretty_string = preg_replace("/bitcoin/i", 'Bitcoin', $pretty_string);
$pretty_string = preg_replace("/exchange/i", 'Exchange', $pretty_string);
$pretty_string = preg_replace("/market/i", 'Market', $pretty_string);
$pretty_string = preg_replace("/forex/i", 'Forex', $pretty_string);
$pretty_string = preg_replace("/finex/i", 'Finex', $pretty_string);
$pretty_string = preg_replace("/stamp/i", 'Stamp', $pretty_string);
$pretty_string = preg_replace("/flyer/i", 'Flyer', $pretty_string);
$pretty_string = preg_replace("/panda/i", 'Panda', $pretty_string);

return trim($pretty_string);


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function smtp_vars() {

// To preserve SMTPMailer class upgrade structure, by creating a global var to be run in classes/smtp-mailer/conf/config_smtp.php

global $app_version, $base_dir, $app_config;

$vars = array();

$log_file = $base_dir . "/cache/logs/smtp_errors.log";
$log_file_debugging = $base_dir . "/cache/logs/smtp_debugging.log";

// Don't overwrite globals
$temp_smtp_login = explode("||", $app_config['smtp_login'] );
$temp_smtp_server = explode(":", $app_config['smtp_server'] );

// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
$smtp_user = trim($temp_smtp_login[0]);
$smtp_password = $temp_smtp_login[1];

$smtp_host = trim($temp_smtp_server[0]);
$smtp_port = trim($temp_smtp_server[1]);

// Port vars over to class format (so it runs out-of-the-box as much as possible)
$vars['cfg_log_file']   = $log_file;
$vars['cfg_log_file_debugging']   = $log_file_debugging;
$vars['cfg_server']   = $smtp_host;
$vars['cfg_port']     =  $smtp_port;
$vars['cfg_secure']   = $app_config['smtp_secure'];
$vars['cfg_username'] = $smtp_user;
$vars['cfg_password'] = $smtp_password;
$vars['cfg_debug_mode'] = $app_config['debug_mode']; // DFD Cryptocoin Values debug mode setting
$vars['cfg_strict_ssl'] = $app_config['smtp_strict_ssl']; // DFD Cryptocoin Values strict SSL setting
$vars['cfg_app_version'] = $app_version; // DFD Cryptocoin Values version

return $vars;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE) {
	
// WARNING: THIS ONLY WORKS WELL FOR HTTP-BASED RUNTIME, ----NOT CLI---!
// CACHE IT TO FILE DURING UI RUNTIME FOR CLI TO USE LATER ;-)

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


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function time_date_format($offset=false, $mode=false) {


	if ( $offset == FALSE ) {
	$time = time();
	}
	else {
	$time = time() + ( $offset * (60 * 60) );  // Offset is in hours
	}


	if ( $mode == FALSE ) {
	$date = date("Y-m-d H:i:s", $time); // Format: 2001-03-10 17:16:18 (the MySQL DATETIME format)
	}
	elseif ( $mode == 'pretty_date_time' ) {
	$date = date("F jS, @ g:ia", $time); // Format: March 10th, @ 5:16pm
	}
	elseif ( $mode == 'pretty_date' ) {
	$date = date("F jS", $time); // Format: March 10th
	}
	elseif ( $mode == 'pretty_time' ) {
	$date = date("g:ia", $time); // Format: 5:16pm
	}

$date = preg_replace("/@/", "at", $date); // 'at' is a stubborn word to escape into the date() function, so we cheat a little

return $date;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function file_download($file, $save_as, $delete=true) {
	
global $app_config;

$type = pathinfo($save_as, PATHINFO_EXTENSION);

	if ( $type == 'csv' ) {
	$content_type = 'Content-type: text/csv; charset=' . $app_config['charset_standard'];
	}
	else {
	$content_type = 'Content-type: application/octet-stream';
	}


	if ( file_exists($file) ) {
		
		header('Content-description: file transfer');
		header($content_type);
		header('Content-disposition: attachment; filename="'.basename($save_as).'"');
		header('Expires: 0');
		header('Cache-control: must-revalidate');
		header('Pragma: public');
		header('Content-length: ' . filesize($file));
		
		$result = readfile($file);
		
			if ( $result != false && $delete == true ) {
			unlink($file); // Delete file
			}
		
		exit;
		
	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function csv_file_array($file) {
	
	$row = 0;
	if ( ( $handle = fopen($file, "r") ) != FALSE ) {
		
		while ( ( $data = fgetcsv($handle, 0, ",") ) != FALSE ) {
			
		$num = count($data);
		
			if ( $data[0] != 'Asset Symbol' ) {  // Skip importing header
			$asset = strtoupper($data[0]);
		
				for ($c=0; $c < $num; $c++) {
					
					// Make sure asset symbol variable is alphanumeric, otherwise skip
					// (this helps a lot detecting failed or successful importing)
					if ( ctype_alnum($asset) ) { 
					$csv_rows[$asset][] = $data[$c];
					}
				
				}
			
			}
			
		$row++;
			
		}
		fclose($handle);
		
	}

unlink($file); // Delete temp file

return $csv_rows;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function store_file_contents($file, $content, $mode=false) {

global $app_config, $possible_http_users, $http_runtime_user;
	
$path_parts = pathinfo($file);
	
	
	// We ALWAYS set .htaccess files to a more secure 664 permission AFTER EDITING, 
	// so we TEMPORARILY set .htaccess to 666 for NEW EDITING...
	if ( strstr($file, 'htaccess') != false ) {
		
	$chmod_setting = octdec('0666');
	
	
		// Run chmod compatibility on certain PHP setups
		if ( !$http_runtime_user || in_array($http_runtime_user, $possible_http_users) ) {
			
		$oldmask = umask(0);
		
		$did_chmod = chmod($file, $chmod_setting);
		
			if ( !$did_chmod && $app_config['debug_mode'] == 'all' || !$did_chmod && $app_config['debug_mode'] == 'telemetry' ) {
			app_logging('other_debugging', 'Chmod failed for file "' . $file . '" (check permissions for the path "' . $path_parts['dirname'] . '", and the file "' . $path_parts['basename'] . '")', 'chmod_setting: ' . $chmod_setting . '; http_runtime_user: ' . $http_runtime_user . ';');
			}
		
		umask($oldmask);
		
		}
	
	}
	



	// Write to the file
	if ( $mode == 'append' ) {
	$result = file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
	}
	else {
	$result = file_put_contents($file, $content, LOCK_EX);
	}
	
	// Log any error
	if ( $result == false ) {
	app_logging('other_error', 'File write failed for file "' . $file . '" (check permissions for the path "' . $path_parts['dirname'] . '", and the file "' . $path_parts['basename'] . '")');
	}
	
	
	
	
	// For security, NEVER make an .htaccess file writable by any user not in the group
	if ( strstr($file, 'htaccess') != false ) {
	$chmod_setting = octdec('0664');
	}
	// All other files
	else {
	$chmod_setting = octdec('0666');
	}
	
	// Run chmod compatibility on certain PHP setups
	if ( !$http_runtime_user || in_array($http_runtime_user, $possible_http_users) ) {
		
	$oldmask = umask(0);
	
	$did_chmod = chmod($file, $chmod_setting);
		
		if ( !$did_chmod && $app_config['debug_mode'] == 'all' || !$did_chmod && $app_config['debug_mode'] == 'telemetry' ) {
		app_logging('other_debugging', 'Chmod failed for file "' . $file . '" (check permissions for the path "' . $path_parts['dirname'] . '", and the file "' . $path_parts['basename'] . '")', 'chmod_setting: ' . $chmod_setting . '; http_runtime_user: ' . $http_runtime_user . ';');
		}
		
	umask($oldmask);
	
	}
	
	
	
return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function chart_time_interval($file, $linecount, $length) {


	$last_lines = get_last_lines($file, $linecount, $length);
	foreach ( $last_lines as $line ) {
	$data = explode("||", $line);
	
		if ( $data[0] != '' ) {
		$timestamps[] = $data[0];
		}
	
	}
	
	
	$count = 0;
	foreach ( $timestamps as $key => $value ) {
		
		if ( $timestamps[($key - 1)] != '' ) {
		$count = $count + 1;
		$total_minutes = $total_minutes + round( ( $timestamps[$key] - $timestamps[($key - 1)] ) / 60 );
		}
		
	}


$average_interval = round( $total_minutes / ( sizeof($timestamps) - 1 ) );


// Only return average intervals if we have a minimum of 24 intervals to average out
// (set to 1 until then, to keep chart buttons from acting weird until we have enough data)
return ( $count >= 24 ? round( 60 / $average_interval ) : 1 );  


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function in_megabytes($string) {

$string_value = preg_replace("/ (.*)/i", "", $string);

	// Always in megabytes
	if ( preg_match("/kilo/i", $string) || preg_match("/kb/i", $string) ) {
	$in_megs = $string_value * 0.001;
	$type = 'Kilobytes';
	}
	elseif ( preg_match("/mega/i", $string) || preg_match("/mb/i", $string) ) {
	$in_megs = $string_value * 1;
	$type = 'Megabytes';
	}
	elseif ( preg_match("/giga/i", $string) || preg_match("/gb/i", $string) ) {
	$in_megs = $string_value * 1000;
	$type = 'Gigabytes';
	}
	elseif ( preg_match("/tera/i", $string) || preg_match("/tb/i", $string) ) {
	$in_megs = $string_value * 1000000;
	$type = 'Terabytes';
	}
	elseif ( preg_match("/peta/i", $string) || preg_match("/pb/i", $string) ) {
	$in_megs = $string_value * 1000000000;
	$type = 'Petabytes';
	}
	elseif ( preg_match("/exa/i", $string) || preg_match("/eb/i", $string) ) {
	$in_megs = $string_value * 1000000000000;
	$type = 'Exabytes';
	}
	elseif ( preg_match("/zetta/i", $string) || preg_match("/zb/i", $string) ) {
	$in_megs = $string_value * 1000000000000000;
	$type = 'Zettabytes';
	}
	elseif ( preg_match("/yotta/i", $string) || preg_match("/yb/i", $string) ) {
	$in_megs = $string_value * 1000000000000000000;
	$type = 'Yottabytes';
	}

$result['num_val'] = $string_value;
$result['type'] = $type;
$result['in_megs'] = round($in_megs, 3);

return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// Always display very large / small numbers in non-scientific format
// Also removes any leading and trailing zeros for efficient storage / UX / etc
function float_to_string($val) {

global $app_config;

// Trim any whitespace off the ends
$val = trim($val);


	// Covert scientific notation to a normal value / string
    
	// MUST ALLOW MAXIMUM OF 9 DECIMALS, TO COUNT WATCH-ONLY ASSETS
	// (ANYTHING OVER 9 DECIMALS SHOULD BE AVOIDED FOR UX)
   $detect_decimals = (string)$val;
   if ( preg_match('~\.(\d+)E([+-])?(\d+)~', $detect_decimals, $matches) ) {
   $decimals = $matches[2] === '-' ? strlen($matches[1]) + $matches[3] : 0;
   }
   else {
   $decimals = mb_strpos( strrev($detect_decimals) , '.', 0, 'utf-8');
   }
    
	if ( $decimals > 9 ) {
	$decimals = 9;
	}
   
   $val = number_format($val, $decimals, '.', '');


	// Remove TRAILING zeros ie. 140.00000 becomes 140.
	// (ONLY IF DECIMAL PLACE EXISTS)
	if ( preg_match("/\./", $val) ) {
	$val = rtrim($val, '0');
	}


	// Remove any extra LEADING zeros 
	// IF less than 1.00
	if ( $val < 1 ) {
	$val = preg_replace("/(.*)00\./", "0.", $val);
	}
	// IF greater than or equal to 1.00
	elseif ( $val >= 1 ) {
	$val = ltrim($val, '0');
	}
	

// Remove decimal point if an integer ie. 140. becomes 140
$val = rtrim($val, '.');
	
	
	// Always at least return zero
	if ( $val >= 0.000000001 ) {
	return $val;
	}
	else {
	return 0;
	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function content_data_encoding($content) {
	
global $app_config;


// Charsets we want to try and detect here
// (SAVE HERE FOR POSSIBLE FUTURE USE)
$charset_array = array(
								'ASCII',
								'UCS-2',
								'UCS-2BE',
								'UTF-16BE',
								'UTF-16LE',
								'UTF-16',
								'UTF-8',
								);


// Changs only if non-UTF-8 / non-ASCII characters are detected further down in this function
$set_charset = $app_config['charset_standard'];

$words = explode(" ", $content);
	
	
	foreach ( $words as $scan_key => $scan_value ) {
		
	$scan_value = trim($scan_value);
	
	$scan_charset = ( mb_detect_encoding($scan_value, 'auto') != false ? mb_detect_encoding($scan_value, 'auto') : NULL );
	
		if ( isset($scan_charset) && !preg_match("/" . $app_config['charset_standard'] . "/i", $scan_charset) && !preg_match("/ASCII/i", $scan_charset) ) {
		$set_charset = $app_config['charset_unicode'];
		}
	
	}

	
	foreach ( $words as $word_key => $word_value ) {
		
	$word_value = trim($word_value);
	
	$word_charset = ( mb_detect_encoding($word_value, 'auto') != false ? mb_detect_encoding($word_value, 'auto') : NULL );
	
   $result['debug_original_charset'] .= ( isset($word_charset) ? $word_charset . ' ' : 'unknown_charset ' );
	
		if ( isset($word_charset) && strtolower($word_charset) == strtolower($set_charset) ) {
   	$temp = $word_value . ' ';
		}
		elseif ( isset($word_charset) && strtolower($set_charset) != strtolower($word_charset) ) {
   	$temp = mb_convert_encoding($word_value . ' ', $set_charset, $word_charset);
		}
		elseif ( !isset($word_charset) ) {
   	$temp = mb_convert_encoding($word_value . ' ', $set_charset);
		}
		
		$temp_converted .= $temp;
		
	}
	

$temp_converted = trim($temp_converted);
	
$result['debug_original_charset'] = trim($result['debug_original_charset']);

$result['debug_temp_converted'] = $temp_converted;

$result['charset'] = $set_charset;
	
$result['length'] = mb_strlen($temp_converted, $set_charset); // Get character length AFTER trim() / BEFORE bin2hex() processing
		
	
	if ( $set_charset == $app_config['charset_unicode'] ) {
		
		for($i =0; $i < strlen($temp_converted); $i++) {
		//$content_converted .= ' ' . strtoupper(bin2hex($temp_converted[$i])); // Spacing between characters
		$content_converted .= strtoupper(bin2hex($temp_converted[$i])); // No spacing
		}
	
	$result['content_output'] = trim($content_converted);
	}
	else {
	$result['content_output'] = $temp_converted;
	}
	

return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function chart_data($file, $chart_format) {

global $app_config, $default_btc_primary_currency_pairing;


	if ( array_key_exists($chart_format, $app_config['bitcoin_market_currencies']) ) {
	$fiat_formatting = 1;
	}
	elseif ( $chart_format == 'system' ) {
	$system_statistics_chart = 1;
	}


$data = array();
$fn = fopen($file,"r");
  
  while( !feof($fn) )  {
  	
	$result = explode("||", fgets($fn) );
	
		if ( trim($result[0]) != '' ) {
			
		$data['time'] .= trim($result[0]) . '000,';  // Zingchart wants 3 more zeros with unix time (milliseconds)
		
		
         if ( $system_statistics_chart == 1 ) {
         
         $data['temperature_celsius'] .= trim($result[2]) . ',';
         $data['free_memory_percentage'] .= trim($result[4]) . ',';
         $data['cron_runtime_seconds'] .= trim($result[7]) . ',';
         $data['free_memory_gigabytes'] .= trim($result[3]) . ',';
         $data['load_average_15_minutes'] .= trim($result[1]) . ',';
         $data['free_space_terabtyes'] .= trim($result[5]) . ',';
         $data['portfolio_cache_gigabytes'] .= trim($result[6]) . ',';
         
         }
         else {
         
            // Format or round primary currency price depending on value (non-stablecoin crypto values are already stored in the format we want for the interface)
            if ( $fiat_formatting == 1 ) {
            $data['spot'] .= ( float_to_string($result[1]) >= 1.00 ? number_format((float)$result[1], 2, '.', '')  :  round($result[1], $app_config['primary_currency_decimals_max'])  ) . ',';
            $data['volume'] .= round($result[2]) . ',';
            }
            // Non-stablecoin crypto
            else {
            $data['spot'] .= $result[1] . ',';
            $data['volume'] .= round($result[2], 3) . ',';
            }
         
         }
		
		
		}
	
  }

fclose($fn);

// Trim away extra commas
$data['time'] = rtrim($data['time'],',');

	if ( $system_statistics_chart == 1 ) {
	$data['temperature_celsius'] = rtrim($data['temperature_celsius'],',');
	$data['free_memory_percentage'] = rtrim($data['free_memory_percentage'],',');
	$data['cron_runtime_seconds'] = rtrim($data['cron_runtime_seconds'],',');
	$data['free_memory_gigabytes'] = rtrim($data['free_memory_gigabytes'],',');
	$data['load_average_15_minutes'] = rtrim($data['load_average_15_minutes'],',');
	$data['free_space_terabtyes'] = rtrim($data['free_space_terabtyes'],',');
	$data['portfolio_cache_gigabytes'] = rtrim($data['portfolio_cache_gigabytes'],',');
	}
	else {
	$data['spot'] = rtrim($data['spot'],',');
	$data['volume'] = rtrim($data['volume'],',');
	}

return $data;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function update_cookies($set_coin_values, $set_pairing_values, $set_market_values, $set_paid_values, $set_leverage_values, $set_margintype_values) {

           
           // Cookies expire in 1 year (31536000 seconds)
           
           // Portfolio data
           store_cookie_contents("coin_amounts", $set_coin_values, mktime()+31536000);
           store_cookie_contents("coin_pairings", $set_pairing_values, mktime()+31536000);
           store_cookie_contents("coin_markets", $set_market_values, mktime()+31536000);
           store_cookie_contents("coin_paid", $set_paid_values, mktime()+31536000);
           store_cookie_contents("coin_leverage", $set_leverage_values, mktime()+31536000);
           store_cookie_contents("coin_margintype", $set_margintype_values, mktime()+31536000);
           
           

           // UI settings (not included in any portfolio data)
           if ( $_POST['submit_check'] == 1 ) {
           	
               
               if ( $_POST['show_charts'] != NULL ) {
               store_cookie_contents("show_charts", $_POST['show_charts'], mktime()+31536000);
               }
               else {
               store_cookie_contents("show_charts", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['show_charts']);  // Delete any existing cookies
               }
              
               if ( $_POST['theme_selected'] != NULL ) {
               store_cookie_contents("theme_selected", $_POST['theme_selected'], mktime()+31536000);
               }
               else {
               store_cookie_contents("theme_selected", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['theme_selected']);  // Delete any existing cookies
               }
               
               if ( $_POST['sort_by'] != NULL ) {
               store_cookie_contents("sort_by", $_POST['sort_by'], mktime()+31536000);
               }
               else {
               store_cookie_contents("sort_by", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['sort_by']);  // Delete any existing cookies
               }
              
               if ( $_POST['use_alert_percent'] != NULL ) {
               store_cookie_contents("alert_percent", $_POST['use_alert_percent'], mktime()+31536000);
               }
               else {
               store_cookie_contents("alert_percent", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['alert_percent']);  // Delete any existing cookies
               }
              
               if ( $_POST['primary_currency_market_standalone'] != NULL ) {
               store_cookie_contents("primary_currency_market_standalone", $_POST['primary_currency_market_standalone'], mktime()+31536000);
               }
               else {
               store_cookie_contents("primary_currency_market_standalone", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['primary_currency_market_standalone']);  // Delete any existing cookies
               }
              
           	
               // Notes (only creation / deletion here, update logic is in cookies.php)
               if ( $_POST['use_notes'] == 1 && !$_COOKIE['notes_reminders'] ) {
               store_cookie_contents("notes_reminders", " ", mktime()+31536000); // Initialized with some whitespace when blank
               }
               elseif ( $_POST['use_notes'] != 1 ) {
               store_cookie_contents("notes_reminders", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['notes_reminders']);  // Delete any existing cookies
               }
           
           
           }
           
 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function delete_all_cookies() {

// To be safe, delete cookies using 2 methods
  
  // Portfolio
  store_cookie_contents("coin_amounts", "", time()-3600);  
  store_cookie_contents("coin_pairings", "", time()-3600);  
  store_cookie_contents("coin_markets", "", time()-3600);   
  store_cookie_contents("coin_paid", "", time()-3600);    
  store_cookie_contents("coin_leverage", "", time()-3600);  
  store_cookie_contents("coin_margintype", "", time()-3600);  
  
  
  // Settings
  store_cookie_contents("coin_reload", "", time()-3600);  
  store_cookie_contents("notes_reminders", "", time()-3600);   
  store_cookie_contents("show_charts", "", time()-3600);  
  store_cookie_contents("theme_selected", "", time()-3600);  
  store_cookie_contents("sort_by", "", time()-3600);  
  store_cookie_contents("alert_percent", "", time()-3600); 
  store_cookie_contents("primary_currency_market_standalone", "", time()-3600); 
  
  
  // --------------------------
  
  
  // Portfolio
  unset($_COOKIE['coin_amounts']); 
  unset($_COOKIE['coin_pairings']); 
  unset($_COOKIE['coin_markets']); 
  unset($_COOKIE['coin_paid']); 
  unset($_COOKIE['coin_leverage']); 
  unset($_COOKIE['coin_margintype']); 
  
  
  // Settings
  unset($_COOKIE['coin_reload']);  
  unset($_COOKIE['notes_reminders']);
  unset($_COOKIE['show_charts']);  
  unset($_COOKIE['theme_selected']);  
  unset($_COOKIE['sort_by']);  
  unset($_COOKIE['alert_percent']);  
  unset($_COOKIE['primary_currency_market_standalone']);  
 
 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function pretty_numbers($value_to_pretty, $num_decimals, $small_unlimited=false) {

global $app_config;

// Pretty number formatting, while maintaining decimals


// Strip formatting, convert from scientific format, and remove leading / trailing zeros
$raw_value_to_pretty = remove_number_format($value_to_pretty);

// Do any rounding that may be needed now (skip WATCH-ONLY 9 decimal values)
if ( float_to_string($raw_value_to_pretty) > 0.00000000 && $small_unlimited != TRUE ) { 
$raw_value_to_pretty = number_format($raw_value_to_pretty, $num_decimals, '.', '');
}

// AFTER ROUNDING, RE-PROCESS removing leading / trailing zeros
$raw_value_to_pretty = float_to_string($raw_value_to_pretty);
	    
	    
	    // Pretty things up...
	    
	    
	    	if ( preg_match("/\./", $raw_value_to_pretty) ) {
	    	$value_no_decimal = preg_replace("/\.(.*)/", "", $raw_value_to_pretty);
	    	$decimal_amount = preg_replace("/(.*)\./", "", $raw_value_to_pretty);
	    	$check_decimal_amount = '0.' . $decimal_amount;
	    	}
	    	else {
	    	$value_no_decimal = $raw_value_to_pretty;
	    	$decimal_amount = NULL;
	    	$check_decimal_amount = NULL;
	    	}
	    	
	    	
	    // Limit $decimal_amount to $num_decimals (unless it's a watch-only asset)
	    if ( $raw_value_to_pretty != 0.000000001 ) {
	    $decimal_amount = ( iconv_strlen($decimal_amount, $app_config['charset_standard']) > $num_decimals ? substr($decimal_amount, 0, $num_decimals) : $decimal_amount );
	    }
	    
	    	
	    	// Show EVEN IF LOW VALUE IS OFF THE MAP, just for UX purposes (tracking token price only, etc)
	    	if ( float_to_string($raw_value_to_pretty) > 0.00000000 && $small_unlimited == TRUE ) {  
	    		
	    		if ( $num_decimals == 2 ) {
	    		$value_to_pretty = number_format($raw_value_to_pretty, 2, '.', ',');
	    		}
	    		else {
				// $value_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$value_to_pretty = number_format($value_no_decimal, 0, '.', ',') . ( float_to_string($check_decimal_amount) > 0.00000000 ? '.' . $decimal_amount : '' );
	    		}
	    	
	    	}
	    	// Show low value only with $decimal_amount minimum
	    	elseif ( float_to_string($raw_value_to_pretty) >= 0.00000001 && $small_unlimited == FALSE ) {  
	    		
	    		if ( $num_decimals == 2 ) {
	    		$value_to_pretty = number_format($raw_value_to_pretty, 2, '.', ',');
	    		}
	    		else {
				// $value_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$value_to_pretty = number_format($value_no_decimal, 0, '.', ',') . ( float_to_string($check_decimal_amount) > 0.00000000 ? '.' . $decimal_amount : '' );
	    		}
	    	
	    	}
	    	else {
	    	$value_to_pretty = 0;
	    	}
	    	
	    	
	    
return $value_to_pretty;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function zip_recursively($source, $destination) {
	
		
		if ( !extension_loaded('zip') ) {
			return 'no_extension';
		}
		elseif ( !file_exists($source) ) {
			return 'no_source';
		}
	
		$zip = new ZipArchive();
		if ( !$zip->open($destination, ZIPARCHIVE::CREATE) ) {
			return 'no_open_dest';
		}
	
		$source = str_replace('\\', '/', realpath($source));
	
		if ( is_dir($source) === true ) {
			
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
	
			foreach ($files as $file) {
				
				$file = str_replace('\\', '/', $file);
	
				// Ignore "." and ".." folders
				if ( in_array( substr($file, strrpos($file, '/')+1) , array('.', '..') ) )
					continue;
	
				$file = realpath($file);
	
				if (is_dir($file) === true) {
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				elseif (is_file($file) === true) {
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
				
			}
			
		}
		elseif ( is_file($source) === true ) {
			$zip->addFromString(basename($source), file_get_contents($source));
		}
	
		return $zip->close();
		
    
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function start_page_html($page) {
?>
<span class='start_page_menu'> 
	
	<select onchange='
	
		if ( this.value == "index.php?start_page=<?=$page?>" ) {
		var anchor = "#<?=$page?>";
		}
		else {
		var anchor = "";
		}
	
	// This start page method saves portfolio data during the session, even without cookie data enabled
	var set_action = this.value + anchor;
	set_target_action("coin_amounts", "_self", set_action);
	document.coin_amounts.submit();
	
	'>
		<option value='index.php'> Show Portfolio Page First </option>
		<?php
		if ( $_GET['start_page'] != '' && $_GET['start_page'] != $page ) {
		$another_set = 1;
		?>
		<option value='index.php?start_page=<?=$_GET['start_page']?>' selected > Show <?=ucwords( preg_replace("/_/i", " ", $_GET['start_page']) )?> Page First </option>
		<?php
		}
		?>
		<option value='index.php?start_page=<?=$page?>' <?=( $_GET['start_page'] == $page ? 'selected' : '' )?> > Show <?=ucwords( preg_replace("/_/i", " ", $page) )?> Page First </option>
	</select> 
	
</span>

	<?php
	if ( $another_set == 1 ) {
	?>
	<span class='red'>&nbsp;(another secondary page is currently the start page)</span>
	 <br clear='all' />
	<?php
	}
	
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function safe_mail($to, $subject, $message, $content_type='text', $charset=null) {
	
global $app_version, $app_config;

	if ( $charset == null ) {
	$charset = $app_config['charset_standard'];
	}

// Stop injection vulnerability
$app_config['from_email'] = str_replace("\r\n", "", $app_config['from_email']); // windows -> unix
$app_config['from_email'] = str_replace("\r", "", $app_config['from_email']);   // remaining -> unix

// Trim any (remaining) whitespace off ends
$app_config['from_email'] = trim($app_config['from_email']);
$to = trim($to);
		
		
	// Validate TO email
	$email_check = validate_email($to);
	if ( $email_check != 'valid' ) {
	return $email_check;
	}
	
	
	// SMTP mailing, or PHP's built-in mail() function
	if ( $app_config['smtp_login'] != '' && $app_config['smtp_server'] != '' ) {
	return @smtp_mail($to, $subject, $message, $content_type, $charset); 
	}
	else {
		
		// Use array for safety from header injection >= PHP 7.2 
		if ( PHP_VERSION_ID >= 70200 ) {
	
		$headers = array(
	    					'From' => $app_config['from_email'],
	    					'X-Mailer' => 'DFD_Cryptocoin_Values/' . $app_version . ' - PHP/' . phpversion(),
	    					'Content-Type' => $content_type . '/plain; charset=' . $charset
							);
	
		}
		else {
			
		$headers = 'From: ' . $app_config['from_email'] . "\r\n" .
    	'X-Mailer: DFD_Cryptocoin_Values/' . $app_version . ' - PHP/' . phpversion() .
    	'Content-Type: ' . $content_type . '/plain; charset=' . $charset;
    	
		}
	
	return @mail($to, $subject, $message, $headers);
	
	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function backup_archive($backup_prefix, $backup_target, $interval) {

global $app_config, $base_dir, $base_url;


	if ( update_cache_file('cache/events/backup_'.$backup_prefix.'.dat', ( $interval * 1440 ) ) == true ) {

	$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
	
	
		// We only want to store backup files with suffixes that can't be guessed, 
		// otherwise halt the application if an issue is detected safely creating a random hash
		if ( $secure_128bit_hash == false ) {
		app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for '.$backup_prefix.' backup archive filename suffix, backup aborted to preserve backups directory privacy');
		}
		else {
			
			$backup_file = $backup_prefix . '_'.date( "Y-M-d", time() ).'_'.$secure_128bit_hash.'.zip';
			$backup_dest = $base_dir . '/cache/secured/backups/' . $backup_file;
			
			// Zip archive
			$backup_results = zip_recursively($backup_target, $backup_dest);
			
			
				if ( $backup_results == 1 ) {
					
				store_file_contents($base_dir . '/cache/events/backup_'.$backup_prefix.'.dat', time_date_format(false, 'pretty_date_time') );
					
				$backup_url = 'download.php?backup=' . $backup_file;
				
				$message = "A backup archive has been created for: ".$backup_prefix."\n\nHere is a link to download the backup to your computer: " . $base_url . $backup_url . "\n\n(backup archives are purged after " . $app_config['delete_old_backups'] . " days)";
				
				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
				$send_params = array(
										'email' => array(
															'subject' => 'DFD Cryptocoin Values - Backup Archive For: ' . $backup_prefix,
															'message' => $message
															)
										);
							
				// Send notifications
				@queue_notifications($send_params);
				
				}
				else {
				app_logging('other_error', 'Backup zip archive creation failed with ' . $backup_results);
				}
				
		
		}
	

	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function get_last_lines($file, $linecount, $length) {
	
$linecount = $linecount + 2; // Offset including blank data on ends

$length = $length * 1.5; // Offset to assure we get enough data

//we double the offset factor on each iteration
//if our first guess at the file offset doesn't
//yield $linecount lines
$offset_factor = 1;

$bytes = filesize($file);

$fp = fopen($file, "r");

	if ( !$fp ) {
	return false;
	}


	$complete = false;
	while ( !$complete ) {
		
		
		//seek to a position close to end of file
		$offset = $linecount * $length * $offset_factor;
		fseek($fp, -$offset, SEEK_END);
	
	
		//we might seek mid-line, so read partial line
		//if our offset means we're reading the whole file, 
		//we don't skip...
		if ( $offset < $bytes ) {
		fgets($fp);
		}
	
	
		//read all following lines, store last x
		$lines = array();
		while( !feof($fp) ) {
			
			$line = fgets($fp);
			array_push($lines, $line);
			
			if ( count($lines) > $linecount ) {
			array_shift($lines);
			$complete = true;
			}
			
		}
	
	
		//if we read the whole file, we're done, even if we
		//don't have enough lines
		if ( $offset >= $bytes ) {
		$complete = true;
		}
		else {
		$offset_factor *= 2; //otherwise let's seek even further back
		}
		 
		 
	}

fclose($fp);


	if ( !$lines ) {
	return false;
	}
	else {
	return array_slice( $lines, (0 - $linecount) );
	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function debugging_logs() {

global $app_config, $base_dir;

	if ( $app_config['debug_mode'] == 'off' ) {
	return false;
	}

// Combine all debugging logged
$debugging_logs .= strip_tags($_SESSION['api_data_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($_SESSION['config_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($_SESSION['security_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($_SESSION['cmc_config_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($_SESSION['other_debugging']); // Remove any HTML formatting used in UI alerts


	foreach ( $_SESSION['cache_debugging'] as $debugging ) {
	$debugging_logs .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
	}


	// If it's time to email debugging logs...
	if ( $app_config['mail_logs'] > 0 && update_cache_file('cache/events/email-debugging-logs.dat', ( $app_config['mail_logs'] * 1440 ) ) == true ) {
		
	$emailed_logs = "\n\n ------------------debugging.log------------------ \n\n" . file_get_contents('cache/logs/debugging.log') . "\n\n ------------------smtp_debugging.log------------------ \n\n" . file_get_contents('cache/logs/smtp_debugging.log');
		
	$message = " Here are the current debugging logs from the ".$base_dir."/cache/logs/ directory: \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No debugging logs currently.' );
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'DFD Cryptocoin Values - Debugging Logs Report',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @queue_notifications($send_params);
          	
	store_file_contents($base_dir . '/cache/events/email-debugging-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
	
	}
	
	
	// Log debugging...Purge old logs before storing new logs, if it's time to...otherwise just append.
	if ( update_cache_file('cache/events/purge-debugging-logs.dat', ( $app_config['purge_logs'] * 1440 ) ) == true ) {
	store_file_contents($base_dir . '/cache/logs/debugging.log', $debugging_logs); // NULL if no new debugging, but that's OK because we are purging any old entries 
	store_file_contents($base_dir . '/cache/logs/smtp_debugging.log', NULL);
	store_file_contents('cache/events/purge-debugging-logs.dat', date('Y-m-d H:i:s'));
	}
	elseif ( $debugging_logs != NULL ) {
	store_file_contents($base_dir . '/cache/logs/debugging.log', $debugging_logs, "append");
	}
	

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function error_logs() {

global $app_config, $base_dir;

// Combine all errors logged
$error_logs .= strip_tags($_SESSION['api_data_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($_SESSION['config_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($_SESSION['security_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($_SESSION['cmc_config_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($_SESSION['other_error']); // Remove any HTML formatting used in UI alerts


	foreach ( $_SESSION['cache_error'] as $error ) {
	$error_logs .= strip_tags($error); // Remove any HTML formatting used in UI alerts
	}


	// If it's time to email error logs...
	if ( $app_config['mail_logs'] > 0 && update_cache_file('cache/events/email-error-logs.dat', ( $app_config['mail_logs'] * 1440 ) ) == true ) {
		
	$emailed_logs = "\n\n ------------------errors.log------------------ \n\n" . file_get_contents('cache/logs/errors.log') . "\n\n ------------------smtp_errors.log------------------ \n\n" . file_get_contents('cache/logs/smtp_errors.log');
		
	$message = " Here are the current error logs from the ".$base_dir."/cache/logs/ directory: \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No error logs currently.' );
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'DFD Cryptocoin Values - Error Logs Report',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @queue_notifications($send_params);
          	
	store_file_contents($base_dir . '/cache/events/email-error-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
	
	}
	
	
	// Log errors...Purge old logs before storing new logs, if it's time to...otherwise just append.
	if ( update_cache_file('cache/events/purge-error-logs.dat', ( $app_config['purge_logs'] * 1440 ) ) == true ) {
	store_file_contents($base_dir . '/cache/logs/errors.log', $error_logs); // NULL if no new errors, but that's OK because we are purging any old entries 
	store_file_contents($base_dir . '/cache/logs/smtp_errors.log', NULL);
	store_file_contents('cache/events/purge-error-logs.dat', date('Y-m-d H:i:s'));
	}
	elseif ( $error_logs != NULL ) {
	store_file_contents($base_dir . '/cache/logs/errors.log', $error_logs, "append");
	}
	

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function queue_notifications($send_params) {

global $base_dir, $app_config;


	// Queue messages
	
	// RANDOM HASH SHOULD BE CALLED PER-STATEMENT, OTHERWISE FOR SOME REASON SEEMS TO REUSE SAME HASH FOR THE WHOLE RUNTIME INSTANCE (if set as a variable beforehand)
	
	// Notifyme
   if ( $send_params['notifyme'] != '' && trim($app_config['notifyme_accesscode']) != '' ) {
	store_file_contents($base_dir . '/cache/secured/messages/notifyme-' . random_hash(8) . '.queue', $send_params['notifyme']);
   }
  
   // Textbelt
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
   if ( $send_params['text']['message'] != '' && trim($app_config['textbelt_apikey']) != '' && $app_config['textlocal_account'] == '' ) { // Only run if textlocal API isn't being used to avoid double texts
	store_file_contents($base_dir . '/cache/secured/messages/textbelt-' . random_hash(8) . '.queue', $send_params['text']['message']);
   }
  
   // Textlocal
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
   if ( $send_params['text']['message'] != '' && $app_config['textlocal_account'] != '' && trim($app_config['textbelt_apikey']) == '' ) { // Only run if textbelt API isn't being used to avoid double texts
	store_file_contents($base_dir . '/cache/secured/messages/textlocal-' . random_hash(8) . '.queue', $send_params['text']['message']);
   }
   
           
   // Text email
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
	// Only use text-to-email if other text services aren't configured
   if ( $send_params['text']['message'] != '' && validate_email( text_email($app_config['to_text']) ) == 'valid' && trim($app_config['textbelt_apikey']) == '' && $app_config['textlocal_account'] == '' ) { 
   
   // $send_params['text_charset'] SHOULD ALWAYS BE SET FROM THE CALL TO HERE (for emojis, or other unicode characters to send via text message properly)
   // SUBJECT !!MUST BE SET!! OR SOME TEXT SERVICES WILL NOT ACCEPT THE MESSAGE!
   $textemail_array = array('subject' => 'Text Notify', 'message' => $send_params['text']['message'], 'content_type' => 'text', 'charset' => $send_params['text']['charset'] );
   
   	// json_encode() only accepts UTF-8, SO TEMPORARILY CONVERT TO THAT FOR MESSAGE QUEUE STORAGE
   	if ( strtolower($send_params['text']['charset']) != 'utf-8' ) {
   		
   		foreach( $textemail_array as $textemail_key => $textemail_value ) {
   		$textemail_array[$textemail_key] = mb_convert_encoding($textemail_value, 'UTF-8', mb_detect_encoding($textemail_value, "auto") );
   		}
   	
   	}
   
	store_file_contents($base_dir . '/cache/secured/messages/textemail-' . random_hash(8) . '.queue', json_encode($textemail_array) );
	
   }
          
   // Normal email
   if ( $send_params['email']['message'] != '' && validate_email($app_config['to_email']) == 'valid' ) {
   
   $email_array = array('subject' => $send_params['email']['subject'], 'message' => $send_params['email']['message'], 'content_type' => ( $send_params['email']['content_type'] ? $send_params['email']['content_type'] : 'text' ), 'charset' => ( $send_params['email']['charset'] ? $send_params['email']['charset'] : $app_config['charset_standard'] ) );
   
   	// json_encode() only accepts UTF-8, SO TEMPORARILY CONVERT TO THAT FOR MESSAGE QUEUE STORAGE
   	if ( strtolower($send_params['email']['charset']) != 'utf-8' ) {
   		
   		foreach( $email_array as $email_key => $email_value ) {
   		$email_array[$email_key] = mb_convert_encoding($email_value, 'UTF-8', mb_detect_encoding($email_value, "auto") );
   		}
   	
   	}
   
	store_file_contents($base_dir . '/cache/secured/messages/normalemail-' . random_hash(8) . '.queue', json_encode($email_array) );
	
   }
  

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function system_info() {

global $runtime_mode, $app_version, $base_dir;
	


// OS
$system['operating_system'] = PHP_OS;
	
	
	
	// CPU stats
	if ( is_readable('/proc/cpuinfo') ) {
	$cpu_info = @file_get_contents('/proc/cpuinfo');
	
	$raw_cpu_info_array = explode("\n", $cpu_info);
	
		foreach ( $raw_cpu_info_array as $cpu_info_field ) {
		
			if ( trim($cpu_info_field) != '' ) {
				
			$temp_array = explode(":", $cpu_info_field);
			
				$loop = 0;
				foreach ( $temp_array as $key => $value ) {
				$trimmed_value = ( $loop < 1 ? strtolower(trim($value)) : trim($value) );
				$trimmed_value = ( $loop < 1 ? preg_replace('/\s/', '_', $trimmed_value) : $trimmed_value );
				$temp_array_cleaned[$key] = $trimmed_value;
				$loop = $loop + 1;
				}
			
			$cpu_info_array[$temp_array_cleaned[0]] = $temp_array_cleaned[1];
			}
		
		}
	
	$cpu['cpu_info'] = $cpu_info_array;
	
		if ( $cpu['cpu_info']['model'] ) {
		$system['model'] = $cpu['cpu_info']['model'];
		}
		
		if ( $cpu['cpu_info']['hardware'] ) {
		$system['hardware'] = $cpu['cpu_info']['hardware'];
		}
		
		if ( $cpu['cpu_info']['model_name'] ) {
		$system['model_name'] = $cpu['cpu_info']['model_name'];
		}
	
	}


	
	// Uptime stats
	if ( is_readable('/proc/uptime') ) {
		
 	$uptime_info = @file_get_contents('/proc/uptime');
 	
 	$num   = floatval($uptime_info);
	$secs  = fmod($num, 60); $num = (int)($num / 60);
	$mins  = $num % 60;      $num = (int)($num / 60);
	$hours = $num % 24;      $num = (int)($num / 24);
	$days  = $num;
 	
 	$system['uptime'] = $days . ' days, ' . $hours . ' hours, ' . $mins . ' minutes, ' . round($secs) . ' seconds';
 	
	}
	
	
	
	// System loads
	$loop = 1;
	foreach ( sys_getloadavg() as $load ) {
		
		if ( $loop == 1 ) {
		$time = 1;
		}
		elseif ( $loop == 2 ) {
		$time = 5;
		}
		elseif ( $loop == 3 ) {
		$time = 15;
		}
		
	$system['system_load'] .= $load . ' (' . $time . ' min avg) ';
	$loop = $loop + 1;
	}
	$system['system_load'] = trim($system['system_load']);
	


	// Temperature stats
	if ( is_readable('/sys/class/thermal/thermal_zone0/temp') ) {
 	$temp_info = @file_get_contents('/sys/class/thermal/thermal_zone0/temp');
 	$system['system_temp'] = round($temp_info/1000) . '° Celsius';
	}
	elseif ( is_readable('/sys/class/thermal/thermal_zone1/temp') ) {
 	$temp_info = @file_get_contents('/sys/class/thermal/thermal_zone1/temp');
 	$system['system_temp'] = round($temp_info/1000) . '° Celsius';
	}
	elseif ( is_readable('/sys/class/thermal/thermal_zone2/temp') ) {
 	$temp_info = @file_get_contents('/sys/class/thermal/thermal_zone2/temp');
 	$system['system_temp'] = round($temp_info/1000) . '° Celsius';
	}
	


	// Memory stats
	if ( is_readable('/proc/meminfo') ) {
		
    $data = explode("\n", file_get_contents("/proc/meminfo"));
    
    	foreach ($data as $line) {
        list($key, $val) = explode(":", $line);
        $ram['ram_'.strtolower($key)] = trim($val);
    	}
   
		if ( $ram['ram_memtotal'] ) {
		$system['memory_total'] = $ram['ram_memtotal'];
		}
   
		if ( $ram['ram_memfree'] ) {
		$system['memory_free'] = $ram['ram_memfree'];
		}
   
		if ( $ram['ram_swapcached'] ) {
		$system['memory_swap'] = $ram['ram_swapcached'];
		}
	
	}
	


// Free space on this partition
$system['free_partition_space'] = convert_bytes( disk_free_space($base_dir) , 3);



	// App cache size (update hourly, or if runtime is cron)
	if ( update_cache_file('cache/vars/cache_size.dat', (60 * 1) ) == true || $runtime_mode == 'cron' ) {  
	$portfolio_cache = convert_bytes( directory_size($base_dir . '/cache/') , 3);
	store_file_contents($base_dir . '/cache/vars/cache_size.dat', $portfolio_cache);
	}
	
$system['portfolio_cache'] = ( $portfolio_cache != '' ? $portfolio_cache : trim( file_get_contents('cache/vars/cache_size.dat') ) );
	


// Software
$system['software'] = 'DFD_Cryptocoin_Values/' . $app_version . ' - PHP/' . phpversion();



	// Server stats
	if ( is_readable('/proc/stat') ) {
	$server_info = @file_get_contents('/proc/stat');
	
	$raw_server_info_array = explode("\n", $server_info);
	
		foreach ( $raw_server_info_array as $server_info_field ) {
		
			if ( trim($server_info_field) != '' ) {
				
			$server_info_field = preg_replace('/\s/', ':', $server_info_field, 1);
				
			$temp_array = explode(":", $server_info_field);
				
				$loop = 0;
				foreach ( $temp_array as $key => $value ) {
				$trimmed_value = ( $loop < 1 ? strtolower(trim($value)) : trim($value) );
				$trimmed_value = ( $loop < 1 ? preg_replace('/\s/', '_', $trimmed_value) : $trimmed_value );
				$temp_array_cleaned[$key] = $trimmed_value;
				$loop = $loop + 1;
				}
			
			$server_info_array[$temp_array_cleaned[0]] = $temp_array_cleaned[1];
			}
		
		}
	
	$server['server_info'] = $server_info_array;
	
	}
	


return $system;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function send_notifications() {

global $base_dir, $app_config;


// Array of currently queued messages in the cache
$messages_queue = sort_files($base_dir . '/cache/secured/messages', 'queue', 'asc');
	
//var_dump($messages_queue); // DEBUGGING ONLY
//return false; // DEBUGGING ONLY


	// If queued messages exist, proceed
	if ( sizeof($messages_queue) > 0 ) {
	
	
	
		if ( !isset($_SESSION['notifications_count']) ) {
		$_SESSION['notifications_count'] = 0;
		}
		
		
		
		// If it's been well over 5 minutes since a notifyme alert was sent 
		// (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), 
		// and no session count is set, set session count to zero
		// Don't update the file-cached count here, that will happen automatically from resetting the session count to zero 
		// (if there are notifyme messages queued to send)
		if ( !isset($_SESSION['notifyme_count']) && update_cache_file($base_dir . '/cache/events/notifyme-alerts-sent.dat', 6 ) == true ) {
		$_SESSION['notifyme_count'] = 0;
		}
		// If it hasn't been well over 5 minutes since the last notifyme send
		// (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), and there is no session count, 
		// use the file-cached count for the session count starting point
		elseif ( !isset($_SESSION['notifyme_count']) && update_cache_file($base_dir . '/cache/events/notifyme-alerts-sent.dat', 6 ) == false ) {
		$_SESSION['notifyme_count'] = trim( file_get_contents($base_dir . '/cache/events/notifyme-alerts-sent.dat') );
		}
		
		
		
		if ( !isset($_SESSION['text_count']) ) {
		$_SESSION['text_count'] = 0;
		}
		
		
		
		if ( !isset($_SESSION['email_count']) ) {
		$_SESSION['email_count'] = 0;
		}
	
		
	
		// ONLY process queued messages IF they are NOT already being processed by another runtime instance
		// Use file locking with flock() to do this
		$fp = fopen($base_dir . '/cache/events/notifications-queue-processing.dat', "w+");
		if ( flock($fp, LOCK_EX) ) {  // If we are allowed a file lock, we can proceed
		
		
		////////////START//////////////////////
		
		
			// Sleep for 2 seconds before starting ANY consecutive message send, to help avoid being blacklisted
			if ( $_SESSION['notifications_count'] > 0 ) {
			sleep(2);
			}
			
		
		
		$notifyme_params = array(
									 'notification' => NULL, // Setting this right before sending
									 'accessCode' => $app_config['notifyme_accesscode']
									   );
						
						
		$textbelt_params = array(
									 'message' => NULL, // Setting this right before sending
									 'phone' => text_number($app_config['to_text']),
									 'key' => $app_config['textbelt_apikey']
									);
						
						
		$textlocal_params = array(
									  'message' => NULL, // Setting this right before sending
									  'username' => string_to_array($app_config['textlocal_account'])[0],
									  'hash' => string_to_array($app_config['textlocal_account'])[1],
									  'numbers' => text_number($app_config['to_text'])
									   );
		
		
		
			
			// Send messages
			foreach ( $messages_queue as $queued_cache_file ) {
				
			
			
			$message_data = trim( file_get_contents($base_dir . '/cache/secured/messages/' . $queued_cache_file) );
			
			
				
				// Notifyme
			   if ( $message_data != '' && trim($app_config['notifyme_accesscode']) != '' && preg_match("/notifyme/i", $queued_cache_file) ) { 
			   
			   $notifyme_params['notification'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive notifyme message, to throttle MANY outgoing messages, to help avoid being blacklisted
				$notifyme_sleep = 1 * $_SESSION['notifyme_count'];
				sleep($notifyme_sleep);
				
					
					// Only 5 notifyme messages allowed per minute
					if ( $_SESSION['notifyme_count'] < 5 ) {
					
					$notifyme_response = @api_data('array', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
				
					$_SESSION['notifyme_count'] = $_SESSION['notifyme_count'] + 1;
					
					$message_sent = 1;
					
					store_file_contents($base_dir . '/cache/events/notifyme-alerts-sent.dat', $_SESSION['notifyme_count']); 
					
						if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' ) {
						store_file_contents($base_dir . '/cache/logs/debugging/api/last-response-notifyme.log', $notifyme_response);
						}
					
					unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
					
					}
				
				
				
			   }
			  
			  
			  
			   // Textbelt
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only run if textlocal API isn't being used to avoid double texts
			   if ( $message_data != '' && trim($app_config['textbelt_apikey']) != '' && $app_config['textlocal_account'] == '' && preg_match("/textbelt/i", $queued_cache_file) ) {  
			   
			   $textbelt_params['message'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blacklisted
				$text_sleep = 1 * $_SESSION['text_count'];
				sleep($text_sleep);
			   
			   $textbelt_response = @api_data('array', $textbelt_params, 0, 'https://textbelt.com/text', 2);
			   
			   $_SESSION['text_count'] = $_SESSION['text_count'] + 1;
				
				$message_sent = 1;
			   
			   	if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' ) {
					store_file_contents($base_dir . '/cache/logs/debugging/api/last-response-textbelt.log', $textbelt_response);
					}
				
				unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
				
			   }
			  
			  
			  
			   // Textlocal
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only run if textbelt API isn't being used to avoid double texts
			   if ( $message_data != '' && $app_config['textlocal_account'] != '' && trim($app_config['textbelt_apikey']) == '' && preg_match("/textlocal/i", $queued_cache_file) ) {  
			   
			   $textlocal_params['message'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blacklisted
				$text_sleep = 1 * $_SESSION['text_count'];
				sleep($text_sleep);
			   
			   $textlocal_response = @api_data('array', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
			   
			   $_SESSION['text_count'] = $_SESSION['text_count'] + 1;
				
				$message_sent = 1;
			   
			   	if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' ) {
					store_file_contents($base_dir . '/cache/logs/debugging/api/last-response-textlocal.log', $textlocal_response);
					}
				
				unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
				
			   }
			   
					   
					   
			   // SEND EMAILS LAST, IN CASE OF SMTP METHOD FAILURE AND RUNTIME EXIT
			  
			  
			  
			   // Text email
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only use text-to-email if other text services aren't configured
			   if ( validate_email( text_email($app_config['to_text']) ) == 'valid' && trim($app_config['textbelt_apikey']) == '' && $app_config['textlocal_account'] == '' && preg_match("/textemail/i", $queued_cache_file) ) { 
			   
			   $textemail_array = json_decode($message_data, true);
			   
			   $restore_text_charset = $textemail_array['charset'];
			   
   				// json_encode() only accepts UTF-8, SO CONVERT BACK TO ORIGINAL CHARSET
   				if ( strtolower($restore_text_charset) != 'utf-8' ) {
   					
   					foreach( $textemail_array as $textemail_key => $textemail_value ) {
   					// Leave charset / content_type vars UTF-8
   					$textemail_array[$textemail_key] = ( $textemail_key == 'charset' || $textemail_key == 'content_type' ? $textemail_value : mb_convert_encoding($textemail_value, $restore_text_charset, 'UTF-8') );
   					}
   	
   				}
   
			   
					if ( $textemail_array['subject'] != '' && $textemail_array['message'] != '' ) {
						
					// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blacklisted
					$text_sleep = 1 * $_SESSION['text_count'];
					sleep($text_sleep);
			   
					$result = @safe_mail( text_email($app_config['to_text']) , $textemail_array['subject'], $textemail_array['message'], $textemail_array['content_type'], $textemail_array['charset']);
			   
			   		if ( $result == true ) {
			   		
			   		$_SESSION['text_count'] = $_SESSION['text_count'] + 1;
			   	
						$message_sent = 1;
					
						unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
			   		
			   		}
			   		else {
			   		app_logging( 'other_error', 'Email-to-mobile-text sending failed', 'to_text_email: ' . text_email($app_config['to_text']) . '; from: ' . $app_config['from_email'] . '; subject: ' . $textemail_array['subject'] . '; function_response: ' . $result . ';');
			   		}
					
					
					}
				
				
			   }
					  
					  
					  
			   // Normal email
			   if ( validate_email($app_config['to_email']) == 'valid' && preg_match("/normalemail/i", $queued_cache_file) ) {
			   
			   $email_array = json_decode($message_data, true);
			   
			   $restore_email_charset = $email_array['charset'];
			   
   				// json_encode() only accepts UTF-8, SO CONVERT BACK TO ORIGINAL CHARSET
   				if ( strtolower($restore_email_charset) != 'utf-8' ) {
   					
   					foreach( $email_array as $email_key => $email_value ) {
   					// Leave charset / content_type vars UTF-8
   					$email_array[$email_key] = ( $email_key == 'charset' || $email_key == 'content_type' ? $email_value : mb_convert_encoding($email_value, $restore_email_charset, 'UTF-8') );
   					}
   	
   				}
			   
			   
					if ( $email_array['subject'] != '' && $email_array['message'] != '' ) {
			   
					// Sleep for 1 second EXTRA on EACH consecutive email message, to throttle MANY outgoing messages, to help avoid being blacklisted
					$email_sleep = 1 * $_SESSION['email_count'];
					sleep($email_sleep);
			   
					$result = @safe_mail($app_config['to_email'], $email_array['subject'], $email_array['message'], $email_array['content_type'], $email_array['charset']);
			   
			   		if ( $result == true ) {
			   		
			   		$_SESSION['email_count'] = $_SESSION['email_count'] + 1;
			   	
						$message_sent = 1;
					
						unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
			   		
			   		}
			   		else {
			   		app_logging( 'other_error', 'Email sending failed', 'to_email: ' . $app_config['to_email'] . '; from: ' . $app_config['from_email'] . '; subject: ' . $email_array['subject'] . '; function_response: ' . $result . ';');
			   		}
			   		
					
					}
				
				
			   }
			   
		   
			
			
		   }
	  
	  
	  
			if ( $message_sent == 1 ) {
			$_SESSION['notifications_count'] = $_SESSION['notifications_count'] + 1;
			}
		
		
		
		////////////END//////////////////////
		
		
		
		// We are done processing the queue, so we can release the lock
	   fwrite($fp, time_date_format(false, 'pretty_date_time'). " UTC (with file lock)\n");
	   fflush($fp);            // flush output before releasing the lock
	   flock($fp, LOCK_UN);    // release the lock
		return true;
		
		} 
		else {
	   fwrite($fp, time_date_format(false, 'pretty_date_time'). " UTC (no file lock)\n");
	   return false; // Another runtime instance was already processing the queue, so skip processing and return false
		}
		fclose($fp);
	
	
	
	}
	else {
	return false; // No messages are queued to send, so skip and return false
	}



}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function api_data($mode, $request, $ttl, $api_server=null, $post_encoding=3, $test_proxy=NULL, $headers=NULL) { // Default to JSON encoding post requests (most used)

// $app_config['btc_primary_currency_pairing'] / $app_config['btc_primary_exchange'] / $btc_primary_currency_value USED FOR TRACE DEBUGGING (TRACING)
global $base_dir, $app_config, $api_runtime_cache, $btc_primary_currency_value, $user_agent;

$cookie_jar = tempnam('/tmp','cookie');
	
// To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
$hash_check = ( $mode == 'array' ? md5(serialize($request)) : md5($request) );


	// Cache API data if set to cache...runtime cache is only for runtime cache (deleted at end of runtime)
	// ...persistent cache is the file cache (which only reliably updates near end of a runtime session because of file locking)


	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// FIRST, see if we have data in the RUNTIME cache (the MEMORY cache, NOT the FILE cache), for the quickest data retrieval time
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	if ( $api_runtime_cache[$hash_check] ) {
	
	$data = $api_runtime_cache[$hash_check];
	
		
		if ( $data == 'none' ) {
		
			if ( !$_SESSION['error_duplicates'][$hash_check] ) {
			$_SESSION['error_duplicates'][$hash_check] = 1; 
			}
			else {
			$_SESSION['error_duplicates'][$hash_check] = $_SESSION['error_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
		
		app_logging( 'cache_error', 'no data in RUNTIME cache from connection failure with ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint request at ' . $request ), 'request attempt(s) from: cache ('.$_SESSION['error_duplicates'][$hash_check].' runtime instances); proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $hash_check . ';', $hash_check );
			
		}
		elseif ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' || $app_config['debug_mode'] == 'api_cache_only' ) {
		
			if ( !$_SESSION['debugging_duplicates'][$hash_check] ) {
			$_SESSION['debugging_duplicates'][$hash_check] = 1; 
			}
			else {
			$_SESSION['debugging_duplicates'][$hash_check] = $_SESSION['debugging_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
		
		app_logging( 'cache_debugging', 'RUNTIME cache data request for ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint at ' . $request ), 'request(s) from: cache ('.$_SESSION['debugging_duplicates'][$hash_check].' runtime instances); proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $hash_check . ';', $hash_check );
		
		}
	
	
	}
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// If flagged for FILE cache deletion with -1 as $ttl
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif ( $ttl < 0 ) {
	unlink('cache/apis/'.$hash_check.'.dat');
	}
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// Live data retrieval 
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif ( update_cache_file('cache/apis/'.$hash_check.'.dat', $ttl) == true && $ttl > 0 && !$api_runtime_cache[$hash_check] 
	|| $ttl == 0 && !$api_runtime_cache[$hash_check] ) {	
	
	$ch = curl_init( ( $mode == 'array' ? $api_server : '' ) );
	
	
		// If this is an API service that requires multiple calls (for each market), 
		// and a request to it has been made consecutively, we throttle it to avoid being blacklisted
		$check_api_endpoint = ( $mode == 'array' ? $api_server : $request );
		$endpoint_tld = get_tld($check_api_endpoint);
		
		
		// Throttled endpoints
		if ( in_array($endpoint_tld, $app_config['limited_apis']) ) {
		
		$tld_session_prefix = preg_replace("/\./i", "_", $endpoint_tld);
		
			if ( !$_SESSION[$tld_session_prefix . '_calls'] ) {
			$_SESSION[$tld_session_prefix . '_calls'] = 1;
			}
			elseif ( $_SESSION[$tld_session_prefix . '_calls'] == 1 ) {
			usleep(1550000); // Throttle 1.55 seconds
			}

		}
		
		
		// If header data is being passed in
		if ( $headers != NULL ) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		
		
		// If proxies are configured
		if ( sizeof($app_config['proxy_list']) > 0 ) {
			
		$current_proxy = ( $mode == 'proxy-check' && $test_proxy != NULL ? $test_proxy : random_proxy() );
		
		// Check for valid proxy config
		$ip_port = explode(':', $current_proxy);

		$ip = $ip_port[0];
		$port = $ip_port[1];

			// If no ip/port detected in data string, cancel and continue runtime
			if ( !$ip || !$port ) {
			app_logging('api_data_error', 'proxy '.$current_proxy.' is not a valid format');
			return FALSE;
			}

		
		curl_setopt($ch, CURLOPT_PROXY, trim($current_proxy) );     
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);  
		
			// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
			if ( $app_config['proxy_login'] != ''  ) {
		
			$user_pass = explode('||', $app_config['proxy_login']);
				
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $user_pass[0] . ':' . $user_pass[1] );  
			
			}
		
		} 
		else {
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
		}
		
		if ( $mode == 'array' && $post_encoding == 1 ) {
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $request );
		}
		elseif ( $mode == 'array' && $post_encoding == 2 ) {
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt( $ch, CURLOPT_POSTFIELDS,  http_build_query($request) );
		}
		elseif ( $mode == 'array' && $post_encoding == 3 ) {
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($request) );
		}
		elseif ( $mode == 'url' || $mode == 'proxy-check' ) {
		curl_setopt($ch, CURLOPT_URL, $request);
		}
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $app_config['api_timeout']);
	curl_setopt($ch, CURLOPT_TIMEOUT, $app_config['api_timeout']);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	
	
		// If this is an SSL connection, add SSL parameters
		if (  preg_match("/https:\/\//i", ( $mode == 'array' ? $api_server : $request ) )  ) {
			
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, ( $app_config['api_strict_ssl'] == 'on' ? 2 : 0 ) );
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, ( $app_config['api_strict_ssl'] == 'on' ? TRUE : FALSE ) ); 
		
			if ( PHP_VERSION_ID >= 70700 && CURL_VERSION_ID >= 7410 ) {
			curl_setopt ($ch, CURLOPT_SSL_VERIFYSTATUS, ( $app_config['api_strict_ssl'] == 'on' ? TRUE : FALSE ) ); 
			}

		}
		
	
	$data = curl_exec($ch);
	curl_close($ch);
		
		
		
		// No data error logging
		if ( !$data ) {
		
		// LOG-SAFE VERSION (no post data with API keys etc)
		app_logging( 'api_data_error', 'connection failed for ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint request at ' . $request ), 'request attempt from: server (local timeout setting ' . $app_config['api_timeout'] . ' seconds); proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $hash_check . ';' );
		
			if ( sizeof($app_config['proxy_list']) > 0 && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops doing proxy checks

			$_SESSION['proxy_checkup'][] = array(
															'endpoint' => ( $mode == 'array' ? 'API server at ' . $api_server : 'Request URL at ' . $request ),
															'proxy' => $current_proxy
															);
															
			}
		
		}
		// Log this latest live data response, 
		// ONLY IF WE DETECT AN $endpoint_tld, AND TTL IS !NOT! ZERO (TTL==0 usually means too many unique requests that would bloat the cache)
		elseif ( $endpoint_tld != '' && $ttl != 0 ) {
		
		
			// If response seems to contain an error message
			if ( preg_match("/error/i", $data) ) {
			
			
				// ATTEMPT to weed out false positives before logging as an error
				// Needed for kraken, coinmarketcap
				// https://www.php.net/manual/en/regexp.reference.meta.php
				if ( $endpoint_tld == 'kraken.com' && preg_match("/\"error\":\[\],/i", $data) 
				|| $endpoint_tld == 'coinmarketcap.com' && preg_match("/\"error_code\": 0,/i", $data) ) {
				$false_positive = 1;
				}
				
				
				// If no false positive detected
				if ( !$false_positive ) {
				$error_response_log = '/cache/logs/errors/api/error-response-'.preg_replace("/\./", "_", $endpoint_tld).'-'.$hash_check.'.log';
			
				// LOG-SAFE VERSION (no post data with API keys etc)
				app_logging( 'api_data_error', 'POSSIBLE error response received for ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint request at ' . $request ), 'request attempt from: server (local timeout setting ' . $app_config['api_timeout'] . ' seconds); proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; log_file: ' . $error_response_log . '; btc_primary_currency_pairing: ' . $app_config['btc_primary_currency_pairing'] . '; btc_primary_exchange: ' . $app_config['btc_primary_exchange'] . '; btc_primary_currency_value: ' . $btc_primary_currency_value . '; hash_check: ' . $hash_check . ';' );
			
				// Log this error response from this data request
				store_file_contents($base_dir . $error_response_log, $data);
				
				}
		
		
			}
		
		
			// Data debugging telemetry
			if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' || $app_config['debug_mode'] == 'api_live_only' ) {
				
			// LOG-SAFE VERSION (no post data with API keys etc)
			app_logging( 'api_data_debugging', 'connection request for ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint at ' . $request ), 'request from: server (local timeout setting ' . $app_config['api_timeout'] . ' seconds); proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $hash_check . ';' );
			
			// Log this as the latest response from this data request
			store_file_contents($base_dir . '/cache/logs/debugging/api/last-response-'.preg_replace("/\./", "_", $endpoint_tld).'-'.$hash_check.'.log', $data);
			
			}
			
			
		}
		
		
		
		// Don't log cmc throttle notices, BUT nullify $data since we're getting no API data (just a throttle notice)
		if ( preg_match("/coinmarketcap/i", $request) && !preg_match("/last_updated/i", $data) ) {
		$data = NULL;
		}
	
		
		
		// Never cache proxy checking data
		if ( $mode != 'proxy-check' ) {
		$api_runtime_cache[$hash_check] = ( $data ? $data : 'none' ); // Cache API data for this runtime session AFTER PERSISTENT FILE CACHE UPDATE, file cache doesn't reliably update until runtime session is ending because of file locking
		}
		
		
		
		// Cache data to the file cache, EVEN IF WE HAVE NO DATA, TO AVOID CONSECUTIVE TIMEOUT HANGS (during page reloads etc) FROM A NON-RESPONSIVE API ENDPOINT
		if ( $ttl > 0 && $mode != 'proxy-check'  ) {
		store_file_contents($base_dir . '/cache/apis/'.$hash_check.'.dat', $api_runtime_cache[$hash_check]);
		}
		

	
	}
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// IF --FILE-- CACHE DATA WITHIN IT'S TTL EXISTS
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	else {
	
	
		// Use runtime cache if it exists. Remember file cache doesn't update until session is nearly over because of file locking, so only reliable for persisting a cache long term
		// If no API data was received, add error notices to UI / error logs (we don't try fetching the data again until cache TTL expiration, so as to NOT hang the app)
		// Run from runtime cache if requested again (for runtime speed improvements)
		if ( $api_runtime_cache[$hash_check] ) {
		$data = $api_runtime_cache[$hash_check];
		}
		else {
		$data = trim( file_get_contents('cache/apis/'.$hash_check.'.dat') );
		$api_runtime_cache[$hash_check] = $data;
		}
	
	
		
		if ( $data == 'none' ) {
		
			if ( !$_SESSION['error_duplicates'][$hash_check] ) {
			$_SESSION['error_duplicates'][$hash_check] = 1; 
			}
			else {
			$_SESSION['error_duplicates'][$hash_check] = $_SESSION['error_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
		
		app_logging( 'cache_error', 'no data in FILE cache from connection failure with ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint request at ' . $request ), 'request attempt(s) from: cache ('.$_SESSION['error_duplicates'][$hash_check].' runtime instances); proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $hash_check . ';', $hash_check );
			
		}
		elseif ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' || $app_config['debug_mode'] == 'api_cache_only' ) {
		
			if ( !$_SESSION['debugging_duplicates'][$hash_check] ) {
			$_SESSION['debugging_duplicates'][$hash_check] = 1; 
			}
			else {
			$_SESSION['debugging_duplicates'][$hash_check] = $_SESSION['debugging_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
		
		app_logging( 'cache_debugging', 'FILE cache data request for ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint at ' . $request ), 'request(s) from: cache ('.$_SESSION['debugging_duplicates'][$hash_check].' runtime instances); proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $hash_check . ';', $hash_check );
		
		}
	
	
	}
	
	

return $data;


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function test_proxy($problem_proxy_array) {

global $base_dir, $app_config, $runtime_mode;


// Endpoint to test proxy connectivity: https://www.myip.com/api-docs/
$proxy_test_url = 'https://api.myip.com/';


$problem_endpoint = $problem_proxy_array['endpoint'];
$problem_proxy = $problem_proxy_array['proxy'];

$ip_port = explode(':', $problem_proxy);

$ip = $ip_port[0];
$port = $ip_port[1];

	// If no ip/port detected in data string, cancel and continue runtime
	if ( !$ip || !$port ) {
	app_logging('api_data_error', 'proxy '.$problem_proxy.' is not a valid format');
	return FALSE;
	}

// Create cache filename / session var
$cache_filename = $problem_proxy;
$cache_filename = preg_replace("/\./", "-", $cache_filename);
$cache_filename = preg_replace("/:/", "_", $cache_filename);

	if ( $app_config['proxy_alerts_runtime'] == 'all' ) {
	$run_alerts = 1;
	}
	elseif ( $app_config['proxy_alerts_runtime'] == 'cron' && $runtime_mode == 'cron' ) {
	$run_alerts = 1;
	}
	elseif ( $app_config['proxy_alerts_runtime'] == 'ui' && $runtime_mode == 'ui' ) {
	$run_alerts = 1;
	}
	else {
	$run_alerts = NULL;
	}

	if ( $run_alerts == 1 && update_cache_file('cache/alerts/proxy-check-'.$cache_filename.'.dat', ( $app_config['proxy_alerts_freq'] * 60 ) ) == true
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
			
			$text_alert = 'Proxy ' . $problem_proxy . ' remote address mismatch (detected as: ' . $data['ip'] . '). runtime: ' . $runtime_mode;
		
			}
			
			
		$cached_logs = ( $misconfigured == 1 ? 'runtime: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = MISCONFIGURED (test endpoint ' . $proxy_test_url . ' detected the incoming ip as: ' . $data['ip'] . ')' . "; \n " . 'Remote address DOES NOT match proxy address;' : 'runtime: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = OK (test endpoint ' . $proxy_test_url . ' detected the incoming ip as: ' . $data['ip'] . ');' );
		
		
		}
		else {
			
		$misconfigured = 1;
		
		$notifyme_alert = 'A checkup on proxy ' . $ip . ', port ' . $port . ' resulted in a failed data request. No endpoint connection could be established. Runtime mode is ' . $runtime_mode . '.';
			
		$text_alert = 'Proxy ' . $problem_proxy . ' failed, no endpoint connection. runtime: ' . $runtime_mode;
		
		$cached_logs = 'runtime: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = DATA REQUEST FAILED' . "; \n " . 'No connection established at test endpoint ' . $proxy_test_url . ';';

		}
		
		
		// Log to error logs
		if ( $misconfigured == 1 ) {
		app_logging('api_data_error', 'proxy '.$problem_proxy.' connection failed', $cached_logs);
		}
	

		// Update alerts cache for this proxy (to prevent running alerts for this proxy too often)
		store_file_contents($base_dir . '/cache/alerts/proxy-check-'.$cache_filename.'.dat', $cached_logs);
			
      
      $email_alert = " The proxy " . $problem_proxy . " recently did not receive data when accessing this endpoint: \n " . $problem_endpoint . " \n \n A check on this proxy was performed at " . $proxy_test_url . ", and results logged: \n ============================================================== \n " . $cached_logs . " \n ============================================================== \n \n ";
                    
		
		// Send out alerts
		if ( $misconfigured == 1 || $app_config['proxy_checkup_ok'] == 'include' ) {
                    
                    
  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				if ( $app_config['proxy_alerts'] == 'all' ) {
  				
  				// Minimize function calls
  				$encoded_text_alert = content_data_encoding($text_alert);
  					
          	$send_params = array(
          								'notifyme' => $notifyme_alert,
          								'text' => array(
          														// Unicode support included for text messages (emojis / asian characters / etc )
          														'message' => $encoded_text_alert['content_output'],
          														'charset' => $encoded_text_alert['charset']
          														),
          								'email' => array(
          														'subject' => 'A Proxy Was Unresponsive',
          														'message' => $email_alert
          														)
          								);
          	
          	}
  				elseif ( $app_config['proxy_alerts'] == 'email' ) {
  					
          	$send_params['email'] = array(
          											'subject' => 'A Proxy Was Unresponsive',
          											'message' => $email_alert
          											);
          	
          	}
  				elseif ( $app_config['proxy_alerts'] == 'text' ) {
          	$send_params['text'] = $text_alert;
          	}
  				elseif ( $app_config['proxy_alerts'] == 'notifyme' ) {
          	$send_params['notifyme'] = $notifyme_alert;
          	}
          	
          	
          	// Send notifications
          	@queue_notifications($send_params);
          	
           
       }
          
          
		
	}



}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>
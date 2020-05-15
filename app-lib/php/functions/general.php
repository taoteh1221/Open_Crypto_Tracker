<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function substri_count($haystack, $needle) {
    return substr_count(strtoupper($haystack), strtoupper($needle));
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function test_ipv4($str) {
$ret = filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
return $ret;
}



////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function test_ipv6($str) {
$ret = filter_var($str, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
return $ret;
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function telegram_message($message, $chat_id) {

// Using 3rd party Telegram class, initiated already as global var $telegram_messaging
global $telegram_messaging;

return $telegram_messaging->send->chat($chat_id)->text($message)->send();

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


function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
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


function is_msie() {

	if ( preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT']) || preg_match("/trident/i", $_SERVER['HTTP_USER_AGENT']) ) {
	return true;
	}
	else {
	return false;
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


function delete_all_files($dir) {

$files = glob($dir . '/*'); // get all file names

	foreach($files as $file) { // iterate files
	
  		if( is_file($file) ) {
    	unlink($file); // delete file
  		}
  		
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function timestamps_usort_newest($a, $b) {
	
	if ( $a->pubDate != '' ) {
	$a = $a->pubDate;
	$b = $b->pubDate;
	}
	elseif ( $a->published != '' ) {
	$a = $a->published;
	$b = $b->published;
	}
	elseif ( $a->updated != '' ) {
	$a = $a->updated;
	$b = $b->updated;
	}

return strtotime($b) - strtotime($a);
	
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

// To keep admin nonce key a secret, and make CSRF attacks harder with a different key per submission item
function admin_hashed_nonce($key) {
	
	if ( !isset($_SESSION['admin_logged_in']) ) {
	return false;
	}
	
	if ( !isset($_SESSION['nonce']) ) {
	return false;
	}
	else {
	return hash('ripemd160', $key . $_SESSION['nonce']);
	}
	
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


function store_cookie_contents($name, $value, $time) {

$result = setcookie($name, $value, $time);
	
	
	// Android / Safari maximum cookie size is 4093 bytes, Chrome / Firefox max is 4096
	if ( strlen($value) > 4093 ) {  
	app_logging('other_error', 'Cookie size is greater than 4093 bytes (' . strlen($value) . ' bytes). If saving portfolio as cookie data fails on your browser, try using CSV file import / export instead for large portfolios.');
	}
	
	if ( $result == false ) {
	app_logging('system_error', 'Cookie creation failed for cookie "' . $name . '"');
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


// hex2bin requires PHP >= 5.4.0.
// If, for whatever reason, you are using a legacy version of PHP, you can implement hex2bin with this function:
 
if ( !function_exists('hex2bin') ) {
	
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
	if ( $href_link != false ) {
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

global $app_config, $possible_http_users, $http_runtime_user;

	if ( !is_dir($path) ) {
	
		// Run cache compatibility on certain PHP setups
		if ( !$http_runtime_user || in_array($http_runtime_user, $possible_http_users) ) {
		$oldmask = umask(0);
		$result = mkdir($path, octdec($app_config['developer']['chmod_cache_directories']), true); // Recursively create whatever path depth desired if non-existent
		umask($oldmask);
		return $result;
		}
		else {
		return  mkdir($path, octdec($app_config['developer']['chmod_cache_directories']), true); // Recursively create whatever path depth desired if non-existent
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
	app_logging('config_error', '$password_pepper not set properly');
	return false;
	}
	else {
		
	$password_pepper_hashed = hash_hmac("sha256", $password, $password_pepper);
	
		if ( $password_pepper_hashed == false ) {
		app_logging('config_error', 'hash_hmac() returned false in the pepper_hashed_password() function');
		return false;
		}
		else {
		return password_hash($password_pepper_hashed, PASSWORD_DEFAULT);
		}
	
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// Return the TLD only (no subdomain)
function get_tld_or_ip($url) {

global $app_config;

$urlData = parse_url($url);
	
	// If this is an ip address, then we can return that as the result now
	if ( test_ipv4($urlData['host']) != false || test_ipv6($urlData['host']) != false ) {
	return $urlData['host'];
	}

$hostData = explode('.', $urlData['host']);
$hostData = array_reverse($hostData);


	if ( array_search($hostData[1] . '.' . $hostData[0], $app_config['developer']['top_level_domain_map']) !== false ) {
   $host = $hostData[2] . '.' . $hostData[1] . '.' . $hostData[0];
	} 
	elseif ( array_search($hostData[0], $app_config['developer']['top_level_domain_map']) !== false ) {
   $host = $hostData[1] . '.' . $hostData[0];
 	}


return strtolower( trim($host) );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function check_pepper_hashed_password($input_password, $stored_hashed_password) {

global $password_pepper;

	if ( !$password_pepper ) {
	app_logging('config_error', '$password_pepper not set properly');
	return false;
	}
	else {
		
	$input_password_pepper_hashed = hash_hmac("sha256", $input_password, $password_pepper);
	
		if ( $input_password_pepper_hashed == false ) {
		app_logging('config_error', 'hash_hmac() returned false in the check_pepper_hashed_password() function');
		return false;
		}
		else {
		return password_verify($input_password_pepper_hashed, $stored_hashed_password);
		}
		
	}

}
    

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// Credit: https://www.alexkras.com/simple-rss-reader-in-85-lines-of-php/
function get_rss_feeds($chosen_feeds, $feed_size, $recache_only=false) {
	
global $app_config;

$news_feeds = $app_config['power_user']['news_feeds'];

	 // If we are just re-caching for quick use later (as cron job, for faster ui load times)
	 if ( $recache_only == true ) {
	 	foreach($news_feeds as $feed_key => $feed_unused) {
	 		if ( trim($news_feeds[$feed_key]["url"]) != '' ) {
	 		rss_feed_data($news_feeds[$feed_key]["url"], $feed_size, $news_feeds[$feed_key]["atom_format"]);
	 		}
	 	}
	 }
	 elseif ( is_array($chosen_feeds) ) {
	 
	 $html = "";

	 // Alphabetically sort chosen feeds
	 sort($chosen_feeds);
    
    	foreach($chosen_feeds as $feed) {
    	
    	$feed = str_replace(array('[',']'),'',$feed); // Remove brackets from js storage format
    
    		if ( is_array($news_feeds[$feed]) ) {
    		$html .= "<fieldset class='subsection_fieldset'><legend class='subsection_legend'> ".$news_feeds[$feed]["title"].'</legend>';
    		$html .= rss_feed_data($news_feeds[$feed]["url"], $feed_size, $news_feeds[$feed]["atom_format"]);
    		$html .= "</fieldset>";    
    		}
    
    	}

	 return $html;
	 }

}
    

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function subarray_app_config_upgrade($category_key, $config_key, $skip_upgrading) {

global $upgraded_app_config, $cached_app_config, $check_default_app_config, $default_app_config;

	// Check for new variables, and add them
	foreach ( $default_app_config[$category_key][$config_key] as $setting_key => $setting_value ) {
	
		if ( is_array($setting_value) ) {
		app_logging('config_error', 'Sub-array depth to deep for app config upgrade parser');
		}
		elseif ( !in_array($setting_key, $skip_upgrading) && !isset($upgraded_app_config[$category_key][$config_key][$setting_key]) ) {
		$upgraded_app_config[$category_key][$config_key][$setting_key] = $default_app_config[$category_key][$config_key][$setting_key];
		app_logging('config_error', 'New app config parameter $app_config[' . $category_key . '][' . $config_key . '][' . $setting_key . '] imported (default value: ' . $default_app_config[$category_key][$config_key][$setting_key] . ')');
		$config_upgraded = 1;
		}
			
	}
	
	// Check for depreciated variables, and remove them
	foreach ( $cached_app_config[$category_key][$config_key] as $setting_key => $setting_value ) {
	
		if ( is_array($setting_value) ) {
		app_logging('config_error', 'Sub-array depth to deep for app config upgrade parser');
		}
		elseif ( !in_array($setting_key, $skip_upgrading) && !isset($default_app_config[$category_key][$config_key][$setting_key]) ) {
		unset($upgraded_app_config[$category_key][$config_key][$setting_key]);
		app_logging('config_error', 'Depreciated app config parameter $app_config[' . $category_key . '][' . $config_key . '][' . $setting_key . '] removed');
		$config_upgraded = 1;
		}
			
	}
	
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function smtp_mail($to, $subject, $message, $content_type='text', $charset=null) {

// Using 3rd party SMTP class, initiated already as global var $smtp
global $app_config, $smtp;

	if ( $charset == null ) {
	$charset = $app_config['developer']['charset_default'];
	}
	
	
	// Fallback, if no From email set in app config
	if ( validate_email($app_config['comms']['from_email']) == 'valid' ) {
	$from_email = $app_config['comms']['from_email'];
	}
	else {
	$temp_data = explode("||", $app_config['comms']['smtp_login']);
	$from_email = $temp_data[0];
	}


$smtp->From($from_email); 
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


function app_logging($log_type, $log_message, $verbose_tracing=false, $hashcheck=false, $overwrite=false) {

global $runtime_mode, $app_config, $logs_array;


// Less verbose log category
$category = $log_type;
$category = preg_replace("/_error/i", "", $category);
$category = preg_replace("/_debugging/i", "", $category);


	// Disable logging any included verbose tracing, if log detail level config is set to normal, AND debug mode is off
	if ( $app_config['developer']['debug_mode'] == 'off' && $app_config['developer']['log_verbosity'] == 'normal' ) {
	$verbose_tracing = false;
	}


	if ( $hashcheck != false ) {
	$logs_array[$log_type][$hashcheck] = '[' . date('Y-m-d H:i:s') . '] ' . $runtime_mode . ' => ' . $category . ': ' . $log_message . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
	}
	elseif ( $overwrite != false ) {
	$logs_array[$log_type] = '[' . date('Y-m-d H:i:s') . '] ' . $runtime_mode . ' => ' . $category . ': ' . $log_message . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
	}
	else {
	$logs_array[$log_type] .= '[' . date('Y-m-d H:i:s') . '] ' . $runtime_mode . ' => ' . $category . ': ' . $log_message . ( $verbose_tracing != false ? '; [ '  . $verbose_tracing . ' ]' : ';' ) . " <br /> \n";
	}


}
 

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////

 
 // For captcha image
 // Credit to: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
function captcha_string($input, $strength=10) {
	
    $input_length = strlen($input);
    $random_string = '';
    
    	
        
        $count = 0;
        	while ( $count < $strength ) {
        			
        			$rand_case = rand(1, 2);
        		   if( $rand_case % 2 == 0 ){ 
        			// Even number  
        			$random_character = strtoupper( $input[mt_rand(0, $input_length - 1)] );
    				} 
    				else { 
        			// Odd number
        			$random_character = strtolower( $input[mt_rand(0, $input_length - 1)] );
    				} 
        	
        		if ( stristr($random_string, $random_character) == false ) {
        		//echo $random_character . ' -- ';
        		$random_string .= $random_character;
            $count = $count + 1;
        		}
        	
        	}
        
  
    return $random_string;
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function valid_username($username) {

global $app_config;

    if ( mb_strlen($username, $app_config['developer']['charset_default']) < 4 ) {
    $error .= "requires 4 minimum characters; ";
    }
    
    if ( mb_strlen($username, $app_config['developer']['charset_default']) > 30 ) {
    $error .= "requires 30 maximum characters; ";
    }
    
	 if ( !preg_match("/^[a-z]([a-z0-9]+)$/", $username) ) {
    $error .= "lowercase letters and numbers only (lowercase letters first, then optionally numbers, no spaces); ";
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
        $result = false;
        
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


function smtp_vars() {

// To preserve SMTPMailer class upgrade structure, by creating a global var to be run in classes/smtp-mailer/conf/config_smtp.php

global $app_version, $base_dir, $app_config;

$vars = array();

$log_file = $base_dir . "/cache/logs/smtp_errors.log";
$log_file_debugging = $base_dir . "/cache/logs/smtp_debugging.log";

// Don't overwrite globals
$temp_smtp_email_login = explode("||", $app_config['comms']['smtp_login'] );
$temp_smtp_email_server = explode(":", $app_config['comms']['smtp_server'] );

// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
$smtp_user = trim($temp_smtp_email_login[0]);
$smtp_password = $temp_smtp_email_login[1];

$smtp_host = trim($temp_smtp_email_server[0]);
$smtp_port = trim($temp_smtp_email_server[1]);

// Port vars over to class format (so it runs out-of-the-box as much as possible)
$vars['cfg_log_file']   = $log_file;
$vars['cfg_log_file_debugging']   = $log_file_debugging;
$vars['cfg_server']   = $smtp_host;
$vars['cfg_port']     =  $smtp_port;
$vars['cfg_secure']   = $app_config['comms']['smtp_secure'];
$vars['cfg_username'] = $smtp_user;
$vars['cfg_password'] = $smtp_password;
$vars['cfg_debug_mode'] = $app_config['developer']['debug_mode']; // DFD Cryptocoin Values debug mode setting
$vars['cfg_strict_ssl'] = $app_config['power_user']['smtp_strict_ssl']; // DFD Cryptocoin Values strict SSL setting
$vars['cfg_app_version'] = $app_version; // DFD Cryptocoin Values version

return $vars;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function base_url($atRoot=false, $atCore=false, $parse=false) {
	
// WARNING: THIS ONLY WORKS WELL FOR HTTP-BASED RUNTIME, ----NOT CLI---!
// CACHE IT TO FILE DURING UI RUNTIME FOR CLI TO USE LATER ;-)

	if ( isset($_SERVER['HTTP_HOST']) ) {
        	
   $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
   $hostname = $_SERVER['HTTP_HOST'];
   $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

   $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), null, PREG_SPLIT_NO_EMPTY);
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


	if ( $offset == false ) {
	$time = time();
	}
	else {
	$time = time() + ( $offset * (60 * 60) );  // Offset is in hours
	}


	if ( $mode == false ) {
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
	$content_type = 'Content-type: text/csv; charset=' . $app_config['developer']['charset_default'];
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
	if ( ( $handle = fopen($file, "r") ) != false ) {
		
		while ( ( $data = fgetcsv($handle, 0, ",") ) != false ) {
			
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


/* Usage: 

// HTML
$content = getTextBetweenTags('a', $html);

foreach( $content as $item ) {
    echo $item.'<br />';
}

// XML
$content2 = getTextBetweenTags('description', $xml, 1);

foreach( $content2 as $item ) {
    echo $item.'<br />';
}

*/

// Credit: https://phpro.org/examples/Get-Text-Between-Tags.html
function getTextBetweenTags($tag, $html, $strict=0) {
    /*** a new dom object ***/
    $dom = new domDocument;

    /*** load the html into the object ***/
    if($strict==1)
    {
        $dom->loadXML($html);
    }
    else
    {
        $dom->loadHTML($html);
    }

    /*** discard white space ***/
    $dom->preserveWhiteSpace = false;

    /*** the tag by its tag name ***/
    $content = $dom->getElementsByTagname($tag);

    /*** the array to return ***/
    $out = array();
    foreach ($content as $item)
    {
        /*** add node value to the out array ***/
        $out[] = $item->nodeValue;
    }
    /*** return the results ***/
    return $out;
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
  store_cookie_contents("show_feeds", "", time()-3600);  
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
  unset($_COOKIE['show_feeds']);  
  unset($_COOKIE['theme_selected']);  
  unset($_COOKIE['sort_by']);  
  unset($_COOKIE['alert_percent']);  
  unset($_COOKIE['primary_currency_market_standalone']);  
 
 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function password_strength($password, $min_length, $max_length) {

global $app_config;

    if ( $min_length == $max_length && mb_strlen($password, $app_config['developer']['charset_default']) != $min_length ) {
    $error .= "MUST BE EXACTLY ".$min_length." characters; ";
    }
    elseif ( mb_strlen($password, $app_config['developer']['charset_default']) < $min_length ) {
    $error .= "requires AT LEAST ".$min_length." characters; ";
    }
    elseif ( mb_strlen($password, $app_config['developer']['charset_default']) > $max_length ) {
    $error .= "requires NO MORE THAN ".$max_length." characters; ";
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
	 
	 if ( preg_match('/\|\|/',$password) ) {
    $error .= "no double pipe (||) allowed; ";
	 }
	 
	 if ( preg_match('/\:/',$password) ) {
    $error .= "no colon (:) allowed; ";
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


function reset_price_alerts_notice() {

global $app_config, $price_alerts_fixed_reset_array, $default_btc_primary_currency_pairing;


// Alphabetical asset sort, for message UX 
ksort($price_alerts_fixed_reset_array);


	$count = 0;
	foreach( $price_alerts_fixed_reset_array as $reset_data ) {
	
		foreach( $reset_data as $asset_alert ) {
		
		$reset_list .= $asset_alert . ', ';
		
		$count = $count + 1;
		
		}
	
	}
	
	// Trim results
	$reset_list = trim($reset_list);
	$reset_list = rtrim($reset_list, ',');

	
	// Return if no resets occurred
	if ( $count < 1 ) {
	return;
	}


$text_message = strtoupper($default_btc_primary_currency_pairing) . ' Price Alert Fixed Reset(s) ['.$count.']: ' . $reset_list;

$email_message = 'The following ' . $count . ' ' . strtoupper($default_btc_primary_currency_pairing) . ' price alert fixed reset(s) have been processed, with the latest spot price data: ' . $reset_list;

$notifyme_message = $email_message . ' Timestamp is ' . time_date_format($app_config['general']['local_time_offset'], 'pretty_time') . '.';


// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                    
// Minimize function calls
$encoded_text_message = content_data_encoding($text_message);
                    
$send_params = array(

                     'notifyme' => $notifyme_message,
                     'telegram' => $email_message,
                     'text' => array(
                                     // Unicode support included for text messages (emojis / asian characters / etc )
                                     'message' => $encoded_text_message['content_output'],
                                     'charset' => $encoded_text_message['charset']
                                     ),
                     'email' => array(
                                      'subject' => 'Price Alert Fixed Reset Processed For ' . $count . ' Alert(s)',
                                      'message' => $email_message 
                                      )
                                      
                       );
                
                
                
// Send notifications
@queue_notifications($send_params);
      

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// Check to see if we need to upgrade the app config (add new primary vars / remove depreciated primary vars)
function upgraded_cached_app_config() {

global $upgraded_app_config, $cached_app_config, $check_default_app_config, $default_app_config;

$upgraded_app_config = $cached_app_config;


// WE LEAVE THE SUB-ARRAYS FOR PROXIES / CHARTS / TEXT GATEWAYS / PORTFOLIO ASSETS / ETC / ETC ALONE
// (ANY SUB-ARRAY WHERE A USER ADDS / DELETES VARIABLES THEY WANTED DIFFERENT FROM DEFAULT VARS)
$skip_upgrading = array(
								'proxy',
								'tracked_markets',
								'crypto_pairing',
								'crypto_pairing_preferred_markets',
								'bitcoin_currency_markets',
								'bitcoin_preferred_currency_markets',
								'ethereum_subtoken_ico_values',
								'mobile_network_text_gateways',
								'portfolio_assets',
								'news_feeds',
								);


	// If no cached app config or it's corrupt, just use full default app config
	if ( $cached_app_config != true ) {
	return $default_app_config;
	}
	// If the default app config has changed since last check (from upgrades / end user editing)
	elseif ( $check_default_app_config != md5(serialize($default_app_config)) ) {
		
		
		// Check for new variables, and add them
		foreach ( $default_app_config as $category_key => $category_value ) {
			
			foreach ( $category_value as $config_key => $config_value ) {
		
				if ( !in_array($category_key, $skip_upgrading) && !in_array($config_key, $skip_upgrading) ) {
					
					if ( is_array($config_value) ) {
					subarray_app_config_upgrade($category_key, $config_key, $skip_upgrading);
					}
					elseif ( !isset($upgraded_app_config[$category_key][$config_key]) ) {
					$upgraded_app_config[$category_key][$config_key] = $default_app_config[$category_key][$config_key];
					app_logging('config_error', 'New app config parameter $app_config[' . $category_key . '][' . $config_key . '] imported (default value: ' . $default_app_config[$category_key][$config_key] . ')');
					$config_upgraded = 1;
					}
			
				}
			
			}
		
		}
		
		
		// Check for depreciated variables, and remove them
		foreach ( $cached_app_config as $cached_category_key => $cached_category_value ) {
			
			foreach ( $cached_category_value as $cached_config_key => $cached_config_value ) {
		
				if ( !in_array($cached_category_key, $skip_upgrading) && !in_array($cached_config_key, $skip_upgrading) ) {
				
					if ( is_array($cached_config_value) ) {
					subarray_app_config_upgrade($cached_category_key, $cached_config_key, $skip_upgrading);
					}
					elseif ( !isset($default_app_config[$cached_category_key][$cached_config_key]) ) {
					unset($upgraded_app_config[$cached_category_key][$cached_config_key]);
					app_logging('config_error', 'Depreciated app config parameter $app_config[' . $cached_category_key . '][' . $cached_config_key . '] removed');
					$config_upgraded = 1;
					}
					
				}
				
			}
			
		}
		
	
	return $upgraded_app_config;
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
$set_charset = $app_config['developer']['charset_default'];

$words = explode(" ", $content);
	
	
	foreach ( $words as $scan_key => $scan_value ) {
		
	$scan_value = trim($scan_value);
	
	$scan_charset = ( mb_detect_encoding($scan_value, 'auto') != false ? mb_detect_encoding($scan_value, 'auto') : null );
	
		if ( isset($scan_charset) && !preg_match("/" . $app_config['developer']['charset_default'] . "/i", $scan_charset) && !preg_match("/ASCII/i", $scan_charset) ) {
		$set_charset = $app_config['developer']['charset_unicode'];
		}
	
	}

	
	foreach ( $words as $word_key => $word_value ) {
		
	$word_value = trim($word_value);
	
	$word_charset = ( mb_detect_encoding($word_value, 'auto') != false ? mb_detect_encoding($word_value, 'auto') : null );
	
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
		
	
	if ( $set_charset == $app_config['developer']['charset_unicode'] ) {
		
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


	// #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
	if ( array_key_exists($chart_format, $app_config['power_user']['bitcoin_currency_markets']) && !array_key_exists($chart_format, $app_config['power_user']['crypto_pairing']) ) {
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
         $data['used_memory_percentage'] .= trim($result[4]) . ',';
         $data['cron_runtime_seconds'] .= trim($result[7]) . ',';
         $data['used_memory_gigabytes'] .= trim($result[3]) . ',';
         $data['load_average_15_minutes'] .= trim($result[1]) . ',';
         $data['free_disk_space_terabtyes'] .= trim($result[5]) . ',';
         $data['portfolio_cache_size_gigabytes'] .= trim($result[6]) . ',';
         
         }
         else {
         
            // Format or round primary currency price depending on value (non-stablecoin crypto values are already stored in the format we want for the interface)
            if ( $fiat_formatting == 1 ) {
            $data['spot'] .= ( number_to_string($result[1]) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? number_format((float)$result[1], 2, '.', '')  :  round($result[1], $app_config['general']['primary_currency_decimals_max'])  ) . ',';
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
	$data['used_memory_percentage'] = rtrim($data['used_memory_percentage'],',');
	$data['cron_runtime_seconds'] = rtrim($data['cron_runtime_seconds'],',');
	$data['used_memory_gigabytes'] = rtrim($data['used_memory_gigabytes'],',');
	$data['load_average_15_minutes'] = rtrim($data['load_average_15_minutes'],',');
	$data['free_disk_space_terabtyes'] = rtrim($data['free_disk_space_terabtyes'],',');
	$data['portfolio_cache_size_gigabytes'] = rtrim($data['portfolio_cache_size_gigabytes'],',');
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
           	
               
               if ( $_POST['show_charts'] != null ) {
               store_cookie_contents("show_charts", $_POST['show_charts'], mktime()+31536000);
               }
               else {
               store_cookie_contents("show_charts", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['show_charts']);  // Delete any existing cookies
               }
               
               if ( $_POST['show_feeds'] != null ) {
               store_cookie_contents("show_feeds", $_POST['show_feeds'], mktime()+31536000);
               }
               else {
               store_cookie_contents("show_feeds", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['show_feeds']);  // Delete any existing cookies
               }
              
               if ( $_POST['theme_selected'] != null ) {
               store_cookie_contents("theme_selected", $_POST['theme_selected'], mktime()+31536000);
               }
               else {
               store_cookie_contents("theme_selected", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['theme_selected']);  // Delete any existing cookies
               }
               
               if ( $_POST['sort_by'] != null ) {
               store_cookie_contents("sort_by", $_POST['sort_by'], mktime()+31536000);
               }
               else {
               store_cookie_contents("sort_by", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['sort_by']);  // Delete any existing cookies
               }
              
               if ( $_POST['use_alert_percent'] != null ) {
               store_cookie_contents("alert_percent", $_POST['use_alert_percent'], mktime()+31536000);
               }
               else {
               store_cookie_contents("alert_percent", "", time()-3600);  // Delete any existing cookies
               unset($_COOKIE['alert_percent']);  // Delete any existing cookies
               }
              
               if ( $_POST['primary_currency_market_standalone'] != null ) {
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


function zip_recursively($source, $destination, $password=false) {
	
		
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
		
		
		// If we are password-protecting
		if ( $password != false ) {
		$zip->setPassword($password);
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
					
					// If we are password-protecting
					if ( $password != false ) {
					$zip->setEncryptionName(str_replace($source . '/', '', $file), ZipArchive::EM_AES_256);
					}
					
				}
				
			}
			
		}
		elseif ( is_file($source) === true ) {
			
		$zip->addFromString(basename($source), file_get_contents($source));
			
			// If we are password-protecting
			if ( $password != false ) {
			$zip->setEncryptionName(basename($source), ZipArchive::EM_AES_256);
			}
			
		}
	
		return $zip->close();
		
    
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function start_page_html($page) {
	
	if ( $_GET['start_page'] != '' ) {
	$border_highlight = '_red';
	$text_class = 'red';
	}
	
?>
<span class='start_page_menu<?=$border_highlight?>'> 
	
	<select class='<?=$text_class?>' onchange='
	
		if ( this.value == "index.php?start_page=<?=$page?>" ) {
		var anchor = "#<?=$page?>";
		}
		else {
		var anchor = "";
		}
	
	// This start page method saves portfolio data during the session, even without cookie data enabled
	var set_action = this.value + anchor;
	set_target_action("coin_amounts", "_self", set_action);
	$("#coin_amounts").submit();
	
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
	<span class='red'>&nbsp;(this other secondary page is currently the start page)</span>
	 <br class='clear_both' />
	<?php
	}
	elseif ( $_GET['start_page'] == $page ) {
	?>
	<span class='red'>&nbsp;(this page is currently the start page)</span>
	 <br class='clear_both' />
	<?php
	}
	
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function safe_mail($to, $subject, $message, $content_type='text', $charset=null) {
	
global $app_version, $app_config;

	if ( $charset == null ) {
	$charset = $app_config['developer']['charset_default'];
	}

// Stop injection vulnerability
$app_config['comms']['from_email'] = str_replace("\r\n", "", $app_config['comms']['from_email']); // windows -> unix
$app_config['comms']['from_email'] = str_replace("\r", "", $app_config['comms']['from_email']);   // remaining -> unix

// Trim any (remaining) whitespace off ends
$app_config['comms']['from_email'] = trim($app_config['comms']['from_email']);
$to = trim($to);
		
		
	// Validate TO email
	$email_check = validate_email($to);
	if ( $email_check != 'valid' ) {
	return $email_check;
	}
	
	
	// SMTP mailing, or PHP's built-in mail() function
	if ( $app_config['comms']['smtp_login'] != '' && $app_config['comms']['smtp_server'] != '' ) {
	return @smtp_mail($to, $subject, $message, $content_type, $charset); 
	}
	else {
		
		// Use array for safety from header injection >= PHP 7.2 
		if ( PHP_VERSION_ID >= 70200 ) {
			
			// Fallback, if no From email set in app config
			if ( validate_email($app_config['comms']['from_email']) == 'valid' ) {
			
			$headers = array(
	    					'From' => $app_config['comms']['from_email'],
	    					'X-Mailer' => 'DFD_Cryptocoin_Values/' . $app_version . ' - PHP/' . phpversion(),
	    					'Content-Type' => $content_type . '/plain; charset=' . $charset
								);
			
			}
			else {
			
			$headers = array(
	    					'X-Mailer' => 'DFD_Cryptocoin_Values/' . $app_version . ' - PHP/' . phpversion(),
	    					'Content-Type' => $content_type . '/plain; charset=' . $charset
								);
			
			}
	
		}
		else {
			
			// Fallback, if no From email set in app config
			if ( validate_email($app_config['comms']['from_email']) == 'valid' ) {
			
			$headers = 'From: ' . $app_config['comms']['from_email'] . "\r\n" .
    	'X-Mailer: DFD_Cryptocoin_Values/' . $app_version . ' - PHP/' . phpversion() . "\r\n" .
    	'Content-Type: ' . $content_type . '/plain; charset=' . $charset;
    	
			}
			else {
			
			$headers = 'X-Mailer: DFD_Cryptocoin_Values/' . $app_version . ' - PHP/' . phpversion() . "\r\n" .
    	'Content-Type: ' . $content_type . '/plain; charset=' . $charset;
    	
			}
    	
		}
	
	return @mail($to, $subject, $message, $headers);
	
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


function system_info() {

global $runtime_mode, $app_version, $base_dir;
	


// OS
$system['operating_system'] = php_uname();
	
	
	
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
    	
	
	
	$memory_applications_mb = in_megabytes($ram['ram_memtotal'])['in_megs'] - in_megabytes($ram['ram_memfree'])['in_megs'] - in_megabytes($ram['ram_buffers'])['in_megs'] - in_megabytes($ram['ram_cached'])['in_megs'];
	
	$system_memory_total_mb = in_megabytes($ram['ram_memtotal'])['in_megs'];
	
	$memory_applications_percent = abs( ( $memory_applications_mb - $system_memory_total_mb ) / abs($system_memory_total_mb) * 100 );
	$memory_applications_percent = round( 100 - $memory_applications_percent, 2);
	
    	
	$system['memory_total'] = $ram['ram_memtotal'];
	
	$system['memory_buffers'] = $ram['ram_buffers'];
	
	$system['memory_cached'] = $ram['ram_cached'];
	
	$system['memory_free'] = $ram['ram_memfree'];
	
	$system['memory_swap'] = $ram['ram_swapcached'];
	
	$system['memory_used_megabytes'] = $memory_applications_mb;
	
	$system['memory_used_percent'] = $memory_applications_percent;


	}
	


// Free space on this partition
$system['free_partition_space'] = convert_bytes( disk_free_space($base_dir) , 3);



// Portfolio cache size (cached for efficiency)
$portfolio_cache = trim( file_get_contents($base_dir . '/cache/vars/cache_size.dat') );
$system['portfolio_cache'] = ( number_to_string($portfolio_cache) > 0 ? $portfolio_cache : 0 );
	


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


?>
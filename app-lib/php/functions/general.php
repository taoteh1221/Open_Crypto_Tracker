<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
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

global $proxy_list;

$proxy = array_rand($proxy_list);

return $proxy_list[$proxy];

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


// Always display very large / small numbers in non-scientific format
function floattostr($val) {

preg_match( "#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim($val), $o );

return (int)$o[1].sprintf('%d',$o[2]).($o[3]!='.'?$o[3]:'');

//return floatval($val);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function remove_number_format($text) {

$text = str_replace("    ", '', $text);
$text = str_replace(" ", '', $text);
$text = str_replace(",", "", $text);
$text = trim($text);

return floattostr($text);
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


function dir_structure($path) {

	if ( !is_dir($path) ) {
	return  mkdir($path, 0777, true); // Recursively create whatever path depth desired if non-existent
	}
	else {
	return TRUE;
	}

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


function smtp_mail($subject, $message) {

// Using 3rd party SMTP class, initiated already as global var $smtp
global $smtp;

// Added to email in post-init.php one time...because class adds to an array each call, even if already added

$smtp->Subject($subject);
$smtp->Text($message);

return $smtp->Send();

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function delete_old_files($dir, $days, $ext) {
	
$files = glob($dir."*.".$ext);

  foreach ($files as $file) {
  	
    if ( is_file($file) ) {
    	
      if ( time() - filemtime($file) >= 60 * 60 * 24 * $days ) {
      unlink($file);
      }
      
    }
    
  }
  
 }


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function create_csv_file($file, $array) {

	if ( $file == 'temp' ) {
	$file = tempnam(sys_get_temp_dir(), 'temp');
	}

$fp = fopen($file, 'w');

	foreach($array as $fields) {
	fputcsv($fp, $fields);
	}

file_download($file, 'csv'); // Download file (by default deletes after download, then exits)

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


function store_cookie_contents($name, $value, $time) {
	
global $runtime_mode;

$result = setcookie($name, $value, $time);
	
	
	// Android / Safari maximum cookie size is 4093 bytes, Chrome / Firefox max is 4096
	if ( strlen($value) > 4093 ) {  
	$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | cookie_error: Cookie size is greater than 4093 bytes (' . strlen($value) . ' bytes), which is not compatible with modern browser maximum cookie sizes. Portfolio may be too large for saving as cookie data on your particular browser. If saving portfolio as cookie data fails on your browser, try using CSV file import / export instead for large portfolios.' . "<br /> \n";
	}
	
	if ( $result == FALSE ) {
	$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | cookie_error: Cookie creation failed for cookie "' . $name . '"' . "<br /> \n";
	}
	
	
return $result;

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
				$csv_rows[$asset][] = $data[$c];
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
	
global $runtime_mode;

	if ( $mode == 'append' ) {
	$result = file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
	}
	else {
	$result = file_put_contents($file, $content, LOCK_EX);
	}
	
	
	if ( $result == FALSE ) {
	$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | file_write_error: File write failed for file "' . $file . '"' . "<br /> \n";
	}
	
	
return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function chart_data($file) {

$data = array();
$fn = fopen($file,"r");
  
  while(! feof($fn))  {
  	
	$result = explode("||", fgets($fn) );
	
		if ( trim($result[0]) != '' && trim($result[1]) != '' && trim($result[2]) != '' ) {
		$data['time'] .= trim($result[0]) . '000,';  // Zingchart wants 3 more zeros with unix time (milliseconds)
		$data['spot'] .= trim($result[1]) . ',';
		$data['volume'] .= round(trim($result[2])) . ',';
		}
	
  }

fclose($fn);

$data['time'] = rtrim($data['time'],',');
$data['spot'] = rtrim($data['spot'],',');
$data['volume'] = rtrim($data['volume'],',');

return $data;

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
	return "The email domain \"$domain\" appears incorrect.";
	}
	else {
	return "valid";
	}
			

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function random_hash($num_bytes) {

global $base_dir;

	// PHP 4 
	if ( PHP_VERSION_ID < 50000 ) {
	$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | Error: Upgrade to PHP v5 or later to support cryptographically secure pseudo-random bytes in this application, or your application may not function properly' . "<br /> \n";
	}
	// PHP 5 (V6 RELEASE WAS SKIPPED)
	elseif ( PHP_VERSION_ID < 60000 ) {
	require_once($base_dir . '/app-lib/php/other/random-compat/lib/random.php');
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


function file_download($file, $type=false, $save=false) {


	if ( $type == FALSE ) {
	$content_type = 'Content-Type: application/octet-stream';
	$filename = $file;
	}
	elseif ( $type == 'csv' ) {
	$content_type = 'Content-type: text/csv; charset=UTF-8';
	$filename = $file . '.csv';
	}


	if ( file_exists($file) ) {
		
		header('Content-Description: File Transfer');
		header($content_type);
		header('Content-Disposition: attachment; filename="'.basename($filename).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		
		$result = readfile($file);
		
			if ( $result != FALSE && $save == FALSE ) {
			unlink($file); // Delete file
			}
		
		exit;
		
	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function smtp_vars() {

// To preserve SMTPMailer class upgrade structure, by creating a global var to be run in classes/smtp-mailer/conf/config_smtp.php

global $smtp_login, $smtp_server;

$vars = array();

$log_file = preg_replace("/\/app-lib(.*)/i", "/cache/logs/errors.log", dirname(__FILE__) );

$smtp_login = explode("||", $smtp_login );
$smtp_server = explode(":", $smtp_server );

// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
$smtp_user = trim($smtp_login[0]);
$smtp_password = $smtp_login[1];

$smtp_host = trim($smtp_server[0]);
$smtp_port = trim($smtp_server[1]);

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
////////////////////////////////////////////////////////


function text_email($string) {

$string = explode("||",$string);

$number = substr($string[0], -10); // USA 10 digit number without country code
$carrier = trim($string[1]);


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

return trim($number) . $domain;

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


function store_all_cookies($set_coin_values, $set_pairing_values, $set_market_values, $set_paid_values, $set_leverage_values, $set_margintype_values) {


           // Cookies expire in 1 year (31536000 seconds)
           
           
           // Notes (only creation / deletion here, update logic is in cookies.php)
           if ( $_POST['submit_check'] == 1 && $_POST['use_notes'] == 1 && !$_COOKIE['notes_reminders'] ) {
           store_cookie_contents("notes_reminders", " ", mktime()+31536000); // Initialized with some whitespace when blank
           }
           elseif ( $_POST['submit_check'] == 1 && $_POST['use_notes'] != 1 ) {
           store_cookie_contents("notes_reminders", "", time()-3600);  // Delete any existing cookies
           unset($_COOKIE['notes_reminders']);  // Delete any existing cookies
           }
           
           
           // Charts
           if ( $_POST['submit_check'] == 1 ) {
           store_cookie_contents("show_charts", $_POST['show_charts'], mktime()+31536000);
           }
           
           
           // Portfolio
           store_cookie_contents("coin_amounts", $set_coin_values, mktime()+31536000);
           store_cookie_contents("coin_pairings", $set_pairing_values, mktime()+31536000);
           store_cookie_contents("coin_markets", $set_market_values, mktime()+31536000);
           store_cookie_contents("coin_paid", $set_paid_values, mktime()+31536000);
           store_cookie_contents("coin_leverage", $set_leverage_values, mktime()+31536000);
           store_cookie_contents("coin_margintype", $set_margintype_values, mktime()+31536000);
           
           
           
 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function delete_all_cookies() {

  store_cookie_contents("coin_amounts", "", time()-3600);  
  store_cookie_contents("coin_pairings", "", time()-3600);  
  store_cookie_contents("coin_markets", "", time()-3600);   
  store_cookie_contents("coin_paid", "", time()-3600);    
  store_cookie_contents("coin_leverage", "", time()-3600);  
  store_cookie_contents("coin_margintype", "", time()-3600);  
  store_cookie_contents("coin_reload", "", time()-3600);  
  store_cookie_contents("notes_reminders", "", time()-3600);   
  store_cookie_contents("show_charts", "", time()-3600);  
  
  
  store_cookie_contents("theme_selected", "", time()-3600);  
  store_cookie_contents("sort_by", "", time()-3600);  
  store_cookie_contents("alert_percent", "", time()-3600); 
  
  // --------------------------
  
  unset($_COOKIE['coin_amounts']); 
  unset($_COOKIE['coin_pairings']); 
  unset($_COOKIE['coin_markets']); 
  unset($_COOKIE['coin_paid']); 
  unset($_COOKIE['coin_leverage']); 
  unset($_COOKIE['coin_margintype']); 
  unset($_COOKIE['coin_reload']);  
  unset($_COOKIE['notes_reminders']);
  unset($_COOKIE['show_charts']);  
  
  
  unset($_COOKIE['theme_selected']);  
  unset($_COOKIE['sort_by']);  
  unset($_COOKIE['alert_percent']);  
 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function pretty_numbers($amount_value, $amount_decimals, $small_unlimited=false) {


// Pretty number formatting, while maintaining decimals
$raw_amount_value = remove_number_format($amount_value);
	    
	    
	    	if ( preg_match("/\./", $raw_amount_value) ) {
	    	$amount_no_decimal = preg_replace("/\.(.*)/", "", $raw_amount_value);
	    	$amount_decimal = preg_replace("/(.*)\./", "", $raw_amount_value);
	    	$check_amount_decimal = '0.' . $amount_decimal;
	    	}
	    	else {
	    	$amount_no_decimal = $raw_amount_value;
	    	$amount_decimal = NULL;
	    	$check_amount_decimal = NULL;
	    	}
	    
	    	
	    	// Show even if low value is off the map, just for UX purposes (tracking token price only, etc)
	    	if ( floattostr($raw_amount_value) > 0.00000000 && $small_unlimited == TRUE ) {  
	    		
	    		if ( $amount_decimals == 2 ) {
	    		$amount_value = number_format($raw_amount_value, 2, '.', ',');
	    		}
	    		else {
				// $X_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$amount_value = number_format($amount_no_decimal, 0, '.', ',') . ( floattostr($check_amount_decimal) > 0.00000000 ? '.' . $amount_decimal : '' );
	    		}
	    	
	    	}
	    	// Show low value only with 8 decimals minimum
	    	elseif ( floattostr($raw_amount_value) >= 0.00000001 && $small_unlimited == FALSE ) {  
	    		
	    		if ( $amount_decimals == 2 ) {
	    		$amount_value = number_format($raw_amount_value, 2, '.', ',');
	    		}
	    		else {
				// $X_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$amount_value = number_format($amount_no_decimal, 0, '.', ',') . ( floattostr($check_amount_decimal) > 0.00000000 ? '.' . $amount_decimal : '' );
	    		}
	    	
	    	}
	    	else {
	    	$amount_value = NULL;
	    	}
	    	
	    
return $amount_value;

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


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function backup_archive($backup_prefix, $backup_target, $interval) {

global $runtime_mode, $delete_old_backups, $base_dir, $base_url;


	if ( update_cache_file('cache/events/backup_'.$backup_prefix.'.dat', ( $interval * 1440 ) ) == true ) {

	$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
	
	
		// We only want to store backup files with suffixes that can't be guessed, 
		// otherwise halt the application if an issue is detected safely creating a random hash
		if ( $secure_128bit_hash == false ) {
		$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | Error: Cryptographically secure pseudo-random bytes could not be generated for '.$backup_prefix.' backup archive filename suffix, backup aborted to preserve backups directory privacy' . "<br /> \n";
		}
		else {
			
			$backup_file = $backup_prefix . '_'.date( "Y-M-d", time() ).'_'.$secure_128bit_hash.'.zip';
			$backup_dest = $base_dir . '/backups/' . $backup_file;
			
			// Zip archive
			$backup_results = zip_recursively($backup_target, $backup_dest);
			
			
				if ( $backup_results == 1 ) {
					
				store_file_contents($base_dir . '/cache/events/backup_'.$backup_prefix.'.dat', time());
					
				$backup_url = 'download.php?backup=' . $backup_file;
				
				$message = "A backup archive has been created for: ".$backup_prefix."\n\nHere is a link to download the backup to your computer: " . $base_url . $backup_url . "\n\n(backup archives are purged after " . $delete_old_backups . " days)";
				
				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
				$send_params = array(
										'email' => array(
															'subject' => 'DFD Cryptocoin Values - Backup Archive For: ' . $backup_prefix,
															'message' => $message
															)
										);
							
				// Send notifications
				@send_notifications($send_params);
				
				}
				else {
				$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | Error: Backup zip archive creation failed with '.$backup_results . "<br /> \n";
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


function error_logs($error_logs=null) {

global $purge_error_logs, $mail_error_logs, $base_dir;

// Combine all errors logged
$error_logs .= strip_tags($_SESSION['api_data_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($_SESSION['config_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($_SESSION['other_error']); // Remove any HTML formatting used in UI alerts

	foreach ( $_SESSION['repeat_error'] as $error ) {
	$error_logs .= strip_tags($error); // Remove any HTML formatting used in UI alerts
	}


	// If it's time to email error logs...
	if ( $mail_error_logs > 0 && update_cache_file('cache/events/email-error-logs.dat', ( $mail_error_logs * 1440 ) ) == true ) {
		
	$emailed_logs = file_get_contents('cache/logs/errors.log');
		
	$message = " Here are the current error logs from the ".$base_dir."/cache/logs/errors.log file: \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No error logs currently.' );
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'DFD Cryptocoin Values - Error Logs Report',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @send_notifications($send_params);
          	
	store_file_contents($base_dir . '/cache/events/email-error-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
	
	}
	
	
	// Log errors...Purge old logs before storing new logs, if it's time to...otherwise just append.
	if ( update_cache_file('cache/events/purge-error-logs.dat', ( $purge_error_logs * 1440 ) ) == true ) {
	store_file_contents($base_dir . '/cache/logs/errors.log', $error_logs); // NULL if no new errors, but that's OK because we are purging any old entries 
	store_file_contents('cache/events/purge-error-logs.dat', date('Y-m-d H:i:s'));
	}
	elseif ( $error_logs != NULL ) {
	store_file_contents($base_dir . '/cache/logs/errors.log', $error_logs, "append");
	}
	

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function send_notifications($send_params) {

global $to_email, $to_text, $notifyme_accesscode, $textbelt_apikey, $textlocal_account;


$notifyme_params = array(
								 'notification' => $send_params['notifyme'],
  				             'accessCode' => $notifyme_accesscode
  				 	           );
  				
  				
$textbelt_params = array(
								 'message' => $send_params['text'],
  				             'phone' => text_number($to_text),
  				             'key' => $textbelt_apikey
  		                    );
  				
  				
$textlocal_params = array(
								  'message' => $send_params['text'],
  				              'username' => string_to_array($textlocal_account)[0],
  				              'hash' => string_to_array($textlocal_account)[1],
  		                    'numbers' => text_number($to_text)
  				               );


	// Send messages
	
	// Notifyme
   if ( $send_params['notifyme'] != '' && trim($notifyme_accesscode) != '' ) {
   @api_data('array', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
   }
  
   // Textbelt
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
   if ( $send_params['text'] != '' && trim($textbelt_apikey) != '' && $textlocal_account == '' ) { // Only run if textlocal API isn't being used to avoid double texts
   @api_data('array', $textbelt_params, 0, 'https://textbelt.com/text', 2);
   }
  
   // Textlocal
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
   if ( $send_params['text'] != '' && $textlocal_account != '' && trim($textbelt_apikey) == '' ) { // Only run if textbelt API isn't being used to avoid double texts
   @api_data('array', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
   }
           
   // SEND EMAILS LAST, IN CASE OF SMTP METHOD FAILURE AND RUNTIME EXIT
  
   // Text email
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
   if ( $send_params['text'] != '' && validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) == '' && $textlocal_account == '' ) { 
   // Only use text-to-email if other text services aren't configured
   @safe_mail( text_email($to_text) , 'Text Notify', $send_params['text']);
   }
          
   // Email
   if ( $send_params['email']['message'] != '' && validate_email($to_email) == 'valid' ) {
   @safe_mail($to_email, $send_params['email']['subject'], $send_params['email']['message']);
   }
  

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function api_data($mode, $request, $ttl, $api_server=null, $post_encoding=3, $test_proxy=NULL) { // Default to JSON encoding post requests (most used)

global $base_dir, $user_agent, $api_timeout, $api_strict_ssl, $proxy_login, $proxy_list, $runtime_mode;

$cookie_jar = tempnam('/tmp','cookie');
	
// To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
$hash_check = ( $mode == 'array' ? md5(serialize($request)) : md5($request) );


	// Cache API data if set to cache...SESSION cache is only for runtime cache (deleted at end of runtime)...persistent cache is the file cache (which only reliably updates near end of a runtime session because of file locking)
	if ( update_cache_file('cache/apis/'.$hash_check.'.dat', $ttl) == true && $ttl > 0 && !$_SESSION['api_cache'][$hash_check] 
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
			$_SESSION['api_data_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | data attempt from: server (local timeout setting ' . $api_timeout . ' seconds) | proxy_used: ' . $current_proxy . ' | canceling API data connection, proxy '.$current_proxy.' is not a valid proxy format (required format ip:port)' . "<br /> \n";
			return FALSE;
			}

		
		curl_setopt($ch, CURLOPT_PROXY, trim($current_proxy) );     
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);  
		
			// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
			if ( $proxy_login != ''  ) {
		
			$user_pass = explode('||', $proxy_login);
				
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
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $api_timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $api_timeout);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	
	
		// If this is an SSL connection, add SSL parameters
		if (  preg_match("/https:\/\//i", ( $mode == 'array' ? $api_server : $request ) )  ) {
			
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, ( $api_strict_ssl == 'on' ? 2 : 0 ) );
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, ( $api_strict_ssl == 'on' ? TRUE : FALSE ) ); 
		
			if ( PHP_VERSION_ID >= 70700 && CURL_VERSION_ID >= 7410 ) {
			curl_setopt ($ch, CURLOPT_SSL_VERIFYSTATUS, ( $api_strict_ssl == 'on' ? TRUE : FALSE ) ); 
			}

		}
		
	
	$data = curl_exec($ch);
	curl_close($ch);


		if ( !$data ) {
		
		// SAFE UI ALERT VERSION (no post data with API keys etc)
		$_SESSION['api_data_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | data attempt from: server (local timeout setting ' . $api_timeout . ' seconds) | proxy_used: ' .( $current_proxy ? $current_proxy : 'none' ). ' | connection failed for: ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint request at ' . $request ) . "<br /> \n";
		
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
		store_file_contents($base_dir . '/cache/apis/'.$hash_check.'.dat', $_SESSION['api_cache'][$hash_check]);
		}
		

	
	}
	elseif ( $ttl < 0 ) {
	// If flagged for cache file deletion with -1 as $ttl
	unlink('cache/apis/'.$hash_check.'.dat'); // Delete cache if $ttl flagged to less than zero
	}
	else {
	
	
	// Use session cache if it exists. Remember file cache doesn't update until session is nearly over because of file locking, so only reliable for persisting a cache long term
	// If no API data was received, add error notices to UI / error logs
	$data = ( $_SESSION['api_cache'][$hash_check] ? $_SESSION['api_cache'][$hash_check] : file_get_contents('cache/apis/'.$hash_check.'.dat') );
		
		if ( $data == 'none' ) {
		
			if ( !$_SESSION['error_duplicates'][$hash_check] ) {
			$_SESSION['error_duplicates'][$hash_check] = 1; 
			}
			else {
			$_SESSION['error_duplicates'][$hash_check] = $_SESSION['error_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
		$_SESSION['repeat_error'][$hash_check] = date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | data attempt(s) from: cache ('.$_SESSION['error_duplicates'][$hash_check].' runtime instances) | proxy_used: ' .( $current_proxy ? $current_proxy : 'none' ). ' | no data in cache, from connection failure for: ' . ( $mode == 'array' ? 'API server at ' . $api_server : 'endpoint request at ' . $request ) . "<br /> \n";
			
		}
	
	
	}
	
	
return $data;


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function test_proxy($problem_proxy_array) {

global $base_dir, $proxy_alerts_freq, $proxy_alerts, $proxy_alerts_runtime, $proxy_checkup_ok, $runtime_mode;


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
		store_file_contents($base_dir . '/cache/alerts/proxy-check-'.$cache_filename.'.dat', $cached_logs);
			
      
      $email_alert = " The proxy " . $problem_proxy . " recently did not receive data when accessing this endpoint: \n " . $problem_endpoint . " \n \n A check on this proxy was performed at " . $proxy_test_url . ", and results logged: \n ============================================================== \n " . $cached_logs . " \n ============================================================== \n \n ";
                    
		
		// Send out alerts
		if ( $misconfigured == 1 || $proxy_checkup_ok == 'include' ) {
                    
                    
  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				if ( $proxy_alerts == 'all' ) {
  					
          	$send_params = array(
          								'text' => $text_alert,
          								'notifyme' => $notifyme_alert,
          								'email' => array(
          														'subject' => 'A Proxy Was Unresponsive',
          														'message' => $email_alert
          														)
          								);
          	
          	}
  				elseif ( $proxy_alerts == 'email' ) {
  					
          	$send_params['email'] = array(
          											'subject' => 'A Proxy Was Unresponsive',
          											'message' => $email_alert
          											);
          	
          	}
  				elseif ( $proxy_alerts == 'text' ) {
          	$send_params['text'] = $text_alert;
          	}
  				elseif ( $proxy_alerts == 'notifyme' ) {
          	$send_params['notifyme'] = $notifyme_alert;
          	}
          	
          	
          	// Send notifications
          	@send_notifications($send_params);
          	
           
       }
          
          
		
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>
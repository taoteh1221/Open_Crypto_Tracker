<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function max_100($num) {
return ( number_to_string($num) > 100.00 ? 100.00 : $num );
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function strip_brackets($string) {
return str_replace(array('[',']'),'',$string);
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function strip_underscore_and_after($string) {
return substr($string, 0, strpos($string, "_"));
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function substri_count($haystack, $needle) {
    return substr_count(strtoupper($haystack), strtoupper($needle));
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function string_to_array($string) {

$string = explode("||",$string);

return $string;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function strip_formatting($price) {

$price = preg_replace("/ /", "", $price); // Space
$price = preg_replace("/,/", "", $price); // Comma
$price = preg_replace("/  /", "", $price); // Tab

return $price;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function clean_array($data) {

   foreach ( $data as $key => $value ) {
   $data[$key] = trim(strip_formatting($value));
   }
        
return $data;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function remove_number_format($text) {

$text = str_replace("    ", '', $text);
$text = str_replace(" ", '', $text);
$text = str_replace(",", "", $text);
$text = trim($text);

return number_to_string($text);

}

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function random_array_var($array) {

$rand = array_rand($array);

return $array[$rand];

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


function get_digest($string, $max_length=false) {

	if ( $max_length > 0 ) {
	$result = substr( hash('ripemd160', $string) , 0, $max_length);
	}
	else {
	$result = hash('ripemd160', $string);
	}
	
return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function strip_non_alpha($string, $case=false) {

	if ( $case == 'lower' ) {
	$result = strtolower(preg_replace('/[^\w\d]+/','', $string));
	}
	else {
	$result = preg_replace('/[^\w\d]+/','', $string);
	}
	
return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function regex_compat_url($url) {
	
$regex_url = trim($url);

$regex_url = preg_replace("/(http|https|ftp|tcp|ssl):\/\//i", "", $regex_url);

$regex_url = preg_replace("/\//i", "\/", $regex_url);

return $regex_url;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function delimited_string_sample($string, $delimiter, $position, $charset='utf-8') {
	
	if ( $position == 'first' ) {
	$result = substr($string, 0, mb_strpos($string, $delimiter, 0, $charset) );
	}
	elseif ( $position == 'last' ) {
	$result = array_pop( explode(',', $string) );
	}

return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function auto_correct_string($string, $mode) {

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


function obfuscate_string($str, $show=1) {
	
$len = strlen($str);

	if ( $len <= ($show * 2) ) {
	$show = 0;
	}

	if ( $show == 0 ) {
	return str_repeat('*', $len);
	}
	else {
   return substr($str, 0, $show) . str_repeat('*', $len - (2*$show) ) . substr($str, $len - $show, $show);
	}
	
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function obfuscated_path_data($path) {
	
global $app_config;

	// Secured cache data
	if ( preg_match("/cache\/secured/i", $path) ) {
		
	$subpath = preg_replace("/(.*)cache\/secured\//i", "", $path);
	
	$subpath_array = explode("/", $subpath);
		
		// Subdirectories of /secured/
		if ( sizeof($subpath_array) > 1 ) {
		$path = str_replace($subpath_array[0], obfuscate_string($subpath_array[0], 1), $path);
		$path = str_replace($subpath_array[1], obfuscate_string($subpath_array[1], 5), $path);
		}
		// Files directly in /secured/
		else {
		$path = str_replace($subpath, obfuscate_string($subpath, 5), $path);
		}
			
	//$path = str_replace('cache/secured', obfuscate_string('cache', 0) . '/' . obfuscate_string('secured', 0), $path);
	
	}

return $path;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function obfuscated_url_data($url) {
	
global $app_config;

// Keep our color-coded logs in the admin UI pretty, remove '//' and put in parenthesis
$url = preg_replace("/:\/\//i", ") ", $url);

	// Etherscan
	if ( preg_match("/etherscan/i", $url) ) {
	$url = str_replace($app_config['general']['etherscanio_api_key'], obfuscate_string($app_config['general']['etherscanio_api_key'], 2), $url);
	}
	// Telegram
	elseif ( preg_match("/telegram/i", $url) ) {
	$url = str_replace($app_config['comms']['telegram_bot_token'], obfuscate_string($app_config['comms']['telegram_bot_token'], 2), $url); 
	}
	// Defipulse
	elseif ( preg_match("/defipulse/i", $url) ) {
	$url = str_replace($app_config['general']['defipulsecom_api_key'], obfuscate_string($app_config['general']['defipulsecom_api_key'], 2), $url); 
	}

// Keep our color-coded logs in the admin UI pretty, remove '//' and put in parenthesis
return '('.$url;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function snake_case_to_name($string) {


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
$pretty_string = preg_replace("/base/i", 'Base', $pretty_string);
$pretty_string = preg_replace("/forex/i", 'Forex', $pretty_string);
$pretty_string = preg_replace("/finex/i", 'Finex', $pretty_string);
$pretty_string = preg_replace("/stamp/i", 'Stamp', $pretty_string);
$pretty_string = preg_replace("/flyer/i", 'Flyer', $pretty_string);
$pretty_string = preg_replace("/panda/i", 'Panda', $pretty_string);
$pretty_string = preg_replace("/pay/i", 'Pay', $pretty_string);
$pretty_string = preg_replace("/swap/i", 'Swap', $pretty_string);
$pretty_string = preg_replace("/iearn/i", 'iEarn', $pretty_string);
$pretty_string = preg_replace("/pulse/i", 'Pulse', $pretty_string);
$pretty_string = preg_replace("/defi/i", 'DeFi', $pretty_string);
$pretty_string = preg_replace("/ring/i", 'Ring', $pretty_string);

return trim($pretty_string);


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function list_sort($list_string, $delimiter, $mode, $delimiter_space=false) {

$list_array = explode('/', $list_string);

// Trim
$list_array = array_map('trim', $list_array);

	if ( $mode == 'sort' ) {
	sort($list_array);
	}
	elseif ( $mode == 'asort' ) {
	asort($list_array);
	}
	elseif ( $mode == 'ksort' ) {
	ksort($list_array);
	}

	foreach( $list_array as $value ) {
		
		if ( $delimiter_space == true ) {
		$result .= $value . ' '.$delimiter.' ';
		}
		else {
		$result .= $value . $delimiter;
		}
	
	}

// Trim
$result = trim($result);
$result = trim($result, $delimiter);
$result = trim($result);

return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


// Always display very large / small numbers in non-scientific format
// Also removes any leading and trailing zeros for efficient storage / UX / etc
function number_to_string($val) {

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
		
		// Negative numbers
		if ( substr($val, 0, 1) == '-' ) {
		$val = preg_replace("/-(.*)00\./", "-0.", $val);
		}
		// Positive numbers
		else {
		$val = preg_replace("/(.*)00\./", "0.", $val);
		}
		
	}
	// IF greater than or equal to 1.00
	elseif ( $val >= 1 ) {
	$val = ltrim($val, '0');
	}
	

// Remove decimal point if an integer ie. 140. becomes 140
$val = rtrim($val, '.');
	
	
return $val; // ALWAYS RETURN, EVEN IF NEGATIVE

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function pretty_numbers($value_to_pretty, $num_decimals, $small_unlimited=false) {

global $app_config;

// Pretty number formatting, while maintaining decimals


// Strip formatting, convert from scientific format, and remove leading / trailing zeros
$raw_value_to_pretty = remove_number_format($value_to_pretty);

// Do any rounding that may be needed now (skip WATCH-ONLY 9 decimal values)
if ( number_to_string($raw_value_to_pretty) > 0.00000000 && $small_unlimited != TRUE ) { 
$raw_value_to_pretty = number_format($raw_value_to_pretty, $num_decimals, '.', '');
}

// AFTER ROUNDING, RE-PROCESS removing leading / trailing zeros
$raw_value_to_pretty = number_to_string($raw_value_to_pretty);
	    
	    
	    // Pretty things up...
	    
	    
	    	if ( preg_match("/\./", $raw_value_to_pretty) ) {
	    	$value_no_decimal = preg_replace("/\.(.*)/", "", $raw_value_to_pretty);
	    	$decimal_amount = preg_replace("/(.*)\./", "", $raw_value_to_pretty);
	    	$check_decimal_amount = '0.' . $decimal_amount;
	    	}
	    	else {
	    	$value_no_decimal = $raw_value_to_pretty;
	    	$decimal_amount = null;
	    	$check_decimal_amount = null;
	    	}
	    	
	    	
	    // Limit $decimal_amount to $num_decimals (unless it's a watch-only asset)
	    if ( $raw_value_to_pretty != 0.000000001 ) {
	    $decimal_amount = ( iconv_strlen($decimal_amount, 'utf-8') > $num_decimals ? substr($decimal_amount, 0, $num_decimals) : $decimal_amount );
	    }
	    
	    	
	    	// Show EVEN IF LOW VALUE IS OFF THE MAP, just for UX purposes (tracking token price only, etc)
	    	if ( number_to_string($raw_value_to_pretty) > 0.00000000 && $small_unlimited == true ) {  
	    		
	    		if ( $num_decimals == 2 ) {
	    		$value_to_pretty = number_format($raw_value_to_pretty, 2, '.', ',');
	    		}
	    		else {
				// $value_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$value_to_pretty = number_format($value_no_decimal, 0, '.', ',') . ( number_to_string($check_decimal_amount) > 0.00000000 ? '.' . $decimal_amount : '' );
	    		}
	    	
	    	}
	    	// Show low value only with $decimal_amount minimum
	    	elseif ( number_to_string($raw_value_to_pretty) >= 0.00000001 && $small_unlimited == false ) {  
	    		
	    		if ( $num_decimals == 2 ) {
	    		$value_to_pretty = number_format($raw_value_to_pretty, 2, '.', ',');
	    		}
	    		else {
				// $value_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$value_to_pretty = number_format($value_no_decimal, 0, '.', ',') . ( number_to_string($check_decimal_amount) > 0.00000000 ? '.' . $decimal_amount : '' );
	    		}
	    	
	    	}
	    	else {
	    	$value_to_pretty = 0;
	    	}
	    	
	    	
	    
return $value_to_pretty;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////



?>
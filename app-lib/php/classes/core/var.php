<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



class ct_var {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function str_to_array($str) {
   return explode("||",$str);
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function strip_brackets($str) {
   return str_replace(array('[',']'),'',$str);
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////


   function strip_underscore_and_after($str) {
   return substr($str, 0, strpos($str, "_"));
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   function max_100($num) {
   return ( $this->num_to_str($num) > 100.00 ? 100.00 : $num );
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function substri_count($haystack, $needle) {
   return substr_count(strtoupper($haystack), strtoupper($needle));
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function strip_formatting($str) {
   
   $str = preg_replace("/ /", "", $str); // Space
   $str = preg_replace("/,/", "", $str); // Comma
   $str = preg_replace("/  /", "", $str); // Tab
   
   return $str;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function clean_array($data) {
   
      foreach ( $data as $key => $val ) {
      $data[$key] = trim($this->strip_formatting($val));
      }
           
   return $data;
   
   }
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function random_array_var($array) {
   
   $rand = array_rand($array);
   
   return $array[$rand];
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function stristr_in_array($arr, $str) {

      foreach ($arr as $key => $val) {
           
         if ( stristr($val, $str) ) {
         return $key;
         }

      }
      
   return -1;

   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function rem_num_format($str) {
   
   $str = str_replace("    ", '', $str);
   $str = str_replace(" ", '', $str);
   $str = str_replace(",", "", $str);
   $str = trim($str);
   
      if ( is_numeric( $this->num_to_str($str) ) ) {
      return $this->num_to_str($str);
      }
      else {
      return false;
      }
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
	
	
   function strip_non_alpha($str, $case=false) {
	
	 if ( $case == 'lower' ) {
	 $result = strtolower( preg_replace('/[^\w\d]+/','', $str) );
	 }
	 else if ( $case == 'upper' ) {
	 $result = strtoupper( preg_replace('/[^\w\d]+/','', $str) );
	 }
	 else {
	 $result = preg_replace('/[^\w\d]+/','', $str);
	 }
		
   return trim($result);
	
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function delimited_str_sample($str, $delimiter, $position, $charset='utf-8') {
      
      if ( $position == 'first' ) {
      $result = substr($str, 0, mb_strpos($str, $delimiter, 0, $charset) );
      }
      elseif ( $position == 'last' ) {
      $result = array_pop( explode(',', $str) );
      }
   
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function auto_correct_str($str, $mode) {
   
      // Upper or lower case
      if ( $mode == 'lower' ) {
      $str = strtolower($str);
      }
      elseif ( $mode == 'upper' ) {
      $str = strtoupper($str);
      }
   
   // Remove all whitespace
   $str = preg_replace('/\s/', '', $str);
   
   return $str;
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function possible_base64_encoding($str) {
        
      if ( $str == '' ) {
      return false;
      }
   
   // Decode the string in strict mode, TO CHECK FOR *POSSIBLE* BASE64 ENCODING
   // (checking for illegal base64 characters)
   $possible_base64 = base64_decode($str, true);   
   
      if (
      $possible_base64
      && preg_match("/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=|[A-Za-z0-9+\/]{4})$/", $str)
      ) {
      $compare = base64_encode($possible_base64);
      }
      
      // TECHNICALLY, we CANNOT tell if ANY VALID base64 string is base64-encoded, but if it validates WELL
      // as a base64 string, we flag as possible encoding (to decode / scan for attack signatures)
      if ( isset($compare) && $compare === $str ) {
      return true;
      }
      else {
      return false;
      }
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function possible_hex_encoding($str) {
        
      if ( $str == '' ) {
      return false;
      }
   
   // Decode the string, TO CHECK FOR *POSSIBLE* HEX ENCODING
   // (checking for illegal hex characters)
   $possible_hex = hex2bin($str);   
   
      if ( $possible_hex
      && preg_match('/^(?:0x)?[a-f0-9]{1,}$/i', $str)
      ) {
      $compare = bin2hex($possible_hex);
      }
      
      // TECHNICALLY, we CANNOT tell if ANY VALID hex string is hex-encoded, but if it validates WELL
      // as a hex string, we flag as possible encoding (to decode / scan for attack signatures)
      if ( isset($compare) && $compare == $str ) {
      return true;
      }
      else {
      return false;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function obfusc_str($str, $show=1) {
      
   $len = strlen($str);
   
   
      // If string is too short for the passed $show var on each end of string, 
      // make $show roughly 20% of string length (1/5 rounded)
      if ( $len <= ($show * 2) ) {
      $show = round($len / 5);
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
   
   
   // See if $val is a whole number without decimals
   function whole_int($val) {
   
   // We need the number to be a string to test it with ctype_digit()
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
   
   
   function list_sort($list_str, $delimiter, $mode, $delimiter_space=false) {
   
   $list_array = explode('/', $list_str);
   
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
   
   
      foreach( $list_array as $val ) {
         
         if ( $delimiter_space == true ) {
         $result .= $val . ' '.$delimiter.' ';
         }
         else {
         $result .= $val . $delimiter;
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
   function num_to_str($val) {
        
   global $ct;
   
   $orig_val = $val;
   
   // Trim any whitespace off the ends
   $val = trim($val);
   
   
      // If not numeric, return orig value
      if ( !is_numeric($val) ) {
      return $orig_val;
      }
   
   
      // Covert scientific notation to a normal value / string
       
      // MUST ALLOW MAXIMUM OF 9 DECIMALS, TO COUNT WATCH-ONLY ASSETS
      // (ANYTHING OVER 9 DECIMALS SHOULD BE AVOIDED FOR UX)
      $detect_dec = (string)$val;
      // Scientific
      if ( preg_match('~\.(\d+)E([+-])?(\d+)~', $detect_dec, $matches) ) {
      $decimals = $matches[2] === '-' ? strlen($matches[1]) + $matches[3] : 0;
      }
      // Normal
      else {
      $decimals = mb_strpos( strrev($detect_dec) , '.', 0, 'utf-8');
      }
      
      
      // Get max decimals from the config settings
      if ( $ct['conf']['gen']['crypto_decimals_max'] >= $ct['conf']['gen']['currency_decimals_max'] ) {
      $dec_max = $ct['conf']['gen']['crypto_decimals_max'];
      }
      else {
      $dec_max = $ct['conf']['gen']['currency_decimals_max'];
      }
      
      
      // *PLUS ONE EXTRA DECIMAL* FOR OUR 'WATCH ONLY' PORTFOLIO LOGIC
      if ( $decimals > ($dec_max + 1) ) {
      $decimals = ($dec_max + 1);
      }
      
      
      if ( is_float($val) ) {
      $val = number_format($val, $decimals, '.', '');
      }
      else {
      $val = number_format( floatval($val) , $decimals, '.', '');
      }
   
   
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
   
   
   // Pretty number formatting, while maintaining decimals (max decimals required, min decimals optional)
   function num_pretty($val_to_pretty, $dec_max, $small_unlimited=false, $dec_min=false) {
        
   global $min_fiat_val_test, $min_crypto_val_test;
   
   // Strip formatting, convert from scientific format, and remove leading / trailing zeros
   $raw_val_to_pretty = $this->rem_num_format($val_to_pretty);
   
   
   // GET INITIAL AMOUNT OF DECIMALS
   $decimal_check = preg_replace("/(.*)\./", "", $raw_val_to_pretty);
   $raw_dec_amount = iconv_strlen($decimal_check, 'utf-8');
   
   
      // IF ORIGINAL DECIMALS IS LOWER THAN MAX DECIMALS, LOWER MAX DECIMAL TO MATCH ORIGINAL DECIMALS
      if ( $raw_dec_amount < $dec_max ) {
      $dec_max = $raw_dec_amount;
      }
      
      // IF MIN DECIMALS IS SET HIGHER THAN MAX DECIMALS, UP MAX DECIMAL TO MATCH MIN DECIMAL
      if ( $dec_min > $dec_max ) {
      $dec_max = $dec_min;
      }
      
      
      // Get overall MINIMUM value used, from the config settings
      if ( $min_crypto_val_test < $min_fiat_val_test ) {
      $min_val_test = $min_crypto_val_test;
      }
      else {
      $min_val_test = $min_fiat_val_test;
      }
      
      
      // If our value IS LESS THAN WHAT WOULD SHOW *AT ALL* WITH $min_val_test,
      // THEN SET THE FLAG $small_unlimited TO DISREGARD MAX DECIMALS (allow unlimited decimals)
      // (abs() used to properly calculate with negative numbers)
      if ( $min_val_test > abs($raw_val_to_pretty) ) {
      $small_unlimited = true;
      }
   
   
   	 // Do any MAX decimal allowed rounding that may be needed FIRST
   	 // (skip WATCH-ONLY flag values)
   	 if ( $small_unlimited != true ) { 
   	 $raw_val_to_pretty = number_format($raw_val_to_pretty, $dec_max, '.', '');
   	 }
   
   
   // AFTER MAX DECIMAL ROUNDING, RE-PROCESS removing leading / trailing zeros
   $raw_val_to_pretty = $this->num_to_str($raw_val_to_pretty);
   
   
      // IF #MIN# DECIMAL ALLOWED IS SET, THEN WE RE-PROCESS AGAIN, TO #FORCE# ANY NEEDED ZERO DECIMALS ONTO THE RIGHT SIDE
      // (skip WATCH-ONLY flag values)
      if ( $dec_min != false && $small_unlimited != true ) {
          
          if ( stristr($raw_val_to_pretty, '.') == false && $dec_min > 0 ) {
          $raw_val_to_pretty = number_format($raw_val_to_pretty, $dec_min, '.', '');
          }
          else {
      
          $decimal_check = preg_replace("/(.*)\./", "", $raw_val_to_pretty);
            
            // #ONLY IF# amount of decimals is LESS the min decimal, FORCE NEEDED ZEROS TO RIGHT SIDE
            if ( iconv_strlen($decimal_check, 'utf-8') < $dec_min ) {
            $raw_val_to_pretty = number_format($raw_val_to_pretty, $dec_min, '.', '');
            }
          
          }
      
      }
      
      
      // Optimized return for zero values
      if ( $raw_val_to_pretty == 0 ) {
          
          if ( $dec_min != false ) {
          $val_to_pretty = number_format(0, $dec_min, '.', ',');
          }
          else {
          $val_to_pretty = 0;
          }
      
      return $val_to_pretty;
          
      }
          
          
   // Ready to pretty existing numbers up now...
          
      if ( preg_match("/\./", $raw_val_to_pretty) ) {
      $val_no_decimal = preg_replace("/\.(.*)/", "", $raw_val_to_pretty);
      $decimal_amnt = preg_replace("/(.*)\./", "", $raw_val_to_pretty);
      $check_decimal_amnt = '0.' . $decimal_amnt;
      }
      else {
      $val_no_decimal = $raw_val_to_pretty;
      $decimal_amnt = null;
      $check_decimal_amnt = null;
      }
   
   
      if ( isset($decimal_amnt) && trim($decimal_amnt) != '' ) {
      $render_decimals = '.' . $decimal_amnt;
      }
   
      
   // Show decimal value with $decimal_amnt
   // $val_no_decimal stops rounding any whole number left of decimal, AND number_format gives us pretty numbers left of decimal
   $val_to_pretty = number_format($val_no_decimal, 0, '.', ',') . ( $this->num_to_str($check_decimal_amnt) > 0.00000000 || $dec_min != false ? $render_decimals : '' );
     
   return $val_to_pretty;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
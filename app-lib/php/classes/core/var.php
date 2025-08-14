<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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

   
   function length_usort($a, $b) {
   return strlen($b)-strlen($a); // Descending sort
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
   
   // Support for < PHP 7.3 (WE SUPPORT >= PHP 7.2)
   function array_key_first($arr) {
       foreach($arr as $key => $unused) {
       return $key;
       }
   return null;
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function str_replace_last($search, $replace, $str) {
        
      if( ( $pos = strrpos($str, $search) ) !== false ) {
      $search_length = strlen($search);
      $str = substr_replace($str, $replace, $pos, $search_length);
      }
   
   return $str;
    
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function assoc_array_order_map($sorting_array) {
   
   $new_array = array();
   
      foreach ($sorting_array as $key => $unused) {
      $new_array[] = $key;
      }
   
   return $new_array;
   
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
   
   
   function replace_key($array, $old_key, $new_key) {
   
   $keys = array_keys($array);
       
       if ( false === $index = array_search($old_key, $keys, true) ) {
        throw new Exception(sprintf('Key "%s" does not exist', $old_key));
       }
       
   $keys[$index] = $new_key;
   
   return array_combine($keys, array_values($array));
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function assoc_array_order($target_array, $sorting_array) {
   
   $new_array = array();
   
      // We want to preserve DEPRECIATED array keys, to remove AND LOG THE REMOVAL EVENT WITH DETAILS after this
      // ($target_array will only contain DEPRECIATED keys after unsetting EXISTING values)
      foreach ($sorting_array as $key) {
      $new_array[$key] = $target_array[$key];
      unset($target_array[$key]);
      }
      
   $arrays_merged = array_merge($new_array, $target_array);
   
   return $arrays_merged;
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   function has_string_keys($array) {
   
   $result = false;
        
        if ( !is_array($array) ) {
        return $result;
        }
        
        // https://stackoverflow.com/a/69351814/6190099
        foreach ( $array as $key => $unused ) {
        $result = ( ctype_digit( (string) $key ) ? false : true );
        }
        
   return $result;
        
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
   
   
   function usort_asc($a, $b) {
   
   global $ct;
       
       // Sort by specific subkey path value (UNLIMITED depth supported)
       if ( $ct['sort_by_nested'] ) {
            
       $nested_path = explode('=>', $ct['sort_by_nested']);
       
           foreach ( $nested_path as $level ) {
                
                if (
                isset($a[$level])
                && isset($b[$level])
                && trim($a[$level]) != ''
                && trim($b[$level]) != ''
                ) {
                $a = $a[$level];
                $b = $b[$level];
                }

           }
           
       }
       
   // Sort by STRING value, case-insensitive equivalent comparison via strtolower()
   return strnatcmp( strtolower( (string)$a ), strtolower( (string)$b ) );
   
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function usort_desc($a, $b) {
   
   global $ct;
       
       // Sort by specific subkey path value (UNLIMITED depth supported)
       if ( $ct['sort_by_nested'] ) {
            
       $nested_path = explode('=>', $ct['sort_by_nested']);
       
           foreach ( $nested_path as $level ) {
                
                if (
                isset($a[$level])
                && isset($b[$level])
                && trim($a[$level]) != ''
                && trim($b[$level]) != ''
                ) {
                $a = $a[$level];
                $b = $b[$level];
                }

           }
           
       }
       
   // Sort by STRING value, case-insensitive equivalent comparison via strtolower()
   return strnatcmp( strtolower( (string)$b ), strtolower( (string)$a ) );
   
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
   
   
   function timestamp_usort_decending($a, $b) {
        
      
      if ( isset($a['timestamp']) && isset($b['timestamp']) ) {
      $result_a = $a['timestamp'];
      $result_b = $b['timestamp'];
      }
      elseif ( isset($a->pubDate) && isset($b->pubDate) ) {
      $result_a = strtotime($a->pubDate);
      $result_b = strtotime($b->pubDate);
      }
      elseif ( isset($a->published) && isset($b->published) ) {
      $result_a = strtotime($a->published);
      $result_b = strtotime($b->published);
      }
      elseif ( isset($a->updated) && isset($b->updated) ) {
      $result_a = strtotime($a->updated);
      $result_b = strtotime($b->updated);
      }
	 // Support for the 'dc' namespace
	 elseif (
	 is_object($a)
	 && is_object( $a->children('dc', true) )
	 && sizeof( $a->children('dc', true) ) > 0
	 && is_object($b)
	 && is_object( $b->children('dc', true) )
	 && sizeof( $b->children('dc', true) ) > 0
	 ) {
			         
      $dc_namespace_a = $a->children('dc', true);
      $dc_namespace_b = $b->children('dc', true);
			         
           if ( $dc_namespace_a->date != '' && $dc_namespace_b->date != '' ) {
           $result_a = $dc_namespace_a->date;
           $result_b = $dc_namespace_b->date;
           }
			         
	 }
      elseif ( !is_array($a) && !is_array($b) ) {
      $result_a = $a;
      $result_b = $b;
      }
   
   return $result_b - $result_a;
      
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function begins_with_in_array($arr, $pattern_check) {
        
   $result = array();
   
   $max_characters_checked = strlen($pattern_check);


      $result['count'] = 0;
      foreach ($arr as $key => $val) {
         
         // We only want to check the first $max_characters_checked characters
         if ( substr($val, 0, $max_characters_checked) == $pattern_check ) {
              
         $result['count'] = $result['count'] + 1;
            
            // We only want the first instance
            // (we leave duplicate checks for logic outside this function, using $result['count'])
            if ( !isset($result['key']) ) {
            $result['key'] = $key;
            }
         
         }

      }

      
      if ( !isset($result['key']) ) {
      $result['key'] = -1;
      }

   
   return $result;

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

   
       if ( is_array($val) ) {
       
       $ct['gen']->log(
    		'other_error',
    		'num_to_str() cannot parse arrays (ONLY strings allowed): ' . json_encode($val) . '(DEBUG_BACKTRACE: ' . json_encode( debug_backtrace(2) ) . ')'
    		);
    		
       return false;

       }

   
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
      if ( $ct['conf']['currency']['crypto_decimals_max'] >= $ct['conf']['currency']['currency_decimals_max'] ) {
      $dec_max = $ct['conf']['currency']['crypto_decimals_max'];
      }
      else {
      $dec_max = $ct['conf']['currency']['currency_decimals_max'];
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
        
   global $ct;
   
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
      if ( $ct['min_crypto_val_test'] < $ct['min_fiat_val_test'] ) {
      $min_val_test = $ct['min_crypto_val_test'];
      }
      else {
      $min_val_test = $ct['min_fiat_val_test'];
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
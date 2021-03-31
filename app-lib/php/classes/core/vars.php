<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



class ocpt_var {
	
// Class variables / arrays
var $ocpt_var1;
var $ocpt_var2;
var $ocpt_var3;
var $ocpt_array1 = array();

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function max_100($num) {
   return ( $this->num_to_str($num) > 100.00 ? 100.00 : $num );
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
   
   
   function str_to_array($string) {
   
   $string = explode("||",$string);
   
   return $string;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function strip_formatting($string) {
   
   $string = preg_replace("/ /", "", $string); // Space
   $string = preg_replace("/,/", "", $string); // Comma
   $string = preg_replace("/  /", "", $string); // Tab
   
   return $string;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function clean_array($data) {
   
      foreach ( $data as $key => $value ) {
      $data[$key] = trim($this->strip_formatting($value));
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
   
   
   function rem_num_format($string) {
   
   $string = str_replace("    ", '', $string);
   $string = str_replace(" ", '', $string);
   $string = str_replace(",", "", $string);
   $string = trim($string);
   
   return $this->num_to_str($string);
   
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
   
   
   function delimited_str_sample($string, $delimiter, $position, $charset='utf-8') {
      
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
   
   
   function auto_correct_str($string, $mode) {
   
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
   
   
   function obfuscate_str($str, $show=1) {
      
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
   function num_to_str($val) {
   
   // Trim any whitespace off the ends
   $val = trim($val);
   
   
      // Covert scientific notation to a normal value / string
       
      // MUST ALLOW MAXIMUM OF 9 DECIMALS, TO COUNT WATCH-ONLY ASSETS
      // (ANYTHING OVER 9 DECIMALS SHOULD BE AVOIDED FOR UX)
      $detect_dec = (string)$val;
      if ( preg_match('~\.(\d+)E([+-])?(\d+)~', $detect_dec, $matches) ) {
      $decimals = $matches[2] === '-' ? strlen($matches[1]) + $matches[3] : 0;
      }
      else {
      $decimals = mb_strpos( strrev($detect_dec) , '.', 0, 'utf-8');
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
   
   
   function num_pretty($value_to_pretty, $num_dec, $small_unlimited=false) {
   
   global $ocpt_conf;
   
   // Pretty number formatting, while maintaining decimals
   
   
   // Strip formatting, convert from scientific format, and remove leading / trailing zeros
   $raw_val_to_pretty = $this->rem_num_format($value_to_pretty);
   
   // Do any rounding that may be needed now (skip WATCH-ONLY 9 decimal values)
   if ( $this->num_to_str($raw_val_to_pretty) > 0.00000000 && $small_unlimited != TRUE ) { 
   $raw_val_to_pretty = number_format($raw_val_to_pretty, $num_dec, '.', '');
   }
   
   // AFTER ROUNDING, RE-PROCESS removing leading / trailing zeros
   $raw_val_to_pretty = $this->num_to_str($raw_val_to_pretty);
          
          
          // Pretty things up...
          
          
            if ( preg_match("/\./", $raw_val_to_pretty) ) {
            $value_no_decimal = preg_replace("/\.(.*)/", "", $raw_val_to_pretty);
            $decimal_amount = preg_replace("/(.*)\./", "", $raw_val_to_pretty);
            $check_decimal_amount = '0.' . $decimal_amount;
            }
            else {
            $value_no_decimal = $raw_val_to_pretty;
            $decimal_amount = null;
            $check_decimal_amount = null;
            }
            
            
          // Limit $decimal_amount to $num_dec (unless it's a watch-only asset)
          if ( $raw_val_to_pretty != 0.000000001 ) {
          $decimal_amount = ( iconv_strlen($decimal_amount, 'utf-8') > $num_dec ? substr($decimal_amount, 0, $num_dec) : $decimal_amount );
          }
          
            
            // Show EVEN IF LOW VALUE IS OFF THE MAP, just for UX purposes (tracking token price only, etc)
            if ( $this->num_to_str($raw_val_to_pretty) > 0.00000000 && $small_unlimited == true ) {  
               
               if ( $num_dec == 2 ) {
               $value_to_pretty = number_format($raw_val_to_pretty, 2, '.', ',');
               }
               else {
               // $value_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
               $value_to_pretty = number_format($value_no_decimal, 0, '.', ',') . ( $this->num_to_str($check_decimal_amount) > 0.00000000 ? '.' . $decimal_amount : '' );
               }
            
            }
            // Show low value only with $decimal_amount minimum
            elseif ( $this->num_to_str($raw_val_to_pretty) >= 0.00000001 && $small_unlimited == false ) {  
               
               if ( $num_dec == 2 ) {
               $value_to_pretty = number_format($raw_val_to_pretty, 2, '.', ',');
               }
               else {
               // $value_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
               $value_to_pretty = number_format($value_no_decimal, 0, '.', ',') . ( $this->num_to_str($check_decimal_amount) > 0.00000000 ? '.' . $decimal_amount : '' );
               }
            
            }
            else {
            $value_to_pretty = 0;
            }
            
            
          
   return $value_to_pretty;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   

}


?>
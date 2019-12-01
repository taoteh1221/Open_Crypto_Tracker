<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Updating trading notes is separate from updating all other data
if ( $_POST['update_notes'] == 1 && trim($_POST['notes_reminders']) != '' && $_COOKIE['notes_reminders'] ) {
store_cookie_contents("notes_reminders", $_POST['notes_reminders'], mktime()+31536000);
header("Location: " . start_page($_GET['start_page']));
exit;
}
elseif ( $_POST['update_notes'] == 1 && trim($_POST['notes_reminders']) == '' && $_COOKIE['notes_reminders'] ) {
store_cookie_contents("notes_reminders", " ", mktime()+31536000); // Initialized with some whitespace when blank
header("Location: " . start_page($_GET['start_page']));
exit;
}


//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////


// If cookies are enabled or not, update accordingly
if ( $_POST['submit_check'] == 1 && $_POST['use_cookies'] != 1 ) {
delete_all_cookies(); // Delete any existing cookies, if cookies have been disabled
}
elseif ( $_POST['submit_check'] == 1 && $_POST['use_cookies'] == 1 || $run_csv_import == 1 && $_COOKIE['coin_amounts'] != '' ) {
 
 
 // UI form POST data
 if ( $_POST['submit_check'] == 1 ) {
  
  
  	// Parse portfolio values
   foreach ( $_POST as $key => $value ) {
  
  
      if ( preg_match("/_amount/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_coin_values .= $key.'-'. remove_number_format($_POST[$key]) . '#';
         }
      
      }
   
  
      if ( preg_match("/_pairing/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_pairing_values .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_market/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_market_values .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_paid/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_paid_values .= $key.'-'. remove_number_format($_POST[$key]) . '#';
         }
      
      }
   
  
      if ( preg_match("/_leverage/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_leverage_values .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_margintype/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_margintype_values .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
   }
  
 
 
 	// UI settings only (not included in any portfolio importing)
   // Cookies expire in 1 year (31536000 seconds)
   if ( $_POST['theme_selected'] != NULL ) {
   store_cookie_contents("theme_selected", $_POST['theme_selected'], mktime()+31536000);
   }
  
   if ( $_POST['sort_by'] != NULL ) {
   store_cookie_contents("sort_by", $_POST['sort_by'], mktime()+31536000);
   }
   else {
   store_cookie_contents("sort_by", "", time()-3600);  // Delete any existing cookie
   unset($_COOKIE['sort_by']);  // Delete any existing cookie
   }
  
   if ( $_POST['use_alert_percent'] != NULL ) {
   store_cookie_contents("alert_percent", $_POST['use_alert_percent'], mktime()+31536000);
   }
   else {
   store_cookie_contents("alert_percent", "", time()-3600);  // Delete any existing cookie
   unset($_COOKIE['alert_percent']);  // Delete any existing cookie
   }
  
  
  
  
  }
  // File import form POST data
  elseif ( $_POST['csv_check'] == 1 ) {
  
	 	foreach( $csv_file_array as $key => $value ) {
	        		
	    // Must be compatible with UI form cookie storage method: $key.'-'. $_POST[$key] . '#'
	    
	     $value[5] = ( whole_int($value[5]) != false ? $value[5] : 1 ); // If market ID input is corrupt, default to 1
	     $value[3] = ( whole_int($value[3]) != false ? $value[3] : 0 ); // If leverage amount input is corrupt, default to 0
	    
	     $compat_key = strtolower($key);
	     	
	     $set_pairing_values .= $compat_key . '_pairing-' . strtolower($value[6]) . '#';
	     
		  $set_market_values .= $compat_key . '_market-' . $value[5] . '#';
		  
	     $set_coin_values .= $compat_key . '_amount-' . remove_number_format($value[1]) . '#';
	     
	     $set_paid_values .= $compat_key . '_paid-' . remove_number_format($value[2]) . '#';
	     
	     $set_leverage_values .= $compat_key . '_leverage-' . $value[3] . '#';
	     
	     $set_margintype_values .= $compat_key . '_margintype-' . strtolower($value[4]) . '#';
	     
	     
	   }
	        		
  
  }



// Store all cookies and redirect to app URL, to clear any POST data from any future page refreshing
$set_coin_values = ( $set_coin_values != NULL ? $set_coin_values : ' ' ); // Initialized with some whitespace when blank
store_all_cookies($set_coin_values, $set_pairing_values, $set_market_values, $set_paid_values, $set_leverage_values, $set_margintype_values);
header("Location: " . start_page($_GET['start_page'])); // Preserve any start page data
exit;
 	
 
}
  
 
 ?>
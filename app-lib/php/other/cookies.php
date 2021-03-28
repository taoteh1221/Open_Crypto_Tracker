<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// Updating trading notes is separate from updating all other data
if ( $_POST['update_notes'] == 1 && trim($_POST['notes']) != '' && $_COOKIE['notes'] ) {
$pt_gen->store_cookie("notes", $_POST['notes'], mktime()+31536000);
header("Location: " . start_page($_GET['start_page']));
exit;
}
elseif ( $_POST['update_notes'] == 1 && trim($_POST['notes']) == '' && $_COOKIE['notes'] ) {
$pt_gen->store_cookie("notes", " ", mktime()+31536000); // Initialized with some whitespace when blank
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
      
      $_POST[$key] = $pt_vars->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_coin_values .= $key.'-'. $pt_vars->rem_num_format($_POST[$key]) . '#';
         }
      
      }
   
  
      if ( preg_match("/_pairing/i", $key) ) {
      
      $_POST[$key] = $pt_vars->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_pairing_values .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_market/i", $key) ) {
      
      $_POST[$key] = $pt_vars->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_market_values .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_paid/i", $key) ) {
      
      $_POST[$key] = $pt_vars->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_paid_values .= $key.'-'. $pt_vars->rem_num_format($_POST[$key]) . '#';
         }
      
      }
   
  
      if ( preg_match("/_leverage/i", $key) ) {
      
      $_POST[$key] = $pt_vars->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_leverage_values .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_margintype/i", $key) ) {
      
      $_POST[$key] = $pt_vars->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_margintype_values .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
   }
  
  
  }
  // File import form POST data
  elseif ( $_POST['csv_check'] == 1 ) {
  
	 	foreach( $csv_file_array as $key => $value ) {
	        		
	    // Must be compatible with UI form cookie storage method: $key.'-'. $_POST[$key] . '#'
	    
	    // We already validated / auto-corrected $csv_file_array
	    
	     $compat_key = strtolower($key);
		  
	     $set_coin_values .= $compat_key . '_amount-' . $value[1] . '#';
	     
	     $set_paid_values .= $compat_key . '_paid-' . $value[2] . '#';
	     
	     $set_leverage_values .= $compat_key . '_leverage-' . $value[3] . '#';
	     
	     $set_margintype_values .= $compat_key . '_margintype-' . $value[4] . '#';
	     
		  $set_market_values .= $compat_key . '_market-' . $value[5] . '#';
	     	
	     $set_pairing_values .= $compat_key . '_pairing-' . $value[6] . '#';
	     
	     
	   }
	        		
  
  }



// Store all cookies and redirect to app URL, to clear any POST data from any future page refreshing
$set_coin_values = ( $set_coin_values != NULL ? $set_coin_values : ' ' ); // Initialized with some whitespace when blank
$pt_gen->update_cookies($set_coin_values, $set_pairing_values, $set_market_values, $set_paid_values, $set_leverage_values, $set_margintype_values);
header("Location: " . start_page($_GET['start_page'])); // Preserve any start page data
exit;
 	
 
}
  
 
 ?>
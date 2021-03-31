<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// Updating trading notes is separate from updating all other data
if ( $_POST['update_notes'] == 1 && trim($_POST['notes']) != '' && $_COOKIE['notes'] ) {
$ocpt_gen->store_cookie("notes", $_POST['notes'], mktime()+31536000);
header("Location: " . start_page($_GET['start_page']));
exit;
}
elseif ( $_POST['update_notes'] == 1 && trim($_POST['notes']) == '' && $_COOKIE['notes'] ) {
$ocpt_gen->store_cookie("notes", " ", mktime()+31536000); // Initialized with some whitespace when blank
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
      
      $_POST[$key] = $ocpt_var->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_coin_vals .= $key.'-'. $ocpt_var->rem_num_format($_POST[$key]) . '#';
         }
      
      }
   
  
      if ( preg_match("/_pairing/i", $key) ) {
      
      $_POST[$key] = $ocpt_var->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_pairing_vals .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_market/i", $key) ) {
      
      $_POST[$key] = $ocpt_var->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_market_vals .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_paid/i", $key) ) {
      
      $_POST[$key] = $ocpt_var->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_paid_vals .= $key.'-'. $ocpt_var->rem_num_format($_POST[$key]) . '#';
         }
      
      }
   
  
      if ( preg_match("/_leverage/i", $key) ) {
      
      $_POST[$key] = $ocpt_var->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_leverage_vals .= $key.'-'. $_POST[$key] . '#';
         }
      
      }
   
  
      if ( preg_match("/_margintype/i", $key) ) {
      
      $_POST[$key] = $ocpt_var->strip_formatting($value);
      
         if ( isset($_POST[$key]) ) {
            $set_margintype_vals .= $key.'-'. $_POST[$key] . '#';
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
		  
	     $set_coin_vals .= $compat_key . '_amount-' . $value[1] . '#';
	     
	     $set_paid_vals .= $compat_key . '_paid-' . $value[2] . '#';
	     
	     $set_leverage_vals .= $compat_key . '_leverage-' . $value[3] . '#';
	     
	     $set_margintype_vals .= $compat_key . '_margintype-' . $value[4] . '#';
	     
		  $set_market_vals .= $compat_key . '_market-' . $value[5] . '#';
	     	
	     $set_pairing_vals .= $compat_key . '_pairing-' . $value[6] . '#';
	     
	     
	   }
	        		
  
  }



// Store all cookies and redirect to app URL, to clear any POST data from any future page refreshing
$set_coin_vals = ( $set_coin_vals != NULL ? $set_coin_vals : ' ' ); // Initialized with some whitespace when blank
$ocpt_gen->update_cookies($set_coin_vals, $set_pairing_vals, $set_market_vals, $set_paid_vals, $set_leverage_vals, $set_margintype_vals);
header("Location: " . start_page($_GET['start_page'])); // Preserve any start page data
exit;
 	
 
}
  
 
 ?>
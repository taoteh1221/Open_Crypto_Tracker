<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Removing 1 and 2 minute auto-reload options (to reduce request denied API server responses)
if ( $_COOKIE['coin_reload'] > '0' && $_COOKIE['coin_reload'] < '300' ) {

unset($_COOKIE['coin_reload']);  // Delete any existing cookie
setcookie ("coin_reload", "", time()-3600);  // Delete any existing cookie
 
$_COOKIE['coin_reload'] = '300';

// Cookie expires in 1 year (31536000 seconds)
setcookie("coin_reload", $_COOKIE['coin_reload'], mktime()+31536000);

}

if ( $_POST['update_notes'] == 1 && trim($_POST['notes_reminders']) != '' && $_COOKIE['notes_reminders'] ) {
setcookie("notes_reminders", $_POST['notes_reminders'], mktime()+31536000);

header("Location: " . $_SERVER['PHP_SELF']);
exit;
}
elseif ( $_POST['update_notes'] == 1 && trim($_POST['notes_reminders']) == '' && $_COOKIE['notes_reminders'] ) {
setcookie("notes_reminders", " ", mktime()+31536000); // Initialized with some whitespace when blank

header("Location: " . $_SERVER['PHP_SELF']);
exit;
}

//////////////////////////////////////////////////////////////

if ( $_POST['submit_check'] == 1 ) {
 
 
 if (is_array($_POST) || is_object($_POST)) {
  
  
   foreach ( $_POST as $key => $value ) {
  
      if ( preg_match("/_amount/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( $_POST['use_cookies'] == 1 && isset($_POST[$key]) ) {
          
          
            $set_coin_values .= $key.'-'. $_POST[$key] . '#';
          
          
         }
      
      }
   
  
      if ( preg_match("/_pairing/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( $_POST['use_cookies'] == 1 && isset($_POST[$key]) ) {
          
          
            $set_pairing_values .= $key.'-'. $_POST[$key] . '#';
          
          
         }
      
      }
   
  
      if ( preg_match("/_market/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( $_POST['use_cookies'] == 1 && isset($_POST[$key]) ) {
          
          
            $set_market_values .= $key.'-'. $_POST[$key] . '#';
          
          
         }
      
      }
   
  
   }
   
  }

 
 
  if ( $_POST['use_cookies'] == 1 && $_POST['sort_by'] != '' ) {
  
           // Cookie expires in 1 year (31536000 seconds)
           
           setcookie("sort_by", $_POST['sort_by'], mktime()+31536000);
           
  }
  else {
  unset($_COOKIE['sort_by']);  // Delete any existing cookie
  setcookie ("sort_by", "", time()-3600);  // Delete any existing cookie
  }
 
  if ( $_POST['use_cookies'] == 1 && $_POST['use_alert_percent'] != '' ) {
  
           // Cookie expires in 1 year (31536000 seconds)
           
           setcookie("alert_percent", $_POST['use_alert_percent'], mktime()+31536000);
           
  }
  else {
  unset($_COOKIE['alert_percent']);  // Delete any existing cookie
  setcookie ("alert_percent", "", time()-3600);  // Delete any existing cookie
  }
 
 
 
  if ( $_POST['use_cookies'] == 1 ) {
   
           // Cookie expires in 1 year (31536000 seconds)
           
           
           if ( $_POST['use_notes'] == 1 && !$_COOKIE['notes_reminders'] ) {
           setcookie("notes_reminders", " ", mktime()+31536000); // Initialized with some whitespace when blank
           }
           elseif ( $_POST['use_notes'] != 1 ) {
           unset($_COOKIE['notes_reminders']);  // Delete any existing cookies
           setcookie ("notes_reminders", "", time()-3600);  // Delete any existing cookies
           }
           
           
           setcookie("coin_amounts", $set_coin_values, mktime()+31536000);
           setcookie("coin_pairings", $set_pairing_values, mktime()+31536000);
           setcookie("coin_markets", $set_market_values, mktime()+31536000);
           
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }
  else {
  	
  // Delete any existing cookies
   
  unset($_COOKIE['notes_reminders']);
  unset($_COOKIE['coin_amounts']); 
  unset($_COOKIE['coin_pairings']); 
  unset($_COOKIE['coin_markets']); 
  unset($_COOKIE['coin_reload']);  
  unset($_COOKIE['alert_percent']);  
  
  setcookie ("notes_reminders", "", time()-3600);  
  setcookie ("coin_amounts", "", time()-3600);  
  setcookie ("coin_pairings", "", time()-3600);  
  setcookie ("coin_markets", "", time()-3600);  
  setcookie ("coin_reload", "", time()-3600);  
  setcookie ("alert_percent", "", time()-3600);  
 
  }
  
  
 
 
}
 
 ?>
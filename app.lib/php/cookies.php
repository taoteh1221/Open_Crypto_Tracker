<?php

if ( $_POST['submit_check'] == 1 ) {
 
 
 if (is_array($_POST) || is_object($_POST)) {
  
  
   foreach ( $_POST as $key => $value ) {
  
      if ( preg_match("/_amount/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( $_POST['use_cookies'] == 1 && isset($_POST[$key]) ) {
          
          
            $set_coin_values .= $key.'-'. $_POST[$key] . '#';
          
          
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
  
  if ( $_POST['use_cookies'] == 1 ) {
   
           // Cookie expires in 1 year (31536000 seconds)
           
           setcookie("coin_amounts", $set_coin_values, mktime()+31536000);
           setcookie("coin_markets", $set_market_values, mktime()+31536000);
           
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }
  else {
   
  unset($_COOKIE['coin_amounts']);  // Delete any existing cookies
  unset($_COOKIE['coin_markets']);  // Delete any existing cookies
  unset($_COOKIE['coin_reload']);  // Delete any existing cookies
  
  setcookie ("coin_amounts", "", time()-3600);  // Delete any existing cookies
  setcookie ("coin_markets", "", time()-3600);  // Delete any existing cookies
  setcookie ("coin_reload", "", time()-3600);  // Delete any existing cookies
 
  }
  
  
  //var_dump($set_coin_values);
  
 
}
 
 ?>
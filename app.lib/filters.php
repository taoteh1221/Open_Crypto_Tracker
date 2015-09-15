<?php

if ( $_POST['submit_check'] == 1 ) {
 
 
 $cookie_domain_info = ereg_replace("www.", "", $SERVER_NAME);
 
 setcookie ("coin_amounts", "", time()-31536000, "/", $cookie_domain_info, 0);  // Delete cookie
 setcookie ("coin_markets", "", time()-31536000, "/", $cookie_domain_info, 0);  // Delete cookie
 
 if (is_array($_POST) || is_object($_POST)) {
  
   foreach ( $_POST as $key => $value ) {
  
      if ( preg_match("/_amount/i", $key) ) {
      
      $_POST[$key] = strip_price_formatting($value);
      
         if ( $_POST['use_cookies'] == 1 && $_POST[$key] > 0.00000000 ) {
          
          
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
   
           $cookie_domain_info = ereg_replace("www.", "", $SERVER_NAME);
           // Cookie expires in 1 year (31536000 seconds)
           
           setcookie ("coin_amounts", $set_coin_values, mktime()+31536000, "/", $cookie_domain_info, 0);
           setcookie ("coin_markets", $set_market_values, mktime()+31536000, "/", $cookie_domain_info, 0);
           
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
  }
  
  
  //var_dump($set_coin_values);
  
 
}
 
 ?>
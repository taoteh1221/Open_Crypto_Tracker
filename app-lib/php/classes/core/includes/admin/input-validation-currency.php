<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

             
$update_config_error_seperator = '<br /> ';
        
$coingecko_pairings_search = array_map( "trim", explode(',', $_POST['currency']['coingecko_pairings_search']) );

$kraken_pairings_search = array_map( "trim", explode(',', $_POST['currency']['kraken_pairings_search']) );

$jupiter_ag_pairings_search = array_map( "trim", explode(',', $_POST['currency']['jupiter_ag_pairings_search']) );

$upbit_pairings_search = array_map( "trim", explode(',', $_POST['currency']['upbit_pairings_search']) );

$additional_pairings_search = array_map( "trim", explode(',', $_POST['currency']['additional_pairings_search']) );


foreach ( $_POST['currency']['token_presales_usd'] as $key => $val ) {

// Auto-correct
$val = $ct['var']->auto_correct_str($val, 'lower'); 

$_POST['currency']['token_presales_usd'][$key] = $val;

// Check array
$val_config = array_map( "trim", explode("=", $val) ); 
             
             
     if ( sizeof($_POST['currency']['token_presales_usd']) == 1 && trim($val) == '' ) {
     // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
     }
     elseif ( sizeof($val_config) < 2 ) {
     $ct['update_config_error'] .= '<br />"token_presales_usd" formatting is NOT valid (MUST be: TOKEN_NAME = PRESALE_PRICE): "' . $val;
     }
     else {
     
          if ( !ctype_alnum($val_config[0]) ) {
          $ct['update_config_error'] .= '<br />"token_presales_usd" Coin NAME seems INVALID: "'.$val_config[0].'" ('.$val.')';
          }
     
          if ( !is_numeric($val_config[1]) ) {
          $ct['update_config_error'] .= '<br />"token_presales_usd" Coin VALUE seems INVALID: "'.$val_config[1].'" ('.$val.')';
          }
     
     }

     	 
}
  
  
// Make sure CoinGecko market pairings is set
if ( isset($_POST['currency']['coingecko_pairings_search']) && trim($_POST['currency']['coingecko_pairings_search']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"CoinGecko.com Pairings Search" MUST be filled in';
}
else {

     foreach ( $coingecko_pairings_search as $pair ) {
     
         if ( !ctype_alpha($pair) ) {
         $ct['update_config_error'] .= $update_config_error_seperator . '"CoinGecko.com Pairings Search" MUST be alphabetic letters only ("'.$pair.'" is invalid)';
         }
     
     }
     
}
  
  
// Make sure Kraken market pairings is set
if ( isset($_POST['currency']['kraken_pairings_search']) && trim($_POST['currency']['kraken_pairings_search']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Kraken Pairings Search" MUST be filled in';
}
else {

     foreach ( $kraken_pairings_search as $pair ) {
     
         if ( !ctype_alpha($pair) ) {
         $ct['update_config_error'] .= $update_config_error_seperator . '"Kraken Pairings Search" MUST be alphabetic letters only ("'.$pair.'" is invalid)';
         }
     
     }
     
}
  
  
// Make sure jupiter_ag market pairings is set
if ( isset($_POST['currency']['jupiter_ag_pairings_search']) && trim($_POST['currency']['jupiter_ag_pairings_search']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Jupiter Aggregator Pairings Search" MUST be filled in';
}
else {

     foreach ( $jupiter_ag_pairings_search as $pair ) {
     
         if ( !ctype_alpha($pair) ) {
         $ct['update_config_error'] .= $update_config_error_seperator . '"Jupiter Aggregator Pairings Search" MUST be alphabetic letters only ("'.$pair.'" is invalid)';
         }
     
     }
     
}
  
  
// Make sure UpBit market pairings is set
if ( isset($_POST['currency']['upbit_pairings_search']) && trim($_POST['currency']['upbit_pairings_search']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"UpBit Pairings Search" MUST be filled in';
}
else {

     foreach ( $upbit_pairings_search as $pair ) {
     
         if ( !ctype_alpha($pair) ) {
         $ct['update_config_error'] .= $update_config_error_seperator . '"UpBit Pairings Search" MUST be alphabetic letters only ("'.$pair.'" is invalid)';
         }
     
     }
     
}
  
  
// Make sure Additional market pairings is set
if ( isset($_POST['currency']['additional_pairings_search']) && trim($_POST['currency']['additional_pairings_search']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Additional Pairings Search" MUST be filled in';
}
else {

     foreach ( $additional_pairings_search as $pair ) {
     
         if ( !ctype_alpha($pair) ) {
         $ct['update_config_error'] .= $update_config_error_seperator . '"Additional Pairings Search" MUST be alphabetic letters only ("'.$pair.'" is invalid)';
         }
     
     }
     
}
        

foreach ( $_POST['currency']['bitcoin_currency_markets'] as $key => $val ) {

// Auto-correct
$val = $ct['var']->auto_correct_str($val, 'lower'); 

$_POST['currency']['bitcoin_currency_markets'][$key] = $val;

// Check array
$val_config = array_map( "trim", explode("=", $val) );
             
             
     if ( sizeof($_POST['currency']['bitcoin_currency_markets']) == 1 && trim($val) == '' ) {
     // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
     }
     elseif ( sizeof($val_config) < 2 ) {
     $ct['update_config_error'] .= '<br />"bitcoin_currency_markets" formatting is NOT valid (MUST be: TICKER = SYMBOL): "' . $val;
     }
     else {
     
          if ( !ctype_alnum($val_config[0]) ) {
          $ct['update_config_error'] .= '<br />"bitcoin_currency_markets" Ticker seems INVALID: "'.$val_config[0].'" ('.$val.')';
          }
     
          if ( !isset($val_config[1]) || $val_config[1] == '' ) {
          $ct['update_config_error'] .= '<br />"bitcoin_currency_markets" No ticker SYMBOL detected: "'.$val_config[1].'" ('.$val.')';
          }

     }
     
     	 
}
        

foreach ( $_POST['currency']['bitcoin_preferred_currency_markets'] as $key => $val ) {

// Auto-correct
$val = $ct['var']->auto_correct_str($val, 'lower'); 

$_POST['currency']['bitcoin_preferred_currency_markets'][$key] = $val;

// Check array
$val_config = array_map( "trim", explode("=", $val) );
             
             
     if ( sizeof($_POST['currency']['bitcoin_preferred_currency_markets']) == 1 && trim($val) == '' ) {
     // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
     }
     elseif ( sizeof($val_config) < 2 ) {
     $ct['update_config_error'] .= '<br />"bitcoin_preferred_currency_markets" formatting is NOT valid (MUST be: TICKER = EXCHANGE): "' . $val;
     }
     else {
     
          if ( !ctype_alnum($val_config[0]) ) {
          $ct['update_config_error'] .= '<br />"bitcoin_preferred_currency_markets" Ticker seems INVALID: "'.$val_config[0].'" ('.$val.')';
          }
     
          if ( !isset($val_config[1]) || $val_config[1] == '' ) {
          $ct['update_config_error'] .= '<br />"bitcoin_preferred_currency_markets" No ticker EXCHANGE detected: "'.$val_config[1].'" ('.$val.')';
          }

     }
     
     	 
}
        

foreach ( $_POST['currency']['crypto_pair'] as $key => $val ) {

// Auto-correct
$val = $ct['var']->auto_correct_str($val, 'lower'); 

$_POST['currency']['crypto_pair'][$key] = $val;

// Check array
$val_config = array_map( "trim", explode("=", $val) );
             
             
     if ( sizeof($_POST['currency']['crypto_pair']) == 1 && trim($val) == '' ) {
     // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
     }
     elseif ( sizeof($val_config) < 2 ) {
     $ct['update_config_error'] .= '<br />"crypto_pair" formatting is NOT valid (MUST be: TICKER = SYMBOL): "' . $val;
     }
     else {
     
          if ( !ctype_alnum($val_config[0]) ) {
          $ct['update_config_error'] .= '<br />"crypto_pair" Ticker seems INVALID: "'.$val_config[0].'" ('.$val.')';
          }
     
          if ( !isset($val_config[1]) || $val_config[1] == '' ) {
          $ct['update_config_error'] .= '<br />"crypto_pair" No ticker SYMBOL detected: "'.$val_config[1].'" ('.$val.')';
          }

     }
     
     	 
}
        

foreach ( $_POST['currency']['crypto_pair_preferred_markets'] as $key => $val ) {

// Auto-correct
$val = $ct['var']->auto_correct_str($val, 'lower'); 

$_POST['currency']['crypto_pair_preferred_markets'][$key] = $val;

// Check array
$val_config = array_map( "trim", explode("=", $val) );
             
             
     if ( sizeof($_POST['currency']['crypto_pair_preferred_markets']) == 1 && trim($val) == '' ) {
     // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
     }
     elseif ( sizeof($val_config) < 2 ) {
     $ct['update_config_error'] .= '<br />"crypto_pair_preferred_markets" formatting is NOT valid (MUST be: TICKER = EXCHANGE): "' . $val;
     }
     else {
     
          if ( !ctype_alnum($val_config[0]) ) {
          $ct['update_config_error'] .= '<br />"crypto_pair_preferred_markets" Ticker seems INVALID: "'.$val_config[0].'" ('.$val.')';
          }
     
          if ( !isset($val_config[1]) || $val_config[1] == '' ) {
          $ct['update_config_error'] .= '<br />"crypto_pair_preferred_markets" No ticker EXCHANGE detected: "'.$val_config[1].'" ('.$val.')';
          }

     }
     
     	 
}
        
  
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
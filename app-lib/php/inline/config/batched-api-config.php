<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



//////////////////////////////////////////////////////////////////
// BATCHED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// MAKE SURE **ANYTHING** RUN IN HERE --IS ENGINEERED TO-- BE CLEANLY RELOADED!!


// COINGECKO - START

// RESET, since we reload this logic on config resets / user updates

$check_pairs = array();
$check_assets = array();

$ct['coingecko_pairs'] = null;
$ct['coingecko_assets'] = null;


// Active coingecko asset configs
foreach ( $ct['conf']['assets'] as $mrkts_conf ) {
                  
    foreach ( $mrkts_conf['pair'] as $pair_conf ) {
                  
         foreach ( $pair_conf as $exchange_key => $exchange_val ) {
            
              // EXCLUDE 'coingecko_terminal' markets (as they are a completely different format / API endpoint)
              if ( stristr($exchange_key, 'coingecko_') != false && $exchange_key != 'coingecko_terminal' ) {
            		        
              $paired_conf = explode('_', strtolower($exchange_key) );
              $paired_conf = $paired_conf[1];
      
            	    if ( !in_array($paired_conf, $check_pairs) ) {
            	    $ct['coingecko_pairs'] .= $paired_conf . ',';
            	    $check_pairs[] = $paired_conf;
                   }
      
            	    if ( !in_array($exchange_val, $check_assets) ) {
            	    $ct['coingecko_assets'] .= $exchange_val . ',';
            	    $check_assets[] = $exchange_val;
            	    }
            		        
              }
            	            
         }
        	         
    }
                    
}


// Coingecko pairing search support, in currency support admin config
// (so we can IMMEDIATELY check market data for valid numbers, during 'add market' searches)
$coingecko_pairings = $ct['api']->coingecko_currencies();
            
foreach ( $coingecko_pairings as $pairing ) {
     
$pairing = strtolower($pairing);

     if ( !in_array($pairing, $check_pairs) ) {
     $ct['coingecko_pairs'] .= $pairing . ',';
     $check_pairs[] = $pairing;
     }
                                     
}


$ct['coingecko_pairs'] = substr($ct['coingecko_pairs'], 0, -1); // NEEDED NO MATTER WHAT
// WE DONT WANT ASSETS FROM THE APP CONFIG, IF WE ARE IN ADMIN RUNNING 'ADD ASSET MARKET' SEARCH
$ct['coingecko_assets'] = ( $ct['ticker_markets_search'] ? null : substr($ct['coingecko_assets'], 0, -1) );
            
// COINGECKO - END


$ct['jupiter_ag_pairs'] = array(); // RESET, since we reload this logic on config resets / user updates

$ct['upbit_batched_markets'] = null; // RESET, since we reload this logic on config resets / user updates


// OTHER THAN COINGECKO PAIRINGS ABOVE, we only pre-populate BATCHED asset price API calls from our asset configs IF WE ARE NOT IN ADMIN
// RUNNING 'ADD ASSET MARKET' SEARCH, as we don't want to included assets WE ALREADY HAVE IN THE CONFIG when searching for new asset markets
if ( !$ct['ticker_markets_search'] ) {


// JUP AG - START

     foreach ( $ct['conf']['assets'] as $markets ) {
                   
         foreach ( $markets['pair'] as $exchange_pairs ) {
              	            
              if ( isset($exchange_pairs['jupiter_ag']) && $exchange_pairs['jupiter_ag'] != '' ) { // In case user messes up Admin Config, this helps
              		        
              $jup_market = explode('/', $exchange_pairs['jupiter_ag']);
                   
              $ct['jupiter_ag_pairs'][ $jup_market[1] ] .= $jup_market[0] . ',';
              		        
              }
              	            
         }
                     
     }
     
                 
     foreach ( $ct['jupiter_ag_pairs'] as $key => $val ) {
     $ct['jupiter_ag_pairs'][$key] = substr($val, 0, -1);
     }
            
// JUP AG - END


// UPBIT - START

     foreach ( $ct['conf']['assets'] as $markets ) {
                   
         foreach ( $markets['pair'] as $exchange_pairs ) {
         	            
         	    if ( isset($exchange_pairs['upbit']) && $exchange_pairs['upbit'] != '' ) { // In case user messes up Admin Config, this helps
         	    $ct['upbit_batched_markets'] .= $exchange_pairs['upbit'] . ',';
         	    }
         	            
         }
                     
     }
    
$ct['upbit_batched_markets'] = substr($ct['upbit_batched_markets'], 0, -1);
            
// UPBIT - END


} // END !$ct['ticker_markets_search']


//////////////////////////////////////////////////////////////////
// END BATCHED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



//////////////////////////////////////////////////////////////////
// BATCHED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// MAKE SURE **ANYTHING** RUN IN HERE --IS ENGINEERED TO-- BE CLEANLY RELOADED!!


// RESET, since we reload this logic on config resets / user updates

$ct['jupiter_ag_pairs'] = array(); 

$coingecko_check_pairs = array();
$coingecko_check_assets = array();

$ct['coingecko_pairs'] = null;
$ct['coingecko_assets'] = null;

$ct['upbit_batched_markets'] = null;


// We only need ALL available coingecko pairings during ticker market searches
if ( $ct['ticker_markets_search'] ) {
     
// WE DONT WANT ASSETS FROM THE APP CONFIG, IF WE ARE IN ADMIN RUNNING 'ADD ASSET MARKET' SEARCH
$ct['coingecko_assets'] = null;

            
     foreach ( $ct['coingecko_currencies'] as $pairing ) {
          
     $pairing = strtolower($pairing);
     
          if ( !in_array($pairing, $coingecko_check_pairs) ) {
          $ct['coingecko_pairs'] .= $pairing . ',';
          $coingecko_check_pairs[] = $pairing;
          }
                                          
     }
     

}
// Active coingecko / jupiter / upbit  asset configs
else {
     
     
     foreach ( $ct['conf']['assets'] as $markets ) {
              
                       
         foreach ( $markets['pair'] as $exchange_pairs ) {
     
              
              // In case user messes up Admin Config, this helps
              if ( isset($exchange_pairs['jupiter_ag']) && $exchange_pairs['jupiter_ag'] != '' ) { 
                   		        
              $jup_market = explode('/', $exchange_pairs['jupiter_ag']);
                        
              $ct['jupiter_ag_pairs'][ $jup_market[1] ] .= $jup_market[0] . ',';
                   		        
              }
              // In case user messes up Admin Config, this helps
              elseif ( isset($exchange_pairs['upbit']) && $exchange_pairs['upbit'] != '' ) {
              $ct['upbit_batched_markets'] .= $exchange_pairs['upbit'] . ',';
              }
              	            
                       
              foreach ( $exchange_pairs as $exchange_key => $exchange_val ) {
                 
                   // EXCLUDE 'coingecko_terminal' markets (as they are a completely different format / API endpoint)
                   if ( stristr($exchange_key, 'coingecko_') != false && $exchange_key != 'coingecko_terminal' ) {
                 		        
                   $paired_conf = explode('_', strtolower($exchange_key) );
                   $paired_conf = $paired_conf[1];
           
                 	    if ( !in_array($paired_conf, $coingecko_check_pairs) ) {
                 	    $ct['coingecko_pairs'] .= $paired_conf . ',';
                 	    $coingecko_check_pairs[] = $paired_conf;
                        }
           
                 	    if ( !in_array($exchange_val, $coingecko_check_assets) ) {
                 	    $ct['coingecko_assets'] .= $exchange_val . ',';
                 	    $coingecko_check_assets[] = $exchange_val;
                 	    }
                 		        
                   }
                 	            
              }
             	         
         }
                         
     }
     
                 
     foreach ( $ct['jupiter_ag_pairs'] as $key => $val ) {
     $ct['jupiter_ag_pairs'][$key] = substr($val, 0, -1);
     }

    
$ct['upbit_batched_markets'] = substr($ct['upbit_batched_markets'], 0, -1);

$ct['coingecko_assets'] = substr($ct['coingecko_assets'], 0, -1);

}


// NEEDED NO MATTER WHAT
$ct['coingecko_pairs'] = substr($ct['coingecko_pairs'], 0, -1); 


//////////////////////////////////////////////////////////////////
// END BATCHED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
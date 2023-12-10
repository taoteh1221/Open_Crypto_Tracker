<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



//////////////////////////////////////////////////////////////////
// BATCHED MARKET SETTINGS
//////////////////////////////////////////////////////////////////


// KRAKEN - START

foreach ( $ct['conf']['assets'] as $markets ) {
              
    foreach ( $markets['pair'] as $exchange_pairs ) {
    	            
    	    if ( isset($exchange_pairs['kraken']) && $exchange_pairs['kraken'] != '' ) { // In case user messes up Admin Config, this helps
    	    $ct['kraken_pairs'] .= $exchange_pairs['kraken'] . ',';
    	    }
    	            
    }
                
}
    
$ct['kraken_pairs'] = substr($ct['kraken_pairs'], 0, -1);

// KRAKEN - END


// JUP AG - START

foreach ( $ct['conf']['assets'] as $markets ) {
              
    foreach ( $markets['pair'] as $exchange_pairs ) {
         	            
         if ( isset($exchange_pairs['jupiter_ag']) && $exchange_pairs['jupiter_ag'] != '' ) { // In case user messes up Admin Config, this helps
         		        
         $jup_pairs = explode('/', $exchange_pairs['jupiter_ag']);
         		        
         $ct['jupiter_ag_pairs'][ $jup_pairs[1] ] .= $jup_pairs[0] . ',';
         		        
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
    	    $ct['upbit_pairs'] .= $exchange_pairs['upbit'] . ',';
    	    }
    	            
    }
                
}
    
$ct['upbit_pairs'] = substr($ct['upbit_pairs'], 0, -1);
            
// UPBIT - END


// COINGECKO - START

$check_pairs = array();
$check_assets = array();
            
foreach ( $ct['conf']['assets'] as $mrkts_conf ) {
                  
    foreach ( $mrkts_conf['pair'] as $pair_conf ) {
                  
         foreach ( $pair_conf as $exchange_key => $exchange_val ) {
            	            
              if ( stristr($exchange_key, 'coingecko_') != false && trim($exchange_val) != '' ) { // In case user messes up Admin Config, this helps
            		        
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
            
$ct['coingecko_pairs'] = substr($ct['coingecko_pairs'], 0, -1);
$ct['coingecko_assets'] = substr($ct['coingecko_assets'], 0, -1);
            
// COINGECKO - END


//////////////////////////////////////////////////////////////////
// END BATCHED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
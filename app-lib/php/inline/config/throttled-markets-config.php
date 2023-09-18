<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////
// THROTTLED MARKET SETTINGS
//////////////////////////////////////////////////////////////////


// THROTTLE ALPHAVANTAGE - START

// IF we don't have a PREMIUM PLAN (ALL premium plans are UNLIMITED daily requests)
// (zero is the flag for UNLIMITED daily requests, auto-adjusted in config-init.php)
if ( $ct['dev']['alphavantage_per_day_limit'] > 0 ) {

     // Figure out what our throttled cache time has to be for alphavantage stock asset API calls
     foreach ( $ct['conf']['assets'] as $markets ) {
                   
         foreach ( $markets['pair'] as $exchange_pairs ) {
              	            
            if ( isset($exchange_pairs['alphavantage_stock']) && $exchange_pairs['alphavantage_stock'] != '' ) { // In case user messes up Admin Config, this helps
            $alphavantage_pairs = $alphavantage_pairs + 1;
            }
              	            
         }
                     
     }

$alphavantage_max_daily_requests_per_asset = floor($ct['dev']['alphavantage_per_day_limit'] / $alphavantage_pairs);
          
$alphavantage_cache_time_per_asset = floor( ( 24 / $alphavantage_max_daily_requests_per_asset ) * 60 );

// Throttled based on how many times a day each asset can get LIVE data, AND STILL NOT GO OVER THE DAILY LIMIT
$throttled_api_cache_time['alphavantage.co'] = ( $alphavantage_cache_time_per_asset >  $ct['conf']['power']['last_trade_cache_time'] ? $alphavantage_cache_time_per_asset : $ct['conf']['power']['last_trade_cache_time'] );

}
// Otherwise, if we have an UNLIMITED daily requests plan, just use the same 'last_trade_cache_time' as everything else does
else {
$throttled_api_cache_time['alphavantage.co'] = $ct['conf']['power']['last_trade_cache_time'];
}

// We still do per minute too, because Alphavantage has a per-minute restriction (EVEN FOR PREMIUM SERVICES)
$throttled_api_per_minute_limit['alphavantage.co'] = $ct['conf']['ext_apis']['alphavantage_per_minute_limit'];

// THROTTLE ALPHAVANTAGE - END


//////////////////////////////////////////////////////////////////
// END THROTTLED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
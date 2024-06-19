<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



//////////////////////////////////////////////////////////////////
// THROTTLED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// MAKE SURE **ANYTHING** RUN IN HERE --IS ENGINEERED TO-- BE CLEANLY RELOADED!!


// THROTTLE ALPHAVANTAGE - START

// If we have an AlphaVantage UNLIMITED daily requests plan
// https://www.alphavantage.co/premium/
if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] > 5 ) {
$alphavantage_per_day_limit = 0; // Unlimited
}
else {
$alphavantage_per_day_limit = $ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit'];
}


// IF we don't have a PREMIUM PLAN (ALL premium plans are UNLIMITED daily requests)
// (zero is the flag for UNLIMITED daily requests, auto-adjusted in config-init.php)
if ( $alphavantage_per_day_limit > 0 ) {

$ct['alphavantage_pairs'] = 0; // RESET, since we reload this logic on config resets / user updates

     // Figure out what our throttled cache time has to be for alphavantage stock asset API calls
     foreach ( $ct['conf']['assets'] as $markets ) {
                   
         foreach ( $markets['pair'] as $exchange_pairs ) {
              	            
            if ( isset($exchange_pairs['alphavantage_stock']) && $exchange_pairs['alphavantage_stock'] != '' ) { // In case user messes up Admin Config, this helps
            $ct['alphavantage_pairs'] = $ct['alphavantage_pairs'] + 1;
            }
              	            
         }
                     
     }


     if ( $alphavantage_per_day_limit >= 1 ) {
     $alphavantage_cache_time_per_asset =  floor( ( (24 / $alphavantage_per_day_limit) * 60 ) * $ct['alphavantage_pairs']);
     }
     else {
     $alphavantage_cache_time_per_asset = 99999999999999999999; // Simple / effective "never runs" cache time
     }


// Throttled based on how many times a day each asset can get LIVE data, AND STILL NOT GO OVER THE DAILY LIMIT
$ct['throttled_api_cache_time']['alphavantage.co'] = ( $alphavantage_cache_time_per_asset >  $ct['conf']['power']['last_trade_cache_time'] ? $alphavantage_cache_time_per_asset : $ct['conf']['power']['last_trade_cache_time'] );

}
// Otherwise, if we have an UNLIMITED daily requests plan, just use the same 'last_trade_cache_time' as everything else does
else {
$ct['throttled_api_cache_time']['alphavantage.co'] = $ct['conf']['power']['last_trade_cache_time'];
}

// We still do per minute too, because Alphavantage has a per-minute restriction (EVEN FOR PREMIUM SERVICES)
$ct['throttled_api_per_minute_limit']['alphavantage.co'] = $ct['conf']['ext_apis']['alphavantage_per_minute_limit'];

// THROTTLE ALPHAVANTAGE - END


//////////////////////////////////////////////////////////////////
// END THROTTLED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
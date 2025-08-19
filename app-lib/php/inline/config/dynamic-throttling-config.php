<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



//////////////////////////////////////////////////////////////////
// DYNAMIC THROTTLED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// MAKE SURE **ANYTHING** RUN IN HERE --IS ENGINEERED TO-- BE CLEANLY RELOADED!!


// ALPHAVANTAGE - START


// IF we do NOT have a PREMIUM PLAN
if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {

$ct['alphavantage_pairs'] = 0; // RESET, since we reload this logic on config resets / user updates


     // Figure out what our throttled cache time has to be for alphavantage stock asset API calls
     foreach ( $ct['conf']['assets'] as $markets ) {
                   
         foreach ( $markets['pair'] as $exchange_pairs ) {
             
             // In case user messes up Admin Config, this CONDITION CHECK helps
             if ( isset($exchange_pairs['alphavantage_stock']) && $exchange_pairs['alphavantage_stock'] != '' ) { 
             $ct['alphavantage_pairs'] = $ct['alphavantage_pairs'] + 1;
             }
              	            
         }
                     
     }


$alphavantage_cache_time =  floor( ( (24 / $ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit']) * 60 ) * $ct['alphavantage_pairs']);

// Throttled based on how many times a day each asset can get LIVE data, AND STILL NOT GO OVER THE DAILY LIMIT
$ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'] = ( $alphavantage_cache_time > $ct['conf']['power']['last_trade_cache_time'] ? $alphavantage_cache_time : $ct['conf']['power']['last_trade_cache_time'] );

// Alphavantage DAILY limit FOR FREE tier (all premium tiers have NO daily limit)
$ct['dev']['throttled_apis']['alphavantage.co']['per_day'] = $ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit'];

// Add 1 request per-second throttling for the FREE tier,
// AS THE ALPHAVANTAGE FREE API CAN BE A LITTLE WONKY TO BEGIN WITH
$ct['dev']['throttled_apis']['alphavantage.co']['per_second'] = 1;

}
// Otherwise, if we have an UNLIMITED daily requests plan, just use the same 'last_trade_cache_time' as everything else does
else {
$ct['dev']['throttled_apis']['alphavantage.co']['min_cache_time'] = $ct['conf']['power']['last_trade_cache_time'];
}


// We still do per minute too, because Alphavantage has a per-minute restriction (EVEN FOR PREMIUM SERVICES)
$ct['dev']['throttled_apis']['alphavantage.co']['per_minute'] = $ct['conf']['ext_apis']['alphavantage_per_minute_limit'];


// ALPHAVANTAGE - END


//////////////////////////////////////////////////////////////////
// END DYNAMIC THROTTLED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
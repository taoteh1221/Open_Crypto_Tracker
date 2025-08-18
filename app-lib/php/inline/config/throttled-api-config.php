<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



//////////////////////////////////////////////////////////////////
// THROTTLED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// MAKE SURE **ANYTHING** RUN IN HERE --IS ENGINEERED TO-- BE CLEANLY RELOADED!!


// THROTTLE ALPHAVANTAGE - START


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


$alphavantage_cache_time_per_asset =  floor( ( (24 / $ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit']) * 60 ) * $ct['alphavantage_pairs']);

// Throttled based on how many times a day each asset can get LIVE data, AND STILL NOT GO OVER THE DAILY LIMIT
$ct['throttled_api_min_cache_time']['alphavantage.co'] = ( $alphavantage_cache_time_per_asset > $ct['conf']['power']['last_trade_cache_time'] ? $alphavantage_cache_time_per_asset : $ct['conf']['power']['last_trade_cache_time'] );

// Alphavantage DAILY limit FOR FREE tier (all premium tiers have NO daily limit)
$ct['throttled_api_per_day_limit']['alphavantage.co'] = $ct['conf']['ext_apis']['alphavantage_free_plan_daily_limit'];

// Add 1 request per-second throttling for the FREE tier,
// AS THE ALPHAVANTAGE FREE API CAN BE A LITTLE WONKY TO BEGIN WITH
$ct['throttled_api_per_second_limit']['alphavantage.co'] = 1;

}
// Otherwise, if we have an UNLIMITED daily requests plan, just use the same 'last_trade_cache_time' as everything else does
else {
$ct['throttled_api_min_cache_time']['alphavantage.co'] = $ct['conf']['power']['last_trade_cache_time'];
}


// We still do per minute too, because Alphavantage has a per-minute restriction (EVEN FOR PREMIUM SERVICES)
$ct['throttled_api_per_minute_limit']['alphavantage.co'] = $ct['conf']['ext_apis']['alphavantage_per_minute_limit'];


// THROTTLE ALPHAVANTAGE - END


// THROTTLE Reddit (PER-MINUTE)
// https://www.reddit.com/r/redditdev/comments/14nbw6g/updated_rate_limits_going_into_effect_over_the/
$ct['throttled_api_per_minute_limit']['reddit.com'] = 10;


// THROTTLE Coingecko TERMINAL (PER-MINUTE)
// https://apiguide.geckoterminal.com/faq
$ct['throttled_api_per_minute_limit']['geckoterminal.com'] = 30;


// THROTTLE Coingecko (PER-MINUTE)
// https://support.coingecko.com/hc/en-us/articles/4538771776153-What-is-the-rate-limit-for-CoinGecko-API-public-plan
$ct['throttled_api_per_minute_limit']['coingecko.com'] = 10;
////
// EVEN THOUGH COINGECKO HAS NO PER-SECOND THROTTLE LIMIT,
// SET TO 2 PER-SECOND, AS THEY ARE USUALLY PRETTY STRICT WITH ACCESS LIMITS          
$ct['throttled_api_per_second_limit']['coingecko.com'] = 2;


// THROTTLE JUP_AG (PER-SECOND)
// FREE tier is one request per second (as of 2025/may/3rd):
// https://dev.jup.ag/docs/api-setup
$ct['throttled_api_per_second_limit']['jup.ag'] = 1;


// THROTTLE Coinbase (PER-SECOND)
// https://docs.cdp.coinbase.com/exchange/docs/rate-limits
$ct['throttled_api_per_second_limit']['coinbase.com'] = 10;


// THROTTLE Solana (PER-SECOND)
// https://solana.com/docs/references/clusters
$ct['throttled_api_per_second_limit']['solana.com'] = 4;


// THROTTLE Twilio (PER-SECOND)
// https://help.twilio.com/articles/115002943027-Understanding-Twilio-Rate-Limits-and-Message-Queues
$ct['throttled_api_per_second_limit']['twilio.com'] = 1;


// THROTTLE Etherscan (PER-SECOND)
// https://docs.etherscan.io/support/rate-limits
$ct['throttled_api_per_second_limit']['etherscan.io'] = 10;


// THROTTLE Gemini (PER-SECOND)
// https://docs.gemini.com/rest-api/#two-factor-authentication
$ct['throttled_api_per_second_limit']['gemini.com'] = 1;


// THROTTLE Bitstamp (PER-SECOND)
// https://www.bitstamp.net/api/#section/Response-codes
$ct['throttled_api_per_second_limit']['bitstamp.net'] = 400;


// THROTTLE Medium (PER-SECOND)
// NO DOCS, BUT CAN BE FINICKY, SO MIGHT AS WELL THROTTLE IT
$ct['throttled_api_per_second_limit']['medium.com'] = 1;


//////////////////////////////////////////////////////////////////
// END THROTTLED MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
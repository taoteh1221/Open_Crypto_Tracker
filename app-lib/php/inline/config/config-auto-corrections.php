<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG AUTO-CORRECT
//////////////////////////////////////////////////////////////////


// Cleaning lowercase alphanumeric string values, and auto-correct minor errors
$ct['conf']['comms']['to_email'] = $ct['var']->auto_correct_str($ct['conf']['comms']['to_email'], 'lower');
$ct['conf']['power']['debug_mode'] = $ct['var']->auto_correct_str($ct['conf']['power']['debug_mode'], 'lower');
$ct['conf']['comms']['upgrade_alert_channels'] = $ct['var']->auto_correct_str($ct['conf']['comms']['upgrade_alert_channels'], 'lower');
$ct['conf']['currency']['bitcoin_primary_currency_pair'] = $ct['var']->auto_correct_str($ct['conf']['currency']['bitcoin_primary_currency_pair'], 'lower');
$ct['conf']['currency']['bitcoin_primary_currency_exchange'] = $ct['var']->auto_correct_str($ct['conf']['currency']['bitcoin_primary_currency_exchange'], 'lower');
$ct['conf']['power']['log_verbosity'] = $ct['var']->auto_correct_str($ct['conf']['power']['log_verbosity'], 'lower');
$ct['conf']['gen']['default_theme'] = $ct['var']->auto_correct_str($ct['conf']['gen']['default_theme'], 'lower');
$ct['conf']['gen']['primary_marketcap_site'] = $ct['var']->auto_correct_str($ct['conf']['gen']['primary_marketcap_site'], 'lower');
$ct['conf']['charts_alerts']['price_alert_block_volume_error'] = $ct['var']->auto_correct_str($ct['conf']['charts_alerts']['price_alert_block_volume_error'], 'lower');
$ct['conf']['sec']['remote_api_strict_ssl'] = $ct['var']->auto_correct_str($ct['conf']['sec']['remote_api_strict_ssl'], 'lower');
$ct['conf']['charts_alerts']['enable_price_charts'] = $ct['var']->auto_correct_str($ct['conf']['charts_alerts']['enable_price_charts'], 'lower');
$ct['conf']['proxy']['proxy_alert_channels'] = $ct['var']->auto_correct_str($ct['conf']['proxy']['proxy_alert_channels'], 'lower');
$ct['conf']['proxy']['proxy_alert_runtime'] = $ct['var']->auto_correct_str($ct['conf']['proxy']['proxy_alert_runtime'], 'lower');
$ct['conf']['proxy']['proxy_alert_checkup_ok'] = $ct['var']->auto_correct_str($ct['conf']['proxy']['proxy_alert_checkup_ok'], 'lower');


// Trimming whitespace
$ct['conf']['charts_alerts']['whale_alert_thresholds'] = trim($ct['conf']['charts_alerts']['whale_alert_thresholds']);


// Auto-correct case on market tickers...

$ct['conf']['currency']['upbit_pairings_search'] = $ct['gen']->auto_correct_market_id($ct['conf']['currency']['upbit_pairings_search'], 'upbit');

$ct['conf']['currency']['additional_pairings_search'] = $ct['var']->auto_correct_str($ct['conf']['currency']['additional_pairings_search'], 'lower');


// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $key => $val ) {
$cleaned_charts_and_price_alerts[$key] = $ct['var']->auto_correct_str($val, 'lower');
}
$ct['conf']['charts_alerts']['tracked_markets'] = $cleaned_charts_and_price_alerts;


// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $ct['conf']['mobile_network']['text_gateways'] as $key => $val ) {
$cleaned_mobile_networks[$key] = $ct['var']->auto_correct_str($val, 'lower');
}
$ct['conf']['mobile_network']['text_gateways'] = $cleaned_mobile_networks;


// Convert to lowercase some arrays THAT ARE SAFE TO...

$ct['conf']['proxy']['anti_proxy_servers'] = array_map("strtolower", $ct['conf']['proxy']['anti_proxy_servers']);

$ct['conf']['news']['strict_news_feed_servers'] = array_map("strtolower", $ct['conf']['news']['strict_news_feed_servers']);

$ct['conf']['currency']['bitcoin_preferred_currency_markets'] = array_map("strtolower", $ct['conf']['currency']['bitcoin_preferred_currency_markets']);

$ct['conf']['currency']['crypto_pair_preferred_markets'] = array_map("strtolower", $ct['conf']['currency']['crypto_pair_preferred_markets']);

$ct['conf']['charts_alerts']['tracked_markets'] = array_map("strtolower", $ct['conf']['charts_alerts']['tracked_markets']);

$ct['conf']['mobile_network']['text_gateways'] = array_map("strtolower", $ct['conf']['mobile_network']['text_gateways']);

$ct['conf']['currency']['token_presales_usd'] = array_map("strtolower", $ct['conf']['currency']['token_presales_usd']);


// Trim whitepace from some values THAT ARE SAFE TO RUN TRIMMING ON...

// STRINGS

$ct['conf']['comms']['smtp_server'] = trim($ct['conf']['comms']['smtp_server']);

// ARRAYS

$ct['conf']['proxy']['proxy_list'] = array_map("trim", $ct['conf']['proxy']['proxy_list']);

$ct['conf']['proxy']['anti_proxy_servers'] = array_map("trim", $ct['conf']['proxy']['anti_proxy_servers']);

$ct['conf']['news']['strict_news_feed_servers'] = array_map("trim", $ct['conf']['news']['strict_news_feed_servers']);

$ct['conf']['currency']['conversion_currency_symbols'] = array_map("trim", $ct['conf']['currency']['conversion_currency_symbols']);

$ct['conf']['currency']['bitcoin_preferred_currency_markets'] = array_map("trim", $ct['conf']['currency']['bitcoin_preferred_currency_markets']);

$ct['conf']['currency']['crypto_pair'] = array_map("trim", $ct['conf']['currency']['crypto_pair']);

$ct['conf']['currency']['crypto_pair_preferred_markets'] = array_map("trim", $ct['conf']['currency']['crypto_pair_preferred_markets']);

$ct['conf']['charts_alerts']['tracked_markets'] = array_map("trim", $ct['conf']['charts_alerts']['tracked_markets']);

$ct['conf']['mobile_network']['text_gateways'] = array_map("trim", $ct['conf']['mobile_network']['text_gateways']);

$ct['conf']['currency']['token_presales_usd'] = array_map("trim", $ct['conf']['currency']['token_presales_usd']);


foreach ( $ct['conf']['news']['feeds'] as $key => $val ) {
$ct['conf']['news']['feeds'][$key] = array_map("trim", $ct['conf']['news']['feeds'][$key]);
}


// Idiot-proof maximum RANGE of jupiter aggregator search results
if ( $ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] > 250 ) {
$ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] = 250;
}
elseif ( $ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] < 75 ) {
$ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] = 75;
}


// Idiot-proof maximum RANGE of $ct['conf']['currency']['currency_decimals_max'] 
if ( $ct['conf']['currency']['currency_decimals_max'] > 15 ) {
$ct['conf']['currency']['currency_decimals_max'] = 15;
}
elseif ( $ct['conf']['currency']['currency_decimals_max'] < 5 ) {
$ct['conf']['currency']['currency_decimals_max'] = 5;
}


// Idiot-proof maximum RANGE of $ct['conf']['currency']['crypto_decimals_max']
if ( $ct['conf']['currency']['crypto_decimals_max'] > 20 ) {
$ct['conf']['currency']['crypto_decimals_max'] = 20;
}
elseif ( $ct['conf']['currency']['crypto_decimals_max'] < 10 ) {
$ct['conf']['currency']['crypto_decimals_max'] = 10;
}


// Idiot-proof maximum RANGE of $ct['conf']['comms']['market_error_threshold']
if ( $ct['conf']['comms']['market_error_threshold'] > 15 ) {
$ct['conf']['comms']['market_error_threshold'] = 15;
}
elseif ( $ct['conf']['comms']['market_error_threshold'] < 5 ) {
$ct['conf']['comms']['market_error_threshold'] = 5;
}


// Idiot-proof maximum of +-35 on captcha text contrast
if ( abs($ct['conf']['sec']['captcha_text_contrast']) > 35 ) {
$ct['conf']['sec']['captcha_text_contrast'] = 35;
}


// Idiot-proof maximum of 35 degrees on captcha text angle-offset
if ( abs($ct['conf']['sec']['captcha_text_angle']) > 35 || $ct['conf']['sec']['captcha_text_angle'] < 0 ) {
$ct['conf']['sec']['captcha_text_angle'] = 35;
}


// Idiot-proof last_trade_cache_time
if ( abs($ct['conf']['power']['last_trade_cache_time']) > 60 || $ct['conf']['power']['last_trade_cache_time'] < 0 ) {
$ct['conf']['power']['last_trade_cache_time'] = 60;
}


// Idiot-proof access_stats_delete_old
if ( $ct['conf']['power']['access_stats_delete_old'] > 360 ) {
$ct['conf']['power']['access_stats_delete_old'] = 360;
}
elseif ( $ct['conf']['power']['access_stats_delete_old'] < 15 ) {
$ct['conf']['power']['access_stats_delete_old'] = 15;
}


// Idiot-proof marketcap_cache_time
if ( $ct['conf']['power']['marketcap_cache_time'] > 200 ) {
$ct['conf']['power']['marketcap_cache_time'] = 200;
}
elseif ( $ct['conf']['power']['marketcap_cache_time'] < 50 ) {
$ct['conf']['power']['marketcap_cache_time'] = 50;
}


// Idiot-proof marketcap_ranks_max
if ( $ct['conf']['power']['marketcap_ranks_max'] > 1000 ) {
$ct['conf']['power']['marketcap_ranks_max'] = 1000;
}
elseif ( $ct['conf']['power']['marketcap_ranks_max'] < 100 ) {
$ct['conf']['power']['marketcap_ranks_max'] = 100;
}


// Idiot-proof admin login MAX expiration time
if ( !$ct['var']->whole_int($ct['conf']['sec']['admin_cookie_expires']) || $ct['conf']['sec']['admin_cookie_expires'] > 6 ) {
$ct['conf']['sec']['admin_cookie_expires'] = 6;
}


// Remove SECONDARY crypto pairs that have no configged markets
// (EXCEPT BTC, AS ITS **THE PRIMARY CRYPTO MARKET** [WE ADD ABOVE IN THIS FILE])
foreach ( $ct['opt_conf']['crypto_pair'] as $pair_key => $pair_unused ) {


     foreach ( $ct['conf']['assets'] as $asset_key => $asset_unused ) {
     
          if ( $asset_key == 'BTC' || isset($ct['conf']['assets'][strtoupper($pair_key)]['pair']['btc']) ) {
          $ct['check_crypto_pair'][$pair_key] = true;
          }
     
     }

     
     if ( !isset($ct['check_crypto_pair'][$pair_key]) ) {
     unset($ct['opt_conf']['crypto_pair'][$pair_key]);
     }


}


// REMOVE primary BTC currency pairs that have no configged markets
foreach ( $ct['opt_conf']['conversion_currency_symbols'] as $pair_key => $pair_unused ) {

     if ( !isset($ct['conf']['assets']['BTC']['pair'][$pair_key]) ) {
     unset($ct['opt_conf']['conversion_currency_symbols'][$pair_key]);
     }

}


// ADD primary BTC currency pairs NOT YET ADDED, THAT HAVE BTC MARKETS CONFIGGED
foreach ( $ct['conf']['assets']['BTC']['pair'] as $btc_currency_pair => $unused ) {

     if (
     is_array($ct['conf']['assets']['BTC']['pair'][$btc_currency_pair])
     && sizeof($ct['conf']['assets']['BTC']['pair'][$btc_currency_pair]) > 0
     && !isset($ct['opt_conf']['conversion_currency_symbols'][$btc_currency_pair])
     ) {
     // Just set the ticker as the symbol, since we really should include this automatically for better (more) currency support
     // (ADD A SPACE AT END, SO IT DOESN'T LOOK WEIRD)
     $ct['opt_conf']['conversion_currency_symbols'][$btc_currency_pair] = strtoupper($btc_currency_pair) . ' ';
     }

}


//////////////////////////////////////////////////////////////////
// END APP CONFIG AUTO-CORRECT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
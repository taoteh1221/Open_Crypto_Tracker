<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////


// Set light charts config array
$ct['light_chart_day_intervals'] = array_map( "trim", explode(',', $ct['conf']['power']['light_chart_day_intervals']) );

// Numericly sort light chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($ct['light_chart_day_intervals']);

// Append default light chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$ct['light_chart_day_intervals'][] = 'all';
    

// START CONFIG AUTO-CORRECT (fix any basic end user data entry errors in possibly user-customized DEFAULTS in config.php)


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

$ct['conf']['currency']['coingecko_pairings_search'] = $ct['gen']->auto_correct_market_id($ct['conf']['currency']['coingecko_pairings_search'], 'coingecko');

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


// If user blanked out a SINGLE REPEATABLE value via the admin interface, we need to unset the blank values to have the app logic run smoothly
// (as we require at least one blank value IN THE INTERFACE WHEN SUBMITTING UPDATES, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config)
if ( is_array($ct['conf']['proxy']['proxy_list']) && sizeof($ct['conf']['proxy']['proxy_list']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $ct['conf']['proxy']['proxy_list'] as $key => $val ) {
     
          if ( trim($val) == '' ) {
          unset($ct['conf']['proxy']['proxy_list'][$key]);
          }
     
     }
     
}


if ( is_array($ct['conf']['proxy']['anti_proxy_servers']) && sizeof($ct['conf']['proxy']['anti_proxy_servers']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $ct['conf']['proxy']['anti_proxy_servers'] as $key => $val ) {
     
          if ( trim($val) == '' ) {
          unset($ct['conf']['proxy']['anti_proxy_servers'][$key]);
          }
     
     }
     
}


if ( is_array($ct['conf']['news']['strict_news_feed_servers']) && sizeof($ct['conf']['news']['strict_news_feed_servers']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $ct['conf']['news']['strict_news_feed_servers'] as $key => $val ) {
     
          if ( trim($val) == '' ) {
          unset($ct['conf']['news']['strict_news_feed_servers'][$key]);
          }
     
     }
     
}


if ( is_array($ct['conf']['currency']['conversion_currency_symbols']) ) {
     
     if ( sizeof($ct['conf']['currency']['conversion_currency_symbols']) == 1 ) {
          
          // We are NOT assured key == 0, if it was updated via the admin interface
          foreach ( $ct['conf']['currency']['conversion_currency_symbols'] as $key => $val ) {
          
               if ( trim($val) == '' ) {
               unset($ct['conf']['currency']['conversion_currency_symbols']);
               }
          
          }
          
     }
     
     
     // Convert stored format (that's easily editable in user interfacing) 
     // to an optimized format for using in programming logic
     $ct['opt_conf']['conversion_currency_symbols'] = array();
     foreach ( $ct['conf']['currency']['conversion_currency_symbols'] as $key => $val ) {
     $conversion_array = explode('=', $val);
     $conversion_array = array_map("trim", $conversion_array);
     // Auto-correct config values
     $ct['conf']['currency']['conversion_currency_symbols'][$key] = strtolower($conversion_array[0]) . ' = ' . $conversion_array[1];
     // Auto-formatting
     $ct['opt_conf']['conversion_currency_symbols'][ strtolower($conversion_array[0]) ] = $conversion_array[1];
     }
     
// Alphabetically sort
sort($ct['conf']['currency']['conversion_currency_symbols']);
ksort($ct['opt_conf']['conversion_currency_symbols']);
     
}


if ( is_array($ct['conf']['currency']['bitcoin_preferred_currency_markets']) ) {
     
     
     if ( sizeof($ct['conf']['currency']['bitcoin_preferred_currency_markets']) == 1 ) {
          
          // We are NOT assured key == 0, if it was updated via the admin interface
          foreach ( $ct['conf']['currency']['bitcoin_preferred_currency_markets'] as $key => $val ) {
          
               if ( trim($val) == '' ) {
               unset($ct['conf']['currency']['bitcoin_preferred_currency_markets']);
               }
          
          }
          
     }
     
     
     // Convert stored format (that's easily editable in user interfacing) 
     // to an optimized format for using in programming logic
     $ct['opt_conf']['bitcoin_preferred_currency_markets'] = array();
     foreach ( $ct['conf']['currency']['bitcoin_preferred_currency_markets'] as $val ) {
     $conversion_array = explode('=', $val);
     $conversion_array = array_map("trim", $conversion_array);
     $ct['opt_conf']['bitcoin_preferred_currency_markets'][ strtolower($conversion_array[0]) ] = $conversion_array[1];
     }
     
     
// Alphabetically sort
sort($ct['conf']['currency']['bitcoin_preferred_currency_markets']);
ksort($ct['opt_conf']['bitcoin_preferred_currency_markets']);
     
}


if ( is_array($ct['conf']['currency']['crypto_pair']) ) {
     
     if ( sizeof($ct['conf']['currency']['crypto_pair']) == 1 ) {
          
          // We are NOT assured key == 0, if it was updated via the admin interface
          foreach ( $ct['conf']['currency']['crypto_pair'] as $key => $val ) {
          
               if ( trim($val) == '' ) {
               unset($ct['conf']['currency']['crypto_pair']);
               }
          
          }
          
     }
     
     
     // Convert stored format (that's easily editable in user interfacing) 
     // to an optimized format for using in programming logic
     $ct['opt_conf']['crypto_pair'] = array();
     foreach ( $ct['conf']['currency']['crypto_pair'] as $val ) {
     $conversion_array = explode('=', $val);
     $conversion_array = array_map("trim", $conversion_array);
     // Auto-formatting
     $ct['opt_conf']['crypto_pair'][ strtolower($conversion_array[0]) ] = $conversion_array[1];
     }
     
// Alphabetically sort
sort($ct['conf']['currency']['crypto_pair']);
ksort($ct['opt_conf']['crypto_pair']);
     
}


if ( is_array($ct['conf']['currency']['crypto_pair_preferred_markets']) ) {
     
     if ( sizeof($ct['conf']['currency']['crypto_pair_preferred_markets']) == 1 ) {
          
          // We are NOT assured key == 0, if it was updated via the admin interface
          foreach ( $ct['conf']['currency']['crypto_pair_preferred_markets'] as $key => $val ) {
          
               if ( trim($val) == '' ) {
               unset($ct['conf']['currency']['crypto_pair_preferred_markets']);
               }
          
          }
          
     }
     
     
     // Convert stored format (that's easily editable in user interfacing) 
     // to an optimized format for using in programming logic
     $ct['opt_conf']['crypto_pair_preferred_markets'] = array();
     foreach ( $ct['conf']['currency']['crypto_pair_preferred_markets'] as $val ) {
     $conversion_array = explode('=', $val);
     $conversion_array = array_map("trim", $conversion_array);
     $ct['opt_conf']['crypto_pair_preferred_markets'][ strtolower($conversion_array[0]) ] = $conversion_array[1];
     }
     
// Alphabetically sort
sort($ct['conf']['currency']['crypto_pair_preferred_markets']);
ksort($ct['opt_conf']['crypto_pair_preferred_markets']);
     
}


if ( is_array($ct['conf']['currency']['token_presales_usd']) ) {
     
     if ( sizeof($ct['conf']['currency']['token_presales_usd']) == 1 ) {
          
          // We are NOT assured key == 0, if it was updated via the admin interface
          foreach ( $ct['conf']['currency']['token_presales_usd'] as $key => $val ) {
          
               if ( trim($val) == '' ) {
               unset($ct['conf']['currency']['token_presales_usd']);
               }
          
          }
          
     }
     
     
     // Convert stored format (that's easily editable in user interfacing) 
     // to an optimized format for using in programming logic
     $ct['opt_conf']['token_presales_usd'] = array();
     foreach ( $ct['conf']['currency']['token_presales_usd'] as $val ) {
     $conversion_array = explode('=', $val);
     $conversion_array = array_map("trim", $conversion_array);
     $ct['opt_conf']['token_presales_usd'][ strtolower($conversion_array[0]) ] = $conversion_array[1];
     }
     
// Alphabetically sort
sort($ct['conf']['currency']['token_presales_usd']);
ksort($ct['opt_conf']['token_presales_usd']);
     
}


if ( is_array($ct['conf']['charts_alerts']['tracked_markets']) && sizeof($ct['conf']['charts_alerts']['tracked_markets']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $key => $val ) {
     
          if ( trim($val) == '' ) {
          unset($ct['conf']['charts_alerts']['tracked_markets']);
          }
     
     }
     
}


if ( is_array($ct['conf']['mobile_network']['text_gateways']) && sizeof($ct['conf']['mobile_network']['text_gateways']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $ct['conf']['mobile_network']['text_gateways'] as $key => $val ) {
     
          if ( trim($val) == '' ) {
          unset($ct['conf']['mobile_network']['text_gateways']);
          }
     
     }
     
}


if ( is_array($ct['conf']['news']['feeds']) && sizeof($ct['conf']['news']['feeds']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $ct['conf']['news']['feeds'] as $key => $val ) {
     
          if ( trim($val['url']) == '' ) {
          unset($ct['conf']['news']['feeds'][$key]);
          }
     
     }
     
}



// END CONFIG CLEANUP



// Dynamically reconfigure / configure where needed


// Alphabetically sort plugin status list
ksort($ct['conf']['plugins']['plugin_status']);


// Default BTC CRYPTO/CRYPTO market pair support, BEFORE GENERATING MISCASSETS / BTCNFTS / ETHNFTS / SOLNFTS / ALTNFTS ARRAYS
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$ct['opt_conf']['crypto_pair'] = array('btc' => 'Ƀ ') + $ct['opt_conf']['crypto_pair']; // ADD TO #BEGINNING# OF ARRAY, FOR UX


// Idiot-proof maximum RANGE of jupiter aggregator search results
if ( $ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] > 250 ) {
$ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] = 250;
}
elseif ( $ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] < 75 ) {
$ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] = 75;
}


// Idiot-proof maximum RANGE of $ct['conf']['currency']['currency_decimals_max'] 
if ( $ct['conf']['currency']['currency_decimals_max'] > 10 ) {
$ct['conf']['currency']['currency_decimals_max'] = 10;
}
elseif ( $ct['conf']['currency']['currency_decimals_max'] < 5 ) {
$ct['conf']['currency']['currency_decimals_max'] = 5;
}


// Idiot-proof maximum RANGE of $ct['conf']['currency']['crypto_decimals_max']
if ( $ct['conf']['currency']['crypto_decimals_max'] > 15 ) {
$ct['conf']['currency']['crypto_decimals_max'] = 15;
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
if ( abs($ct['conf']['power']['access_stats_delete_old']) > 360 || $ct['conf']['power']['access_stats_delete_old'] < 0 ) {
$ct['conf']['power']['access_stats_delete_old'] = 360;
}

// Idiot-proof blockchain_stats_cache_time
if ( abs($ct['conf']['power']['blockchain_stats_cache_time']) > 100 || $ct['conf']['power']['blockchain_stats_cache_time'] < 0 ) {
$ct['conf']['power']['blockchain_stats_cache_time'] = 100;
}

// Idiot-proof marketcap_cache_time
if ( abs($ct['conf']['power']['marketcap_cache_time']) > 120 || $ct['conf']['power']['marketcap_cache_time'] < 0 ) {
$ct['conf']['power']['marketcap_cache_time'] = 120;
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


// Dynamically add MISCASSETS to $ct['conf']['assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $ct['conf']['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
// ALSO ADDING BTCNFTS / ETHNFTS / SOLNFTS / ALTNFTS DYNAMICALLY HERE
if ( is_array($ct['conf']['assets']) ) {
    
    
    // MISCASSETS
    $ct['conf']['assets']['MISCASSETS'] = array(
                                        'name' => 'Misc. Assets', // Filled in within primary-bitcoin-markets-config
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct['opt_conf']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['MISCASSETS']['pair'][$pair_key] = array('misc_assets' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct['conf']['assets']['MISCASSETS']['pair']) ) {
            	$ct['conf']['assets']['MISCASSETS']['pair'][$pair_key] = array('misc_assets' => $pair_key);
            	}
            
            }
    
    
    // BTCNFTS
    $ct['conf']['assets']['BTCNFTS'] = array(
                                        'name' => 'Bitcoin NFTs',
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct['opt_conf']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['BTCNFTS']['pair'][$pair_key] = array('btc_nfts' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct['conf']['assets']['BTCNFTS']['pair']) ) {
            	$ct['conf']['assets']['BTCNFTS']['pair'][$pair_key] = array('btc_nfts' => $pair_key);
            	}
            
            }
    
    
    // ETHNFTS
    $ct['conf']['assets']['ETHNFTS'] = array(
                                        'name' => 'Ethereum NFTs',
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct['opt_conf']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['ETHNFTS']['pair'][$pair_key] = array('eth_nfts' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct['conf']['assets']['ETHNFTS']['pair']) ) {
            	$ct['conf']['assets']['ETHNFTS']['pair'][$pair_key] = array('eth_nfts' => $pair_key);
            	}
            
            }
    
    
    // SOLNFTS
    $ct['conf']['assets']['SOLNFTS'] = array(
                                        'name' => 'Solana NFTs',
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct['opt_conf']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['SOLNFTS']['pair'][$pair_key] = array('sol_nfts' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct['conf']['assets']['SOLNFTS']['pair']) ) {
            	$ct['conf']['assets']['SOLNFTS']['pair'][$pair_key] = array('sol_nfts' => $pair_key);
            	}
            
            }
    
    
    // ALTNFTS
    $ct['conf']['assets']['ALTNFTS'] = array(
                                        'name' => 'Alt NFTs', // Filled in within primary-bitcoin-markets-config
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct['opt_conf']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['ALTNFTS']['pair'][$pair_key] = array('alt_nfts' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct['conf']['assets']['ALTNFTS']['pair']) ) {
            	$ct['conf']['assets']['ALTNFTS']['pair'][$pair_key] = array('alt_nfts' => $pair_key);
            	}
            
            }
                                        
}


// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairs for UX
if ( is_array($ct['conf']['assets']) ) {
    
    foreach ( $ct['conf']['assets'] as $symbol_key => $symbol_unused ) {
            
            
            if ( $ct['conf']['assets'][$symbol_key] == 'MISCASSETS' ) {
            asort($ct['conf']['assets'][$symbol_key]['pair']); // Sort maintaining indices
            }
            else if ( is_array($ct['conf']['assets'][$symbol_key]['pair']) ) {
            ksort($ct['conf']['assets'][$symbol_key]['pair']); // Sort by key name
            }
            
            
            foreach ( $ct['conf']['assets'][$symbol_key]['pair'] as $pair_key => $pair_unused ) {
                 
                 if ( is_array($ct['conf']['assets'][$symbol_key]['pair'][$pair_key]) ) {
                 ksort($ct['conf']['assets'][$symbol_key]['pair'][$pair_key]);
                 }
            
            }
            
        
    }
    
}


// Alphabetically sort mobile text email gateways
sort($ct['conf']['mobile_network']['text_gateways']);


// Alphabetically sort price charts / alerts
sort($ct['conf']['charts_alerts']['tracked_markets']);


// Alphabetically sort assets by 'name'
// We need to use uasort, instead of usort, to maintain the associative array structure
$ct['sort_alpha_assoc_multidem'] = 'name';
uasort($ct['conf']['assets'], array($ct['var'], 'alpha_usort') );

//$ct['gen']->array_debugging($ct['conf']['assets'], true); // DEBUGGING ONLY


// Better decimal support for these vars...
$ct['conf']['power']['system_stats_first_chart_maximum_scale'] = $ct['var']->num_to_str($ct['conf']['power']['system_stats_first_chart_maximum_scale']); 
$ct['conf']['charts_alerts']['price_alert_threshold'] = $ct['var']->num_to_str($ct['conf']['charts_alerts']['price_alert_threshold']); 


// Admin login MAX expiration time
if ( !$ct['var']->whole_int($ct['conf']['sec']['admin_cookie_expires']) || $ct['conf']['sec']['admin_cookie_expires'] > 6 ) {
$ct['conf']['sec']['admin_cookie_expires'] = 6;
}


// Mining calculator settings (DURING 'ui' ONLY, since we run the interface mining settings from here)
if ( $ct['runtime_mode'] == 'ui' ) {
require('dynamic-config.php');
}


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
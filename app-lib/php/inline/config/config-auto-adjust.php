<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

    

// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in possibly user-customized DEFAULTS in config.php)


// Cleaning lowercase alphanumeric string values, and auto-correct minor errors
$ct['conf']['comms']['to_email'] = $ct['var']->auto_correct_str($ct['conf']['comms']['to_email'], 'lower');
$ct['conf']['power']['debug_mode'] = $ct['var']->auto_correct_str($ct['conf']['power']['debug_mode'], 'lower');
$ct['conf']['comms']['upgrade_alert'] = $ct['var']->auto_correct_str($ct['conf']['comms']['upgrade_alert'], 'lower');
$ct['conf']['gen']['bitcoin_primary_currency_pair'] = $ct['var']->auto_correct_str($ct['conf']['gen']['bitcoin_primary_currency_pair'], 'lower');
$ct['conf']['gen']['bitcoin_primary_currency_exchange'] = $ct['var']->auto_correct_str($ct['conf']['gen']['bitcoin_primary_currency_exchange'], 'lower');
$ct['conf']['power']['log_verbosity'] = $ct['var']->auto_correct_str($ct['conf']['power']['log_verbosity'], 'lower');
$ct['conf']['gen']['default_theme'] = $ct['var']->auto_correct_str($ct['conf']['gen']['default_theme'], 'lower');
$ct['conf']['gen']['primary_marketcap_site'] = $ct['var']->auto_correct_str($ct['conf']['gen']['primary_marketcap_site'], 'lower');
$ct['conf']['charts_alerts']['price_alert_block_volume_error'] = $ct['var']->auto_correct_str($ct['conf']['charts_alerts']['price_alert_block_volume_error'], 'lower');
$ct['conf']['sec']['remote_api_strict_ssl'] = $ct['var']->auto_correct_str($ct['conf']['sec']['remote_api_strict_ssl'], 'lower');
$ct['conf']['charts_alerts']['enable_price_charts'] = $ct['var']->auto_correct_str($ct['conf']['charts_alerts']['enable_price_charts'], 'lower');
$ct['conf']['proxy']['proxy_alert'] = $ct['var']->auto_correct_str($ct['conf']['proxy']['proxy_alert'], 'lower');
$ct['conf']['proxy']['proxy_alert_runtime'] = $ct['var']->auto_correct_str($ct['conf']['proxy']['proxy_alert_runtime'], 'lower');
$ct['conf']['proxy']['proxy_alert_checkup_ok'] = $ct['var']->auto_correct_str($ct['conf']['proxy']['proxy_alert_checkup_ok'], 'lower');


// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $key => $val ) {
$cleaned_charts_and_price_alerts[$key] = $ct['var']->auto_correct_str($val, 'lower');
}
$ct['conf']['charts_alerts']['tracked_markets'] = $cleaned_charts_and_price_alerts;


// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $ct['conf']['mobile_network_text_gateways'] as $key => $val ) {
$cleaned_key = $ct['var']->auto_correct_str($key, 'lower');
$cleaned_val = $ct['var']->auto_correct_str($val, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_val;
}
$ct['conf']['mobile_network_text_gateways'] = $cleaned_mobile_networks;


// If user blanked out a SINGLE proxy list / strict api servers / strict news feeds server / news feeds value via the admin interface,
// we need to unset the blank values to have the app logic run smoothly
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


if ( is_array($ct['conf']['news']['feeds']) && sizeof($ct['conf']['news']['feeds']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $ct['conf']['news']['feeds'] as $key => $val ) {
     
          if ( trim($val['url']) == '' ) {
          unset($ct['conf']['news']['feeds'][$key]);
          }
     
     }
     
}


// Trim whitepace from some values THAT ARE SAFE TO RUN TRIMMING ON...

foreach ( $ct['conf']['proxy']['proxy_list'] as $key => $val ) {
$ct['conf']['proxy']['proxy_list'][$key] = trim($val);
}

foreach ( $ct['conf']['proxy']['anti_proxy_servers'] as $key => $val ) {
$ct['conf']['proxy']['anti_proxy_servers'][$key] = trim($val);
}

foreach ( $ct['conf']['news']['strict_news_feed_servers'] as $key => $val ) {
$ct['conf']['news']['strict_news_feed_servers'][$key] = trim($val);
}

foreach ( $ct['conf']['news']['feeds'] as $key => $val ) {
$ct['conf']['news']['feeds'][$key]['title'] = trim($val['title']);
$ct['conf']['news']['feeds'][$key]['url'] = trim($val['url']);
}



// END CONFIG CLEANUP



// Dynamically reconfigure / configure where needed


// Alphabetically sort plugin status list
ksort($ct['conf']['plugins']['plugin_status']);


// Default BTC CRYPTO/CRYPTO market pair support, BEFORE GENERATING MISCASSETS / BTCNFTS / ETHNFTS / SOLNFTS / ALTNFTS ARRAYS
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$ct['conf']['power']['crypto_pair'] = array('btc' => 'Ƀ ') + $ct['conf']['power']['crypto_pair']; // ADD TO #BEGINNING# OF ARRAY, FOR UX

// Numericly sort light chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($ct['conf']['power']['light_chart_day_intervals']);

// Default light chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$ct['conf']['power']['light_chart_day_intervals'][] = 'all';


// Idiot-proof maximum of +-35 on captcha text contrast
if ( abs($ct['conf']['sec']['captcha_text_contrast']) > 35 ) {
$ct['conf']['sec']['captcha_text_contrast'] = 35;
}

// Idiot-proof maximum of 35 degrees on captcha text angle-offset
if ( abs($ct['conf']['sec']['captcha_text_angle']) > 35 || $ct['conf']['sec']['captcha_text_angle'] < 0 ) {
$ct['conf']['sec']['captcha_text_angle'] = 35;
}



// Remove SECONDARY crypto pairs that have no configged markets
// (EXCEPT BTC, AS ITS **THE PRIMARY CRYPTO MARKET** [WE ADD ABOVE IN THIS FILE])
foreach ( $ct['conf']['power']['crypto_pair'] as $pair_key => $pair_unused ) {

     foreach ( $ct['conf']['assets'] as $asset_key => $asset_unused ) {
     
          if ( $asset_key == 'BTC' || isset($ct['conf']['assets'][strtoupper($pair_key)]['pair']['btc']) ) {
          $check_crypto_pair[$pair_key] = true;
          }
     
     }
     
     if ( !isset($check_crypto_pair[$pair_key]) ) {
     unset($ct['conf']['power']['crypto_pair'][$pair_key]);
     }

}


// Remove primary currency pairs that have no configged markets
foreach ( $ct['conf']['power']['bitcoin_currency_markets'] as $pair_key => $pair_unused ) {

     if ( !isset($ct['conf']['assets']['BTC']['pair'][$pair_key]) ) {
     unset($ct['conf']['power']['bitcoin_currency_markets'][$pair_key]);
     }

}


// Dynamically add MISCASSETS to $ct['conf']['assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $ct['conf']['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
// ALSO ADDING BTCNFTS / ETHNFTS / SOLNFTS / ALTNFTS DYNAMICALLY HERE
if ( is_array($ct['conf']['assets']) ) {
    
    
    // MISCASSETS
    $ct['conf']['assets']['MISCASSETS'] = array(
                                        'name' => '', // Filled in within primary-bitcoin-markets-config
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct['conf']['power']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['MISCASSETS']['pair'][$pair_key] = array('misc_assets' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['power']['bitcoin_currency_markets'] as $pair_key => $pair_unused ) {
            	
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
            
            
            foreach ( $ct['conf']['power']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['BTCNFTS']['pair'][$pair_key] = array('btc_nfts' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['power']['bitcoin_currency_markets'] as $pair_key => $pair_unused ) {
            	
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
            
            
            foreach ( $ct['conf']['power']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['ETHNFTS']['pair'][$pair_key] = array('eth_nfts' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['power']['bitcoin_currency_markets'] as $pair_key => $pair_unused ) {
            	
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
            
            
            foreach ( $ct['conf']['power']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['SOLNFTS']['pair'][$pair_key] = array('sol_nfts' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['power']['bitcoin_currency_markets'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct['conf']['assets']['SOLNFTS']['pair']) ) {
            	$ct['conf']['assets']['SOLNFTS']['pair'][$pair_key] = array('sol_nfts' => $pair_key);
            	}
            
            }
    
    
    // ALTNFTS
    $ct['conf']['assets']['ALTNFTS'] = array(
                                        'name' => '', // Filled in within primary-bitcoin-markets-config
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct['conf']['power']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct['conf']['assets']['ALTNFTS']['pair'][$pair_key] = array('alt_nfts' => $pair_key);
            }
            
            
            foreach ( $ct['conf']['power']['bitcoin_currency_markets'] as $pair_key => $pair_unused ) {
            	
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


// Better decimal support for these vars...
$ct['conf']['power']['system_stats_first_chart_maximum_scale'] = $ct['var']->num_to_str($ct['conf']['power']['system_stats_first_chart_maximum_scale']); 
$ct['conf']['charts_alerts']['price_alert_threshold'] = $ct['var']->num_to_str($ct['conf']['charts_alerts']['price_alert_threshold']); 
$ct['conf']['power']['hivepower_yearly_interest'] = $ct['var']->num_to_str($ct['conf']['power']['hivepower_yearly_interest']); 


// Admin login MAX expiration time
if ( !$ct['var']->whole_int($ct['conf']['sec']['admin_cookie_expires']) || $ct['conf']['sec']['admin_cookie_expires'] > 6 ) {
$ct['conf']['sec']['admin_cookie_expires'] = 6;
}


// Update dynamic mining calculator settings (DURING 'ui' ONLY), since we are running the app's main settings from a cache
if ( $ct['runtime_mode'] == 'ui' && is_array($ct['conf']['power']['mining_calculators']) ) {
require('dynamic-config.php');
}


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
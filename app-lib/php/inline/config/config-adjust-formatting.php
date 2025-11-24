<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////


// Alphabetically sort plugin status list
ksort($ct['conf']['plugins']['plugin_status']);


// Alphabetically sort mobile text email gateways
sort($ct['conf']['mobile_network']['text_gateways']);


// Alphabetically sort price charts / alerts
sort($ct['conf']['charts_alerts']['tracked_markets']);


// Set light charts config array
$ct['light_chart_day_intervals'] = array_map( "trim", explode(',', $ct['conf']['power']['light_chart_day_intervals']) );

// Numericly sort light chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($ct['light_chart_day_intervals']);

// Append default light chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$ct['light_chart_day_intervals'][] = 'all';
    

// Better decimal support for these vars...

$ct['conf']['power']['system_stats_first_chart_maximum_scale'] = $ct['var']->num_to_str($ct['conf']['power']['system_stats_first_chart_maximum_scale']); 

$ct['conf']['charts_alerts']['price_alert_threshold'] = $ct['var']->num_to_str($ct['conf']['charts_alerts']['price_alert_threshold']); 



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


if ( is_array($ct['conf']['charts_alerts']['tracked_markets']) && sizeof($ct['conf']['charts_alerts']['tracked_markets']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $key => $val ) {
     
          if ( trim($val) == '' ) {
          unset($ct['conf']['charts_alerts']['tracked_markets']);
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

// Default BTC CRYPTO/CRYPTO market pair support, BEFORE GENERATING MISCASSETS / BTCNFTS / ETHNFTS / SOLNFTS / ALTNFTS ARRAYS
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$ct['opt_conf']['crypto_pair'] = array('btc' => 'Ƀ ') + $ct['opt_conf']['crypto_pair']; // ADD TO #BEGINNING# OF ARRAY, FOR UX
     
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


// Alphabetically sort assets by 'name' (AFTER adding special assets above!)
// We need to use uasort, instead of usort, to maintain the associative array structure
$ct['sort_by_nested'] = 'root=>name';
uasort($ct['conf']['assets'], array($ct['var'], 'usort_asc') );
$ct['sort_by_nested'] = false; // RESET

//$ct['gen']->array_debugging($ct['conf']['assets'], true); // DEBUGGING ONLY


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
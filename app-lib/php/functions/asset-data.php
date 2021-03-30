<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function powerdown_prim_curr($data) {

global $hive_market, $ocpt_conf, $selected_btc_prim_curr_value;

return ( $data * $hive_market * $selected_btc_prim_curr_value );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function get_sub_token_price($chosen_market, $market_pairing) {

global $ocpt_conf;

  if ( strtolower($chosen_market) == 'eth_subtokens_ico' ) {
  return $ocpt_conf['power_user']['ethereum_subtoken_ico_values'][$market_pairing];
  }
 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function bitcoin_total() {
	
global $btc_worth_array;

  	foreach ( $btc_worth_array as $key => $value ) {
  	$total_value = ($value + $total_value);
  	}
  
return $total_value;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function coin_stats_data($request) {

global $coin_stats_array;

  	foreach ( $coin_stats_array as $key => $value ) {
  	$results = ($results + $value[$request]);
	}
		
return $results;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function asset_list_internal_api() {

global $ocpt_conf;

$result = array();

	foreach ( $ocpt_conf['assets'] as $key => $unused ) {
		
		if ( strtolower($key) != 'miscassets' ) {
		$result[] = strtolower($key);
		}
		
	}
	
sort($result);
return array('asset_list' => $result);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function conversion_list_internal_api() {

global $ocpt_conf;

$result = array();

	foreach ( $ocpt_conf['power_user']['btc_currency_markets'] as $key => $unused ) {
	$result[] = $key;
	}
	
sort($result);
return array('conversion_list' => $result);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function btc_market($input) {

global $ocpt_conf;

	$pairing_loop = 0;
	foreach ( $ocpt_conf['assets']['BTC']['pairing'][$ocpt_conf['general']['btc_prim_curr_pairing']] as $market_key => $market_id ) {
		
		// If a numeric id, return the exchange name
		if ( is_int($input) && $pairing_loop == $input ) {
		return $market_key;
		}
		// If an exchange name (alphnumeric with possible underscores), return the numeric id (used in UI html forms)
		elseif ( preg_match("/^[A-Za-z0-9_]+$/", $input) && $market_key == $input ) {
		return $pairing_loop + 1;
		}
	$pairing_loop = $pairing_loop + 1;
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function exchange_list_internal_api() {

global $ocpt_conf;

$result = array();

	foreach ( $ocpt_conf['assets'] as $asset_key => $unused ) {

		foreach ( $ocpt_conf['assets'][$asset_key]['pairing'] as $pairing_key => $unused ) {
					
			foreach ( $ocpt_conf['assets'][$asset_key]['pairing'][$pairing_key] as $exchange_key => $unused ) {
					
				if( !in_array(strtolower($exchange_key), $result) && !preg_match("/misc_assets/i", $exchange_key) ) {
				//$all_exchange_count = $all_exchange_count + 1;
				$result[] = strtolower($exchange_key);
				}
			
			}
				
		}
	
	}

sort($result);
return array('exchange_list' => $result);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function market_list_internal_api($exchange) {

global $ocpt_conf, $remote_ip;

$exchange = strtolower($exchange);

$result = array();

	foreach( $ocpt_conf['assets'] as $asset_key => $asset_value ) {
	
		foreach( $asset_value['pairing'] as $market_pairing_key => $market_pairing_value ) {
			
			foreach( $market_pairing_value as $exchange_key => $unused ) {
				
				if ( $exchange_key == $exchange ) {
				$result[] = $exchange_key . '-' . strtolower($asset_key) . '-' . $market_pairing_key;
				}
				
			}
			
		}
	
	}
	
	sort($result);
	
	
	if ( !$exchange ) {
	app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing parameter: exchange)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
	return array('error' => 'Missing parameter: [exchange]; ');
	}
	if ( sizeof($result) < 1 ) {
	app_logging('int_api_error', 'From ' . $remote_ip . ' (No markets found for exchange: ' . $exchange . ')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
	return array('error' => 'No markets found for exchange: ' . $exchange);
	}
	else {
	
	return array(
					'market_list' => array($exchange => $result)
					);
	
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function defi_pools_info($pairing_array, $pool_address=null) {

global $ocpt_conf, $ocpt_cache;


	if ( $ocpt_conf['power_user']['defi_liquidity_pools_sort_by'] == 'volume' ) {
	$sort_by = 'usdVolume';
	}
	elseif ( $ocpt_conf['power_user']['defi_liquidity_pools_sort_by'] == 'liquidity' ) {
	$sort_by = 'usdLiquidity';
	}

   
   if ( $pool_address ) {
   $json_string = 'https://data-api.defipulse.com/api/v1/blocklytics/pools/v1/exchange/'.$pool_address.'?api-key=' . $ocpt_conf['general']['defipulse_key'];
   }
   else {
   $json_string = 'https://data-api.defipulse.com/api/v1/blocklytics/pools/v1/exchanges?limit=' . $ocpt_conf['power_user']['defi_liquidity_pools_max'] . '&orderBy='.$sort_by.'&direction=desc&api-key=' . $ocpt_conf['general']['defipulse_key'];
   }


$jsondata = @$ocpt_cache->ext_data('url', $json_string, $ocpt_conf['power_user']['defi_pools_info_cache_time']); // Re-cache exchanges => addresses data, etc
     
$data = json_decode($jsondata, true);


	if ( $pool_address ) {
	$new_data = array($data);
	$data = $new_data;
	}
	else {
	$data = $data['results'];
	}

  
      if ( is_array($data) ) {
  			
       	foreach ($data as $key => $value) {
       			
         		foreach ( $value['assets'] as $asset ) {
         			
         			// Check for main asset
         			if ( $asset['symbol'] == $pairing_array[0] || preg_match("/([a-z]{1})".$pairing_array[0]."/", $asset['symbol']) ) {
         			$debug_asset = $asset['symbol'];
         			$is_asset = true;
         			}
         			// Check for pairing asset
         			elseif ( $asset['symbol'] == $pairing_array[1] || preg_match("/([a-z]{1})".$pairing_array[1]."/", $asset['symbol']) ) {
         			$debug_pairing = $asset['symbol'];
         			$is_pairing = true;
         			}
         			
         			
         			if ( !$done && $is_asset && $is_pairing ) {
         			
         			$done = true;
         			$result['platform'] = $value['platform'];
         			$result['pool_name'] = $value['poolName'];
         			$result['pool_address'] = $value['exchange'];
         			$result['pool_assets'] = $value['assets'];
         			$result['pool_usd_volume'] = $value['usdVolume'];
         			
         				if ( $result['pool_usd_volume'] < 1 ) {
  							app_logging('market_error', 'No 24 hour trade volume for DeFi liquidity pool at address ' . $result['pool_address'] . ' (' . $pairing_array[0] . '/' . $pairing_array[1] . ')');
         				}
       			
         			}
         			
         		}
     		
         $is_asset = false;
         $is_pairing = false;
       	}
      
      }
 

 
return $result;
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function marketcap_data($symbol, $force_currency=null) {
	
global $ocpt_conf, $ocpt_var, $ocpt_api, $alert_percent, $coinmarketcap_currencies, $cap_data_force_usd, $cmc_notes, $coingecko_api, $coinmarketcap_api;

$symbol = strtolower($symbol);

$data = array();


	if ( $ocpt_conf['general']['prim_mcap_site'] == 'coingecko' ) {
	
		
		// Check for currency support, fallback to USD if needed
		if ( $force_currency != null ) {
			
		$app_notice = 'Forcing '.strtoupper($force_currency).' stats.';
		
		$coingecko_api_no_overwrite = $ocpt_api->coingecko($force_currency);
			
			// Overwrite previous app notice and unset force usd flag, if this appears to be a data error rather than an unsupported language
			if ( !isset($coingecko_api_no_overwrite['btc']['market_cap_rank']) ) {
			$app_notice = 'Coingecko.com API data error, check the error logs for more information.';
			}
		
		}
		elseif ( !isset($coingecko_api['btc']['market_cap_rank']) && strtoupper($ocpt_conf['general']['btc_prim_curr_pairing']) != 'USD' ) {
			
		$app_notice = 'Coingecko.com does not seem to support '.strtoupper($ocpt_conf['general']['btc_prim_curr_pairing']).' stats,<br />showing USD stats instead.';
		
		$cap_data_force_usd = 1;
		
		$coingecko_api = $ocpt_api->coingecko('usd');
			
			// Overwrite previous app notice and unset force usd flag, if this appears to be a data error rather than an unsupported language
			if ( !isset($coingecko_api['btc']['market_cap_rank']) ) {
			$cap_data_force_usd = null;
			$app_notice = 'Coingecko.com API data error, check the error logs for more information.';
			}
		
		}
		elseif ( $cap_data_force_usd == 1 ) {
		$app_notice = 'Coingecko.com does not seem to support '.strtoupper($ocpt_conf['general']['btc_prim_curr_pairing']).' stats,<br />showing USD stats instead.';
		}
	
	
	// Marketcap data
	$marketcap_data = ( $coingecko_api_no_overwrite ? $coingecko_api_no_overwrite : $coingecko_api );
	
		
	$data['rank'] = $marketcap_data[$symbol]['market_cap_rank'];
	$data['price'] = $marketcap_data[$symbol]['current_price'];
	$data['market_cap'] = round( $ocpt_var->rem_num_format($marketcap_data[$symbol]['market_cap']) );
	
		if ( $ocpt_var->rem_num_format($marketcap_data[$symbol]['total_supply']) > $ocpt_var->rem_num_format($marketcap_data[$symbol]['circulating_supply']) ) {
		$data['market_cap_total'] = round( $ocpt_var->rem_num_format($marketcap_data[$symbol]['current_price']) * $ocpt_var->rem_num_format($marketcap_data[$symbol]['total_supply']) );
		}
		
	$data['volume_24h'] = $marketcap_data[$symbol]['total_volume'];
	
	$data['percent_change_1h'] = number_format( $marketcap_data[$symbol]['price_change_percentage_1h_in_currency'] , 2, ".", ",");
	$data['percent_change_24h'] = number_format( $marketcap_data[$symbol]['price_change_percentage_24h_in_currency'] , 2, ".", ",");
	$data['percent_change_7d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_7d_in_currency'] , 2, ".", ",");
	
	$data['circulating_supply'] = $marketcap_data[$symbol]['circulating_supply'];
	$data['total_supply'] = $marketcap_data[$symbol]['total_supply'];
	$data['max_supply'] = null;
	
	$data['last_updated'] = strtotime( $marketcap_data[$symbol]['last_updated'] );
	
	$data['app_notice'] = $app_notice;
	
	// Coingecko-only
	$data['percent_change_14d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_14d_in_currency'] , 2, ".", ",");
	$data['percent_change_30d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_30d_in_currency'] , 2, ".", ",");
	$data['percent_change_60d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_60d_in_currency'] , 2, ".", ",");
	$data['percent_change_200d'] = number_format( $marketcap_data[$symbol]['price_change_percentage_200d_in_currency'] , 2, ".", ",");
	$data['percent_change_1y'] = number_format( $marketcap_data[$symbol]['price_change_percentage_1y_in_currency'] , 2, ".", ",");
	
	}
	elseif ( $ocpt_conf['general']['prim_mcap_site'] == 'coinmarketcap' ) {

	// Don't overwrite global
	$coinmarketcap_prim_curr = strtoupper($ocpt_conf['general']['btc_prim_curr_pairing']);
	
	
		// Default to USD, if selected primary currency is not supported
		if ( $force_currency != null ) {
		$app_notice .= ' Forcing '.strtoupper($force_currency).' stats. ';
		$coinmarketcap_api_no_overwrite = $ocpt_api->coinmarketcap($force_currency);
		}
		elseif ( isset($cap_data_force_usd) ) {
		$coinmarketcap_prim_curr = 'USD';
		}
		
		
		if ( isset($cmc_notes) ) {
		$app_notice .= $cmc_notes;
		}
		
	
	// Marketcap data
	$marketcap_data = ( $coinmarketcap_api_no_overwrite ? $coinmarketcap_api_no_overwrite : $coinmarketcap_api );
		
		
	$data['rank'] = $marketcap_data[$symbol]['cmc_rank'];
	$data['price'] = $marketcap_data[$symbol]['quote'][$coinmarketcap_prim_curr]['price'];
	$data['market_cap'] = round( $ocpt_var->rem_num_format($marketcap_data[$symbol]['quote'][$coinmarketcap_prim_curr]['market_cap']) );
	
		if ( $ocpt_var->rem_num_format($marketcap_data[$symbol]['total_supply']) > $ocpt_var->rem_num_format($marketcap_data[$symbol]['circulating_supply']) ) {
		$data['market_cap_total'] = round( $ocpt_var->rem_num_format($marketcap_data[$symbol]['quote'][$coinmarketcap_prim_curr]['price']) * $ocpt_var->rem_num_format($marketcap_data[$symbol]['total_supply']) );
		}
		
	$data['volume_24h'] = $marketcap_data[$symbol]['quote'][$coinmarketcap_prim_curr]['volume_24h'];
	
	$data['percent_change_1h'] = number_format( $marketcap_data[$symbol]['quote'][$coinmarketcap_prim_curr]['percent_change_1h'] , 2, ".", ",");
	$data['percent_change_24h'] = number_format( $marketcap_data[$symbol]['quote'][$coinmarketcap_prim_curr]['percent_change_24h'] , 2, ".", ",");
	$data['percent_change_7d'] = number_format( $marketcap_data[$symbol]['quote'][$coinmarketcap_prim_curr]['percent_change_7d'] , 2, ".", ",");
	
	$data['circulating_supply'] = $marketcap_data[$symbol]['circulating_supply'];
	$data['total_supply'] = $marketcap_data[$symbol]['total_supply'];
	$data['max_supply'] = $marketcap_data[$symbol]['max_supply'];
	
	$data['last_updated'] = strtotime( $marketcap_data[$symbol]['last_updated'] );
	
	$data['app_notice'] = $app_notice;
	
	}
 	
	
	// UX on number values
	$data['price'] = ( $ocpt_var->num_to_str($data['price']) >= $ocpt_conf['general']['prim_curr_dec_max_thres'] ? $ocpt_var->num_pretty($data['price'], 2) : $ocpt_var->num_pretty($data['price'], $ocpt_conf['general']['prim_curr_dec_max']) );
	

// Return null if we don't even detect a rank
return ( $data['rank'] != NULL ? $data : NULL );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function prim_curr_trade_volume($asset_symbol, $pairing, $last_trade, $vol_in_pairing) {

global $ocpt_conf, $selected_btc_prim_curr_value;
	
	
	// Return negative number, if no volume data detected (so we know when data errors happen)
	if ( is_numeric($vol_in_pairing) != true ) {
	return -1;
	}
	// If no pairing data, skip calculating trade volume to save on uneeded overhead
	elseif ( !$asset_symbol || !$pairing || !isset($last_trade) || $last_trade == 0 ) {
	return false;
	}


	// WE NEED TO SET THIS (ONLY IF NOT SET ALREADY) for $ocpt_api->market() calls, 
	// because it is not set as a global THE FIRST RUNTIME CALL TO $ocpt_api->market()
	if ( strtoupper($asset_symbol) == 'BTC' && !$selected_btc_prim_curr_value ) {
	$temp_btc_prim_curr_value = $last_trade; // Don't overwrite global
	}
	else {
	$temp_btc_prim_curr_value = $selected_btc_prim_curr_value; // Don't overwrite global
	}


	// Get primary currency volume value	
	// Currency volume from Bitcoin's DEFAULT PAIRING volume
	if ( $pairing == $ocpt_conf['general']['btc_prim_curr_pairing'] ) {
	$volume_prim_curr_raw = number_format( $vol_in_pairing , 0, '.', '');
	}
	// Currency volume from btc PAIRING volume
	elseif ( $pairing == 'btc' ) {
	$volume_prim_curr_raw = number_format( $temp_btc_prim_curr_value * $vol_in_pairing , 0, '.', '');
	}
	// Currency volume from other PAIRING volume
	else { 
	
	$pairing_btc_value = pairing_btc_value($pairing);

		if ( $pairing_btc_value == null ) {
		app_logging('market_error', 'pairing_btc_value() returned null in prim_curr_trade_volume()', 'pairing: ' . $pairing);
		}
	
	$volume_prim_curr_raw = number_format( $temp_btc_prim_curr_value * ( $vol_in_pairing * $pairing_btc_value ) , 0, '.', '');
	
	}
	
	
return $volume_prim_curr_raw;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function market_conversion_internal_api($market_conversion, $all_markets_data_array) {

global $ocpt_conf, $ocpt_var, $ocpt_api, $remote_ip, $selected_btc_prim_curr_value;

$result = array();

// Cleanup
$market_conversion = strtolower($market_conversion);
$all_markets_data_array = array_map('trim', $all_markets_data_array);
$all_markets_data_array = array_map('strtolower', $all_markets_data_array);
    
$possible_dos_attack = 0;


	 // Return error message if there are missing parameters
	 if ( $market_conversion != 'market_only' && !$ocpt_conf['power_user']['btc_currency_markets'][$market_conversion] || $all_markets_data_array[0] == '' ) {
			
			if ( $market_conversion == '' ) {
			$result['error'] .= 'Missing parameter: [currency_symbol|market_only]; ';
			app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing parameter: currency_symbol|market_only)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
			}
			elseif ( $market_conversion != 'market_only' && !$ocpt_conf['power_user']['btc_currency_markets'][$market_conversion] ) {
			$result['error'] .= 'Conversion market does not exist: '.$market_conversion.'; ';
			app_logging('int_api_error', 'From ' . $remote_ip . ' (Conversion market does not exist: '.$market_conversion.')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
			}
			
			if ( $all_markets_data_array[0] == '' ) {
			$result['error'] .= 'Missing parameter: [exchange-asset-pairing]; ';
			app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing parameter: exchange-asset-pairing)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
			}
		
	 return $result;
	 
	 }
	 
	 
	 // Return error message if the markets lists is more markets than allowed by $ocpt_conf['developer']['local_api_market_limit']
	 if ( sizeof($all_markets_data_array) > $ocpt_conf['developer']['local_api_market_limit'] ) {
	 $result['error'] = 'Exceeded maximum of ' . $ocpt_conf['developer']['local_api_market_limit'] . ' markets allowed per request (' . sizeof($all_markets_data_array) . ').';
	 app_logging('int_api_error', 'From ' . $remote_ip . ' (Exceeded maximum markets allowed per request)', 'markets_requested: ' . sizeof($all_markets_data_array) . '; uri: ' . $_SERVER['REQUEST_URI'] . ';');
	 return $result;
	 }


    // Loop through each set of market data
    foreach( $all_markets_data_array as $market_data ) {

    	
    	  // Stop processing output and return an error message, if this is a possible dos attack
    	  if ( $possible_dos_attack > 5 ) {
    	  $result = array(); // reset for no output other than error notice
    	  $result['error'] = 'Too many non-existent markets requested.';
		  app_logging('int_api_error', 'From ' . $remote_ip . ' (Too many non-existent markets requested)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
    	  return $result;
    	  }
    	  
    
    
    $market_data_array = explode("-", $market_data); // Market data array
                
    $exchange = $market_data_array[0];
        
    $asset = $market_data_array[1];
        
    $market_pairing = $market_data_array[2];
        
    $pairing_id = $ocpt_conf['assets'][strtoupper($asset)]['pairing'][$market_pairing][$exchange];
        
    
    
    	  // If market exists, get latest data
        if ( $pairing_id != '' ) {
        
              
              
              // GET BTC MARKET CONVERSION VALUE #BEFORE ANYTHING ELSE#, OR WE WON'T GET PROPER VOLUME IN CURRENCY ETC
              // IF NOT SET YET, get bitcoin market data (if we are getting converted fiat currency values)
              if ( $market_conversion != 'market_only' && !isset($btc_exchange) && !isset($market_conversion_btc_value) ) {
              
              	
              		  // If a preferred bitcoin market is set in app config, use it...otherwise use first array key
              		  if ( isset($ocpt_conf['power_user']['btc_pref_currency_markets'][$market_conversion]) ) {
              		  $btc_exchange = $ocpt_conf['power_user']['btc_pref_currency_markets'][$market_conversion];
						  }
						  else {
						  $btc_exchange = key($ocpt_conf['assets']['BTC']['pairing'][$market_conversion]);
						  }
                
                
              $btc_pairing_id = $ocpt_conf['assets']['BTC']['pairing'][$market_conversion][$btc_exchange];
              
              $market_conversion_btc_value = $ocpt_api->market('BTC', $btc_exchange, $btc_pairing_id)['last_trade'];
              
              		  
              		  // FAILSAFE: If the exchange market is DOES NOT RETURN a value, 
              		  // move the internal array pointer one forward, until we've tried all exchanges for this btc pairing
              		  $switch_exchange = true;
              		  while ( !isset($market_conversion_btc_value) && $switch_exchange != false || $ocpt_var->num_to_str($market_conversion_btc_value) < 0.00000001 && $switch_exchange != false ) {
              		  	
              		  $switch_exchange = next($ocpt_conf['assets']['BTC']['pairing'][$market_conversion]);
              		  
              		  		if ( $switch_exchange != false ) {
              		  			
              		  		$btc_exchange = key($ocpt_conf['assets']['BTC']['pairing'][$market_conversion]);
              		  		
              		  		$btc_pairing_id = $ocpt_conf['assets']['BTC']['pairing'][$market_conversion][$btc_exchange];
              
              		  		$market_conversion_btc_value = $ocpt_api->market('BTC', $btc_exchange, $btc_pairing_id)['last_trade'];
              		  
              		  		}
              
              		  }
        
        		  
              // OVERWRITE SELECTED BITCOIN CURRENCY MARKET GLOBALS
              $ocpt_conf['general']['btc_prim_curr_pairing'] = $market_conversion;
    			  $ocpt_conf['general']['btc_prim_exchange'] = $btc_exchange;
              
        		  // OVERWRITE #GLOBAL# BTC PRIMARY CURRENCY VALUE (so we get correct values for volume in currency etc)
        		  $selected_btc_prim_curr_value = $market_conversion_btc_value;
        		  
              }
              
                
                
        $asset_market_data = $ocpt_api->market(strtoupper($asset), $exchange, $pairing_id, $market_pairing);
        
        $coin_value_raw = $asset_market_data['last_trade'];
        
        // Pretty numbers
        $coin_value_raw = $ocpt_var->num_to_str($coin_value_raw);
        
        // If no pair volume is available for this market, emulate it within reason with: asset value * asset volume
        $volume_pairing_raw = $ocpt_var->num_to_str($asset_market_data['24hr_pairing_volume']);
        
        
        
              // More pretty numbers formatting
              if ( array_key_exists($market_pairing, $ocpt_conf['power_user']['btc_currency_markets']) ) {
              $coin_value_raw = ( $ocpt_var->num_to_str($coin_value_raw) >= $ocpt_conf['general']['prim_curr_dec_max_thres'] ? round($coin_value_raw, 2) : round($coin_value_raw, $ocpt_conf['general']['prim_curr_dec_max']) );
              $volume_pairing_rounded = round($volume_pairing_raw);
              }
              else {
              $volume_pairing_rounded = round($volume_pairing_raw, 3);
              }
              
              
              
              // Get converted fiat currency values if requested
              if ( $market_conversion != 'market_only' ) {
              
        				  // Value in fiat currency
                    if ( $market_pairing == 'btc' ) {
                    $coin_prim_market_worth_raw = $coin_value_raw * $market_conversion_btc_value;
                    }
                    else {
                    $pairing_btc_value = pairing_btc_value($market_pairing);
                    		if ( $pairing_btc_value == null ) {
                    		app_logging('market_error', 'pairing_btc_value() returned null in market_conversion_internal_api()', 'pairing: ' . $market_pairing);
                    		}
                    $coin_prim_market_worth_raw = ($coin_value_raw * $pairing_btc_value) * $market_conversion_btc_value;
                    }
              
              // Pretty numbers for fiat currency
              $coin_prim_market_worth_raw = ( $ocpt_var->num_to_str($coin_prim_market_worth_raw) >= $ocpt_conf['general']['prim_curr_dec_max_thres'] ? round($coin_prim_market_worth_raw, 2) : round($coin_prim_market_worth_raw, $ocpt_conf['general']['prim_curr_dec_max']) );
              
              }
        
        
        
              // Results
              if ( $market_conversion != $market_pairing && $market_conversion != 'market_only' ) {
              
              // Flag we are doing a price conversion
              $price_conversion = 1;
                
              $result['market_conversion'][$market_data] = array(
                                                        						'market' => array( $market_pairing => array('spot_price' => $coin_value_raw, '24hr_volume' => $volume_pairing_rounded) ),
                                                        						'conversion' => array( $market_conversion => array('spot_price' => $coin_prim_market_worth_raw, '24hr_volume' => round($asset_market_data['24hr_prim_curr_volume']) ) )
                                                    							);
                                                                            
              }
              else {
                
              $result['market_conversion'][$market_data] = array(
                                                        						'market' => array( $market_pairing => array('spot_price' => $coin_value_raw, '24hr_volume' => $volume_pairing_rounded) )
                                                    							);
                                                    
              }
        
        
        
        }
        elseif ( sizeof($market_data_array) < 3 ) {
        $result['market_conversion'][$market_data] = array('error' => "Missing all 3 REQUIRED sub-parameters: [exchange-asset-pairing]");
		  app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing all 3 REQUIRED sub-parameters: exchange-asset-pairing)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
        $possible_dos_attack = $possible_dos_attack + 1;
        }
        elseif ( $pairing_id == '' ) {
        $result['market_conversion'][$market_data] = array('error' => "Market does not exist: [" . $exchange . "-" . $asset . "-" . $market_pairing . "]");
		  app_logging('int_api_error', 'From ' . $remote_ip . ' (Market does not exist: ' . $exchange . "-" . $asset . "-" . $market_pairing . ')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
        $possible_dos_attack = $possible_dos_attack + 1;
        }
    
    
    }



	 // If we did a price conversion, show market used
	 if ( $market_conversion != 'market_only' && $price_conversion == 1 ) {
	 
	 // Reset internal array pointer
    reset($ocpt_conf['assets']['BTC']['pairing'][$market_conversion]);
    
	 $result['market_conversion_source'] = $btc_exchange . '-btc-' . $market_conversion;
	 
	 }



return $result;


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function pairing_btc_value($pairing) {

global $ocpt_conf, $ocpt_var, $ocpt_api, $btc_pairing_markets, $btc_pairing_markets_excluded;

$pairing = strtolower($pairing);


	// Safeguard / cut down on runtime
	if ( $pairing == null ) {
	return null;
	}
	// If BTC
	elseif ( $pairing == 'btc' ) {
	return 1;
	}
	// If session value exists
	elseif ( isset($btc_pairing_markets[$pairing.'_btc']) ) {
	return $btc_pairing_markets[$pairing.'_btc'];
	}
	// If we need an ALTCOIN/BTC market value (RUN BEFORE CURRENCIES FOR BEST MARKET DATA, AS SOME CRYPTOS ARE INCLUDED IN BOTH)
	elseif ( array_key_exists($pairing, $ocpt_conf['power_user']['crypto_pairing']) ) {
		
		
		// Include a basic array check, since we want valid data to avoid an endless loop in our fallback support
		if ( !is_array($ocpt_conf['assets'][strtoupper($pairing)]['pairing']['btc']) ) {
   	app_logging('market_error', 'pairing_btc_value() - market failure (unknown pairing) for ' . $pairing);
		return null;
		}
		// Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
		elseif ( sizeof($ocpt_conf['assets'][strtoupper($pairing)]['pairing']['btc']) > 1 && array_key_exists($pairing, $ocpt_conf['power_user']['crypto_pairing_pref_markets']) ) {
		$market_override = $ocpt_conf['power_user']['crypto_pairing_pref_markets'][$pairing];
		}
	
	
		// Loop until we find a market override / non-excluded pairing market
		foreach ( $ocpt_conf['assets'][strtoupper($pairing)]['pairing']['btc'] as $market_key => $market_value ) {
					
					
			if ( isset($market_override) && $market_override == $market_key && !in_array($market_key, $btc_pairing_markets_excluded[$pairing])
			|| isset($market_override) && $market_override != $market_key && in_array($market_override, $btc_pairing_markets_excluded[$pairing]) && !in_array($market_key, $btc_pairing_markets_excluded[$pairing])
			|| !isset($market_override) && !in_array($market_key, $btc_pairing_markets_excluded[$pairing]) ) {
				
   		$btc_pairing_markets[$pairing.'_btc'] = $ocpt_api->market(strtoupper($pairing), $market_key, $market_value)['last_trade'];
   		
   			// Fallback support IF THIS IS A FUTURES MARKET (we want a normal / current value), OR no data returned
   			if ( stristr($market_key, 'bitmex_') == false && $ocpt_var->num_to_str($btc_pairing_markets[$pairing.'_btc']) >= 0.00000001 ) {
   				
   				// Data debugging telemetry
					if ( $ocpt_conf['developer']['debug_mode'] == 'all' || $ocpt_conf['developer']['debug_mode'] == 'all_telemetry' ) {
					app_logging('market_debugging', 'pairing_btc_value() market request succeeded for ' . $pairing, 'exchange: ' . $market_key);
					}		
   					
   			return $ocpt_var->num_to_str($btc_pairing_markets[$pairing.'_btc']);
   			
   			}
   			// ONLY LOG AN ERROR IF ALL AVAILABLE MARKETS FAIL (AND RETURN NULL)
   			// We only want to loop a fallback for the amount of available markets
   			elseif ( sizeof($btc_pairing_markets_excluded[$pairing]) == sizeof($ocpt_conf['assets'][strtoupper($pairing)]['pairing']['btc']) ) {
   			app_logging('market_error', 'pairing_btc_value() - market request failure (all '.sizeof($btc_pairing_markets_excluded[$pairing]).' markets failed) for ' . $pairing . ' / btc (' . $market_key . ')', $pairing . '_markets_excluded_count: ' . sizeof($btc_pairing_markets_excluded[$pairing]) );
   			return null;
   			}
   			else {
   			$btc_pairing_markets[$pairing.'_btc'] = null; // Reset
   			$btc_pairing_markets_excluded[$pairing][] = $market_key; // Market exclusion list, getting pairing data from this exchange IN ANY PAIRING, for this runtime only
   			return pairing_btc_value($pairing);
   			}
   		
			}
			
			
		}
		return null; // If we made it this deep in the logic, no data was found	
	
	}
	// If we need a BITCOIN/CURRENCY market value 
	// RUN AFTER CRYPTO MARKETS...WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
	elseif ( array_key_exists($pairing, $ocpt_conf['power_user']['btc_currency_markets']) ) {
	
	
		// Include a basic array check, since we want valid data to avoid an endless loop in our fallback support
		if ( !is_array($ocpt_conf['assets']['BTC']['pairing'][$pairing]) ) {
   	app_logging('market_error', 'pairing_btc_value() - market failure (unknown pairing) for ' . $pairing);
		return null;
		}
		// Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
		elseif ( sizeof($ocpt_conf['assets']['BTC']['pairing'][$pairing]) > 1 && array_key_exists($pairing, $ocpt_conf['power_user']['btc_pref_currency_markets']) ) {
		$market_override = $ocpt_conf['power_user']['btc_pref_currency_markets'][$pairing];
		}
				
				
		// Loop until we find a market override / non-excluded pairing market
		foreach ( $ocpt_conf['assets']['BTC']['pairing'][$pairing] as $market_key => $market_value ) {
					
					
			if ( isset($market_override) && $market_override == $market_key && !in_array($market_key, $btc_pairing_markets_excluded[$pairing])
			|| isset($market_override) && $market_override != $market_key && in_array($market_override, $btc_pairing_markets_excluded[$pairing]) && !in_array($market_key, $btc_pairing_markets_excluded[$pairing])
			|| !isset($market_override) && !in_array($market_key, $btc_pairing_markets_excluded[$pairing]) ) {
						
   		$btc_pairing_markets[$pairing.'_btc'] = ( 1 / $ocpt_api->market(strtoupper($pairing), $market_key, $market_value)['last_trade'] );
   					
   			// Fallback support IF THIS IS A FUTURES MARKET (we want a normal / current value), OR no data returned
   			if ( stristr($market_key, 'bitmex_') == false && $ocpt_var->num_to_str($btc_pairing_markets[$pairing.'_btc']) >= 0.0000000000000000000000001 ) { // FUTURE-PROOF FIAT ROUNDING WITH 25 DECIMALS, IN CASE BITCOIN MOONS HARD
   						
   				// Data debugging telemetry
					if ( $ocpt_conf['developer']['debug_mode'] == 'all' || $ocpt_conf['developer']['debug_mode'] == 'all_telemetry' ) {
					app_logging('market_debugging', 'pairing_btc_value() market request succeeded for ' . $pairing, 'exchange: ' . $market_key);
					}
							
   			return $ocpt_var->num_to_str($btc_pairing_markets[$pairing.'_btc']);
   					
   			}
   			// ONLY LOG AN ERROR IF ALL AVAILABLE MARKETS FAIL (AND RETURN NULL)
   			// We only want to loop a fallback for the amount of available markets
   			elseif ( sizeof($btc_pairing_markets_excluded[$pairing]) >= sizeof($ocpt_conf['assets']['BTC']['pairing'][$pairing]) ) {
   			app_logging('market_error', 'pairing_btc_value() - market request failure (all '.sizeof($btc_pairing_markets_excluded[$pairing]).' markets failed) for btc / ' . $pairing . ' (' . $market_key . ')', $pairing . '_markets_excluded_count: ' . sizeof($btc_pairing_markets_excluded[$pairing]) );
   			return null;
   			}
   			else {
   			$btc_pairing_markets[$pairing.'_btc'] = null; // Reset	
   			$btc_pairing_markets_excluded[$pairing][] = $market_key; // Market exclusion list, getting pairing data from this exchange IN ANY PAIRING, for this runtime only
   			return pairing_btc_value($pairing);
   			}
   		
   				
			}
					
						
		}
		return null; // If we made it this deep in the logic, no data was found	
   		
		
	}
   else {
   return null; // If we made it this deep in the logic, no data was found
   }
   
   
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function hivepower_time($time) {
    
global $_POST, $hive_market, $ocpt_conf, $selected_btc_prim_curr_value;

$powertime = null;
$powertime = null;
$hive_total = null;
$prim_curr_total = null;

$decimal_yearly_interest = $ocpt_conf['power_user']['hivepower_yearly_interest'] / 100;  // Convert APR in config to decimal representation

$speed = ($_POST['hp_total'] * $decimal_yearly_interest) / 525600;  // Interest per minute

    if ( $time == 'day' ) {
    $powertime = ($speed * 60 * 24);
    }
    elseif ( $time == 'week' ) {
    $powertime = ($speed * 60 * 24 * 7);
    }
    elseif ( $time == 'month' ) {
    $powertime = ($speed * 60 * 24 * 30);
    }
    elseif ( $time == '2month' ) {
    $powertime = ($speed * 60 * 24 * 60);
    }
    elseif ( $time == '3month' ) {
    $powertime = ($speed * 60 * 24 * 90);
    }
    elseif ( $time == '6month' ) {
    $powertime = ($speed * 60 * 24 * 180);
    }
    elseif ( $time == '9month' ) {
    $powertime = ($speed * 60 * 24 * 270);
    }
    elseif ( $time == '12month' ) {
    $powertime = ($speed * 60 * 24 * 365);
    }
    
    $powertime_prim_curr = ( $powertime * $hive_market * $selected_btc_prim_curr_value );
    
    $hive_total = ( $powertime + $_POST['hp_total'] );
    $prim_curr_total = ( $hive_total * $hive_market * $selected_btc_prim_curr_value );
    
    $power_purchased = ( $_POST['hp_purchased'] / $hive_total );
    $power_earned = ( $_POST['hp_earned'] / $hive_total );
    $power_interest = 1 - ( $power_purchased + $power_earned );
    
    $powerdown_total = ( $hive_total / $ocpt_conf['power_user']['hive_powerdown_time'] );
    $powerdown_purchased = ( $powerdown_total * $power_purchased );
    $powerdown_earned = ( $powerdown_total * $power_earned );
    $powerdown_interest = ( $powerdown_total * $power_interest );
    
    ?>
    
<div class='result'>
    <h2> Interest Per <?=ucfirst($time)?> </h2>
    <ul>
        
        <li><b><?=number_format( $powertime, 3, '.', ',')?> HIVE</b> <i>in interest</i> (after a <?=$time?> time period) = <b><?=$ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']]?><?=number_format( $powertime_prim_curr, 2, '.', ',')?></b></li>
        
        <li><b><?=number_format( $hive_total, 3, '.', ',')?> HIVE</b> <i>in total</i> (including original vested amount) = <b><?=$ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']]?><?=number_format( $prim_curr_total, 2, '.', ',')?></b></li>
    
    </ul>

  <p><b>A Power Down Weekly Payout <i>Started At This Time</i> Would Be (rounded to nearest cent):</b></p>
        <table border='1' cellpadding='10' cellspacing='0'>
            <tr>
        <th class='normal'> Purchased </th>
        <th class='normal'> Earned </th>
        <th class='normal'> Interest </th>
        <th> Total </th>
            </tr>
                <tr>

                <td> <?=number_format( $powerdown_purchased, 3, '.', ',')?> HIVE = <?=$ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']]?><?=number_format( powerdown_prim_curr($powerdown_purchased), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_earned, 3, '.', ',')?> HIVE = <?=$ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']]?><?=number_format( powerdown_prim_curr($powerdown_earned), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_interest, 3, '.', ',')?> HIVE = <?=$ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']]?><?=number_format( powerdown_prim_curr($powerdown_interest), 2, '.', ',')?> </td>
                <td> <b><?=number_format( $powerdown_total, 3, '.', ',')?> HIVE</b> = <b><?=$ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']]?><?=number_format( powerdown_prim_curr($powerdown_total), 2, '.', ',')?></b> </td>

                </tr>
           
        </table>     
        
</div>

    <?php
    
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function mining_calc_form($calculation_form_data, $network_measure, $hash_unit='hash') {

global $_POST, $ocpt_conf;

?>

				<form name='<?=$calculation_form_data['symbol']?>' action='<?=start_page('mining')?>' method='post'>
				
				
				<p><b><?=ucfirst($network_measure)?>:</b> 
				<?php
				if ( $hash_unit == 'hash' ) {
				?>
				
				<input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calculation_form_data['difficulty']) )?>' name='network_measure' /> 
				
				<?php
				}
				?>
				</p>
				
				
				<p><b>Your Hashrate:</b>  
				<input type='text' value='<?=( $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['your_hashrate'] : '' )?>' name='your_hashrate' /> 
				
				
				
				<?php
				if ( $hash_unit == 'hash' ) {
				?>
				<select class='browser-default custom-select' name='hash_level'>
				<option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Hs (hashes per second) </option>
				<option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Khs (thousand hashes per second) </option>
				<option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Mhs (million hashes per second) </option>
				<option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ghs (billion hashes per second) </option>
				<option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ths (trillion hashes per second) </option>
				<option value='1000000000000000' <?=( $_POST['hash_level'] == '1000000000000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Phs (quadrillion hashes per second) </option>
				<option value='1000000000000000000' <?=( $_POST['hash_level'] == '1000000000000000000' && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ehs (quintillion hashes per second) </option>
				</select>
				
				<?php
				}
				?>
				
				
				</p>
				
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['block_reward'] && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['block_reward'] : $calculation_form_data['block_reward'] )?>' name='block_reward' /> (MAY be static from Power User Config, verify manually)</p>
				
				
				<p><b>Watts Used:</b> <input type='text' value='<?=( isset($_POST['watts_used']) && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['watts_used'] : '300' )?>' name='watts_used' /></p>
				
				
				<p><b>kWh Rate (<?=$ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']]?>/kWh):</b> <input type='text' value='<?=( isset($_POST['watts_rate']) && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['watts_rate'] : '0.1000' )?>' name='watts_rate' /></p>
				
				
				<p><b>Pool Fee:</b> <input type='text' value='<?=( isset($_POST['pool_fee']) && $_POST[$calculation_form_data['symbol'].'_submitted'] == 1 ? $_POST['pool_fee'] : '1' )?>' size='4' name='pool_fee' />%</p>
				    
				    
			   <input type='hidden' value='1' name='<?=$calculation_form_data['symbol']?>_submitted' />
				    
			   <input type='hidden' value='<?=$calculation_form_data['symbol']?>' name='pow_calc' />
				
				<input type='submit' value='Calculate <?=strtoupper($calculation_form_data['symbol'])?> Mining Profit' />
	
				</form>
				

<?php
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>
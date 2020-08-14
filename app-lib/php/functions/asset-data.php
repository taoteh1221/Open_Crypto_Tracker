<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function monero_reward() {
return monero_api('last_reward') / 1000000000000;
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function powerdown_primary_currency($data) {

global $hive_market, $app_config, $selected_btc_primary_currency_value;

return ( $data * $hive_market * $selected_btc_primary_currency_value );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function get_sub_token_price($chosen_market, $market_pairing) {

global $app_config;

  if ( strtolower($chosen_market) == 'eth_subtokens_ico' ) {
  return $app_config['power_user']['ethereum_subtoken_ico_values'][$market_pairing];
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

global $app_config;

$result = array();

	foreach ( $app_config['portfolio_assets'] as $key => $unused ) {
		
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

global $app_config;

$result = array();

	foreach ( $app_config['power_user']['bitcoin_currency_markets'] as $key => $unused ) {
	$result[] = $key;
	}
	
sort($result);
return array('conversion_list' => $result);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function btc_market($input) {

global $app_config;

	$pairing_loop = 0;
	foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['general']['btc_primary_currency_pairing']] as $market_key => $market_id ) {
		
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

global $app_config;

$result = array();

	foreach ( $app_config['portfolio_assets'] as $asset_key => $unused ) {

		foreach ( $app_config['portfolio_assets'][$asset_key]['market_pairing'] as $pairing_key => $unused ) {
					
			foreach ( $app_config['portfolio_assets'][$asset_key]['market_pairing'][$pairing_key] as $exchange_key => $unused ) {
					
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

global $app_config, $remote_ip;

$exchange = strtolower($exchange);

$result = array();

	foreach( $app_config['portfolio_assets'] as $asset_key => $asset_value ) {
	
		foreach( $asset_value['market_pairing'] as $market_pairing_key => $market_pairing_value ) {
			
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


function marketcap_data($symbol) {
	
global $app_config, $alert_percent, $coinmarketcap_currencies, $cap_data_force_usd, $cmc_notes, $coingecko_api, $coinmarketcap_api;

$symbol = strtolower($symbol);

$data = array();


	if ( $app_config['general']['primary_marketcap_site'] == 'coingecko' ) {
	
		
		// Check for currency support, fallback to USD if needed
		if ( !isset($coingecko_api['btc']['market_cap_rank']) && strtoupper($app_config['general']['btc_primary_currency_pairing']) != 'USD' ) {
			
		$app_notice = 'Coingecko.com does not seem to support '.strtoupper($app_config['general']['btc_primary_currency_pairing']).' stats,<br />showing USD stats instead.';
		
		$cap_data_force_usd = 1;
		
		$coingecko_api = coingecko_api('usd');
			
			// Overwrite previous app notice and unset force usd flag, if this appears to be a data error rather than an unsupported language
			if ( !isset($coingecko_api['btc']['market_cap_rank']) ) {
			$cap_data_force_usd = null;
			$app_notice = 'Coingecko.com API data error, check the error logs for more information.';
			}
		
		}
		elseif ( $cap_data_force_usd == 1 ) {
		$app_notice = 'Coingecko.com does not seem to support '.strtoupper($app_config['general']['btc_primary_currency_pairing']).' stats,<br />showing USD stats instead.';
		}
		
		
	$data['rank'] = $coingecko_api[$symbol]['market_cap_rank'];
	$data['price'] = $coingecko_api[$symbol]['current_price'];
	$data['market_cap'] = $coingecko_api[$symbol]['market_cap'];
	$data['volume_24h'] = $coingecko_api[$symbol]['total_volume'];
	
	$data['percent_change_1h'] = number_format( $coingecko_api[$symbol]['price_change_percentage_1h_in_currency'] , 2, ".", ",");
	$data['percent_change_24h'] = number_format( $coingecko_api[$symbol]['price_change_percentage_24h_in_currency'] , 2, ".", ",");
	$data['percent_change_7d'] = number_format( $coingecko_api[$symbol]['price_change_percentage_7d_in_currency'] , 2, ".", ",");
	
	$data['circulating_supply'] = $coingecko_api[$symbol]['circulating_supply'];
	$data['total_supply'] = $coingecko_api[$symbol]['total_supply'];
	$data['max_supply'] = null;
	
	$data['last_updated'] = strtotime( $coingecko_api[$symbol]['last_updated'] );
	
	$data['app_notice'] = $app_notice;
	
	// Coingecko-only
	$data['percent_change_14d'] = number_format( $coingecko_api[$symbol]['price_change_percentage_14d_in_currency'] , 2, ".", ",");
	$data['percent_change_30d'] = number_format( $coingecko_api[$symbol]['price_change_percentage_30d_in_currency'] , 2, ".", ",");
	$data['percent_change_60d'] = number_format( $coingecko_api[$symbol]['price_change_percentage_60d_in_currency'] , 2, ".", ",");
	$data['percent_change_200d'] = number_format( $coingecko_api[$symbol]['price_change_percentage_200d_in_currency'] , 2, ".", ",");
	$data['percent_change_1y'] = number_format( $coingecko_api[$symbol]['price_change_percentage_1y_in_currency'] , 2, ".", ",");
	
	}
	elseif ( $app_config['general']['primary_marketcap_site'] == 'coinmarketcap' ) {

	// Don't overwrite global
	$coinmarketcap_primary_currency = strtoupper($app_config['general']['btc_primary_currency_pairing']);
	
	
		// Default to USD, if selected primary currency is not supported
		if ( isset($cap_data_force_usd) ) {
		$coinmarketcap_primary_currency = 'USD';
		}
		
		
		if ( isset($cmc_notes) ) {
		$app_notice = $cmc_notes;
		}
		
		
	$data['rank'] = $coinmarketcap_api[$symbol]['cmc_rank'];
	$data['price'] = $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['price'];
	$data['market_cap'] = $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['market_cap'];
	$data['volume_24h'] = $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['volume_24h'];
	
	$data['percent_change_1h'] = number_format( $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_1h'] , 2, ".", ",");
	$data['percent_change_24h'] = number_format( $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_24h'] , 2, ".", ",");
	$data['percent_change_7d'] = number_format( $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_7d'] , 2, ".", ",");
	
	$data['circulating_supply'] = $coinmarketcap_api[$symbol]['circulating_supply'];
	$data['total_supply'] = $coinmarketcap_api[$symbol]['total_supply'];
	$data['max_supply'] = $coinmarketcap_api[$symbol]['max_supply'];
	
	$data['last_updated'] = strtotime( $coinmarketcap_api[$symbol]['last_updated'] );
	
	$data['app_notice'] = $app_notice;
	
	}
 	
	
	// UX on number values
	$data['price'] = ( number_to_string($data['price']) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? pretty_numbers($data['price'], 2) : pretty_numbers($data['price'], $app_config['general']['primary_currency_decimals_max']) );
	

// Return null if we don't even detect a rank
return ( $data['rank'] != NULL ? $data : NULL );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function primary_currency_trade_volume($asset_symbol, $pairing, $last_trade, $vol_in_pairing) {

global $app_config, $selected_btc_primary_currency_value;
	
	
	// Return negative number, if no volume data detected (so we know when data errors happen)
	if ( is_numeric($vol_in_pairing) != true ) {
	return -1;
	}
	// If no pairing data, skip calculating trade volume to save on uneeded overhead
	elseif ( !$asset_symbol || !$pairing || !isset($last_trade) || $last_trade == 0 ) {
	return false;
	}


	// WE NEED TO SET THIS (ONLY IF NOT SET ALREADY) for asset_market_data() calls, 
	// because it is not set as a global THE FIRST RUNTIME CALL TO asset_market_data()
	if ( strtoupper($asset_symbol) == 'BTC' && !$selected_btc_primary_currency_value ) {
	$temp_btc_primary_currency_value = $last_trade; // Don't overwrite global
	}
	else {
	$temp_btc_primary_currency_value = $selected_btc_primary_currency_value; // Don't overwrite global
	}


	// Get primary currency volume value	
	// Currency volume from Bitcoin's DEFAULT PAIRING volume
	if ( $pairing == $app_config['general']['btc_primary_currency_pairing'] ) {
	$volume_primary_currency_raw = number_format( $vol_in_pairing , 0, '.', '');
	}
	// Currency volume from btc PAIRING volume
	elseif ( $pairing == 'btc' ) {
	$volume_primary_currency_raw = number_format( $temp_btc_primary_currency_value * $vol_in_pairing , 0, '.', '');
	}
	// Currency volume from other PAIRING volume
	else { 
	
	$pairing_btc_value = pairing_market_value($pairing);

		if ( $pairing_btc_value == null ) {
		app_logging('market_error', 'pairing_market_value() returned null in primary_currency_trade_volume()', 'pairing: ' . $pairing);
		}
	
	$volume_primary_currency_raw = number_format( $temp_btc_primary_currency_value * ( $vol_in_pairing * $pairing_btc_value ) , 0, '.', '');
	
	}
	
	
return $volume_primary_currency_raw;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function market_conversion_internal_api($market_conversion, $all_markets_data_array) {

global $app_config, $remote_ip, $selected_btc_primary_currency_value;

$result = array();

// Cleanup
$market_conversion = strtolower($market_conversion);
$all_markets_data_array = array_map('trim', $all_markets_data_array);
$all_markets_data_array = array_map('strtolower', $all_markets_data_array);
    
$possible_dos_attack = 0;


	 // Return error message if there are missing parameters
	 if ( $market_conversion != 'market_only' && !$app_config['power_user']['bitcoin_currency_markets'][$market_conversion] || $all_markets_data_array[0] == '' ) {
			
			if ( $market_conversion == '' ) {
			$result['error'] .= 'Missing parameter: [currency_symbol|market_only]; ';
			app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing parameter: currency_symbol|market_only)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
			}
			elseif ( $market_conversion != 'market_only' && !$app_config['power_user']['bitcoin_currency_markets'][$market_conversion] ) {
			$result['error'] .= 'Conversion market does not exist: '.$market_conversion.'; ';
			app_logging('int_api_error', 'From ' . $remote_ip . ' (Conversion market does not exist: '.$market_conversion.')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
			}
			
			if ( $all_markets_data_array[0] == '' ) {
			$result['error'] .= 'Missing parameter: [exchange-asset-pairing]; ';
			app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing parameter: exchange-asset-pairing)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
			}
		
	 return $result;
	 
	 }
	 
	 
	 // Return error message if the markets lists is more markets than allowed by $app_config['developer']['local_api_market_limit']
	 if ( sizeof($all_markets_data_array) > $app_config['developer']['local_api_market_limit'] ) {
	 $result['error'] = 'Exceeded maximum of ' . $app_config['developer']['local_api_market_limit'] . ' markets allowed per request (' . sizeof($all_markets_data_array) . ').';
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
        
    $pairing_id = $app_config['portfolio_assets'][strtoupper($asset)]['market_pairing'][$market_pairing][$exchange];
        
    
    
    	  // If market exists, get latest data
        if ( $pairing_id != '' ) {
        
              
              
              // GET BTC MARKET CONVERSION VALUE #BEFORE ANYTHING ELSE#, OR WE WON'T GET PROPER VOLUME IN CURRENCY ETC
              // IF NOT SET YET, get bitcoin market data (if we are getting converted fiat currency values)
              if ( $market_conversion != 'market_only' && !isset($btc_exchange) && !isset($market_conversion_btc_value) ) {
              
              	
              		  // If a preferred bitcoin market is set in app config, use it...otherwise use first array key
              		  if ( isset($app_config['power_user']['bitcoin_preferred_currency_markets'][$market_conversion]) ) {
              		  $btc_exchange = $app_config['power_user']['bitcoin_preferred_currency_markets'][$market_conversion];
						  }
						  else {
						  $btc_exchange = key($app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion]);
						  }
                
                
              $btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion][$btc_exchange];
              
              $market_conversion_btc_value = asset_market_data('BTC', $btc_exchange, $btc_pairing_id)['last_trade'];
              
              		  
              		  // FAILSAFE: If the exchange market is DOES NOT RETURN a value, 
              		  // move the internal array pointer one forward, until we've tried all exchanges for this btc pairing
              		  $switch_exchange = true;
              		  while ( !isset($market_conversion_btc_value) && $switch_exchange != false || number_to_string($market_conversion_btc_value) < 0.00000001 && $switch_exchange != false ) {
              		  	
              		  $switch_exchange = next($app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion]);
              		  
              		  		if ( $switch_exchange != false ) {
              		  			
              		  		$btc_exchange = key($app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion]);
              		  		
              		  		$btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion][$btc_exchange];
              
              		  		$market_conversion_btc_value = asset_market_data('BTC', $btc_exchange, $btc_pairing_id)['last_trade'];
              		  
              		  		}
              
              		  }
        
        		  
              // OVERWRITE SELECTED BITCOIN CURRENCY MARKET GLOBALS
              $app_config['general']['btc_primary_currency_pairing'] = $market_conversion;
    			  $app_config['general']['btc_primary_exchange'] = $btc_exchange;
              
        		  // OVERWRITE #GLOBAL# BTC PRIMARY CURRENCY VALUE (so we get correct values for volume in currency etc)
        		  $selected_btc_primary_currency_value = $market_conversion_btc_value;
        		  
              }
              
                
                
        $asset_market_data = asset_market_data(strtoupper($asset), $exchange, $pairing_id, $market_pairing);
        
        $coin_value_raw = $asset_market_data['last_trade'];
        
        // Pretty numbers
        $coin_value_raw = number_to_string($coin_value_raw);
        
        // If no pair volume is available for this market, emulate it within reason with: asset value * asset volume
        $volume_pairing_raw = number_to_string($asset_market_data['24hr_pairing_volume']);
        
        
        
              // More pretty numbers formatting
              if ( array_key_exists($market_pairing, $app_config['power_user']['bitcoin_currency_markets']) ) {
              $coin_value_raw = ( number_to_string($coin_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($coin_value_raw, 2) : round($coin_value_raw, $app_config['general']['primary_currency_decimals_max']) );
              $volume_pairing_rounded = round($volume_pairing_raw);
              }
              else {
              $volume_pairing_rounded = round($volume_pairing_raw, 3);
              }
              
              
              
              // Get converted fiat currency values if requested
              if ( $market_conversion != 'market_only' ) {
              
        				  // Value in fiat currency
                    if ( $market_pairing == 'btc' ) {
                    $coin_primary_market_worth_raw = $coin_value_raw * $market_conversion_btc_value;
                    }
                    else {
                    $pairing_btc_value = pairing_market_value($market_pairing);
                    		if ( $pairing_btc_value == null ) {
                    		app_logging('market_error', 'pairing_market_value() returned null in market_conversion_internal_api()', 'pairing: ' . $market_pairing);
                    		}
                    $coin_primary_market_worth_raw = ($coin_value_raw * $pairing_btc_value) * $market_conversion_btc_value;
                    }
              
              // Pretty numbers for fiat currency
              $coin_primary_market_worth_raw = ( number_to_string($coin_primary_market_worth_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($coin_primary_market_worth_raw, 2) : round($coin_primary_market_worth_raw, $app_config['general']['primary_currency_decimals_max']) );
              
              }
        
        
        
              // Results
              if ( $market_conversion != $market_pairing && $market_conversion != 'market_only' ) {
              
              // Flag we are doing a price conversion
              $price_conversion = 1;
                
              $result['market_conversion'][$market_data] = array(
                                                        						'market' => array( $market_pairing => array('spot_price' => $coin_value_raw, '24hr_volume' => $volume_pairing_rounded) ),
                                                        						'conversion' => array( $market_conversion => array('spot_price' => $coin_primary_market_worth_raw, '24hr_volume' => round($asset_market_data['24hr_primary_currency_volume']) ) )
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
    reset($app_config['portfolio_assets']['BTC']['market_pairing'][$market_conversion]);
    
	 $result['market_conversion_source'] = $btc_exchange . '-btc-' . $market_conversion;
	 
	 }



return $result;


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function pairing_market_value($pairing) {

global $app_config, $btc_pairing_markets, $btc_pairing_markets_blacklist;

$pairing = strtolower($pairing);


	// Safeguard / cut down on runtime
	if ( $pairing == null || $pairing == 'btc' ) {
	return null;
	}
	// If session value exists
	elseif ( number_to_string($btc_pairing_markets[$pairing.'_btc']) >= 0.00000001 ) {
	return $btc_pairing_markets[$pairing.'_btc'];
	}
	// If we need an ALTCOIN/BTC market value (RUN BEFORE CURRENCIES FOR BEST MARKET DATA, AS SOME CRYPTOS ARE INCLUDED IN BOTH)
	elseif ( array_key_exists($pairing, $app_config['power_user']['crypto_pairing']) ) {
		
		
		// Include a basic array check, since we want valid data to avoid an endless loop in our fallback support
		if ( !is_array($app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc']) ) {
   	app_logging('market_error', 'pairing_market_value() - market failure (unknown pairing) for ' . $pairing);
		return null;
		}
		// Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
		elseif ( sizeof($app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc']) > 1 && array_key_exists($pairing, $app_config['power_user']['crypto_pairing_preferred_markets']) ) {
		$whitelist = $app_config['power_user']['crypto_pairing_preferred_markets'][$pairing];
		}
	
	
		// Loop until we find a whitelisted / non-blacklisted pairing market
		foreach ( $app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc'] as $market_key => $market_value ) {
					
					
			if ( isset($whitelist) && $whitelist == $market_key && !in_array($market_key, $btc_pairing_markets_blacklist[$pairing])
			|| isset($whitelist) && $whitelist != $market_key && in_array($whitelist, $btc_pairing_markets_blacklist[$pairing]) && !in_array($market_key, $btc_pairing_markets_blacklist[$pairing])
			|| !isset($whitelist) && !in_array($market_key, $btc_pairing_markets_blacklist[$pairing]) ) {
				
   		$btc_pairing_markets[$pairing.'_btc'] = asset_market_data(strtoupper($pairing), $market_key, $market_value)['last_trade'];
   		
   			// Fallback support IF THIS IS A FUTURES MARKET (we want a normal / current value), OR no data returned
   			if ( stristr($market_key, 'bitmex_') == false && number_to_string($btc_pairing_markets[$pairing.'_btc']) >= 0.00000001 ) {
   				
   				// Data debugging telemetry
					if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' ) {
					app_logging('market_debugging', 'pairing_market_value() market request succeeded for ' . $pairing, 'exchange: ' . $market_key);
					}		
   					
   			return number_to_string($btc_pairing_markets[$pairing.'_btc']);
   			
   			}
   			// ONLY LOG AN ERROR IF ALL AVAILABLE MARKETS FAIL (AND RETURN NULL)
   			// We only want to loop a fallback for the amount of available markets
   			elseif ( sizeof($btc_pairing_markets_blacklist[$pairing]) == sizeof($app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc']) ) {
   			app_logging('market_error', 'pairing_market_value() - market request failure (all '.sizeof($btc_pairing_markets_blacklist[$pairing]).' markets failed) for ' . $pairing . ' / btc (' . $market_key . ')', $pairing . '_blacklisted_count: ' . sizeof($btc_pairing_markets_blacklist[$pairing]) );
   			return null;
   			}
   			else {
   			$btc_pairing_markets[$pairing.'_btc'] = null; // Reset
   			$btc_pairing_markets_blacklist[$pairing][] = $market_key; // Blacklist getting pairing data from this exchange IN ANY PAIRING, for this runtime only
   			return pairing_market_value($pairing);
   			}
   		
			}
			
			
		}
		return null; // If we made it this deep in the logic, no data was found	
	
	}
	// If we need a BITCOIN/CURRENCY market value 
	// RUN AFTER CRYPTO MARKETS...WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
	elseif ( array_key_exists($pairing, $app_config['power_user']['bitcoin_currency_markets']) ) {
	
	
		// Include a basic array check, since we want valid data to avoid an endless loop in our fallback support
		if ( !is_array($app_config['portfolio_assets']['BTC']['market_pairing'][$pairing]) ) {
   	app_logging('market_error', 'pairing_market_value() - market failure (unknown pairing) for ' . $pairing);
		return null;
		}
		// Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
		elseif ( sizeof($app_config['portfolio_assets']['BTC']['market_pairing'][$pairing]) > 1 && array_key_exists($pairing, $app_config['power_user']['bitcoin_preferred_currency_markets']) ) {
		$whitelist = $app_config['power_user']['bitcoin_preferred_currency_markets'][$pairing];
		}
				
				
		// Loop until we find a whitelisted / non-blacklisted pairing market
		foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$pairing] as $market_key => $market_value ) {
					
					
			if ( isset($whitelist) && $whitelist == $market_key && !in_array($market_key, $btc_pairing_markets_blacklist[$pairing])
			|| isset($whitelist) && $whitelist != $market_key && in_array($whitelist, $btc_pairing_markets_blacklist[$pairing]) && !in_array($market_key, $btc_pairing_markets_blacklist[$pairing])
			|| !isset($whitelist) && !in_array($market_key, $btc_pairing_markets_blacklist[$pairing]) ) {
						
   		$btc_pairing_markets[$pairing.'_btc'] = ( 1 / asset_market_data(strtoupper($pairing), $market_key, $market_value)['last_trade'] );
   					
   			// Fallback support IF THIS IS A FUTURES MARKET (we want a normal / current value), OR no data returned
   			if ( stristr($market_key, 'bitmex_') == false && number_to_string($btc_pairing_markets[$pairing.'_btc']) >= 0.0000000000000000000000001 ) { // FUTURE-PROOF FIAT ROUNDING WITH 25 DECIMALS, IN CASE BITCOIN MOONS HARD
   						
   				// Data debugging telemetry
					if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' ) {
					app_logging('market_debugging', 'pairing_market_value() market request succeeded for ' . $pairing, 'exchange: ' . $market_key);
					}
							
   			return number_to_string($btc_pairing_markets[$pairing.'_btc']);
   					
   			}
   			// ONLY LOG AN ERROR IF ALL AVAILABLE MARKETS FAIL (AND RETURN NULL)
   			// We only want to loop a fallback for the amount of available markets
   			elseif ( sizeof($btc_pairing_markets_blacklist[$pairing]) >= sizeof($app_config['portfolio_assets']['BTC']['market_pairing'][$pairing]) ) {
   			app_logging('market_error', 'pairing_market_value() - market request failure (all '.sizeof($btc_pairing_markets_blacklist[$pairing]).' markets failed) for btc / ' . $pairing . ' (' . $market_key . ')', $pairing . '_blacklisted_count: ' . sizeof($btc_pairing_markets_blacklist[$pairing]) );
   			return null;
   			}
   			else {
   			$btc_pairing_markets[$pairing.'_btc'] = null; // Reset	
   			$btc_pairing_markets_blacklist[$pairing][] = $market_key; // Blacklist getting pairing data from this exchange IN ANY PAIRING, for this runtime only
   			return pairing_market_value($pairing);
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
    
global $_POST, $hive_market, $app_config, $selected_btc_primary_currency_value;

$powertime = null;
$powertime = null;
$hive_total = null;
$primary_currency_total = null;

$decimal_yearly_interest = $app_config['power_user']['hivepower_yearly_interest'] / 100;  // Convert APR in config to decimal representation

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
    
    $powertime_primary_currency = ( $powertime * $hive_market * $selected_btc_primary_currency_value );
    
    $hive_total = ( $powertime + $_POST['hp_total'] );
    $primary_currency_total = ( $hive_total * $hive_market * $selected_btc_primary_currency_value );
    
    $power_purchased = ( $_POST['hp_purchased'] / $hive_total );
    $power_earned = ( $_POST['hp_earned'] / $hive_total );
    $power_interest = 1 - ( $power_purchased + $power_earned );
    
    $powerdown_total = ( $hive_total / $app_config['power_user']['hive_powerdown_time'] );
    $powerdown_purchased = ( $powerdown_total * $power_purchased );
    $powerdown_earned = ( $powerdown_total * $power_earned );
    $powerdown_interest = ( $powerdown_total * $power_interest );
    
    ?>
    
<div class='result'>
    <h2> Interest Per <?=ucfirst($time)?> </h2>
    <ul>
        
        <li><b><?=number_format( $powertime, 3, '.', ',')?> HIVE</b> <i>in interest</i> (after a <?=$time?> time period) = <b><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( $powertime_primary_currency, 2, '.', ',')?></b></li>
        
        <li><b><?=number_format( $hive_total, 3, '.', ',')?> HIVE</b> <i>in total</i> (including original vested amount) = <b><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( $primary_currency_total, 2, '.', ',')?></b></li>
    
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

                <td> <?=number_format( $powerdown_purchased, 3, '.', ',')?> HIVE = <?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_purchased), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_earned, 3, '.', ',')?> HIVE = <?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_earned), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_interest, 3, '.', ',')?> HIVE = <?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_interest), 2, '.', ',')?> </td>
                <td> <b><?=number_format( $powerdown_total, 3, '.', ',')?> HIVE</b> = <b><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_total), 2, '.', ',')?></b> </td>

                </tr>
           
        </table>     
        
</div>

    <?php
    
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function mining_calc_form($calculation_form_data, $network_measure, $hash_unit='hash') {

global $_POST, $app_config;

?>

				<form name='<?=$calculation_form_data[1]?>' action='<?=start_page('mining')?>' method='post'>
				
				
				<p><b><?=ucfirst($network_measure)?>:</b> 
				<?php
				if ( $hash_unit == 'hash' ) {
				?>
				
				<input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calculation_form_data[3]) )?>' name='network_measure' /> 
				
				(uses <a href='<?=$calculation_form_data[4]?>' target='_blank'><?=$calculation_form_data[5]?></a>)
				
				<?php
				}
				?>
				</p>
				
				
				<p><b>Your Hashrate:</b>  
				<input type='text' value='<?=( $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['your_hashrate'] : '' )?>' name='your_hashrate' /> 
				
				
				
				<?php
				if ( $hash_unit == 'hash' ) {
				?>
				<select class='browser-default custom-select' name='hash_level'>
				<option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Hs (hashes per second) </option>
				<option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Khs (thousand hashes per second) </option>
				<option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Mhs (million hashes per second) </option>
				<option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ghs (billion hashes per second) </option>
				<option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ths (trillion hashes per second) </option>
				<option value='1000000000000000' <?=( $_POST['hash_level'] == '1000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Phs (quadrillion hashes per second) </option>
				<option value='1000000000000000000' <?=( $_POST['hash_level'] == '1000000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ehs (quintillion hashes per second) </option>
				</select>
				
				<?php
				}
				?>
				
				
				</p>
				
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['block_reward'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['block_reward'] : $app_config['power_user']['mining_rewards'][$calculation_form_data[1]] )?>' name='block_reward' /> (may be static from config.php file, verify current block reward manually)</p>
				
				
				<p><b>Watts Used:</b> <input type='text' value='<?=( isset($_POST['watts_used']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['watts_used'] : '300' )?>' name='watts_used' /></p>
				
				
				<p><b>kWh Rate (<?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?>/kWh):</b> <input type='text' value='<?=( isset($_POST['watts_rate']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['watts_rate'] : '0.1000' )?>' name='watts_rate' /></p>
				
				
				<p><b>Pool Fee:</b> <input type='text' value='<?=( isset($_POST['pool_fee']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['pool_fee'] : '1' )?>' size='4' name='pool_fee' />%</p>
				    
				    
			   <input type='hidden' value='1' name='<?=$calculation_form_data[1]?>_submitted' />
				
				<input type='submit' value='Calculate <?=strtoupper($calculation_form_data[1])?> Mining Profit' />
	
				</form>
				

<?php
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function charts_and_price_alerts($asset_data, $exchange, $pairing, $mode) {

// Globals
global $base_dir, $app_config, $default_btc_primary_exchange, $default_btc_primary_currency_value, $default_btc_primary_currency_pairing, $price_alerts_fixed_reset_array;

	
	// Return true (no errors) if alert-only, and alerts are disabled
	if ( $mode == 'alert' && $app_config['comms']['price_alerts_threshold'] == 0 ) {
	return true;
	}

$pairing = strtolower($pairing);

/////////////////////////////////////////////////////////////////
// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($asset_data, "-") == false ? $asset_data : substr( $asset_data, 0, mb_strpos($asset_data, "-", 0, 'utf-8') ) );
$asset = strtoupper($asset);


	// Fiat or equivalent pairing?
	// #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
	if ( array_key_exists($pairing, $app_config['power_user']['bitcoin_currency_markets']) && !array_key_exists($pairing, $app_config['power_user']['crypto_pairing']) ) {
	$fiat_eqiv = 1;
	}
/////////////////////////////////////////////////////////////////



// Get any necessary variables for calculating asset's PRIMARY CURRENCY CONFIG value

// Consolidate function calls for runtime speed improvement
$asset_market_data = asset_market_data($asset, $exchange, $app_config['portfolio_assets'][$asset]['market_pairing'][$pairing][$exchange], $pairing);
   
   
	// Get asset PRIMARY CURRENCY CONFIG value
	/////////////////////////////////////////////////////////////////
	// PRIMARY CURRENCY CONFIG CHARTS
	if ( $pairing == $default_btc_primary_currency_pairing ) {
	$asset_primary_currency_value_raw = $asset_market_data['last_trade']; 
	}
	// BTC PAIRINGS CONVERTED TO PRIMARY CURRENCY CONFIG (EQUIV) CHARTS
	elseif ( $pairing == 'btc' ) {
	$asset_primary_currency_value_raw = number_format( $default_btc_primary_currency_value * $asset_market_data['last_trade'] , 8, '.', '');
	}
	// OTHER PAIRINGS CONVERTED TO PRIMARY CURRENCY CONFIG (EQUIV) CHARTS
	else {
		
	$pairing_btc_value = pairing_market_value($pairing); 
	
		if ( $pairing_btc_value == null ) {
		app_logging('market_error', 'pairing_market_value() returned null in charts_and_price_alerts()', 'pairing: ' . $pairing);
		}
	
	$asset_primary_currency_value_raw = number_format( $default_btc_primary_currency_value * ( $asset_market_data['last_trade'] * $pairing_btc_value ) , 8, '.', '');
	
	}
	/////////////////////////////////////////////////////////////////
	
	
		
/////////////////////////////////////////////////////////////////
$volume_pairing_raw = number_to_string($asset_market_data['24hr_pairing_volume']); // If available, we'll use this for chart volume UX
$volume_primary_currency_raw = $asset_market_data['24hr_primary_currency_volume'];
		
$asset_pairing_value_raw = number_format( $asset_market_data['last_trade'] , 8, '.', '');
/////////////////////////////////////////////////////////////////
	
	
	
	/////////////////////////////////////////////////////////////////
	// Make sure we have basic values, otherwise log errors / return false
	// Return false if we have no $default_btc_primary_currency_value
	if ( !isset($default_btc_primary_currency_value) || $default_btc_primary_currency_value == 0 ) {
	app_logging('market_error', 'charts_and_price_alerts() - No Bitcoin '.strtoupper($default_btc_primary_currency_pairing).' value ('.strtoupper($pairing).' pairing) for '.$mode.' "' . $asset_data . '"', $asset_data . ': ' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . ';' );
	$set_return = 1;
	}
	
	
	// Return false if we have no asset value
	if ( number_to_string( trim($asset_primary_currency_value_raw) ) >= 0.00000001 ) {
	// Continue
	}
	else {
	app_logging('market_error', 'charts_and_price_alerts() - No '.strtoupper($default_btc_primary_currency_pairing).' conversion value ('.strtoupper($pairing).' pairing) for '.$mode.' "' . $asset_data . '"', $asset_data . ': ' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . '; pairing_id: ' . $app_config['portfolio_assets'][$asset]['market_pairing'][$pairing][$exchange] . ';' );
	$set_return = 1;
	}
	
	
	if ( $set_return == 1 ) {
	return false;
	}
	/////////////////////////////////////////////////////////////////
	
	
	
// Optimizing storage size needed for charts data
/////////////////////////////////////////////////////////////////
// Round PRIMARY CURRENCY CONFIG volume to nullify insignificant decimal amounts / for prettier numbers UX, and to save on data set / storage size
$volume_primary_currency_raw = ( isset($volume_primary_currency_raw) ? round($volume_primary_currency_raw) : null );		
	
// Round PAIRING volume to only keep 3 decimals max (for crypto volume etc), to save on data set / storage size
$volume_pairing_raw = ( isset($volume_pairing_raw) ? round($volume_pairing_raw, ( $fiat_eqiv == 1 ? 0 : 3 ) ) : null );	
	
	
// Round PRIMARY CURRENCY CONFIG asset price to only keep $app_config['general']['primary_currency_decimals_max'] decimals maximum 
// (or only 2 decimals if worth $app_config['general']['primary_currency_decimals_max_threshold'] or more), to save on data set / storage size
$asset_primary_currency_value_raw = ( number_to_string($asset_primary_currency_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($asset_primary_currency_value_raw, 2) : round($asset_primary_currency_value_raw, $app_config['general']['primary_currency_decimals_max']) );
	
	
	// If fiat equivalent format, round asset price 
	// to only keep $app_config['general']['primary_currency_decimals_max'] decimals maximum 
	// (or only 2 decimals if worth $app_config['general']['primary_currency_decimals_max_threshold'] or more), to save on data set / storage size
   if ( $fiat_eqiv == 1 ) {
   $asset_pairing_value_raw = ( number_to_string($asset_pairing_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($asset_pairing_value_raw, 2) : round($asset_pairing_value_raw, $app_config['general']['primary_currency_decimals_max']) );
   }


// Remove any leading / trailing zeros from CRYPTO asset price, to save on data set / storage size
$asset_pairing_value_raw = number_to_string($asset_pairing_value_raw);

// Remove any leading / trailing zeros from PAIRING VOLUME, to save on data set / storage size
$volume_pairing_raw = number_to_string($volume_pairing_raw);
/////////////////////////////////////////////////////////////////

	

	// Charts (WE DON'T WANT TO STORE DATA WITH A CORRUPT TIMESTAMP)
	/////////////////////////////////////////////////////////////////
	// If the charts page is enabled in config.php, save latest chart data for assets with price alerts configured on them
	if ( $mode == 'both' && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && $app_config['general']['asset_charts_toggle'] == 'on'
	|| $mode == 'chart' && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && $app_config['general']['asset_charts_toggle'] == 'on' ) {
	
	// In case a rare error occured from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
	// (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
	$now = time();
	
		if ( $now > 0 ) {
		// Continue
		}
		else {
		// Return
		app_logging('system_error', 'time() returned a corrupt value (from power outage / corrupt memory / etc), chart updating canceled', 'chart_type: asset market');
		return false;
		}
		
	// PRIMARY CURRENCY CONFIG ARCHIVAL charts (CRYPTO/PRIMARY CURRENCY CONFIG markets, 
	// AND ALSO crypto-to-crypto pairings converted to PRIMARY CURRENCY CONFIG equiv value for PRIMARY CURRENCY CONFIG equiv charts)
	
	$primary_currency_chart_path = $base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset.'/'.$asset_data.'_chart_'.strtolower($default_btc_primary_currency_pairing).'.dat';
	$primary_currency_chart_data = $now . '||' . $asset_primary_currency_value_raw . '||' . $volume_primary_currency_raw;
	store_file_contents($primary_currency_chart_path, $primary_currency_chart_data . "\n", "append", false);  // WITH newline (UNLOCKED file write)
		
		
		// Crypto / secondary currency pairing ARCHIVAL charts, volume as pairing (for UX)
		if ( $pairing != strtolower($default_btc_primary_currency_pairing) ) {
		$crypto_secondary_currency_chart_path = $base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset.'/'.$asset_data.'_chart_'.$pairing.'.dat';
		$crypto_secondary_currency_chart_data = $now . '||' . $asset_pairing_value_raw . '||' . $volume_pairing_raw;
		store_file_contents($crypto_secondary_currency_chart_path, $crypto_secondary_currency_chart_data . "\n", "append", false); // WITH newline (UNLOCKED file write)
		}
		
		
		// Lite charts (update time dynamically determined in update_lite_chart() logic)
		// Wait 0.05 seconds before updating lite charts (which reads archival data)
		usleep(50000); // Wait 0.05 seconds
		
		foreach ( $app_config['power_user']['lite_chart_day_intervals'] as $light_chart_days ) {
			
		// Primary currency lite charts
		update_lite_chart($primary_currency_chart_path, $primary_currency_chart_data, $light_chart_days); // WITHOUT newline (var passing)
				
			// Crypto / secondary currency pairing lite charts
			if ( $pairing != strtolower($default_btc_primary_currency_pairing) ) {
			update_lite_chart($crypto_secondary_currency_chart_path, $crypto_secondary_currency_chart_data, $light_chart_days); // WITHOUT newline (var passing)
			}
		
		}
		
		
	}
	/////////////////////////////////////////////////////////////////
	
	
	
	
	// Alert checking START
	/////////////////////////////////////////////////////////////////
	if ( $mode == 'alert' && $app_config['comms']['price_alerts_threshold'] > 0 || $mode == 'both' && $app_config['comms']['price_alerts_threshold'] > 0 ) {

        
   // WE USE PAIRING VOLUME FOR VOLUME PERCENTAGE CHANGES, FOR BETTER PERCENT CHANGE ACCURACY THAN FIAT EQUIV
   $alert_cache_contents = $asset_primary_currency_value_raw . '||' . $volume_primary_currency_raw . '||' . $volume_pairing_raw;
   	
	// Grab any cached price alert data
   $data_file = trim( file_get_contents('cache/alerts/'.$asset_data.'.dat') );
    
   $cached_array = explode("||", $data_file);
   
    
      // Make sure numbers are cleanly pulled from cache file
      foreach ( $cached_array as $key => $value ) {
      $cached_array[$key] = remove_number_format($value);
      }
    
    
      // Backwards compatibility
      if ( $cached_array[0] == null ) {
      $cached_asset_primary_currency_value = $data_file;
      $cached_primary_currency_volume = -1;
      $cached_pairing_volume = -1;
      }
      else {
      $cached_asset_primary_currency_value = $cached_array[0];  // PRIMARY CURRENCY CONFIG token value
      $cached_primary_currency_volume = round($cached_array[1]); // PRIMARY CURRENCY CONFIG volume value (round PRIMARY CURRENCY CONFIG volume to nullify insignificant decimal amounts skewing checks)
      $cached_pairing_volume = $cached_array[2]; // Crypto volume value (more accurate percent increase / decrease stats than PRIMARY CURRENCY CONFIG value fluctuations)
      }
    
    
    
    	// Price checks (done early for including with price alert reset logic)
    	// If cached and current price exist
    	if ( number_to_string( trim($cached_asset_primary_currency_value) ) >= 0.00000001 && number_to_string( trim($asset_primary_currency_value_raw) ) >= 0.00000001 ) {
    	
    	
    	// PRIMARY CURRENCY CONFIG price percent change (!MUST BE! absolute value)
    	$percent_change = abs( ($asset_primary_currency_value_raw - $cached_asset_primary_currency_value) / abs($cached_asset_primary_currency_value) * 100 );
    	$percent_change = number_to_string($percent_change); // Better decimal support
              
                    
		// Pretty exchange name / percent change for UI / UX (defined early for any price alert reset logic)
      $percent_change_text = number_format($percent_change, 2, '.', ',');
		$exchange_text = snake_case_to_name($exchange);
		
              
      	// UX / UI variables
      	if ( number_to_string($asset_primary_currency_value_raw) < number_to_string($cached_asset_primary_currency_value) ) {
      	$change_symbol = '-';
      	$increase_decrease = 'decreased';
      	}
      	elseif ( number_to_string($asset_primary_currency_value_raw) >= number_to_string($cached_asset_primary_currency_value) ) {
      	$change_symbol = '+';
      	$increase_decrease = 'increased';
      	}
              
    	
      	// INITIAL check whether we should send an alert (we ALSO check for a few different conditions further down, and UPDATE THIS VAR AS NEEDED THEN)
      	if ( $percent_change >= $app_config['comms']['price_alerts_threshold'] ) {
      	$send_alert = 1;
      	}
              
              
    	}
              
    
    
      ////// If flagged to run alerts //////////// 
      if ( $send_alert == 1 ) {
        
   	
      // Check for a file modified time !!!BEFORE ANY!!! file creation / updating happens (to calculate time elapsed between updates)
        
      $last_check_days = ( time() - filemtime('cache/alerts/'.$asset_data.'.dat') ) / 86400;
    	$last_check_days = number_to_string($last_check_days); // Better decimal support for whale alerts etc
       
       
        		  if ( $last_check_days >= 365 ) {
        		  $last_check_time = number_format( ($last_check_days / 365) , 2, '.', ',') . ' years';
        		  }
        		  elseif ( $last_check_days >= 30 ) {
        		  $last_check_time = number_format( ($last_check_days / 30) , 2, '.', ',') . ' months';
        		  }
        		  elseif ( $last_check_days >= 7 ) {
        		  $last_check_time = number_format( ($last_check_days / 7) , 2, '.', ',') . ' weeks';
        		  }
        		  else {
        		  $last_check_time = number_format($last_check_days, 2, '.', ',') . ' days';
        		  }
       
        
               
      // Crypto volume checks
              
      // Crypto volume percent change (!MUST BE! absolute value)
      $volume_percent_change = abs( ($volume_pairing_raw - $cached_pairing_volume) / abs($cached_pairing_volume) * 100 );        
      $volume_percent_change = number_to_string($volume_percent_change); // Better decimal support
      
              
              
              // UX adjustments, and UI / UX variables
              if ( $cached_primary_currency_volume <= 0 && $volume_primary_currency_raw <= 0 ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
              $volume_percent_change = 0; // Skip calculating percent change if cached / live PRIMARY CURRENCY CONFIG volume are both zero or -1 (exchange API error)
              $volume_change_symbol = '+';
              }
              elseif ( $cached_primary_currency_volume <= 0 && $volume_pairing_raw >= $cached_pairing_volume ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
              $volume_percent_change = $volume_primary_currency_raw; // Use PRIMARY CURRENCY CONFIG volume value for percent up, for UX sake, if volume is up from zero or -1 (exchange API error)
              $volume_change_symbol = '+';
              }
              elseif ( $cached_primary_currency_volume > 0 && $volume_pairing_raw < $cached_pairing_volume ) {
              $volume_change_symbol = '-';
              }
              elseif ( $cached_primary_currency_volume > 0 && $volume_pairing_raw > $cached_pairing_volume ) {
              $volume_change_symbol = '+';
              }
              
              
              
              // Whale alert (price change average of X or greater over X day(s) or less, with X percent pair volume increase average that is at least a X primary currency volume increase average)
              $whale_alert_threshold = explode("||", $app_config['charts_alerts']['price_alerts_whale_alert_threshold']);
    
              if ( trim($whale_alert_threshold[0]) != '' && trim($whale_alert_threshold[1]) != '' && trim($whale_alert_threshold[2]) != '' && trim($whale_alert_threshold[3]) != '' ) {
              
              $whale_max_days_to_24hr_average_over = number_to_string( trim($whale_alert_threshold[0]) );
              
              $whale_min_price_percent_change_24hr_average = number_to_string( trim($whale_alert_threshold[1]) );
              
              $whale_min_volume_percent_increase_24hr_average = number_to_string( trim($whale_alert_threshold[2]) );
              
              $whale_min_volume_currency_increase_24hr_average = number_to_string( trim($whale_alert_threshold[3]) );
              
              
                // WE ONLY WANT PRICE CHANGE PERCENT AS AN ABSOLUTE VALUE HERE, ALL OTHER VALUES SHOULD BE ALLOWED TO BE NEGATIVE IF THEY ARE NEGATIVE
                if ( $last_check_days <= $whale_max_days_to_24hr_average_over 
                && number_to_string($percent_change / $last_check_days) >= $whale_min_price_percent_change_24hr_average 
                && number_to_string($volume_change_symbol . $volume_percent_change / $last_check_days) >= $whale_min_volume_percent_increase_24hr_average 
                && number_to_string( ($volume_primary_currency_raw - $cached_primary_currency_volume) / $last_check_days ) >= $whale_min_volume_currency_increase_24hr_average ) {
                $whale_alert = 1;
                }
                
             
              }
             
             
              
              // We disallow alerts where minimum 24 hour trade PRIMARY CURRENCY CONFIG volume has NOT been met, ONLY if an API request doesn't fail to retrieve volume data
              if ( $volume_primary_currency_raw >= 0 && $volume_primary_currency_raw < $app_config['comms']['price_alerts_min_volume'] ) {
              $send_alert = null;
              }
      
      
      
      
              // We disallow alerts if they are not activated
              if ( $mode != 'both' && $mode != 'alert' ) {
              $send_alert = null;
              }
      
      
              // We disallow alerts if $app_config['comms']['price_alerts_block_volume_error'] is on, and there is a volume retrieval error
              // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
              if ( $volume_primary_currency_raw == -1 && $app_config['comms']['price_alerts_block_volume_error'] == 'on' ) {
              $send_alert = null;
              }
              
              
              
              
              
              // Sending the alerts
              if ( update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $app_config['comms']['price_alerts_freq_max'] * 60 ) ) == true && $send_alert == 1 ) {
              
                            
              // Message formatting for display to end user
                
              $desc_alert_type = ( $app_config['charts_alerts']['price_alerts_fixed_reset'] > 0 ? 'reset' : 'alert' );
              
                
                // IF PRIMARY CURRENCY CONFIG volume was between 0 and 1 last alert / reset, for UX sake 
                // we use current PRIMARY CURRENCY CONFIG volume instead of pair volume (for percent up, so it's not up 70,000% for altcoins lol)
                if ( $cached_primary_currency_volume >= 0 && $cached_primary_currency_volume <= 1 ) {
                $volume_describe = strtoupper($default_btc_primary_currency_pairing) . ' volume was ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $cached_primary_currency_volume . ' last price ' . $desc_alert_type . ', and ';
                $volume_describe_mobile = strtoupper($default_btc_primary_currency_pairing) . ' volume up from ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $cached_primary_currency_volume . ' last ' . $desc_alert_type;
                }
                // Best we can do feasibly for UX on volume reporting errors
                elseif ( $cached_primary_currency_volume == -1 ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                $volume_describe = strtoupper($default_btc_primary_currency_pairing) . ' volume was NULL last price ' . $desc_alert_type . ', and ';
                $volume_describe_mobile = strtoupper($default_btc_primary_currency_pairing) . ' volume up from NULL last ' . $desc_alert_type;
                }
                else {
                $volume_describe = 'pair volume ';
                $volume_describe_mobile = 'pair volume'; // no space
                }
              
              
              
              
              // Pretty up textual output to end-user (convert raw numbers to have separators, remove underscores in names, etc)
                
                    
              // Pretty numbers UX on PRIMARY CURRENCY CONFIG asset value
              $asset_primary_currency_text = ( number_to_string($asset_primary_currency_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? pretty_numbers($asset_primary_currency_value_raw, 2) : pretty_numbers($asset_primary_currency_value_raw, $app_config['general']['primary_currency_decimals_max']) );
                    
              $volume_primary_currency_text = $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . number_format($volume_primary_currency_raw, 0, '.', ',');
                    
              $volume_change_text = 'has ' . ( $volume_change_symbol == '+' ? 'increased ' : 'decreased ' ) . $volume_change_symbol . number_format($volume_percent_change, 2, '.', ',') . '% to a ' . strtoupper($default_btc_primary_currency_pairing) . ' value of';
                    
              $volume_change_text_mobile = '(' . $volume_change_symbol . number_format($volume_percent_change, 2, '.', ',') . '% ' . $volume_describe_mobile . ')';
                    
                    
                    
                    
                // If -1 from exchange API error not reporting any volume data (not even zero)
                // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                if ( $cached_primary_currency_volume == -1 || $volume_primary_currency_raw == -1 ) {
                $volume_change_text = null;
                $volume_change_text_mobile = null;
                }
                
                
                
                // Format trade volume data
                
                // Volume filter skipped message, only if filter is on and error getting trade volume data (otherwise is NULL)
                if ( $volume_primary_currency_raw == null && $app_config['comms']['price_alerts_min_volume'] > 0 || $volume_primary_currency_raw < 1 && $app_config['comms']['price_alerts_min_volume'] > 0 ) {
                $volume_filter_skipped_text = ', so volume filter was skipped';
                }
                else {
                $volume_filter_skipped_text = null;
                }
                
                
                
                // Successfully received > 0 volume data, at or above an enabled volume filter
                    if ( $volume_primary_currency_raw > 0 && $app_config['comms']['price_alerts_min_volume'] > 0 && $volume_primary_currency_raw >= $app_config['comms']['price_alerts_min_volume'] ) {
                $email_volume_summary = '24 hour ' . $volume_describe . $volume_change_text . ' ' . $volume_primary_currency_text . ' (volume filter on).';
                }
                // NULL if not setup to get volume, negative number returned if no data received from API, therefore skipping any enabled volume filter
                // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                    elseif ( $volume_primary_currency_raw == -1 ) { 
                $email_volume_summary = 'No data received for 24 hour volume' . $volume_filter_skipped_text . '.';
                $volume_primary_currency_text = 'No data';
                }
                // If volume is zero or greater in successfully received volume data, without an enabled volume filter (or filter skipped)
                // IF exchange PRIMARY CURRENCY CONFIG value price goes up/down and triggers alert, 
                // BUT current reported volume is zero (temporary error on exchange side etc, NOT on our app's side),
                // inform end-user of this probable volume discrepancy being detected.
                elseif ( $volume_primary_currency_raw >= 0 ) {
                $email_volume_summary = '24 hour ' . $volume_describe . $volume_change_text . ' ' . $volume_primary_currency_text . ( $volume_primary_currency_raw == 0 ? ' (probable volume discrepancy detected' . $volume_filter_skipped_text . ')' : '' ) . '.'; 
                }
                    
                    
                    
                    
              // Build the different messages, configure comm methods, and send messages
                    
              $email_message = ( $whale_alert == 1 ? 'WHALE ALERT: ' : '' ) . 'The ' . $asset . ' trade value in the ' . strtoupper($pairing) . ' market at the ' . $exchange_text . ' exchange has ' . $increase_decrease . ' ' . $change_symbol . $percent_change_text . '% in ' . strtoupper($default_btc_primary_currency_pairing) . ' value to ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $asset_primary_currency_text . ' over the past ' . $last_check_time . ' since the last price ' . $desc_alert_type . '. ' . $email_volume_summary;
                    
              // Were're just adding a human-readable timestamp to smart home (audio) alerts
              $notifyme_message = $email_message . ' Timestamp: ' . time_date_format($app_config['general']['local_time_offset'], 'pretty_time') . '.';
                    
              $text_message = ( $whale_alert == 1 ? '🐳 ' : '' ) . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange_text . ' ' . $increase_decrease . ' ' . $change_symbol . $percent_change_text . '% in ' . strtoupper($default_btc_primary_currency_pairing) . ' value to ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $asset_primary_currency_text . ' over ' . $last_check_time . '. 24 Hour ' . strtoupper($default_btc_primary_currency_pairing) . ' Volume: ' . $volume_primary_currency_text . ' ' . $volume_change_text_mobile;
                    
                    
                    
                    
              // Cache the new lower / higher value + volume data
              store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
                
                
                
              // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                    
              // Minimize function calls
              $encoded_text_message = content_data_encoding($text_message);
                    
              $send_params = array(
                                            'notifyme' => $notifyme_message,
                                            'telegram' => ( $whale_alert == 1 ? '🐳 ' : '' ) . $email_message, // Add emoji here, so it's not sent with alexa / google home alerts
                                            'text' => array(
                                                                    // Unicode support included for text messages (emojis / asian characters / etc )
                                                                    'message' => $encoded_text_message['content_output'],
                                                                    'charset' => $encoded_text_message['charset']
                                                                    ),
                                            'email' => array(
                                                                    'subject' => $asset . ' Asset Value '.ucfirst($increase_decrease).' Alert' . ( $whale_alert == 1 ? ' (🐳 WHALE ALERT)' : '' ),
                                                                    'message' => ( $whale_alert == 1 ? '🐳 ' : '' ) . $email_message // Add emoji here, so it's not sent with alexa / google home alerts
                                                                    )
                                            );
                
                
                
              // Send notifications
              @queue_notifications($send_params);
      
                 
              }
              
              
      
      }
        
   
   
		// Cache a price alert value / volumes if not already done, OR if config setting set to reset every X days
		if ( number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && !file_exists('cache/alerts/'.$asset_data.'.dat') ) {
		store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
		}
		elseif ( $send_alert != 1 && $app_config['charts_alerts']['price_alerts_fixed_reset'] >= 1 && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 
		&& update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $app_config['charts_alerts']['price_alerts_fixed_reset'] * 1440 ) ) == true ) {
			
		store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
		
		// Comms data (for one alert message, including data on all resets per runtime)
		$price_alerts_fixed_reset_array[strtolower($asset)][$asset_data] = $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange_text . ' (' . $change_symbol . $percent_change_text . '%)';
		
		}


   
	////// Alert checking END //////////////
	}
	/////////////////////////////////////////////////////////////////
	
	

// If we haven't returned false yet because of any issues being detected, return true to indicate all seems ok
return true;


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function ui_coin_data_row($asset_name, $asset_symbol, $asset_amount, $all_pairing_markets, $selected_pairing, $selected_exchange, $purchase_price=NULL, $leverage_level, $selected_margintype) {


// Globals
global $_POST, $btc_worth_array, $coin_stats_array, $td_color_zebra, $cap_data_force_usd, $theme_selected, $primary_currency_market_standalone, $app_config, $selected_btc_primary_currency_value, $alert_percent, $coingecko_api, $coinmarketcap_api;

    
$original_market = $selected_exchange;


  //  For faster runtimes, minimize runtime usage here to held / watched amount is > 0, OR we are setting end-user (interface) preferred Bitcoin market settings
  if ( number_to_string($asset_amount) > 0.00000000 || strtolower($asset_name) == 'bitcoin' ) {
    
    
      // Update, get the selected market name
      
    $loop = 0;
    foreach ( $all_pairing_markets as $key => $value ) {
       
        if ( $loop == $selected_exchange || $key == "eth_subtokens_ico" ) {
        $selected_exchange = $key;
         
         if ( sizeof($primary_currency_market_standalone) != 2 && strtolower($asset_name) == 'bitcoin' ) {
         $app_config['general']['btc_primary_exchange'] = $key;
         $app_config['general']['btc_primary_currency_pairing'] = $selected_pairing;
         
                // Dynamically modify MISCASSETS in $app_config['portfolio_assets']
                // ONLY IF USER HASN'T MESSED UP $app_config['portfolio_assets'], AS WE DON'T WANT TO CANCEL OUT ANY
                // CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
                if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {
                $app_config['portfolio_assets']['MISCASSETS']['asset_name'] = 'Misc. '.strtoupper($selected_pairing).' Value';
                }
    
         ?>
         
         <script>
         window.btc_primary_currency_value = '<?=asset_market_data('BTC', $key, $app_config['portfolio_assets']['BTC']['market_pairing'][$selected_pairing][$key])['last_trade']?>';
         
         window.btc_primary_currency_pairing = '<?=strtoupper($selected_pairing)?>';
         </script>
         
         <?php
         }
         
        }
       
    $loop = $loop + 1;
    }
    $loop = null; 
    
    
  $market_id = $all_pairing_markets[$selected_exchange];
    
    
  // Overwrite PRIMARY CURRENCY CONFIG / BTC market value, in case user changed preferred market IN THE UI
  $selected_btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['general']['btc_primary_currency_pairing']][$app_config['general']['btc_primary_exchange']];
  $selected_btc_primary_currency_value = asset_market_data('BTC', $app_config['general']['btc_primary_exchange'], $selected_btc_pairing_id)['last_trade'];
    
    
    // Log any Bitcoin market errors
    if ( !isset($selected_btc_primary_currency_value) || $selected_btc_primary_currency_value == 0 ) {
    app_logging('market_error', 'ui_coin_data_row() Bitcoin primary currency value not properly set', 'exchange: ' . $app_config['general']['btc_primary_exchange'] . '; pairing_id: ' . $selected_btc_pairing_id . '; value: ' . $selected_btc_primary_currency_value );
    }
    
    

  }
  
  
  

  // Start rendering table row in the interface, if value set
  if ( number_to_string($asset_amount) > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
  	
  		
  		// For watch-only, we always want only zero to show here in the UI (with no decimals)
  		if ( number_to_string($asset_amount) == 0.000000001 ) {
  		$asset_amount = 0;
  		}
  		

  $rand_id = rand(10000000,100000000);
      
  $sort_order = ( array_search($asset_symbol, array_keys($app_config['portfolio_assets'])) + 1);
    
  $all_pairings = $app_config['portfolio_assets'][$asset_symbol]['market_pairing'];
    

	 // Consolidate function calls for runtime speed improvement
	 // (called here so first runtime with NO SELECTED ASSETS RUNS SIGNIFICANTLY QUICKER)
	 if ( $app_config['general']['primary_marketcap_site'] == 'coingecko' && sizeof($coingecko_api) < 1 ) {
	 $coingecko_api = coingecko_api();
	 }
	 elseif ( $app_config['general']['primary_marketcap_site'] == 'coinmarketcap' && sizeof($coinmarketcap_api) < 1 ) {
	 $coinmarketcap_api = coinmarketcap_api();
	 }
	
    
    // UI table coloring
    if ( !$td_color_zebra || $td_color_zebra == '#d6d4d4' ) {
    $td_color_zebra = 'white';
    }
    else {
    $td_color_zebra = '#d6d4d4';
    }

	
  
	 // Get coin values, including non-BTC pairings
    
    
    // Consolidate function calls for runtime speed improvement
    $asset_market_data = asset_market_data($asset_symbol, $selected_exchange, $market_id, $selected_pairing);
	 
	 ?>
	 
	 <script>
	 // DEBUGGING ONLY
	 //console.log("asset_symbol = <?=$asset_symbol?>; selected_pairing = <?=$selected_pairing?>; pairing_volume = <?=$asset_market_data['24hr_pairing_volume']?>; currency_volume = <?=$asset_market_data['24hr_primary_currency_volume']?>;");
	 </script>
	 
	 <?php
	 
	 // BTC PAIRINGS
    if ( $selected_pairing == 'btc' ) {
    $coin_value_raw = $asset_market_data['last_trade'];
    $btc_trade_eqiv = number_format($coin_value_raw, 8);
    $coin_value_total_raw = ($asset_amount * $coin_value_raw);
  	 $coin_primary_currency_worth_raw = $coin_value_total_raw * $selected_btc_primary_currency_value;
    $btc_worth_array[$asset_symbol] = $coin_value_total_raw;
    }
    // ETH ICOS
    elseif ( $selected_pairing == 'eth' && $selected_exchange == 'eth_subtokens_ico' ) {
    $pairing_btc_value = pairing_market_value($selected_pairing);
		if ( $pairing_btc_value == null ) {
		app_logging('market_error', 'pairing_market_value() returned null in ui_coin_data_row()', 'pairing: ' . $selected_pairing);
		}
    $coin_value_raw = get_sub_token_price($selected_exchange, $market_id);
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $coin_value_total_raw = ($asset_amount * $coin_value_raw);
  	 $coin_primary_currency_worth_raw = ($coin_value_total_raw * $pairing_btc_value) * $selected_btc_primary_currency_value;
    $btc_worth_array[$asset_symbol] = number_to_string($coin_value_total_raw * $pairing_btc_value);  
    }
    // OTHER PAIRINGS
    else {
    $pairing_btc_value = pairing_market_value($selected_pairing);
		if ( $pairing_btc_value == null ) {
		app_logging('market_error', 'pairing_market_value() returned null in ui_coin_data_row()', 'pairing: ' . $selected_pairing);
		}
    $coin_value_raw = $asset_market_data['last_trade'];
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $coin_value_total_raw = ($asset_amount * $coin_value_raw);
  	 $coin_primary_currency_worth_raw = ($coin_value_total_raw * $pairing_btc_value) * $selected_btc_primary_currency_value;
    $btc_worth_array[$asset_symbol] = ( strtolower($asset_name) == 'bitcoin' ? $asset_amount : number_to_string($coin_value_total_raw * $pairing_btc_value) );
  	 }
	
  	 
  	 
  	 
    // FLAG SELECTED PAIRING IF FIAT EQUIVALENT formatting should be used, AS SUCH
    // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
    if ( array_key_exists($selected_pairing, $app_config['power_user']['bitcoin_currency_markets']) && !array_key_exists($selected_pairing, $app_config['power_user']['crypto_pairing']) ) {
	 $fiat_eqiv = 1;
    }
    
  
	 
	 
  	 // Calculate gain / loss if purchase price was populated, AND asset held is at least 1 satoshi
	 if ( number_to_string($purchase_price) >= 0.00000001 && number_to_string($asset_amount) >= 0.00000001 ) {
	 	
	 $coin_paid_total_raw = ($asset_amount * $purchase_price);
	 
	 $gain_loss = $coin_primary_currency_worth_raw - $coin_paid_total_raw;
	 	 
	 	 
	 	// Convert $gain_loss for shorts with leverage
		if ( $leverage_level >= 2 && $selected_margintype == 'short' ) {
  		
 		$prev_gain_loss_val = $gain_loss;
 			
 			if ( $prev_gain_loss_val >= 0 ) {
 	 		$gain_loss = $prev_gain_loss_val - ( $prev_gain_loss_val * 2 );
 	 		$coin_primary_currency_worth_raw = $coin_primary_currency_worth_raw - ( $prev_gain_loss_val * 2 );
 		 	}
 	 		else {
 		 	$gain_loss = $prev_gain_loss_val + ( abs($prev_gain_loss_val) * 2 );
 			$coin_primary_currency_worth_raw = $coin_primary_currency_worth_raw + ( abs($prev_gain_loss_val) * 2 );
 	 		}

 	 	}
	 
	 
	 // Gain / loss percent (!MUST NOT BE! absolute value)
	 $gain_loss_percent = ($coin_primary_currency_worth_raw - $coin_paid_total_raw) / abs($coin_paid_total_raw) * 100;
	 
	 // Check for any leverage gain / loss
	 $only_leverage_gain_loss = ( $leverage_level >= 2 ? ($gain_loss * ($leverage_level - 1) ) : 0 );
	 
	 $inc_leverage_gain_loss = ( $leverage_level >= 2 ? ($gain_loss * $leverage_level) : $gain_loss );
	 
	 $inc_leverage_gain_loss_percent =  ( $leverage_level >= 2 ? ($gain_loss_percent * $leverage_level) : $gain_loss_percent );
	 
    
	 }
	 else {
	 $no_purchase_price = 1;
	 $purchase_price = null;
	 $coin_paid_total_raw = null;
	 }
	  
	 
	 
	 
	 
    $coin_stats_array[] = array(
    													'coin_symbol' => $asset_symbol, 
    													'coin_leverage' => $leverage_level,
    													'selected_margintype' => $selected_margintype,
    													'coin_worth_total' => $coin_primary_currency_worth_raw,
    													'coin_total_worth_if_purchase_price' => ($no_purchase_price == 1 ? null : $coin_primary_currency_worth_raw),
    													'coin_paid' => $purchase_price,
    													'coin_paid_total' => $coin_paid_total_raw,
    													'gain_loss_only_leverage' => $only_leverage_gain_loss,
    													'gain_loss_total' => $inc_leverage_gain_loss,
    													'gain_loss_percent_total' => $inc_leverage_gain_loss_percent,
    													);
    										




  // Get trade volume
  $trade_volume = $asset_market_data['24hr_primary_currency_volume'];
  
  
  
  // START rendering webpage UI output
  
  ?>


<!-- Coin data row START -->
<tr id='<?=strtolower($asset_symbol)?>_row'>
  


<td class='data border_lb'>

<span class='app_sort_filter'><?php echo $sort_order; ?></span>

</td>



<td class='data border_lb' align='right' style='position: relative; white-space: nowrap;'>
 
 
 <?php
 
 $mkcap_render_data = trim($app_config['portfolio_assets'][$asset_symbol]['marketcap_website_slug']);
 
// Consolidate function calls for runtime speed improvement
 $marketcap_data = marketcap_data($asset_symbol);
 
 $info_icon = ( !$marketcap_data['rank'] && $asset_symbol != 'MISCASSETS' ? 'info-red.png' : 'info.png' );
 
 
	if ( $mkcap_render_data != '' ) {
 	
 
 		if ( $app_config['general']['primary_marketcap_site'] == 'coinmarketcap' ) {
 		$asset_pagebase = 'coinmarketcap.com/currencies/';
 		}
 		elseif ( $app_config['general']['primary_marketcap_site'] == 'coingecko' ) {
 		$asset_pagebase = 'coingecko.com/en/coins/';
 		}
 	
 	
 		?>
 		
 <a title='' href='https://<?=$asset_pagebase?><?=$mkcap_render_data?>/' target='_blank' class='blue app_sort_filter'><?=$asset_name?></a> <img id='<?=$mkcap_render_data?>' src='templates/interface/media/images/<?=$info_icon?>' alt='' style='position: relative; vertical-align:middle; height: 30px; width: 30px;' /> 
 <script>

		<?php
		if ( !$marketcap_data['rank'] ) {
			
			if ( $app_config['general']['primary_marketcap_site'] == 'coinmarketcap' && trim($app_config['general']['coinmarketcapcom_api_key']) == null ) {
			?>

			var cmc_content = '<p class="coin_info"><span class="red_bright"><?=ucfirst($app_config['general']['primary_marketcap_site'])?> API key is required. <br />Configuration adjustments can be made in config.php.</span></p>';
	
			<?php
			}
			else {
			?>

			var cmc_content = '<p class="coin_info"><span class="red_bright"><?=ucfirst($app_config['general']['primary_marketcap_site'])?> API may be offline / under heavy load, <br />marketcap range not set high enough (current range is top <?=$app_config['power_user']['marketcap_ranks_max']?> marketcaps), <br />or API timeout set too low (current timeout is <?=$app_config['developer']['remote_api_timeout']?> seconds). <br /><br />Configuration adjustments can be made in config.php.</span></p>';
	
			<?php
			}

			if ( sizeof($alert_percent) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
			?>
			
			setTimeout(function() {
    		row_alert("<?=strtolower($asset_symbol)?>_row", "visual", "no_cmc", "<?=$theme_selected?>"); // Assets with marketcap data not set or functioning properly
			}, 1000);
			
			<?php
			}
		
        }
        else {
        	
        		if ( isset($cap_data_force_usd) ) {
        		$cmc_primary_currency_symbol = '$';
        		$cmc_primary_currency_ticker = 'USD';
        		}
        		else {
        		$cmc_primary_currency_symbol = $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']];
        		$cmc_primary_currency_ticker = strtoupper($app_config['general']['btc_primary_currency_pairing']);
        		}
        		
        ?> 
    
        var cmc_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;"><?=ucfirst($app_config['general']['primary_marketcap_site'])?>.com Summary For <?=$asset_name?> (<?=$asset_symbol?>):</h5>'
        
        		<?php
            if ( $marketcap_data['app_notice'] != '' ) {
        		?>
        +'<p class="coin_info red_bright">Notice: <?=$marketcap_data['app_notice']?></p>'
        		<?php
            }
        		?>
        
        +'<p class="coin_info"><span class="yellow">Marketcap Ranking:</span> #<?=$marketcap_data['rank']?></p>'
        +'<p class="coin_info"><span class="yellow">Marketcap Value:</span> <?=$cmc_primary_currency_symbol?><?=number_format($marketcap_data['market_cap'],0,".",",")?></p>'
        +'<p class="coin_info"><span class="yellow">Available Supply:</span> <?=number_format($marketcap_data['circulating_supply'], 0, '.', ',')?></p>'
        
        <?php
            if ( $marketcap_data['total_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Total Supply:</span> <?=number_format($marketcap_data['total_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( $marketcap_data['max_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Maximum Supply:</span> <?=number_format($marketcap_data['max_supply'], 0, '.', ',')?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">Unit Value (global average):</span> <?=$cmc_primary_currency_symbol?><?=$marketcap_data['price']?></p>'
        <?php
            if ( $marketcap_data['percent_change_1h'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">1 Hour Change:</span> <?=( stristr($marketcap_data['percent_change_1h'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_1h'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_1h'].'%</span>' )?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">24 Hour Change:</span> <?=( stristr($marketcap_data['percent_change_24h'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_24h'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_24h'].'%</span>' )?></p>'
        <?php
            if ( $marketcap_data['percent_change_7d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">7 Day Change:</span> <?=( stristr($marketcap_data['percent_change_7d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_7d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_7d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_14d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">14 Day Change:</span> <?=( stristr($marketcap_data['percent_change_14d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_14d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_14d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_30d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">30 Day Change:</span> <?=( stristr($marketcap_data['percent_change_30d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_30d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_30d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_90d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">90 Day Change:</span> <?=( stristr($marketcap_data['percent_change_90d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_90d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_90d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_200d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">200 Day Change:</span> <?=( stristr($marketcap_data['percent_change_200d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_200d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_200d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_1y'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">1 Year Change:</span> <?=( stristr($marketcap_data['percent_change_1y'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_1y'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_1y'].'%</span>' )?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">24 Hour Volume (global):</span> <?=$cmc_primary_currency_symbol?><?=number_format($marketcap_data['volume_24h'],0,".",",")?></p>'
        <?php
            if ( $marketcap_data['last_updated'] != '' ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Timestamp (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", $marketcap_data['last_updated'])?></p>'
        +'<p class="coin_info"><span class="yellow">App Cache Time:</span> <span class="bitcoin"><?=$app_config['power_user']['marketcap_cache_time']?> minute(s)</span></p>'
        <?php
            }
            ?>
    
        +'<p class="coin_info"><span class="yellow">*Current config setting retrieves the top <?=$app_config['power_user']['marketcap_ranks_max']?> rankings.</span></p>';
    
        <?php
        
        }
        ?>
    
        $('#<?=$mkcap_render_data?>').balloon({
        html: true,
        position: "right",
        contents: cmc_content,
        css: {
                fontSize: ".8rem",
                minWidth: ".8rem",
                padding: ".3rem .7rem",
                border: "2px solid rgba(212, 212, 212, .4)",
                borderRadius: "6px",
                boxShadow: "3px 3px 6px #555",
                color: "#eee",
                backgroundColor: "#111",
                opacity: "0.99",
                zIndex: "32767",
                textAlign: "left"
                }
        });
    
    
    <?php
    
    
        if ( sizeof($alert_percent) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
        	
        $percent_alert_filter = $alert_percent[2]; // gain / loss / both
    
        $percent_change_alert = $alert_percent[1];
    
        $percent_alert_type = $alert_percent[4];
    
    
            if ( $alert_percent[3] == '1hour' ) {
            $percent_change = $marketcap_data['percent_change_1h'];
            }
            elseif ( $alert_percent[3] == '24hour' ) {
            $percent_change = $marketcap_data['percent_change_24h'];
            }
            elseif ( $alert_percent[3] == '7day' ) {
            $percent_change = $marketcap_data['percent_change_7d'];
            }
          
         
            if ( $percent_alert_filter != 'gain' && stristr($percent_change, '-') != false && abs($percent_change) >= abs($percent_change_alert) && is_numeric($percent_change) ) {
            ?>
         
            setTimeout(function() {
               row_alert("<?=strtolower($asset_symbol)?>_row", "<?=$percent_alert_type?>", "yellow", "<?=$theme_selected?>");
            }, 1000);
            
            <?php
            }
            elseif ( $percent_alert_filter != 'loss' && stristr($percent_change, '-') == false && abs($percent_change) >= abs($percent_change_alert) && is_numeric($percent_change) ) {
            ?>
            
            setTimeout(function() {
               row_alert("<?=strtolower($asset_symbol)?>_row", "<?=$percent_alert_type?>", "green", "<?=$theme_selected?>");
            }, 1000);
            
            <?php
            }
        
        
        }
        ?>
     </script>
     
 <?php
	}
	else {
		
  ?>
  
  <span class='blue app_sort_filter'><?=$asset_name?></span> <img id='<?=$rand_id?>' src='templates/interface/media/images/<?=$info_icon?>' alt='' style='position: relative; vertical-align:middle; height: 30px; width: 30px;' /> 
 <script>
 
 			<?php
			if ( $asset_symbol == 'MISCASSETS' ) {
			?>

			var cmc_content = '<h5 class="yellow align_center" style="position: relative; white-space: nowrap;"><?=$asset_name?> (<?=$asset_symbol?>):</h5>'
    
        +'<p class="coin_info" style="white-space: normal; max-width: 600px;"><span class="yellow">Miscellaneous <?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?> value can be included in you portfolio stats, by entering it under the "MISCASSETS" asset on the "Update" page.</span></p>'
        
        +'<p class="coin_info" style="white-space: normal; max-width: 600px;"><span class="yellow">Additionally, you can see it\'s potential market value in another asset by changing the "Market" value on the "Portfolio" page to an asset other than <?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?>.</span></p>';
	
			<?php
			}
			else {
			?>
,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			var cmc_content = '<p class="coin_info"><span class="red_bright">No <?=ucfirst($app_config['general']['primary_marketcap_site'])?>.com data for <?=$asset_name?> (<?=$asset_symbol?>) has been configured yet.</span></p>';
	
			<?php
			}
			?>
 
 $('#<?=$rand_id?>').balloon({
  html: true,
  position: "right",
  contents: cmc_content,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
});

		<?php
		if ( sizeof($alert_percent) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
		?>
		
		setTimeout(function() {
    	row_alert("<?=strtolower($asset_symbol)?>_row", "visual", "no_cmc", "<?=$theme_selected?>"); // Assets with marketcap data not set or functioning properly
		}, 1000);
		
		<?php
		}
		?>
		
 </script>
 
	<?php
	}
 
 ?>
 
 
</td>



<td class='data border_b'>


<?php
  
  $coin_primary_currency_value = ( $selected_btc_primary_currency_value * $btc_trade_eqiv );

  // UX on FIAT EQUIV number values
  $coin_primary_currency_value = ( number_to_string($coin_primary_currency_value) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? pretty_numbers($coin_primary_currency_value, 2) : pretty_numbers($coin_primary_currency_value, $app_config['general']['primary_currency_decimals_max']) );
	
  echo "<span class='white'>" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . "</span>" . "<span class='app_sort_filter'>" . $coin_primary_currency_value . "</span>";

?>

</td>



<td class='data border_lb'>
 
    <select class='browser-default custom-select' name='change_<?=strtolower($asset_symbol)?>_market' title='Choose which exchange you want.' onchange='
    $("#<?=strtolower($asset_symbol)?>_market").val(this.value);
    $("#coin_amounts").submit();
    '>
        <?php
        foreach ( $all_pairing_markets as $market_key => $market_name ) {
         $loop = $loop + 1;
         	if ( $original_market == ($loop -1) ) {
         	$ui_selected_market = snake_case_to_name($market_key);
         	}
        ?>
        <option value='<?=($loop)?>' <?=( $original_market == ($loop -1) ? ' selected ' : '' )?>> <?=snake_case_to_name($market_key)?> </option>
        <?php
        }
        $loop = null;
        ?>
    </select>
    
    <div class='app_sort_filter' style='display: none;'><?=$ui_selected_market?></div>

</td>



<td class='data border_b' align='right'>

<span class='app_sort_filter'>

<?php 

	// UX on FIAT EQUIV number values
	if ( $fiat_eqiv == 1 ) {
	$coin_value_primary_currency_decimals = ( number_to_string($coin_value_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? 2 : $app_config['general']['primary_currency_decimals_max'] );
	}
  
echo ( $fiat_eqiv == 1 ? pretty_numbers($coin_value_raw, $coin_value_primary_currency_decimals) : pretty_numbers($coin_value_raw, 8) ); 

?>

</span>

<?php

  if ( $selected_pairing != 'btc' && strtolower($asset_name) != 'bitcoin' ) {
  echo '<div class="btc_worth">(' . pretty_numbers($btc_trade_eqiv, 8) . ' BTC)</div>';
  }
  
?>

</td>



<td class='data border_b'> 

 
    <select class='browser-default custom-select' name='change_<?=strtolower($asset_symbol)?>_pairing' title='Choose which market you want.' onchange='
    $("#<?=strtolower($asset_symbol)?>_pairing").val(this.value); 
    $("#<?=strtolower($asset_symbol)?>_market").val(1); // Just reset to first listed market for this pairing
    $("#coin_amounts").submit();
    '>
    
    
        <?php
		  
        $loop = 0;

	        foreach ( $all_pairings as $pairing_key => $pairing_name ) {
	         $loop = $loop + 1;
	         	if ( $selected_pairing == $pairing_key ) {
	         	$ui_selected_pairing = $pairing_key;
	         	}
	        ?>
	        <option value='<?=$pairing_key?>' <?=( $selected_pairing == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper($pairing_key)?> </option>
	        <?php
	        }
        
        $loop = null;
        
        ?>
        
        
    </select>
    
    <div class='app_sort_filter' style='display: none;'><?=$ui_selected_pairing?></div>

</td>



<td class='data border_b'>

<span class='white'><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?></span><span class='app_sort_filter'><?php 

  // NULL if not setup to get volume, negative number returned if no data received from API
  if ( $trade_volume == NULL || $trade_volume == -1 ) {
  echo '0';
  }
  elseif ( $trade_volume >= 0 ) {
  echo number_format($trade_volume, 0, '.', ',');
  }

?></span>

</td>



<td class='data border_lb blue' align='right'>

<?php

	if ( strtoupper($asset_symbol) == 'MISCASSETS' ) {
	$asset_amount_decimals = 2;
	}
	else {
	$asset_amount_decimals = 8;
	}
	
$pretty_coin_amount = pretty_numbers($asset_amount, $asset_amount_decimals);

echo "<span class='app_sort_filter blue'>" . ( $pretty_coin_amount != null ? $pretty_coin_amount : 0 ) . "</span>";

?>

</td>



<td class='data border_b'><span class='app_sort_filter'>

<?php echo $asset_symbol; ?></span>

</td>



<td class='data border_b blue'>

<?php


	// UX on FIAT EQUIV number values
	if ( $fiat_eqiv == 1 ) {
	$coin_value_total_primary_currency_decimals = ( number_to_string($coin_value_total_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? 2 : $app_config['general']['primary_currency_decimals_max'] );
	}
  
$pretty_coin_value_total_raw = ( $fiat_eqiv == 1 ? pretty_numbers($coin_value_total_raw, $coin_value_total_primary_currency_decimals) : pretty_numbers($coin_value_total_raw, 8) ); 


echo ' <span class="blue"><span class="data app_sort_filter blue">' . $pretty_coin_value_total_raw . '</span> ' . strtoupper($selected_pairing) . '</span>';

  if ( $selected_pairing != 'btc' && strtolower($asset_name) != 'bitcoin' ) {
  echo '<div class="btc_worth"><span>(' . pretty_numbers( $coin_value_total_raw * $pairing_btc_value, 8 ) . ' BTC)</span></div>';
  }

?>

</td>



<td class='data border_rb blue' style='white-space: nowrap;'>



<?php


echo '<span class="' . ( $purchase_price >= 0.00000001 && $leverage_level >= 2 && $selected_margintype == 'short' ? 'short">★ ' : 'blue">' ) . '<span class="blue">' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . '</span><span class="app_sort_filter blue">' . number_format($coin_primary_currency_worth_raw, 2, '.', ',') . '</span></span>';

  if ( $purchase_price >= 0.00000001 && $leverage_level >= 2 ) {

  $coin_worth_inc_leverage = $coin_primary_currency_worth_raw + $only_leverage_gain_loss;
  
  echo ' <span class="extra_data">(' . $leverage_level . 'x ' . $selected_margintype . ')</span>';

  // Here we parse out negative symbols
  $parsed_gain_loss = preg_replace("/-/", "-" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']], number_format( $gain_loss, 2, '.', ',' ) );
  
  $parsed_inc_leverage_gain_loss = preg_replace("/-/", "-" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']], number_format( $inc_leverage_gain_loss, 2, '.', ',' ) );
  
  $parsed_only_leverage_gain_loss = preg_replace("/-/", "-" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']], number_format($only_leverage_gain_loss, 2, '.', ',' ) );
  
  // Here we can go negative 'total worth' with the margin leverage (unlike with the margin deposit)
  // We only want a negative sign here in the UI for 'total worth' clarity (if applicable), NEVER a plus sign
  // (plus sign would indicate a gain, NOT 'total worth')
  $parsed_coin_worth_inc_leverage = preg_replace("/-/", "", number_format($coin_worth_inc_leverage, 2, '.', ',' ) );
  
  
  // Pretty format, but no need to parse out anything here
  $pretty_coin_primary_currency_worth_raw = number_format( ($coin_primary_currency_worth_raw) , 2, '.', ',' );
  $pretty_leverage_gain_loss_percent = number_format( $inc_leverage_gain_loss_percent, 2, '.', ',' );
  
  
  		// Formatting
  		$gain_loss_span_color = ( $gain_loss >= 0 ? 'green_bright' : 'red_bright' );
  		$gain_loss_primary_currency = ( $gain_loss >= 0 ? '+' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] : '' );
  		
		?> 
		<img id='<?=$rand_id?>_leverage' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' />
	 <script>
	
			var leverage_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;"><?=$leverage_level?>x <?=ucfirst($selected_margintype)?> For <?=$asset_name?> (<?=$asset_symbol?>):</h5>'
			
			+'<p class="coin_info"><span class="yellow">Deposit (1x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_gain_loss?></span> (<?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=$pretty_coin_primary_currency_worth_raw?>)</p>'
			
			+'<p class="coin_info"><span class="yellow">Margin (<?=($leverage_level - 1)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_only_leverage_gain_loss?></span></p>'
			
			+'<p class="coin_info"><span class="yellow">Total (<?=($leverage_level)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_inc_leverage_gain_loss?> / <?=( $gain_loss >= 0 ? '+' : '' )?><?=$pretty_leverage_gain_loss_percent?>%</span> (<?=( $coin_worth_inc_leverage >= 0 ? '' : '-' )?><?=$app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]?><?=$parsed_coin_worth_inc_leverage?>)</p>'
			
				
			+'<p class="coin_info"><span class="yellow"> </span></p>';
		
		
			$('#<?=$rand_id?>_leverage').balloon({
			html: true,
			position: "left",
			contents: leverage_content,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
		<?php
  		}

?>



</td>


  
</tr>
<!-- Coin data row END -->


<?php
  }
  
  // END of render webpage UI output
  // Stop rendering table row



}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>
<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// !!!!!!! MAKE SURE API'S TLD HAS SUPPORT ADDED IN 'top_level_domain_map' in Admin Config DEVELOPER section !!!!!!!
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


//////////////////////////////////////////////////////////


// We only need $pairing data if our function call needs 24hr trade volumes, so it's optional overhead
function asset_market_data($asset_symbol, $chosen_exchange, $market_id, $pairing=false) { 


global $selected_btc_primary_currency_value, $app_config, $defipulse_api_limit;
  
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
 
  
  
  if ( strtolower($chosen_exchange) == 'bigone' ) {
     
     
     $json_string = 'https://big.one/api/v3/asset_pairs/tickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
   
  	  $data = $data['data'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value["asset_pair_name"] == $market_id ) {
         	
         $result = array(
    							'last_trade' => $value["close"],
    							'24hr_asset_volume' => $value["volume"],
    							'24hr_pairing_volume' => null // No pairing volume data for this API
    						);
          
         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'binance' ) {
     
     
     $json_string = 'https://www.binance.com/api/v3/ticker/24hr';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $value['symbol'] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["lastPrice"],
    						'24hr_asset_volume' => $value["volume"],
    						'24hr_pairing_volume' => $value["quoteVolume"]
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'binance_us' ) {
     
     
     $json_string = 'https://api.binance.us/api/v3/ticker/24hr';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $value['symbol'] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["lastPrice"],
    						'24hr_asset_volume' => $value["volume"],
    						'24hr_pairing_volume' => $value["quoteVolume"]
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  

  elseif ( strtolower($chosen_exchange) == 'bit2c' ) {
  
  
  $json_string = 'https://bit2c.co.il/Exchanges/'.$market_id.'/Ticker.json';
  
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
  
  $data = json_decode($jsondata, true);
  
  $result = array(
    					'last_trade' => $data["ll"],
    					'24hr_asset_volume' => $data["a"],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    				);
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  

  elseif ( strtolower($chosen_exchange) == 'bitbns' ) {
  	
     
     $json_string = 'https://bitbns.com/order/getTickerWithVolume/';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $key == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["last_traded_price"],
    						'24hr_asset_volume' => $value["volume"]["volume"],
    						'24hr_pairing_volume' => null // No pairing volume data for this API
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'bitfinex' || strtolower($chosen_exchange) == 'ethfinex' ) {
  	
     
     $json_string = 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data as $object ) {
         
         if ( $object[0] == $market_id ) {
                 
          
         $result = array(
    							'last_trade' => $object[( sizeof($object) - 4 )],
    							'24hr_asset_volume' => $object[( sizeof($object) - 3 )],
    							'24hr_pairing_volume' => null // No pairing volume data for this API
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  

  elseif ( strtolower($chosen_exchange) == 'bitforex' ) {
  
  
  $json_string = 'https://api.bitforex.com/api/v1/market/ticker?symbol=' . $market_id;
  
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
  
  $data = json_decode($jsondata, true);
  
  $result = array(
    					'last_trade' => $data["data"]["last"],
    					'24hr_asset_volume' => $data["data"]["vol"],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    				);
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  

  elseif ( strtolower($chosen_exchange) == 'bitflyer' ) {
  
  
  $json_string = 'https://api.bitflyer.com/v1/getticker?product_code=' . $market_id;
  
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
  
  $data = json_decode($jsondata, true);
  
  $result = array(
    					'last_trade' => $data["ltp"],
    					'24hr_asset_volume' => $data["volume_by_product"],
    					'24hr_pairing_volume' => null // Seems to be an EXACT duplicate of asset volume in MANY cases, skipping to be safe
    				);
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'bitmex' || strtolower($chosen_exchange) == 'bitmex_u20' || strtolower($chosen_exchange) == 'bitmex_z20' ) {
  
  // GET NEWEST DATA SETS (25 one hour buckets, SINCE WE #NEED# THE CURRENT PARTIAL DATA SET, 
  // OTHERWISE WE DON'T GET THE LATEST TRADE VALUE AND CAN'T CALCULATE REAL-TIME VOLUME)
  $json_string = 'https://www.bitmex.com/api/v1/trade/bucketed?binSize=1h&partial=true&count=25&symbol='.$market_id.'&reverse=true'; // Sort NEWEST first
     
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
  $data = json_decode($jsondata, true);
   
  
      if (is_array($data) || is_object($data)) {
  
      	foreach ($data as $key => $value) {
         
       		// We only want the FIRST data set for trade value
         	if ( !$last_trade && $value['symbol'] == $market_id ) {
         	$last_trade = $value['close'];
         	$asset_volume = $value['homeNotional'];
         	$pairing_volume = $value['foreignNotional'];
         	}
         	elseif ( $value['symbol'] == $market_id ) {
         		
         	$asset_volume = number_to_string($asset_volume + $value['homeNotional']);
         	$pairing_volume = number_to_string($pairing_volume + $value['foreignNotional']);
    			
    			// Average of 24 hours, since we are always between 23.5 and 24.5
    			// (least resource-intensive way to get close enough to actual 24 hour volume)
         	// Overwrites until it's the last values
         	$half_oldest_hour_asset_volume = round($value['homeNotional'] / 2);
         	$half_oldest_hour_pairing_volume = round($value['foreignNotional'] / 2);
         	
         	}
       
      	}
  		
  		$result = array(
    						'last_trade' => $last_trade,
    						// Average of 24 hours, since we are always between 23.5 and 24.5
    						// (least resource-intensive way to get close enough to actual 24 hour volume)
    						'24hr_asset_volume' => number_to_string($asset_volume - $half_oldest_hour_asset_volume),
    						'24hr_pairing_volume' =>  number_to_string($pairing_volume - $half_oldest_hour_pairing_volume)
    						);
  
      }
      
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'bitpanda' ) {
 
     
     $json_string = 'https://api.exchange.bitpanda.com/public/v1/market-ticker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $value['instrument_code'] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["last_price"],
    						'24hr_asset_volume' => $value["base_volume"],
    						'24hr_pairing_volume' => $value["quote_volume"]
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  

  elseif ( strtolower($chosen_exchange) == 'bitso' ) {
  
  
  $json_string = 'https://api.bitso.com/v3/ticker/?book='.$market_id;
  
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
  
  $data = json_decode($jsondata, true);
  
  $data = $data['payload'];
  
  $result = array(
    					'last_trade' => $data["last"],
    					'24hr_asset_volume' => $data["volume"],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    				);
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'bitstamp' ) {
  	
  
  $json_string = 'https://www.bitstamp.net/api/v2/ticker/' . $market_id;
  
    $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
    
    $data = json_decode($jsondata, true);
    
    $result = array(
    					'last_trade' => number_format( $data['last'], 8, '.', ''),
    					'24hr_asset_volume' => $data["volume"],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    					);
    
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'bittrex' || strtolower($chosen_exchange) == 'bittrex_global' ) {
  
  $result = array();
     
     // LAST TRADE VALUE
     $json_string = 'https://api.bittrex.com/v3/markets/tickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value['symbol'] == $market_id ) {
          
         $result['last_trade'] = $value["lastTradeRate"];
          
         }
     
       }
      
      }
     
     
     usleep(55000); // Wait 0.055 seconds before fetching volume data
     
     
     // 24 HOUR VOLUME
     $json_string = 'https://api.bittrex.com/v3/markets/summaries';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value['symbol'] == $market_id ) {
          
         $result['24hr_asset_volume'] = $value["volume"];
         $result['24hr_pairing_volume'] = $value["quoteVolume"];
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'braziliex' ) {
     
     
     $json_string = 'https://braziliex.com/api/v1/public/ticker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value['market'] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["baseVolume24"],
    							'24hr_pairing_volume' => $value["quoteVolume24"]
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'btcmarkets' ) {
     
  
     $json_string = 'https://api.btcmarkets.net/market/'.$market_id.'/tick';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);

     $result = array(
    					'last_trade' => $data['lastPrice'],
    					'24hr_asset_volume' => $data["volume24h"],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    					);
   
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'btcturk' ) {
     
     
     $json_string = 'https://api.btcturk.com/api/v2/ticker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
     $data = $data['data'];
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value['pair'] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["volume"],
    							'24hr_pairing_volume' => null // No pairing volume data for this API
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'buyucoin' ) {
     
     
     $json_string = 'https://api.buyucoin.com/ticker/v1.0/liveData';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
     $data = $data['data'];
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value["marketName"] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["LTRate"],
    							'24hr_asset_volume' => $value["v24"], 
    							'24hr_pairing_volume' => $value["tp24"] 
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'cex' ) {
 
     
     $json_string = 'https://cex.io/api/tickers/BTC/USD/USDT/RUB/EUR/GBP';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
     $data = $data['data'];
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $value["pair"] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["last"],
    						'24hr_asset_volume' => $value["volume"],
    						'24hr_pairing_volume' => null // No pairing volume data for this API
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  

  elseif ( strtolower($chosen_exchange) == 'coinbase' ) {
  
  
     $json_string = 'https://api.pro.coinbase.com/products/'.$market_id.'/ticker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);

     $result = array(
    					'last_trade' => $data['price'],
    					'24hr_asset_volume' => $data["volume"],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    					);
   
   
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'coinex' ) {
 
     
     $json_string = 'https://api.coinex.com/v1/market/ticker/all';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
     $data = $data['data']['ticker'];
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
    // var_dump($value);
         
         
         if ( $key == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["last"],
    						'24hr_asset_volume' => $value["vol"],
    						'24hr_pairing_volume' => null // No pairing volume data for this API
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'cryptofresh' ) {
  
  
  $json_string = 'https://cryptofresh.com/api/asset/markets?asset=' . $market_id;
  
    $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
    
    $data = json_decode($jsondata, true);
	
		if ( preg_match("/BRIDGE/", $market_id) ) {
			
		$result = array(
    					'last_trade' => number_format( $data['BRIDGE.BTC']['price'], 8, '.', ''),
    					'24hr_asset_volume' => $data['BRIDGE.BTC']['volume24'],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    					);
    					
		}
		elseif ( preg_match("/OPEN/", $market_id) ) {
			
		$result = array(
    					'last_trade' => number_format( $data['OPEN.BTC']['price'], 8, '.', ''),
    					'24hr_asset_volume' => $data['OPEN.BTC']['volume24'],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    					);
    					
		}
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////


  // https://docs.defipulse.com/api-docs-by-provider/pools.fyi/exchange
  elseif ( strtolower($chosen_exchange) == 'defipulse' ) {
  	
  		
  		if ( trim($app_config['general']['defipulsecom_api_key']) == null ) {
  		app_logging('notify_error', '"defipulsecom_api_key" (free API key) is not configured in Admin Config GENERAL section', false, 'defipulsecom_api_key');
  		return false;
  		}
  		
  	
  	$asset_data = explode('/', $market_id);
  	
  	$defi_pools_info = defi_pools_info($asset_data);
  		
  		
  		if ( $defipulse_api_limit == true ) {
  		app_logging('notify_error', 'DeFiPulse.com monthly API limit exceeded (check your account there)', false, 'defipulsecom_api_limit');
  		return false;
  		}
      elseif ( !$defi_pools_info['pool_address'] ) {
  		app_logging('market_error', 'No DeFi liquidity pool found for ' . $market_id . ', try setting "defi_liquidity_pools_max" HIGHER in the POWER USER config (current setting is '.$app_config['power_user']['defi_liquidity_pools_max'].', results are sorted by highest trade volume pools first)');
  		return false;
  		}
     
     
     $json_string = 'https://data-api.defipulse.com/api/v1/blocklytics/pools/v1/trades/' . $defi_pools_info['pool_address'] . '?limit=' . $app_config['power_user']['defi_pools_max_trades'] . '&orderBy=timestamp&direction=desc&api-key=' . $app_config['general']['defipulsecom_api_key'];
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
     $data = $data['results'];
     
  
      if (is_array($data) || is_object($data)) {
      
       if ( preg_match("/curve/i", $defi_pools_info['platform']) ) {
       $fromSymbol = $asset_data[0];
       $toSymbol = $asset_data[1];
       }
       else {
       $fromSymbol = $asset_data[1];
       $toSymbol = $asset_data[0];
       }
       
  
       foreach ($data as $key => $value) {
       	
         // Check for main asset
         if ( $value["fromSymbol"] == $fromSymbol || preg_match("/([a-z]{1})".$fromSymbol."/", $value["fromSymbol"]) ) {
         $trade_asset = true;
         }
         			
         // Check for pairing asset
         if ( $value["toSymbol"] == $toSymbol || preg_match("/([a-z]{1})".$toSymbol."/", $value["toSymbol"]) ) {
         $trade_pairing = true;
         }
         			
         
         if ( $trade_asset && $trade_pairing ) {
          
         $result = array(
    						'defi_pool_name' => $defi_pools_info['pool_name'],
    						'defi_platform' => $defi_pools_info['platform'],
    						'last_trade' => $value["price"],
    						'24hr_asset_volume' => null, // No asset volume data for this API
    						'24hr_pairing_volume' => null, // No pairing volume data for this API
    						'24hr_usd_volume' => $defi_pools_info['pool_usd_volume']
    						);

         }
         
         
       
     	 $trade_asset = false;
     	 $trade_pairing = false;
       }
      
      
      	if ( !$result ) {
  			app_logging('market_error', 'No trades found for ' . $market_id . ', try setting "defi_pools_max_trades" HIGHER in the POWER USER config (current setting is '.$app_config['power_user']['defi_pools_max_trades'].', results are sorted by most recent trades first)');
      	}
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'gateio' ) {


     $json_string = 'https://api.gateio.ws/api/v4/spot/tickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value["currency_pair"] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["base_volume"],
    							'24hr_pairing_volume' => $value["quote_volume"]
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'gemini' ) {
  
  
  $json_string = 'https://api.gemini.com/v1/pubticker/' . $market_id;
  
    $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
    
    $data = json_decode($jsondata, true);
    
    $result = array(
    					'last_trade' => $data['last'],
    					'24hr_asset_volume' => $data['volume'][strtoupper($asset_symbol)],
    					'24hr_pairing_volume' => $data['volume'][strtoupper($pairing)]
    					);
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
  elseif ( strtolower($chosen_exchange) == 'graviex' ) {


     $json_string = 'https://graviex.net//api/v2/tickers.json';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $data[$market_id] != '' ) {
          
         $result = array(
    							'last_trade' => $data[$market_id]['ticker']['last'],
    							'24hr_asset_volume' => $data[$market_id]['ticker']['vol'],
    							'24hr_pairing_volume' => null // Weird pairing volume always in BTC according to array keyname, skipping
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'hitbtc' ) {


     $json_string = 'https://api.hitbtc.com/api/2/public/ticker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value["symbol"] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["volume"],
    							'24hr_pairing_volume' => $value["volumeQuote"]
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'hotbit' ) {


     $json_string = 'https://api.hotbit.io/api/v1/allticker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
   
  		$data = $data['ticker'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value["symbol"] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["vol"],
    							'24hr_pairing_volume' => null // No pairing volume data for this API
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'huobi' ) {
 
     
     $json_string = 'https://api.huobi.pro/market/tickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
     $data = $data['data'];
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $value["symbol"] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["close"],
    						'24hr_asset_volume' => $value["amount"],
    						'24hr_pairing_volume' => $value["vol"]
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'idex' ) {
     
     
     $json_string = 'https://api.idex.market/returnTicker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_id ) {
         	
         $result = array(
    							'last_trade' => $value["last"],
    							// ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
    							'24hr_asset_volume' => $value["quoteVolume"],
    							'24hr_pairing_volume' => $value["baseVolume"]
    						);
          
         }
       
     
       }
      
      }
  
  
  }
 
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'korbit' ) {
     
     
     $json_string = 'https://api.korbit.co.kr/v1/ticker/detailed/all';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_id ) {
         	
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["volume"],
    							'24hr_pairing_volume' => null // No pairing volume data for this API
    						);
          
         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'kraken' ) {
   	
   	
   	$kraken_pairs = null; // In case user messes up the config file, this helps
  		foreach ( $app_config['portfolio_assets'] as $markets ) {
  		
  			foreach ( $markets['market_pairing'] as $exchange_pairs ) {
  			
  				if ( isset($exchange_pairs['kraken']) && $exchange_pairs['kraken'] != '' ) { // In case user messes up the config file, this helps
				
				$kraken_pairs .= $exchange_pairs['kraken'] . ',';
				  				
  				}
  			
  			}
  			
  		}

		$kraken_pairs = substr($kraken_pairs, 0, -1);
		
   
  $json_string = 'https://api.kraken.com/0/public/Ticker?pair=' . $kraken_pairs;
  
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
  
  $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == 'result' ) {
         
          foreach ($value as $key2 => $value2) {
            
            if ( $key2 == $market_id ) {
             
            $result = array(
    								'last_trade' => $value[$key2]["c"][0],
    								'24hr_asset_volume' => $value[$key2]["v"][1],
    								'24hr_pairing_volume' => null // No pairing volume data for this API
    							);
             
            }
        
          }
       
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'kucoin' ) {


     $json_string = 'https://api.kucoin.com/api/v1/market/allTickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
  		$data = $data['data']['ticker'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ($value['symbol'] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["vol"],
    							'24hr_pairing_volume' => $value["volValue"]
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'liquid' ) {
 
     
     $json_string = 'https://api.liquid.com/products';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $value["currency_pair_code"] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["last_traded_price"],
    						'24hr_asset_volume' => $value["volume_24h"],
    						'24hr_pairing_volume' => null // No pairing volume data for this API
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'localbitcoins' ) {
     
     
     $json_string = 'https://localbitcoins.com/bitcoinaverage/ticker-all-currencies/';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $key == $market_id ) {
          
         $result = array(
    						'last_trade' => number_to_string($value["rates"]["last"]), // Handle large / small values better with number_to_string()
    						'24hr_asset_volume' => $value["volume_btc"],
    						'24hr_pairing_volume' => null // No pairing volume data for this API
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'luno' ) {
     
     
     $json_string = 'https://api.mybitx.com/api/1/tickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
     $data = $data['tickers'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $value["pair"] == $market_id ) {
          
         $result = array(
    						'last_trade' => number_to_string($value["last_trade"]), // Handle large / small values better with number_to_string()
    						'24hr_asset_volume' => $value["rolling_24_hour_volume"],
    						'24hr_pairing_volume' => null // No pairing volume data for this API
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'okcoin' ) {
  
  
    $json_string = 'https://www.okcoin.com/api/spot/v3/instruments/ticker';
    
    $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
    
    $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
       	
         
         if ( $value['instrument_id'] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value['last'],
    						'24hr_asset_volume' => $value['base_volume_24h'],
    						'24hr_pairing_volume' => $value['quote_volume_24h']
    						);

         }
       
     
       }
      
      }
  
    
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'okex' ) {
  	
  
  $json_string = 'https://www.okex.com/api/spot/v3/instruments/ticker';
  
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
    
  $data = json_decode($jsondata, true);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
       	
         
         if ( $value['instrument_id'] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["last"],
    						'24hr_asset_volume' => $value["base_volume_24h"],
    						'24hr_pairing_volume' => $value['quote_volume_24h']
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'poloniex' ) {


     $json_string = 'https://poloniex.com/public?command=returnTicker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_id ) {
          
         $result = array(
    							'last_trade' =>$value["last"],
    							// ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
    							'24hr_asset_volume' => $value["quoteVolume"],
    							'24hr_pairing_volume' => $value["baseVolume"]
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'southxchange' ) {


     $json_string = 'https://www.southxchange.com/api/prices';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value["Market"] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["Last"],
    							'24hr_asset_volume' => $value["Volume24Hr"],
    							'24hr_pairing_volume' => null // No pairing volume data for this API
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
  elseif ( strtolower($chosen_exchange) == 'tradeogre' ) {


     $json_string = 'https://tradeogre.com/api/v1/markets';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value[$market_id] != '' ) {
          
         $result = array(
    							'last_trade' => $value[$market_id]["price"],
    							'24hr_asset_volume' => null, // No asset volume data for this API
    							'24hr_pairing_volume' => $value[$market_id]["volume"]
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'upbit' ) {
  	
  	
  		$upbit_pairs = null; // In case user messes up the config file, this helps
  		foreach ( $app_config['portfolio_assets'] as $markets ) {
  		
  			foreach ( $markets['market_pairing'] as $exchange_pairs ) {
  			
  				if ( isset($exchange_pairs['upbit']) && $exchange_pairs['upbit'] != '' ) { // In case user messes up the config file, this helps
				
				$upbit_pairs .= $exchange_pairs['upbit'] . ',';
				  				
  				}
  			
  			}
  			
  		}

		$upbit_pairs = substr($upbit_pairs, 0, -1);


     $json_string = 'https://api.upbit.com/v1/ticker?markets=' . $upbit_pairs;
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data as $key => $value ) {
         
         if ( $value["market"] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["trade_price"],
    							'24hr_asset_volume' => $value["acc_trade_volume_24h"],
    							'24hr_pairing_volume' => null // No 24 hour trade volume going by array keynames, skipping
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'wazirx' ) {


     $json_string = 'https://api.wazirx.com/api/v2/tickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["volume"],
    							'24hr_pairing_volume' => null // No pairing volume data for this API
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'zebpay' ) {


     $json_string = 'https://www.zebapi.com/pro/v1/market/';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value['pair'] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["market"],
    							'24hr_asset_volume' => $value["volume"],
    							'24hr_pairing_volume' => null // No pairing volume data for this API
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'misc_assets' ) {
	
	
  // BTC value of 1 unit of the default primary currency
  $currency_to_btc = number_to_string(1 / $selected_btc_primary_currency_value);	
	
	  // BTC pairing
	  if ( $market_id == 'btc' ) {
     $result = array(
    					'last_trade' => $currency_to_btc
    					);
     }
     // All other pairing
	  else {
	  
	  $pairing_btc_value = pairing_btc_value($market_id);
	
			if ( $pairing_btc_value == null ) {
			app_logging('market_error', 'pairing_btc_value() returned null', 'market_id: ' . $market_id);
			}
	
     $result = array(
    					'last_trade' => ( 1 / number_to_string($pairing_btc_value / $currency_to_btc) )
    					);
     }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////


	if ( strtolower($chosen_exchange) != 'misc_assets' ) {
		
	// Better large / small number support
	$result['last_trade'] = number_to_string($result['last_trade']);
		
		// SET FIRST...emulate pairing volume if non-existent
		if ( is_numeric($result['24hr_pairing_volume']) != true ) {
		$result['24hr_pairing_volume'] = number_to_string($result['last_trade'] * $result['24hr_asset_volume']);
		}
	
		// Set primary currency volume value
		if ( $pairing == $app_config['general']['btc_primary_currency_pairing'] ) {
		$result['24hr_primary_currency_volume'] = number_to_string($result['24hr_pairing_volume']); // Save on runtime, if we don't need to compute the fiat value
		}
		elseif ( !$result['24hr_pairing_volume'] && $result['24hr_usd_volume'] ) {
			
			// Fiat or equivalent pairing?
			// #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
			if ( array_key_exists($pairing, $app_config['power_user']['bitcoin_currency_markets']) && !array_key_exists($pairing, $app_config['power_user']['crypto_pairing']) ) {
			$fiat_eqiv = 1;
			}
		
		$pairing_btc_value = pairing_btc_value($pairing);
		$usd_btc_value = pairing_btc_value('usd');
		
		$vol_in_btc = $result['24hr_usd_volume'] * $usd_btc_value;
		$vol_in_pairing = round( ($vol_in_btc / $pairing_btc_value) , ( $fiat_eqiv == 1 ? 0 : $app_config['power_user']['charts_crypto_volume_decimals'] ) );
		
		$result['24hr_pairing_volume'] = number_to_string($vol_in_pairing);
		$result['24hr_primary_currency_volume'] = number_to_string( primary_currency_trade_volume('BTC', 'usd', 1, $result['24hr_usd_volume']) );
		
		}
		else {
		$result['24hr_primary_currency_volume'] = number_to_string( primary_currency_trade_volume($asset_symbol, $pairing, $result['last_trade'], $result['24hr_pairing_volume']) );
		}
		
	
	}


return $result;

}



?>
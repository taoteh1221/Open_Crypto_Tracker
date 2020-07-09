<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// !!!!!!! MAKE SURE API'S TLD HAS SUPPORT ADDED IN $app_config['developer']['top_level_domain_map'] @ config.php !!!!!!!
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


//////////////////////////////////////////////////////////


// We only need $pairing data if our function call needs 24hr trade volumes, so it's optional overhead
function asset_market_data($asset_symbol, $chosen_exchange, $market_id, $pairing=false) { 


global $selected_btc_primary_currency_value, $app_config;
  
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
 
  
  
  if ( strtolower($chosen_exchange) == 'bigone' ) {
     
     
     $json_string = 'https://big.one/api/v2/tickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
   
  	  $data = $data['data'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value["market_id"] == $market_id ) {
         	
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
     
     
     $json_string = 'https://www.binance.com/api/v1/ticker/24hr';
     
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



  elseif ( strtolower($chosen_exchange) == 'bitmex' ) {
  
  // GET NEWEST DATA SETS (WE #NEED# PARTIAL DATA SETS, OTHERWISE WE DON'T GET THE LATEST TRADE VALUE)
  // WE DYNAMICALLY ADD 'startTime' TO THE END OF THE ENDPOINT REQUEST WITHIN THE external_api_data() CALL,
  // SO WE USE THE CACHE SETTINGS AND AVOID NEW CALLS EVERY RUNTIME (BECAUSE THE ENDPOINT URL IS ALWAYS DIFFERENT WITH TIME DATA IN IT)
  $json_string = 'https://www.bitmex.com/api/v1/trade/bucketed?binSize=1d&partial=true&reverse=true'; // Sort NEWEST first, 'startTime' added dynamically in external_api_data()
     
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
  $data = json_decode($jsondata, true);
   
  
      if (is_array($data) || is_object($data)) {
  
      	foreach ($data as $key => $value) {
         
       		// We only want the FIRST data set
         	if ( !$result && $value['symbol'] == $market_id ) {
         		
  				$result = array(
    								'last_trade' => $value['close'],
    								'24hr_asset_volume' => $value['homeNotional'],
    								'24hr_pairing_volume' =>  $value['foreignNotional']
    								);
  
         	}
       
      	}
      	
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
     
     
     $json_string = 'https://bittrex.com/api/v1.1/public/getmarketsummaries';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
  	  $data = $data['result'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value['MarketName'] == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["Last"],
    							// ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
    							'24hr_asset_volume' => $value["Volume"],
    							'24hr_pairing_volume' => $value["BaseVolume"]
    						);
          
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



  elseif ( strtolower($chosen_exchange) == 'coss' ) {
 
     
     $json_string = 'https://trade.coss.io/v1/getmarketsummaries';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
     $data = $data['result'];
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
    // var_dump($value);
         
         
         if ( $value['MarketName'] == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["Last"],
    						'24hr_asset_volume' => $value["Volume"],
    						'24hr_pairing_volume' => $value["BaseVolume"]
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



  elseif ( strtolower($chosen_exchange) == 'gateio' ) {


     $json_string = 'https://data.gate.io/api2/1/tickers';
     
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


     $json_string = 'https://api.hitbtc.com/api/1/public/ticker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_id ) {
          
         $result = array(
    							'last_trade' => $value["last"],
    							'24hr_asset_volume' => $value["volume"],
    							'24hr_pairing_volume' => $value["volume_quote"]
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



  elseif ( strtolower($chosen_exchange) == 'lakebtc' ) {
 
     
     $json_string = 'https://api.lakebtc.com/api_v2/ticker';
     
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



  elseif ( strtolower($chosen_exchange) == 'livecoin' ) {


     $json_string = 'https://api.livecoin.net/exchange/ticker';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $value['symbol'] == $market_id ) {
          
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


// https://github.com/namebasehq/exchange-api-documentation/blob/master/rest-api.md
  elseif ( strtolower($chosen_exchange) == 'namebase' ) {
  
  
    $json_string = 'https://www.namebase.io/api/v0/ticker/day?symbol=' . $market_id;
    
    $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
    
    $data = json_decode($jsondata, true);
    
    $result = array(
    					'last_trade' => $data['closePrice'],
    					'24hr_asset_volume' => $data['volume'],
    					'24hr_pairing_volume' => $data['quoteVolume']
    					);
    
    
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'okcoin' ) {
  
  
    $json_string = 'https://www.okcoin.com/api/v1/ticker.do?symbol=' . $market_id;
    
    $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
    
    $data = json_decode($jsondata, true);
    
    $result = array(
    					'last_trade' => number_format( $data['ticker']['last'], 2, '.', ''),
    					'24hr_asset_volume' => $data['ticker']['vol'],
    					'24hr_pairing_volume' => null // No pairing volume data for this API
    					);
    
    
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
    						'24hr_pairing_volume' => null // No pairing volume data for this API
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



  elseif ( strtolower($chosen_exchange) == 'tidebit' ) {
 
     
     $json_string = 'https://www.tidebit.com/api/v2/tickers';
     
     $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['last_trade_cache_time']);
     
     $data = json_decode($jsondata, true);
     
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $key == $market_id ) {
          
         $result = array(
    						'last_trade' => $value["ticker"]["last"],
    						'24hr_asset_volume' => $value["ticker"]["vol"],
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



  elseif ( strtolower($chosen_exchange) == 'zebpay' ) {


     $json_string = 'https://www.zebapi.com/api/v1/market';
     
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
  $currency_to_btc = ( 1 / $selected_btc_primary_currency_value );		
	
	  // BTC pairing
	  if ( $market_id == 'btc' ) {
     $result = array(
    					'last_trade' => $currency_to_btc
    					);
     }
     // All other pairing
	  else {
	  
	  $pairing_btc_value = pairing_market_value($market_id);
	
			if ( $pairing_btc_value == null ) {
			app_logging('market_error', 'pairing_market_value() returned null', 'market_id: ' . $market_id);
			}
	
     $result = array(
    					'last_trade' => ( 1 / ( $pairing_btc_value / $currency_to_btc ) )
    					);
     }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////


	if ( strtolower($chosen_exchange) != 'misc_assets' ) {
		
		// SET FIRST...emulate pairing volume if non-existent
		if ( is_numeric($result['24hr_pairing_volume']) != true ) {
		$result['24hr_pairing_volume'] = number_to_string($result['last_trade'] * $result['24hr_asset_volume']);
		}
	
		// Set primary currency volume value
		if ( $pairing == $app_config['general']['btc_primary_currency_pairing'] ) {
		$result['24hr_primary_currency_volume'] = number_to_string($result['24hr_pairing_volume']); // Save on runtime, if we don't need to compute the fiat value
		}
		else {
		$result['24hr_primary_currency_volume'] = number_to_string( primary_currency_trade_volume($asset_symbol, $pairing, $result['last_trade'], $result['24hr_pairing_volume']) );
		}
	
	}


return $result;

}



?>
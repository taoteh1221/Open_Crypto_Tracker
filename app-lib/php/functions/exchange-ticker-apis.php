<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////


function asset_market_data($asset_symbol, $chosen_exchange, $market_pairing, $pairing_config=false) {


global $btc_exchange, $btc_fiat_value, $coins_list, $last_trade_cache;
         	
         	
$pairing = ( $pairing_config != false ? $pairing_config : detect_pairing($market_pairing) );
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
 
  
  
  if ( strtolower($chosen_exchange) == 'bigone' ) {
     
     $json_string = 'https://big.one/api/v2/tickers';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
   
  	  $data = $data['data'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]["market_id"] == $market_pairing ) {
         	
         return  array(
    							'last_trade' => $data[$key]["close"],
    							'24hr_asset_volume' => $data[$key]["volume"],
    							'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["volume"], $data[$key]["close"])
    						);
          
         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'binance' ) {
     
     $json_string = 'https://www.binance.com/api/v1/ticker/24hr';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $data[$key]['symbol'] == $market_pairing ) {
          
         return  array(
    						'last_trade' => $data[$key]["lastPrice"],
    						'24hr_asset_volume' => $data[$key]["volume"],
    						'24hr_pairing_volume' => $data[$key]["quoteVolume"],
    						'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["volume"], $data[$key]["lastPrice"], $data[$key]["quoteVolume"])
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'binance_us' ) {
     
     $json_string = 'https://api.binance.us/api/v3/ticker/24hr';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $data[$key]['symbol'] == $market_pairing ) {
          
         return  array(
    						'last_trade' => $data[$key]["lastPrice"],
    						'24hr_asset_volume' => $data[$key]["volume"],
    						'24hr_pairing_volume' => $data[$key]["quoteVolume"],
    						'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["volume"], $data[$key]["lastPrice"], $data[$key]["quoteVolume"])
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'bitfinex' || strtolower($chosen_exchange) == 'ethfinex' ) {
  	
     
     $json_string = 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data as $object ) {
         
         if ( $object[0] == $market_pairing ) {
                 
          
         return  array(
    							'last_trade' => $object[( sizeof($object) - 4 )],
    							'24hr_asset_volume' => $object[( sizeof($object) - 3 )],
    							'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $object[( sizeof($object) - 3 )], $object[( sizeof($object) - 4 )])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  

  elseif ( strtolower($chosen_exchange) == 'bitforex' ) {
  
  $json_string = 'https://api.bitforex.com/api/v1/market/ticker?symbol=' . $market_pairing;
  
  $jsondata = @api_data('url', $json_string, $last_trade_cache);
  
  $data = json_decode($jsondata, TRUE);
  
  return  array(
    					'last_trade' => $data["data"]["last"],
    					'24hr_asset_volume' => $data["data"]["vol"],
    					'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    					'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data["data"]["vol"], $data["data"]["last"])
    				);
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'bitstamp' ) {
  	
  
  $json_string = 'https://www.bitstamp.net/api/v2/ticker/' . $market_pairing;
  
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['last'], 8, '.', ''),
    					'24hr_asset_volume' => $data["volume"],
    					'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    					'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data["volume"], $data["last"])
    					);
    
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'bittrex' || strtolower($chosen_exchange) == 'bittrex_global' ) {
     
     $json_string = 'https://bittrex.com/api/v1.1/public/getmarketsummaries';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  	  $data = $data['result'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]['MarketName'] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["Last"],
    							// ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
    							'24hr_asset_volume' => $data[$key]["Volume"],
    							'24hr_pairing_volume' => $data[$key]["BaseVolume"],
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["Volume"], $data[$key]["Last"], $data[$key]["BaseVolume"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'btcmarkets' ) {
     
  
     $json_string = 'https://api.btcmarkets.net/market/'.$market_pairing.'/tick';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);

     return  array(
    					'last_trade' => $data['lastPrice'],
    					'24hr_asset_volume' => $data["volume24h"],
    					'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    					'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data["volume24h"], $data['lastPrice'])
    					);
   
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  

  elseif ( strtolower($chosen_exchange) == 'coinbase' ) {
  
     $json_string = 'https://api.pro.coinbase.com/products/'.$market_pairing.'/ticker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);

     return  array(
    					'last_trade' => $data['price'],
    					'24hr_asset_volume' => $data["volume"],
    					'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    					'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data["volume"], $data['price'])
    					);
   
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'cryptofresh' ) {
  
  $json_string = 'https://cryptofresh.com/api/asset/markets?asset=' . $market_pairing;
  
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
	
		if ( preg_match("/BRIDGE/", $market_pairing) ) {
		return  array(
    					'last_trade' => number_format( $data['BRIDGE.BTC']['price'], 8, '.', ''),
    					'24hr_asset_volume' => $data['BRIDGE.BTC']['volume24'],
    					'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    					'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data['BRIDGE.BTC']['volume24'], number_format( $data['BRIDGE.BTC']['price'], 8, '.', ''))
    					);
		}
		elseif ( preg_match("/OPEN/", $market_pairing) ) {
		return  array(
    					'last_trade' => number_format( $data['OPEN.BTC']['price'], 8, '.', ''),
    					'24hr_asset_volume' => $data['OPEN.BTC']['volume24'],
    					'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    					'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data['OPEN.BTC']['volume24'], number_format( $data['OPEN.BTC']['price'], 8, '.', ''))
    					);
		}
  
    
    
    
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'gateio' ) {

     $json_string = 'https://data.gate.io/api2/1/tickers';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							// ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
    							'24hr_asset_volume' => $data[$key]["quoteVolume"],
    							'24hr_pairing_volume' => $data[$key]["baseVolume"],
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["quoteVolume"], $data[$key]["last"], $data[$key]["baseVolume"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'gemini' ) {
  
  $json_string = 'https://api.gemini.com/v1/pubticker/' . $market_pairing;
  
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return array(
    					'last_trade' => $data['last'],
    					'24hr_asset_volume' => $data['volume'][strtoupper($asset_symbol)],
    					'24hr_pairing_volume' => $data['volume'][strtoupper($pairing)],
    					'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data['volume'][strtoupper($asset_symbol)], $data['last'], $data['volume'][strtoupper($pairing)])
    					);
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
  elseif ( strtolower($chosen_exchange) == 'graviex' ) {

     $json_string = 'https://graviex.net//api/v2/tickers.json';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $data[$market_pairing] != '' ) {
          
         return  array(
    							'last_trade' => $data[$market_pairing]['ticker']['last'],
    							'24hr_asset_volume' => $data[$market_pairing]['ticker']['vol'],
    							'24hr_pairing_volume' => NULL, // Weird pairing volume always in BTC according to array keyname, skipping
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$market_pairing]['ticker']['vol'], $data[$market_pairing]['ticker']['last'])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'hitbtc' ) {

     $json_string = 'https://api.hitbtc.com/api/1/public/ticker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_asset_volume' => $data[$key]["volume"],
    							'24hr_pairing_volume' => $data[$key]["volume_quote"],
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["volume"], $data[$key]["last"], $data[$key]["volume_quote"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'hotbit' ) {

     $json_string = 'https://api.hotbit.io/api/v1/allticker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
   
  		$data = $data['ticker'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]["symbol"] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_asset_volume' => $data[$key]["vol"],
    							'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["vol"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'idex' ) {
     
     $json_string = 'https://api.idex.market/returnTicker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_pairing ) {
         	
         return  array(
    							'last_trade' => $data[$key]["last"],
    							// ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
    							'24hr_asset_volume' => $data[$key]["quoteVolume"],
    							'24hr_pairing_volume' => $data[$key]["baseVolume"],
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["quoteVolume"], $data[$key]["last"], $data[$key]["baseVolume"])
    						);
          
         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'kraken' ) {
   	
   	
  		foreach ( $coins_list as $markets ) {
  		
  			foreach ( $markets['market_pairing'] as $exchange_pairs ) {
  			
  				if ( $exchange_pairs['kraken'] != '' ) {
				
				$kraken_pairs .= $exchange_pairs['kraken'] . ',';
				  				
  				}
  			
  			}
  			
  		}

		$kraken_pairs = substr($kraken_pairs, 0, -1);
   
   $json_string = 'https://api.kraken.com/0/public/Ticker?pair=' . $kraken_pairs;
  
  $jsondata = @api_data('url', $json_string, $last_trade_cache);
  
  $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == 'result' ) {
         
          foreach ($data[$key] as $key2 => $value2) {
            
            if ( $key2 == $market_pairing ) {
             
            return  array(
    								'last_trade' => $data[$key][$key2]["c"][0],
    								'24hr_asset_volume' => $data[$key][$key2]["v"][1],
    								'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    								'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key][$key2]["v"][1], $data[$key][$key2]["c"][0])
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
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  		$data = $data['data']['ticker'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]['symbol'] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_asset_volume' => $data[$key]["vol"],
    							'24hr_pairing_volume' => $data[$key]["volValue"],
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["vol"], $data[$key]["last"], $data[$key]["volValue"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'livecoin' ) {

     $json_string = 'https://api.livecoin.net/exchange/ticker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]['symbol'] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_asset_volume' => $data[$key]["volume"],
    							'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["volume"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'okcoin' ) {
  
    $json_string = 'https://www.okcoin.com/api/v1/ticker.do?symbol=' . $market_pairing;
    
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['ticker']['last'], 2, '.', ''),
    					'24hr_asset_volume' => $data['ticker']['vol'],
    					'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    					'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data['ticker']['vol'], number_format( $data['ticker']['last'], 2, '.', ''))
    					);
    
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'okex' ) {
  	
  
  $json_string = 'https://www.okex.com/api/spot/v3/instruments/ticker';
  
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
       	
         
         if ( $data[$key]['instrument_id'] == $market_pairing ) {
          
         return  array(
    						'last_trade' => $data[$key]["last"],
    						'24hr_asset_volume' => $data[$key]["base_volume_24h"],
    						'24hr_pairing_volume' => NULL, // No pairing volume data for this API
    						'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["base_volume_24h"], $data[$key]["last"])
    						);

         }
       
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  


  elseif ( strtolower($chosen_exchange) == 'poloniex' ) {

     $json_string = 'https://poloniex.com/public?command=returnTicker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							// ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
    							'24hr_asset_volume' => $data[$key]["quoteVolume"],
    							'24hr_pairing_volume' => $data[$key]["baseVolume"],
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["quoteVolume"], $data[$key]["last"], $data[$key]["baseVolume"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////
  
  
  
  elseif ( strtolower($chosen_exchange) == 'tradeogre' ) {

     $json_string = 'https://tradeogre.com/api/v1/markets';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key][$market_pairing] != '' ) {
          
         return  array(
    							'last_trade' => $data[$key][$market_pairing]["price"],
    							'24hr_asset_volume' => NULL, // No asset volume data for this API
    							'24hr_pairing_volume' => $data[$key][$market_pairing]["volume"],
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key][$market_pairing]["volume"], $data[$key][$market_pairing]["price"], $data[$key][$market_pairing]["volume"]) 
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'tradesatoshi' ) {

     $json_string = 'https://tradesatoshi.com/api/public/getmarketsummaries';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  		$data = $data['result'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]['market'] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							// ARRAY KEY SEMANTICS BACKWARDS COMPARED TO OTHER EXCHANGES
    							'24hr_asset_volume' => $data[$key]["volume"],
    							'24hr_pairing_volume' => $data[$key]["baseVolume"],
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["volume"], $data[$key]["last"], $data[$key]["baseVolume"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'upbit' ) {
  	
  	
  		foreach ( $coins_list as $markets ) {
  		
  			foreach ( $markets['market_pairing'] as $exchange_pairs ) {
  			
  				if ( $exchange_pairs['upbit'] != '' ) {
				
				$upbit_pairs .= $exchange_pairs['upbit'] . ',';
				  				
  				}
  			
  			}
  			
  		}

		$upbit_pairs = substr($upbit_pairs, 0, -1);

     $json_string = 'https://api.upbit.com/v1/ticker?markets=' . $upbit_pairs;
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data as $key => $value ) {
         
         if ( $data[$key]["market"] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["trade_price"],
    							'24hr_asset_volume' => $data[$key]["acc_trade_volume_24h"],
    							'24hr_pairing_volume' => NULL, // No 24 hour trade volume going by array keynames, skipping
    							'24hr_fiat_volume' => trade_volume($asset_symbol, $pairing, $data[$key]["acc_trade_volume_24h"], $data[$key]["trade_price"])
    						);
          
         }
     
       }
      
      }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////



  elseif ( strtolower($chosen_exchange) == 'fiat_assets' ) {
		
	  $fiattobtc = ( 1 / $btc_fiat_value );		
		
	  if ( $market_pairing == 'fiattobtc' ) {
     return  array(
    					'last_trade' => $fiattobtc,
    					'24hr_asset_volume' => NULL,
    					'24hr_pairing_volume' => NULL,
    					'24hr_fiat_volume' => NULL
    					);
     }
	  elseif ( $market_pairing == 'fiattoxmr' ) {
     return  array(
    					'last_trade' => ( 1 / ( asset_market_data('XMR', 'binance', 'XMRBTC')['last_trade'] / $fiattobtc ) ),
    					'24hr_asset_volume' => NULL,
    					'24hr_pairing_volume' => NULL,
    					'24hr_fiat_volume' => NULL
    					);
     }
	  elseif ( $market_pairing == 'fiattoeth' ) {
     return  array(
    					'last_trade' => ( 1 / ( asset_market_data('ETH', 'binance', 'ETHBTC')['last_trade'] / $fiattobtc ) ),
    					'24hr_asset_volume' => NULL,
    					'24hr_pairing_volume' => NULL,
    					'24hr_fiat_volume' => NULL
    					);
     }
	  elseif ( $market_pairing == 'fiattoltc' ) {
     return  array(
    					'last_trade' => ( 1 / ( asset_market_data('LTC', 'binance', 'LTCBTC')['last_trade'] / $fiattobtc ) ),
    					'24hr_asset_volume' => NULL,
    					'24hr_pairing_volume' => NULL,
    					'24hr_fiat_volume' => NULL
    					);
     }
  
  
  }
 
 
 
 ////////////////////////////////////////////////////////////////////////////////////////////////


  
}



?>
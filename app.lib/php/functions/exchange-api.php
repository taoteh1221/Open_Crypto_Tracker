<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

//////////////////////////////////////////////////////////

function get_btc_usd($btc_exchange) {

global $last_trade_cache;
  
    if ( strtolower($btc_exchange) == 'coinbase' ) {
    
    $json_string = 'https://api.pro.coinbase.com/products/BTC-USD/ticker';
    
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['price'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['volume'], number_format( $data['price'], 2, '.', ''))
    					);

    }


  elseif ( strtolower($btc_exchange) == 'binance' ) {
     
     $json_string = 'https://www.binance.com/api/v1/ticker/24hr';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $data[$key]['symbol'] == 'BTCUSDT' ) {
          
         return  array(
    						'last_trade' => $data[$key]["lastPrice"],
    						'24hr_usd_volume' => volume_usd('bitcoin', $data[$key]["volume"], $data[$key]["lastPrice"])
    						);
          
         }
       
     
       }
      
      }
  
  
  }


  
  
    elseif ( strtolower($btc_exchange) == 'bitfinex' ) {
    
     
     $json_string = 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data as $object ) {
         
         if ( $object[0] == 'tBTCUSD' ) {
                 
         return  array(
    						'last_trade' => $object[( sizeof($object) - 4 )],
    						'24hr_usd_volume' => volume_usd('bitcoin', $object[( sizeof($object) - 3 )], $object[( sizeof($object) - 4 )])
    						);
          
         }
       
     
       }
      
      }
  
  
    }
  
  
    elseif ( strtolower($btc_exchange) == 'hitbtc' ) {
  
    $json_string = 'https://api.hitbtc.com/api/1/public/BTCUSD/ticker';
    
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['last'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['volume'], number_format( $data['last'], 2, '.', ''))
    					);

    }
  

    elseif ( strtolower($btc_exchange) == 'gemini' ) {
    
    $json_string = 'https://api.gemini.com/v1/pubticker/btcusd';
    
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
      
    $data = json_decode($jsondata, TRUE);
      
    return  array(
    					'last_trade' => number_format( $data['last'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['volume']['BTC'], number_format( $data['last'], 2, '.', ''))
    					);

    }


    elseif ( strtolower($btc_exchange) == 'okcoin' ) {
  
    $json_string = 'https://www.okcoin.com/api/v1/ticker.do?symbol=btc_usd';
    
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['ticker']['last'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['ticker']['vol'], number_format( $data['ticker']['last'], 2, '.', ''))
    					);
    
    }
  
  
    elseif ( strtolower($btc_exchange) == 'bitstamp' ) {
 	
    $json_string = 'https://www.bitstamp.net/api/ticker/';
    
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['last'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['volume'], number_format( $data['last'], 2, '.', ''))
    					);
    				
    }

   elseif ( strtolower($btc_exchange) == 'livecoin' ) {
 
 
      $json_string = 'https://api.livecoin.net/exchange/ticker';
      
      $jsondata = @api_data('url', $json_string, $last_trade_cache);
      
      $data = json_decode($jsondata, TRUE);
   
   
       if (is_array($data) || is_object($data)) {
         
             foreach ( $data as $key => $value ) {
               
               if ( $data[$key]['symbol'] == 'BTC/USD' ) {
                
    				return  array(
    									'last_trade' => $data[$key]["last"],
    									'24hr_usd_volume' => volume_usd('bitcoin', $data[$key]['volume'], $data[$key]["last"])
    									);
                 
               }
             
     
             }
             
       }
   
   
   }

   elseif ( strtolower($btc_exchange) == 'kraken' ) {
   
   $json_string = 'https://api.kraken.com/0/public/Ticker?pair=XXBTZUSD';
   
   $jsondata = @api_data('url', $json_string, $last_trade_cache);
   
   $data = json_decode($jsondata, TRUE);
   
       if (is_array($data) || is_object($data)) {
   
        foreach ($data as $key => $value) {
          
          if ( $key == 'result' ) {
          
           foreach ($data[$key] as $key2 => $value2) {
             
             if ( $key2 == 'XXBTZUSD' ) {
              
    				return  array(
    									'last_trade' => $data[$key][$key2]["c"][0],
    									'24hr_usd_volume' => volume_usd('bitcoin', $data[$key][$key2]["v"][1], $data[$key][$key2]["c"][0])
    									);
              
             }
           
         
           }
        
          }
      
        }
       
       }
   
   
   }
  

}


//////////////////////////////////////////////////////////


function get_coin_value($chosen_market, $market_pairing) {

global $btc_exchange, $coins_list, $last_trade_cache;
 

  if ( strtolower($chosen_market) == 'gemini' ) {
  
  $json_string = 'https://api.gemini.com/v1/pubticker/' . $market_pairing;
  
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return array(
    					'last_trade' => number_format( $data['last'], 8, '.', ''),
    					'24hr_usd_volume' => volume_usd($market_pairing, $data['volume'][strtoupper(detect_pairing($market_pairing))], '', detect_pairing($market_pairing))
    					);
  
  }


  elseif ( strtolower($chosen_market) == 'bitstamp' ) {
  	
  
  $json_string = 'https://www.bitstamp.net/api/v2/ticker/' . $market_pairing;
  
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['last'], 8, '.', ''),
    					'24hr_usd_volume' => volume_usd($market_pairing, $data["volume"], $data["last"])
    					);
    
  }



  elseif ( strtolower($chosen_market) == 'okex' ) {
  	
  	// Available markets listed here: https://www.okex.com/v2/markets/products
  
  $json_string = 'https://www.okex.com/api/v1/ticker.do?symbol=' . $market_pairing;
  
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['ticker']['last'], 8, '.', ''),
    					'24hr_usd_volume' => volume_usd($market_pairing, $data['ticker']["vol"], $data['ticker']["last"])
    					);
   
  }



  elseif ( strtolower($chosen_market) == 'binance' ) {
     
     $json_string = 'https://www.binance.com/api/v1/ticker/24hr';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
   
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $data[$key]['symbol'] == $market_pairing ) {
          
         return  array(
    						'last_trade' => $data[$key]["lastPrice"],
    						'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["volume"], $data[$key]["lastPrice"])
    						);

         }
       
     
       }
      
      }
  
  
  }



  elseif ( strtolower($chosen_market) == 'idex' ) {
     
     $json_string = 'https://api.idex.market/returnTicker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_pairing ) {
         	
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["baseVolume"], '', detect_pairing($market_pairing))
    						);
          
         }
       
     
       }
      
      }
  
  
  }
  
  
  elseif ( strtolower($chosen_market) == 'bigone' ) {
     
     $json_string = 'https://big.one/api/v2/tickers';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
   
  	  $data = $data['data'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]["market_id"] == $market_pairing ) {
         	
         return  array(
    							'last_trade' => $data[$key]["close"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["volume"], $data[$key]["close"])
    						);
          
         }
       
     
       }
      
      }
  
  
  }



  elseif ( strtolower($chosen_market) == 'coinbase' ) {
  
     $json_string = 'https://api.pro.coinbase.com/products/'.$market_pairing.'/ticker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);

     return  array(
    					'last_trade' => $data['price'],
    					'24hr_usd_volume' => volume_usd($market_pairing, $data["volume"], $data['price'])
    					);
   
  }
  

  elseif ( strtolower($chosen_market) == 'cryptofresh' ) {
  
  $json_string = 'https://cryptofresh.com/api/asset/markets?asset=' . $market_pairing;
  
    $jsondata = @api_data('url', $json_string, $last_trade_cache);
    
    $data = json_decode($jsondata, TRUE);
	
		if ( preg_match("/BRIDGE/", $market_pairing) ) {
		return  array(
    					'last_trade' => number_format( $data['BRIDGE.BTC']['price'], 8, '.', ''),
    					'24hr_usd_volume' => volume_usd($market_pairing, $data['BRIDGE.BTC']['volume24'], number_format( $data['BRIDGE.BTC']['price'], 8, '.', ''))
    					);
		}
		elseif ( preg_match("/OPEN/", $market_pairing) ) {
		return  array(
    					'last_trade' => number_format( $data['OPEN.BTC']['price'], 8, '.', ''),
    					'24hr_usd_volume' => volume_usd($market_pairing, $data['OPEN.BTC']['volume24'], number_format( $data['OPEN.BTC']['price'], 8, '.', ''))
    					);
		}
  
    
    
    
  
  }


  elseif ( strtolower($chosen_market) == 'bittrex' ) {
     
     $json_string = 'https://bittrex.com/api/v1.1/public/getmarketsummaries';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  	  $data = $data['result'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]['MarketName'] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["Last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["Volume"], $data[$key]["Last"])
    						);
          
         }
     
       }
      
      }
  
  
  }


  elseif ( strtolower($chosen_market) == 'tradesatoshi' ) {

     $json_string = 'https://tradesatoshi.com/api/public/getmarketsummaries';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  		$data = $data['result'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]['market'] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["volume"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'bitforex' ) {
  
  $json_string = 'https://api.bitforex.com/api/v1/market/ticker?symbol=' . $market_pairing;
  
  $jsondata = @api_data('url', $json_string, $last_trade_cache);
  
  $data = json_decode($jsondata, TRUE);
  
  return  array(
    					'last_trade' => $data["data"]["last"],
    					'24hr_usd_volume' => volume_usd($market_pairing, $data["data"]["vol"], $data["data"]["last"])
    				);
  
  }

  elseif ( strtolower($chosen_market) == 'poloniex' ) {

     $json_string = 'https://poloniex.com/public?command=returnTicker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["quoteVolume"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }
  
  elseif ( strtolower($chosen_market) == 'tradeogre' ) {

     $json_string = 'https://tradeogre.com/api/v1/markets';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key][$market_pairing] != '' ) {
          
         return  array(
    							'last_trade' => $data[$key][$market_pairing]["price"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key][$market_pairing]["volume"], '', detect_pairing($market_pairing))
    						);
          
         }
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'hotbit' ) {

     $json_string = 'https://api.hotbit.io/api/v1/allticker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
   
  		$data = $data['ticker'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]["symbol"] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["vol"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'gateio' ) {

     $json_string = 'https://data.gate.io/api2/1/tickers';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["quoteVolume"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'kucoin' ) {

     $json_string = 'https://api.kucoin.com/api/v1/market/allTickers';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  		$data = $data['data']['ticker'];
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]['symbol'] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["vol"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'livecoin' ) {

     $json_string = 'https://api.livecoin.net/exchange/ticker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $data[$key]['symbol'] == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["volume"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }
  
  elseif ( strtolower($chosen_market) == 'cryptopia' ) {

     $json_string = 'https://www.cryptopia.co.nz/api/GetMarkets';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
  		$data = $data['Data'];
  
      if (is_array($data) || is_object($data)) {
  
           foreach ($data as $key => $value) {
            
            if ( $data[$key]['Label'] == $market_pairing ) {
             
            return  array(
    							'last_trade' => $data[$key]["LastPrice"],
    							'24hr_usd_volume' => NULL  // Offline from hack still, will be back eventually
    							);
             
            }
        
          }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'hitbtc' ) {

     $json_string = 'https://api.hitbtc.com/api/1/public/ticker';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == $market_pairing ) {
          
         return  array(
    							'last_trade' => $data[$key]["last"],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["volume"], $data[$key]["last"])
    						);
          
         }
     
       }
      
      }
  
  
  }
  
  
  elseif ( strtolower($chosen_market) == 'graviex' ) {

     $json_string = 'https://graviex.net//api/v2/tickers.json';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         
         if ( $data[$market_pairing] != '' ) {
          
         return  array(
    							'last_trade' => $data[$market_pairing]['ticker']['last'],
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$market_pairing]['ticker']['vol'], $data[$market_pairing]['ticker']['last'])
    						);
          
         }
     
       }
      
      }
  
  
  }


  elseif ( strtolower($chosen_market) == 'kraken' ) {
  
  $json_string = 'https://api.kraken.com/0/public/Ticker?pair=' . $market_pairing;
  
  $jsondata = @api_data('url', $json_string, $last_trade_cache);
  
  $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         if ( $key == 'result' ) {
         
          foreach ($data[$key] as $key2 => $value2) {
            
            if ( $key2 == $market_pairing ) {
             
            return  array(
    								'last_trade' => $data[$key][$key2]["c"][0],
    								'24hr_usd_volume' => volume_usd($market_pairing, $data[$key][$key2]["v"][1], $data[$key][$key2]["c"][0])
    							);
             
            }
        
          }
       
         }
     
       }
      
      }
  
  
  }



  elseif ( strtolower($chosen_market) == 'upbit' ) {
  	
  	
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
    							'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["acc_trade_volume_24h"], $data[$key]["trade_price"])
    						);
          
         }
     
       }
      
      }
  
  
  }


  elseif ( strtolower($chosen_market) == 'ethfinex' || strtolower($chosen_market) == 'bitfinex' ) {
  	
     
     $json_string = 'https://api-pub.bitfinex.com/v2/tickers?symbols=ALL';
     
     $jsondata = @api_data('url', $json_string, $last_trade_cache);
     
     $data = json_decode($jsondata, TRUE);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data as $object ) {
         
         if ( $object[0] == $market_pairing ) {
                 
          
         return  array(
    							'last_trade' => $object[( sizeof($object) - 4 )],
    							'24hr_usd_volume' => volume_usd($market_pairing, $object[( sizeof($object) - 3 )], $object[( sizeof($object) - 4 )])
    						);
          
         }
     
       }
      
      }
  
  
  }


  elseif ( strtolower($chosen_market) == 'usd_assets' ) {
		
	  $usdtobtc = ( 1 / get_btc_usd($btc_exchange)['last_trade'] );		
		
	  if ( $market_pairing == 'usdtobtc' ) {
     return  array(
    					'last_trade' => $usdtobtc,
    					'24hr_usd_volume' => NULL
    					);
     }
	  elseif ( $market_pairing == 'usdtoxmr' ) {
     return  array(
    					'last_trade' => ( 1 / ( get_coin_value('binance', 'XMRBTC')['last_trade'] / $usdtobtc ) ),
    					'24hr_usd_volume' => NULL
    					);
     }
	  elseif ( $market_pairing == 'usdtoeth' ) {
     return  array(
    					'last_trade' => ( 1 / ( get_coin_value('binance', 'ETHBTC')['last_trade'] / $usdtobtc ) ),
    					'24hr_usd_volume' => NULL
    					);
     }
	  elseif ( $market_pairing == 'usdtoltc' ) {
     return  array(
    					'last_trade' => ( 1 / ( get_coin_value('binance', 'LTCBTC')['last_trade'] / $usdtobtc ) ),
    					'24hr_usd_volume' => NULL
    					);
     }
  
  
  }



  
}

//////////////////////////////////////////////////////////


?>
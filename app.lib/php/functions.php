<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

 
 
 
//////////////////////////////////////////////////////////
 function etherscan_api($block_info) {
 	
  $json_string = 'http://api.etherscan.io/api?module=proxy&action=eth_blockNumber';
  $jsondata = @get_data($json_string);
    
  $data = json_decode($jsondata, TRUE);
  
  $block_number = $data['result'];
    
    	if ( !$block_number ) {
    	return;
    	}
    	else {
		
  		$json_string = 'http://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true';
  		$jsondata = @get_data($json_string);
    	
    	$data = json_decode($jsondata, TRUE);
    	
    	//var_dump($data);
    	
    	$_SESSION['etherscan_data'] = $data['result'];
    	
    	return $_SESSION['etherscan_data'][$block_info];
  
    	}
  
}
//////////////////////////////////////////////////////////
 
 
 
 
//////////////////////////////////////////////////////////
function get_btc_usd($btc_in_usd) {

  
    if ( strtolower($btc_in_usd) == 'coinbase' ) {
    
    $json_string = 'https://api.coinbase.com/v2/prices/spot?currency=USD';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['data']['amount'], 2, '.', '');
  
    }
  
  
    elseif ( strtolower($btc_in_usd) == 'hitbtc' ) {
  
    $json_string = 'https://api.hitbtc.com/api/1/public/BTCUSD/ticker';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['last'], 2, '.', '');
  
    }
  
  
    elseif ( strtolower($btc_in_usd) == 'bitfinex' ) {
  
    $data = get_trade_price('bitfinex', 'tBTCUSD');
    
    return number_format( $data, 2, '.', '');
  
    }
  

    elseif ( strtolower($btc_in_usd) == 'gemini' ) {
    
    $json_string = 'https://api.gemini.com/v1/pubticker/btcusd';
    
      $jsondata = @get_data($json_string);
      
      $data = json_decode($jsondata, TRUE);
      
    return number_format( $data['last'], 2, '.', '');
      
    }


    elseif ( strtolower($btc_in_usd) == 'okcoin' ) {
  
    $json_string = 'https://www.okcoin.com/api/ticker.do?ok=1';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['ticker']['last'], 2, '.', '');
    
  
    }
  
  
    elseif ( strtolower($btc_in_usd) == 'bitstamp' ) {
 	
    $json_string = 'https://www.bitstamp.net/api/ticker/';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['last'], 2, '.', '');
    
    }
  
 
   elseif ( strtolower($btc_in_usd) == 'gatecoin' ) {
 
 
      if ( !$_SESSION['gatecoin_markets'] ) {
      
      $json_string = 'https://api.gatecoin.com/Public/LiveTickers';
      
      $jsondata = @get_data($json_string);
      
      $data = json_decode($jsondata, TRUE);
      
      $_SESSION['gatecoin_markets'] = $data;
    
      }
      else {
        
      $data = $_SESSION['gatecoin_markets'];
      
      }
   
   
   
   //var_dump($data);
       if (is_array($data) || is_object($data)) {
         
             foreach ( $data['tickers'] as $key => $value ) {
               
               if ( $data['tickers'][$key]["currencyPair"] == 'BTCUSD' ) {
                
               return $data['tickers'][$key]["last"];
                
                
               }
             
     
             }
             
       }
   
   
   }

   elseif ( strtolower($btc_in_usd) == 'livecoin' ) {
 
 
      if ( !$_SESSION['livecoin_markets'] ) {
      
      $json_string = 'https://api.livecoin.net/exchange/ticker';
      
      $jsondata = @get_data($json_string);
      
      $data = json_decode($jsondata, TRUE);
      
      $_SESSION['livecoin_markets'] = $data;
    
      }
      else {
        
      $data = $_SESSION['livecoin_markets'];
      
      }
   
   
   
   //var_dump($data);
       if (is_array($data) || is_object($data)) {
         
             foreach ( $data as $key => $value ) {
               
               if ( $data[$key]['symbol'] == 'BTC/USD' ) {
                
               return $data[$key]["last"];
                
                
               }
             
     
             }
             
       }
   
   
   }

   elseif ( strtolower($btc_in_usd) == 'kraken' ) {
   
   $json_string = 'https://api.kraken.com/0/public/Ticker?pair=XXBTZUSD';
   
   $jsondata = @get_data($json_string);
   
   $data = json_decode($jsondata, TRUE);
   
   //print_r($json_string);print_r($data);
   
       if (is_array($data) || is_object($data)) {
   
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
         if ( $key == 'result' ) {
     
         //print_r($data[$key]);
         
     foreach ($data[$key] as $key2 => $value2) {
       
       //print_r($data[$key][$key2]);
       
       if ( $key2 == 'XXBTZUSD' ) {
        
       return $data[$key][$key2]["c"][0];
        
        
       }
     
   
     }
       
         }
     
       }
       
       }
   
   
   }
  

}
//////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////
function get_trade_price($markets, $market_ids) {

global $coins_array;
 

  if ( strtolower($markets) == 'gemini' ) {
  
  $json_string = 'https://api.gemini.com/v1/pubticker/' . $market_ids;
  
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['last'], 8, '.', '');
    
  
  }


  elseif ( strtolower($markets) == 'binance' ) {
  
  $json_string = 'https://www.binance.com/api/v1/ticker/24hr?symbol=' . $market_ids;
  
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['lastPrice'], 8, '.', '');
    
  
  }


  elseif ( strtolower($markets) == 'coinbase' ) {
  
     $json_string = 'https://api.coinbase.com/v2/exchange-rates?currency=' . $market_ids;
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     return $data['data']['rates']['BTC'];
   
  }
  

  elseif ( strtolower($markets) == 'cryptofresh' ) {
  
  $json_string = 'https://cryptofresh.com/api/asset/markets?asset=' . $market_ids;
  
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['OPEN.BTC']['price'], 8, '.', '');
    
  
  }


  elseif ( strtolower($markets) == 'bittrex' ) {
     
     $json_string = 'https://bittrex.com/api/v1.1/public/getmarketsummaries';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
   
  
  $data = $data['result'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['MarketName'] == $market_ids ) {
         
        return $data[$key]["Last"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($markets) == 'mercatox' ) {

     $json_string = 'https://mercatox.com/public/json24';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
  
  
  $data = $data['pairs'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
        foreach ($data as $key => $value) {
          
          //print_r($key);
          
           if ( $key == $market_ids ) {
            
           return $data[$key]["last"];
            
            
           }
        
       
        }
      
      }
  
  
  }

  elseif ( strtolower($markets) == 'tradesatoshi' ) {

     $json_string = 'https://tradesatoshi.com/api/public/getmarketsummaries';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
  
  $data = $data['result'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['market'] == $market_ids ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($markets) == 'liqui' ) {
  
  $json_string = 'https://api.liqui.io/api/3/ticker/' . $market_ids;
  
  $jsondata = @get_data($json_string);
  
  $data = json_decode($jsondata, TRUE);
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == $market_ids ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  }

  elseif ( strtolower($markets) == 'poloniex' ) {

     $json_string = 'https://poloniex.com/public?command=returnTicker';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
   
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == $market_ids ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($markets) == 'kucoin' ) {

     $json_string = 'https://api.kucoin.com/v1/open/tick';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
   
  
  $data = $data['data'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['symbol'] == $market_ids ) {
         
        return $data[$key]["lastDealPrice"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($markets) == 'livecoin' ) {

     $json_string = 'https://api.livecoin.net/exchange/ticker';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['symbol'] == $market_ids ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }
  
  elseif ( strtolower($markets) == 'cryptopia' ) {

     $json_string = 'https://www.cryptopia.co.nz/api/GetMarkets';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
  
  $data = $data['Data'];
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
            foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['Label'] == $market_ids ) {
         
        return $data[$key]["LastPrice"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($markets) == 'hitbtc' ) {

     $json_string = 'https://api.hitbtc.com/api/1/public/ticker';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == $market_ids ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($markets) == 'bter' ) {

     $json_string = 'http://data.bter.com/api/1/marketlist';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //var_dump($data);
        
        if ( $data[$key]['pair'] == $market_ids ) {
         
        return $data[$key]['rate'];
         
         
        }
      
    
      }
      
      }
  
  
  }


  elseif ( strtolower($markets) == 'kraken' ) {
  
  $json_string = 'https://api.kraken.com/0/public/Ticker?pair=' . $market_ids;
  
  $jsondata = @get_data($json_string);
  
  $data = json_decode($jsondata, TRUE);
  
  //print_r($json_string);print_r($data);
  
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == 'result' ) {
    
        //print_r($data[$key]);
        
    foreach ($data[$key] as $key2 => $value2) {
      
      //print_r($data[$key][$key2]);
      
      if ( $key2 == $market_ids ) {
       
      return $data[$key][$key2]["c"][0];;
       
       
      }
    
  
    }
      
        }
    
      }
      
      }
  
  
  }



  elseif ( strtolower($markets) == 'gatecoin' ) {

     $json_string = 'https://api.gatecoin.com/Public/LiveTickers';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ( $data['tickers'] as $key => $value ) {
        
        if ( $data['tickers'][$key]["currencyPair"] == $market_ids ) {
         
        return $data['tickers'][$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }



  elseif ( strtolower($markets) == 'upbit' ) {
  	
  	
  		foreach ( $coins_array as $markets ) {
  		
  			foreach ( $markets['market_ids'] as $market_pairings ) {
  			
  				if ( $market_pairings['upbit'] != '' ) {
				
				$upbit_pairs .= 'CRIX.UPBIT.' . $market_pairings['upbit'] . ',';
				  				
  				}
  			
  			}
  			
  		}


     $json_string = 'https://crix-api-endpoint.upbit.com/v1/crix/recent?codes=' . $upbit_pairs;
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ( $data as $key => $value ) {
        
        if ( $data[$key]["code"] == 'CRIX.UPBIT.' . $market_ids ) {
         
        return $data[$key]["tradePrice"];
         
         
        }
      
    
      }
      
      }
  
  
  }


  elseif ( strtolower($markets) == 'ethfinex' || strtolower($markets) == 'bitfinex' ) {
  	
  	
  		foreach ( $coins_array as $markets ) {
  		
  			foreach ( $markets['market_ids'] as $market_pairings ) {
  			
  				if ( $market_pairings['ethfinex'] != '' ) {
				
				$finex_pairs .= $market_pairings['ethfinex'] . ',';
				  				
  				}
  				
  				if ( $market_pairings['bitfinex'] != '' ) {
				
				$finex_pairs .= $market_pairings['bitfinex'] . ',';
				  				
  				}
  			
  			}
  			
  		}


     $json_string = 'https://api.bitfinex.com/v2/tickers?symbols=' . $finex_pairs;
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
  
      if (is_array($data) || is_object($data)) {
  
      foreach ( $data as $object ) {
        
        if ( $object[0] == $market_ids ) {
        	
         //var_dump($object);
         
        return $object[( sizeof($object) - 4 )];
         
         
        }
      
    
      }
      
      }
  
  
  }


  
}
//////////////////////////////////////////////////////////
 
//////////////////////////////////////////////////////////
function get_sub_token_price($markets, $market_ids) {

global $eth_subtokens_ico_values;

 if ( strtolower($markets) == 'eth_subtokens_ico' ) {

  return $eth_subtokens_ico_values[$market_ids];
  }
 

}
///////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////
function strip_price_formatting($price) {

$price = preg_replace("/ /", "", $price); // Space
$price = preg_replace("/,/", "", $price); // Comma
$price = preg_replace("/  /", "", $price); // Tab

return $price;

}
//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////
function coinmarketcap_api($symbol) {

global $coinmarketcap_ranks_max;

     $json_string = 'https://api.coinmarketcap.com/v1/ticker/?limit=' . $coinmarketcap_ranks_max;
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
//  print_r($data);

      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['symbol'] == strtoupper($symbol) ) {
         
        return $data[$key];
         
         
        }
      
    
      }
      
      }
  
  
}
//////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////
function coin_data($coin_name, $trade_symbol, $coin_amount, $markets, $market_ids, $trade_pairing, $sort_order) {

global $_POST, $coins_array, $btc_in_usd, $alert_percent, $coinmarketcap_ranks_max, $api_timeout;


//var_dump($markets);


$orig_markets = $markets;  // Save this for dynamic HTML form

$all_markets = $coins_array[$trade_symbol]['market_ids'][$trade_pairing];  // Get all markets for this coin

  // Update, get the selected market name
  
  $loop = 0;
   foreach ( $all_markets as $key => $value ) {
   
    if ( $loop == $markets || $key == "eth_subtokens_ico" ) {
    $markets = $key;
     
     if ( $coin_name == 'Bitcoin' ) {
     $_SESSION['btc_in_usd'] = $key;
     }
     
    }
   
   $loop = $loop + 1;
   }
  $loop = NULL; 


if ( $_SESSION['btc_in_usd'] ) {
$btc_in_usd = $_SESSION['btc_in_usd'];
}


//var_dump($markets);

$market_ids = $market_ids[$markets];

//var_dump($market_ids);

  
  
  if ( $coin_amount > 0.00000000 ) {
    
    if ( !$_SESSION['td_color'] || $_SESSION['td_color'] == '#e8e8e8' ) {
    $_SESSION['td_color'] = 'white';
    }
    else {
    $_SESSION['td_color'] = '#e8e8e8';
    }

    if ( $trade_pairing == 'btc' ) {
    $coin_to_trade_raw = ( $coin_name == 'Bitcoin' ? get_btc_usd($btc_in_usd) : get_trade_price($markets, $market_ids) );
    $coin_to_trade = number_format( $coin_to_trade_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $coin_to_trade_worth_raw = ($coin_amount * $coin_to_trade_raw);
    $coin_to_trade_worth = number_format($coin_to_trade_worth_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $btc_worth = number_format( $coin_to_trade_worth_raw, 8 );  
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', ( $coin_name == 'Bitcoin' ? $coin_amount : $btc_worth ) );
    $trade_pairing_description = ( $coin_name == 'Bitcoin' ? 'US Dollar' : 'Bitcoin' );
    $trade_pairing_symbol = ( $coin_name == 'Bitcoin' ? 'USD' : 'BTC' );
    }
    else if ( $trade_pairing == 'ltc' ) {
    
    	if ( !$_SESSION['ltc_btc'] ) {
    	$_SESSION['ltc_btc'] = get_trade_price('poloniex', 'BTC_LTC');
    	}
    
    $coin_to_btc = $_SESSION['ltc_btc'];
    
    $coin_to_trade_raw = get_trade_price($markets, $market_ids);
    $coin_to_trade = number_format( $coin_to_trade_raw, 8, '.', ',');
    $coin_to_trade_worth_raw = ($coin_amount * $coin_to_trade_raw);
    $coin_to_trade_worth = number_format($coin_to_trade_worth_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_to_trade_worth_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_to_trade * $coin_to_btc), 8);
    $trade_pairing_description = 'Litecoin';
    $trade_pairing_symbol = 'LTC';
    
    }
    else if ( $trade_pairing == 'eth' ) {
    
    	if ( !$_SESSION['eth_btc'] ) {
    	$_SESSION['eth_btc'] = get_trade_price('poloniex', 'BTC_ETH');
    	}
    
    $coin_to_btc = $_SESSION['eth_btc'];
     
     if ( $markets == 'eth_subtokens_ico' ) {
     
     $coin_to_trade_raw = get_sub_token_price($markets, $market_ids);
     $coin_to_trade = number_format( $coin_to_trade_raw, 8, '.', ',');
     $coin_to_trade_worth_raw = ($coin_amount * $coin_to_trade_raw);
     $coin_to_trade_worth = number_format($coin_to_trade_worth_raw, 8, '.', ',');
     $btc_worth = number_format( ($coin_to_trade_worth_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_to_trade * $coin_to_btc), 8);
     $trade_pairing_description = 'Ethereum';
     $trade_pairing_symbol = 'ETH';
     
     }
     else {
      
     $coin_to_trade_raw = get_trade_price($markets, $market_ids);
     $coin_to_trade = number_format( $coin_to_trade_raw, 8, '.', ',');
     $coin_to_trade_worth_raw = ($coin_amount * $coin_to_trade_raw);
     $coin_to_trade_worth = number_format($coin_to_trade_worth_raw, 8, '.', ',');
     $btc_worth = number_format( ($coin_to_trade_worth_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_to_trade * $coin_to_btc), 8);
     $trade_pairing_description = 'Ethereum';
     $trade_pairing_symbol = 'ETH';
     
     }

    }
    else if ( $trade_pairing == 'usdt' ) {
    
    	if ( !$_SESSION['usdt_btc'] ) {
    	$_SESSION['usdt_btc'] = number_format( ( 1 / get_trade_price('poloniex', 'USDT_BTC') ), 8, '.', '');
    	}
    
    $coin_to_btc = $_SESSION['usdt_btc'];
    
    $coin_to_trade_raw = get_trade_price($markets, $market_ids);
    $coin_to_trade = number_format( $coin_to_trade_raw, 8, '.', ',');
    $coin_to_trade_worth_raw = ($coin_amount * $coin_to_trade_raw);
    $coin_to_trade_worth = number_format($coin_to_trade_worth_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_to_trade_worth_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_to_trade * $coin_to_btc), 8);
    $trade_pairing_description = 'Tether';
    $trade_pairing_symbol = 'USDT';
    
    }
  
  
  ?>
<tr id='<?=strtolower($trade_symbol)?>_row'>

<td class='data border_lb'><span><?php echo $sort_order; ?></span></td>

<td class='data border_lb'>
 
    <select name='change_<?=strtolower($trade_symbol)?>_market' onchange='
    document.coin_amounts.<?=strtolower($trade_symbol)?>_market.value = this.value; document.coin_amounts.submit();
    '>
        <?php
        foreach ( $all_markets as $market_key => $market_name ) {
         $loop = $loop + 1;
        ?>
        <option value='<?=($loop)?>' <?=( $orig_markets == ($loop -1) ? ' selected ' : '' )?>> <?=ucwords(preg_replace("/_/i", " ", $market_key))?> </option>
        <?php
        }
        $loop = NULL;
        ?>
    </select>

</td>

<td class='data border_lb' align='right' style='position: relative; padding-right: 32px; <?=( $coins_array[$trade_symbol]['ico'] == 'yes' ? 'padding-left: 32px;' : '' )?>'>
 
 <?php
 $cmkcap_render_data = trim($coins_array[$trade_symbol]['coinmarketcap']);
 
 if ( $cmkcap_render_data != '' ) {
 ?>
 <?=( $coins_array[$trade_symbol]['ico'] == 'yes' ? "<a title='SEC Website On ICO Guidance And Safety' href='https://www.sec.gov/ICO' target='_blank'><img src='templates/default/images/alert.png' border=0' style='position: absolute; top: 4px; left: 0px; margin: 0px; height: 30px; width: 30px;' /></a> " : "" )?><img id='<?=$cmkcap_render_data?>' src='templates/default/images/info.png' border=0' style='position: absolute; top: 4px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <a title='' href='http://coinmarketcap.com/currencies/<?=$cmkcap_render_data?>/' target='_blank'><?php echo $coin_name; ?></a>
 <script>

	<?php
	if ( coinmarketcap_api($trade_symbol)['rank'] == '' ) {
	?>

	var cmc_content = 'Coinmarketcap API may be offline / under heavy load, marketcap range not set high enough (current range is top <?=$coinmarketcap_ranks_max?> marketcaps), or API timeout set too low (current timeout is <?=$api_timeout?> seconds). Configuration adjustments can be made in config.php.';

	<?php
	}
	else {
	?> 

	var cmc_content = '<h3 class="orange">Coinmarketcap.com Summary For <?=$coin_name?> (<?=$trade_symbol?>):</h3>'
    +'<p><span class="orange">Marketcap Ranking:</span> #<?=coinmarketcap_api($trade_symbol)['rank']?></p>'
    +'<p><span class="orange">Marketcap (USD):</span> $<?=number_format(coinmarketcap_api($trade_symbol)['market_cap_usd'],0,".",",")?></p>'
    +'<p><span class="orange">24 Hour Volume (USD):</span> $<?=number_format(coinmarketcap_api($trade_symbol)['24h_volume_usd'],0,".",",")?></p>'
    +'<p><span class="orange">1 Hour Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['percent_change_1h'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['percent_change_1h'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['percent_change_1h'].'%</span>' )?></p>'
    +'<p><span class="orange">24 Hour Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['percent_change_24h'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['percent_change_24h'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['percent_change_24h'].'%</span>' )?></p>'
    +'<p><span class="orange">7 Day Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['percent_change_7d'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['percent_change_7d'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['percent_change_7d'].'%</span>' )?></p>'
    +'<p><span class="orange">Available Supply:</span> <?=number_format(coinmarketcap_api($trade_symbol)['available_supply'], 0, '.', ',')?></p>'
    +'<p><span class="orange">Total Supply:</span> <?=number_format(coinmarketcap_api($trade_symbol)['total_supply'], 0, '.', ',')?></p>'
    +'<p><span class="orange">Last Updated (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", coinmarketcap_api($trade_symbol)['last_updated'])?></p>';

	<?php
	}
	?>

	$('#<?=$cmkcap_render_data?>').balloon({
  	html: true,
  	position: "right",
  	contents: cmc_content
	});


<?php


if ( sizeof($alert_percent) > 1 ) {

$percent_change_alert = $alert_percent[0];

$percent_alert_type = $alert_percent[2];

 if ( $alert_percent[1] == '1hour' ) {
 $percent_change = coinmarketcap_api($trade_symbol)['percent_change_1h'];
 }
 elseif ( $alert_percent[1] == '24hour' ) {
 $percent_change = coinmarketcap_api($trade_symbol)['percent_change_24h'];
 }
 elseif ( $alert_percent[1] == '7day' ) {
 $percent_change = coinmarketcap_api($trade_symbol)['percent_change_7d'];
 }
 
  //echo 'console.log("' . $percent_change_alert . '|' . $percent_change . '");';
  
 
 if ( stristr($percent_change_alert, '-') != false && $percent_change_alert >= $percent_change && is_numeric($percent_change) ) {
 ?>
 
 setTimeout(function() {
    play_alert("<?=strtolower($trade_symbol)?>_row", "<?=$percent_alert_type?>", "yellow");
 }, 1000);
 
 <?php
 }
 elseif ( stristr($percent_change_alert, '-') == false && $percent_change_alert <= $percent_change && is_numeric($percent_change) ) {
 ?>
 
 setTimeout(function() {
    play_alert("<?=strtolower($trade_symbol)?>_row", "<?=$percent_alert_type?>", "green");
 }, 1000);
 
 <?php
 }

}
?>


 </script>
 <?php
 }
 else {
  $rand_id = rand(10000000,100000000);
  ?>
  <img id='<?=$rand_id?>' src='templates/default/images/info.png' border=0' style='position: absolute; top: 4px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <?=$coin_name?>
 <script>
 $('#<?=$rand_id?>').balloon({
  html: true,
  position: "right",
  contents: '<h3 class="orange">No Coinmarketcap.com data for <?=$coin_name?> (<?=$trade_symbol?>) has been configured yet.</h3>'
});
 </script>
 <?php
 }
 
 $cmkcap_render_data = NULL;
 $rand_id = NULL;
 ?>
 
</td>

<td class='data border_b'><span><?php

  if ( $btc_trade_eq ) {
  echo ' ($'.number_format(( get_btc_usd($btc_in_usd) * $btc_trade_eq ), 8, '.', ',').' USD)';
  }
  elseif ( $coin_name != 'Bitcoin' ) {
  echo ' ($'.number_format(( get_btc_usd($btc_in_usd) * $coin_to_trade ), 8, '.', ',').' USD)';
  }
  else {
  echo ' ($'.number_format(get_btc_usd($btc_in_usd), 2, '.', ',').' USD)';
  }

?></span></td>

<td class='data border_lb' align='right'><?php echo number_format($coin_amount, 8, '.', ','); ?></td>

<td class='data border_b'><span><?php echo $trade_symbol; ?></span></td>

<td class='data border_lb' align='right'><?php echo $coin_to_trade; ?>

<?php

  if ( $trade_pairing != 'btc' ) {
  echo '<div class="btc_worth">(' . ( $btc_trade_eq > 0.00000000 ? $btc_trade_eq : '0.00000000' ) . ' Bitcoin)</div>';
  }
  
?>


</td>

<td class='data border_b'> <span>(<?=$trade_pairing_description?>)</span></span></td>

<td class='data border_lb'><?php
echo ' <span><span class="data">' . $coin_to_trade_worth . '</span> ' . $trade_pairing_symbol . '</span>';
  if ( $trade_pairing != 'btc' ) {
  echo '<div class="btc_worth"><span>(' . $btc_worth . ' BTC)</span></div>';
  }

?></td>

<td class='data border_lrb'><?php

  if ( $trade_pairing == 'btc' ) {
  $coin_usd_worth = ( $coin_name == 'Bitcoin' ? $coin_to_trade_worth_raw : ($coin_to_trade_worth_raw * get_btc_usd($btc_in_usd)) );
  }
  else {
  $coin_usd_worth = ( ($coin_to_trade_worth_raw * $coin_to_btc) * get_btc_usd($btc_in_usd));
  }
  

echo '$' . number_format($coin_usd_worth, 2, '.', ',');

?></td>

</tr>

<?php
  }

}
//////////////////////////////////////////////////////////




//////////////////////////////////////////////////////////
function bitcoin_total() {

    if (is_array($_SESSION['btc_worth_array']) || is_object($_SESSION['btc_worth_array'])) {
      
  foreach ( $_SESSION['btc_worth_array'] as $coin_value ) {
  
  $total_value = ($coin_value + $total_value);
  
  }
  
    }

return $total_value;
}
//////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////
function get_data($url) {

global $version, $user_agent, $api_timeout;

// To avoid duplicate requests in current update session, AND cache data
$url_check = md5($url);


	if ( !$_SESSION['api_cache'][$url_check] ) {	
	
	$ch = curl_init();
	$cookie_jar = tempnam('/tmp','cookie');
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_jar);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $api_timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $api_timeout);
	
	$data = curl_exec($ch);
	
		if ( !$data ) {
		$data = 'no';
		$_SESSION['get_data_error'] .= ' No data returned from API endpoint "' . $url . '" (with timeout configuration setting of ' . $api_timeout . ' seconds). <br /> ';
		}
		
		elseif ( preg_match("/coinmarketcap/i", $url) && !preg_match("/last_updated/i", $data) ) {
		$_SESSION['get_data_error'] .= '##REQUEST## data error response from '.$url.': <br /> =================================== <br />' . $data . ' <br /> =================================== <br />';
		}
	
	
	curl_close($ch);
	unlink($cookie_jar) or die("Can't unlink $cookie_jar");
	
	$_SESSION['api_cache'][$url_check] = $data; // Cache API data for this update session
	
	// DEBUGGING ONLY
	//$_SESSION['get_data_error'] .= '##REQUEST## Request to endpoint "' . $url . '". <br /> ';
	
	}
	else {
		
	$data = $_SESSION['api_cache'][$url_check];
	
	// DEBUGGING ONLY
	//$_SESSION['get_data_error'] .= ' ##DUPLICATE## request ignored to endpoint "' . $url . '". <br /> ';
	
	
	}
	
	
return $data;


}
//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////

function trim_array($data) {

        foreach ( $data as $key => $value ) {
        $data[$key] = trim(remove_formatting($value));
        }
        
return $data;

}
//////////////////////////////////////////////////////////

function remove_formatting($data) {

$data = preg_replace("/ /i", "", $data); // Space
$data = preg_replace("/ /i", "", $data); // Tab
$data = preg_replace("/,/i", "", $data); // Comma
        
return $data;

}
//////////////////////////////////////////////////////////

function powerdown_usd($data) {

global $steem_market, $btc_in_usd;

return ( $data * $steem_market * get_btc_usd($btc_in_usd) );

}
//////////////////////////////////////////////////////////


function steempower_time($time) {
    
global $_POST, $steem_market, $btc_in_usd, $steem_powerdown_time, $steempower_yearly_interest;

$powertime = NULL;
$powertime = NULL;
$steem_total = NULL;
$usd_total = NULL;

$decimal_yearly_interest = $steempower_yearly_interest / 100;  // Convert APR in config to decimal representation

$speed = ($_POST['sp_total'] * $decimal_yearly_interest) / 525600;  // Interest per minute

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
    
    $powertime_usd = ( $powertime * $steem_market * get_btc_usd($btc_in_usd) );
    
    $steem_total = ( $powertime + $_POST['sp_total'] );
    $usd_total = ( $steem_total * $steem_market * get_btc_usd($btc_in_usd) );
    
    $power_purchased = ( $_POST['sp_purchased'] / $steem_total );
    $power_earned = ( $_POST['sp_earned'] / $steem_total );
    $power_interest = 1 - ( $power_purchased + $power_earned );
    
    $powerdown_total = ( $steem_total / $steem_powerdown_time );
    $powerdown_purchased = ( $powerdown_total * $power_purchased );
    $powerdown_earned = ( $powerdown_total * $power_earned );
    $powerdown_interest = ( $powerdown_total * $power_interest );
    
//echo $power_purchased;
//echo $power_earned;
//echo $power_interest;
    ?>
    
<div class='result'>
    <h2> Interest Per <?=ucfirst($time)?> </h2>
    <ul>
        
        <li><b><?=number_format( $powertime, 3, '.', ',')?> STEEM</b> <i>in interest</i>, after a <?=$time?> time period = <b>$<?=number_format( $powertime_usd, 2, '.', ',')?></b></li>
        
        <li><b><?=number_format( $steem_total, 3, '.', ',')?> STEEM</b> <i>in total</i>, including original vested amount = <b>$<?=number_format( $usd_total, 2, '.', ',')?></b></li>
    
    </ul>

        <table border='1' cellpadding='10' cellspacing='0'>
  <caption><b>A Power Down Weekly Payout <i>Started At This Time</i> Would Be (rounded to nearest cent):</b></caption>
         <thead>
            <tr>
        <th class='normal'> Purchased </th>
        <th class='normal'> Earned </th>
        <th class='normal'> Interest </th>
        <th> Total </th>
            </tr>
          </thead>
         <tbody>
                <tr>

                <td> <?=number_format( $powerdown_purchased, 3, '.', ',')?> STEEM = $<?=number_format( powerdown_usd($powerdown_purchased), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_earned, 3, '.', ',')?> STEEM = $<?=number_format( powerdown_usd($powerdown_earned), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_interest, 3, '.', ',')?> STEEM = $<?=number_format( powerdown_usd($powerdown_interest), 2, '.', ',')?> </td>
                <td> <b><?=number_format( $powerdown_total, 3, '.', ',')?> STEEM</b> = <b>$<?=number_format( powerdown_usd($powerdown_total), 2, '.', ',')?></b> </td>

                </tr>
           
        </tbody>
        </table>     
        
</div>

    <?php
    
}
//////////////////////////////////////////////////////////


?>
<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

 
 
 
//////////////////////////////////////////////////////////
 function etherscan_api($block_info) {


  if ( !$_SESSION['etherscan_data'] ) {
  
  $json_string = 'http://api.etherscan.io/api?module=proxy&action=eth_blockNumber';
  $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    $block_number = $data['result'];
    
  $json_string = 'http://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true';
  $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    //var_dump($data);
    
    $_SESSION['etherscan_data'] = $data['result'];
    
    return $_SESSION['etherscan_data'][$block_info];
  
  
  }
  else {
    
  return $_SESSION['etherscan_data'][$block_info];
  
  }
  

}
//////////////////////////////////////////////////////////
 
 
 
 
//////////////////////////////////////////////////////////
function get_btc_usd($btc_in_usd) {

  if ( !$_SESSION['btc_usd'] ) {

  
    if ( strtolower($btc_in_usd) == 'coinbase' ) {
  
    
  
    $json_string = 'https://api.coinbase.com/v2/prices/spot?currency=USD';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    $_SESSION['btc_usd'] = number_format( $data['data']['amount'], 2, '.', '');
    
    
    return $_SESSION['btc_usd'];
  
    }
  
    elseif ( strtolower($btc_in_usd) == 'hitbtc' ) {
  
    
  
    $json_string = 'https://api.hitbtc.com/api/1/public/BTCUSD/ticker';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    $_SESSION['btc_usd'] = number_format( $data['last'], 2, '.', '');
    
    
    return $_SESSION['btc_usd'];
  
    }
  
    elseif ( strtolower($btc_in_usd) == 'bitfinex' ) {
  
    
  
    $json_string = 'https://api.bitfinex.com/v1/pubticker/btcusd';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    $_SESSION['btc_usd'] = number_format( $data['last_price'], 2, '.', '');
    
    
    return $_SESSION['btc_usd'];
  
    }
  

    elseif ( strtolower($btc_in_usd) == 'gemini' ) {
    
    $json_string = 'https://api.gemini.com/v1/pubticker/btcusd';
    
      $jsondata = @get_data($json_string);
      
      $data = json_decode($jsondata, TRUE);
      
    
    $_SESSION['btc_usd'] = number_format( $data['last'], 2, '.', '');
    
    
    return $_SESSION['btc_usd'];
      
    
    }


    elseif ( strtolower($btc_in_usd) == 'okcoin' ) {
  
    
  
    $json_string = 'https://www.okcoin.com/api/ticker.do?ok=1';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    $_SESSION['btc_usd'] = number_format( $data['ticker']['last'], 2, '.', '');
    
    
    return $_SESSION['btc_usd'];
  
    }
  
  

    elseif ( strtolower($btc_in_usd) == 'bitstamp' ) {
  
    
  
    $json_string = 'https://www.bitstamp.net/api/ticker/';
    
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    $_SESSION['btc_usd'] = number_format( $data['last'], 2, '.', '');
    
    
    return $_SESSION['btc_usd'];
  
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
  else {
    
  return $_SESSION['btc_usd'];
  
  }
  

}
//////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////
function get_trade_price($markets, $market_ids) {
  



  if ( strtolower($markets) == 'bitfinex' ) {
  
  $json_string = 'https://api.bitfinex.com/v1/pubticker/' . $market_ids;
  
    $jsondata = @get_data($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['last_price'], 8, '.', '');
    
  
  }


  elseif ( strtolower($markets) == 'gemini' ) {
  
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


     if ( !$_SESSION['bittrex_markets'] ) {
     
     $json_string = 'https://bittrex.com/api/v1.1/public/getmarketsummaries';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['bittrex_markets'] = $data;
   
     }
     else {
       
     $data = $_SESSION['bittrex_markets'];
     
     }
  
  
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


     if ( !$_SESSION['mercatox_markets'] ) {
     
     $json_string = 'https://mercatox.com/public/json24';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['mercatox_markets'] = $data;
   
     }
     else {
       
     $data = $_SESSION['mercatox_markets'];
     
     }
  
  
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


     if ( !$_SESSION['tradesatoshi_markets'] ) {
     
     $json_string = 'https://tradesatoshi.com/api/public/getmarketsummaries';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['tradesatoshi_markets'] = $data;
   
     }
     else {
       
     $data = $_SESSION['tradesatoshi_markets'];
     
     }
  
  
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


     if ( !$_SESSION['poloniex_markets'] ) {
     
     $json_string = 'https://poloniex.com/public?command=returnTicker';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['poloniex_markets'] = $data;
   
     }
     else {
       
     $data = $_SESSION['poloniex_markets'];
     
     }
  
  
  
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

  elseif ( strtolower($markets) == 'livecoin' ) {


     if ( !$_SESSION['livecoin_markets'] ) {
     
     $json_string = 'https://api.livecoin.net/exchange/ticker';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['livecoin_markets'] = $data;
   
     }
     else {
       
     $data = $_SESSION['livecoin_markets'];
     
     }
  
  
  
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


     if ( !$_SESSION['cryptopia_markets'] ) {
     
     $json_string = 'https://www.cryptopia.co.nz/api/GetMarkets';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['cryptopia_markets'] = $data;
   
     }
     else {
       
     $data = $_SESSION['cryptopia_markets'];
     
     }
  
  
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


     if ( !$_SESSION['hitbtc_markets'] ) {
     
     $json_string = 'https://api.hitbtc.com/api/1/public/ticker';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['hitbtc_markets'] = $data;
   
     }
     else {
       
     $data = $_SESSION['hitbtc_markets'];
     
     }
  
  
  
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


     if ( !$_SESSION['bter_markets'] ) {
     
     $json_string = 'http://data.bter.com/api/1/marketlist';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     $data = $data['data'];
     
     $_SESSION['bter_markets'] = $data['data'];
   
     }
     else {
       
     $data = $_SESSION['bter_markets'];
     
     }
  
  
  
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
        
        if ( $data['tickers'][$key]["currencyPair"] == $market_ids ) {
         
        return $data['tickers'][$key]["last"];
         
         
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



     if ( !$_SESSION['coinmarketcap_api'] ) {
     
     $json_string = 'https://api.coinmarketcap.com/v1/ticker/';
     
     $jsondata = @get_data($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['coinmarketcap_api'] = $data;
   
     }
     else {
       
     $data = $_SESSION['coinmarketcap_api'];
     
     }
  
  
  //print_r($data);
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

global $_POST, $coins_array, $btc_in_usd, $alert_percent;


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
    $coin_to_trade_worth = ($coin_amount * $coin_to_trade_raw);
    $coin_to_trade_worth2 = number_format($coin_to_trade_worth, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $btc_worth = number_format( $coin_to_trade_worth, 8 );  
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
    $coin_to_trade_worth = ($coin_amount * $coin_to_trade_raw);
    $coin_to_trade_worth2 = number_format($coin_to_trade_worth, 8, '.', ',');
    $btc_worth = number_format( ($coin_to_trade_worth * $coin_to_btc), 8 );  // Convert value to bitcoin
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
     $coin_to_trade_worth = ($coin_amount * $coin_to_trade_raw);
     $coin_to_trade_worth2 = number_format($coin_to_trade_worth, 8, '.', ',');
     $btc_worth = number_format( ($coin_to_trade_worth * $coin_to_btc), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_to_trade * $coin_to_btc), 8);
     $trade_pairing_description = 'Ethereum';
     $trade_pairing_symbol = 'ETH';
     
     }
     else {
      
     $coin_to_trade_raw = get_trade_price($markets, $market_ids);
     $coin_to_trade = number_format( $coin_to_trade_raw, 8, '.', ',');
     $coin_to_trade_worth = ($coin_amount * $coin_to_trade_raw);
     $coin_to_trade_worth2 = number_format($coin_to_trade_worth, 8, '.', ',');
     $btc_worth = number_format( ($coin_to_trade_worth * $coin_to_btc), 8 );  // Convert value to bitcoin
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
    
    // DEBUGGING
    //echo ' usdt '; var_dump($coin_to_btc); var_dump($markets); var_dump($market_ids); var_dump($coin_to_trade_raw);
    
    $coin_to_trade = number_format( $coin_to_trade_raw, 8, '.', ',');
    $coin_to_trade_worth = ($coin_amount * $coin_to_trade_raw);
    $coin_to_trade_worth2 = number_format($coin_to_trade_worth, 8, '.', ',');
    $btc_worth = number_format( ($coin_to_trade_worth * $coin_to_btc), 8 );  // Convert value to bitcoin
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

<td class='data border_lb' align='right' style='position: relative; padding-right: 32px;'>
 
 <?php
 $cmkcap_render_data = trim($coins_array[$trade_symbol]['coinmarketcap']);
 
 if ( $cmkcap_render_data != '' ) {
 ?>
 <img id='<?=$cmkcap_render_data?>' src='templates/default/images/info.png' border=0' style='position: absolute; top: 4px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <a title='' href='http://coinmarketcap.com/currencies/<?=$cmkcap_render_data?>/' target='_blank'><?php echo $coin_name; ?></a>
 <script>
  
 $('#<?=$cmkcap_render_data?>').balloon({
  html: true,
  position: "right",
  contents: '<h3 class="orange">Coinmarketcap.com Summary For <?=$coin_name?> (<?=$trade_symbol?>):</h3>'
    +'<p><span class="orange">Marketcap Ranking:</span> #<?=coinmarketcap_api($trade_symbol)['rank']?></p>'
    +'<p><span class="orange">Marketcap (USD):</span> $<?=number_format(coinmarketcap_api($trade_symbol)['market_cap_usd'],0,".",",")?></p>'
    +'<p><span class="orange">24 Hour Volume (USD):</span> $<?=number_format(coinmarketcap_api($trade_symbol)['24h_volume_usd'],0,".",",")?></p>'
    +'<p><span class="orange">1 Hour Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['percent_change_1h'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['percent_change_1h'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['percent_change_1h'].'%</span>' )?></p>'
    +'<p><span class="orange">24 Hour Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['percent_change_24h'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['percent_change_24h'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['percent_change_24h'].'%</span>' )?></p>'
    +'<p><span class="orange">7 Day Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['percent_change_7d'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['percent_change_7d'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['percent_change_7d'].'%</span>' )?></p>'
    +'<p><span class="orange">Last Updated (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", coinmarketcap_api($trade_symbol)['last_updated'])?></p>'
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
echo ' <span><span class="data">' . $coin_to_trade_worth2 . '</span> ' . $trade_pairing_symbol . '</span>';
  if ( $trade_pairing != 'btc' ) {
  echo '<div class="btc_worth"><span>(' . $btc_worth . ' BTC)</span></div>';
  }

?></td>

<td class='data border_lrb'><?php

  if ( $trade_pairing == 'btc' ) {
  $coin_usd_worth = ( $coin_name == 'Bitcoin' ? $coin_to_trade_worth : ($coin_to_trade_worth * get_btc_usd($btc_in_usd)) );
  }
  else {
  $coin_usd_worth = ( ($coin_to_trade_worth * $coin_to_btc) * get_btc_usd($btc_in_usd));
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
function eth_difficulty() {


  $json_string = 'https://www.etherchain.org/api/basic_stats';
  
  $jsondata = @get_data($json_string);
  
  $data = json_decode($jsondata, TRUE);
  
  var_dump($jsondata);
  
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == 'data' ) {
         
        return $data[$key]["difficulty"]["difficulty"];
         
         
        }
      
    
      }
      
      }
  
 
}
//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////
function get_data($url) {

$ch = curl_init();
$timeout = 15;

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36');


curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

$data = curl_exec($ch);


//var_dump($data);

curl_close($ch);
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

global $steam_market, $btc_in_usd;

return ( $data * $steam_market * get_btc_usd($btc_in_usd) );

}
//////////////////////////////////////////////////////////


function steempower_time($time) {
    
global $_POST, $steam_market, $btc_in_usd, $steem_powerdown_time, $steempower_yearly_interest;

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
    
    $powertime_usd = ( $powertime * $steam_market * get_btc_usd($btc_in_usd) );
    
    $steem_total = ( $powertime + $_POST['sp_total'] );
    $usd_total = ( $steem_total * $steam_market * get_btc_usd($btc_in_usd) );
    
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
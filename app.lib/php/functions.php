<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


////////////////////////////////////////////////////////

function asset_alert($asset, $exchange, $pairing, $alert_level) {

global $coins_array, $btc_usd, $to_email, $cron_alerts_freq;

$asset_usd = ( $asset == 'BTC' ? $btc_usd : number_format( $btc_usd * get_trade_price($exchange, $coins_array[$asset]['market_pairing'][$pairing][$exchange]) , 8) );
$message = 'The ' . $asset . ' market at the ' . ucfirst($exchange) . ' exchange is at or above your set alert level of $'.$alert_level.'. The current value is $' . $asset_usd . '. ';
//$message = substr($message,0,60); // Make sure it will fit in a single text message

	if ( update_cache_file('cache/alerts/'.$asset.'.dat', $cron_alerts_freq) == true && floatval($asset_usd) >= floatval($alert_level) ) {
		
	safe_mail($to_email, $asset . ' Asset Value Increase Alert', $message);
	file_put_contents('cache/alerts/'.$asset.'.dat', 1, LOCK_EX);
	
	}

}
 
 
/////////////////////////////////////////////////////////

function validate_email($email) {


	$address = explode("@",$email);
	
	$domain = $address[1];
	
	// Validate "To" address
	if ( !$email || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/", $email) ) {
	return "Please enter a valid email address.";
	}
	elseif (function_exists("getmxrr") && !getmxrr($domain,$mxrecords)) {
	return "The email domain \"$domain\" appears incorrect.";
	}
	else {
	return "valid";
	}
			

}

/////////////////////////////////////////////////////////

function safe_mail($to, $subject, $message) {
	
global $from_email;

// Stop injection vulnerability for PHP < 7.2
$from_email = str_replace("\r\n", "\n", $from_email); // windows -> unix
$from_email = str_replace("\r", "\n", $from_email);   // remaining -> unix

$email_check = validate_email($to);

	if ( $email_check != 'valid' ) {
	return $email_check;
	}
			

	// Use array for safety from header injection >= PHP 7.2 
	if ( PHP_VERSION_ID >= 70200 ) {
	
	$headers = array(
	    					'From' => $from_email
	    					//'From' => $from_email,
	    					//'Reply-To' => $from_email,
	    					//'X-Mailer' => 'PHP/' . phpversion()
							);
	
	}
	else {
	$headers = 'From: ' . $from_email;
	}

return mail($to, $subject, $message, $headers);

}

 
/////////////////////////////////////////////////////////

function update_cache_file($cache_file, $minutes) {

	if ( file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * $minutes )) ) {
	   return false; 
	} 
	else {
	   // Our cache is out-of-date
	   return true;
	}

}

//////////////////////////////////////////////////////////
function etherscan_api($block_info) {
 	
  $json_string = 'http://api.etherscan.io/api?module=proxy&action=eth_blockNumber';
  $jsondata = @get_data('url', $json_string, 5);
    
  $data = json_decode($jsondata, TRUE);
  
  $block_number = $data['result'];
    
    	if ( !$block_number ) {
    	return;
    	}
    	else {
		
  		$json_string = 'http://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true';
  		$jsondata = @get_data('url', $json_string, 0);
    	
    	$data = json_decode($jsondata, TRUE);
    	
    	//var_dump($data);
    	
    	$_SESSION['etherscan_data'] = $data['result'];
    	
    	return $_SESSION['etherscan_data'][$block_info];
  
    	}
  
}
//////////////////////////////////////////////////////////
 
 
 
 
//////////////////////////////////////////////////////////
function decred_api($request) {
 		
 	$json_string = 'https://explorer.dcrdata.org/api/block/best/verbose';
 	$jsondata = @get_data('url', $json_string, 5);
  	
  	$data = json_decode($jsondata, TRUE);
    
		if ( !$data ) {
		return;
		}
		else {
		
		return $data[$request];
		  
		}
  
}
//////////////////////////////////////////////////////////
 
 
 
 
//////////////////////////////////////////////////////////
function mining_calc_form($calculation_form_data, $network_measure) {

global $_POST, $mining_rewards;

?>

				<form name='<?=$calculation_form_data[1]?>' action='index.php#calculators' method='post'>
				
				<p><b><?=ucfirst($network_measure)?>:</b> <input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calculation_form_data[3]) )?>' name='network_measure' /> (uses <a href='<?=$calculation_form_data[4]?>' target='_blank'><?=$calculation_form_data[5]?></a>)</p>
				
				<p><b>Your Hashrate:</b> <input type='text' value='<?=( $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['your_hashrate'] : '' )?>' name='your_hashrate' />
				
				<select name='hash_level'>
				<option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ths </option>
				<option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ghs </option>
				<option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Mhs </option>
				<option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Khs </option>
				<option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Hs </option>
				</select>
				</p>
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['block_reward'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['block_reward'] : $mining_rewards[$calculation_form_data[1]] )?>' name='block_reward' /> (may be static from config.php file, verify current block reward manually)</p>
				
				<p><b>Watts Used:</b> <input type='text' value='<?=( isset($_POST['watts_used']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['watts_used'] : '300' )?>' name='watts_used' /></p>
				
				<p><b>kWh Rate ($/kWh):</b> <input type='text' value='<?=( isset($_POST['watts_rate']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['watts_rate'] : '0.1000' )?>' name='watts_rate' /></p>
				
				<p><b>Pool Fee:</b> <input type='text' value='<?=( isset($_POST['pool_fee']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['pool_fee'] : '1' )?>' size='4' name='pool_fee' />%</p>
				    
			   <input type='hidden' value='1' name='<?=$calculation_form_data[1]?>_submitted' />
				
				<input type='submit' value='Calculate <?=strtoupper($calculation_form_data[1])?> Mining Profit' />
	
				</form>
				

<?php
  
}
//////////////////////////////////////////////////////////

 
 
 
//////////////////////////////////////////////////////////
function monero_api($request) {
 		
 	$json_string = 'https://moneroblocks.info/api/get_stats';
 	$jsondata = @get_data('url', $json_string, 5);
  	
  	$data = json_decode($jsondata, TRUE);
    
		if ( !$data ) {
		return;
		}
		else {
		
		return $data[$request];
		  
		}
  
}
//////////////////////////////////////////////////////////

 
 
 
//////////////////////////////////////////////////////////
function monero_reward() {
 		
 	return monero_api('last_reward') / 1000000000000;
  
}
//////////////////////////////////////////////////////////

 
 
 
//////////////////////////////////////////////////////////
function vertcoin_api($request) {
 		
		
		if ( $request == 'height' ) {
		
		return trim(@get_data('url', 'http://explorer.vertcoin.info/api/getblockcount', 5));
		  
		}
		elseif ( $request == 'difficulty' ) {
		
		return trim(@get_data('url', 'http://explorer.vertcoin.info/api/getdifficulty', 5));
		  
		}
  
}
//////////////////////////////////////////////////////////
 
 
 
 
//////////////////////////////////////////////////////////
function ravencoin_api($request) {
 		
		
		if ( $request == 'height' ) {
		
		return trim(@get_data('url', 'http://rvnhodl.com/api/getblockcount', 5));
		  
		}
		elseif ( $request == 'difficulty' ) {
		
		return trim(@get_data('url', 'http://rvnhodl.com/api/getdifficulty', 5));
		  
		}
  
}
//////////////////////////////////////////////////////////
 
 
 
 
//////////////////////////////////////////////////////////
function get_btc_usd($btc_in_usd) {

global $last_trade_ttl;
  
    if ( strtolower($btc_in_usd) == 'coinbase' ) {
    
    $json_string = 'https://api.coinbase.com/v2/prices/spot?currency=USD';
    
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['data']['amount'], 2, '.', '');
  
    }
  
  
    elseif ( strtolower($btc_in_usd) == 'hitbtc' ) {
  
    $json_string = 'https://api.hitbtc.com/api/1/public/BTCUSD/ticker';
    
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['last'], 2, '.', '');
  
    }
  
  
    elseif ( strtolower($btc_in_usd) == 'bitfinex' ) {
  
    $data = get_trade_price('bitfinex', 'tBTCUSD');
    
    return number_format( $data, 2, '.', '');
  
    }
  

    elseif ( strtolower($btc_in_usd) == 'gemini' ) {
    
    $json_string = 'https://api.gemini.com/v1/pubticker/btcusd';
    
      $jsondata = @get_data('url', $json_string, $last_trade_ttl);
      
      $data = json_decode($jsondata, TRUE);
      
    return number_format( $data['last'], 2, '.', '');
      
    }


    elseif ( strtolower($btc_in_usd) == 'okcoin' ) {
  
    $json_string = 'https://www.okcoin.com/api/ticker.do?ok=1';
    
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['ticker']['last'], 2, '.', '');
    
  
    }
  
  
    elseif ( strtolower($btc_in_usd) == 'bitstamp' ) {
 	
    $json_string = 'https://www.bitstamp.net/api/ticker/';
    
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['last'], 2, '.', '');
    
    }
  
 
   elseif ( strtolower($btc_in_usd) == 'gatecoin' ) {
 
      
      $json_string = 'https://api.gatecoin.com/Public/LiveTickers';
      
      $jsondata = @get_data('url', $json_string, $last_trade_ttl);
      
      $data = json_decode($jsondata, TRUE);
   
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
 
 
      $json_string = 'https://api.livecoin.net/exchange/ticker';
      
      $jsondata = @get_data('url', $json_string, $last_trade_ttl);
      
      $data = json_decode($jsondata, TRUE);
   
   
   
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
   
   $jsondata = @get_data('url', $json_string, $last_trade_ttl);
   
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
function get_trade_price($chosen_market, $market_pairing) {

global $btc_in_usd, $coins_array, $last_trade_ttl;
 

  if ( strtolower($chosen_market) == 'gemini' ) {
  
  $json_string = 'https://api.gemini.com/v1/pubticker/' . $market_pairing;
  
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['last'], 8, '.', '');
    
  
  }


  elseif ( strtolower($chosen_market) == 'bitstamp' ) {
  	
  
  $json_string = 'https://www.bitstamp.net/api/v2/ticker/' . $market_pairing;
  
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['last'], 8, '.', '');
    
  
  }



  elseif ( strtolower($chosen_market) == 'okex' ) {
  	
  	// Available markets listed here: https://www.okex.com/v2/markets/products
  
  $json_string = 'https://www.okex.com/api/v1/ticker.do?symbol=' . $market_pairing;
  
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['ticker']['last'], 8, '.', '');
    
  
  }



  elseif ( strtolower($chosen_market) == 'binance' ) {
  
  $json_string = 'https://www.binance.com/api/v1/ticker/24hr?symbol=' . $market_pairing;
  
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return number_format( $data['lastPrice'], 8, '.', '');
    
  
  }


  elseif ( strtolower($chosen_market) == 'coinbase' ) {
  
     $json_string = 'https://api.coinbase.com/v2/exchange-rates?currency=' . $market_pairing;
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
     return $data['data']['rates']['BTC'];
   
  }
  

  elseif ( strtolower($chosen_market) == 'cryptofresh' ) {
  
  $json_string = 'https://cryptofresh.com/api/asset/markets?asset=' . $market_pairing;
  
    $jsondata = @get_data('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
	
		if ( preg_match("/BRIDGE/", $market_pairing) ) {
		return number_format( $data['BRIDGE.BTC']['price'], 8, '.', '');
		}
		elseif ( preg_match("/OPEN/", $market_pairing) ) {
		return number_format( $data['OPEN.BTC']['price'], 8, '.', '');
		}
  
    
    
    
  
  }


  elseif ( strtolower($chosen_market) == 'bittrex' ) {
     
     $json_string = 'https://bittrex.com/api/v1.1/public/getmarketsummaries';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  $data = $data['result'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['MarketName'] == $market_pairing ) {
         
        return $data[$key]["Last"];
         
         
        }
      
    
      }
      
      }
  
  
  }


  elseif ( strtolower($chosen_market) == 'tradesatoshi' ) {

     $json_string = 'https://tradesatoshi.com/api/public/getmarketsummaries';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  $data = $data['result'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['market'] == $market_pairing ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'liqui' ) {
  
  $json_string = 'https://api.liqui.io/api/3/ticker/' . $market_pairing;
  
  $jsondata = @get_data('url', $json_string, $last_trade_ttl);
  
  $data = json_decode($jsondata, TRUE);
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == $market_pairing ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  }

  elseif ( strtolower($chosen_market) == 'poloniex' ) {

     $json_string = 'https://poloniex.com/public?command=returnTicker';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == $market_pairing ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'gateio' ) {

     $json_string = 'https://data.gate.io/api2/1/tickers';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == $market_pairing ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'kucoin' ) {

     $json_string = 'https://api.kucoin.com/v1/open/tick';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  $data = $data['data'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['symbol'] == $market_pairing ) {
         
        return $data[$key]["lastDealPrice"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'livecoin' ) {

     $json_string = 'https://api.livecoin.net/exchange/ticker';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['symbol'] == $market_pairing ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }
  
  elseif ( strtolower($chosen_market) == 'cryptopia' ) {

     $json_string = 'https://www.cryptopia.co.nz/api/GetMarkets';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  $data = $data['Data'];
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
            foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $data[$key]['Label'] == $market_pairing ) {
         
        return $data[$key]["LastPrice"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'hitbtc' ) {

     $json_string = 'https://api.hitbtc.com/api/1/public/ticker';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == $market_pairing ) {
         
        return $data[$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'bter' ) {

     $json_string = 'http://data.bter.com/api/1/marketlist';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //var_dump($data);
        
        if ( $data[$key]['pair'] == $market_pairing ) {
         
        return $data[$key]['rate'];
         
         
        }
      
    
      }
      
      }
  
  
  }
  
  
  elseif ( strtolower($chosen_market) == 'graviex' ) {

     $json_string = 'https://graviex.net//api/v2/tickers.json';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //var_dump($data);
        
        if ( $data[$market_pairing] != '' ) {
         
        return $data[$market_pairing]['ticker']['last'];
         
         
        }
      
    
      }
      
      }
  
  
  }


  elseif ( strtolower($chosen_market) == 'kraken' ) {
  
  $json_string = 'https://api.kraken.com/0/public/Ticker?pair=' . $market_pairing;
  
  $jsondata = @get_data('url', $json_string, $last_trade_ttl);
  
  $data = json_decode($jsondata, TRUE);
  
  //print_r($json_string);print_r($data);
  
      if (is_array($data) || is_object($data)) {
  
      foreach ($data as $key => $value) {
        
        //print_r($key);
        
        if ( $key == 'result' ) {
    
        //print_r($data[$key]);
        
    foreach ($data[$key] as $key2 => $value2) {
      
      //print_r($data[$key][$key2]);
      
      if ( $key2 == $market_pairing ) {
       
      return $data[$key][$key2]["c"][0];;
       
       
      }
    
  
    }
      
        }
    
      }
      
      }
  
  
  }



  elseif ( strtolower($chosen_market) == 'gatecoin' ) {

     $json_string = 'https://api.gatecoin.com/Public/LiveTickers';
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ( $data['tickers'] as $key => $value ) {
        
        if ( $data['tickers'][$key]["currencyPair"] == $market_pairing ) {
         
        return $data['tickers'][$key]["last"];
         
         
        }
      
    
      }
      
      }
  
  
  }



  elseif ( strtolower($chosen_market) == 'upbit' ) {
  	
  	
  		foreach ( $coins_array as $markets ) {
  		
  			foreach ( $markets['market_pairing'] as $exchange_pairs ) {
  			
  				if ( $exchange_pairs['upbit'] != '' ) {
				
				$upbit_pairs .= 'CRIX.UPBIT.' . $exchange_pairs['upbit'] . ',';
				  				
  				}
  			
  			}
  			
  		}


     $json_string = 'https://crix-api-endpoint.upbit.com/v1/crix/recent?codes=' . $upbit_pairs;
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
      if (is_array($data) || is_object($data)) {
  
      foreach ( $data as $key => $value ) {
        
        if ( $data[$key]["code"] == 'CRIX.UPBIT.' . $market_pairing ) {
         
        return $data[$key]["tradePrice"];
         
         
        }
      
    
      }
      
      }
  
  
  }


  elseif ( strtolower($chosen_market) == 'ethfinex' || strtolower($chosen_market) == 'bitfinex' ) {
  	
  	
  		foreach ( $coins_array as $markets ) {
  		
  			foreach ( $markets['market_pairing'] as $exchange_pairs ) {
  			
  				if ( $exchange_pairs['ethfinex'] != '' ) {
				
				$finex_pairs .= $exchange_pairs['ethfinex'] . ',';
				  				
  				}
  				
  				if ( $exchange_pairs['bitfinex'] != '' ) {
				
				$finex_pairs .= $exchange_pairs['bitfinex'] . ',';
				  				
  				}
  			
  			}
  			
  		}


     $json_string = 'https://api.bitfinex.com/v2/tickers?symbols=' . $finex_pairs;
     
     $jsondata = @get_data('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
  
      if (is_array($data) || is_object($data)) {
  
      foreach ( $data as $object ) {
        
        if ( $object[0] == $market_pairing ) {
        	
         //var_dump($object);
         
        return $object[( sizeof($object) - 4 )];
         
         
        }
      
    
      }
      
      }
  
  
  }


  elseif ( strtolower($chosen_market) == 'usd_assets' ) {
		
	  $usdtobtc = ( 1 / get_btc_usd($btc_in_usd) );		
		
	  if ( $market_pairing == 'usdtobtc' ) {
     return $usdtobtc;
     }
	  elseif ( $market_pairing == 'usdtoxmr' ) {
     return ( 1 / ( get_trade_price('poloniex', 'BTC_XMR') / $usdtobtc ) );
     }
	  elseif ( $market_pairing == 'usdtoeth' ) {
     return ( 1 / ( get_trade_price('poloniex', 'BTC_ETH') / $usdtobtc ) );
     }
	  elseif ( $market_pairing == 'usdtoltc' ) {
     return ( 1 / ( get_trade_price('poloniex', 'BTC_LTC') / $usdtobtc ) );
     }
  
  
  }



  
}
//////////////////////////////////////////////////////////
 
//////////////////////////////////////////////////////////
function get_sub_token_price($chosen_market, $market_pairing) {

global $eth_subtokens_ico_values;

 if ( strtolower($chosen_market) == 'eth_subtokens_ico' ) {

  return $eth_subtokens_ico_values[$market_pairing];
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
	
global $coinmarketcap_ranks_max, $coinmarketcap_ttl;


	if ( !$_SESSION['cmc_data'] ) {

		if ( !$_SESSION['cmc_json_array'] ) {
			
			
		//Coinmarketcap's new v2 API caps each API request at 100 assets, so we need to break requests up that are over 100 assets...
		$offset = 1;
		$rankings_left = $coinmarketcap_ranks_max;
		
			while ( $rankings_left > 0 ) {
					
				if ( $rankings_left > 99 ) {
				$limit = 100;
				}
				else {
				$limit = $rankings_left;
				}
			
			$_SESSION['cmc_json_array'][] = "https://api.coinmarketcap.com/v2/ticker/?start=".$offset."&limit=".$limit;
			
			$offset = $offset + $limit;
			$rankings_left = $rankings_left - $limit;
			
			}
	
		
		}
		
		
		foreach ( $_SESSION['cmc_json_array'] as $cmc_request ) {
			
     	$json_string = $cmc_request;
     	     
	  	$jsondata = @get_data('url', $json_string, $coinmarketcap_ttl);
	   
   	$data = json_decode($jsondata, TRUE);
    
    	$array_merging[] = $data['data'];
    	
	
		}
		
		$cmc_data = array(); // Empty array MUST be pre-defined for array_merge_recursive()
		foreach ( $array_merging as $array ) {
			
 	  	$cmc_data = array_merge_recursive($cmc_data, $array);
	   
 	   }
 	   
 	   $_SESSION['cmc_data'] = $cmc_data;
		

	}
	else {
	$cmc_data = $_SESSION['cmc_data'];
	}
		
	     	

     if ( is_array($cmc_data) || is_object($cmc_data) ) {
  		
  	   	foreach ($cmc_data as $key => $value) {
     	  	
  	     	
        		if ( $cmc_data[$key]['symbol'] == strtoupper($symbol) ) {
  	      		
        		return $cmc_data[$key];
        
        
     	  		}
    	 
    
  	   	}
      	
     
     	}
		  
  
}
//////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////
function coin_data($coin_name, $trade_symbol, $coin_amount, $market_pairing_array, $selected_pairing, $selected_market, $sort_order) {

global $_POST, $coins_array, $btc_in_usd, $alert_percent, $coinmarketcap_ranks_max, $api_timeout;


$original_market = $selected_market;

$all_markets = $coins_array[$trade_symbol]['market_pairing'][$selected_pairing];  // Get all markets for this coin

  // Update, get the selected market name
  
  $loop = 0;
   foreach ( $all_markets as $key => $value ) {
   
    if ( $loop == $selected_market || $key == "eth_subtokens_ico" ) {
    $selected_market = $key;
     
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


$market_pairing = $market_pairing_array[$selected_market];
  
  
  if ( $coin_amount > 0.00000000 ) {
    
    if ( !$_SESSION['td_color'] || $_SESSION['td_color'] == '#e8e8e8' ) {
    $_SESSION['td_color'] = 'white';
    }
    else {
    $_SESSION['td_color'] = '#e8e8e8';
    }

    if ( $selected_pairing == 'btc' ) {
    $coin_trade_raw = ( $coin_name == 'Bitcoin' ? get_btc_usd($btc_in_usd) : get_trade_price($selected_market, $market_pairing) );
    $coin_trade = number_format( $coin_trade_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $btc_worth = number_format( $coin_trade_total_raw, 8 );  
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', ( $coin_name == 'Bitcoin' ? $coin_amount : $btc_worth ) );
    $pairing_description = ( $coin_name == 'Bitcoin' ? 'US Dollar' : 'Bitcoin' );
    $pairing_symbol = ( $coin_name == 'Bitcoin' ? 'USD' : 'BTC' );
    }
    else if ( $selected_pairing == 'xmr' ) {
    
    	if ( !$_SESSION['xmr_btc'] ) {
    	$_SESSION['xmr_btc'] = get_trade_price('poloniex', 'BTC_XMR');
    	}
    
    $coin_to_btc = $_SESSION['xmr_btc'];
    
    $coin_trade_raw = get_trade_price($selected_market, $market_pairing);
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $coin_to_btc), 8);
    $pairing_description = 'Monero';
    $pairing_symbol = 'XMR';
    
    }
    else if ( $selected_pairing == 'ltc' ) {
    
    	if ( !$_SESSION['ltc_btc'] ) {
    	$_SESSION['ltc_btc'] = get_trade_price('poloniex', 'BTC_LTC');
    	}
    
    $coin_to_btc = $_SESSION['ltc_btc'];
    
    $coin_trade_raw = get_trade_price($selected_market, $market_pairing);
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $coin_to_btc), 8);
    $pairing_description = 'Litecoin';
    $pairing_symbol = 'LTC';
    
    }
    else if ( $selected_pairing == 'eth' ) {
    
    	if ( !$_SESSION['eth_btc'] ) {
    	$_SESSION['eth_btc'] = get_trade_price('poloniex', 'BTC_ETH');
    	}
    
    $coin_to_btc = $_SESSION['eth_btc'];
     
     if ( $selected_market == 'eth_subtokens_ico' ) {
     
     $coin_trade_raw = get_sub_token_price($selected_market, $market_pairing);
     $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
     $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
     $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
     $btc_worth = number_format( ($coin_trade_total_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_trade * $coin_to_btc), 8);
     $pairing_description = 'Ethereum';
     $pairing_symbol = 'ETH';
     
     }
     else {
      
     $coin_trade_raw = get_trade_price($selected_market, $market_pairing);
     $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
     $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
     $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
     $btc_worth = number_format( ($coin_trade_total_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_trade * $coin_to_btc), 8);
     $pairing_description = 'Ethereum';
     $pairing_symbol = 'ETH';
     
     }

    }
    else if ( $selected_pairing == 'usdt' ) {
    
    	if ( !$_SESSION['usdt_btc'] ) {
    	$_SESSION['usdt_btc'] = number_format( ( 1 / get_trade_price('poloniex', 'USDT_BTC') ), 8, '.', '');
    	}
    
    $coin_to_btc = $_SESSION['usdt_btc'];
    
    $coin_trade_raw = get_trade_price($selected_market, $market_pairing);
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $coin_to_btc), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $coin_to_btc), 8);
    $pairing_description = 'Tether';
    $pairing_symbol = 'USDT';
    
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
        <option value='<?=($loop)?>' <?=( $original_market == ($loop -1) ? ' selected ' : '' )?>> <?=ucwords(preg_replace("/_/i", " ", $market_key))?> </option>
        <?php
        }
        $loop = NULL;
        ?>
    </select>

</td>

<td class='data border_lb' align='right' style='position: relative; padding-right: 32px; <?=( $coins_array[$trade_symbol]['ico'] == 'yes' ? 'padding-left: 32px;' : '' )?>'>
 
 <?php
 $cmkcap_render_data = trim($coins_array[$trade_symbol]['coinmarketcap']);
 $info_icon = ( !coinmarketcap_api($trade_symbol)['rank'] ? 'info-none.png' : 'info.png' );
 
 if ( $cmkcap_render_data != '' ) {
 ?>
 <?=( $coins_array[$trade_symbol]['ico'] == 'yes' ? "<a title='SEC Website On ICO Guidance And Safety' href='https://www.sec.gov/ICO' target='_blank'><img src='templates/default/images/alert.png' border=0' style='position: absolute; top: 3px; left: 0px; margin: 0px; height: 30px; width: 30px;' /></a> " : "" )?><img id='<?=$cmkcap_render_data?>' src='templates/default/images/<?=$info_icon?>' border=0' style='position: absolute; top: 3px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <a title='' href='http://coinmarketcap.com/currencies/<?=$cmkcap_render_data?>/' target='_blank'><?php echo $coin_name; ?></a>
 <script>

	<?php
	if ( !coinmarketcap_api($trade_symbol)['rank'] ) {
	?>

	var cmc_content = '<h3 style="color: #e5f1ff;">Coinmarketcap API may be offline / under heavy load, <br />marketcap range not set high enough (current range is top <?=$coinmarketcap_ranks_max?> marketcaps), <br />or API timeout set too low (current timeout is <?=$api_timeout?> seconds). <br />Configuration adjustments can be made in config.php.</h3>';
	
		<?php
		if ( sizeof($alert_percent) > 1 ) {
		?>
		
		setTimeout(function() {
    	play_alert("<?=strtolower($trade_symbol)?>_row", "visual", "blue"); // Assets with CMC data not set or functioning properly
		}, 1000);
		
		<?php
		}
		
	}
	else {
	?> 

	var cmc_content = '<h3 class="orange">Coinmarketcap.com Summary For <?=$coin_name?> (<?=$trade_symbol?>):</h3>'
    +'<p><span class="orange">Average Market Price:</span> $<?=number_format(coinmarketcap_api($trade_symbol)['quotes']['USD']['price'],8,".",",")?></p>'
    +'<p><span class="orange">Marketcap Ranking:</span> #<?=coinmarketcap_api($trade_symbol)['rank']?></p>'
    +'<p><span class="orange">Marketcap (USD):</span> $<?=number_format(coinmarketcap_api($trade_symbol)['quotes']['USD']['market_cap'],0,".",",")?></p>'
    +'<p><span class="orange">24 Hour Volume (USD):</span> $<?=number_format(coinmarketcap_api($trade_symbol)['quotes']['USD']['volume_24h'],0,".",",")?></p>'
    +'<p><span class="orange">1 Hour Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_1h'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_1h'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_1h'].'%</span>' )?></p>'
    +'<p><span class="orange">24 Hour Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_24h'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_24h'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_24h'].'%</span>' )?></p>'
    +'<p><span class="orange">7 Day Change:</span> <?=( stristr(coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_7d'], '-') != false ? '<span class="red">'.coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_7d'].'%</span>' : '<span class="green">'.coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_7d'].'%</span>' )?></p>'
    +'<p><span class="orange">Available Supply:</span> <?=number_format(coinmarketcap_api($trade_symbol)['circulating_supply'], 0, '.', ',')?></p>'
    +'<p><span class="orange">Total Supply:</span> <?=number_format(coinmarketcap_api($trade_symbol)['total_supply'], 0, '.', ',')?></p>'
    <?php
		if ( coinmarketcap_api($trade_symbol)['max_supply'] > 0 ) {
		?>
    +'<p><span class="orange">Maximum Supply:</span> <?=number_format(coinmarketcap_api($trade_symbol)['max_supply'], 0, '.', ',')?></p>'
    <?php
		}
		?>
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
 $percent_change = coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_1h'];
 }
 elseif ( $alert_percent[1] == '24hour' ) {
 $percent_change = coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_24h'];
 }
 elseif ( $alert_percent[1] == '7day' ) {
 $percent_change = coinmarketcap_api($trade_symbol)['quotes']['USD']['percent_change_7d'];
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
  <img id='<?=$rand_id?>' src='templates/default/images/<?=$info_icon?>' border=0' style='position: absolute; top: 4px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <?=$coin_name?>
 <script>
 $('#<?=$rand_id?>').balloon({
  html: true,
  position: "right",
  contents: '<h3 style="color: #e5f1ff;">No Coinmarketcap.com data for <?=$coin_name?> (<?=$trade_symbol?>) has been configured yet.</h3>'
});

		<?php
		if ( sizeof($alert_percent) > 1 ) {
		?>
		
		setTimeout(function() {
    	play_alert("<?=strtolower($trade_symbol)?>_row", "visual", "blue"); // Assets with CMC data not set or functioning properly
		}, 1000);
		
		<?php
		}
		?>
		
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
  echo ' ($'.number_format(( get_btc_usd($btc_in_usd) * $coin_trade ), 8, '.', ',').' USD)';
  }
  else {
  echo ' ($'.number_format(get_btc_usd($btc_in_usd), 2, '.', ',').' USD)';
  }

?></span></td>

<td class='data border_lb' align='right'><?php echo number_format($coin_amount, 8, '.', ','); ?></td>

<td class='data border_b'><span><?php echo $trade_symbol; ?></span></td>

<td class='data border_lb' align='right'><?php echo $coin_trade; ?>

<?php

  if ( $selected_pairing != 'btc' ) {
  echo '<div class="btc_worth">(' . ( $btc_trade_eq > 0.00000000 ? $btc_trade_eq : '0.00000000' ) . ' Bitcoin)</div>';
  }
  
?>


</td>

<td class='data border_b'> <span>(<?=$pairing_description?>)</span></span></td>

<td class='data border_lb'><?php
echo ' <span><span class="data">' . $coin_trade_total . '</span> ' . $pairing_symbol . '</span>';
  if ( $selected_pairing != 'btc' ) {
  echo '<div class="btc_worth"><span>(' . $btc_worth . ' BTC)</span></div>';
  }

?></td>

<td class='data border_lrb'><?php

  if ( $selected_pairing == 'btc' ) {
  $coin_usd_worth = ( $coin_name == 'Bitcoin' ? $coin_trade_total_raw : ($coin_trade_total_raw * get_btc_usd($btc_in_usd)) );
  }
  else {
  $coin_usd_worth = ( ($coin_trade_total_raw * $coin_to_btc) * get_btc_usd($btc_in_usd));
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

function get_data($mode, $request, $ttl) {

global $version, $user_agent, $api_server, $api_timeout;

$cookie_jar = tempnam('/tmp','cookie');
	
// To avoid duplicate requests in current update session, AND cache data
$hash_check = ( $mode == 'array' ? md5(serialize($request)) : md5($request) );


	//if ( !$_SESSION['api_cache'][$hash_check] ) {	
	// Cache API data for 1 minute
	if ( update_cache_file('cache/api/'.$hash_check.'.dat', $ttl) == true && $ttl > 0 || $ttl == 0 ) {	
	
	$ch = curl_init( ( $mode == 'array' ? $api_server : '' ) );
	
		if ( $mode == 'array' ) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($request) );
		}
		elseif ( $mode == 'url' ) {
		curl_setopt($ch, CURLOPT_URL, $request);
		}
	
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
		$_SESSION['get_data_error'] .= ' No data returned from ' . ( $mode == 'array' ? 'API server "' . $api_server : 'request "' . $request ) . '" (with timeout configuration setting of ' . $api_timeout . ' seconds). <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
		}
		
		if ( preg_match("/coinmarketcap/i", $request) && !preg_match("/last_updated/i", $data) ) {
		$_SESSION['get_data_error'] .= '##REAL-TIME REQUEST## data error response from '.( $mode == 'array' ? $api_server : $request ).': <br /> =================================== <br />' . $data . ' <br /> =================================== <br />';
		}
	
	
	curl_close($ch);
	unlink($cookie_jar) or die("Can't unlink $cookie_jar");
	
	
		//$_SESSION['api_cache'][$hash_check] = $data; // Cache API data for this update session
		if ( $data && $ttl > 0 ) {
	
		//echo 'Caching data '; // DEBUGGING ONLY

		file_put_contents('cache/api/'.$hash_check.'.dat', $data, LOCK_EX);
		
		}
		elseif ( !$data ) {
		unlink('cache/api/'.$hash_check.'.dat'); // Delete any existing cache if empty value
		//echo 'Deleted cache file, no data. '; // DEBUGGING ONLY
		}

	
	// DEBUGGING ONLY
	//$_SESSION['get_data_error'] .= '##REQUEST## Requested ' . ( $mode == 'array' ? 'API server "' . $api_server : 'endpoint "' . $request ) . '". <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
	
	}
	elseif ( $ttl < 0 ) {
	unlink('cache/api/'.$hash_check.'.dat'); // Delete cache if $ttl flagged to less than zero
	//echo 'Deleted cache file, flagged for deletion. '; // DEBUGGING ONLY
	}
	else {
	
	//$data = $_SESSION['api_cache'][$hash_check];
	$data = file_get_contents('cache/api/'.$hash_check.'.dat');
	
		if ( !$data ) {
		unlink('cache/api/'.$hash_check.'.dat'); // Delete any existing cache if empty value
		//echo 'Deleted cache file, no data. ';
		}
		else {
		//echo 'Cached data '; // DEBUGGING ONLY
		}
	
		if ( !preg_match("/coinmarketcap/i", $_SESSION['get_data_error']) && preg_match("/coinmarketcap/i", $request) && !preg_match("/last_updated/i", $data) ) {
		$_SESSION['cmc_error'] = '##CACHED REQUEST## data error response from '.( $mode == 'array' ? $api_server : $request ).': <br /> =================================== <br />' . $data . ' <br /> =================================== <br />';
		}
	
	
	// DEBUGGING ONLY
	//$_SESSION['get_data_error'] .= ' ##DUPLICATE## request ignored for ' . ( $mode == 'array' ? 'API server "' . $api_server : 'endpoint "' . $request ) . '". <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
	
	
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
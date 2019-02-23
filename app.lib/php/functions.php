<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

////////////////////////////////////////////////////////

function text_email($string) {

$string = explode("|",$string);

$number = substr($string[0], -10); // USA 10 digit number without country code
$carrier = $string[1];


	if ( $carrier == 'alltel' ) {
	$domain = '@message.alltel.com';
	}
	elseif ( $carrier == 'att' ) {
	$domain = '@txt.att.net';
	}
	elseif ( $carrier == 'tmobile' ) {
	$domain = '@tmomail.net';
	}
	elseif ( $carrier == 'virgin' ) {
	$domain = '@vmobl.com';
	}
	elseif ( $carrier == 'sprint' ) {
	$domain = '@messaging.sprintpcs.com';
	}
	elseif ( $carrier == 'verizon' ) {
	$domain = '@vtext.com';
	}
	elseif ( $carrier == 'nextel' ) {
	$domain = '@messaging.nextel.com';
	}

return $number . $domain;

}

////////////////////////////////////////////////////////

function text_number($string) {

$string = explode("|",$string);

$number = $string[0];

return $number;

}

////////////////////////////////////////////////////////

function string_to_array($string) {

$string = explode("|",$string);

return $string;

}

////////////////////////////////////////////////////////

function asset_alert_check($asset_data, $exchange, $pairing, $alert_mode) {

global $coins_array, $btc_exchange, $btc_usd, $to_email, $to_text, $notifyme_accesscode, $textbelt_apikey, $textlocal_account, $cron_alerts_freq, $cron_alerts_percent, $cron_alerts_refresh;

// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($asset_data, "-") == false ? $asset_data : substr( $asset_data, 0, strpos($asset_data, "-") ) );

//echo $asset_data. ", ".$asset . " " .$alert_mode." check \n"; // DEBUGGING


	// Get any necessary variables for calculating asset's USD value
	if ( $asset == 'BTC' ) {
	$btc_usd = get_btc_usd($exchange)['last_trade']; // Overwrite global var with selected exchange (rather then default), when asset is Bitcoin
	}

   if ( $pairing == 'xmr' && !$_SESSION['xmr_btc'] ) {
   $_SESSION['xmr_btc'] = get_trade_data('binance', 'XMRBTC')['last_trade'];
   }
   elseif ( $pairing == 'ltc' && !$_SESSION['ltc_btc'] ) {
   $_SESSION['ltc_btc'] = get_trade_data('binance', 'LTCBTC')['last_trade'];
   }
   elseif ( $pairing == 'eth' && !$_SESSION['eth_btc'] ) {
   $_SESSION['eth_btc'] = get_trade_data('binance', 'ETHBTC')['last_trade'];
   }
   elseif ( $pairing == 'usdt' && !$_SESSION['usdt_btc'] ) {
   $_SESSION['usdt_btc'] = number_format( ( 1 / get_trade_data('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
   }
    
	// Get asset USD value
	if ( $asset == 'BTC' ){ 
	$asset_usd = $btc_usd;
	$volume_usd = get_btc_usd($exchange)['24hr_usd_volume'];
	}
	else {
		
		if ( $pairing == 'btc' ) {
		$asset_usd = number_format( $btc_usd * get_trade_data($exchange, $coins_array[$asset]['market_pairing'][$pairing][$exchange])['last_trade'] , 8);
		}
		else {
		$pairing_btc_value = $_SESSION[$pairing.'_btc'];
		$asset_usd = number_format( $btc_usd * ( $pairing_btc_value * get_trade_data($exchange, $coins_array[$asset]['market_pairing'][$pairing][$exchange])['last_trade'] ) , 8);
		}
		
		$volume_usd = get_trade_data($exchange, $coins_array[$asset]['market_pairing'][$pairing][$exchange])['24hr_usd_volume'];
	
	}

	
	
	// Check for a file modified time !!!BEFORE ANY!!! file creation / updating happens (to calculate time elapsed between updates)
	if ( file_exists('cache/alerts/'.$asset_data.'.dat') ) {
	
   $last_check_days = ( time() - filemtime('cache/alerts/'.$asset_data.'.dat') ) / 86400;
   
   	if ( floatval($last_check_days) >= 365 ) {
   	$last_check_time = number_format( ($last_check_days / 365) , 2, '.', ',') . ' years';
   	}
   	elseif ( floatval($last_check_days) >= 30 ) {
   	$last_check_time = number_format( ($last_check_days / 30) , 2, '.', ',') . ' months';
   	}
   	elseif ( floatval($last_check_days) >= 7 ) {
   	$last_check_time = number_format( ($last_check_days / 7) , 2, '.', ',') . ' weeks';
   	}
   	else {
   	$last_check_time = number_format($last_check_days, 2, '.', ',') . ' days';
   	}
   
	}
	
	
$cached_value = trim( file_get_contents('cache/alerts/'.$asset_data.'.dat') );



	////// If cached value, run alert checking ////////////
	if ( $cached_value != '' ) {
	
  
          if ( $alert_mode == 'decreased' ) {
          $cron_alerts_value = $cached_value - ( $cached_value * ($cron_alerts_percent / 100) );
          $percent_change = 100 - ( $asset_usd / ( $cached_value / 100 ) );
          $change_symbol = '-';
          
                  if ( floatval($asset_usd) >= 0.00000001 && floatval($asset_usd) <= floatval($cron_alerts_value) ) {
                  $send_alert = 1;
                  }
          
          }
          elseif ( $alert_mode == 'increased' ) {
          $cron_alerts_value = $cached_value + ( $cached_value * ($cron_alerts_percent / 100) );
          $percent_change = ( $asset_usd / ( $cached_value / 100 ) ) - 100;
          $change_symbol = '+';
          
                  if ( floatval($asset_usd) >= 0.00000001 && floatval($asset_usd) >= floatval($cron_alerts_value) ) {
                  $send_alert = 1;
                  }
          
          }
  
  
  // Message formatting
  $cached_value_text = ( $asset == 'BTC' ? number_format($cached_value, 2, '.', ',') : $cached_value );
  $asset_usd_text = ( $asset == 'BTC' ? number_format($asset_usd, 2, '.', ',') : $asset_usd );
  
  $email_message = 'The ' . $asset . ' trade value in the '.strtoupper($pairing).' market at the ' . ucfirst($exchange) . ' exchange has '.$alert_mode.' '.$change_symbol.number_format($percent_change, 2, '.', ',').'% from it\'s previous value of $'.$cached_value_text.', to a current value of $' . $asset_usd_text . ' over the past '.$last_check_time.'. 24 hour trade volume is $' . $volume_usd . '.';
  
  $text_message = $asset . '/'.strtoupper($pairing).' @' . ucfirst($exchange) . ' '.$alert_mode.' '.$change_symbol.number_format($percent_change, 2, '.', ',').'% from $'.$cached_value_text.' to $' . $asset_usd_text . ' in '.$last_check_time.'. 24hr Vol: $' . $volume_usd;
  
  
  // Alert parameter configs for comm methods
  $notifyme_params = array(
                          'notification' => $email_message,
                          'accessCode' => $notifyme_accesscode
                          );
  
  $textbelt_params = array(
                          'phone' => text_number($to_text),
                          'message' => $text_message,
                          'key' => $textbelt_apikey
                          );
  
  $textlocal_params = array(
                           'username' => string_to_array($textlocal_account)[0],
                           'hash' => string_to_array($textlocal_account)[1],
                           'numbers' => text_number($to_text),
                           'message' => $text_message
                           );
  
          
          // Sending the alerts
          if ( update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $cron_alerts_freq * 60 ) ) == true && $send_alert == 1 ) {
          
          file_put_contents('cache/alerts/'.$asset_data.'.dat', $asset_usd, LOCK_EX); // Cache the new lower / higher value
          
                  if (  validate_email($to_email) == 'valid' ) {
                  safe_mail($to_email, $asset . ' Asset Value '.ucfirst($alert_mode).' Alert', $email_message);
                  }
  
                  if ( validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) != '' && trim($textlocal_account) != '' ) { // Only use text-to-email if other text services aren't configured
                  safe_mail( text_email($to_text) , $asset . ' Value Alert', $text_message);
                  }
  
                  if ( trim($notifyme_accesscode) != '' ) {
                  data_request('array', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
                  }
  
                  if ( trim($textbelt_apikey) != '' && trim($textlocal_account) == '' ) { // Only run if textlocal API isn't being used to avoid double texts
                  data_request('array', $textbelt_params, 0, 'https://textbelt.com/text', 2);
                  }
  
                  if ( trim($textlocal_account) != '' && trim($textbelt_apikey) == '' ) { // Only run if textbelt API isn't being used to avoid double texts
                  data_request('array', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
                  }
          
          
          }
  
  
	}
	////// END alert checking //////////////



	// Cache a price value if not already done, OR if config setting set to refresh every X days
	if ( floatval($asset_usd) >= 0.00000001 && update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $cron_alerts_refresh * 1440 ) ) == true ) {
	file_put_contents('cache/alerts/'.$asset_data.'.dat', $asset_usd, LOCK_EX); 
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
 
global $chainstats_cache;

  $json_string = 'http://api.etherscan.io/api?module=proxy&action=eth_blockNumber';
  $jsondata = @data_request('url', $json_string, $chainstats_cache);
    
  $data = json_decode($jsondata, TRUE);
  
  $block_number = $data['result'];
    
    	if ( !$block_number ) {
    	return;
    	}
    	else {
    		
    		// Non-dynamic cache file name, because filename would change every recache and create cache bloat
    		if ( update_cache_file('cache/api/eth-stats.dat', $chainstats_cache ) == true ) {
			
  			$json_string = 'http://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true';
  			$jsondata = @data_request('url', $json_string, 0); // ZERO TO NOT CACHE DATA (WOULD CREATE CACHE BLOAT)
    		
    		file_put_contents('cache/api/eth-stats.dat', $jsondata, LOCK_EX);
    		
    		$data = json_decode($jsondata, TRUE);
    		
    		return $data['result'][$block_info];
    		
    		}
    		else {
    			
    		$cached_data = trim( file_get_contents('cache/api/eth-stats.dat') );
    		
    		$data = json_decode($cached_data, TRUE);
    		
    		return $data['result'][$block_info];

    		}
  
    	}
  
}

//////////////////////////////////////////////////////////

function decred_api($type, $request) {
 
global $chainstats_cache;
 		
 	if ( $type == 'block' ) {
 	$json_string = 'https://explorer.dcrdata.org/api/block/best/verbose';
 	}
 	elseif ( $type == 'subsidy' ) {
 	$json_string = 'https://explorer.dcrdata.org/api/block/best/subsidy';
 	}
 	
 	$jsondata = @data_request('url', $json_string, $chainstats_cache);
  	
  	$data = json_decode($jsondata, TRUE);
    
		if ( !$data ) {
		return;
		}
		else {
		
		return $data[$request];
		  
		}
  
}

//////////////////////////////////////////////////////////

function monero_api($request) {
 
global $chainstats_cache;
 		
 	$json_string = 'https://moneroblocks.info/api/get_stats';
 	$jsondata = @data_request('url', $json_string, $chainstats_cache);
  	
  	$data = json_decode($jsondata, TRUE);
    
		if ( !$data ) {
		return;
		}
		else {
		
		return $data[$request];
		  
		}
  
}

//////////////////////////////////////////////////////////

function monero_reward() {
 		
 	return monero_api('last_reward') / 1000000000000;
  
}

//////////////////////////////////////////////////////////

function vertcoin_api($request) {
 
global $chainstats_cache;
		
		if ( $request == 'height' ) {
		
		return trim(@data_request('url', 'http://explorer.vertcoin.info/api/getblockcount', $chainstats_cache));
		  
		}
		elseif ( $request == 'difficulty' ) {
		
		return trim(@data_request('url', 'http://explorer.vertcoin.info/api/getdifficulty', $chainstats_cache));
		  
		}
  
}

//////////////////////////////////////////////////////////

function ravencoin_api($request) {
 
global $chainstats_cache;
 		
    $json_string = 'https://ravencoin.network/api/status?q=getInfo';
    
    $jsondata = @data_request('url', $json_string, $chainstats_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    
		if ( $request == 'height' ) {
		
		return $data['info']['blocks'];
		  
		}
		elseif ( $request == 'difficulty' ) {
		
		return $data['info']['difficulty'];
		  
		}
  
  
}

//////////////////////////////////////////////////////////

function mining_calc_form($calculation_form_data, $network_measure) {

global $_POST, $mining_rewards;

?>

				<form name='<?=$calculation_form_data[1]?>' action='index.php#calculators' method='post'>
				
				<p><b><?=ucfirst($network_measure)?>:</b> <input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calculation_form_data[3]) )?>' name='network_measure' /> (uses <a href='<?=$calculation_form_data[4]?>' target='_blank'><?=$calculation_form_data[5]?></a>)</p>
				
				<p><b>Your Hashrate:</b> <input type='text' value='<?=( $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['your_hashrate'] : '' )?>' name='your_hashrate' />
				
				<select name='hash_level'>
				<option value='1000000000000000000' <?=( $_POST['hash_level'] == '1000000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ehs (one quintillion hashes per second) </option>
				<option value='1000000000000000' <?=( $_POST['hash_level'] == '1000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Phs (one quadrillion hashes per second) </option>
				<option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ths (one trillion hashes per second) </option>
				<option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ghs (one billion hashes per second) </option>
				<option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Mhs (one million hashes per second) </option>
				<option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Khs (one thousand hashes per second) </option>
				<option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Hs (one hash per second) </option>
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

function get_btc_usd($btc_exchange) {

global $last_trade_ttl;
  
    if ( strtolower($btc_exchange) == 'coinbase' ) {
    
    $json_string = 'https://api.coinbase.com/v2/prices/spot?currency=USD';
    
    $jsondata = @data_request('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['data']['amount'], 2, '.', ''),
    					'24hr_usd_volume' => NULL // Need volume API docs
    					);

  
    }
  
  
    elseif ( strtolower($btc_exchange) == 'bitfinex' ) {
  
    $data = get_trade_data('bitfinex', 'tBTCUSD')['last_trade'];
    //$vol = get_trade_data('bitfinex', 'tBTCUSD')['24hr_usd_volume'];
    
    
    return  array(
    					'last_trade' => number_format( $data, 2, '.', ''),
    					'24hr_usd_volume' => NULL  // Shitty API crapped out, try later
    					);

  
    }
  
  
    elseif ( strtolower($btc_exchange) == 'hitbtc' ) {
  
    $json_string = 'https://api.hitbtc.com/api/1/public/BTCUSD/ticker';
    
    $jsondata = @data_request('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    
    return  array(
    					'last_trade' => number_format( $data['last'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['volume'], number_format( $data['last'], 2, '.', ''))
    					);

  
    }
  

    elseif ( strtolower($btc_exchange) == 'gemini' ) {
    
    $json_string = 'https://api.gemini.com/v1/pubticker/btcusd';
    
      $jsondata = @data_request('url', $json_string, $last_trade_ttl);
      
      $data = json_decode($jsondata, TRUE);
      
      
    return  array(
    					'last_trade' => number_format( $data['last'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['volume']['BTC'], number_format( $data['last'], 2, '.', ''))
    					);

      
    }


    elseif ( strtolower($btc_exchange) == 'okcoin' ) {
  
    $json_string = 'https://www.okcoin.com/api/v1/ticker.do?symbol=btc_usd';
    
    $jsondata = @data_request('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    
    return  array(
    					'last_trade' => number_format( $data['ticker']['last'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['ticker']['vol'], number_format( $data['ticker']['last'], 2, '.', ''))
    					);

    
  
    }
  
  
    elseif ( strtolower($btc_exchange) == 'bitstamp' ) {
 	
    $json_string = 'https://www.bitstamp.net/api/ticker/';
    
    $jsondata = @data_request('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    
    return  array(
    					'last_trade' => number_format( $data['last'], 2, '.', ''),
    					'24hr_usd_volume' => volume_usd('bitcoin', $data['volume'], number_format( $data['last'], 2, '.', ''))
    					);
    				
    }
  
 
   elseif ( strtolower($btc_exchange) == 'gatecoin' ) {
 
      
      $json_string = 'https://api.gatecoin.com/Public/LiveTickers';
      
      $jsondata = @data_request('url', $json_string, $last_trade_ttl);
      
      $data = json_decode($jsondata, TRUE);
   
   //var_dump($data);
       if (is_array($data) || is_object($data)) {
         
             foreach ( $data['tickers'] as $key => $value ) {
               
               if ( $data['tickers'][$key]["currencyPair"] == 'BTCUSD' ) {
                
                
    				return  array(
    									'last_trade' => $data['tickers'][$key]["last"],
    									'24hr_usd_volume' => volume_usd('bitcoin', $data['tickers'][$key]["volume"], $data['tickers'][$key]["last"])  // Very stubborn, try later
    									);

                
                
               }
             
     
             }
             
       }
   
   
   }

   elseif ( strtolower($btc_exchange) == 'livecoin' ) {
 
 
      $json_string = 'https://api.livecoin.net/exchange/ticker';
      
      $jsondata = @data_request('url', $json_string, $last_trade_ttl);
      
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
   
   $jsondata = @data_request('url', $json_string, $last_trade_ttl);
   
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

function get_trade_data($chosen_market, $market_pairing) {

global $btc_exchange, $coins_array, $last_trade_ttl;
 

  if ( strtolower($chosen_market) == 'gemini' ) {
  
  $json_string = 'https://api.gemini.com/v1/pubticker/' . $market_pairing;
  
    $jsondata = @data_request('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return array(
    					'last_trade' => number_format( $data['last'], 8, '.', ''),
    					'24hr_usd_volume' => volume_usd($market_pairing, $data['volume'][strtoupper(detect_pairing($market_pairing))], '', detect_pairing($market_pairing))
    				);
  
  }


  elseif ( strtolower($chosen_market) == 'bitstamp' ) {
  	
  
  $json_string = 'https://www.bitstamp.net/api/v2/ticker/' . $market_pairing;
  
    $jsondata = @data_request('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['last'], 8, '.', ''),
    					'24hr_usd_volume' => volume_usd($market_pairing, $data["volume"], $data["last"])
    				);
    
  
  }



  elseif ( strtolower($chosen_market) == 'okex' ) {
  	
  	// Available markets listed here: https://www.okex.com/v2/markets/products
  
  $json_string = 'https://www.okex.com/api/v1/ticker.do?symbol=' . $market_pairing;
  
    $jsondata = @data_request('url', $json_string, $last_trade_ttl);
    
    $data = json_decode($jsondata, TRUE);
    
    return  array(
    					'last_trade' => number_format( $data['ticker']['last'], 8, '.', ''),
    					'24hr_usd_volume' => volume_usd($market_pairing, $data['ticker']["vol"], $data['ticker']["last"])
    				);
    
  
  }



  elseif ( strtolower($chosen_market) == 'binance' ) {
     
     $json_string = 'https://www.binance.com/api/v1/ticker/24hr';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  //print_r($data);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
         if ( $key == $market_pairing ) {
         	
         //print_r($data[$key]);
         
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  	  $data = $data['data'];
  //print_r($data);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
         if ( $data[$key]["market_id"] == $market_pairing ) {
         	
         //print_r($data[$key]);
         
         return  array(
    					'last_trade' => $data[$key]["close"],
    					'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["volume"], $data[$key]["close"])
    				);
          
         }
       
     
       }
      
      }
  
  
  }



  elseif ( strtolower($chosen_market) == 'coinbase' ) {
  
     $json_string = 'https://api.coinbase.com/v2/exchange-rates?currency=' . $market_pairing;
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
     return  array(
    					'last_trade' => $data['data']['rates']['BTC'],
    					'24hr_usd_volume' => NULL
    				);
   
  }
  

  elseif ( strtolower($chosen_market) == 'cryptofresh' ) {
  
  $json_string = 'https://cryptofresh.com/api/asset/markets?asset=' . $market_pairing;
  
    $jsondata = @data_request('url', $json_string, $last_trade_ttl);
    
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  $data = $data['result'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  $data = $data['result'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
         if ( $data[$key]['market'] == $market_pairing ) {
          
         return  array(
    					'last_trade' => $data[$key]["last"],
    					'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["volume"], $data[$key]["last"])
    				);
          
          
         }
       
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'liqui' ) {
  
  $json_string = 'https://api.liqui.io/api/3/ticker/' . $market_pairing;
  
  $jsondata = @data_request('url', $json_string, $last_trade_ttl);
  
  $data = json_decode($jsondata, TRUE);
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
         if ( $key == $market_pairing ) {
          
         return  array(
    					'last_trade' => $data[$key]["last"],
    					'24hr_usd_volume' => NULL
    				);
          
          
         }
       
     
       }
      
      }
  
  }

  elseif ( strtolower($chosen_market) == 'bitforex' ) {
  
  $json_string = 'https://api.bitforex.com/api/v1/market/ticker?symbol=' . $market_pairing;
  
  $jsondata = @data_request('url', $json_string, $last_trade_ttl);
  
  $data = json_decode($jsondata, TRUE);
  
  return  array(
    					'last_trade' => $data["data"]["last"],
    					'24hr_usd_volume' => volume_usd($market_pairing, $data["data"]["vol"], $data["data"]["last"])
    				);
  
  }

  elseif ( strtolower($chosen_market) == 'poloniex' ) {

     $json_string = 'https://poloniex.com/public?command=returnTicker';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($value);
         
         if ( $data[$key][$market_pairing] != '' ) {
          
         return  array(
    					'last_trade' => $data[$key][$market_pairing]["price"],
    					'24hr_usd_volume' => volume_usd($market_pairing, $data[$key][$market_pairing]["volume"], '', 'btc')
    				);
          
          
         }
       
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'hotbit' ) {

     $json_string = 'https://api.hotbit.io/api/v1/allticker';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  $data = $data['ticker'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
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

     $json_string = 'https://api.kucoin.com/v1/open/tick';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
   
  
  $data = $data['data'];
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
         if ( $data[$key]['symbol'] == $market_pairing ) {
          
         return  array(
    					'last_trade' => $data[$key]["lastDealPrice"],
    					'24hr_usd_volume' => NULL  // V1 offline, no docs yet for v2
    				);
          
          
         }
       
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'livecoin' ) {

     $json_string = 'https://api.livecoin.net/exchange/ticker';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  $data = $data['Data'];
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
           foreach ($data as $key => $value) {
            
            //print_r($key);
            
            if ( $data[$key]['Label'] == $market_pairing ) {
             
            return  array(
    					'last_trade' => $data[$key]["LastPrice"],
    					'24hr_usd_volume' => NULL  // Offline from hack
    				);
             
             
            }
          
        
          }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'hitbtc' ) {

     $json_string = 'https://api.hitbtc.com/api/1/public/ticker';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //print_r($key);
         
         if ( $key == $market_pairing ) {
          
         return  array(
    					'last_trade' => $data[$key]["last"],
    					'24hr_usd_volume' => volume_usd($market_pairing, $data[$key]["volume"], $data[$key]["last"])
    				);
          
          
         }
       
     
       }
      
      }
  
  
  }

  elseif ( strtolower($chosen_market) == 'bter' ) {

     $json_string = 'http://data.bter.com/api/1/marketlist';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //var_dump($data);
         
         if ( $data[$key]['pair'] == $market_pairing ) {
          
         return  array(
    					'last_trade' => $data[$key]['rate'],
    					'24hr_usd_volume' => NULL  // Offline
    				);
          
          
         }
       
     
       }
      
      }
  
  
  }
  
  
  elseif ( strtolower($chosen_market) == 'graviex' ) {

     $json_string = 'https://graviex.net//api/v2/tickers.json';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
     
  //print_r($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ($data as $key => $value) {
         
         //var_dump($data);
         
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
  
  $jsondata = @data_request('url', $json_string, $last_trade_ttl);
  
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



  elseif ( strtolower($chosen_market) == 'gatecoin' ) {

     $json_string = 'https://api.gatecoin.com/Public/LiveTickers';
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data['tickers'] as $key => $value ) {
         
         if ( $data['tickers'][$key]["currencyPair"] == $market_pairing ) {
          
         return  array(
    					'last_trade' => $data['tickers'][$key]["last"],
    					'24hr_usd_volume' => volume_usd($market_pairing, $data['tickers'][$key]["volume"], $data['tickers'][$key]["last"])
    				);
          
          
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data as $key => $value ) {
         
         if ( $data[$key]["code"] == 'CRIX.UPBIT.' . $market_pairing ) {
          
         return  array(
    					'last_trade' => $data[$key]["tradePrice"],
    					'24hr_usd_volume' => NULL
    				);
          
          
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
     
     $jsondata = @data_request('url', $json_string, $last_trade_ttl);
     
     $data = json_decode($jsondata, TRUE);
  
  //var_dump($data);
  
      if (is_array($data) || is_object($data)) {
  
       foreach ( $data as $object ) {
         
         if ( $object[0] == $market_pairing ) {
                 
          //var_dump($object);
          
         return  array(
    					'last_trade' => $object[( sizeof($object) - 4 )],
    					'24hr_usd_volume' => NULL
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
    					'last_trade' => ( 1 / ( get_trade_data('binance', 'XMRBTC')['last_trade'] / $usdtobtc ) ),
    					'24hr_usd_volume' => NULL
    				);
     }
	  elseif ( $market_pairing == 'usdtoeth' ) {
     return  array(
    					'last_trade' => ( 1 / ( get_trade_data('binance', 'ETHBTC')['last_trade'] / $usdtobtc ) ),
    					'24hr_usd_volume' => NULL
    				);
     }
	  elseif ( $market_pairing == 'usdtoltc' ) {
     return  array(
    					'last_trade' => ( 1 / ( get_trade_data('binance', 'LTCBTC')['last_trade'] / $usdtobtc ) ),
    					'24hr_usd_volume' => NULL
    				);
     }
  
  
  }



  
}

//////////////////////////////////////////////////////////

function get_sub_token_price($chosen_market, $market_pairing) {

global $eth_subtokens_ico_values;

 if ( strtolower($chosen_market) == 'eth_subtokens_ico' ) {

  return $eth_subtokens_ico_values[$market_pairing];
  }
 

}

///////////////////////////////////////////////////////////

function strip_price_formatting($price) {

$price = preg_replace("/ /", "", $price); // Space
$price = preg_replace("/,/", "", $price); // Comma
$price = preg_replace("/  /", "", $price); // Tab

return $price;

}

//////////////////////////////////////////////////////////

function marketcap_data($symbol) {
	
global $marketcap_site, $alert_percent;

$data = array();


	if ( $marketcap_site == 'coinmarketcap' ) { 
		
	$data['rank'] = coinmarketcap_api($symbol)['rank'];
	$data['price'] = coinmarketcap_api($symbol)['quotes']['USD']['price'];
	$data['market_cap'] = coinmarketcap_api($symbol)['quotes']['USD']['market_cap'];
	$data['volume_24h'] = coinmarketcap_api($symbol)['quotes']['USD']['volume_24h'];
	$data['percent_change_1h'] = coinmarketcap_api($symbol)['quotes']['USD']['percent_change_1h'];
	$data['percent_change_24h'] = coinmarketcap_api($symbol)['quotes']['USD']['percent_change_24h'];
	$data['percent_change_7d'] = coinmarketcap_api($symbol)['quotes']['USD']['percent_change_7d'];
	$data['circulating_supply'] = coinmarketcap_api($symbol)['circulating_supply'];
	$data['total_supply'] = coinmarketcap_api($symbol)['total_supply'];
	$data['max_supply'] = coinmarketcap_api($symbol)['max_supply'];
	$data['last_updated'] = coinmarketcap_api($symbol)['last_updated'];
	
	}
	elseif ( $marketcap_site == 'coingecko' ) {
		
	$data['rank'] = coingecko_api($symbol)['market_data']['market_cap_rank'];
	$data['price'] = coingecko_api($symbol)['market_data']['current_price']['usd'];
	$data['market_cap'] = coingecko_api($symbol)['market_data']['market_cap']['usd'];
	$data['volume_24h'] = coingecko_api($symbol)['market_data']['total_volume']['usd'];
	
	$data['percent_change_1h'] = number_format( coingecko_api($symbol)['market_data']['price_change_percentage_1h_in_currency']['usd'] , 2, ".", ",");
	$data['percent_change_24h'] = number_format( coingecko_api($symbol)['market_data']['price_change_percentage_24h'] , 2, ".", ",");
	$data['percent_change_7d'] = number_format( coingecko_api($symbol)['market_data']['price_change_percentage_7d'] , 2, ".", ",");
	
	$data['circulating_supply'] = coingecko_api($symbol)['market_data']['circulating_supply'];
	$data['total_supply'] = coingecko_api($symbol)['market_data']['total_supply'];
	$data['max_supply'] = NULL;
	
	$data['last_updated'] = strtotime( coingecko_api($symbol)['last_updated'] );
	
	}


return $data;

}

//////////////////////////////////////////////////////////

function coingecko_api($symbol) {
	
global $marketcap_ranks_max, $marketcap_ttl;

$array_merging = array();


	if ( !$_SESSION['cgk_data'] ) {

		if ( !$_SESSION['cgk_json_array'] ) {
			
			
		$page = 1;
		$rankings_left = $marketcap_ranks_max;
		
			while ( $rankings_left > 0 ) {
					
			$limit = 100;
			
			$_SESSION['cgk_json_array'][] = 'https://api.coingecko.com/api/v3/coins?per_page='.$limit.'&page='.$page;
			
			$page = $page + 1;
			$rankings_left = $rankings_left - $limit;
			
			}
	
		
		}
		
		
		foreach ( $_SESSION['cgk_json_array'] as $cgk_request ) {
			
     	$json_string = $cgk_request;
     	     
	  	$jsondata = @data_request('url', $json_string, $marketcap_ttl);
	   
   	$data = json_decode($jsondata, TRUE);
    
    

    	$array_merging[] = $data;
    	
	
		}
		

		$cgk_data = array(); // Empty array MUST be pre-defined for array_merge_recursive()
		foreach ( $array_merging as $array ) {
			
 	  	$cgk_data = array_merge_recursive($cgk_data, $array);
	   
 	   }
 	   
 	   $_SESSION['cgk_data'] = $cgk_data;
		

	}
	else {
	$cgk_data = $_SESSION['cgk_data'];
	}
		

     if ( is_array($cgk_data) || is_object($cgk_data) ) {
  		
  	   	foreach ($cgk_data as $key => $value) {
     	  	
  	     	
        		if ( $cgk_data[$key]['symbol'] == strtolower($symbol) ) {
  	      		

        		return $cgk_data[$key];
        
        
     	  		}
    	 
    
  	   	}
      	
     
     	}
		  
  
}

//////////////////////////////////////////////////////////

function coinmarketcap_api($symbol) {
	
global $marketcap_ranks_max, $marketcap_ttl;

$array_merging = array();

	if ( !$_SESSION['cmc_data'] ) {

		if ( !$_SESSION['cmc_json_array'] ) {
			
			
		//Coinmarketcap's new v2 API caps each API request at 100 assets, so we need to break requests up that are over 100 assets...
		$offset = 1;
		$rankings_left = $marketcap_ranks_max;
		
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
     	     
	  	$jsondata = @data_request('url', $json_string, $marketcap_ttl);
	   
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

function coin_data($coin_name, $trade_symbol, $coin_amount, $market_pairing_array, $selected_pairing, $selected_market, $sort_order) {

global $_POST, $coins_array, $btc_exchange, $marketcap_site, $marketcap_ttl, $alert_percent, $marketcap_ranks_max, $api_timeout;


$original_market = $selected_market;

$all_markets = $market_pairing_array;  // All markets for this pairing

$all_pairings = $coins_array[$trade_symbol]['market_pairing'];

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
$btc_exchange = $_SESSION['btc_in_usd'];
}


$market_pairing = $all_markets[$selected_market];
  
  
  if ( $coin_amount > 0.00000000 ) {
    
    if ( !$_SESSION['td_color'] || $_SESSION['td_color'] == '#e8e8e8' ) {
    $_SESSION['td_color'] = 'white';
    }
    else {
    $_SESSION['td_color'] = '#e8e8e8';
    }

    if ( $selected_pairing == 'btc' ) {
    $coin_trade_raw = ( $coin_name == 'Bitcoin' ? get_btc_usd($btc_exchange)['last_trade'] : get_trade_data($selected_market, $market_pairing)['last_trade'] );
    $coin_trade = number_format( $coin_trade_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $btc_worth = number_format( $coin_trade_total_raw, 8 );  
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', ( $coin_name == 'Bitcoin' ? $coin_amount : $btc_worth ) );
    $pairing_symbol = ( $coin_name == 'Bitcoin' ? 'USD' : 'BTC' );
    }
    else if ( $selected_pairing == 'xmr' ) {
    
    	if ( !$_SESSION['xmr_btc'] ) {
    	$_SESSION['xmr_btc'] = get_trade_data('binance', 'XMRBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['xmr_btc'];
    
    $coin_trade_raw = get_trade_data($selected_market, $market_pairing)['last_trade'];
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
    $pairing_symbol = 'XMR';
    
    }
    else if ( $selected_pairing == 'ltc' ) {
    
    	if ( !$_SESSION['ltc_btc'] ) {
    	$_SESSION['ltc_btc'] = get_trade_data('binance', 'LTCBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['ltc_btc'];
    
    $coin_trade_raw = get_trade_data($selected_market, $market_pairing)['last_trade'];
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
    $pairing_symbol = 'LTC';
    
    }
    else if ( $selected_pairing == 'eth' ) {
    
    	if ( !$_SESSION['eth_btc'] ) {
    	$_SESSION['eth_btc'] = get_trade_data('binance', 'ETHBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['eth_btc'];
     
     if ( $selected_market == 'eth_subtokens_ico' ) {
     
     $coin_trade_raw = get_sub_token_price($selected_market, $market_pairing);
     $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
     $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
     $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
     $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
     $pairing_symbol = 'ETH';
     
     }
     else {
      
     $coin_trade_raw = get_trade_data($selected_market, $market_pairing)['last_trade'];
     $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
     $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
     $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
     $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
     $pairing_symbol = 'ETH';
     
     }

    }
    else if ( $selected_pairing == 'usdt' ) {
    
    	if ( !$_SESSION['usdt_btc'] ) {
    	$_SESSION['usdt_btc'] = number_format( ( 1 / get_trade_data('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
    	}
    
    $pairing_btc_value = $_SESSION['usdt_btc'];
    
    $coin_trade_raw = get_trade_data($selected_market, $market_pairing)['last_trade'];
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
    $pairing_symbol = 'USDT';
    
    }
  
  $trade_volume = ( $coin_name == 'Bitcoin' ? get_btc_usd($btc_exchange)['24hr_usd_volume'] : get_trade_data($selected_market, $market_pairing)['24hr_usd_volume'] );
  
  ?>
<tr id='<?=strtolower($trade_symbol)?>_row'>

<td class='data border_lb'><span><?php echo $sort_order; ?></span></td>

<td class='data border_lb' align='right' style='position: relative; padding-right: 32px; <?=( $coins_array[$trade_symbol]['ico'] == 'yes' ? 'padding-left: 32px;' : '' )?>'>
 
 <?php
 $mkcap_render_data = trim($coins_array[$trade_symbol]['marketcap-website-slug']);
 $info_icon = ( !marketcap_data($trade_symbol)['rank'] ? 'info-none.png' : 'info.png' );
 
	if ( $mkcap_render_data != '' ) {
 	
 
 		if ( $marketcap_site == 'coinmarketcap' ) {
 		$asset_pagebase = 'coinmarketcap.com/currencies/';
 		}
 		elseif ( $marketcap_site == 'coingecko' ) {
 		$asset_pagebase = 'coingecko.com/en/coins/';
 		}
 	
 	
 		?>
 <?=( $coins_array[$trade_symbol]['ico'] == 'yes' ? "<a title='SEC Website On ICO Guidance And Safety' href='https://www.sec.gov/ICO' target='_blank'><img src='templates/default/images/alert.png' border=0' style='position: absolute; top: 3px; left: 0px; margin: 0px; height: 30px; width: 30px;' /></a> " : "" )?><img id='<?=$mkcap_render_data?>' src='templates/default/images/<?=$info_icon?>' border=0' style='position: absolute; top: 3px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <a title='' href='https://<?=$asset_pagebase?><?=$mkcap_render_data?>/' target='_blank' style='color: blue;'><?php echo $coin_name; ?></a>
 <script>

		<?php
		if ( !marketcap_data($trade_symbol)['rank'] ) {
		?>

	var cmc_content = '<h3 style="color: #e5f1ff;"><?=ucfirst($marketcap_site)?> API may be offline / under heavy load, <br />marketcap range not set high enough (current range is top <?=$marketcap_ranks_max?> marketcaps), <br />or API timeout set too low (current timeout is <?=$api_timeout?> seconds). <br />Configuration adjustments can be made in config.php.</h3>';
	
			<?php
			if ( sizeof($alert_percent) > 1 ) {
			?>
			
			setTimeout(function() {
    		play_alert("<?=strtolower($trade_symbol)?>_row", "visual", "blue"); // Assets with marketcap data not set or functioning properly
			}, 1000);
			
			<?php
			}
		
        }
        else {
        ?> 
    
        var cmc_content = '<h3 class="orange" style="position: relative; top: -3px;"><?=ucfirst($marketcap_site)?>.com Summary For <?=$coin_name?> (<?=$trade_symbol?>):</h3>'
        +'<p><span class="orange">Average Global Market Price:</span> $<?=number_format(marketcap_data($trade_symbol)['price'],8,".",",")?></p>'
        +'<p><span class="orange">Marketcap Ranking:</span> #<?=marketcap_data($trade_symbol)['rank']?></p>'
        +'<p><span class="orange">Marketcap (USD):</span> $<?=number_format(marketcap_data($trade_symbol)['market_cap'],0,".",",")?></p>'
        +'<p><span class="orange">24 Hour Global Volume (USD):</span> $<?=number_format(marketcap_data($trade_symbol)['volume_24h'],0,".",",")?></p>'
        +'<p><span class="orange">1 Hour Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_1h'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_1h'].'%</span>' : '<span class="green">'.marketcap_data($trade_symbol)['percent_change_1h'].'%</span>' )?></p>'
        +'<p><span class="orange">24 Hour Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_24h'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_24h'].'%</span>' : '<span class="green">'.marketcap_data($trade_symbol)['percent_change_24h'].'%</span>' )?></p>'
        +'<p><span class="orange">7 Day Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_7d'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_7d'].'%</span>' : '<span class="green">'.marketcap_data($trade_symbol)['percent_change_7d'].'%</span>' )?></p>'
        +'<p><span class="orange">Available Supply:</span> <?=number_format(marketcap_data($trade_symbol)['circulating_supply'], 0, '.', ',')?></p>'
        <?php
            if ( marketcap_data($trade_symbol)['total_supply'] > 0 ) {
            ?>
        +'<p><span class="orange">Total Supply:</span> <?=number_format(marketcap_data($trade_symbol)['total_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( marketcap_data($trade_symbol)['max_supply'] > 0 ) {
            ?>
        +'<p><span class="orange">Maximum Supply:</span> <?=number_format(marketcap_data($trade_symbol)['max_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( marketcap_data($trade_symbol)['last_updated'] != '' ) {
            ?>
        +'<p><span class="orange">Timestamp (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", marketcap_data($trade_symbol)['last_updated'])?></p>'
    
        <?php
            }
            ?>
        +'<p><span class="orange">Cache time:</span> <?=$marketcap_ttl?> minute(s)</p>';
    
        <?php
        
        }
        ?>
    
        $('#<?=$mkcap_render_data?>').balloon({
        html: true,
        position: "right",
        contents: cmc_content,
        css: {
                fontSize: ".9rem",
                minWidth: ".9rem",
                padding: ".3rem .7rem",
                border: "1px solid rgba(212, 212, 212, .4)",
                borderRadius: "6px",
                boxShadow: "3px 3px 6px #555",
                color: "#eee",
                backgroundColor: "#111",
                opacity: "0.95",
                zIndex: "32767",
                textAlign: "left"
                }
        });
    
    
    <?php
    
    
        if ( sizeof($alert_percent) > 1 ) {
    
        $percent_change_alert = $alert_percent[1];
    
        $percent_alert_type = $alert_percent[3];
    
            if ( $alert_percent[2] == '1hour' ) {
            $percent_change = marketcap_data($trade_symbol)['percent_change_1h'];
            }
            elseif ( $alert_percent[2] == '24hour' ) {
            $percent_change = marketcap_data($trade_symbol)['percent_change_24h'];
            }
            elseif ( $alert_percent[2] == '7day' ) {
            $percent_change = marketcap_data($trade_symbol)['percent_change_7d'];
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
  contents: '<h3 style="color: #e5f1ff;">No <?=ucfirst($marketcap_site)?>.com data for <?=$coin_name?> (<?=$trade_symbol?>) has been configured yet.</h3>'
});

		<?php
		if ( sizeof($alert_percent) > 1 ) {
		?>
		
		setTimeout(function() {
    	play_alert("<?=strtolower($trade_symbol)?>_row", "visual", "blue"); // Assets with marketcap data not set or functioning properly
		}, 1000);
		
		<?php
		}
		?>
		
 </script>
	<?php
	}
 
 $mkcap_render_data = NULL;
 $rand_id = NULL;
 ?>
 
</td>

<td class='data border_b'><span><?php

  if ( $btc_trade_eq ) {
  echo ' $'.number_format(( get_btc_usd($btc_exchange)['last_trade'] * $btc_trade_eq ), 8, '.', ',');
  }
  elseif ( $coin_name != 'Bitcoin' ) {
  echo ' $'.number_format(( get_btc_usd($btc_exchange)['last_trade'] * $coin_trade ), 8, '.', ',');
  }
  else {
  echo ' $'.number_format(get_btc_usd($btc_exchange)['last_trade'], 2, '.', ',');
  }

?></span></td>

<td class='data border_lb' align='right'><?php echo number_format($coin_amount, 8, '.', ','); ?></td>

<td class='data border_b'><span><?php echo $trade_symbol; ?></span></td>

<td class='data border_lb'>
 
    <select name='change_<?=strtolower($trade_symbol)?>_market' onchange='
    $("#<?=strtolower($trade_symbol)?>_market").val(this.value); document.coin_amounts.submit();
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

<td class='data border_b'><span>$<?php echo ( $trade_volume > 0 ? $trade_volume : 0 ); ?></span></td>

<td class='data border_b' align='right'><span><?php echo $coin_trade; ?></span>

<?php

  if ( $selected_pairing != 'btc' ) {
  echo '<div class="btc_worth">(' . ( $btc_trade_eq > 0.00000000 ? $btc_trade_eq : '0.00000000' ) . ' Bitcoin)</div>';
  }
  
?>


</td>

<td class='data border_b'> <span>
 
    <select name='change_<?=strtolower($trade_symbol)?>_pairing' onchange='
    $("#<?=strtolower($trade_symbol)?>_pairing").val(this.value); 
    $("#<?=strtolower($trade_symbol)?>_market").val(1); // Just reset to first listed market for this pairing
    document.coin_amounts.submit();
    '>
        <?php
        
		  if ( $coin_name == 'Bitcoin' ) {
		  ?>
		  <option value='btc'> USD </option>
		  <?php
		  }
        
        else {
        	
        $loop = 0;

	        foreach ( $all_pairings as $pairing_key => $pairing_name ) {
	         $loop = $loop + 1;
	        ?>
	        <option value='<?=$pairing_key?>' <?=( strtolower($pairing_symbol) == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper($pairing_key)?> </option>
	        <?php
	        }
        
        $loop = NULL;
        
        }
        ?>
    </select>

</span></td>

<td class='data border_lb'><?php
echo ' <span><span class="data">' . $coin_trade_total . '</span> ' . $pairing_symbol . '</span>';
  if ( $selected_pairing != 'btc' ) {
  echo '<div class="btc_worth"><span>(' . $btc_worth . ' BTC)</span></div>';
  }

?></td>

<td class='data border_lrb'><?php

  if ( $selected_pairing == 'btc' ) {
  $coin_usd_worth = ( $coin_name == 'Bitcoin' ? $coin_trade_total_raw : ($coin_trade_total_raw * get_btc_usd($btc_exchange)['last_trade']) );
  }
  else {
  $coin_usd_worth = ( ($coin_trade_total_raw * $pairing_btc_value) * get_btc_usd($btc_exchange)['last_trade']);
  }
  

echo '$' . number_format($coin_usd_worth, 2, '.', ',');

?></td>

</tr>

<?php
  }

}

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

function data_request($mode, $request, $ttl, $api_server=null, $post_encoding=3, $test_proxy=NULL) { // Default to JSON encoding post requests (most used)

global $version, $user_agent, $api_timeout, $proxy_list;

$cookie_jar = tempnam('/tmp','cookie');
	
// To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
$hash_check = ( $mode == 'array' ? md5(serialize($request)) : md5($request) );


	// Cache API data if set to cache...SESSION cache is only for runtime cache (deleted at end of runtime)...persistent cache is the file cache (which only reliably updates near end of a runtime session because of file locking)
	if ( update_cache_file('cache/api/'.$hash_check.'.dat', $ttl) == true && $ttl > 0 && !$_SESSION['api_cache'][$hash_check] 
	|| $ttl == 0 && !$_SESSION['api_cache'][$hash_check] ) {	
	
	$ch = curl_init( ( $mode == 'array' ? $api_server : '' ) );
	
		
		if ( sizeof($proxy_list) > 0 ) {
		$current_proxy = ( $mode == 'proxy-check' && $test_proxy != NULL ? $test_proxy : random_proxy() );
		$ip_port = explode(':', $current_proxy);
		
		curl_setopt($ch, CURLOPT_PROXY, trim($ip_port[0]) );    
		curl_setopt($ch, CURLOPT_PROXYPORT, trim($ip_port[1]) );    
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);  
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		} 
		else {
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		}
		
		if ( $mode == 'array' && $post_encoding == 1 ) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $request );
		}
		elseif ( $mode == 'array' && $post_encoding == 2 ) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS,  http_build_query($request) );
		}
		elseif ( $mode == 'array' && $post_encoding == 3 ) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($request) );
		}
		elseif ( $mode == 'url' || $mode == 'proxy-check' ) {
		curl_setopt($ch, CURLOPT_URL, $request);
		}
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $api_timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $api_timeout);
	
	$data = curl_exec($ch);
	curl_close($ch);


		if ( !$data ) {
		$data = 'no';
		
		// SAFE VERSION
		$_SESSION['get_data_error'] .= ' No data returned from ' . ( $mode == 'array' ? 'API server "' . $api_server : 'request "' . $request ) . '" (with timeout configuration setting of ' . $api_timeout . ' seconds). <br /> ';
		
		// DEBUGGING VERSION ONLY, CONTAINS USER DATA (API KEYS ETC)
		//$_SESSION['get_data_error'] .= ' No data returned from ' . ( $mode == 'array' ? 'API server "' . $api_server : 'request "' . $request ) . '" (with timeout configuration setting of ' . $api_timeout . ' seconds). <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
		
			if ( sizeof($proxy_list) > 0 && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops
			test_proxies($current_proxy); // Test this proxy, to make sure it's online / configured properly
			}
		
		}
		
		if ( preg_match("/coinmarketcap/i", $request) && !preg_match("/last_updated/i", $data) ) {
		$_SESSION['get_data_error'] .= '##REAL-TIME REQUEST## data error response from '.( $mode == 'array' ? $api_server : $request ).': <br /> =================================== <br />' . $data . ' <br /> =================================== <br />';
		}
	
	
		if ( $data && $ttl > 0 && $mode ) {
		//echo 'File caching data '; // DEBUGGING ONLY
		file_put_contents('cache/api/'.$hash_check.'.dat', $data, LOCK_EX);
		}
		elseif ( !$data ) {
		unlink('cache/api/'.$hash_check.'.dat'); // Delete any existing cache if empty value
		//echo 'Deleted cache file, no data. '; // DEBUGGING ONLY
		}
		
		// Never cache proxy checking data
		if ( $mode != 'proxy-check' ) {
		$_SESSION['api_cache'][$hash_check] = $data; // Cache API data for this runtime session AFTER PERSISTENT FILE CACHE UPDATE, file cache doesn't reliably update until runtime session is ending because of file locking
		}

	// DEBUGGING ONLY
	//$_SESSION['get_data_error'] .= '##REQUEST## Requested ' . ( $mode == 'array' ? 'API server "' . $api_server : 'endpoint "' . $request ) . '". <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
	
	}
	elseif ( $ttl < 0 ) {
	unlink('cache/api/'.$hash_check.'.dat'); // Delete cache if $ttl flagged to less than zero
	//echo 'Deleted cache file, flagged for deletion. '; // DEBUGGING ONLY
	}
	else {
	
	// Use session cache if it exists. Remember file cache doesn't update until session is nearly over because of file locking, so only reliable for persisting a cache long term
	$data = ( $_SESSION['api_cache'][$hash_check] ? $_SESSION['api_cache'][$hash_check] : file_get_contents('cache/api/'.$hash_check.'.dat') );
	
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
	//$_SESSION['get_data_error'] .= ' ##CACHED## request response for ' . ( $mode == 'array' ? 'API server "' . $api_server : 'endpoint "' . $request ) . '". <br /> ' . ( $mode == 'array' ? '<pre>' . print_r($request, TRUE) . '</pre>' : '' ) . ' <br /> ';
	
	
	}
	
	
return $data;


}

//////////////////////////////////////////////////////////

function test_proxies($problem_proxy) {

global $proxy_alerts_freq, $proxy_alerts_type, $to_email, $to_text, $notifyme_accesscode, $textbelt_apikey, $textlocal_account;

$ip_port = explode(':', $problem_proxy);

$ip = $ip_port[0];
$port = $ip_port[1];

// Create cache filename / session var
$cache_filename = $problem_proxy;
$cache_filename = preg_replace("/\./", "-", $cache_filename);
$cache_filename = preg_replace("/:/", "_", $cache_filename);

$proxy_test_url = 'http://httpbin.org/ip';

	if ( update_cache_file('cache/alerts/proxy-check-'.$cache_filename.'.dat', ( $proxy_alerts_freq * 60 ) ) == true
	&& in_array($cache_filename, $_SESSION['proxies_checked']) == false ) {
	
	//$jsondata = @data_request('proxy-check', 'http://httpbin.org/ip', 0, '', '', $problem_proxy);
	$jsondata = @data_request('proxy-check', $proxy_test_url, 0, '', '', $problem_proxy);
	
	$data = json_decode($jsondata, TRUE);
	
		if ( trim($data['origin']) != '' ) {

			
			// Look for the IP in the response
			if ( strstr($data['origin'], $ip) == false ) {
				
			$misconfigured = 1;
			
			$notifyme_alert = 'A checkup on proxy '.$ip.', port '.$port.' detected a misconfiguration. Remote address '.$data['origin'].' Does not match the proxy address.';
			
			$text_alert = 'Proxy '.$problem_proxy.' remote address mismatch (detected ip: '.$data['origin'].').';
		
			}
			
			
		$cached_logs = ( $misconfigured == 1 ? 'Proxy '.$problem_proxy.' checkup status = MISCONFIGURED (test endpoint '.$proxy_test_url.' detected the incoming ip as: '.$data['origin'].'); Remote address DOES NOT match proxy address;' : 'Proxy '.$problem_proxy.' checkup status = OK (test endpoint '.$proxy_test_url.' detected the incoming ip as: '.$data['origin'].');' );
		
		
		}
		else {
			
		$misconfigured = 1;
			
		$cached_logs = 'Proxy '.$problem_proxy.' checkup status = DATA REQUEST FAILED; No connection established at test endpoint '.$proxy_test_url.';';
		
		$notifyme_alert = 'A checkup on proxy '.$ip.', port '.$port.' resulted in a failed data request. No endpoint connection could be established.';
			
		$text_alert = 'Proxy '.$problem_proxy.' failed data request, no endpoint connection established.';

		}

      
      $email_alert = " The proxy ".$problem_proxy." was unresponsive recently. A check on this proxy was performed, and results logged: \n \n ============================================================== \n " . $cached_logs . " \n ============================================================== \n \n ";
                    
		
		// SESSION VAR to avoid duplicate alerts close together (while first alert still has cache file locked for writing)
		$_SESSION['proxies_checked'][] = $cache_filename;
		
		// Cache the logs
		file_put_contents('cache/alerts/proxy-check-'.$cache_filename.'.dat', $cached_logs, LOCK_EX);
		
		
		// Send out alerts
		if ( $misconfigured == 1 ) {
                    
                          
          if (  validate_email($to_email) == 'valid' && $proxy_alerts_type == 'email'
          || validate_email($to_email) == 'valid' && $proxy_alerts_type == 'all' ) {
          safe_mail($to_email, 'A Proxy Was Unresponsive', $email_alert);
          }
      
        
          // Alert parameter configs for comm methods
          $notifyme_params = array(
                                  'notification' => $notifyme_alert,
                                  'accessCode' => $notifyme_accesscode
                                  );
          
          $textbelt_params = array(
                                  'phone' => text_number($to_text),
                                  'message' => $text_alert,
                                  'key' => $textbelt_apikey
                                  );
          
          $textlocal_params = array(
                                   'username' => string_to_array($textlocal_account)[0],
                                   'hash' => string_to_array($textlocal_account)[1],
                                   'numbers' => text_number($to_text),
                                   'message' => $text_alert
                                   );
      
                    
           if ( validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) != '' && trim($textlocal_account) != '' && $proxy_alerts_type == 'text'
           || validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) != '' && trim($textlocal_account) != '' && $proxy_alerts_type == 'all' ) { // Only use text-to-email if other text services aren't configured
           safe_mail( text_email($to_text) , 'Unresponsive Proxy', $text_alert);
           }
      
           if ( trim($notifyme_accesscode) != '' && $proxy_alerts_type == 'notifyme'
           || trim($notifyme_accesscode) != '' && $proxy_alerts_type == 'all' ) {
           data_request('array', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
           }
      
           if ( trim($textbelt_apikey) != '' && trim($textlocal_account) == '' && $proxy_alerts_type == 'text'
           || trim($textbelt_apikey) != '' && trim($textlocal_account) == '' && $proxy_alerts_type == 'all' ) { // Only run if textlocal API isn't being used to avoid double texts
           data_request('array', $textbelt_params, 0, 'https://textbelt.com/text', 2);
           }
      
           if ( trim($textlocal_account) != '' && trim($textbelt_apikey) == '' && $proxy_alerts_type == 'text'
           || trim($textlocal_account) != '' && trim($textbelt_apikey) == '' && $proxy_alerts_type == 'all' ) { // Only run if textbelt API isn't being used to avoid double texts
           data_request('array', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
           }
           
           
       }
          
          
		
	}

}

//////////////////////////////////////////////////////////

function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE) {
// WARNING: THIS ONLY WORKS WELL FOR HTTP-BASED RUNTIME, ----NOT CLI---!

	if ( isset($_SERVER['HTTP_HOST']) ) {
        	
   $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
   $hostname = $_SERVER['HTTP_HOST'];
   $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

   $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
   $core = $core[0];

   $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
   $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
   $base_url = sprintf( $tmplt, $http, $hostname, $end );
            
	}
	else $base_url = 'http://localhost/';

   if ($parse) {
   $base_url = parse_url($base_url);
   	     if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
   }


return $base_url;

}

//////////////////////////////////////////////////////////

function trim_array($data) {

        foreach ( $data as $key => $value ) {
        $data[$key] = trim(remove_formatting($value));
        }
        
return $data;

}

//////////////////////////////////////////////////////////

function random_proxy() {

global $proxy_list;

$proxy = array_rand($proxy_list);

return $proxy_list[$proxy];

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

global $steem_market, $btc_exchange;

return ( $data * $steem_market * get_btc_usd($btc_exchange)['last_trade'] );

}

//////////////////////////////////////////////////////////

function steempower_time($time) {
    
global $_POST, $steem_market, $btc_exchange, $steem_powerdown_time, $steempower_yearly_interest;

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
    
    $powertime_usd = ( $powertime * $steem_market * get_btc_usd($btc_exchange)['last_trade'] );
    
    $steem_total = ( $powertime + $_POST['sp_total'] );
    $usd_total = ( $steem_total * $steem_market * get_btc_usd($btc_exchange)['last_trade'] );
    
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

function volume_usd($pair_name, $volume, $last_trade, $vol_in_pairing=false) {

global $btc_exchange;


$pairing = detect_pairing($pair_name);


	// Get any necessary variables for calculating asset's USD value
	if ( $pair_name == 'bitcoin' ) {
	$btc_usd = $last_trade;
	}
	else {
	$btc_usd = get_btc_usd($btc_exchange)['last_trade'];
	}


   if ( $pairing == 'xmr' && !$_SESSION['xmr_btc'] || $vol_in_pairing == 'xmr' && !$_SESSION['xmr_btc'] ) {
   $_SESSION['xmr_btc'] = get_trade_data('binance', 'XMRBTC')['last_trade'];
   }
   elseif ( $pairing == 'ltc' && !$_SESSION['ltc_btc'] || $vol_in_pairing == 'ltc' && !$_SESSION['ltc_btc'] ) {
   $_SESSION['ltc_btc'] = get_trade_data('binance', 'LTCBTC')['last_trade'];
   }
   elseif ( $pairing == 'eth' && !$_SESSION['eth_btc'] || $vol_in_pairing == 'eth' && !$_SESSION['eth_btc'] ) {
   $_SESSION['eth_btc'] = get_trade_data('binance', 'ETHBTC')['last_trade'];
   }
   elseif ( $pairing == 'usdt' && !$_SESSION['usdt_btc'] || $vol_in_pairing == 'usdt' && !$_SESSION['usdt_btc'] ) {
   $_SESSION['usdt_btc'] = number_format( ( 1 / get_trade_data('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
   }
	
    
	// Get asset USD value
	if ( $vol_in_pairing == 'btc' || $pair_name == 'bitcoin' ) { // Volume calculated in Bitcoin
	$volume_usd = number_format( $btc_usd * $volume , 0, '.', ',');
	}
	elseif ( $vol_in_pairing != false ){ 
	$volume_usd = number_format( $btc_usd * ( $_SESSION[$pairing.'_btc'] * $volume ) , 0, '.', ',');
	}
	else {
		
		if ( $pairing == 'btc' ) {
		$volume_usd = number_format( $btc_usd * ( $last_trade * $volume ) , 0, '.', ',');
		}
		else {
		$volume_usd = number_format( $btc_usd * ( ( $_SESSION[$pairing.'_btc'] * $last_trade ) * $volume ) , 0, '.', ',');
		}
	
	}


return $volume_usd;

}

//////////////////////////////////////////////////////////

function detect_pairing($pair_name) {

$pair_name = preg_replace("/tusd/i", "0000", $pair_name); // SKIP TRUEUSD
$pair_name = preg_replace("/usdc/i", "0000", $pair_name); // SKIP USD Coin

	if ( !preg_match("/btc/i", $pair_name) && !preg_match("/xxbt/i", $pair_name) ) {
	
		// On rare occasion USDT is represented in pairing names as USD
		if ( preg_match("/usd/i", $pair_name) ) {
		return 'usdt'; // Tether
		}
		elseif ( preg_match("/eth/i", $pair_name) && !preg_match("/usd/i", $pair_name) ) {
		return 'eth';
		}
		elseif ( preg_match("/ltc/i", $pair_name) && !preg_match("/usd/i", $pair_name) ) {
		return 'ltc';
		}
		elseif ( preg_match("/xmr/i", $pair_name) && !preg_match("/usd/i", $pair_name) ) {
		return 'xmr';
		}
		else {
		return false;
		}
	
	}
	else {
	return 'btc';
	}

}

//////////////////////////////////////////////////////////


?>
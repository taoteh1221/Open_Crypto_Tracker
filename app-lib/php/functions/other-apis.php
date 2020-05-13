<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// !!!!!!! MAKE SURE API'S TLD HAS SUPPORT ADDED IN $app_config['developer']['top_level_domain_map'] @ config.php !!!!!!!
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


//////////////////////////////////////////////////////////


function grin_api($request) {
 
global $app_config;
 		
$json_string = 'https://api.grinmint.com/v1/networkStats';

$jsondata = @external_api_data('url', $json_string, $app_config['power_user']['chainstats_cache_time']);
    
$data = json_decode($jsondata, true);
    
return $data[$request];
  
}


//////////////////////////////////////////////////////////


function monero_api($request) {
 
global $app_config;
 		
 	$json_string = 'https://moneroblocks.info/api/get_stats';
 	$jsondata = @external_api_data('url', $json_string, $app_config['power_user']['chainstats_cache_time']);
  	
  	$data = json_decode($jsondata, true);
    
		if ( !$data ) {
		return;
		}
		else {
		
		return $data[$request];
		  
		}
  
}


//////////////////////////////////////////////////////////


function bitcoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'https://blockchain.info/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'https://blockchain.info/q/getdifficulty';
		  
		}
		
    $data = @external_api_data('url', $string, $app_config['power_user']['chainstats_cache_time']);
    
  return (float)$data;
  
}


//////////////////////////////////////////////////////////


function dogecoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'https://dogechain.info/chain/Dogecoin/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'https://dogechain.info/chain/Dogecoin/q/getdifficulty';
		  
		}
		
    $data = @external_api_data('url', $string, $app_config['power_user']['chainstats_cache_time']);
    
  return (float)$data;
  
}


//////////////////////////////////////////////////////////


function litecoin_api($request) {
 
global $app_config;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'http://explorer.litecoin.net/chain/Litecoin/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'http://explorer.litecoin.net/chain/Litecoin/q/getdifficulty';
		  
		}
		
    $data = @external_api_data('url', $string, $app_config['power_user']['chainstats_cache_time']);
    
  return (float)$data;
  
  
}


//////////////////////////////////////////////////////////


function decred_api($type, $request) {
 
global $app_config, $runtime_mode;


 	if ( $type == 'block' ) {
 	$json_string = 'https://explorer.dcrdata.org/api/block/best/verbose';
 	}
	elseif ( $type == 'subsidy' ) {
 	$json_string = 'https://explorer.dcrdata.org/api/block/best/subsidy';
 	}

 		
$jsondata = @external_api_data('url', $json_string, $app_config['power_user']['chainstats_cache_time']);
  		
$data = json_decode($jsondata, true);
   	 
return $data[$request];
			  
}


//////////////////////////////////////////////////////////

// https://core.telegram.org/bots/api

// https://core.telegram.org/bots/api#making-requests

// https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_send_updates_to}

// https://api.telegram.org/bot{my_bot_token}/deleteWebhook

// https://api.telegram.org/bot{my_bot_token}/getWebhookInfo

function telegram_user_data($mode) {
	
global $app_config;

	if ( $mode == 'updates' ) {
	
	// Don't cache data, we are storing it as a specific (secured) cache var instead
	$get_telegram_chatroom_data = @external_api_data('url', 'https://api.telegram.org/bot'.$app_config['comms']['telegram_bot_token'].'/getUpdates', 0);
		
	$telegram_chatroom = json_decode($get_telegram_chatroom_data, true);

	$telegram_chatroom = $telegram_chatroom['result']; 

		foreach( $telegram_chatroom as $chat_key => $chat_unused ) {
	
			// Overwrites any earlier value while looping, so we have the latest data
			if ( $telegram_chatroom[$chat_key]['message']['chat']['username'] == trim($app_config['comms']['telegram_your_username']) ) {
			$telegram_user_data = $telegram_chatroom[$chat_key];
			}
	
		}

	return $telegram_user_data;
	
	}
	elseif ( $mode == 'webhook' ) {
		
	// Don't cache data, we are storing it as a specific (secured) cache var instead
	$get_telegram_webhook_data = @external_api_data('url', 'https://api.telegram.org/bot'.$app_config['comms']['telegram_bot_token'].'/getWebhookInfo', 0);
		
	$telegram_webhook = json_decode($get_telegram_webhook_data, true);
	
	// logic here
	
	}
	
	
}


//////////////////////////////////////////////////////////


function etherscan_api($block_info) {
 
global $base_dir, $app_config;


	if ( $app_config['general']['etherscanio_api_key'] == '' ) {
	return false;
	}


  $json_string = 'https://api.etherscan.io/api?module=proxy&action=eth_blockNumber&apikey=' . $app_config['general']['etherscanio_api_key'];
  $jsondata = @external_api_data('url', $json_string, $app_config['power_user']['chainstats_cache_time']);
    
  $data = json_decode($jsondata, true);
  
  $block_number = $data['result'];
    
    	if ( !$block_number ) {
    	return;
    	}
    	else {
    		
    		// Non-dynamic cache file name, because filename would change every recache and create cache bloat
    		if ( update_cache_file('cache/secured/external_api/eth-stats.dat', $app_config['power_user']['chainstats_cache_time'] ) == true ) {
			
  			$json_string = 'https://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true&apikey=' . $app_config['general']['etherscanio_api_key'];
  			$jsondata = @external_api_data('url', $json_string, 0); // ZERO TO NOT CACHE DATA (WOULD CREATE CACHE BLOAT)
    		
    		store_file_contents($base_dir . '/cache/secured/external_api/eth-stats.dat', $jsondata);
    		
    		$data = json_decode($jsondata, true);
    		
    		return $data['result'][$block_info];
    		
    		}
    		else {
    			
    		$cached_data = trim( file_get_contents('cache/secured/external_api/eth-stats.dat') );
    		
    		$data = json_decode($cached_data, true);
    		
    		return $data['result'][$block_info];

    		}
  
    	}
  
}


//////////////////////////////////////////////////////////


function coingecko_api($force_primary_currency=null) {
	
global $app_config;

$result = array();

// Don't overwrite global
$coingecko_primary_currency = ( $force_primary_currency != null ? strtolower($force_primary_currency) : strtolower($app_config['general']['btc_primary_currency_pairing']) );

$jsondata = @external_api_data('url', 'https://api.coingecko.com/api/v3/coins/markets?per_page='.$app_config['power_user']['marketcap_ranks_max'].'&page=1&vs_currency='.$coingecko_primary_currency.'&price_change_percentage=1h,24h,7d,14d,30d,200d,1y', $app_config['power_user']['marketcap_cache_time']);
	   
$data = json_decode($jsondata, true);

   if ( is_array($data) || is_object($data) ) {
  		
  	 	foreach ($data as $key => $value) {
     	  	
        	if ( $data[$key]['symbol'] != '' ) {
        	$result[strtolower($data[$key]['symbol'])] = $data[$key];
     	  	}
    
  	  	}
  	  
  	}
		  
		  
return $result;
  
}


//////////////////////////////////////////////////////////


function coinmarketcap_api() {
	
global $app_config, $coinmarketcap_currencies, $cap_data_force_usd, $cmc_notes;

$result = array();


	if ( trim($app_config['general']['coinmarketcapcom_api_key']) == null ) { 
	app_logging('config_error', '"coinmarketcapcom_api_key" (free API key) is not configured in config.php', false, false, true);
	return false;
	}
	

	// Don't overwrite global
	$coinmarketcap_primary_currency = strtoupper($app_config['general']['btc_primary_currency_pairing']);
	
		
		if ( in_array($coinmarketcap_primary_currency, $coinmarketcap_currencies) ) {
		$convert = $coinmarketcap_primary_currency;
		$cap_data_force_usd = null;
		}
		// Default to USD, if currency is not supported
		else {
		$cmc_notes = 'Coinmarketcap.com does not support '.$coinmarketcap_primary_currency.' stats,<br />showing USD stats instead.';
		$convert = 'USD';
		$cap_data_force_usd = 1;
		}
		
	
	$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: ' . $app_config['general']['coinmarketcapcom_api_key']
	];

	$cmc_params = array(
	  							'start' => '1',
	 							'limit' => $app_config['power_user']['marketcap_ranks_max'],
	  							'convert' => $convert
								);

	$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
		
	$qs = http_build_query($cmc_params); // query string encode the parameters
	
	$request = "{$url}?{$qs}"; // create the request URL

	$jsondata = @external_api_data('url', $request, $app_config['power_user']['remote_api_timeout'], null, null, null, $headers);
	
	$data = json_decode($jsondata, true);
        
   $data = $data['data'];
        
	

    if ( is_array($data) || is_object($data) ) {
  		
  	   	foreach ($data as $key => $value) {
     	  	
        		if ( $data[$key]['symbol'] != '' ) {
        		$result[strtolower($data[$key]['symbol'])] = $data[$key];
     	  		}
    	 
  	   	}
     
    return $result;
 	 }

		  
}


//////////////////////////////////////////////////////////


?>
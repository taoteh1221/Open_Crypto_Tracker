<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// !!!!!!! MAKE SURE API'S TLD HAS SUPPORT ADDED IN $tld_map @ /app-lib/php/init.php !!!!!!!
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


//////////////////////////////////////////////////////////


function bitcoin_api($request) {
 
global $chainstats_cache;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'https://blockchain.info/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'https://blockchain.info/q/getdifficulty';
		  
		}
		
    $data = @api_data('url', $string, $chainstats_cache);
    
  return (float)$data;
  
}


//////////////////////////////////////////////////////////


function dogecoin_api($request) {
 
global $chainstats_cache;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'https://dogechain.info/chain/Dogecoin/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'https://dogechain.info/chain/Dogecoin/q/getdifficulty';
		  
		}
		
    $data = @api_data('url', $string, $chainstats_cache);
    
  return (float)$data;
  
}


//////////////////////////////////////////////////////////


function grin_api($request) {
 
global $chainstats_cache;
 		
$json_string = 'https://api.grinmint.com/v1/networkStats';

$jsondata = @api_data('url', $json_string, $chainstats_cache);
    
$data = json_decode($jsondata, TRUE);
    
return $data[$request];
  
}


//////////////////////////////////////////////////////////


function litecoin_api($request) {
 
global $chainstats_cache;
 		
    
		if ( $request == 'height' ) {
		
    	$string = 'http://explorer.litecoin.net/chain/Litecoin/q/getblockcount';
		  
		}
		elseif ( $request == 'difficulty' ) {
		
    	$string = 'http://explorer.litecoin.net/chain/Litecoin/q/getdifficulty';
		  
		}
		
    $data = @api_data('url', $string, $chainstats_cache);
    
  return (float)$data;
  
  
}


//////////////////////////////////////////////////////////


function decred_api($type, $request) {
 
global $chainstats_cache, $runtime_mode;

	if ( $runtime_mode != 'ui' ) {
	return false;  // We only use the block reward config file call for UI data, can skip the API request if not running the UI.
	}
 	else {
 		
 		if ( $type == 'block' ) {
 		$json_string = 'https://explorer.dcrdata.org/api/block/best/verbose';
 		}
 		elseif ( $type == 'subsidy' ) {
 		$json_string = 'https://explorer.dcrdata.org/api/block/best/subsidy';
 		}
 		
 		$jsondata = @api_data('url', $json_string, $chainstats_cache);
  		
  		$data = json_decode($jsondata, TRUE);
   	 
		return $data[$request];
			  
			
	}
  
}


//////////////////////////////////////////////////////////


function monero_api($request) {
 
global $chainstats_cache;
 		
 	$json_string = 'https://moneroblocks.info/api/get_stats';
 	$jsondata = @api_data('url', $json_string, $chainstats_cache);
  	
  	$data = json_decode($jsondata, TRUE);
    
		if ( !$data ) {
		return;
		}
		else {
		
		return $data[$request];
		  
		}
  
}


//////////////////////////////////////////////////////////


function etherscan_api($block_info) {
 
global $base_dir, $chainstats_cache;

  $json_string = 'https://api.etherscan.io/api?module=proxy&action=eth_blockNumber';
  $jsondata = @api_data('url', $json_string, $chainstats_cache);
    
  $data = json_decode($jsondata, TRUE);
  
  $block_number = $data['result'];
    
    	if ( !$block_number ) {
    	return;
    	}
    	else {
    		
    		// Non-dynamic cache file name, because filename would change every recache and create cache bloat
    		if ( update_cache_file('cache/apis/eth-stats.dat', $chainstats_cache ) == true ) {
			
  			$json_string = 'https://api.etherscan.io/api?module=proxy&action=eth_getBlockByNumber&tag='.$block_number.'&boolean=true';
  			$jsondata = @api_data('url', $json_string, 0); // ZERO TO NOT CACHE DATA (WOULD CREATE CACHE BLOAT)
    		
    		store_file_contents($base_dir . '/cache/apis/eth-stats.dat', $jsondata);
    		
    		$data = json_decode($jsondata, TRUE);
    		
    		return $data['result'][$block_info];
    		
    		}
    		else {
    			
    		$cached_data = trim( file_get_contents('cache/apis/eth-stats.dat') );
    		
    		$data = json_decode($cached_data, TRUE);
    		
    		return $data['result'][$block_info];

    		}
  
    	}
  
}


//////////////////////////////////////////////////////////


function coingecko_api($symbol) {
	
global $marketcap_ranks_max, $marketcap_cache;

$array_merging = array();


	if ( !$_SESSION['cgk_data'] ) {


	$jsondata = @api_data('url', 'https://api.coingecko.com/api/v3/coins?per_page='.$marketcap_ranks_max.'&page=1', $marketcap_cache);
	   
   $_SESSION['cgk_data'] = json_decode($jsondata, TRUE);

	}



   if ( is_array($_SESSION['cgk_data']) || is_object($_SESSION['cgk_data']) ) {
  		
  	   	foreach ($_SESSION['cgk_data'] as $key => $value) {
     	  	
  	     	
        		if ( $_SESSION['cgk_data'][$key]['symbol'] == strtolower($symbol) ) {
  	      		

        		return $_SESSION['cgk_data'][$key];
        
        
     	  		}
    	 
    
  	   	}
      	
     
  	 }
		  

  
}


//////////////////////////////////////////////////////////


function coinmarketcap_api($symbol) {
	
global $btc_fiat_pairing, $api_timeout, $coinmarketcapcom_api_key, $marketcap_ranks_max, $marketcap_cache;


	if ( trim($coinmarketcapcom_api_key) == NULL ) { 
	
	app_logging('cmc_config_error', '"$coinmarketcapcom_api_key" is not configured in config.php', false, false, true);
	
	return FALSE;
	
	}
	

	if ( !$_SESSION['cmc_data'] ) {
		
	// Don't overwrite global
	$coinmarketcap_fiat = strtoupper($btc_fiat_pairing);
	
	$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: ' . $coinmarketcapcom_api_key
	];

	$cmc_params = array(
	  							'start' => '1',
	 							'limit' => $marketcap_ranks_max,
	  							'convert' => $coinmarketcap_fiat
								);

	$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
		
	$qs = http_build_query($cmc_params); // query string encode the parameters
	
	$request = "{$url}?{$qs}"; // create the request URL

	$jsondata = @api_data('url', $request, $api_timeout, NULL, NULL, NULL, $headers);
	
	$data = json_decode($jsondata, TRUE);
	
	$_SESSION['cmc_data'] = $data['data'];
	
	}

	

    if ( is_array($_SESSION['cmc_data']) || is_object($_SESSION['cmc_data']) ) {
  		
  		
  	   	foreach ($_SESSION['cmc_data'] as $key => $value) {
     	  	
  	     	
        		if ( $_SESSION['cmc_data'][$key]['symbol'] == strtoupper($symbol) ) {
  	      	
        		return $_SESSION['cmc_data'][$key];
        
        
     	  		}
    	 
    
  	   	}
      	
     
 	 }

		  
  
}


//////////////////////////////////////////////////////////


?>
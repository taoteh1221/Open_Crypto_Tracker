<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

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

function litecoin_api($request) {
 
global $chainstats_cache;
 		
    $json_string = 'https://chain.so/api/v2/get_info/LTC';
    
    $jsondata = @api_data('url', $json_string, $chainstats_cache);
    
    $data = json_decode($jsondata, TRUE);
    
    
		if ( $request == 'height' ) {
		
		return $data['data']['blocks'];
		  
		}
		elseif ( $request == 'difficulty' ) {
		
		return $data['data']['mining_difficulty'];
		  
		}
  
  
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
 
global $chainstats_cache;

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
    		
    		file_put_contents('cache/apis/eth-stats.dat', $jsondata, LOCK_EX);
    		
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
     	     
	  	$jsondata = @api_data('url', $json_string, $marketcap_cache);
	   
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
	
global $marketcap_ranks_max, $marketcap_cache;

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
     	     
	  	$jsondata = @api_data('url', $json_string, $marketcap_cache);
	   
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


?>
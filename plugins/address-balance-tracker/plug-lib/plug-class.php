<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 
 
// CREATE THIS PLUGIN'S CLASS OBJECT DYNAMICALLY AS: $plug['class'][$this_plug]
$plug['class'][$this_plug] = new class() {
				
	
// Class variables / arrays

var $var1;
var $var2;
var $var3;
var $array1 = array();

	
	// Class functions
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
     
     // Validating user input in the admin interface
	function admin_input_validation() {
		 
	global $ct, $plug, $this_plug;
	
     // Test mode (retrieves current block height)    
	$solana_block_height = $this->sol_addr_bal(false, false, $_POST[$this_plug]['solana_rpc_server']);
	
	
          if (
          !isset($solana_block_height)
          || isset($solana_block_height) && !is_int($solana_block_height)
          || isset($solana_block_height) && $solana_block_height < 1
          ) {
          $ct['update_config_error'] .= 'Solana RPC Server "' . $_POST[$this_plug]['solana_rpc_server'] . '" query test FAILED (make sure you entered the RPC endpoint address correctly)';
          }

     
     return $ct['update_config_error'];
		
	}
	
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function btc_addr_bal($address) {
		 
	global $ct, $plug, $this_plug;
		
	// Take into account previous runtime (over start of runtime), and gives wiggle room
	// (MUST BE minimum value of zero...NEGATIVE VALUES ONLY FLAG CACHE DELETION [RETURNS NO DATA])
	$calc = ($plug['conf'][$this_plug]['alerts_frequency_maximum'] * 60) + $ct['dev']['tasks_time_offset'];
	$refresh_cache = ( $calc >= 0 ? $calc : 0 );
		
	$url = 'https://blockchain.info/rawaddr/' . $address;
			 
	$response = @$ct['cache']->ext_data('url', $url, $refresh_cache);
			 
	$data = json_decode($response, true);
		   
		   
		if ( isset($data['final_balance']) ) {
		return $ct['var']->num_to_str( $data['final_balance'] / 100000000 ); // Convert sats to BTC
		}
		elseif ( !isset($data['address']) ) {
			
    	     $ct['gen']->log(
    				'ext_data_error',
    				'BTC address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received for address: ' . $address
    				);
    	
		return 'error';
		
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function eth_addr_bal($address) {
		 
	global $ct, $plug, $this_plug;
		
	// Take into account previous runtime (over start of runtime), and gives wiggle room
	// (MUST BE minimum value of zero...NEGATIVE VALUES ONLY FLAG CACHE DELETION [RETURNS NO DATA])
	$calc = ($plug['conf'][$this_plug]['alerts_frequency_maximum'] * 60) + $ct['dev']['tasks_time_offset'];
	$refresh_cache = ( $calc >= 0 ? $calc : 0 );
		
	$url = 'https://api.etherscan.io/api?module=account&action=balance&address='.$address.'&tag=latest&apikey=' . $ct['conf']['ext_apis']['etherscan_api_key'];
			 
	$response = @$ct['cache']->ext_data('url', $url, $refresh_cache);
			 
	$data = json_decode($response, true);
		   
		   
		if ( isset($data['result']) ) {
		return $ct['var']->num_to_str( $data['result'] / 1000000000000000000 ); // Convert wei to ETH
		}
		elseif ( !isset($data['message']) ) {
			
    	     $ct['gen']->log(
    				'ext_data_error',
    				'ETH address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received for address: ' . $address
    				);
    	
		return 'error';
		
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function sol_addr_bal($address=false, $spl_token=false, $rpc_test=false) {
		 
	global $ct, $plug, $this_plug;
		
	// Take into account previous runtime (over start of runtime), and gives wiggle room
	// (MUST BE minimum value of zero...NEGATIVE VALUES ONLY FLAG CACHE DELETION [RETURNS NO DATA])
	$calc = ($plug['conf'][$this_plug]['alerts_frequency_maximum'] * 60) + $ct['dev']['tasks_time_offset'];
	$refresh_cache = ( $calc >= 0 ? $calc : 0 );
	
        
     $headers = array(
                    'Content-Type: application/json'
                    );
                    
	
     	if ( $rpc_test != false ) {
     	     
     	$refresh_cache = 0;
     	     
     	$rpc_server = $rpc_test;
     	
          $request_params = array(
                                'jsonrpc' => '2.0', // Setting this right before sending
                                'id' => 1,
                                'method' => 'getBlockHeight',
                                );
                                
     	}
     	else {
     	     
     	$rpc_server = $plug['conf'][$this_plug]['solana_rpc_server'];
     	
          $request_params = array(
                                'jsonrpc' => '2.0', // Setting this right before sending
                                'id' => 1,
                                'method' => ( $spl_token == false ? 'getBalance' : 'getTokenAccountBalance' ),
                                'params' => array($address),
                                );
                                
     	}
                    
     
     // https://solana.com/docs/core/clusters#mainnet-beta-rate-limits
	$response = @$ct['cache']->ext_data('params', $request_params, $refresh_cache, $rpc_server, 3, null, $headers);
			 
	$data = json_decode($response, true);
			 
	$data = $data['result'];
		   
		   
		if ( $rpc_test != false ) {
		return $data;
		}
		elseif ( isset($data['value']) ) {
		    
		    
		    if ( $spl_token == false ) {
		    return $ct['var']->num_to_str( $data['value'] / 1000000000 ); // Convert lamports to SOL
		    }
		    else {
		    $divide_by = str_pad(1, (1 + $data['value']['decimals']), "0");
		    return $ct['var']->num_to_str( $data['value']['amount'] / $divide_by ); // Convert to spl token's unit value
		    }
		
		
		}
		elseif ( !isset($data['context']) ) {
			
    	     $ct['gen']->log(
    				'ext_data_error',
    				( $spl_token == false ? 'SOL' : strtoupper($spl_token) ) . ' address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received for address: ' . $address
    				);
    	
		return 'error';
		
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
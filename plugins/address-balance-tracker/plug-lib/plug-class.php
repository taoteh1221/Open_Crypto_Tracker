<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 
 
// CREATE THIS PLUGIN'S CLASS OBJECT DYNAMICALLY AS: $plug_class[$this_plug]
$plug_class[$this_plug] = new class() {
				
	
// Class variables / arrays

var $var1;
var $var2;
var $var3;
var $array1 = array();

	
	// Class functions
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function btc_addr_bal($address) {
		 
	global $ct, $this_plug, $plug_conf;
		
	// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
	$recache = ( $plug_conf[$this_plug]['alerts_freq_max'] >= 3 ? ($plug_conf[$this_plug]['alerts_freq_max'] + $ct['dev']['tasks_time_offset']) : $plug_conf[$this_plug]['alerts_freq_max'] );
		
	$url = 'https://blockchain.info/rawaddr/' . $address;
			 
	$response = @$ct['cache']->ext_data('url', $url, $recache);
			 
	$data = json_decode($response, true);
		   
		   
		if ( isset($data['final_balance']) ) {
		return $ct['var']->num_to_str( $data['final_balance'] / 100000000 ); // Convert sats to BTC
		}
		elseif ( !isset($data['address']) ) {
			
    	     $ct['gen']->log(
    				'ext_data_error',
    				'BTC address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received'
    				);
    	
		return 'error';
		
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function eth_addr_bal($address) {
		 
	global $ct, $this_plug, $plug_conf;
		
	// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
	$recache = ( $plug_conf[$this_plug]['alerts_freq_max'] >= 3 ? ($plug_conf[$this_plug]['alerts_freq_max'] + $ct['dev']['tasks_time_offset']) : $plug_conf[$this_plug]['alerts_freq_max'] );
		
	$url = 'https://api.etherscan.io/api?module=account&action=balance&address='.$address.'&tag=latest&apikey=' . $ct['conf']['ext_apis']['etherscan_key'];
			 
	$response = @$ct['cache']->ext_data('url', $url, $recache);
			 
	$data = json_decode($response, true);
		   
		   
		if ( isset($data['result']) ) {
		return $ct['var']->num_to_str( $data['result'] / 1000000000000000000 ); // Convert wei to ETH
		}
		elseif ( !isset($data['message']) ) {
			
    	     $ct['gen']->log(
    				'ext_data_error',
    				'ETH address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received'
    				);
    	
		return 'error';
		
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function sol_addr_bal($address, $spl_token=false) {
		 
	global $ct, $this_plug, $plug_conf;
		
	// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
	$recache = ( $plug_conf[$this_plug]['alerts_freq_max'] >= 3 ? ($plug_conf[$this_plug]['alerts_freq_max'] + $ct['dev']['tasks_time_offset']) : $plug_conf[$this_plug]['alerts_freq_max'] );
	
        
    $headers = array(
                    'Content-Type: application/json'
                    );
                    
			 
    $request_params = array(
                           'jsonrpc' => '2.0', // Setting this right before sending
                           'id' => 1,
                           'method' => ( $spl_token == false ? 'getBalance' : 'getTokenAccountBalance' ),
                           'params' => array($address),
                           );
                    
                
	$response = @$ct['cache']->ext_data('params', $request_params, $recache, 'https://api.mainnet-beta.solana.com', 3, null, $headers);
			 
	$data = json_decode($response, true);
			 
	$data = $data['result'];
		   
		   
		if ( isset($data['value']) ) {
		    
		    
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
    				( $spl_token == false ? 'SOL' : strtoupper($spl_token) ) . ' address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received'
    				);
    	
		return 'error';
		
		}
		
		
	}
   
   
    ////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////
   
   
    function obfusc_addr($address) {
      
    global $ct, $log_errors;
    
       foreach ( $log_errors as $key => $val ) {
       $log_errors[$key] = str_replace($address, $ct['var']->obfusc_str($address, 1), $log_errors[$key]);
       }
   
    }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
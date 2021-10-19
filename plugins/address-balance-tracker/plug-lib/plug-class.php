<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
		 
	global $this_plug, $oct_conf, $oct_var, $oct_cache;
		
	// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
	$recache = ( $plug_conf[$this_plug]['alerts_freq_max'] >= 3 ? ($plug_conf[$this_plug]['alerts_freq_max'] - 3) : $plug_conf[$this_plug]['alerts_freq_max'] );
		
	$url = 'https://blockchain.info/rawaddr/' . $address;
			 
	$response = @$oct_cache->ext_data('url', $url, $recache);
			 
	$data = json_decode($response, true);
		   
		   
		if ( isset($data['final_balance']) ) {
		return $oct_var->num_to_str( $data['final_balance'] / 100000000 ); // Convert sats to BTC
		}
		elseif ( !isset($data['address']) ) {
			
    	$oct_gen->log(
    				'ext_data_error',
    				'BTC address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received'
    				);
    	
		return 'error';
		
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function eth_addr_bal($address) {
		 
	global $this_plug, $oct_conf, $oct_var, $oct_cache;
		
	// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
	$recache = ( $plug_conf[$this_plug]['alerts_freq_max'] >= 3 ? ($plug_conf[$this_plug]['alerts_freq_max'] - 3) : $plug_conf[$this_plug]['alerts_freq_max'] );
		
	$url = 'https://api.etherscan.io/api?module=account&action=balance&address='.$address.'&tag=latest&apikey=' . $oct_conf['gen']['etherscan_key'];
			 
	$response = @$oct_cache->ext_data('url', $url, $recache);
			 
	$data = json_decode($response, true);
		   
		   
		if ( isset($data['result']) ) {
		return $oct_var->num_to_str( $data['result'] / 1000000000000000000 ); // Convert wei to ETH
		}
		elseif ( !isset($data['message']) ) {
			
    	$oct_gen->log(
    				'ext_data_error',
    				'ETH address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received'
    				);
    	
		return 'error';
		
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function hnt_addr_bal($address) {
		 
	global $this_plug, $oct_conf, $oct_var, $oct_cache;
		
	// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
	$recache = ( $plug_conf[$this_plug]['alerts_freq_max'] >= 3 ? ($plug_conf[$this_plug]['alerts_freq_max'] - 3) : $plug_conf[$this_plug]['alerts_freq_max'] );
		
	$url = 'https://api.helium.io/v1/accounts/'.$address;
			 
	$response = @$oct_cache->ext_data('url', $url, $recache);
			 
	$data = json_decode($response, true);
			 
	$data = $data['data'];
		   
		   
		if ( isset($data['balance']) ) {
		return $oct_var->num_to_str( $data['balance'] / 100000000 ); // Convert bones to HNT
		}
		elseif ( !isset($data['address']) ) {
			
    	$oct_gen->log(
    				'ext_data_error',
    				'HNT address balance retrieval failed in the "' . $this_plug . '" plugin, no API data received'
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
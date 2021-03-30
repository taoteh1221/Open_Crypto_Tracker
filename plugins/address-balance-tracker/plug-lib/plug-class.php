<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */
 
 
// CREATE THIS PLUGIN'S CLASS OBJECT DYNAMICALLY AS: $plug_class[$this_plug]

$plug_class[$this_plug] = new class() {
				
	
// Class variables / arrays

var $ocpt_var1;
var $ocpt_var2;
var $ocpt_var3;
var $ocpt_array1 = array();

	
	// Class functions
	
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function btc_addr_bal($address) {
		 
	global $this_plug, $ocpt_conf, $ocpt_var, $ocpt_cache;
		
	// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
	$recache = ( $plug_conf[$this_plug]['alerts_freq_max'] >= 3 ? ($plug_conf[$this_plug]['alerts_freq_max'] - 3) : $plug_conf[$this_plug]['alerts_freq_max'] );
		
	$json_string = 'https://blockchain.info/rawaddr/' . $address;
			 
	$jsondata = @$ocpt_cache->ext_data('url', $json_string, $recache);
			 
	$data = json_decode($jsondata, true);
		   
		   
		if ( isset($data['final_balance']) ) {
		return $ocpt_var->num_to_str( $data['final_balance'] / 100000000 ); // Convert sats to BTC
		}
		else {
		return false;
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
		
	function eth_addr_bal($address) {
		 
	global $this_plug, $ocpt_conf, $ocpt_var, $ocpt_cache;
		
	// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
	$recache = ( $plug_conf[$this_plug]['alerts_freq_max'] >= 3 ? ($plug_conf[$this_plug]['alerts_freq_max'] - 3) : $plug_conf[$this_plug]['alerts_freq_max'] );
		
	$json_string = 'https://api.etherscan.io/api?module=account&action=balance&address='.$address.'&tag=latest&apikey=' . $ocpt_conf['gen']['etherscan_key'];
			 
	$jsondata = @$ocpt_cache->ext_data('url', $json_string, $recache);
			 
	$data = json_decode($jsondata, true);
		   
		   
		if ( isset($data['result']) ) {
		return $ocpt_var->num_to_str( $data['result'] / 1000000000000000000 ); // Convert wei to ETH
		}
		else {
		return false;
		}
		
		
	}
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////


				
};

// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
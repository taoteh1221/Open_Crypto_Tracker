<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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
	function address_valid($address) {
		 
	global $ct, $plug, $this_plug;

     $original = trim($address);
     
     $sanitized = $original;
     
     // FLAG tabs, spaces, and new lines
     $sanitized = preg_replace("/[\s\W]+/", "FLAG", $sanitized);
     
     // FLAG non-alphanumeric
     $sanitized = preg_replace("/[^A-Za-z0-9 ]/", "FLAG", $sanitized);
     
     // Remove HTML
     $sanitized = strip_tags($sanitized);
     
     
          // Check if sanitized input matches original input
          // (we want to use original input to play it safe or flag invalid, since this is crypto-related)
          if ( trim($address) == '' || $original != $sanitized ) {
          return false;
          }
          else {
          return true;
          }

		
	}
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
		
     
     // Validating user input in the admin interface
	function admin_input_validation() {
		 
	global $ct, $plug, $this_plug;
     
     $update_config_error_seperator = '<br /> ';
          
         // Make sure do not disturb on/off is set properly (IF filled in, CAN BE BLANK TO DISABLE)
         
         foreach ( $_POST[$this_plug]['tracking'] as $key => $val ) {
         
            if ( !$this->address_valid( $_POST[$this_plug]['tracking'][$key]['crypto_address'] ) ) {
         $ct['update_config_error'] .= $update_config_error_seperator . 'Invalid crypto address: "'.$_POST[$this_plug]['tracking'][$key]['crypto_address'].'"';
            }
            
            if ( trim($_POST[$this_plug]['tracking'][$key]['label']) == '' ) {
         $ct['update_config_error'] .= $update_config_error_seperator . 'Label missing (for crypto address: "'.$_POST[$this_plug]['tracking'][$key]['crypto_address'].'")';
            }
         
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
		   
		   
		if ( isset($data['final_balance']) && is_numeric($data['final_balance']) ) {
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
		   
		   
		if ( isset($data['result']) && is_numeric($data['result']) ) {
		return $ct['var']->num_to_str($data['result'] / 1000000000000000000); // Convert wei to ETH
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
		
		
	function sol_addr_bal($address=false, $spl_token=false) {
		 
	global $ct, $plug, $this_plug;
		
	// Take into account previous runtime (over start of runtime), and gives wiggle room
	// (MUST BE minimum value of zero...NEGATIVE VALUES ONLY FLAG CACHE DELETION [RETURNS NO DATA])
	$calc = ($plug['conf'][$this_plug]['alerts_frequency_maximum'] * 60) + $ct['dev']['tasks_time_offset'];
	$refresh_cache = ( $calc >= 0 ? $calc : 0 );
     
     $data = $ct['api']->blockchain_rpc('solana', ( $spl_token == false ? 'getBalance' : 'getTokenAccountBalance' ) , array($address) , $refresh_cache )['result'];
     
     // DEBUGGING
	//$debug_data = json_encode($data, JSON_PRETTY_PRINT);
	//$debug_cache_file = $ct['plug']->debug_cache('sol_addr_bal_'.$address.'.dat', $this_plug);
	//$ct['cache']->save_file($debug_cache_file, $debug_data);
		   
		   
		if ( isset($data['value']) ) {
		    
		    
		    if ( $spl_token == false && is_numeric($data['value']) ) {
		    return $ct['var']->num_to_str( $data['value'] / 1000000000 ); // Convert lamports to SOL
		    }
		    elseif ( isset($data['value']['amount']) && is_numeric($data['value']['amount']) ) {
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
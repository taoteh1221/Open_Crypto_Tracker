<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// CSV header
$csv_download_array[] = array(
	        							'Asset Symbol',
	        							'Holdings',
	        							'Purchase Average (per-token)',
	        							'Margin Leverage',
	        							'Long or Short',
	        							'Exchange ID',
	        							'Market Pairing'
	        							);
	    
	    
	foreach ( $coins_list as $coin_array_key => $coin_array_value ) {
		
	    
	    $field_var_pairing = strtolower($coin_array_key) . '_pairing';
	    $field_var_market = strtolower($coin_array_key) . '_market';
	    $field_var_amount = strtolower($coin_array_key) . '_amount';
	    $field_var_paid = strtolower($coin_array_key) . '_paid';
	    $field_var_leverage = strtolower($coin_array_key) . '_leverage';
	    $field_var_margintype = strtolower($coin_array_key) . '_margintype';
	    $field_var_watchonly = strtolower($coin_array_key) . '_watchonly';
	    $field_var_restore = strtolower($coin_array_key) . '_restore';
	    
	    
	    
	    $coin_pairing_id = $_POST[$field_var_pairing];
	    $coin_market_id = $_POST[$field_var_market];
	    $asset_amount_value = $_POST[$field_var_amount];
	    $coin_paid_value = $_POST[$field_var_paid];
	    $coin_leverage_value = $_POST[$field_var_leverage];
	    $coin_margintype_value = $_POST[$field_var_margintype];
	        	
	        
	    $selected_pairing = ( $coin_pairing_id ? $coin_pairing_id : NULL );
	    
	    
			foreach ( $coins_list[strtoupper($coin_array_key)]['market_pairing'] as $pairing_key => $unused ) {
			$ploop = 0;
					 						
				// Use first pairing key from coins config for this asset, if no pairing value was set properly
				if ( $ploop == 0 ) {
				
					if ( $selected_pairing == NULL || !$coins_list[strtoupper($coin_array_key)]['market_pairing'][$selected_pairing] ) {
					$selected_pairing = $pairing_key;
					}
				
				}
											
			$ploop = $ploop + 1;
			}
											
	    
	    	if ( strtoupper($coin_array_key) == 'MISCASSETS' ) {
	    	$asset_amount_decimals = 2;
	    	}
	    	else {
	    	$asset_amount_decimals = 8;
	    	}
	    
	  	 $asset_amount_value = pretty_numbers($asset_amount_value, $asset_amount_decimals);
	    
	    $coin_paid_value = ( float_to_string($coin_paid_value) >= 1.00 ? pretty_numbers($coin_paid_value, 2) : pretty_numbers($coin_paid_value, $fiat_decimals_max) );
	  	 
	    
	   	// Asset data to array for CSV export
	      if ( remove_number_format($asset_amount_value) >= 0.00000001 ) {
	        	
	        $csv_download_array[] = array(
	        											strtoupper($coin_array_key),
	        											$asset_amount_value,
	        											$coin_paid_value,
	        											$coin_leverage_value,
	        											$coin_margintype_value,
	        											$coin_market_id,
	        											$selected_pairing
	        											);
	        											
	      }
	        
	        
	        
	      
	      
	}
	 



// Log errors / debugging, send notifications, destroy session data
error_logs();
debugging_logs();
send_notifications();
hardy_session_clearing();

// Run last, as it exits when completed
create_csv_file('temp', 'Crypto_Portfolio.csv', $csv_download_array); 


?>
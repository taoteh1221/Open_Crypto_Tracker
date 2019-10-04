<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// CSV header
$csv_download_array[] = array(
	        							'Asset Symbol',
	        							'Amount Held',
	        							'USD Purchase Average (per-token)',
	        							'Margin Leverage',
	        							'Long or Short',
	        							'Market ID (Exchange)',
	        							'Base Pairing'
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
	    $coin_amount_value = $_POST[$field_var_amount];
	    $coin_paid_value = $_POST[$field_var_paid];
	    $coin_leverage_value = $_POST[$field_var_leverage];
	    $coin_margintype_value = $_POST[$field_var_margintype];
	        	
	        
	    $selected_pairing = ( $coin_pairing_id ? $coin_pairing_id : 'btc' );
	    
	    
	    	if ( strtoupper($coin_array_key) == 'MISCUSD' ) {
	    	$coin_amount_decimals = 2;
	    	}
	    	else {
	    	$coin_amount_decimals = 8;
	    	}
	    
	  	 $coin_amount_value = pretty_numbers($coin_amount_value, $coin_amount_decimals);
	    
	  	 $coin_paid_value = pretty_numbers($coin_paid_value, 8);
	  	 
	    
	   	// Asset data to array for CSV export
	      if ( remove_number_format($coin_amount_value) >= 0.00000001 ) {
	        	
	        $csv_download_array[] = array(
	        											strtoupper($coin_array_key),
	        											$coin_amount_value,
	        											$coin_paid_value,
	        											$coin_leverage_value,
	        											$coin_margintype_value,
	        											$coin_market_id,
	        											$selected_pairing
	        											);
	        											
	      }
	        
	        
	        
	      
	      
	}
	 



// Log errors, destroy session data
error_logs();
hardy_session_clearing();

// Run last, as it exits when completed
create_csv_file('temp', $csv_download_array); 


?>
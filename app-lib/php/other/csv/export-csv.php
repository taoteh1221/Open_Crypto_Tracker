<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


$csv_download_array = array();


// CSV header
$csv_download_array[] = array(
	        							'Asset Symbol',
	        							'Holdings',
	        							'Average Paid (per-token)',
	        							'Margin Leverage',
	        							'Long or Short',
	        							'Exchange ID',
	        							'Market Pairing'
	        							);
	    
	    
	foreach ( $ocpt_conf['assets'] as $coin_array_key => $coin_array_val ) {
		
	    
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
	    $asset_amount_val = $_POST[$field_var_amount];
	    $coin_paid_val = $_POST[$field_var_paid];
	    $coin_leverage_val = $_POST[$field_var_leverage];
	    $coin_margintype_val = $_POST[$field_var_margintype];
	        	
	        
	    $sel_pairing = ( $coin_pairing_id ? $coin_pairing_id : NULL );
	    
	    
			foreach ( $ocpt_conf['assets'][strtoupper($coin_array_key)]['pairing'] as $pairing_key => $unused ) {
			$ploop = 0;
					 						
				// Use first pairing key from coins config for this asset, if no pairing value was set properly
				if ( $ploop == 0 ) {
				
					if ( $sel_pairing == NULL || !$ocpt_conf['assets'][strtoupper($coin_array_key)]['pairing'][$sel_pairing] ) {
					$sel_pairing = $pairing_key;
					}
				
				}
											
			$ploop = $ploop + 1;
			}
											
	    
	    	if ( strtoupper($coin_array_key) == 'MISCASSETS' ) {
	    	$asset_amount_dec = 2;
	    	}
	    	else {
	    	$asset_amount_dec = 8;
	    	}
	    
	  	 $asset_amount_val = $ocpt_var->num_pretty($asset_amount_val, $asset_amount_dec);
	    
	    $coin_paid_val = ( $ocpt_var->num_to_str($coin_paid_val) >= $ocpt_conf['gen']['prim_curr_dec_max_thres'] ? $ocpt_var->num_pretty($coin_paid_val, 2) : $ocpt_var->num_pretty($coin_paid_val, $ocpt_conf['gen']['prim_curr_dec_max']) );
	  	 
	    
	   	// Asset data to array for CSV export
	      if ( trim($coin_array_key) != '' && $ocpt_var->rem_num_format($asset_amount_val) >= 0.00000001 ) {
	        	
	        $csv_download_array[] = array(
	        											strtoupper($coin_array_key),
	        											$asset_amount_val,
	        											$coin_paid_val,
	        											$coin_leverage_val,
	        											$coin_margintype_val,
	        											$coin_market_id,
	        											$sel_pairing
	        											);
	        											
	      }
	        
	        
	        
	      
	      
	}
	 


// Run last, as it exits when completed
create_csv_file('temp', 'Crypto_Portfolio.csv', $csv_download_array); 


?>
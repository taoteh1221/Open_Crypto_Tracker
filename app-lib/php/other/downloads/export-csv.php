<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


// CSRF attack protection (REQUIRED #POST# VAR 'submit_check')
if ( $_POST['submit_check'] != 1 ) {
$ct_gen->log('security_error', 'Missing "submit_check" POST data (-possible- CSRF attack) for request: ' . $_SERVER['REQUEST_URI']);
$ct_cache->error_logs();
exit;
}


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
	    
	    
	foreach ( $ct_conf['assets'] as $asset_array_key => $asset_array_val ) {
		
	    
	    $field_var_pairing = strtolower($asset_array_key) . '_pairing';
	    $field_var_market = strtolower($asset_array_key) . '_market';
	    $field_var_amount = strtolower($asset_array_key) . '_amount';
	    $field_var_paid = strtolower($asset_array_key) . '_paid';
	    $field_var_leverage = strtolower($asset_array_key) . '_leverage';
	    $field_var_margintype = strtolower($asset_array_key) . '_margintype';
	    $field_var_watchonly = strtolower($asset_array_key) . '_watchonly';
	    $field_var_restore = strtolower($asset_array_key) . '_restore';
	    
	    
	    
	    $asset_pairing_id = $_POST[$field_var_pairing];
	    $asset_market_id = $_POST[$field_var_market];
	    $asset_amount_val = $_POST[$field_var_amount];
	    $asset_paid_val = $_POST[$field_var_paid];
	    $asset_leverage_val = $_POST[$field_var_leverage];
	    $asset_margintype_val = $_POST[$field_var_margintype];
	        	
	        
	    $sel_pairing = ( $asset_pairing_id ? $asset_pairing_id : NULL );
	    
	    
			foreach ( $ct_conf['assets'][strtoupper($asset_array_key)]['pairing'] as $pairing_key => $unused ) {
			$ploop = 0;
					 						
				// Use first pairing key from coins config for this asset, if no pairing value was set properly
				if ( $ploop == 0 ) {
				
					if ( $sel_pairing == NULL || !$ct_conf['assets'][strtoupper($asset_array_key)]['pairing'][$sel_pairing] ) {
					$sel_pairing = $pairing_key;
					}
				
				}
											
			$ploop = $ploop + 1;
			}
											
	    
	    	if ( strtoupper($asset_array_key) == 'MISCASSETS' ) {
	    	$asset_amount_dec = 2;
	    	}
	    	else {
	    	$asset_amount_dec = 8;
	    	}
	    
	  	 $asset_amount_val = $ct_var->num_pretty($asset_amount_val, $asset_amount_dec);
	    
	    $asset_paid_val = ( $ct_var->num_to_str($asset_paid_val) >= 1 ? $ct_var->num_pretty($asset_paid_val, 2) : $ct_var->num_pretty($asset_paid_val, $ct_conf['gen']['prim_currency_dec_max']) );
	  	 
	    
	   	// Asset data to array for CSV export
	      if ( trim($asset_array_key) != '' && $ct_var->rem_num_format($asset_amount_val) >= 0.00000001 ) {
	        	
	        $csv_download_array[] = array(
	        											strtoupper($asset_array_key),
	        											$asset_amount_val,
	        											$asset_paid_val,
	        											$asset_leverage_val,
	        											$asset_margintype_val,
	        											$asset_market_id,
	        											$sel_pairing
	        											);
	        											
	      }
	        
	        
	        
	      
	      
	}
	 


// Run last, as it exits when completed
$ct_gen->create_csv('temp', 'Crypto_Portfolio.csv', $csv_download_array); 


?>
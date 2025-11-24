<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// CSRF attack protection (REQUIRED #POST# VAR 'submit_check')
if ( $_POST['submit_check'] != 1 ) {
$ct['gen']->log('security_error', 'Missing "submit_check" POST data (-possible- CSRF attack) for request: ' . $_SERVER['REQUEST_URI']);
$ct['cache']->app_log();
exit;
}


$csv_download_array = array();


// CSV header
$csv_download_array[] = array(
	        					'Ticker Key',
	        					'Holdings',
	        					'Average Paid (per-unit)',
	        					'Margin Leverage',
	        					'Long or Short',
	        					'Exchange ID',
	        					'Market Pair'
	        				  );
	    
	    
	foreach ( $ct['conf']['assets'] as $asset_array_key => $asset_array_val ) {
		     
		     
		     if ( array_key_exists( strtolower($asset_array_key), $ct['conf']['assets']['BTC']['pair']) && !array_key_exists( strtolower($asset_array_key), $ct['opt_conf']['crypto_pair']) ) {
			$fiat_equiv = true;
			}
			else {
			$fiat_equiv = false;
			}

	    
	    $field_var_pair = strtolower($asset_array_key) . '_pair';
	    $field_var_mrkt = strtolower($asset_array_key) . '_mrkt';
	    $field_var_amnt = strtolower($asset_array_key) . '_amnt';
	    $field_var_paid = strtolower($asset_array_key) . '_paid';
	    $field_var_lvrg = strtolower($asset_array_key) . '_lvrg';
	    $field_var_mrgntyp = strtolower($asset_array_key) . '_mrgntyp';
	    $field_var_watchonly = strtolower($asset_array_key) . '_watchonly';
	    $field_var_restore = strtolower($asset_array_key) . '_restore';
	    
	    
	    
	    $asset_pair_id = $_POST[$field_var_pair];
	    $asset_mrkt_id = $_POST[$field_var_mrkt];
	    $asset_amnt_val = $_POST[$field_var_amnt];
	    $asset_paid_val = $_POST[$field_var_paid];
	    $asset_lvrg_val = $_POST[$field_var_lvrg];
	    $asset_mrgntyp_val = $_POST[$field_var_mrgntyp];
	        	
	        
	    $sel_pair = ( $asset_pair_id ? $asset_pair_id : NULL );
	    
	    
			foreach ( $ct['conf']['assets'][strtoupper($asset_array_key)]['pair'] as $pair_key => $unused ) {
			$ploop = 0;
					 						
				// Use first pair key from coins config for this asset, if no pair value was set properly
				if ( $ploop == 0 ) {
				
					if ( $sel_pair == NULL || !$ct['conf']['assets'][strtoupper($asset_array_key)]['pair'][$sel_pair] ) {
					$sel_pair = $pair_key;
					}
				
				}
											
			$ploop = $ploop + 1;
			}
											
	    
	    $asset_amnt_dec = ( $fiat_equiv ? $ct['conf']['currency']['currency_decimals_max'] : $ct['conf']['currency']['crypto_decimals_max'] );
	    
	    $asset_amnt_val = $ct['var']->num_pretty($asset_amnt_val, $asset_amnt_dec);
	    
	    $asset_paid_val = $ct['var']->num_pretty($asset_paid_val, $ct['conf']['currency']['currency_decimals_max']);
	  	 
	    
	   	// Asset data to array for CSV export
	      if ( isset($asset_array_key) && trim($asset_array_key) != '' && $ct['var']->rem_num_format($asset_amnt_val) >= $ct['min_crypto_val_test'] ) {
	        	
	        $csv_download_array[] = array(
	        											strtoupper($asset_array_key),
	        											$asset_amnt_val,
	        											$asset_paid_val,
	        											$asset_lvrg_val,
	        											$asset_mrgntyp_val,
	        											$asset_mrkt_id,
	        											$sel_pair
	        											);
	        											
	      }
	        
	        
	        
	      
	      
	}
	 


// Access stats logging / etc
$ct['cache']->log_access_stats();
$ct['cache']->api_throttle_cache();
$ct['cache']->registered_light_charts_cache();

// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

// Run last, as it exits when completed
$ct['gen']->create_csv('temp', 'Crypto_Portfolio.csv', $csv_download_array); 

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!


?>
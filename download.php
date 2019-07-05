<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

$runtime_mode = 'csv_export_download';

require("config.php");



////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////



// Example template download
if ( $_GET['example_template'] == 1 ) {

// CSV header
$example_download_array[] = array(
	        							'Asset Symbol',
	        							'Amount Held',
	        							'USD Purchase Price (per-token)',
	        							'Margin Leverage',
	        							'Long or Short',
	        							'Market ID (Exchange)',
	        							'Base Pairing'
	        							);
	        
// BTC
$example_download_array[] = array(
	        							'BTC',
	        							'0.00123',
	        							'11,500.25',
	        							'0',
	        							'long',
	        							'1',
	        							'btc'
	        							);		
	        
// LTC
$example_download_array[] = array(
	        							'LTC',
	        							'7.255',
	        							'120.50',
	        							'0',
	        							'long',
	        							'1',
	        							'btc'
	        							);			
	        
// GRIN
$example_download_array[] = array(
	        							'GRIN',
	        							'45.755',
	        							'2.25',
	        							'0',
	        							'long',
	        							'1',
	        							'btc'
	        							);	
	        
// USD
$example_download_array[] = array(
	        							'USD',
	        							'80.15',
	        							'',
	        							'0',
	        							'long',
	        							'1',
	        							'btc'
	        							);							
	        							


// Log errors, destroy session data
error_logs();
session_destroy();

// Run last, as it exits when completed
create_csv_file('temp', $example_download_array); 

}



////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////



// Exit at this point (if we haven't already), if no portfolio form data was submitted
if ( $_POST['submit_check'] != 1 ) {
exit;
}


// Portfolio export download
if ( is_array($coins_list) || is_object($coins_list) ) {
	
// CSV header
$csv_download_array[] = array(
	        							'Asset Symbol',
	        							'Amount Held',
	        							'USD Purchase Price (per-token)',
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
	    
	    
		// Pretty number formatting, while maintaining decimals
	   $raw_coin_amount_value = remove_number_format($coin_amount_value);
	    
	    	if ( preg_match("/\./", $raw_coin_amount_value) ) {
	    	$coin_amount_no_decimal = preg_replace("/\.(.*)/", "", $raw_coin_amount_value);
	    	$coin_amount_decimal = preg_replace("/(.*)\./", "", $raw_coin_amount_value);
	    	$check_coin_amount_decimal = '0.' . $coin_amount_decimal;
	    	}
	    	else {
	    	$coin_amount_no_decimal = $raw_coin_amount_value;
	    	$coin_amount_decimal = NULL;
	    	$check_coin_amount_decimal = NULL;
	    	}
	    
	    
	    	if ( floattostr($raw_coin_amount_value) > 0.00000000 ) {  // Show even if decimal is off the map, just for UX purposes tracking token price only
	    		
	    		if ( strtoupper($coin_array_key) == 'USD' ) {
	    		$coin_amount_value = number_format($raw_coin_amount_value, 2, '.', ',');
	    		}
	    		else {
	    		// Show even if decimal is off the map, just for UX purposes tracking token price only
				// $X_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    		$coin_amount_value = number_format($coin_amount_no_decimal, 0, '.', ',') . ( floattostr($check_coin_amount_decimal) > 0.00000000 ? '.' . $coin_amount_decimal : '' );
	    		}
	    	
	    	}
	    	else {
	    	$coin_amount_value = NULL;
	    	}
	    	
	    
	    
	   $raw_paid_value = remove_number_format($coin_paid_value);
	    
	    	if ( preg_match("/\./", $raw_paid_value) ) {
	    	$coin_paid_no_decimal = preg_replace("/\.(.*)/", "", $raw_paid_value);
	    	$coin_paid_decimal = preg_replace("/(.*)\./", "", $raw_paid_value);
	    	$check_coin_paid_decimal = '0.' . $coin_paid_decimal;
	    	}
	    	else {
	    	$coin_paid_no_decimal = $raw_paid_value;
	    	$coin_paid_decimal = NULL;
	    	$check_coin_paid_decimal = NULL;
	    	}
	    
	    
			// $X_no_decimal stops rounding, while number_format gives us pretty numbers left of decimal
	    	if ( floattostr($raw_paid_value) > 0.00000000 ) { 
	    	$coin_paid_value = number_format($coin_paid_no_decimal, 0, '.', ',') . ( floattostr($check_coin_paid_decimal) > 0.00000000 ? '.' . $coin_paid_decimal : '' );
	    	}
	    	else {
	    	$coin_paid_value = NULL;
	    	}
	    	
	    
	    
	   	// Asset data to array for CSV export
	      if ( floattostr($raw_coin_amount_value) >= 0.00000001 ) {
	        	
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
session_destroy();

// Run last, as it exits when completed
create_csv_file('temp', $csv_download_array); 

}
else {
// Log errors, destroy session data
error_logs();
session_destroy();
exit;
}




?>
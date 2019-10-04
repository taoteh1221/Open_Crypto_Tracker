<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// CSV header
$example_download_array[] = array(
	        							'Asset Symbol',
	        							'Amount Held',
	        							'USD Purchase Average (per-token)',
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
	        							'MISCUSD',
	        							'80.15',
	        							'',
	        							'0',
	        							'long',
	        							'1',
	        							'btc'
	        							);							
	        							


// Log errors, destroy session data
error_logs();
hardy_session_clearing();

// Run last, as it exits when completed
create_csv_file('temp', $example_download_array); 


?>
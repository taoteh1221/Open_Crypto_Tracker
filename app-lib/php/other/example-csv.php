<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// CSV header
$example_download_array[] = array(
	        							'Asset Symbol',
	        							'Holdings',
	        							'Purchase Average (per-token)',
	        							'Margin Leverage',
	        							'Long or Short',
	        							'Exchange ID',
	        							'Market Pairing'
	        							);
	        
// BTC
$example_download_array[] = array(
	        							'BTC',
	        							'0.00123',
	        							'11,500.25',
	        							'0',
	        							'long',
	        							'1',
	        							'usd'
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
	        
// MISCASSETS
$example_download_array[] = array(
	        							'MISCASSETS',
	        							'80.15',
	        							'',
	        							'0',
	        							'long',
	        							'1',
	        							'btc'
	        							);							
	        							


// Log errors / debugging, send notifications, destroy session data
error_logs();
debugging_logs();
send_notifications();
hardy_session_clearing();

// Run last, as it exits when completed
create_csv_file('temp', 'Crypto_Portfolio_Example.csv', $example_download_array); 


?>
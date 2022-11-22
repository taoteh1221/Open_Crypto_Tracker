<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// CSV header
$example_download_array[] = array(
	        							'Asset Symbol',
	        							'Holdings',
	        							'Average Paid (per-token)',
	        							'Margin Leverage',
	        							'Long or Short',
	        							'Exchange ID',
	        							'Market Pair'
	        							);
	        
// BTC
$example_download_array[] = array(
	        							'BTC',
	        							'0.00123',
	        							'11,500.25',
	        							'0',
	        							'long',
	        							'1',
	        							'usdt'
	        							);		
	        
// LTC
$example_download_array[] = array(
	        							'ETH',
	        							'7.255',
	        							'120.50',
	        							'0',
	        							'long',
	        							'1',
	        							'usdt'
	        							);			
	        
// GRIN
$example_download_array[] = array(
	        							'UNI',
	        							'67.843',
	        							'4.80',
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
	        							


// Run last, as it exits when completed
$ct_gen->create_csv('temp', 'Crypto_Portfolio_Example.csv', $example_download_array); 

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!


?>
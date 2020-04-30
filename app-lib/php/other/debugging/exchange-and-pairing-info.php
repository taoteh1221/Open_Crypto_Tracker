<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */




if ( $runtime_mode == 'ui' ) {

$exchange_count = 0;
$currency_count = 0;

	// Print out bitcoin markets configuration
	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'btc_markets_config' ) {
		
		
		foreach ( $app_config['power_user']['bitcoin_currency_markets'] as $key => $unused ) {
			
			if( !preg_match("/".$key." /i", $supported_primary_currency_list) ) {
			$currency_count = $currency_count + 1;
			$supported_primary_currency_list .= strtolower($key) . ' / ';
			}
			
		
		}
		
		$pairings_count = $currency_count;
		$all_supported_pairings_list = $supported_primary_currency_list;
		
		foreach ( $app_config['power_user']['crypto_to_crypto_pairing'] as $key => $unused ) {
			
			if( !preg_match("/".$key." /i", $all_supported_pairings_list) ) {
			$pairings_count = $pairings_count + 1;
			$all_supported_pairings_list .= strtolower($key) . ' / ';
			}
			
		
		}
		
		
		// Alphabetical sorting
		$supported_primary_currency_list = list_sort($supported_primary_currency_list, '/', 'asort', true);
		$all_supported_pairings_list = list_sort($all_supported_pairings_list, '/', 'asort', true);
		
		
		foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'] as $pairing_key => $unused ) {
			
				foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$pairing_key] as $exchange_key => $unused ) {
					
					if( !preg_match("/".$exchange_key." /i", $supported_btc_exchange_list) ) {
					$exchange_count = $exchange_count + 1;
					$supported_btc_exchange_list .= strtolower($exchange_key) . ' / ';
					}
			
				
				}
				
		}
		
		$all_exchange_count = $exchange_count;
		$all_exchanges_list = $supported_btc_exchange_list;
		
		foreach ( $app_config['portfolio_assets'] as $asset_key => $unused ) {
			
			if ( $asset_key != 'BTC' ) {
			
				foreach ( $app_config['portfolio_assets'][$asset_key]['market_pairing'] as $pairing_key => $unused ) {
					
					foreach ( $app_config['portfolio_assets'][$asset_key]['market_pairing'][$pairing_key] as $exchange_key => $unused ) {
					
						if( !preg_match("/".$exchange_key." /i", $all_exchanges_list) && !preg_match("/misc_assets/i", $exchange_key) ) {
						$all_exchange_count = $all_exchange_count + 1;
						$all_exchanges_list .= strtolower($exchange_key) . ' / ';
						}
			
					
					}
				
				}
				
			}
				
		}
		
		
		// Alphabetical sorting
		$supported_btc_exchange_list = list_sort($supported_btc_exchange_list, '/', 'asort', true);
		$all_exchanges_list = list_sort($all_exchanges_list, '/', 'asort', true);
	
	
	app_logging('config_debugging', "\n\n" . 'Bitcoin markets configuration information (for config.php documentation) supported_btc_primary_currencies_list['.$currency_count.']: ' . $supported_primary_currency_list . '; ' . "\n\n" . 'supported_btc_exchanges_list['.$exchange_count.']: ' . $supported_btc_exchange_list . ';' . "\n\n" );
	
	
	
	app_logging('config_debugging', "\n\n" . 'ALL markets configuration information (for README.txt documentation) supported_all_pairings_list['.$pairings_count.']: ' . strtoupper($all_supported_pairings_list) . '; ' . "\n\n" . 'supported_all_exchanges_list['.$all_exchange_count.']: ' . strtolower($all_exchanges_list) . ';' . "\n\n" );
	
	
	}



}



?>
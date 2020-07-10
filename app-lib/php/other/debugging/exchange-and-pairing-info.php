<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */




if ( $runtime_mode == 'ui' ) {

$exchange_count = 0;
$currency_count = 0;

	// Print out all market configurations
	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_markets_config' ) {
		
		
		foreach ( $app_config['power_user']['bitcoin_currency_markets'] as $key => $unused ) {
			
			// Detects better with right side space included
			if ( stristr($supported_primary_currency_list, $key . ' ') == false ) {
			$currency_count = $currency_count + 1;
			$supported_primary_currency_list .= $key . ' / ';
			}
			
		
		}
		
		$pairings_count = $currency_count;
		$all_supported_pairings_list = $supported_primary_currency_list;
		
		foreach ( $app_config['power_user']['crypto_pairing'] as $key => $unused ) {
			
			// Detects better with right side space included
			if ( stristr($all_supported_pairings_list, $key . ' ') == false ) {
			$pairings_count = $pairings_count + 1;
			$all_supported_pairings_list .= $key . ' / ';
			}
			
		
		}
		
		
		// Alphabetical sorting
		$supported_primary_currency_list = list_sort($supported_primary_currency_list, '/', 'sort', true);
		$all_supported_pairings_list = list_sort($all_supported_pairings_list, '/', 'sort', true);
		
		
		foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'] as $pairing_key => $unused ) {
			
				foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$pairing_key] as $exchange_key => $unused ) {
					
					// Detects better with right side space included
					if ( stristr($supported_btc_exchange_list, $exchange_key . ' ') == false && stristr($exchange_key, 'bitmex_') == false ) { // Futures markets not allowed
					$exchange_count = $exchange_count + 1;
					$supported_btc_exchange_list .= $exchange_key . ' / ';
					}
			
				
				}
				
		}
		
		$all_exchange_count = $exchange_count;
		$all_exchanges_list = $supported_btc_exchange_list;
		
		foreach ( $app_config['portfolio_assets'] as $asset_key => $unused ) {
			
				foreach ( $app_config['portfolio_assets'][$asset_key]['market_pairing'] as $pairing_key => $unused ) {
					
					foreach ( $app_config['portfolio_assets'][$asset_key]['market_pairing'][$pairing_key] as $exchange_key => $unused ) {
					
						// Detects better with right side space included
						if ( stristr($all_exchanges_list, $exchange_key . ' ') == false && $exchange_key != 'misc_assets' ) {
						$all_exchange_count = $all_exchange_count + 1;
						$all_exchanges_list .= $exchange_key . ' / ';
						}
			
					}
				
				}
				
		}
		
		
		// Alphabetical sorting
		$supported_btc_exchange_list = list_sort($supported_btc_exchange_list, '/', 'sort', true);
		$all_exchanges_list = list_sort($all_exchanges_list, '/', 'sort', true);
	
	
	app_logging('config_debugging', "\n\n" . 'Bitcoin markets configuration information (for config.php documentation) supported_btc_primary_currencies_list['.$currency_count.']: ' . $supported_primary_currency_list . '; ' . "\n\n" . 'supported_btc_exchanges_list['.$exchange_count.']: ' . $supported_btc_exchange_list . "\n\n" );
	
	
	
	app_logging('config_debugging', "\n\n" . 'ALL markets configuration information (for README.txt documentation) supported_all_pairings_list['.$pairings_count.']: ' . strtoupper($all_supported_pairings_list) . '; ' . "\n\n" . 'supported_all_exchanges_list['.$all_exchange_count.']: ' . strtolower($all_exchanges_list) . "\n\n" );
	
	
	}



}



?>
<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



// RUN DURING 'ui' ONLY
if ( $runtime_mode == 'ui' ) {

$exchange_count = 0;
$curr_count = 0;

	// Print out all market configurations
	if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'markets_conf' ) {
		
		
		foreach ( $ocpt_conf['power']['btc_curr_markets'] as $key => $unused ) {
			
			// Detects better with right side space included
			if ( stristr($supported_prim_curr_list, $key . ' ') == false ) {
			$curr_count = $curr_count + 1;
			$supported_prim_curr_list .= $key . ' / ';
			}
			
		
		}
		
		$pairings_count = $curr_count;
		$all_supported_pairings_list = $supported_prim_curr_list;
		
		foreach ( $ocpt_conf['power']['crypto_pairing'] as $key => $unused ) {
			
			// Detects better with right side space included
			if ( stristr($all_supported_pairings_list, $key . ' ') == false ) {
			$pairings_count = $pairings_count + 1;
			$all_supported_pairings_list .= $key . ' / ';
			}
			
		
		}
		
		
		// Alphabetical sorting
		$supported_prim_curr_list = $ocpt_var->list_sort($supported_prim_curr_list, '/', 'sort', true);
		$all_supported_pairings_list = $ocpt_var->list_sort($all_supported_pairings_list, '/', 'sort', true);
		
		
		foreach ( $ocpt_conf['assets']['BTC']['pairing'] as $pairing_key => $unused ) {
			
				foreach ( $ocpt_conf['assets']['BTC']['pairing'][$pairing_key] as $exchange_key => $unused ) {
					
					// Detects better with right side space included
					if ( stristr($supported_btc_exchange_list, $exchange_key . ' ') == false && stristr($exchange_key, 'bitmex_') == false ) { // Futures markets not allowed
					$exchange_count = $exchange_count + 1;
					$supported_btc_exchange_list .= $exchange_key . ' / ';
					}
			
				
				}
				
		}
		
		$all_exchange_count = $exchange_count;
		$all_exchanges_list = $supported_btc_exchange_list;
		
		foreach ( $ocpt_conf['assets'] as $asset_key => $unused ) {
			
				foreach ( $ocpt_conf['assets'][$asset_key]['pairing'] as $pairing_key => $unused ) {
					
					foreach ( $ocpt_conf['assets'][$asset_key]['pairing'][$pairing_key] as $exchange_key => $unused ) {
					
						// Detects better with right side space included
						if ( stristr($all_exchanges_list, $exchange_key . ' ') == false && $exchange_key != 'misc_assets' ) {
						$all_exchange_count = $all_exchange_count + 1;
						$all_exchanges_list .= $exchange_key . ' / ';
						}
			
					}
				
				}
				
		}
		
		
		// Alphabetical sorting
		$supported_btc_exchange_list = $ocpt_var->list_sort($supported_btc_exchange_list, '/', 'sort', true);
		$all_exchanges_list = $ocpt_var->list_sort($all_exchanges_list, '/', 'sort', true);
	
	
	app_logging('config_debugging', "\n\n" . 'Bitcoin markets configuration information (for Admin Config current documentation) supported_btc_prim_currencies_list['.$curr_count.']: ' . $supported_prim_curr_list . '; ' . "\n\n" . 'supported_btc_exchanges_list['.$exchange_count.']: ' . $supported_btc_exchange_list . "\n\n" );
	
	
	
	app_logging('config_debugging', "\n\n" . 'ALL markets configuration information (for README.txt documentation) supported_all_pairings_list['.$pairings_count.']: ' . strtoupper($all_supported_pairings_list) . '; ' . "\n\n" . 'supported_all_exchanges_list['.$all_exchange_count.']: ' . strtolower($all_exchanges_list) . "\n\n" );
	
	
	}



}



?>
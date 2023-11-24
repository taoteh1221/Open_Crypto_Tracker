<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



// UNIT TESTS
// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (main web page loading)
if ( $ct['runtime_mode'] == 'ui' ) {


	// Check configured charts and price alerts
	if ( $ct['conf']['power']['debug_mode'] == 'all' || $ct['conf']['power']['debug_mode'] == 'alerts_charts' ) {
		
		foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $val ) {
		
		$check_asset_params = array_map( "trim", explode("||", $val) );
				
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$check_asset = ( stristr($check_asset_params[0], "-") == false ? $check_asset_params[0] : substr( $check_asset_params[0], 0, mb_strpos($check_asset_params[0], "-", 0, 'utf-8') ) );
		$check_asset = strtoupper($check_asset);
		
		$check_market_id = $ct['conf']['assets'][$check_asset]['pair'][ $check_asset_params[2] ][ $check_asset_params[1] ];
		
		// Consolidate function calls for runtime speed improvement
		$charts_test_data = $ct['api']->market($check_asset, $check_asset_params[1], $check_market_id, $check_asset_params[2]);
		
		
			if ( isset($charts_test_data['last_trade']) && $ct['var']->num_to_str($charts_test_data['last_trade']) >= $min_crypto_val_test ) {
			// DO NOTHING (IS SET / AT LEAST $min_crypto_val_test IN VALUE)
			}
			// TEST FAILURE
			else {
				
			$ct['gen']->log(
						'market_debug',
						'No chart / alert price data available: (conf_item='.$check_asset_params[0].',last_trade='.$ct['var']->num_to_str($charts_test_data['last_trade']).')',
						'market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[2]) . ' @ ' . ucfirst($check_asset_params[1])
						);
			
			}
			
			
			if ( isset($charts_test_data['24hr_prim_currency_vol']) && $ct['var']->num_to_str($charts_test_data['24hr_prim_currency_vol']) >= 1 ) {
			// DO NOTHING (IS SET / AT LEAST 1 IN VALUE)
			}
			// TEST FAILURE
			else {
				
			$ct['gen']->log(
						'market_debug',
						'No chart / alert trade volume data available: (conf_item='.$check_asset_params[0].',trade_volume='.$ct['var']->num_to_str($charts_test_data['24hr_prim_currency_vol']).')',
						'market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[2]) . ' @ ' . ucfirst($check_asset_params[1])
						);
			
			}
				
		
		}
	
	}
	
	
	
	
	// Check configured email to mobile text gateways
	if ( $ct['conf']['power']['debug_mode'] == 'all' || $ct['conf']['power']['debug_mode'] == 'texts' ) {
	
		foreach ( $ct['conf']['mobile_network_text_gateways'] as $key => $val ) {
			
			if ( $key != 'skip_network_name' ) {
			
			$test_result = $ct['gen']->valid_email( 'test@' . trim($val) );
		
				if ( $test_result != 'valid' ) {
					
				$ct['gen']->log(
							'other_debug',
							'email-to-mobile-text gateway '.trim($val).' does not appear valid',
							'key: ' . $key . '; gateway: ' . trim($val) . '; result: ' . $test_result
							);
				
				}
			
			}
		
		}
	
	}
	
	
	
	
	// Check configured coin markets
	if ( $ct['conf']['power']['debug_mode'] == 'all' || $ct['conf']['power']['debug_mode'] == 'markets' ) {
		
		foreach ( $ct['conf']['assets'] as $asset_key => $asset_val ) {
		
		
			foreach ( $asset_val['pair'] as $pair_key => $pair_val ) {
			
			
				foreach ( $pair_val as $key => $val ) {
				
					if ( $key != 'misc_assets' && $key != 'btc_nfts' && $key != 'eth_nfts' && $key != 'sol_nfts' && $key != 'alt_nfts' ) {
					
					// Consolidate function calls for runtime speed improvement
					$mrkts_test_data = $ct['api']->market( strtoupper($asset_key) , $key, $val, $pair_key);
				
				
     				     if ( isset($mrkts_test_data['last_trade']) && $ct['var']->num_to_str($mrkts_test_data['last_trade']) >= $min_crypto_val_test ) {
                 			// DO NOTHING (IS SET / AT LEAST $min_crypto_val_test IN VALUE)
                 			}
                 			// TEST FAILURE
                 			else {
     							
     						$ct['gen']->log(
     									'market_debug',
     									'No market price data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pair_key) . ' @ ' . $ct['gen']->key_to_name($key)
     									);
     						
     					}
     					
     					
     					if ( isset($mrkts_test_data['24hr_prim_currency_vol']) && $ct['var']->num_to_str($mrkts_test_data['24hr_prim_currency_vol']) >= 1 ) {
                 			// DO NOTHING (IS SET / AT LEAST 1 IN VALUE)
                 			}
                 			// TEST FAILURE
                 			else {
     							
     						$ct['gen']->log(
     									'market_debug',
     									'No market volume data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pair_key) . ' @ ' . $ct['gen']->key_to_name($key)
     									);
     						
     					}
						
					
					}
				
				}
				
			
			}
			
		
		}
	
	}
		



}

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
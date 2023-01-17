<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



// UNIT TESTS
// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (main web page loading)
if ( $runtime_mode == 'ui' ) {


	// Check configured charts and price alerts
	if ( $ct_conf['dev']['debug_mode'] == 'all' || $ct_conf['dev']['debug_mode'] == 'alerts_charts' ) {
		
		foreach ( $ct_conf['charts_alerts']['tracked_mrkts'] as $key => $val ) {
				
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$check_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$check_asset = strtoupper($check_asset);
		
		$check_asset_params = explode("||", $val);
		
		$check_market_id = $ct_conf['assets'][$check_asset]['pair'][ $check_asset_params[1] ][ $check_asset_params[0] ];
		
		// Consolidate function calls for runtime speed improvement
		$charts_test_data = $ct_api->market($check_asset, $check_asset_params[0], $check_market_id, $check_asset_params[1]);
		
		
			if ( isset($charts_test_data['last_trade']) && $ct_var->num_to_str($charts_test_data['last_trade']) >= $min_crypto_val_test ) {
			// DO NOTHING (IS SET / AT LEAST $min_crypto_val_test IN VALUE)
			}
			// TEST FAILURE
			else {
				
			$ct_gen->log(
						'market_debug',
						'No chart / alert price data available: (conf_key='.$key.',last_trade='.$ct_var->num_to_str($charts_test_data['last_trade']).')',
						'market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0])
						);
			
			}
			
			
			if ( isset($charts_test_data['24hr_prim_currency_vol']) && $ct_var->num_to_str($charts_test_data['24hr_prim_currency_vol']) >= 1 ) {
			// DO NOTHING (IS SET / AT LEAST 1 IN VALUE)
			}
			// TEST FAILURE
			else {
				
			$ct_gen->log(
						'market_debug',
						'No chart / alert trade volume data available: (conf_key='.$key.',trade_volume='.$ct_var->num_to_str($charts_test_data['24hr_prim_currency_vol']).')',
						'market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0])
						);
			
			}
				
		
		}
	
	}
	
	
	
	
	// Check configured email to mobile text gateways
	if ( $ct_conf['dev']['debug_mode'] == 'all' || $ct_conf['dev']['debug_mode'] == 'texts' ) {
	
		foreach ( $ct_conf['mob_net_txt_gateways'] as $key => $val ) {
			
			if ( $key != 'skip_network_name' ) {
			
			$test_result = $ct_gen->valid_email( 'test@' . trim($val) );
		
				if ( $test_result != 'valid' ) {
					
				$ct_gen->log(
							'other_debug',
							'email-to-mobile-text gateway '.trim($val).' does not appear valid',
							'key: ' . $key . '; gateway: ' . trim($val) . '; result: ' . $test_result
							);
				
				}
			
			}
		
		}
	
	}
	
	
	
	
	// Check configured coin markets
	if ( $ct_conf['dev']['debug_mode'] == 'all' || $ct_conf['dev']['debug_mode'] == 'markets' ) {
		
		foreach ( $ct_conf['assets'] as $asset_key => $asset_val ) {
		
		
			foreach ( $asset_val['pair'] as $pair_key => $pair_val ) {
			
			
				foreach ( $pair_val as $key => $val ) {
				
					if ( $key != 'misc_assets' && $key != 'eth_nfts' && $key != 'sol_nfts' ) {
					
					// Consolidate function calls for runtime speed improvement
					$mrkts_test_data = $ct_api->market( strtoupper($asset_key) , $key, $val, $pair_key);
				
				
     				     if ( isset($mrkts_test_data['last_trade']) && $ct_var->num_to_str($mrkts_test_data['last_trade']) >= $min_crypto_val_test ) {
                 			// DO NOTHING (IS SET / AT LEAST $min_crypto_val_test IN VALUE)
                 			}
                 			// TEST FAILURE
                 			else {
     							
     						$ct_gen->log(
     									'market_debug',
     									'No market price data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pair_key) . ' @ ' . $ct_gen->key_to_name($key)
     									);
     						
     					}
     					
     					
     					if ( isset($mrkts_test_data['24hr_prim_currency_vol']) && $ct_var->num_to_str($mrkts_test_data['24hr_prim_currency_vol']) >= 1 ) {
                 			// DO NOTHING (IS SET / AT LEAST 1 IN VALUE)
                 			}
                 			// TEST FAILURE
                 			else {
     							
     						$ct_gen->log(
     									'market_debug',
     									'No market volume data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pair_key) . ' @ ' . $ct_gen->key_to_name($key)
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
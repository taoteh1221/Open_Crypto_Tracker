<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



// UNIT TESTS


// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (web page loading)
// RUN DURING 'ui' ONLY
if ( $runtime_mode == 'ui' ) {




	// Check configured charts and price alerts
	if ( $oct_conf['dev']['debug'] == 'all' || $oct_conf['dev']['debug'] == 'alerts_charts' ) {
		
		foreach ( $oct_conf['charts_alerts']['tracked_markets'] as $key => $val ) {
				
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$check_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$check_asset = strtoupper($check_asset);
		
		$check_asset_params = explode("||", $val);
		
		$check_pairing_name = $oct_conf['assets'][$check_asset]['pairing'][ $check_asset_params[1] ][ $check_asset_params[0] ];
		
		// Consolidate function calls for runtime speed improvement
		$charts_test_data = $oct_api->market($check_asset, $check_asset_params[0], $check_pairing_name, $check_asset_params[1]);
		
			if ( $charts_test_data['last_trade'] == NULL ) {
				
			$oct_gen->log(
										'market_error',
										'No chart / alert price data available',
										'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0])
										);
			
			}
			
			if ( $charts_test_data['24hr_prim_currency_vol'] == NULL || $charts_test_data['24hr_prim_currency_vol'] < 1 ) {
				
			$oct_gen->log(
										'market_error',
										'No chart / alert volume data available',
										'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0])
										);
			
			}
				
		
		}
	
	}
	
	
	
	
	// Check configured email to mobile text gateways
	if ( $oct_conf['dev']['debug'] == 'all' || $oct_conf['dev']['debug'] == 'texts' ) {
	
		foreach ( $oct_conf['mob_net_txt_gateways'] as $key => $val ) {
			
			if ( $key != 'skip_network_name' ) {
			
			$test_result = $oct_gen->valid_email( 'test@' . trim($val) );
		
				if ( $test_result != 'valid' ) {
					
				$oct_gen->log(
											'other_error',
											'email-to-mobile-text gateway '.trim($val).' does not appear valid',
											'key: ' . $key . '; gateway: ' . trim($val) . '; result: ' . $test_result
											);
				
				}
			
			}
		
		}
	
	}
	
	
	
	
	// Check configured coin markets
	if ( $oct_conf['dev']['debug'] == 'all' || $oct_conf['dev']['debug'] == 'markets' ) {
		
		foreach ( $oct_conf['assets'] as $asset_key => $asset_val ) {
		
		
			foreach ( $asset_val['pairing'] as $pairing_key => $pairing_val ) {
			
			
				foreach ( $pairing_val as $key => $val ) {
				
					if ( $key != 'misc_assets' ) {
					
					// Consolidate function calls for runtime speed improvement
					$markets_test_data = $oct_api->market( strtoupper($asset_key) , $key, $val, $pairing_key);
				
						if ( $markets_test_data['last_trade'] == NULL ) {
							
						$oct_gen->log(
													'market_error',
													'No market price data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . $oct_gen->key_to_name($key)
													);
						
						}
					
						if ( $markets_test_data['24hr_prim_currency_vol'] == NULL || $markets_test_data['24hr_prim_currency_vol'] < 1 ) {
							
						$oct_gen->log(
													'market_error',
													'No market volume data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . $oct_gen->key_to_name($key)
													);
						
						}
					
					}
				
				}
				
			
			}
			
		
		}
	
	}
		



}

?>
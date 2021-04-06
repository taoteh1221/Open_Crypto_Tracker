<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



// UNIT TESTS


// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (web page loading)
// RUN DURING 'ui' ONLY
if ( $runtime_mode == 'ui' ) {




	// Check configured charts and price alerts
	if ( $pt_conf['dev']['debug'] == 'all' || $pt_conf['dev']['debug'] == 'alerts_charts' ) {
		
		foreach ( $pt_conf['charts_alerts']['tracked_markets'] as $key => $val ) {
				
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$check_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$check_asset = strtoupper($check_asset);
		
		$check_asset_params = explode("||", $val);
		
		$check_pairing_name = $pt_conf['assets'][$check_asset]['pairing'][$check_asset_params[1]][$check_asset_params[0]];
		
		// Consolidate function calls for runtime speed improvement
		$charts_test_data = $pt_api->market($check_asset, $check_asset_params[0], $check_pairing_name, $check_asset_params[1]);
		
			if ( $charts_test_data['last_trade'] == NULL ) {
			$pt_gen->app_logging('market_error', 'No chart / alert price data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
			}
			
			if ( $charts_test_data['24hr_prim_curr_vol'] == NULL || $charts_test_data['24hr_prim_curr_vol'] < 1 ) {
			$pt_gen->app_logging('market_error', 'No chart / alert volume data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
			}
				
		
		}
	
	}
	
	
	
	
	// Check configured email to mobile text gateways
	if ( $pt_conf['dev']['debug'] == 'all' || $pt_conf['dev']['debug'] == 'texts' ) {
	
		foreach ( $pt_conf['mob_net_txt_gateways'] as $key => $val ) {
			
			if ( $key != 'skip_network_name' ) {
			
			$test_result = $pt_gen->valid_email( 'test@' . trim($val) );
		
				if ( $test_result != 'valid' ) {
				$pt_gen->app_logging( 'other_error', 'email-to-mobile-text gateway '.trim($val).' does not appear valid', 'key: ' . $key . '; gateway: ' . trim($val) . '; result: ' . $test_result );
				}
			
			}
		
		}
	
	}
	
	
	
	
	// Check configured coin markets
	if ( $pt_conf['dev']['debug'] == 'all' || $pt_conf['dev']['debug'] == 'markets' ) {
		
		foreach ( $pt_conf['assets'] as $asset_key => $asset_val ) {
		
		
			foreach ( $asset_val['pairing'] as $pairing_key => $pairing_val ) {
			
			
				foreach ( $pairing_val as $key => $val ) {
				
					if ( $key != 'misc_assets' ) {
					
					// Consolidate function calls for runtime speed improvement
					$markets_test_data = $pt_api->market( strtoupper($asset_key) , $key, $val, $pairing_key);
				
						if ( $markets_test_data['last_trade'] == NULL ) {
						$pt_gen->app_logging('market_error', 'No market price data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . $pt_gen->snake_case_to_name($key) );
						}
					
						if ( $markets_test_data['24hr_prim_curr_vol'] == NULL || $markets_test_data['24hr_prim_curr_vol'] < 1 ) {
						$pt_gen->app_logging('market_error', 'No market volume data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . $pt_gen->snake_case_to_name($key) );
						}
					
					}
				
				}
				
			
			}
			
		
		}
	
	}
		



}

?>
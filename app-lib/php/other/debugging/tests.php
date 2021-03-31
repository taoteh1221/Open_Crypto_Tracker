<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



// UNIT TESTS


// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (web page loading)
// RUN DURING 'ui' ONLY
if ( $runtime_mode == 'ui' ) {




	// Check configured charts and price alerts
	if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'alerts_charts' ) {
		
		foreach ( $ocpt_conf['charts_alerts']['tracked_markets'] as $key => $value ) {
				
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$check_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$check_asset = strtoupper($check_asset);
		
		$check_asset_params = explode("||", $value);
		
		$check_pairing_name = $ocpt_conf['assets'][$check_asset]['pairing'][$check_asset_params[1]][$check_asset_params[0]];
		
		// Consolidate function calls for runtime speed improvement
		$charts_test_data = $ocpt_api->market($check_asset, $check_asset_params[0], $check_pairing_name, $check_asset_params[1]);
		
			if ( $charts_test_data['last_trade'] == NULL ) {
			app_logging('market_error', 'No chart / alert price data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
			}
			
			if ( $charts_test_data['24hr_prim_curr_vol'] == NULL || $charts_test_data['24hr_prim_curr_vol'] < 1 ) {
			app_logging('market_error', 'No chart / alert volume data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
			}
				
		
		}
	
	}
	
	
	
	
	// Check configured email to mobile text gateways
	if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'texts' ) {
	
		foreach ( $ocpt_conf['mob_net_txt_gateways'] as $key => $value ) {
			
			if ( $key != 'skip_network_name' ) {
			
			$test_result = validate_email( 'test@' . trim($value) );
		
				if ( $test_result != 'valid' ) {
				app_logging( 'other_error', 'email-to-mobile-text gateway '.trim($value).' does not appear valid', 'key: ' . $key . '; gateway: ' . trim($value) . '; result: ' . $test_result );
				}
			
			}
		
		}
	
	}
	
	
	
	
	// Check configured coin markets
	if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'markets' ) {
		
		foreach ( $ocpt_conf['assets'] as $coin_key => $coin_val ) {
		
		
			foreach ( $coin_val['pairing'] as $pairing_key => $pairing_val ) {
			
			
				foreach ( $pairing_val as $key => $value ) {
				
					if ( $key != 'misc_assets' ) {
					
					// Consolidate function calls for runtime speed improvement
					$markets_test_data = $ocpt_api->market( strtoupper($coin_key) , $key, $value, $pairing_key);
				
						if ( $markets_test_data['last_trade'] == NULL ) {
						app_logging('market_error', 'No market price data available for ' . strtoupper($coin_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . $ocpt_gen->snake_case_to_name($key) );
						}
					
						if ( $markets_test_data['24hr_prim_curr_vol'] == NULL || $markets_test_data['24hr_prim_curr_vol'] < 1 ) {
						app_logging('market_error', 'No market volume data available for ' . strtoupper($coin_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . $ocpt_gen->snake_case_to_name($key) );
						}
					
					}
				
				}
				
			
			}
			
		
		}
	
	}
		



}

?>
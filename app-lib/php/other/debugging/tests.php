<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



// UNIT TESTS


// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (web page loading)
// RUN DURING 'ui' ONLY
if ( $runtime_mode == 'ui' ) {




	// Check configured charts and price alerts
	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'alerts_charts' ) {
		
		foreach ( $app_config['charts_alerts']['tracked_markets'] as $key => $value ) {
				
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$check_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$check_asset = strtoupper($check_asset);
		
		$check_asset_params = explode("||", $value);
		
		$check_pairing_name = $app_config['portfolio_assets'][$check_asset]['market_pairing'][$check_asset_params[1]][$check_asset_params[0]];
		
		// Consolidate function calls for runtime speed improvement
		$charts_test_data = $pt_apis->market($check_asset, $check_asset_params[0], $check_pairing_name, $check_asset_params[1]);
		
			if ( $charts_test_data['last_trade'] == NULL ) {
			app_logging('market_error', 'No chart / alert price data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
			}
			
			if ( $charts_test_data['24hr_primary_currency_volume'] == NULL || $charts_test_data['24hr_primary_currency_volume'] < 1 ) {
			app_logging('market_error', 'No chart / alert volume data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
			}
				
		
		}
	
	}
	
	
	
	
	// Check configured email to mobile text gateways
	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'texts' ) {
	
		foreach ( $app_config['mobile_network_text_gateways'] as $key => $value ) {
			
			if ( $key != 'skip_network_name' ) {
			
			$test_result = validate_email( 'test@' . trim($value) );
		
				if ( $test_result != 'valid' ) {
				app_logging( 'other_error', 'email-to-mobile-text gateway '.trim($value).' does not appear valid', 'key: ' . $key . '; gateway: ' . trim($value) . '; result: ' . $test_result );
				}
			
			}
		
		}
	
	}
	
	
	
	
	// Check configured coin markets
	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'markets' ) {
		
		foreach ( $app_config['portfolio_assets'] as $coin_key => $coin_value ) {
		
		
			foreach ( $coin_value['market_pairing'] as $pairing_key => $pairing_value ) {
			
			
				foreach ( $pairing_value as $key => $value ) {
				
					if ( $key != 'misc_assets' ) {
					
					// Consolidate function calls for runtime speed improvement
					$markets_test_data = $pt_apis->market( strtoupper($coin_key) , $key, $value, $pairing_key);
				
						if ( $markets_test_data['last_trade'] == NULL ) {
						app_logging('market_error', 'No market price data available for ' . strtoupper($coin_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . snake_case_to_name($key) );
						}
					
						if ( $markets_test_data['24hr_primary_currency_volume'] == NULL || $markets_test_data['24hr_primary_currency_volume'] < 1 ) {
						app_logging('market_error', 'No market volume data available for ' . strtoupper($coin_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . snake_case_to_name($key) );
						}
					
					}
				
				}
				
			
			}
			
		
		}
	
	}
		



}

?>
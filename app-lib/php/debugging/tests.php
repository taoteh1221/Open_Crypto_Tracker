<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// UNIT TESTS


// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (web page loading)
if ( $runtime_mode == 'ui' ) {




	// Check configured charts and price alerts
	if ( $debug_mode == 'all' || $debug_mode == 'charts' ) {
		
		foreach ( $asset_charts_and_alerts as $key => $value ) {
				
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$check_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$check_asset = strtoupper($check_asset);
		
		$check_asset_params = explode("||", $value);
		
		$check_pairing_name = $coins_list[$check_asset]['market_pairing'][$check_asset_params[1]][$check_asset_params[0]];
		
		// Consolidate function calls for runtime speed improvement
		$charts_test_data = asset_market_data($check_asset, $check_asset_params[0], $check_pairing_name, $check_asset_params[1]);
		
			if ( $charts_test_data['last_trade'] == NULL ) {
			app_logging( 'other_error', 'No chart / alert price data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
			}
			
			if ( $charts_test_data['24hr_primary_currency_volume'] == NULL || $charts_test_data['24hr_primary_currency_volume'] < 1 ) {
			app_logging( 'other_error', 'No chart / alert volume data available', 'chart_key: ' . $key . '; market: ' . $check_asset . ' / ' . strtoupper($check_asset_params[1]) . ' @ ' . ucfirst($check_asset_params[0]) );
			}
				
		
		}
	
	}
	
	
	
	
	// Check configured email to mobile text gateways
	if ( $debug_mode == 'all' || $debug_mode == 'texts' ) {
	
		foreach ( $mobile_networks as $key => $value ) {
			
			if ( $key != 'skip_network_name' ) {
			
			$test_result = validate_email( 'test@' . trim($value) );
		
				if ( $test_result != 'valid' ) {
				app_logging( 'other_error', 'email-to-mobile-text gateway '.trim($value).' does not appear valid', 'key: ' . $key . '; gateway: ' . trim($value) . '; result: ' . $test_result );
				}
			
			}
		
		}
	
	}
	
	
	
	
	// Check configured coin markets
	if ( $debug_mode == 'all' || $debug_mode == 'markets' ) {
		
		foreach ( $coins_list as $coin_key => $coin_value ) {
		
		
			foreach ( $coin_value['market_pairing'] as $pairing_key => $pairing_value ) {
			
			
				foreach ( $pairing_value as $key => $value ) {
				
					if ( $key != 'misc_assets' ) {
					
					// Consolidate function calls for runtime speed improvement
					$markets_test_data = asset_market_data( strtoupper($coin_key) , $key, $value, $pairing_key);
				
						if ( $markets_test_data['last_trade'] == NULL ) {
						app_logging( 'other_error', 'No market price data available', strtoupper($coin_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . name_rendering($key) );
						}
					
						if ( $markets_test_data['24hr_primary_currency_volume'] == NULL || $markets_test_data['24hr_primary_currency_volume'] < 1 ) {
						app_logging( 'other_error', 'No market volume data available', strtoupper($coin_key) . ' / ' . strtoupper($pairing_key) . ' @ ' . name_rendering($key) );
						}
					
					}
				
				}
				
			
			}
			
		
		}
	
	}
		



}

?>
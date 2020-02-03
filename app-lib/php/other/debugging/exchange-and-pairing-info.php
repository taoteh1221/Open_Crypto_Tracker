<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */




if ( $runtime_mode == 'ui' ) {



	// Print out bitcoin markets configuration
	if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'btc_markets_config' ) {
		
		
		foreach ( $app_config['bitcoin_market_currencies'] as $key => $unused ) {
		$supported_primary_currency_list .= strtolower($key) . ' / ';
		}
		$supported_primary_currency_list = "'" . implode("' / '",array_unique(explode(' / ', $supported_primary_currency_list)));
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		$supported_primary_currency_list = rtrim($supported_primary_currency_list,"'");
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		$supported_primary_currency_list = rtrim($supported_primary_currency_list,'/');
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		
		foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'] as $pairing_key => $unused ) {
			
				foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$pairing_key] as $key => $unused ) {
				$supported_exchange_list .= strtolower($key) . ' / ';
				}
				
		}
		
		$supported_exchange_list = "'" . implode("' / '",array_unique(explode(' / ', $supported_exchange_list)));
		$supported_exchange_list = trim($supported_exchange_list);
		$supported_exchange_list = rtrim($supported_exchange_list,"'");
		$supported_exchange_list = rtrim($supported_exchange_list,'/');
		$supported_exchange_list = trim($supported_exchange_list);
	
	app_logging('config_debugging', 'Bitcoin markets configuration information', 'supported_primary_currency_list: ' . $supported_primary_currency_list . '; supported_exchange_list: ' . $supported_exchange_list . ';' );
	
	}



}



?>
<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// UNIT TESTS


// ONLY RUN THESE UNIT TESTS IF RUNTIME IS UI (web page loading)
if ( $runtime_mode == 'ui' ) {



	// Print out exchange config
	if ( $debug_mode == 'all' || $debug_mode == 'btc_markets_config' ) {
		
		
		foreach ( $bitcoin_market_currencies as $key => $unused ) {
		$supported_primary_currency_list .= strtolower($key) . ' / ';
		}
		$supported_primary_currency_list = "'" . implode("' / '",array_unique(explode(' / ', $supported_primary_currency_list)));
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		$supported_primary_currency_list = rtrim($supported_primary_currency_list,"'");
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		$supported_primary_currency_list = rtrim($supported_primary_currency_list,'/');
		$supported_primary_currency_list = trim($supported_primary_currency_list);
		
		foreach ( $coins_list['BTC']['market_pairing'] as $pairing_key => $unused ) {
			
				foreach ( $coins_list['BTC']['market_pairing'][$pairing_key] as $key => $unused ) {
				$supported_exchange_list .= strtolower($key) . ' / ';
				}
				
		}
		
		$supported_exchange_list = "'" . implode("' / '",array_unique(explode(' / ', $supported_exchange_list)));
		$supported_exchange_list = trim($supported_exchange_list);
		$supported_exchange_list = rtrim($supported_exchange_list,"'");
		$supported_exchange_list = rtrim($supported_exchange_list,'/');
		$supported_exchange_list = trim($supported_exchange_list);
	
	app_logging('other_debugging', 'Bitcoin markets configuration information', 'supported_primary_currency_list: ' . $supported_primary_currency_list . '; supported_exchange_list: ' . $supported_exchange_list . ';' );
	
	}



}

?>
<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



// RUN DURING 'ui' ONLY
if ( $ct['runtime_mode'] == 'ui' ) {

$all_exchange_count = 0;
$active_exchange_count = 0;
$active_currency_count = 0;


	// Summarize all market configurations
	if (
	$ct['conf']['power']['debug_mode'] == 'markets_conf'
	|| isset($_GET['subsection']) && $_GET['subsection'] == 'currency_support'
	|| isset($_GET['subsection']) && $_GET['subsection'] == 'price_alerts_charts'
	) {
		
		
		foreach ( $ct['conf']['assets']['BTC']['pair'] as $key => $unused ) {
			
			// Detects better with side space included
			if ( stristr($supported_prim_currency_list, ' ' . $key . ' ') == false ) {
			$active_currency_count = $active_currency_count + 1;
			$supported_prim_currency_list .= ' ' . $key . ' /';
			}
			
		
		}
		
		
	$supported_prim_currency_list = ltrim($supported_prim_currency_list);
		
	$pairs_count = $active_currency_count;
	$all_supported_pairs_list = $supported_prim_currency_list;
		
		
		foreach ( $ct['opt_conf']['crypto_pair'] as $key => $unused ) {
			
			// Detects better with side space included
			if ( stristr($all_supported_pairs_list, ' ' . $key . ' ') == false ) {
			$pairs_count = $pairs_count + 1;
			$all_supported_pairs_list .= ' ' . $key . ' /';
			}
			
		
		}
		
		
	$all_supported_pairs_list = ltrim($all_supported_pairs_list);
		
	// Alphabetical sorting
	$supported_prim_currency_list = $ct['var']->list_sort($supported_prim_currency_list, '/', 'sort', true);
	$all_supported_pairs_list = $ct['var']->list_sort($all_supported_pairs_list, '/', 'sort', true);
		
		
		foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $unused ) {
			
				foreach ( $ct['conf']['assets']['BTC']['pair'][$pair_key] as $exchange_key => $unused ) {
					
					// Detects better with side space included
					if ( !preg_match("/" . $exchange_key . " \//i", $active_btc_exchange_list) && stristr($exchange_key, 'bitmex_') == false ) { // Futures markets not allowed
					$active_exchange_count = $active_exchange_count + 1;
					$active_btc_exchange_list .= ' ' . $exchange_key . ' /';
					}
			
				
				}
				
		}
		
		
	$active_btc_exchange_list = ltrim($active_btc_exchange_list);
		
	$active_exchange_count = $active_exchange_count;
	$active_exchanges_list = $active_btc_exchange_list;
		
		
		foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {
			
				foreach ( $ct['conf']['assets'][$asset_key]['pair'] as $pair_key => $unused ) {
					
					foreach ( $ct['conf']['assets'][$asset_key]['pair'][$pair_key] as $exchange_key => $unused ) {
					
						// Detects better with side space included
						if ( !preg_match("/" . $exchange_key . " \//i", $active_exchanges_list) && $exchange_key != 'misc_assets' && $exchange_key != 'btc_nfts' && $exchange_key != 'eth_nfts' && $exchange_key != 'sol_nfts' && $exchange_key != 'alt_nfts' ) {
						$active_exchange_count = $active_exchange_count + 1;
						$active_exchanges_list .= ' ' . $exchange_key . ' /';
						}
			
					}
				
				}
				
		}
		
		
	$active_exchanges_list = ltrim($active_exchanges_list);		
		
	// Alphabetical sorting
	$active_btc_exchange_list = $ct['var']->list_sort($active_btc_exchange_list, '/', 'sort', true);
	$active_exchanges_list = $ct['var']->list_sort($active_exchanges_list, '/', 'sort', true);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


		
		
		foreach ( $ct['api']->exchange_apis as $all_exchanges_key => $unused ) {
		
		     if ( $all_exchanges_key == 'coingecko' ) {
		     
		     $cg_currencies_array = array_map( "trim", explode(',', $ct['conf']['currency']['coingecko_pairings_search']) );
		     
		          foreach( $cg_currencies_array as $cg_currency ) {
		          $all_exchange_count = $all_exchange_count + 1;
		          $all_exchanges_list .= ' ' . $all_exchanges_key . '_' . $cg_currency . ' /';
		          }
		     
		     }
		     else {
		     $all_exchange_count = $all_exchange_count + 1;
		     $all_exchanges_list .= ' ' . $all_exchanges_key . ' /';
		     }
				
		}
		
		
	$all_exchanges_list = ltrim($all_exchanges_list);		
		
	// Alphabetical sorting
	$all_exchanges_list = $ct['var']->list_sort($all_exchanges_list, '/', 'sort', true);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


	
	
     	if ( $ct['conf']['power']['debug_mode'] == 'markets_conf' ) {
     	
          $ct['gen']->log(
          				'conf_debug',
          				"\n\n" . 'bitcoin ACTIVE (markets in config) information: ' . "\n\n" . ' active_btc_prim_currencies_list['.$active_currency_count.']: ' . $supported_prim_currency_list . '; ' . "\n\n" . 'active_btc_exchanges_list['.$active_exchange_count.']: ' . $active_btc_exchange_list . "\n\n"
          				);
          	
          $ct['gen']->log(
          				'conf_debug',
          				"\n\n" . 'all ACTIVE (markets in config) information: ' . "\n\n" . ' active_pairs_list['.$pairs_count.']: ' . strtoupper($all_supported_pairs_list) . '; ' . "\n\n" . 'active_exchanges_list['.$active_exchange_count.']: ' . strtolower($active_exchanges_list) . "\n\n"
          				);
          	
          $ct['gen']->log(
          				'conf_debug',
          				"\n\n" . 'ALL supported exchanges information: ' . "\n\n" . 'all_supported_exchanges_list['.$all_exchange_count.']: ' . strtolower($all_exchanges_list) . "\n\n"
          				);
     	
     	}
	
	
	}


}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!


?>
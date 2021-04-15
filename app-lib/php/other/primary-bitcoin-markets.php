<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////
// PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////



// If end user tries to use a FUTURES MARKET as the primary bitcoin exchange name,
// we disable and trigger a warning (SINCE VALUES OFTEN DON'T REFLECT NORMAL MARKETS)
if ( stristr($pt_conf['gen']['btc_prim_exchange'], 'bitmex_') != false ) {
	
$pt_gen->log(
							'conf_error',
							'btc_prim_exchange variable not properly set (futures markets are not allowed)',
							'btc_prim_exchange: ' . $pt_conf['gen']['btc_prim_exchange'] . ';'
							);

$pt_conf['gen']['btc_prim_exchange'] = 'futures_markets_not_allowed'; // DISABLE

}


// Re-set default primary currency 'pref_bitcoin_markets' value, ONLY IF THIS VALUE #EXISTS ALREADY#
// (for UX, to override the pre-existing value...if we have set this as the global default currency market, we obviously prefer it)
// SHOULD ONLY BE STATIC, NOT MANIPULATEBLE DYNAMICALLY IN THE INTERFACE...SO WE JUST RUN EARLY HERE ONLY IN INIT.
if ( isset($pt_conf['power']['btc_pref_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ]) ) {
	
$pt_conf['power']['btc_pref_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] = $pt_conf['gen']['btc_prim_exchange'];

}



// Set chart/alert default Bitcoin markets
// BEFORE DEFAULT BITCOIN MARKET IS DYNAMICALLY MANIPULATED (during UI runtime)
// We NEVER change BTC / currency_market value FOR CHARTS/ALERTS (during cron runtime), 
// so move the default $pt_conf['gen']['btc_prim_currency_pairing'] / $pt_conf['gen']['btc_prim_exchange'] values into their own chart/alerts related variables,
// before dynamic updating of $pt_conf['gen']['btc_prim_currency_pairing'] / $pt_conf['gen']['btc_prim_exchange']
$default_btc_prim_currency_pairing = $pt_conf['gen']['btc_prim_currency_pairing']; 
$default_btc_prim_exchange = $pt_conf['gen']['btc_prim_exchange'];



// RUN AFTER SETTING $default_btc_prim_currency_pairing ABOVE
// If $default_btc_prim_currency_pairing has changed, or never been set in cache vars, delete all potentially mismatched data and set in cache vars
if ( $default_btc_prim_currency_pairing != trim( file_get_contents($base_dir . '/cache/vars/default_btc_prim_currency_pairing.dat') ) ) {

// Delete all fiat price alerts cache data
$pt_gen->del_all_files($base_dir . '/cache/alerts/fiat_price'); 

// Delete all lite charts SPOT PRICE data (automatically will trigger a lite chart rebuild)
$pt_cache->remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/lite');

	// Delete show_charts cookie data
	if ( isset($_COOKIE['show_charts']) ) {
	$pt_gen->store_cookie("show_charts", "", time()-3600);  
	unset($_COOKIE['show_charts']);  
	}

	// Delete show_charts post data
	if ( isset($_POST['show_charts']) ) {
	$_POST['show_charts'] = null;  
	}

// Update cache var
$pt_cache->save_file($base_dir . '/cache/vars/default_btc_prim_currency_pairing.dat', $default_btc_prim_currency_pairing);

}




// Charts / alerts / etc
if ( $runtime_mode == 'cron' || $runtime_mode == 'int_api' || $runtime_mode == 'webhook' ) {


// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
$default_btc_pairing_id = $pt_conf['assets']['BTC']['pairing'][$default_btc_prim_currency_pairing][$default_btc_prim_exchange];

$default_btc_prim_currency_val = $pt_api->market('BTC', $default_btc_prim_exchange, $default_btc_pairing_id)['last_trade'];
    
    
    // Log any charts/alerts Bitcoin market errors
    if ( !$pt_conf['assets']['BTC']['pairing'][$default_btc_prim_currency_pairing] ) {
    	
    $pt_gen->log(
    							'conf_error',
    							'primary-bitcoin-markets.php Charts / alerts btc_prim_currency_pairing variable not properly set',
    							'btc_prim_currency_pairing: ' . $default_btc_prim_currency_pairing . ';'
    							);
    
    }
    elseif ( !$pt_conf['assets']['BTC']['pairing'][$default_btc_prim_currency_pairing][$default_btc_prim_exchange] ) {
    	
    $pt_gen->log(
    							'conf_error',
    							'primary-bitcoin-markets.php Charts / alerts btc_prim_exchange variable not properly set',
    							'btc_prim_exchange: ' . $default_btc_prim_exchange . ';'
    							);
    
    }
    
    if ( !isset($default_btc_prim_currency_val) || $default_btc_prim_currency_val == 0 ) {
    	
    $pt_gen->log(
    							'market_error',
    							'primary-bitcoin-markets.php Charts / alerts Bitcoin primary currency market value not properly set',
    							'btc_prim_currency_pairing: ' . $default_btc_prim_currency_pairing . '; exchange: ' . $default_btc_prim_exchange . '; pairing_id: ' . $default_btc_pairing_id . '; value: ' . $default_btc_prim_currency_val
    							);
    
    }


// Set bitcoin market configs THAT ARE USUALLY DYNAMIC IN THE INTERFACE, to be the static default values during cron runtimes
// (may change these to be dynamic in cron runtimes someday for a currently unforseen reason,
// so let's keep dynamic and default bitcoin market variables as separate entities for now)
$sel_opt['sel_btc_pairing_id'] = $default_btc_pairing_id;
$sel_opt['sel_btc_prim_currency_val'] = $default_btc_prim_currency_val;


}
// UI etc
else {


    // If Stand-Alone Currency Market has been enabled (Settings page), REPLACE/OVERWRITE Bitcoin market config defaults
    if ( $_POST['prim_currency_market_standalone'] || $_COOKIE['prim_currency_market_standalone'] ) {
    	
    $sel_opt['prim_currency_market_standalone'] = explode("|", ( $_POST['prim_currency_market_standalone'] != '' ? $_POST['prim_currency_market_standalone'] : $_COOKIE['prim_currency_market_standalone'] ) );
    
    // MUST RUN !BEFORE! $pt_asset->btc_market() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR $pt_asset->btc_market() CALL
    $pt_conf['gen']['btc_prim_currency_pairing'] = $sel_opt['prim_currency_market_standalone'][0]; 
    
    $pt_conf['gen']['btc_prim_exchange'] = $pt_asset->btc_market($sel_opt['prim_currency_market_standalone'][1] - 1);
    
       if ( is_array($pt_conf['assets']) ) {
       $pt_conf['assets']['MISCASSETS']['name'] = 'Misc. '.strtoupper($pt_conf['gen']['btc_prim_currency_pairing']).' Value';
       }
                
    }
    
    
// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime

$sel_opt['sel_btc_pairing_id'] = $pt_conf['assets']['BTC']['pairing'][ $pt_conf['gen']['btc_prim_currency_pairing'] ][ $pt_conf['gen']['btc_prim_exchange'] ];

$sel_opt['sel_btc_prim_currency_val'] = $pt_api->market('BTC', $pt_conf['gen']['btc_prim_exchange'], $sel_opt['sel_btc_pairing_id'])['last_trade'];

    
    // Log any Bitcoin market errors
    if ( !$pt_conf['assets']['BTC']['pairing'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] ) {
    	
    $pt_gen->log(
    							'conf_error',
    							'primary-bitcoin-markets.php btc_prim_currency_pairing variable not properly set', 
    							'btc_prim_currency_pairing: ' . $pt_conf['gen']['btc_prim_currency_pairing'] . ';'
    							);
    
    }
    elseif ( !$pt_conf['assets']['BTC']['pairing'][ $pt_conf['gen']['btc_prim_currency_pairing'] ][ $pt_conf['gen']['btc_prim_exchange'] ] ) {
    	
    $pt_gen->log(
    							'conf_error',
    							'primary-bitcoin-markets.php btc_prim_exchange variable not properly set',
    							'btc_prim_exchange: ' . $pt_conf['gen']['btc_prim_exchange'] . ';'
    							);
    
    }
    
    
    if ( !isset($sel_opt['sel_btc_prim_currency_val']) || $sel_opt['sel_btc_prim_currency_val'] == 0 ) {
    	
    $pt_gen->log(
    							'market_error',
    							'init.php Bitcoin primary currency market value not properly set',
    							'btc_prim_currency_pairing: ' . $pt_conf['gen']['btc_prim_currency_pairing'] . '; exchange: ' . $pt_conf['gen']['btc_prim_exchange'] . '; pairing_id: ' . $sel_opt['sel_btc_pairing_id'] . '; value: ' . $sel_opt['sel_btc_prim_currency_val']
    							);
    
    }


}



//////////////////////////////////////////////////////////////////
// END PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////

  
 
 ?>
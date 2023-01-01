<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////
// PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////



// If end user tries to use a FUTURES MARKET as the primary bitcoin exchange name,
// we disable and trigger a warning (SINCE VALUES OFTEN DON'T REFLECT NORMAL MARKETS)
if ( stristr($ct_conf['gen']['btc_prim_exchange'], 'bitmex_') != false ) {
	
$ct_gen->log(
			'conf_error',
			'btc_prim_exchange variable not properly set (futures markets are not allowed)',
			'btc_prim_exchange: ' . $ct_conf['gen']['btc_prim_exchange'] . ';'
			);

$ct_conf['gen']['btc_prim_exchange'] = 'futures_mrkts_not_allowed'; // DISABLE

}


// Re-set default primary currency 'pref_bitcoin_mrkts' value, ONLY IF THIS VALUE #EXISTS ALREADY#
// (for UX, to override the pre-existing value...if we have set this as the global default currency market, we obviously prefer it)
// SHOULD ONLY BE STATIC, NOT MANIPULATEBLE DYNAMICALLY IN THE INTERFACE...SO WE JUST RUN EARLY HERE ONLY IN INIT.
if ( isset($ct_conf['power']['btc_pref_currency_mrkts'][ $ct_conf['gen']['btc_prim_currency_pair'] ]) ) {
$ct_conf['power']['btc_pref_currency_mrkts'][ $ct_conf['gen']['btc_prim_currency_pair'] ] = $ct_conf['gen']['btc_prim_exchange'];
}



// Set chart/alert default Bitcoin markets
// BEFORE DEFAULT BITCOIN MARKET IS DYNAMICALLY MANIPULATED (during UI runtime)
// We NEVER change BTC / currency_mrkt value FOR CHARTS/ALERTS (during cron runtime), 
// so move the default $ct_conf['gen']['btc_prim_currency_pair'] / $ct_conf['gen']['btc_prim_exchange'] values into their own chart/alerts related variables,
// before dynamic updating of $ct_conf['gen']['btc_prim_currency_pair'] / $ct_conf['gen']['btc_prim_exchange']
$default_btc_prim_currency_pair = $ct_conf['gen']['btc_prim_currency_pair']; 
$default_btc_prim_exchange = $ct_conf['gen']['btc_prim_exchange'];



// RUN AFTER SETTING $default_btc_prim_currency_pair ABOVE
// If $default_btc_prim_currency_pair has changed, or never been set in cache vars, delete all potentially mismatched data and set in cache vars
if (
!file_exists($base_dir . '/cache/vars/default_btc_prim_currency_pair.dat')
|| $default_btc_prim_currency_pair != trim( file_get_contents($base_dir . '/cache/vars/default_btc_prim_currency_pair.dat') )
) {

// Delete all fiat price alerts cache data
$ct_gen->del_all_files($base_dir . '/cache/alerts/fiat_price'); 

// Delete all light charts SPOT PRICE data (automatically will trigger a light chart rebuild)
$ct_cache->remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/light');

	// Delete show_charts cookie data
	if ( isset($_COOKIE['show_charts']) ) {
	unset($_COOKIE['show_charts']);
    $ct_gen->store_cookie('show_charts', '', time()-3600); // Delete 
	}

	// Delete show_charts post data
	if ( isset($_POST['show_charts']) ) {
	$_POST['show_charts'] = null;  
	}

// Update cache var
$ct_cache->save_file($base_dir . '/cache/vars/default_btc_prim_currency_pair.dat', $default_btc_prim_currency_pair);

}




// Charts / alerts / etc
if ( $runtime_mode == 'cron' || $runtime_mode == 'int_api' || $runtime_mode == 'webhook' ) {


// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
$default_btc_pair_id = $ct_conf['assets']['BTC']['pair'][$default_btc_prim_currency_pair][$default_btc_prim_exchange];

$default_btc_prim_currency_val = $ct_api->market('BTC', $default_btc_prim_exchange, $default_btc_pair_id)['last_trade'];
    
    
    // Log any charts/alerts Bitcoin market errors
    if ( !$ct_conf['assets']['BTC']['pair'][$default_btc_prim_currency_pair] ) {
    	
    $ct_gen->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php Charts / alerts btc_prim_currency_pair variable not properly set',
    			'btc_prim_currency_pair: ' . $default_btc_prim_currency_pair . ';'
    			);
    
    }
    elseif ( !$ct_conf['assets']['BTC']['pair'][$default_btc_prim_currency_pair][$default_btc_prim_exchange] ) {
    	
    $ct_gen->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php Charts / alerts btc_prim_exchange variable not properly set',
    			'btc_prim_exchange: ' . $default_btc_prim_exchange . ';'
    			);
    
    }
    
    if ( !isset($default_btc_prim_currency_val) || $default_btc_prim_currency_val == 0 ) {
    	
    $ct_gen->log(
    			'market_error',
    			'primary-bitcoin-markets-config.php Charts / alerts Bitcoin primary currency market value not properly set',
    			'btc_prim_currency_pair: ' . $default_btc_prim_currency_pair . '; exchange: ' . $default_btc_prim_exchange . '; pair_id: ' . $default_btc_pair_id . '; value: ' . $default_btc_prim_currency_val
    			);
    
    }


// Set bitcoin market configs THAT ARE USUALLY DYNAMIC IN THE INTERFACE, to be the static default values during cron runtimes
// (may change these to be dynamic in cron runtimes someday for a currently unforseen reason,
// so let's keep dynamic and default bitcoin market variables as separate entities for now)
$sel_opt['sel_btc_pair_id'] = $default_btc_pair_id;
$sel_opt['sel_btc_prim_currency_val'] = $default_btc_prim_currency_val;


}
// UI etc
else {


    // If Stand-Alone Currency Market has been enabled (Settings page), REPLACE/OVERWRITE Bitcoin market config defaults
    if ( $_POST['prim_currency_mrkt_standalone'] || $_COOKIE['prim_currency_mrkt_standalone'] ) {
    	
    $sel_opt['prim_currency_mrkt_standalone'] = explode("|", ( $_POST['prim_currency_mrkt_standalone'] != '' ? $_POST['prim_currency_mrkt_standalone'] : $_COOKIE['prim_currency_mrkt_standalone'] ) );
    
    // MUST RUN !BEFORE! $ct_asset->btc_mrkt() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR $ct_asset->btc_mrkt() CALL
    $ct_conf['gen']['btc_prim_currency_pair'] = $sel_opt['prim_currency_mrkt_standalone'][0]; 
    
    
    	// (we go by array index number here, rather than 1 or higher for html form values)
        if ( $sel_opt['prim_currency_mrkt_standalone'][1] > 0 ) {
        $ct_conf['gen']['btc_prim_exchange'] = $ct_asset->btc_mrkt($sel_opt['prim_currency_mrkt_standalone'][1] - 1);
        }
        else {
        $ct_conf['gen']['btc_prim_exchange'] = $ct_asset->btc_mrkt(0);
        }
    
    
        if ( is_array($ct_conf['assets']) ) {
        $ct_conf['assets']['MISCASSETS']['name'] = 'Misc. '.strtoupper($ct_conf['gen']['btc_prim_currency_pair']).' Value';
        }
                
                
    }
    
    
// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime

$sel_opt['sel_btc_pair_id'] = $ct_conf['assets']['BTC']['pair'][ $ct_conf['gen']['btc_prim_currency_pair'] ][ $ct_conf['gen']['btc_prim_exchange'] ];

$sel_opt['sel_btc_prim_currency_val'] = $ct_api->market('BTC', $ct_conf['gen']['btc_prim_exchange'], $sel_opt['sel_btc_pair_id'])['last_trade'];

    
    // Log any Bitcoin market errors
    if ( !$ct_conf['assets']['BTC']['pair'][ $ct_conf['gen']['btc_prim_currency_pair'] ] ) {
    	
    $ct_gen->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php btc_prim_currency_pair variable not properly set', 
    			'btc_prim_currency_pair: ' . $ct_conf['gen']['btc_prim_currency_pair'] . ';'
    			);
    
    }
    elseif ( !$ct_conf['assets']['BTC']['pair'][ $ct_conf['gen']['btc_prim_currency_pair'] ][ $ct_conf['gen']['btc_prim_exchange'] ] ) {
    	
    $ct_gen->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php btc_prim_exchange variable not properly set',
    			'btc_prim_exchange: ' . $ct_conf['gen']['btc_prim_exchange'] . ';'
    			);
    
    }
    
    
    if ( !isset($sel_opt['sel_btc_prim_currency_val']) || $sel_opt['sel_btc_prim_currency_val'] == 0 ) {
    	
    $ct_gen->log(
    			'market_error',
    			'init.php Bitcoin primary currency market value not properly set',
    			'btc_prim_currency_pair: ' . $ct_conf['gen']['btc_prim_currency_pair'] . '; exchange: ' . $ct_conf['gen']['btc_prim_exchange'] . '; pair_id: ' . $sel_opt['sel_btc_pair_id'] . '; value: ' . $sel_opt['sel_btc_prim_currency_val']
    			);
    
    }


}



//////////////////////////////////////////////////////////////////
// END PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
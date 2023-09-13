<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////
// PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////



// If end user tries to use a FUTURES MARKET as the primary bitcoin exchange name,
// we disable and trigger a warning (SINCE VALUES OFTEN DON'T REFLECT NORMAL MARKETS)
if ( stristr($ct['conf']['gen']['btc_prim_exchange'], 'bitmex_') != false ) {
	
$ct['gen']->log(
			'conf_error',
			'btc_prim_exchange variable not properly set (futures markets are not allowed)',
			'btc_prim_exchange: ' . $ct['conf']['gen']['btc_prim_exchange'] . ';'
			);

$ct['conf']['gen']['btc_prim_exchange'] = 'futures_mrkts_not_allowed'; // DISABLE

}


// Re-set default primary currency 'pref_bitcoin_mrkts' value, ONLY IF THIS VALUE #EXISTS ALREADY#
// (for UX, to override the pre-existing value...if we have set this as the global default currency market, we obviously prefer it)
// SHOULD ONLY BE STATIC, NOT MANIPULATEBLE DYNAMICALLY IN THE INTERFACE...SO WE JUST RUN EARLY HERE ONLY IN INIT.
if ( isset($ct['conf']['power']['btc_pref_currency_mrkts'][ $ct['conf']['gen']['btc_prim_currency_pair'] ]) ) {
$ct['conf']['power']['btc_pref_currency_mrkts'][ $ct['conf']['gen']['btc_prim_currency_pair'] ] = $ct['conf']['gen']['btc_prim_exchange'];
}



// Set chart/alert default Bitcoin markets
// BEFORE DEFAULT BITCOIN MARKET IS DYNAMICALLY MANIPULATED (during UI runtime)
// We NEVER change BTC / currency_mrkt value FOR CHARTS/ALERTS (during cron runtime), 
// so move the default $ct['conf']['gen']['btc_prim_currency_pair'] / $ct['conf']['gen']['btc_prim_exchange'] values into their own chart/alerts related variables,
// before dynamic updating of $ct['conf']['gen']['btc_prim_currency_pair'] / $ct['conf']['gen']['btc_prim_exchange']
$default_btc_prim_currency_pair = $ct['conf']['gen']['btc_prim_currency_pair']; 
$default_btc_prim_exchange = $ct['conf']['gen']['btc_prim_exchange'];



// RUN AFTER SETTING $default_btc_prim_currency_pair ABOVE
// If $default_btc_prim_currency_pair has changed, or never been set in cache vars, delete all potentially mismatched data and set in cache vars
if (
!file_exists($ct['base_dir'] . '/cache/vars/default_btc_prim_currency_pair.dat')
|| $default_btc_prim_currency_pair != trim( file_get_contents($ct['base_dir'] . '/cache/vars/default_btc_prim_currency_pair.dat') )
) {

// Delete all fiat price alerts cache data
$ct['gen']->del_all_files($ct['base_dir'] . '/cache/alerts/fiat_price'); 

// Delete all light charts SPOT PRICE data (automatically will trigger a light chart rebuild)
$ct['cache']->remove_dir($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/light');

	// Delete show_charts cookie data
	if ( isset($_COOKIE['show_charts']) ) {
	unset($_COOKIE['show_charts']);
    $ct['gen']->store_cookie('show_charts', '', time()-3600); // Delete 
	}

	// Delete show_charts post data
	if ( isset($_POST['show_charts']) ) {
	$_POST['show_charts'] = null;  
	}

// Update cache var
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/default_btc_prim_currency_pair.dat', $default_btc_prim_currency_pair);

}




// Charts / alerts / etc
if ( $ct['runtime_mode'] == 'cron' || $ct['runtime_mode'] == 'int_api' || $ct['runtime_mode'] == 'webhook' ) {


// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
$default_btc_pair_id = $ct['conf']['assets']['BTC']['pair'][$default_btc_prim_currency_pair][$default_btc_prim_exchange];

$default_btc_prim_currency_val = $ct['api']->market('BTC', $default_btc_prim_exchange, $default_btc_pair_id)['last_trade'];
    
    
    // Log any charts/alerts Bitcoin market errors
    if ( !$ct['conf']['assets']['BTC']['pair'][$default_btc_prim_currency_pair] ) {
    	
    $ct['gen']->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php Charts / alerts btc_prim_currency_pair variable not properly set',
    			'btc_prim_currency_pair: ' . $default_btc_prim_currency_pair . ';'
    			);
    
    }
    elseif ( !$ct['conf']['assets']['BTC']['pair'][$default_btc_prim_currency_pair][$default_btc_prim_exchange] ) {
    	
    $ct['gen']->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php Charts / alerts btc_prim_exchange variable not properly set',
    			'btc_prim_exchange: ' . $default_btc_prim_exchange . ';'
    			);
    
    }
    
    if ( !isset($default_btc_prim_currency_val) || $default_btc_prim_currency_val == 0 ) {
    	
    $ct['gen']->log(
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
    
    // MUST RUN !BEFORE! $ct['asset']->btc_mrkt() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR $ct['asset']->btc_mrkt() CALL
    $ct['conf']['gen']['btc_prim_currency_pair'] = $sel_opt['prim_currency_mrkt_standalone'][0]; 
    
    
    	// (we go by array index number here, rather than 1 or higher for html form values)
        if ( $sel_opt['prim_currency_mrkt_standalone'][1] > 0 ) {
        $ct['conf']['gen']['btc_prim_exchange'] = $ct['asset']->btc_mrkt($sel_opt['prim_currency_mrkt_standalone'][1] - 1);
        }
        else {
        $ct['conf']['gen']['btc_prim_exchange'] = $ct['asset']->btc_mrkt(0);
        }
    
    
	   // Dynamically modify MISCASSETS / ALTNFTS in $ct['conf']['assets']
	   // ONLY IF USER HASN'T MESSED UP $ct['conf']['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
	   // CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
        if ( is_array($ct['conf']['assets']) ) {
        $ct['conf']['assets']['MISCASSETS']['name'] = 'Misc. Assets (' . strtoupper($ct['conf']['gen']['btc_prim_currency_pair']) . ')';
        $ct['conf']['assets']['ALTNFTS']['name'] = 'Alternate NFTs (' . strtoupper($ct['conf']['gen']['btc_prim_currency_pair']) . ')';
        }
                
                
    }
    
    
// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime

$sel_opt['sel_btc_pair_id'] = $ct['conf']['assets']['BTC']['pair'][ $ct['conf']['gen']['btc_prim_currency_pair'] ][ $ct['conf']['gen']['btc_prim_exchange'] ];

$sel_opt['sel_btc_prim_currency_val'] = $ct['api']->market('BTC', $ct['conf']['gen']['btc_prim_exchange'], $sel_opt['sel_btc_pair_id'])['last_trade'];

    
    // Log any Bitcoin market errors
    if ( !$ct['conf']['assets']['BTC']['pair'][ $ct['conf']['gen']['btc_prim_currency_pair'] ] ) {
    	
    $ct['gen']->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php btc_prim_currency_pair variable not properly set', 
    			'btc_prim_currency_pair: ' . $ct['conf']['gen']['btc_prim_currency_pair'] . ';'
    			);
    
    }
    elseif ( !$ct['conf']['assets']['BTC']['pair'][ $ct['conf']['gen']['btc_prim_currency_pair'] ][ $ct['conf']['gen']['btc_prim_exchange'] ] ) {
    	
    $ct['gen']->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php btc_prim_exchange variable not properly set',
    			'btc_prim_exchange: ' . $ct['conf']['gen']['btc_prim_exchange'] . ';'
    			);
    
    }
    
    
    if ( !isset($sel_opt['sel_btc_prim_currency_val']) || $sel_opt['sel_btc_prim_currency_val'] == 0 ) {
    	
    $ct['gen']->log(
    			'market_error',
    			'init.php Bitcoin primary currency market value not properly set',
    			'btc_prim_currency_pair: ' . $ct['conf']['gen']['btc_prim_currency_pair'] . '; exchange: ' . $ct['conf']['gen']['btc_prim_exchange'] . '; pair_id: ' . $sel_opt['sel_btc_pair_id'] . '; value: ' . $sel_opt['sel_btc_prim_currency_val']
    			);
    
    }


}



//////////////////////////////////////////////////////////////////
// END PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////
// PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////



// If end user tries to use a FUTURES MARKET as the primary bitcoin exchange name,
// we disable and trigger a warning (SINCE VALUES OFTEN DON'T REFLECT NORMAL MARKETS)
if ( stristr($ocpt_conf['gen']['btc_prim_exchange'], 'bitmex_') != false ) {
app_logging('config_error', 'btc_prim_exchange variable not properly set (futures markets are not allowed)', 'btc_prim_exchange: ' . $ocpt_conf['gen']['btc_prim_exchange'] . ';' );
$ocpt_conf['gen']['btc_prim_exchange'] = 'futures_markets_not_allowed';
}


// Re-set default primary currency 'pref_bitcoin_markets' value, ONLY IF THIS VALUE #EXISTS ALREADY#
// (for UX, to override the pre-existing value...if we have set this as the global default currency market, we obviously prefer it)
// SHOULD ONLY BE STATIC, NOT MANIPULATEBLE DYNAMICALLY IN THE INTERFACE...SO WE JUST RUN EARLY HERE ONLY IN INIT.
if ( isset($ocpt_conf['power']['btc_pref_currency_markets'][$ocpt_conf['gen']['btc_prim_curr_pairing']]) ) {
$ocpt_conf['power']['btc_pref_currency_markets'][$ocpt_conf['gen']['btc_prim_curr_pairing']] = $ocpt_conf['gen']['btc_prim_exchange'];
}



// Set chart/alert default Bitcoin markets
// BEFORE DEFAULT BITCOIN MARKET IS DYNAMICALLY MANIPULATED (during UI runtime)
// We NEVER change BTC / currency_market value FOR CHARTS/ALERTS (during cron runtime), 
// so move the default $ocpt_conf['gen']['btc_prim_curr_pairing'] / $ocpt_conf['gen']['btc_prim_exchange'] values into their own chart/alerts related variables,
// before dynamic updating of $ocpt_conf['gen']['btc_prim_curr_pairing'] / $ocpt_conf['gen']['btc_prim_exchange']
$default_btc_prim_curr_pairing = $ocpt_conf['gen']['btc_prim_curr_pairing']; 
$default_btc_prim_exchange = $ocpt_conf['gen']['btc_prim_exchange'];



// RUN AFTER SETTING $default_btc_prim_curr_pairing ABOVE
// If $default_btc_prim_curr_pairing has changed, or never been set in cache vars, delete all potentially mismatched data and set in cache vars
if ( $default_btc_prim_curr_pairing != trim( file_get_contents($base_dir . '/cache/vars/default_btc_prim_curr_pairing.dat') ) ) {

// Delete all alerts cache data
delete_all_files($base_dir . '/cache/alerts'); 

// Delete all lite charts SPOT PRICE data (automatically will trigger a lite chart rebuild)
remove_directory($base_dir . '/cache/charts/spot_price_24hr_volume/lite');

	// Delete show_charts cookie data
	if ( isset($_COOKIE['show_charts']) ) {
	$ocpt_gen->store_cookie("show_charts", "", time()-3600);  
	unset($_COOKIE['show_charts']);  
	}

	// Delete show_charts post data
	if ( isset($_POST['show_charts']) ) {
	$_POST['show_charts'] = null;  
	}

// Update cache var
$ocpt_cache->save_file($base_dir . '/cache/vars/default_btc_prim_curr_pairing.dat', $default_btc_prim_curr_pairing);

}




// Charts / alerts / etc
if ( $runtime_mode == 'cron' || $runtime_mode == 'int_api' || $runtime_mode == 'webhook' ) {


    // MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
    $default_btc_pairing_id = $ocpt_conf['assets']['BTC']['pairing'][$default_btc_prim_curr_pairing][$default_btc_prim_exchange];
    $default_btc_prim_curr_value = $ocpt_api->market('BTC', $default_btc_prim_exchange, $default_btc_pairing_id)['last_trade'];
    
    
    // Log any charts/alerts Bitcoin market errors
    if ( !$ocpt_conf['assets']['BTC']['pairing'][$default_btc_prim_curr_pairing] ) {
    app_logging('config_error', 'primary-bitcoin-markets.php Charts / alerts btc_prim_curr_pairing variable not properly set', 'btc_prim_curr_pairing: ' . $default_btc_prim_curr_pairing . ';' );
    }
    elseif ( !$ocpt_conf['assets']['BTC']['pairing'][$default_btc_prim_curr_pairing][$default_btc_prim_exchange] ) {
    app_logging('config_error', 'primary-bitcoin-markets.php Charts / alerts btc_prim_exchange variable not properly set', 'btc_prim_exchange: ' . $default_btc_prim_exchange . ';' );
    }
    
    if ( !isset($default_btc_prim_curr_value) || $default_btc_prim_curr_value == 0 ) {
    app_logging('market_error', 'primary-bitcoin-markets.php Charts / alerts Bitcoin primary currency market value not properly set', 'btc_prim_curr_pairing: ' . $default_btc_prim_curr_pairing . '; exchange: ' . $default_btc_prim_exchange . '; pairing_id: ' . $default_btc_pairing_id . '; value: ' . $default_btc_prim_curr_value );
    }


// Set bitcoin market configs THAT ARE USUALLY DYNAMIC IN THE INTERFACE, to be the static default values during cron runtimes
// (may change these to be dynamic in cron runtimes someday for a currently unforseen reason,
// so let's keep dynamic and default bitcoin market variables as separate entities for now)
$sel_btc_pairing_id = $default_btc_pairing_id;
$sel_btc_prim_curr_value = $default_btc_prim_curr_value;


}
// UI etc
else {


    // If Stand-Alone Currency Market has been enabled (Settings page), REPLACE/OVERWRITE Bitcoin market config defaults
    if ( $_POST['prim_curr_market_standalone'] || $_COOKIE['prim_curr_market_standalone'] ) {
    $prim_curr_market_standalone = explode("|", ( $_POST['prim_curr_market_standalone'] != '' ? $_POST['prim_curr_market_standalone'] : $_COOKIE['prim_curr_market_standalone'] ) );
    $ocpt_conf['gen']['btc_prim_curr_pairing'] = $prim_curr_market_standalone[0]; // MUST RUN !BEFORE! btc_market() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR btc_market() CALL
    $ocpt_conf['gen']['btc_prim_exchange'] = btc_market($prim_curr_market_standalone[1] - 1);
    
       if ( is_array($ocpt_conf['assets']) ) {
       $ocpt_conf['assets']['MISCASSETS']['name'] = 'Misc. '.strtoupper($ocpt_conf['gen']['btc_prim_curr_pairing']).' Value';
       }
                
    }
    
    
    
    // MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
    $sel_btc_pairing_id = $ocpt_conf['assets']['BTC']['pairing'][$ocpt_conf['gen']['btc_prim_curr_pairing']][$ocpt_conf['gen']['btc_prim_exchange']];
    $sel_btc_prim_curr_value = $ocpt_api->market('BTC', $ocpt_conf['gen']['btc_prim_exchange'], $sel_btc_pairing_id)['last_trade'];

    
    // Log any Bitcoin market errors
    if ( !$ocpt_conf['assets']['BTC']['pairing'][$ocpt_conf['gen']['btc_prim_curr_pairing']] ) {
    app_logging('config_error', 'primary-bitcoin-markets.php btc_prim_curr_pairing variable not properly set', 'btc_prim_curr_pairing: ' . $ocpt_conf['gen']['btc_prim_curr_pairing'] . ';' );
    }
    elseif ( !$ocpt_conf['assets']['BTC']['pairing'][$ocpt_conf['gen']['btc_prim_curr_pairing']][$ocpt_conf['gen']['btc_prim_exchange']] ) {
    app_logging('config_error', 'primary-bitcoin-markets.php btc_prim_exchange variable not properly set', 'btc_prim_exchange: ' . $ocpt_conf['gen']['btc_prim_exchange'] . ';' );
    }
    
    if ( !isset($sel_btc_prim_curr_value) || $sel_btc_prim_curr_value == 0 ) {
    app_logging('market_error', 'init.php Bitcoin primary currency market value not properly set', 'btc_prim_curr_pairing: ' . $ocpt_conf['gen']['btc_prim_curr_pairing'] . '; exchange: ' . $ocpt_conf['gen']['btc_prim_exchange'] . '; pairing_id: ' . $sel_btc_pairing_id . '; value: ' . $sel_btc_prim_curr_value );
    }


}



//////////////////////////////////////////////////////////////////
// END PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////

  
 
 ?>
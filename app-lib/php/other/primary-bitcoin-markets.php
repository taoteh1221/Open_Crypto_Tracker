<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////
// PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////



// Re-set default primary currency 'preferred_bitcoin_markets' value, ONLY IF THIS VALUE #EXISTS ALREADY#
// (for UX, to override the pre-existing value...if we have set this as the global default currency market, we obviously prefer it)
// SHOULD ONLY BE STATIC, NOT MANIPULATEBLE DYNAMICALLY IN THE INTERFACE...SO WE JUST RUN EARLY HERE ONLY IN INIT.
if ( isset($app_config['power_user']['bitcoin_preferred_currency_markets'][$app_config['general']['btc_primary_currency_pairing']]) ) {
$app_config['power_user']['bitcoin_preferred_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] = $app_config['general']['btc_primary_exchange'];
}



// Set chart/alert default Bitcoin markets
// BEFORE DEFAULT BITCOIN MARKET IS DYNAMICALLY MANIPULATED (during UI runtime)
// We NEVER change BTC / currency_market value FOR CHARTS/ALERTS (during cron runtime), 
// so move the default $app_config['general']['btc_primary_currency_pairing'] / $app_config['general']['btc_primary_exchange'] values into their own chart/alerts related variables,
// before dynamic updating of $app_config['general']['btc_primary_currency_pairing'] / $app_config['general']['btc_primary_exchange']
$default_btc_primary_currency_pairing = $app_config['general']['btc_primary_currency_pairing']; 
$default_btc_primary_exchange = $app_config['general']['btc_primary_exchange'];



// RUN AFTER SETTING $default_btc_primary_currency_pairing ABOVE
// If $default_btc_primary_currency_pairing has changed, or never been set in cache vars, delete all potentially mismatched data and set in cache vars
if ( $default_btc_primary_currency_pairing != trim( file_get_contents($base_dir . '/cache/vars/default_btc_primary_currency_pairing.dat') ) ) {

// Delete all alerts cache data
delete_all_files($base_dir . '/cache/alerts'); 

	// Delete show_charts cookie data
	if ( isset($_COOKIE['show_charts']) ) {
	store_cookie_contents("show_charts", "", time()-3600);  
	unset($_COOKIE['show_charts']);  
	}

	// Delete show_charts post data
	if ( isset($_POST['show_charts']) ) {
	$_POST['show_charts'] = null;  
	}

// Update cache var
store_file_contents($base_dir . '/cache/vars/default_btc_primary_currency_pairing.dat', $default_btc_primary_currency_pairing);

}



// Charts / alerts / etc
if ( $runtime_mode == 'cron' || $runtime_mode == 'int_api' || $runtime_mode == 'webhook' ) {


    // MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
    $default_btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing][$default_btc_primary_exchange];
    $default_btc_primary_currency_value = asset_market_data('BTC', $default_btc_primary_exchange, $default_btc_pairing_id)['last_trade'];
    
    
    // Log any charts/alerts Bitcoin market errors
    if ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing] ) {
    app_logging('config_error', 'primary-bitcoin-markets.php Charts / alerts btc_primary_currency_pairing variable not properly set', 'btc_primary_currency_pairing: ' . $default_btc_primary_currency_pairing . ';' );
    }
    elseif ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing][$default_btc_primary_exchange] ) {
    app_logging('config_error', 'primary-bitcoin-markets.php Charts / alerts btc_primary_exchange variable not properly set', 'btc_primary_exchange: ' . $default_btc_primary_exchange . ';' );
    }
    
    if ( !isset($default_btc_primary_currency_value) || $default_btc_primary_currency_value == 0 ) {
    app_logging('market_error', 'primary-bitcoin-markets.php Charts / alerts Bitcoin primary currency market value not properly set', 'btc_primary_currency_pairing: ' . $default_btc_primary_currency_pairing . '; exchange: ' . $default_btc_primary_exchange . '; pairing_id: ' . $default_btc_pairing_id . '; value: ' . $default_btc_primary_currency_value );
    }


// Set bitcoin market configs THAT ARE USUALLY DYNAMIC IN THE INTERFACE, to be the static default values during cron runtimes
// (may change these to be dynamic in cron runtimes someday for a currently unforseen reason,
// so let's keep dynamic and default bitcoin market variables as separate entities for now)
$selected_btc_pairing_id = $default_btc_pairing_id;
$selected_btc_primary_currency_value = $default_btc_primary_currency_value;


}
// UI etc
else {


    // If Stand-Alone Currency Market has been enabled (Settings page), REPLACE/OVERWRITE Bitcoin market config defaults
    if ( $_POST['primary_currency_market_standalone'] || $_COOKIE['primary_currency_market_standalone'] ) {
    $primary_currency_market_standalone = explode("|", ( $_POST['primary_currency_market_standalone'] != '' ? $_POST['primary_currency_market_standalone'] : $_COOKIE['primary_currency_market_standalone'] ) );
    $app_config['general']['btc_primary_currency_pairing'] = $primary_currency_market_standalone[0]; // MUST RUN !BEFORE! btc_market() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR btc_market() CALL
    $app_config['general']['btc_primary_exchange'] = btc_market($primary_currency_market_standalone[1] - 1);
    
        if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {
       $app_config['portfolio_assets']['MISCASSETS']['asset_name'] = 'Misc. '.strtoupper($app_config['general']['btc_primary_currency_pairing']).' Value';
       }
                
    }
    
    
    
    // MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
    $selected_btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['general']['btc_primary_currency_pairing']][$app_config['general']['btc_primary_exchange']];
    $selected_btc_primary_currency_value = asset_market_data('BTC', $app_config['general']['btc_primary_exchange'], $selected_btc_pairing_id)['last_trade'];
    
    
    // Log any Bitcoin market errors
    if ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['general']['btc_primary_currency_pairing']] ) {
    app_logging('config_error', 'primary-bitcoin-markets.php btc_primary_currency_pairing variable not properly set', 'btc_primary_currency_pairing: ' . $app_config['general']['btc_primary_currency_pairing'] . ';' );
    }
    elseif ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['general']['btc_primary_currency_pairing']][$app_config['general']['btc_primary_exchange']] ) {
    app_logging('config_error', 'primary-bitcoin-markets.php btc_primary_exchange variable not properly set', 'btc_primary_exchange: ' . $app_config['general']['btc_primary_exchange'] . ';' );
    }
    
    if ( !isset($selected_btc_primary_currency_value) || $selected_btc_primary_currency_value == 0 ) {
    app_logging('market_error', 'init.php Bitcoin primary currency market value not properly set', 'btc_primary_currency_pairing: ' . $app_config['general']['btc_primary_currency_pairing'] . '; exchange: ' . $app_config['general']['btc_primary_exchange'] . '; pairing_id: ' . $selected_btc_pairing_id . '; value: ' . $selected_btc_primary_currency_value );
    }


}



//////////////////////////////////////////////////////////////////
// END PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////

  
 
 ?>
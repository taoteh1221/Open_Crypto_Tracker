<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

// Default BTC CRYPTO/CRYPTO market pairing support, BEFORE GENERATING MISCASSETS ARRAY
// (so we activate it here instead of in config.php, for good UX adding altcoin markets dynamically there)
// Add to beginning of the array
$app_config['crypto_to_crypto_pairing'] = array_merge( array('btc' => 'Ƀ ') , $app_config['crypto_to_crypto_pairing']);



// Dynamically add MISCASSETS to $app_config['portfolio_assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $app_config['portfolio_assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {
    
    $app_config['portfolio_assets']['MISCASSETS'] = array(
                                        'coin_name' => 'Misc. '.strtoupper($app_config['btc_primary_currency_pairing']).' Value',
                                        'marketcap_website_slug' => '',
                                        'market_pairing' => array()
                                        );
            
            
            foreach ( $app_config['crypto_to_crypto_pairing'] as $pairing_key => $pairing_unused ) {
            $app_config['portfolio_assets']['MISCASSETS']['market_pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            }
            
            foreach ( $app_config['bitcoin_market_currencies'] as $pairing_key => $pairing_unused ) {
            $app_config['portfolio_assets']['MISCASSETS']['market_pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            }
    
}



// Update dynamic mining rewards (UI only), if we are using the json config in the secured cache
if ( $runtime_mode == 'ui' && is_array($app_config['mining_rewards']) || $runtime_mode == 'ui' && is_object($app_config['mining_rewards']) ) {
$app_config['mining_rewards']['xmr'] = monero_reward(); // (2^64 - 1 - current_supply * 10^12) * 2^-19 * 10^-12
$app_config['mining_rewards']['dcr'] = ( decred_api('subsidy', 'work_reward') / 100000000 );      
}


    
// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairings for UX
if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {
    
    foreach ( $app_config['portfolio_assets'] as $symbol_key => $symbol_unused ) {
            
            if ( $app_config['portfolio_assets'][$symbol_key] == 'MISCASSETS' ) {
            asort($app_config['portfolio_assets'][$symbol_key]['market_pairing']);
            }
            else {
            ksort($app_config['portfolio_assets'][$symbol_key]['market_pairing']);
            }
            
            
            foreach ( $app_config['portfolio_assets'][$symbol_key]['market_pairing'] as $pairing_key => $pairing_unused ) {
            ksort($app_config['portfolio_assets'][$symbol_key]['market_pairing'][$pairing_key]);
            }
        
    }
    
}
    
    
    
// Clean / auto-correct $app_config['btc_primary_currency_pairing'] and $app_config['btc_primary_exchange'] BEFORE BELOW CHARTS/ALERTS LOGIC
$app_config['btc_primary_currency_pairing'] = cleanup_string($app_config['btc_primary_currency_pairing'], 'lower');
$app_config['btc_primary_exchange'] = cleanup_string($app_config['btc_primary_exchange'], 'lower');



// Get chart/alert defaults before default Bitcoin market is dynamically manipulated
// We NEVER change BTC / currency_market value FOR CHARTS/ALERTS, 
// so move the default $app_config['btc_primary_currency_pairing'] / $app_config['btc_primary_exchange'] values into their own chart/alerts related variables,
// before dynamic updating of $app_config['btc_primary_currency_pairing'] / $app_config['btc_primary_exchange']
$default_btc_primary_currency_pairing = $app_config['btc_primary_currency_pairing']; 
$default_btc_primary_exchange = $app_config['btc_primary_exchange'];



// If $default_btc_primary_currency_pairing has changed, delete all mismatched data
if ( file_exists($base_dir . '/cache/vars/default_btc_primary_currency_pairing.dat') 
&& $default_btc_primary_currency_pairing != trim( file_get_contents($base_dir . '/cache/vars/default_btc_primary_currency_pairing.dat') ) ) {

// Delete all alerts cache data
delete_all_files($base_dir . '/cache/alerts'); 

// Delete show_charts cookie
store_cookie_contents("show_charts", "", time()-3600);  
unset($_COOKIE['show_charts']);  

// Update cache var
store_file_contents($base_dir . '/cache/vars/default_btc_primary_currency_pairing.dat', $default_btc_primary_currency_pairing);

}



// If Stand-Alone Currency Market has been enabled (Settings page), REPLACE/OVERWRITE Bitcoin market config defaults
if ( $_POST['primary_currency_market_standalone'] || $_COOKIE['primary_currency_market_standalone'] ) {
$primary_currency_market_standalone = explode("|", ( $_POST['primary_currency_market_standalone'] != '' ? $_POST['primary_currency_market_standalone'] : $_COOKIE['primary_currency_market_standalone'] ) );
$app_config['btc_primary_currency_pairing'] = $primary_currency_market_standalone[0]; // MUST RUN !BEFORE! btc_market() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR btc_market() CALL
$app_config['btc_primary_exchange'] = btc_market($primary_currency_market_standalone[1] - 1);

	if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {
   $app_config['portfolio_assets']['MISCASSETS']['coin_name'] = 'Misc. '.strtoupper($app_config['btc_primary_currency_pairing']).' Value';
   }
     		
}



// Set BTC / currency_market dynamic value, IF $primary_currency_market_standalone NOT SET

if ( sizeof($primary_currency_market_standalone) != 2 && isset($selected_btc_primary_currency_pairing) ) {
$app_config['btc_primary_currency_pairing'] = $selected_btc_primary_currency_pairing;
}

if ( sizeof($primary_currency_market_standalone) != 2 && isset($selected_btc_primary_exchange) ) {
$app_config['btc_primary_exchange'] = $selected_btc_primary_exchange;
}



// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in config.php)

// Cleaning lowercase alphanumeric string values
$app_config['debug_mode'] = cleanup_string($app_config['debug_mode'], 'lower');
$app_config['log_detail_level'] = cleanup_string($app_config['log_detail_level'], 'lower');
$app_config['default_theme'] = cleanup_string($app_config['default_theme'], 'lower');
$app_config['btc_primary_currency_pairing'] = cleanup_string($app_config['btc_primary_currency_pairing'], 'lower');
$app_config['btc_primary_exchange'] = cleanup_string($app_config['btc_primary_exchange'], 'lower');
$app_config['primary_marketcap_site'] = cleanup_string($app_config['primary_marketcap_site'], 'lower');
$app_config['asset_price_alerts_block_volume_error'] = cleanup_string($app_config['asset_price_alerts_block_volume_error'], 'lower');
$app_config['api_strict_ssl'] = cleanup_string($app_config['api_strict_ssl'], 'lower');
$app_config['charts_page'] = cleanup_string($app_config['charts_page'], 'lower');
$app_config['smtp_secure'] = cleanup_string($app_config['smtp_secure'], 'lower');
$app_config['proxy_alerts'] = cleanup_string($app_config['proxy_alerts'], 'lower');
$app_config['proxy_alerts_runtime'] = cleanup_string($app_config['proxy_alerts_runtime'], 'lower');
$app_config['proxy_checkup_ok'] = cleanup_string($app_config['proxy_checkup_ok'], 'lower');

// Cleaning charts/alerts array
$cleaned_asset_charts_and_alerts = array();
foreach ( $app_config['asset_charts_and_alerts'] as $key => $value ) {
$cleaned_key = cleanup_string($key, 'lower');
$cleaned_value = cleanup_string($value, 'lower');
$cleaned_asset_charts_and_alerts[$cleaned_key] = $cleaned_value;
}
$app_config['asset_charts_and_alerts'] = $cleaned_asset_charts_and_alerts;

// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $app_config['mobile_network_text_gateways'] as $key => $value ) {
$cleaned_key = cleanup_string($key, 'lower');
$cleaned_value = cleanup_string($value, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_value;
}
$app_config['mobile_network_text_gateways'] = $cleaned_mobile_networks;

// END CONFIG CLEANUP

// Better decimal support for these vars...
$app_config['system_stats_first_chart_highest_value'] = number_to_string($app_config['system_stats_first_chart_highest_value']); 
$app_config['primary_currency_decimals_max_threshold'] = number_to_string($app_config['primary_currency_decimals_max_threshold']); 
$app_config['asset_price_alerts_percent'] = number_to_string($app_config['asset_price_alerts_percent']); 
$app_config['steempower_yearly_interest'] = number_to_string($app_config['steempower_yearly_interest']); 

//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

?>
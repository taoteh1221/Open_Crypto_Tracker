<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

    

// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in config.php)

// Cleaning lowercase alphanumeric string values
$app_config['debug_mode'] = cleanup_string($app_config['debug_mode'], 'lower');
$app_config['upgrade_check'] = cleanup_string($app_config['upgrade_check'], 'lower');
$app_config['btc_primary_currency_pairing'] = cleanup_string($app_config['btc_primary_currency_pairing'], 'lower');
$app_config['btc_primary_exchange'] = cleanup_string($app_config['btc_primary_exchange'], 'lower');
$app_config['log_detail_level'] = cleanup_string($app_config['log_detail_level'], 'lower');
$app_config['default_theme'] = cleanup_string($app_config['default_theme'], 'lower');
$app_config['primary_marketcap_site'] = cleanup_string($app_config['primary_marketcap_site'], 'lower');
$app_config['price_alerts_block_volume_error'] = cleanup_string($app_config['price_alerts_block_volume_error'], 'lower');
$app_config['remote_api_strict_ssl'] = cleanup_string($app_config['remote_api_strict_ssl'], 'lower');
$app_config['charts_page'] = cleanup_string($app_config['charts_page'], 'lower');
$app_config['smtp_email_secure'] = cleanup_string($app_config['smtp_email_secure'], 'lower');
$app_config['proxy_alerts'] = cleanup_string($app_config['proxy_alerts'], 'lower');
$app_config['proxy_alerts_runtime'] = cleanup_string($app_config['proxy_alerts_runtime'], 'lower');
$app_config['proxy_alerts_checkup_ok'] = cleanup_string($app_config['proxy_alerts_checkup_ok'], 'lower');

// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $app_config['charts_and_price_alerts'] as $key => $value ) {
$cleaned_key = cleanup_string($key, 'lower');
$cleaned_value = cleanup_string($value, 'lower');
$cleaned_charts_and_price_alerts[$cleaned_key] = $cleaned_value;
}
$app_config['charts_and_price_alerts'] = $cleaned_charts_and_price_alerts;

// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $app_config['mobile_network_text_gateways'] as $key => $value ) {
$cleaned_key = cleanup_string($key, 'lower');
$cleaned_value = cleanup_string($value, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_value;
}
$app_config['mobile_network_text_gateways'] = $cleaned_mobile_networks;

// END CONFIG CLEANUP



// Dynamically reconfigure / configure where needed


// Default BTC CRYPTO/CRYPTO market pairing support, BEFORE GENERATING MISCASSETS ARRAY
// (so we activate it here instead of in config.php, for good UX adding ONLY altcoin markets dynamically there)
$app_config['crypto_to_crypto_pairing']['btc'] = 'Ƀ ';



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
            
            foreach ( $app_config['bitcoin_currency_markets'] as $pairing_key => $pairing_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pairing')
            	if ( !array_key_exists($pairing_key, $app_config['crypto_to_crypto_pairing']) ) {
            	$app_config['portfolio_assets']['MISCASSETS']['market_pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            	}
            
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



// Better decimal support for these vars...
$app_config['system_stats_first_chart_highest_value'] = number_to_string($app_config['system_stats_first_chart_highest_value']); 
$app_config['primary_currency_decimals_max_threshold'] = number_to_string($app_config['primary_currency_decimals_max_threshold']); 
$app_config['price_alerts_threshold'] = number_to_string($app_config['price_alerts_threshold']); 
$app_config['hivepower_yearly_interest'] = number_to_string($app_config['hivepower_yearly_interest']); 


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

?>
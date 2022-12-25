<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

    

// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in possibly user-customized DEFAULTS in config.php)

// Cleaning lowercase alphanumeric string values, and auto-correct minor errors
$ct_conf['dev']['debug_mode'] = $ct_var->auto_correct_str($ct_conf['dev']['debug_mode'], 'lower');
$ct_conf['comms']['upgrade_alert'] = $ct_var->auto_correct_str($ct_conf['comms']['upgrade_alert'], 'lower');
$ct_conf['gen']['btc_prim_currency_pair'] = $ct_var->auto_correct_str($ct_conf['gen']['btc_prim_currency_pair'], 'lower');
$ct_conf['gen']['btc_prim_exchange'] = $ct_var->auto_correct_str($ct_conf['gen']['btc_prim_exchange'], 'lower');
$ct_conf['dev']['log_verb'] = $ct_var->auto_correct_str($ct_conf['dev']['log_verb'], 'lower');
$ct_conf['gen']['default_theme'] = $ct_var->auto_correct_str($ct_conf['gen']['default_theme'], 'lower');
$ct_conf['gen']['prim_mcap_site'] = $ct_var->auto_correct_str($ct_conf['gen']['prim_mcap_site'], 'lower');
$ct_conf['comms']['price_alert_block_vol_error'] = $ct_var->auto_correct_str($ct_conf['comms']['price_alert_block_vol_error'], 'lower');
$ct_conf['sec']['remote_api_strict_ssl'] = $ct_var->auto_correct_str($ct_conf['sec']['remote_api_strict_ssl'], 'lower');
$ct_conf['gen']['asset_charts_toggle'] = $ct_var->auto_correct_str($ct_conf['gen']['asset_charts_toggle'], 'lower');
$ct_conf['comms']['proxy_alert'] = $ct_var->auto_correct_str($ct_conf['comms']['proxy_alert'], 'lower');
$ct_conf['comms']['proxy_alert_runtime'] = $ct_var->auto_correct_str($ct_conf['comms']['proxy_alert_runtime'], 'lower');
$ct_conf['comms']['proxy_alert_checkup_ok'] = $ct_var->auto_correct_str($ct_conf['comms']['proxy_alert_checkup_ok'], 'lower');


// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $ct_conf['charts_alerts']['tracked_mrkts'] as $key => $val ) {
$cleaned_key = $ct_var->auto_correct_str($key, 'lower');
$cleaned_val = $ct_var->auto_correct_str($val, 'lower');
$cleaned_charts_and_price_alerts[$cleaned_key] = $cleaned_val;
}
$ct_conf['charts_alerts']['tracked_mrkts'] = $cleaned_charts_and_price_alerts;


// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $ct_conf['mob_net_txt_gateways'] as $key => $val ) {
$cleaned_key = $ct_var->auto_correct_str($key, 'lower');
$cleaned_val = $ct_var->auto_correct_str($val, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_val;
}
$ct_conf['mob_net_txt_gateways'] = $cleaned_mobile_networks;

// END CONFIG CLEANUP



// Dynamically reconfigure / configure where needed


// Default BTC CRYPTO/CRYPTO market pair support, BEFORE GENERATING MISCASSETS / ETHNFTS / SOLNFTS ARRAYS
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$ct_conf['power']['crypto_pair'] = array('btc' => 'Ƀ ') + $ct_conf['power']['crypto_pair']; // ADD TO #BEGINNING# OF ARRAY, FOR UX

// Numericly sort light chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($ct_conf['power']['light_chart_day_intervals']);

// Default light chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$ct_conf['power']['light_chart_day_intervals'][] = 'all';


// Idiot-proof maximum of +-35 on captcha text contrast
if ( abs($ct_conf['sec']['captcha_text_contrast']) > 35 ) {
$ct_conf['sec']['captcha_text_contrast'] = 35;
}

// Idiot-proof maximum of 35 degrees on captcha text angle-offset
if ( abs($ct_conf['sec']['captcha_text_angle']) > 35 || $ct_conf['sec']['captcha_text_angle'] < 0 ) {
$ct_conf['sec']['captcha_text_angle'] = 35;
}


// Dynamically add MISCASSETS to $ct_conf['assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $ct_conf['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
// ALSO ADDING ETHNFTS / SOLNFTS DYNAMICALLY HERE
if ( is_array($ct_conf['assets']) ) {
    
    $ct_conf['assets']['MISCASSETS'] = array(
                                        'name' => 'Misc. '.strtoupper($ct_conf['gen']['btc_prim_currency_pair']).' Value',
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct_conf['power']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct_conf['assets']['MISCASSETS']['pair'][$pair_key] = array('misc_assets' => $pair_key);
            }
            
            foreach ( $ct_conf['power']['btc_currency_mrkts'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct_conf['power']['crypto_pair']) ) {
            	$ct_conf['assets']['MISCASSETS']['pair'][$pair_key] = array('misc_assets' => $pair_key);
            	}
            
            }
    
    
    $ct_conf['assets']['ETHNFTS'] = array(
                                        'name' => 'Ethereum NFTs',
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct_conf['power']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct_conf['assets']['ETHNFTS']['pair'][$pair_key] = array('eth_nfts' => $pair_key);
            }
            
            foreach ( $ct_conf['power']['btc_currency_mrkts'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct_conf['power']['crypto_pair']) ) {
            	$ct_conf['assets']['ETHNFTS']['pair'][$pair_key] = array('eth_nfts' => $pair_key);
            	}
            
            }
    
    
    $ct_conf['assets']['SOLNFTS'] = array(
                                        'name' => 'Solana NFTs',
                                        'mcap_slug' => '',
                                        'pair' => array()
                                        );
            
            
            foreach ( $ct_conf['power']['crypto_pair'] as $pair_key => $pair_unused ) {
            $ct_conf['assets']['SOLNFTS']['pair'][$pair_key] = array('sol_nfts' => $pair_key);
            }
            
            foreach ( $ct_conf['power']['btc_currency_mrkts'] as $pair_key => $pair_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pair')
            	if ( !array_key_exists($pair_key, $ct_conf['power']['crypto_pair']) ) {
            	$ct_conf['assets']['SOLNFTS']['pair'][$pair_key] = array('sol_nfts' => $pair_key);
            	}
            
            }
                                        
}



// Update dynamic mining calculator settings (DURING 'ui' ONLY), since we are running the app's main settings from a cache
if ( $runtime_mode == 'ui' && is_array($ct_conf['power']['mining_calculators']) ) {
require('app-lib/php/other/calculators/mining/pow/dynamic-settings.php');
}


    
// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairs for UX
if ( is_array($ct_conf['assets']) ) {
    
    foreach ( $ct_conf['assets'] as $symbol_key => $symbol_unused ) {
            
            if ( $ct_conf['assets'][$symbol_key] == 'MISCASSETS' ) {
            asort($ct_conf['assets'][$symbol_key]['pair']); // Sort maintaining indices
            }
            else {
            ksort($ct_conf['assets'][$symbol_key]['pair']); // Sort by key name
            }
            
            
            foreach ( $ct_conf['assets'][$symbol_key]['pair'] as $pair_key => $pair_unused ) {
            ksort($ct_conf['assets'][$symbol_key]['pair'][$pair_key]);
            }
        
    }
    
}


// Alphabetically sort news feeds
$usort_feeds_results = usort($ct_conf['power']['news_feed'], array($ct_gen, 'titles_usort_alpha') );
   	
if ( !$usort_feeds_results ) {
$ct_gen->log('other_error', 'RSS feeds failed to sort alphabetically');
}


// Better decimal support for these vars...
$ct_conf['power']['sys_stats_first_chart_max_scale'] = $ct_var->num_to_str($ct_conf['power']['sys_stats_first_chart_max_scale']); 
$ct_conf['comms']['price_alert_thres'] = $ct_var->num_to_str($ct_conf['comms']['price_alert_thres']); 
$ct_conf['power']['hivepower_yearly_interest'] = $ct_var->num_to_str($ct_conf['power']['hivepower_yearly_interest']); 


// Backup archive password protection / encryption
if ( $ct_conf['sec']['backup_arch_pass'] != '' ) {
$backup_arch_pass = $ct_conf['sec']['backup_arch_pass'];
}
else {
$backup_arch_pass = false;
}


// Light chart config tracking / updating (checking for changes to light chart app config, to trigger light chart rebuilds)
$conf_light_chart_struct = md5( serialize($ct_conf['power']['light_chart_day_intervals']) . $ct_conf['power']['light_chart_data_points_max'] );

if ( !file_exists($base_dir . '/cache/vars/light_chart_struct.dat') ) {
$ct_cache->save_file($base_dir . '/cache/vars/light_chart_struct.dat', $conf_light_chart_struct);
$cached_light_chart_struct = $conf_light_chart_struct;
}
else {
$cached_light_chart_struct = trim( file_get_contents($base_dir . '/cache/vars/light_chart_struct.dat') );
}


// Check if we need to rebuild light charts from changes to their structure,
// OR a user-requested light chart reset
if (
$conf_light_chart_struct != $cached_light_chart_struct
|| $_POST['reset_light_charts'] == 1 && $ct_gen->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_light_charts')
) {

// Delete ALL light charts (this will automatically trigger a re-build)
$ct_cache->remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/light');
$ct_cache->remove_dir($base_dir . '/cache/charts/system/light');

// Cache the new light chart structure
$ct_cache->save_file($base_dir . '/cache/vars/light_chart_struct.dat', $conf_light_chart_struct);

}


// #GUI# PHP TIMEOUT tracking / updating (checking for changes to the config value)
$conf_php_timeout = $ct_conf['dev']['ui_max_exec_time'];

if ( !file_exists($base_dir . '/cache/vars/php_timeout.dat') ) {
$ct_cache->save_file($base_dir . '/cache/vars/php_timeout.dat', $conf_php_timeout);
$cached_php_timeout = $conf_php_timeout;
}
else {
$cached_php_timeout = trim( file_get_contents($base_dir . '/cache/vars/php_timeout.dat') );
}


// Check if we need to rebuild ROOT .htaccess / .user.ini
if ( $conf_php_timeout != $cached_php_timeout ) {

// Delete ROOT .htaccess / .user.ini
unlink($base_dir . '/.htaccess');
unlink($base_dir . '/.user.ini');
unlink($base_dir . '/cache/secured/.app_htpasswd');

// Cache the new PHP timeout
$ct_cache->save_file($base_dir . '/cache/vars/php_timeout.dat', $conf_php_timeout);

}


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
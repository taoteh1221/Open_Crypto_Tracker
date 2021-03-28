<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

    

// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in possibly user-customized DEFAULTS in config.php)

// Cleaning lowercase alphanumeric string values, and auto-correct minor errors
$app_config['developer']['debug_mode'] = $pt_vars->auto_correct_str($app_config['developer']['debug_mode'], 'lower');
$app_config['comms']['upgrade_alert'] = $pt_vars->auto_correct_str($app_config['comms']['upgrade_alert'], 'lower');
$app_config['general']['btc_primary_currency_pairing'] = $pt_vars->auto_correct_str($app_config['general']['btc_primary_currency_pairing'], 'lower');
$app_config['general']['btc_primary_exchange'] = $pt_vars->auto_correct_str($app_config['general']['btc_primary_exchange'], 'lower');
$app_config['developer']['log_verbosity'] = $pt_vars->auto_correct_str($app_config['developer']['log_verbosity'], 'lower');
$app_config['general']['default_theme'] = $pt_vars->auto_correct_str($app_config['general']['default_theme'], 'lower');
$app_config['general']['primary_marketcap_site'] = $pt_vars->auto_correct_str($app_config['general']['primary_marketcap_site'], 'lower');
$app_config['comms']['price_alerts_block_volume_error'] = $pt_vars->auto_correct_str($app_config['comms']['price_alerts_block_volume_error'], 'lower');
$app_config['developer']['remote_api_strict_ssl'] = $pt_vars->auto_correct_str($app_config['developer']['remote_api_strict_ssl'], 'lower');
$app_config['general']['asset_charts_toggle'] = $pt_vars->auto_correct_str($app_config['general']['asset_charts_toggle'], 'lower');
$app_config['comms']['proxy_alerts'] = $pt_vars->auto_correct_str($app_config['comms']['proxy_alerts'], 'lower');
$app_config['comms']['proxy_alerts_runtime'] = $pt_vars->auto_correct_str($app_config['comms']['proxy_alerts_runtime'], 'lower');
$app_config['comms']['proxy_alerts_checkup_ok'] = $pt_vars->auto_correct_str($app_config['comms']['proxy_alerts_checkup_ok'], 'lower');


// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $app_config['charts_alerts']['tracked_markets'] as $key => $value ) {
$cleaned_key = $pt_vars->auto_correct_str($key, 'lower');
$cleaned_value = $pt_vars->auto_correct_str($value, 'lower');
$cleaned_charts_and_price_alerts[$cleaned_key] = $cleaned_value;
}
$app_config['charts_alerts']['tracked_markets'] = $cleaned_charts_and_price_alerts;


// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $app_config['mobile_network_text_gateways'] as $key => $value ) {
$cleaned_key = $pt_vars->auto_correct_str($key, 'lower');
$cleaned_value = $pt_vars->auto_correct_str($value, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_value;
}
$app_config['mobile_network_text_gateways'] = $cleaned_mobile_networks;

// END CONFIG CLEANUP



// Dynamically reconfigure / configure where needed


// Default BTC CRYPTO/CRYPTO market pairing support, BEFORE GENERATING MISCASSETS ARRAY
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$app_config['power_user']['crypto_pairing'] = array('btc' => 'Ƀ ') + $app_config['power_user']['crypto_pairing']; // ADD TO #BEGINNING# OF ARRAY, FOR UX

// Numericly sort lite chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($app_config['power_user']['lite_chart_day_intervals']);

// Default lite chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$app_config['power_user']['lite_chart_day_intervals'][] = 'all';


// Idiot-proof maximum of +-35 on captcha text contrast
if ( abs($app_config['power_user']['captcha_text_contrast']) > 35 ) {
$app_config['power_user']['captcha_text_contrast'] = 35;
}

// Idiot-proof maximum of 35 degrees on captcha text angle-offset
if ( abs($app_config['power_user']['captcha_text_angle']) > 35 || $app_config['power_user']['captcha_text_angle'] < 0 ) {
$app_config['power_user']['captcha_text_angle'] = 35;
}


// Dynamically add MISCASSETS to $app_config['portfolio_assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $app_config['portfolio_assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
if ( is_array($app_config['portfolio_assets']) ) {
    
    $app_config['portfolio_assets']['MISCASSETS'] = array(
                                        'asset_name' => 'Misc. '.strtoupper($app_config['general']['btc_primary_currency_pairing']).' Value',
                                        'marketcap_website_slug' => '',
                                        'market_pairing' => array()
                                        );
            
            
            foreach ( $app_config['power_user']['crypto_pairing'] as $pairing_key => $pairing_unused ) {
            $app_config['portfolio_assets']['MISCASSETS']['market_pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            }
            
            foreach ( $app_config['power_user']['bitcoin_currency_markets'] as $pairing_key => $pairing_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pairing')
            	if ( !array_key_exists($pairing_key, $app_config['power_user']['crypto_pairing']) ) {
            	$app_config['portfolio_assets']['MISCASSETS']['market_pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            	}
            
            }
    
}



// Update dynamic mining data (DURING 'ui' ONLY), since we are using the json config in the secured cache
if ( $runtime_mode == 'ui' && is_array($app_config['power_user']['mining_calculators']) ) {
	

// BTC
$app_config['power_user']['mining_calculators']['pow']['btc']['height'] = bitcoin_api('height');
$app_config['power_user']['mining_calculators']['pow']['btc']['difficulty'] = bitcoin_api('difficulty');


// ETH
$app_config['power_user']['mining_calculators']['pow']['eth']['height'] = hexdec( etherscan_api('number') );      
$app_config['power_user']['mining_calculators']['pow']['eth']['difficulty'] = hexdec( etherscan_api('difficulty') );
$app_config['power_user']['mining_calculators']['pow']['eth']['other_network_data'] = '<p><b>Gas limit:</b> ' . number_format( hexdec( etherscan_api('gasLimit') ) ) . '</p>' . ( etherscan_api('number') == false ? '<p><a class="red" href="https://etherscan.io/apis/" target="_blank"><b>EtherScan.io (free) API key is required.</b></a></p>' : '' );

	
	// If a mining calculator is being used this runtime, include mining time formula calculations for that chain
	if ( isset($_POST['pow_calc']) ) {
				    
	$_POST['network_measure'] = $pt_vars->rem_num_format($_POST['network_measure']);
				    
	$_POST['your_hashrate'] = $pt_vars->rem_num_format($_POST['your_hashrate']);
		
	$miner_hashrate = trim($_POST['your_hashrate']) * trim($_POST['hash_level']);
	
		// Mining time formulas can be different per network, unless they copy Bitcoin's formula
		if ( $_POST['pow_calc'] == 'btc' ) {
		// https://en.bitcoin.it/wiki/Difficulty (How soon might I expect to generate a block?)
		$app_config['power_user']['mining_calculators']['pow']['btc']['mining_time_formula'] = trim($_POST['network_measure']) * pow(2, 32) / $miner_hashrate;
		}
		elseif ( $_POST['pow_calc'] == 'eth' ) {
		$app_config['power_user']['mining_calculators']['pow']['eth']['mining_time_formula'] = trim($_POST['network_measure']) / $miner_hashrate;
		}
	
	}
	

}


    
// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairings for UX
if ( is_array($app_config['portfolio_assets']) ) {
    
    foreach ( $app_config['portfolio_assets'] as $symbol_key => $symbol_unused ) {
            
            if ( $app_config['portfolio_assets'][$symbol_key] == 'MISCASSETS' ) {
            asort($app_config['portfolio_assets'][$symbol_key]['market_pairing']); // Sort maintaining indices
            }
            else {
            ksort($app_config['portfolio_assets'][$symbol_key]['market_pairing']); // Sort by key name
            }
            
            
            foreach ( $app_config['portfolio_assets'][$symbol_key]['market_pairing'] as $pairing_key => $pairing_unused ) {
            ksort($app_config['portfolio_assets'][$symbol_key]['market_pairing'][$pairing_key]);
            }
        
    }
    
}


// Alphabetically sort news feeds
$usort_feeds_results = usort($app_config['power_user']['news_feeds'], __NAMESPACE__ . '\titles_usort_alpha');
   	
if ( !$usort_feeds_results ) {
app_logging( 'other_error', 'RSS feeds failed to sort alphabetically');
}


// Better decimal support for these vars...
$app_config['power_user']['system_stats_first_chart_highest_value'] = $pt_vars->num_to_str($app_config['power_user']['system_stats_first_chart_highest_value']); 
$app_config['general']['primary_currency_decimals_max_threshold'] = $pt_vars->num_to_str($app_config['general']['primary_currency_decimals_max_threshold']); 
$app_config['comms']['price_alerts_threshold'] = $pt_vars->num_to_str($app_config['comms']['price_alerts_threshold']); 
$app_config['power_user']['hivepower_yearly_interest'] = $pt_vars->num_to_str($app_config['power_user']['hivepower_yearly_interest']); 


// Backup archive password protection / encryption
if ( $app_config['general']['backup_archive_password'] != '' ) {
$backup_archive_password = $app_config['general']['backup_archive_password'];
}
else {
$backup_archive_password = false;
}


// Light chart config tracking / updating (checking for changes to lite chart app config, to trigger lite chart rebuilds)
$config_lite_chart_structure = md5( serialize($app_config['power_user']['lite_chart_day_intervals']) . $app_config['power_user']['lite_chart_data_points_max'] );

if ( !file_exists($base_dir . '/cache/vars/lite_chart_structure.dat') ) {
store_file_contents($base_dir . '/cache/vars/lite_chart_structure.dat', $config_lite_chart_structure);
$cached_lite_chart_structure = $config_lite_chart_structure;
}
else {
$cached_lite_chart_structure = trim( file_get_contents($base_dir . '/cache/vars/lite_chart_structure.dat') );
}


// Check if we need to rebuild lite charts from changes to their structure
if ( $config_lite_chart_structure != $cached_lite_chart_structure ) {
remove_directory($base_dir . '/cache/charts/spot_price_24hr_volume/lite');
remove_directory($base_dir . '/cache/charts/system/lite');
store_file_contents($base_dir . '/cache/vars/lite_chart_structure.dat', $config_lite_chart_structure);
}


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

?>
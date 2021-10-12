<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

    

// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in possibly user-customized DEFAULTS in config.php)

// Cleaning lowercase alphanumeric string values, and auto-correct minor errors
$oct_conf['dev']['debug'] = $oct_var->auto_correct_str($oct_conf['dev']['debug'], 'lower');
$oct_conf['comms']['upgrade_alert'] = $oct_var->auto_correct_str($oct_conf['comms']['upgrade_alert'], 'lower');
$oct_conf['gen']['btc_prim_currency_pairing'] = $oct_var->auto_correct_str($oct_conf['gen']['btc_prim_currency_pairing'], 'lower');
$oct_conf['gen']['btc_prim_exchange'] = $oct_var->auto_correct_str($oct_conf['gen']['btc_prim_exchange'], 'lower');
$oct_conf['dev']['log_verb'] = $oct_var->auto_correct_str($oct_conf['dev']['log_verb'], 'lower');
$oct_conf['gen']['default_theme'] = $oct_var->auto_correct_str($oct_conf['gen']['default_theme'], 'lower');
$oct_conf['gen']['prim_mcap_site'] = $oct_var->auto_correct_str($oct_conf['gen']['prim_mcap_site'], 'lower');
$oct_conf['comms']['price_alert_block_vol_error'] = $oct_var->auto_correct_str($oct_conf['comms']['price_alert_block_vol_error'], 'lower');
$oct_conf['dev']['remote_api_strict_ssl'] = $oct_var->auto_correct_str($oct_conf['dev']['remote_api_strict_ssl'], 'lower');
$oct_conf['gen']['asset_charts_toggle'] = $oct_var->auto_correct_str($oct_conf['gen']['asset_charts_toggle'], 'lower');
$oct_conf['comms']['proxy_alert'] = $oct_var->auto_correct_str($oct_conf['comms']['proxy_alert'], 'lower');
$oct_conf['comms']['proxy_alert_runtime'] = $oct_var->auto_correct_str($oct_conf['comms']['proxy_alert_runtime'], 'lower');
$oct_conf['comms']['proxy_alert_checkup_ok'] = $oct_var->auto_correct_str($oct_conf['comms']['proxy_alert_checkup_ok'], 'lower');


// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $oct_conf['charts_alerts']['tracked_markets'] as $key => $val ) {
$cleaned_key = $oct_var->auto_correct_str($key, 'lower');
$cleaned_val = $oct_var->auto_correct_str($val, 'lower');
$cleaned_charts_and_price_alerts[$cleaned_key] = $cleaned_val;
}
$oct_conf['charts_alerts']['tracked_markets'] = $cleaned_charts_and_price_alerts;


// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $oct_conf['mob_net_txt_gateways'] as $key => $val ) {
$cleaned_key = $oct_var->auto_correct_str($key, 'lower');
$cleaned_val = $oct_var->auto_correct_str($val, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_val;
}
$oct_conf['mob_net_txt_gateways'] = $cleaned_mobile_networks;

// END CONFIG CLEANUP



// Dynamically reconfigure / configure where needed


// Default BTC CRYPTO/CRYPTO market pairing support, BEFORE GENERATING MISCASSETS ARRAY
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$oct_conf['power']['crypto_pairing'] = array('btc' => 'Ƀ ') + $oct_conf['power']['crypto_pairing']; // ADD TO #BEGINNING# OF ARRAY, FOR UX

// Numericly sort lite chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($oct_conf['power']['lite_chart_day_intervals']);

// Default lite chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$oct_conf['power']['lite_chart_day_intervals'][] = 'all';


// Idiot-proof maximum of +-35 on captcha text contrast
if ( abs($oct_conf['power']['captcha_text_contrast']) > 35 ) {
$oct_conf['power']['captcha_text_contrast'] = 35;
}

// Idiot-proof maximum of 35 degrees on captcha text angle-offset
if ( abs($oct_conf['power']['captcha_text_angle']) > 35 || $oct_conf['power']['captcha_text_angle'] < 0 ) {
$oct_conf['power']['captcha_text_angle'] = 35;
}


// Dynamically add MISCASSETS to $oct_conf['assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $oct_conf['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
if ( is_array($oct_conf['assets']) ) {
    
    $oct_conf['assets']['MISCASSETS'] = array(
                                        'name' => 'Misc. '.strtoupper($oct_conf['gen']['btc_prim_currency_pairing']).' Value',
                                        'mcap_slug' => '',
                                        'pairing' => array()
                                        );
            
            
            foreach ( $oct_conf['power']['crypto_pairing'] as $pairing_key => $pairing_unused ) {
            $oct_conf['assets']['MISCASSETS']['pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            }
            
            foreach ( $oct_conf['power']['btc_currency_markets'] as $pairing_key => $pairing_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pairing')
            	if ( !array_key_exists($pairing_key, $oct_conf['power']['crypto_pairing']) ) {
            	$oct_conf['assets']['MISCASSETS']['pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            	}
            
            }
    
}



// Update dynamic mining data (DURING 'ui' ONLY), since we are using the json config in the secured cache
if ( $runtime_mode == 'ui' && is_array($oct_conf['power']['mining_calculators']) ) {
	

// BTC
$oct_conf['power']['mining_calculators']['pow']['btc']['height'] = $oct_api->bitcoin('height');
$oct_conf['power']['mining_calculators']['pow']['btc']['difficulty'] = $oct_api->bitcoin('difficulty');


// ETH
$oct_conf['power']['mining_calculators']['pow']['eth']['height'] = hexdec( $oct_api->etherscan('number') );      
$oct_conf['power']['mining_calculators']['pow']['eth']['difficulty'] = hexdec( $oct_api->etherscan('difficulty') );
$oct_conf['power']['mining_calculators']['pow']['eth']['other_network_data'] = '<p><b>Gas limit:</b> ' . number_format( hexdec( $oct_api->etherscan('gasLimit') ) ) . '</p>' . ( $oct_api->etherscan('number') == false ? '<p><a class="red" href="https://etherscan.io/apis/" target="_blank"><b>EtherScan.io (free) API key is required.</b></a></p>' : '' );

	
	// If a mining calculator is being used this runtime, include mining time formula calculations for that chain
	if ( isset($_POST['pow_calc']) ) {
				    
	$_POST['network_measure'] = $oct_var->rem_num_format($_POST['network_measure']);
				    
	$_POST['your_hashrate'] = $oct_var->rem_num_format($_POST['your_hashrate']);
		
	$miner_hashrate = trim($_POST['your_hashrate']) * trim($_POST['hash_level']);
	
		// Mining time formulas can be different per network, unless they copy Bitcoin's formula
		if ( $_POST['pow_calc'] == 'btc' ) {
		// https://en.bitcoin.it/wiki/Difficulty (How soon might I expect to generate a block?)
		$oct_conf['power']['mining_calculators']['pow']['btc']['mining_time_formula'] = trim($_POST['network_measure']) * pow(2, 32) / $miner_hashrate;
		}
		elseif ( $_POST['pow_calc'] == 'eth' ) {
		$oct_conf['power']['mining_calculators']['pow']['eth']['mining_time_formula'] = trim($_POST['network_measure']) / $miner_hashrate;
		}
	
	}
	

}


    
// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairings for UX
if ( is_array($oct_conf['assets']) ) {
    
    foreach ( $oct_conf['assets'] as $symbol_key => $symbol_unused ) {
            
            if ( $oct_conf['assets'][$symbol_key] == 'MISCASSETS' ) {
            asort($oct_conf['assets'][$symbol_key]['pairing']); // Sort maintaining indices
            }
            else {
            ksort($oct_conf['assets'][$symbol_key]['pairing']); // Sort by key name
            }
            
            
            foreach ( $oct_conf['assets'][$symbol_key]['pairing'] as $pairing_key => $pairing_unused ) {
            ksort($oct_conf['assets'][$symbol_key]['pairing'][$pairing_key]);
            }
        
    }
    
}


// Alphabetically sort news feeds
$usort_feeds_results = usort($oct_conf['power']['news_feed'], array('oct_gen', 'titles_usort_alpha') );
   	
if ( !$usort_feeds_results ) {
$oct_gen->log('other_error', 'RSS feeds failed to sort alphabetically');
}


// Better decimal support for these vars...
$oct_conf['power']['system_stats_first_chart_highest_val'] = $oct_var->num_to_str($oct_conf['power']['system_stats_first_chart_highest_val']); 
$oct_conf['comms']['price_alert_thres'] = $oct_var->num_to_str($oct_conf['comms']['price_alert_thres']); 
$oct_conf['power']['hivepower_yearly_interest'] = $oct_var->num_to_str($oct_conf['power']['hivepower_yearly_interest']); 


// Backup archive password protection / encryption
if ( $oct_conf['gen']['backup_arch_pass'] != '' ) {
$backup_arch_pass = $oct_conf['gen']['backup_arch_pass'];
}
else {
$backup_arch_pass = false;
}


// Light chart config tracking / updating (checking for changes to lite chart app config, to trigger lite chart rebuilds)
$conf_lite_chart_struct = md5( serialize($oct_conf['power']['lite_chart_day_intervals']) . $oct_conf['power']['lite_chart_data_points_max'] );

if ( !file_exists($base_dir . '/cache/vars/lite_chart_struct.dat') ) {
$oct_cache->save_file($base_dir . '/cache/vars/lite_chart_struct.dat', $conf_lite_chart_struct);
$cached_lite_chart_struct = $conf_lite_chart_struct;
}
else {
$cached_lite_chart_struct = trim( file_get_contents($base_dir . '/cache/vars/lite_chart_struct.dat') );
}


// Check if we need to rebuild lite charts from changes to their structure
if ( $conf_lite_chart_struct != $cached_lite_chart_struct ) {
$oct_cache->remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/lite');
$oct_cache->remove_dir($base_dir . '/cache/charts/system/lite');
$oct_cache->save_file($base_dir . '/cache/vars/lite_chart_struct.dat', $conf_lite_chart_struct);
}


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

?>
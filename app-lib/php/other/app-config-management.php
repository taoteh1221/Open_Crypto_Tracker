<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

    

// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in possibly user-customized DEFAULTS in config.php)

// Cleaning lowercase alphanumeric string values, and auto-correct minor errors
$ct_conf['dev']['debug'] = $ct_var->auto_correct_str($ct_conf['dev']['debug'], 'lower');
$ct_conf['comms']['upgrade_alert'] = $ct_var->auto_correct_str($ct_conf['comms']['upgrade_alert'], 'lower');
$ct_conf['gen']['btc_prim_currency_pairing'] = $ct_var->auto_correct_str($ct_conf['gen']['btc_prim_currency_pairing'], 'lower');
$ct_conf['gen']['btc_prim_exchange'] = $ct_var->auto_correct_str($ct_conf['gen']['btc_prim_exchange'], 'lower');
$ct_conf['dev']['log_verb'] = $ct_var->auto_correct_str($ct_conf['dev']['log_verb'], 'lower');
$ct_conf['gen']['default_theme'] = $ct_var->auto_correct_str($ct_conf['gen']['default_theme'], 'lower');
$ct_conf['gen']['prim_mcap_site'] = $ct_var->auto_correct_str($ct_conf['gen']['prim_mcap_site'], 'lower');
$ct_conf['comms']['price_alert_block_vol_error'] = $ct_var->auto_correct_str($ct_conf['comms']['price_alert_block_vol_error'], 'lower');
$ct_conf['dev']['remote_api_strict_ssl'] = $ct_var->auto_correct_str($ct_conf['dev']['remote_api_strict_ssl'], 'lower');
$ct_conf['gen']['asset_charts_toggle'] = $ct_var->auto_correct_str($ct_conf['gen']['asset_charts_toggle'], 'lower');
$ct_conf['comms']['proxy_alert'] = $ct_var->auto_correct_str($ct_conf['comms']['proxy_alert'], 'lower');
$ct_conf['comms']['proxy_alert_runtime'] = $ct_var->auto_correct_str($ct_conf['comms']['proxy_alert_runtime'], 'lower');
$ct_conf['comms']['proxy_alert_checkup_ok'] = $ct_var->auto_correct_str($ct_conf['comms']['proxy_alert_checkup_ok'], 'lower');


// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $ct_conf['charts_alerts']['tracked_markets'] as $key => $val ) {
$cleaned_key = $ct_var->auto_correct_str($key, 'lower');
$cleaned_val = $ct_var->auto_correct_str($val, 'lower');
$cleaned_charts_and_price_alerts[$cleaned_key] = $cleaned_val;
}
$ct_conf['charts_alerts']['tracked_markets'] = $cleaned_charts_and_price_alerts;


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


// Default BTC CRYPTO/CRYPTO market pairing support, BEFORE GENERATING MISCASSETS ARRAY
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$ct_conf['power']['crypto_pairing'] = array('btc' => 'Ƀ ') + $ct_conf['power']['crypto_pairing']; // ADD TO #BEGINNING# OF ARRAY, FOR UX

// Numericly sort lite chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($ct_conf['power']['lite_chart_day_intervals']);

// Default lite chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$ct_conf['power']['lite_chart_day_intervals'][] = 'all';


// Idiot-proof maximum of +-35 on captcha text contrast
if ( abs($ct_conf['power']['captcha_text_contrast']) > 35 ) {
$ct_conf['power']['captcha_text_contrast'] = 35;
}

// Idiot-proof maximum of 35 degrees on captcha text angle-offset
if ( abs($ct_conf['power']['captcha_text_angle']) > 35 || $ct_conf['power']['captcha_text_angle'] < 0 ) {
$ct_conf['power']['captcha_text_angle'] = 35;
}


// Dynamically add MISCASSETS to $ct_conf['assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $ct_conf['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
if ( is_array($ct_conf['assets']) ) {
    
    $ct_conf['assets']['MISCASSETS'] = array(
                                        'name' => 'Misc. '.strtoupper($ct_conf['gen']['btc_prim_currency_pairing']).' Value',
                                        'mcap_slug' => '',
                                        'pairing' => array()
                                        );
            
            
            foreach ( $ct_conf['power']['crypto_pairing'] as $pairing_key => $pairing_unused ) {
            $ct_conf['assets']['MISCASSETS']['pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            }
            
            foreach ( $ct_conf['power']['btc_currency_markets'] as $pairing_key => $pairing_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pairing')
            	if ( !array_key_exists($pairing_key, $ct_conf['power']['crypto_pairing']) ) {
            	$ct_conf['assets']['MISCASSETS']['pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            	}
            
            }
    
}



// Update dynamic mining data (DURING 'ui' ONLY), since we are using the json config in the secured cache
if ( $runtime_mode == 'ui' && is_array($ct_conf['power']['mining_calculators']) ) {
	

// BTC
$ct_conf['power']['mining_calculators']['pow']['btc']['height'] = $ct_api->bitcoin('height');
$ct_conf['power']['mining_calculators']['pow']['btc']['difficulty'] = $ct_api->bitcoin('difficulty');


// ETH
$ct_conf['power']['mining_calculators']['pow']['eth']['height'] = hexdec( $ct_api->etherscan('number') );      
$ct_conf['power']['mining_calculators']['pow']['eth']['difficulty'] = hexdec( $ct_api->etherscan('difficulty') );
$ct_conf['power']['mining_calculators']['pow']['eth']['other_network_data'] = '<p><b>Gas limit:</b> ' . number_format( hexdec( $ct_api->etherscan('gasLimit') ) ) . '</p>' . ( $ct_api->etherscan('number') == false ? '<p><a class="red" href="https://etherscan.io/apis/" target="_blank"><b>EtherScan.io (free) API key is required.</b></a></p>' : '' );

	
	// If a mining calculator is being used this runtime, include mining time formula calculations for that chain
	if ( isset($_POST['pow_calc']) ) {
				    
	$_POST['network_measure'] = $ct_var->rem_num_format($_POST['network_measure']);
				    
	$_POST['your_hashrate'] = $ct_var->rem_num_format($_POST['your_hashrate']);
		
	$miner_hashrate = trim($_POST['your_hashrate']) * trim($_POST['hash_level']);
	
		// Mining time formulas can be different per network, unless they copy Bitcoin's formula
		if ( $_POST['pow_calc'] == 'btc' ) {
		// https://en.bitcoin.it/wiki/Difficulty (How soon might I expect to generate a block?)
		$ct_conf['power']['mining_calculators']['pow']['btc']['mining_time_formula'] = trim($_POST['network_measure']) * pow(2, 32) / $miner_hashrate;
		}
		elseif ( $_POST['pow_calc'] == 'eth' ) {
		$ct_conf['power']['mining_calculators']['pow']['eth']['mining_time_formula'] = trim($_POST['network_measure']) / $miner_hashrate;
		}
	
	}
	

}


    
// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairings for UX
if ( is_array($ct_conf['assets']) ) {
    
    foreach ( $ct_conf['assets'] as $symbol_key => $symbol_unused ) {
            
            if ( $ct_conf['assets'][$symbol_key] == 'MISCASSETS' ) {
            asort($ct_conf['assets'][$symbol_key]['pairing']); // Sort maintaining indices
            }
            else {
            ksort($ct_conf['assets'][$symbol_key]['pairing']); // Sort by key name
            }
            
            
            foreach ( $ct_conf['assets'][$symbol_key]['pairing'] as $pairing_key => $pairing_unused ) {
            ksort($ct_conf['assets'][$symbol_key]['pairing'][$pairing_key]);
            }
        
    }
    
}


// Alphabetically sort news feeds
$usort_feeds_results = usort($ct_conf['power']['news_feed'], array('ct_gen', 'titles_usort_alpha') );
   	
if ( !$usort_feeds_results ) {
$ct_gen->log('other_error', 'RSS feeds failed to sort alphabetically');
}


// Better decimal support for these vars...
$ct_conf['power']['system_stats_first_chart_highest_val'] = $ct_var->num_to_str($ct_conf['power']['system_stats_first_chart_highest_val']); 
$ct_conf['comms']['price_alert_thres'] = $ct_var->num_to_str($ct_conf['comms']['price_alert_thres']); 
$ct_conf['power']['hivepower_yearly_interest'] = $ct_var->num_to_str($ct_conf['power']['hivepower_yearly_interest']); 


// Backup archive password protection / encryption
if ( $ct_conf['gen']['backup_arch_pass'] != '' ) {
$backup_arch_pass = $ct_conf['gen']['backup_arch_pass'];
}
else {
$backup_arch_pass = false;
}


// Light chart config tracking / updating (checking for changes to lite chart app config, to trigger lite chart rebuilds)
$conf_lite_chart_struct = md5( serialize($ct_conf['power']['lite_chart_day_intervals']) . $ct_conf['power']['lite_chart_data_points_max'] );

if ( !file_exists($base_dir . '/cache/vars/lite_chart_struct.dat') ) {
$ct_cache->save_file($base_dir . '/cache/vars/lite_chart_struct.dat', $conf_lite_chart_struct);
$cached_lite_chart_struct = $conf_lite_chart_struct;
}
else {
$cached_lite_chart_struct = trim( file_get_contents($base_dir . '/cache/vars/lite_chart_struct.dat') );
}


// Check if we need to rebuild lite charts from changes to their structure
if ( $conf_lite_chart_struct != $cached_lite_chart_struct ) {
$ct_cache->remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/lite');
$ct_cache->remove_dir($base_dir . '/cache/charts/system/lite');
$ct_cache->save_file($base_dir . '/cache/vars/lite_chart_struct.dat', $conf_lite_chart_struct);
}


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

?>
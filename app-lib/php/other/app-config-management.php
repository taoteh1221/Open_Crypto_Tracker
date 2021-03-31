<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

    

// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in possibly user-customized DEFAULTS in config.php)

// Cleaning lowercase alphanumeric string values, and auto-correct minor errors
$ocpt_conf['dev']['debug'] = $ocpt_var->auto_correct_str($ocpt_conf['dev']['debug'], 'lower');
$ocpt_conf['comms']['upgrade_alert'] = $ocpt_var->auto_correct_str($ocpt_conf['comms']['upgrade_alert'], 'lower');
$ocpt_conf['gen']['btc_prim_curr_pairing'] = $ocpt_var->auto_correct_str($ocpt_conf['gen']['btc_prim_curr_pairing'], 'lower');
$ocpt_conf['gen']['btc_prim_exchange'] = $ocpt_var->auto_correct_str($ocpt_conf['gen']['btc_prim_exchange'], 'lower');
$ocpt_conf['dev']['log_verb'] = $ocpt_var->auto_correct_str($ocpt_conf['dev']['log_verb'], 'lower');
$ocpt_conf['gen']['default_theme'] = $ocpt_var->auto_correct_str($ocpt_conf['gen']['default_theme'], 'lower');
$ocpt_conf['gen']['prim_mcap_site'] = $ocpt_var->auto_correct_str($ocpt_conf['gen']['prim_mcap_site'], 'lower');
$ocpt_conf['comms']['price_alert_block_vol_error'] = $ocpt_var->auto_correct_str($ocpt_conf['comms']['price_alert_block_vol_error'], 'lower');
$ocpt_conf['dev']['remote_api_strict_ssl'] = $ocpt_var->auto_correct_str($ocpt_conf['dev']['remote_api_strict_ssl'], 'lower');
$ocpt_conf['gen']['asset_charts_toggle'] = $ocpt_var->auto_correct_str($ocpt_conf['gen']['asset_charts_toggle'], 'lower');
$ocpt_conf['comms']['proxy_alert'] = $ocpt_var->auto_correct_str($ocpt_conf['comms']['proxy_alert'], 'lower');
$ocpt_conf['comms']['proxy_alert_runtime'] = $ocpt_var->auto_correct_str($ocpt_conf['comms']['proxy_alert_runtime'], 'lower');
$ocpt_conf['comms']['proxy_alert_checkup_ok'] = $ocpt_var->auto_correct_str($ocpt_conf['comms']['proxy_alert_checkup_ok'], 'lower');


// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $ocpt_conf['charts_alerts']['tracked_markets'] as $key => $val ) {
$cleaned_key = $ocpt_var->auto_correct_str($key, 'lower');
$cleaned_val = $ocpt_var->auto_correct_str($val, 'lower');
$cleaned_charts_and_price_alerts[$cleaned_key] = $cleaned_val;
}
$ocpt_conf['charts_alerts']['tracked_markets'] = $cleaned_charts_and_price_alerts;


// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $ocpt_conf['mob_net_txt_gateways'] as $key => $val ) {
$cleaned_key = $ocpt_var->auto_correct_str($key, 'lower');
$cleaned_val = $ocpt_var->auto_correct_str($val, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_val;
}
$ocpt_conf['mob_net_txt_gateways'] = $cleaned_mobile_networks;

// END CONFIG CLEANUP



// Dynamically reconfigure / configure where needed


// Default BTC CRYPTO/CRYPTO market pairing support, BEFORE GENERATING MISCASSETS ARRAY
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$ocpt_conf['power']['crypto_pairing'] = array('btc' => 'Ƀ ') + $ocpt_conf['power']['crypto_pairing']; // ADD TO #BEGINNING# OF ARRAY, FOR UX

// Numericly sort lite chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($ocpt_conf['power']['lite_chart_day_intervals']);

// Default lite chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$ocpt_conf['power']['lite_chart_day_intervals'][] = 'all';


// Idiot-proof maximum of +-35 on captcha text contrast
if ( abs($ocpt_conf['power']['captcha_text_contrast']) > 35 ) {
$ocpt_conf['power']['captcha_text_contrast'] = 35;
}

// Idiot-proof maximum of 35 degrees on captcha text angle-offset
if ( abs($ocpt_conf['power']['captcha_text_angle']) > 35 || $ocpt_conf['power']['captcha_text_angle'] < 0 ) {
$ocpt_conf['power']['captcha_text_angle'] = 35;
}


// Dynamically add MISCASSETS to $ocpt_conf['assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $ocpt_conf['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
if ( is_array($ocpt_conf['assets']) ) {
    
    $ocpt_conf['assets']['MISCASSETS'] = array(
                                        'name' => 'Misc. '.strtoupper($ocpt_conf['gen']['btc_prim_curr_pairing']).' Value',
                                        'mcap_slug' => '',
                                        'pairing' => array()
                                        );
            
            
            foreach ( $ocpt_conf['power']['crypto_pairing'] as $pairing_key => $pairing_unused ) {
            $ocpt_conf['assets']['MISCASSETS']['pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            }
            
            foreach ( $ocpt_conf['power']['btc_curr_markets'] as $pairing_key => $pairing_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pairing')
            	if ( !array_key_exists($pairing_key, $ocpt_conf['power']['crypto_pairing']) ) {
            	$ocpt_conf['assets']['MISCASSETS']['pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            	}
            
            }
    
}



// Update dynamic mining data (DURING 'ui' ONLY), since we are using the json config in the secured cache
if ( $runtime_mode == 'ui' && is_array($ocpt_conf['power']['mining_calculators']) ) {
	

// BTC
$ocpt_conf['power']['mining_calculators']['pow']['btc']['height'] = $ocpt_api->bitcoin('height');
$ocpt_conf['power']['mining_calculators']['pow']['btc']['difficulty'] = $ocpt_api->bitcoin('difficulty');


// ETH
$ocpt_conf['power']['mining_calculators']['pow']['eth']['height'] = hexdec( $ocpt_api->etherscan('number') );      
$ocpt_conf['power']['mining_calculators']['pow']['eth']['difficulty'] = hexdec( $ocpt_api->etherscan('difficulty') );
$ocpt_conf['power']['mining_calculators']['pow']['eth']['other_network_data'] = '<p><b>Gas limit:</b> ' . number_format( hexdec( $ocpt_api->etherscan('gasLimit') ) ) . '</p>' . ( $ocpt_api->etherscan('number') == false ? '<p><a class="red" href="https://etherscan.io/apis/" target="_blank"><b>EtherScan.io (free) API key is required.</b></a></p>' : '' );

	
	// If a mining calculator is being used this runtime, include mining time formula calculations for that chain
	if ( isset($_POST['pow_calc']) ) {
				    
	$_POST['network_measure'] = $ocpt_var->rem_num_format($_POST['network_measure']);
				    
	$_POST['your_hashrate'] = $ocpt_var->rem_num_format($_POST['your_hashrate']);
		
	$miner_hashrate = trim($_POST['your_hashrate']) * trim($_POST['hash_level']);
	
		// Mining time formulas can be different per network, unless they copy Bitcoin's formula
		if ( $_POST['pow_calc'] == 'btc' ) {
		// https://en.bitcoin.it/wiki/Difficulty (How soon might I expect to generate a block?)
		$ocpt_conf['power']['mining_calculators']['pow']['btc']['mining_time_formula'] = trim($_POST['network_measure']) * pow(2, 32) / $miner_hashrate;
		}
		elseif ( $_POST['pow_calc'] == 'eth' ) {
		$ocpt_conf['power']['mining_calculators']['pow']['eth']['mining_time_formula'] = trim($_POST['network_measure']) / $miner_hashrate;
		}
	
	}
	

}


    
// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairings for UX
if ( is_array($ocpt_conf['assets']) ) {
    
    foreach ( $ocpt_conf['assets'] as $symbol_key => $symbol_unused ) {
            
            if ( $ocpt_conf['assets'][$symbol_key] == 'MISCASSETS' ) {
            asort($ocpt_conf['assets'][$symbol_key]['pairing']); // Sort maintaining indices
            }
            else {
            ksort($ocpt_conf['assets'][$symbol_key]['pairing']); // Sort by key name
            }
            
            
            foreach ( $ocpt_conf['assets'][$symbol_key]['pairing'] as $pairing_key => $pairing_unused ) {
            ksort($ocpt_conf['assets'][$symbol_key]['pairing'][$pairing_key]);
            }
        
    }
    
}


// Alphabetically sort news feeds
$usort_feeds_results = usort($ocpt_conf['power']['news_feed'], array('ocpt_gen', 'titles_usort_alpha') );
   	
if ( !$usort_feeds_results ) {
app_logging( 'other_error', 'RSS feeds failed to sort alphabetically');
}


// Better decimal support for these vars...
$ocpt_conf['power']['system_stats_first_chart_highest_val'] = $ocpt_var->num_to_str($ocpt_conf['power']['system_stats_first_chart_highest_val']); 
$ocpt_conf['gen']['prim_curr_dec_max_thres'] = $ocpt_var->num_to_str($ocpt_conf['gen']['prim_curr_dec_max_thres']); 
$ocpt_conf['comms']['price_alert_thres'] = $ocpt_var->num_to_str($ocpt_conf['comms']['price_alert_thres']); 
$ocpt_conf['power']['hivepower_yearly_interest'] = $ocpt_var->num_to_str($ocpt_conf['power']['hivepower_yearly_interest']); 


// Backup archive password protection / encryption
if ( $ocpt_conf['gen']['backup_arch_pass'] != '' ) {
$backup_arch_pass = $ocpt_conf['gen']['backup_arch_pass'];
}
else {
$backup_arch_pass = false;
}


// Light chart config tracking / updating (checking for changes to lite chart app config, to trigger lite chart rebuilds)
$config_lite_chart_structure = md5( serialize($ocpt_conf['power']['lite_chart_day_intervals']) . $ocpt_conf['power']['lite_chart_data_points_max'] );

if ( !file_exists($base_dir . '/cache/vars/lite_chart_structure.dat') ) {
$ocpt_cache->save_file($base_dir . '/cache/vars/lite_chart_structure.dat', $config_lite_chart_structure);
$cached_lite_chart_structure = $config_lite_chart_structure;
}
else {
$cached_lite_chart_structure = trim( file_get_contents($base_dir . '/cache/vars/lite_chart_structure.dat') );
}


// Check if we need to rebuild lite charts from changes to their structure
if ( $config_lite_chart_structure != $cached_lite_chart_structure ) {
remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/lite');
remove_dir($base_dir . '/cache/charts/system/lite');
$ocpt_cache->save_file($base_dir . '/cache/vars/lite_chart_structure.dat', $config_lite_chart_structure);
}


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

?>
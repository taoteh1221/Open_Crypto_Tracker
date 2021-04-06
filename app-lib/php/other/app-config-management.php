<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

    

// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in possibly user-customized DEFAULTS in config.php)

// Cleaning lowercase alphanumeric string values, and auto-correct minor errors
$pt_conf['dev']['debug'] = $pt_var->auto_correct_str($pt_conf['dev']['debug'], 'lower');
$pt_conf['comms']['upgrade_alert'] = $pt_var->auto_correct_str($pt_conf['comms']['upgrade_alert'], 'lower');
$pt_conf['gen']['btc_prim_curr_pairing'] = $pt_var->auto_correct_str($pt_conf['gen']['btc_prim_curr_pairing'], 'lower');
$pt_conf['gen']['btc_prim_exchange'] = $pt_var->auto_correct_str($pt_conf['gen']['btc_prim_exchange'], 'lower');
$pt_conf['dev']['log_verb'] = $pt_var->auto_correct_str($pt_conf['dev']['log_verb'], 'lower');
$pt_conf['gen']['default_theme'] = $pt_var->auto_correct_str($pt_conf['gen']['default_theme'], 'lower');
$pt_conf['gen']['prim_mcap_site'] = $pt_var->auto_correct_str($pt_conf['gen']['prim_mcap_site'], 'lower');
$pt_conf['comms']['price_alert_block_vol_error'] = $pt_var->auto_correct_str($pt_conf['comms']['price_alert_block_vol_error'], 'lower');
$pt_conf['dev']['remote_api_strict_ssl'] = $pt_var->auto_correct_str($pt_conf['dev']['remote_api_strict_ssl'], 'lower');
$pt_conf['gen']['asset_charts_toggle'] = $pt_var->auto_correct_str($pt_conf['gen']['asset_charts_toggle'], 'lower');
$pt_conf['comms']['proxy_alert'] = $pt_var->auto_correct_str($pt_conf['comms']['proxy_alert'], 'lower');
$pt_conf['comms']['proxy_alert_runtime'] = $pt_var->auto_correct_str($pt_conf['comms']['proxy_alert_runtime'], 'lower');
$pt_conf['comms']['proxy_alert_checkup_ok'] = $pt_var->auto_correct_str($pt_conf['comms']['proxy_alert_checkup_ok'], 'lower');


// Cleaning charts/alerts array
$cleaned_charts_and_price_alerts = array();
foreach ( $pt_conf['charts_alerts']['tracked_markets'] as $key => $val ) {
$cleaned_key = $pt_var->auto_correct_str($key, 'lower');
$cleaned_val = $pt_var->auto_correct_str($val, 'lower');
$cleaned_charts_and_price_alerts[$cleaned_key] = $cleaned_val;
}
$pt_conf['charts_alerts']['tracked_markets'] = $cleaned_charts_and_price_alerts;


// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $pt_conf['mob_net_txt_gateways'] as $key => $val ) {
$cleaned_key = $pt_var->auto_correct_str($key, 'lower');
$cleaned_val = $pt_var->auto_correct_str($val, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_val;
}
$pt_conf['mob_net_txt_gateways'] = $cleaned_mobile_networks;

// END CONFIG CLEANUP



// Dynamically reconfigure / configure where needed


// Default BTC CRYPTO/CRYPTO market pairing support, BEFORE GENERATING MISCASSETS ARRAY
// (so we activate it here instead of in Admin Config, for good UX adding ONLY altcoin markets dynamically there)
$pt_conf['power']['crypto_pairing'] = array('btc' => 'Ƀ ') + $pt_conf['power']['crypto_pairing']; // ADD TO #BEGINNING# OF ARRAY, FOR UX

// Numericly sort lite chart intervals (in case end user didn't do them in order)
// DO BEFORE ADDING 'all' BELOW
sort($pt_conf['power']['lite_chart_day_intervals']);

// Default lite chart mode 'all' (we activate it here instead of in Admin Config, for good UX adding ONLY day intervals there)
$pt_conf['power']['lite_chart_day_intervals'][] = 'all';


// Idiot-proof maximum of +-35 on captcha text contrast
if ( abs($pt_conf['power']['captcha_text_contrast']) > 35 ) {
$pt_conf['power']['captcha_text_contrast'] = 35;
}

// Idiot-proof maximum of 35 degrees on captcha text angle-offset
if ( abs($pt_conf['power']['captcha_text_angle']) > 35 || $pt_conf['power']['captcha_text_angle'] < 0 ) {
$pt_conf['power']['captcha_text_angle'] = 35;
}


// Dynamically add MISCASSETS to $pt_conf['assets'] BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $pt_conf['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
if ( is_array($pt_conf['assets']) ) {
    
    $pt_conf['assets']['MISCASSETS'] = array(
                                        'name' => 'Misc. '.strtoupper($pt_conf['gen']['btc_prim_curr_pairing']).' Value',
                                        'mcap_slug' => '',
                                        'pairing' => array()
                                        );
            
            
            foreach ( $pt_conf['power']['crypto_pairing'] as $pairing_key => $pairing_unused ) {
            $pt_conf['assets']['MISCASSETS']['pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            }
            
            foreach ( $pt_conf['power']['btc_curr_markets'] as $pairing_key => $pairing_unused ) {
            	
            	// WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE (cryptos are added via 'crypto_to_crypto_pairing')
            	if ( !array_key_exists($pairing_key, $pt_conf['power']['crypto_pairing']) ) {
            	$pt_conf['assets']['MISCASSETS']['pairing'][$pairing_key] = array('misc_assets' => $pairing_key);
            	}
            
            }
    
}



// Update dynamic mining data (DURING 'ui' ONLY), since we are using the json config in the secured cache
if ( $runtime_mode == 'ui' && is_array($pt_conf['power']['mining_calculators']) ) {
	

// BTC
$pt_conf['power']['mining_calculators']['pow']['btc']['height'] = $pt_api->bitcoin('height');
$pt_conf['power']['mining_calculators']['pow']['btc']['difficulty'] = $pt_api->bitcoin('difficulty');


// ETH
$pt_conf['power']['mining_calculators']['pow']['eth']['height'] = hexdec( $pt_api->etherscan('number') );      
$pt_conf['power']['mining_calculators']['pow']['eth']['difficulty'] = hexdec( $pt_api->etherscan('difficulty') );
$pt_conf['power']['mining_calculators']['pow']['eth']['other_network_data'] = '<p><b>Gas limit:</b> ' . number_format( hexdec( $pt_api->etherscan('gasLimit') ) ) . '</p>' . ( $pt_api->etherscan('number') == false ? '<p><a class="red" href="https://etherscan.io/apis/" target="_blank"><b>EtherScan.io (free) API key is required.</b></a></p>' : '' );

	
	// If a mining calculator is being used this runtime, include mining time formula calculations for that chain
	if ( isset($_POST['pow_calc']) ) {
				    
	$_POST['network_measure'] = $pt_var->rem_num_format($_POST['network_measure']);
				    
	$_POST['your_hashrate'] = $pt_var->rem_num_format($_POST['your_hashrate']);
		
	$miner_hashrate = trim($_POST['your_hashrate']) * trim($_POST['hash_level']);
	
		// Mining time formulas can be different per network, unless they copy Bitcoin's formula
		if ( $_POST['pow_calc'] == 'btc' ) {
		// https://en.bitcoin.it/wiki/Difficulty (How soon might I expect to generate a block?)
		$pt_conf['power']['mining_calculators']['pow']['btc']['mining_time_formula'] = trim($_POST['network_measure']) * pow(2, 32) / $miner_hashrate;
		}
		elseif ( $_POST['pow_calc'] == 'eth' ) {
		$pt_conf['power']['mining_calculators']['pow']['eth']['mining_time_formula'] = trim($_POST['network_measure']) / $miner_hashrate;
		}
	
	}
	

}


    
// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairings for UX
if ( is_array($pt_conf['assets']) ) {
    
    foreach ( $pt_conf['assets'] as $symbol_key => $symbol_unused ) {
            
            if ( $pt_conf['assets'][$symbol_key] == 'MISCASSETS' ) {
            asort($pt_conf['assets'][$symbol_key]['pairing']); // Sort maintaining indices
            }
            else {
            ksort($pt_conf['assets'][$symbol_key]['pairing']); // Sort by key name
            }
            
            
            foreach ( $pt_conf['assets'][$symbol_key]['pairing'] as $pairing_key => $pairing_unused ) {
            ksort($pt_conf['assets'][$symbol_key]['pairing'][$pairing_key]);
            }
        
    }
    
}


// Alphabetically sort news feeds
$usort_feeds_results = usort($pt_conf['power']['news_feed'], array('pt_gen', 'titles_usort_alpha') );
   	
if ( !$usort_feeds_results ) {
$pt_gen->app_logging( 'other_error', 'RSS feeds failed to sort alphabetically');
}


// Better decimal support for these vars...
$pt_conf['power']['system_stats_first_chart_highest_val'] = $pt_var->num_to_str($pt_conf['power']['system_stats_first_chart_highest_val']); 
$pt_conf['gen']['prim_curr_dec_max_thres'] = $pt_var->num_to_str($pt_conf['gen']['prim_curr_dec_max_thres']); 
$pt_conf['comms']['price_alert_thres'] = $pt_var->num_to_str($pt_conf['comms']['price_alert_thres']); 
$pt_conf['power']['hivepower_yearly_interest'] = $pt_var->num_to_str($pt_conf['power']['hivepower_yearly_interest']); 


// Backup archive password protection / encryption
if ( $pt_conf['gen']['backup_arch_pass'] != '' ) {
$backup_arch_pass = $pt_conf['gen']['backup_arch_pass'];
}
else {
$backup_arch_pass = false;
}


// Light chart config tracking / updating (checking for changes to lite chart app config, to trigger lite chart rebuilds)
$conf_lite_chart_structure = md5( serialize($pt_conf['power']['lite_chart_day_intervals']) . $pt_conf['power']['lite_chart_data_points_max'] );

if ( !file_exists($base_dir . '/cache/vars/lite_chart_structure.dat') ) {
$pt_cache->save_file($base_dir . '/cache/vars/lite_chart_structure.dat', $conf_lite_chart_structure);
$cached_lite_chart_structure = $conf_lite_chart_structure;
}
else {
$cached_lite_chart_structure = trim( file_get_contents($base_dir . '/cache/vars/lite_chart_structure.dat') );
}


// Check if we need to rebuild lite charts from changes to their structure
if ( $conf_lite_chart_structure != $cached_lite_chart_structure ) {
$pt_cache->remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/lite');
$pt_cache->remove_dir($base_dir . '/cache/charts/system/lite');
$pt_cache->save_file($base_dir . '/cache/vars/lite_chart_structure.dat', $conf_lite_chart_structure);
}


//////////////////////////////////////////////////////////////////
// END APP CONFIG DYNAMIC MANAGEMENT
//////////////////////////////////////////////////////////////////

?>
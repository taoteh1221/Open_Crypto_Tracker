<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



/////////////////////////////////////////////////
    	
    	
    	
if ( $debug_mode != 'off' ) {
error_reporting(1); // If debugging is enabled, turn on all PHP error reporting immediately after parsing config.php
}



// Currencies SUPPORTED BY coinmarketcap.com (coingecko.com can be determined differently)
$marketcap_currencies = array(
                                'USD',
                                'ALL',
                                'DZD',
                                'ARS',
                                'AMD',
                                'AUD',
                                'AZN',
                                'BHD',
                                'BDT',
                                'BYN',
                                'BMD',
                                'BOB',
                                'BAM',
                                'BRL',
                                'BGN',
                                'KHR',
                                'CAD',
                                'CLP',
                                'CNY',
                                'COP',
                                'CRC',
                                'HRK',
                                'CUP',
                                'CZK',
                                'DKK',
                                'DOP',
                                'EGP',
                                'EUR',
                                'GEL',
                                'GHS',
                                'GTQ',
                                'HNL',
                                'HKD',
                                'HUF',
                                'ISK',
                                'INR',
                                'IDR',
                                'IRR',
                                'IQD',
                                'ILS',
                                'JMD',
                                'JPY',
                                'JOD',
                                'KZT',
                                'KES',
                                'KWD',
                                'KGS',
                                'LBP',
                                'MKD',
                                'MYR',
                                'MUR',
                                'MXN',
                                'MDL',
                                'MNT',
                                'MAD',
                                'MMK',
                                'NAD',
                                'NPR',
                                'TWD',
                                'NZD',
                                'NIO',
                                'NGN',
                                'NOK',
                                'OMR',
                                'PKR',
                                'PAB',
                                'PEN',
                                'PHP',
                                'PLN',
                                'GBP',
                                'QAR',
                                'RON',
                                'RUB',
                                'SAR',
                                'RSD',
                                'SGD',
                                'ZAR',
                                'KRW',
                                'SSP',
                                'VES',
                                'LKR',
                                'SEK',
                                'CHF',
                                'THB',
                                'TTD',
                                'TND',
                                'TRY',
                                'UGX',
                                'UAH',
                                'AED',
                                'UYU',
                                'UZS',
                                'VND',
										);



// Clear stale LOGS / MARKETS / CHAIN DATA API data from cache (run daily, or if runtime is cron)
if ( update_cache_file('cache/events/clean_cache.dat', (60 * 24) ) == true || $runtime_mode == 'cron' ) {
	

// Daily cleanup
delete_old_files($base_dir . '/cache/apis/', 1, 'dat'); // Delete MARKETS / CHAIN DATA API cache files older than 1 day


// $purge_logs time cleanup
$logs_cache_cleanup = array(
									$base_dir . '/cache/logs/debugging/api/',
									$base_dir . '/cache/logs/errors/api/',
									);
									
delete_old_files($logs_cache_cleanup, $purge_logs, 'dat'); // Delete LOGS API cache files older than $purge_logs day(s)


store_file_contents($base_dir . '/cache/events/clean_cache.dat', time());

}



// Default BTC CRYPTO/CRYPTO market pairing support, BEFORE GENERATING MISCASSETS ARRAY
// (so we activate it here instead of in config.php, for good UX adding altcoin markets dynamically there)
// Add to beginning of the array
$crypto_to_crypto_pairing = array_merge( array('btc' => 'Ƀ ') , $crypto_to_crypto_pairing);



// Dynamically add MISCASSETS to $coins_list BEFORE ALPHABETICAL SORTING
// ONLY IF USER HASN'T MESSED UP $coins_list, AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
if (is_array($coins_list) || is_object($coins_list)) {
    
    $coins_list['MISCASSETS'] = array(
                                        'coin_name' => 'Misc. '.strtoupper($btc_fiat_pairing).' Value',
                                        'marketcap_website_slug' => '',
                                        'market_pairing' => array()
                                        );
            
            
            foreach ( $crypto_to_crypto_pairing as $pairing_key => $pairing_unused ) {
            $coins_list['MISCASSETS']['market_pairing'][$pairing_key] = array('fiat_assets' => $pairing_key);
            }
            
            foreach ( $fiat_currencies as $pairing_key => $pairing_unused ) {
            $coins_list['MISCASSETS']['market_pairing'][$pairing_key] = array('fiat_assets' => $pairing_key);
            }
    
}


    
// !!BEFORE MANIPULATING ANYTHING ELSE!!, alphabetically sort all exchanges / pairings for UX
if (is_array($coins_list) || is_object($coins_list)) {
    
    foreach ( $coins_list as $symbol_key => $symbol_unused ) {
            
            if ( $coins_list[$symbol_key] == 'MISCASSETS' ) {
            asort($coins_list[$symbol_key]['market_pairing']);
            }
            else {
            ksort($coins_list[$symbol_key]['market_pairing']);
            }
            
            
            foreach ( $coins_list[$symbol_key]['market_pairing'] as $pairing_key => $pairing_unused ) {
            ksort($coins_list[$symbol_key]['market_pairing'][$pairing_key]);
            }
        
    }
    
}
    
    
    
// Clean / auto-correct $btc_fiat_pairing and $btc_exchange BEFORE BELOW CHARTS/ALERTS LOGIC
$btc_fiat_pairing = cleanup_config($btc_fiat_pairing, 'lower');
$btc_exchange = cleanup_config($btc_exchange, 'lower');



// Get chart/alert defaults before default Bitcoin market is dynamically manipulated
// We NEVER change BTC / currency_market value FOR CHARTS/ALERTS, 
// so move the default $btc_fiat_pairing / $btc_exchange values into their own chart/alerts related variables,
// before dynamic updating of $btc_fiat_pairing / $btc_exchange
$charts_alerts_btc_fiat_pairing = $btc_fiat_pairing; 
$charts_alerts_btc_exchange = $btc_exchange;



// If Stand-Alone Fiat Currency Market has been enabled (Settings page), REPLACE/OVERWRITE Bitcoin market config defaults
if ( $_POST['fiat_market_standalone'] || $_COOKIE['fiat_market_standalone'] ) {
$fiat_market_standalone = explode("|", ( $_POST['fiat_market_standalone'] != '' ? $_POST['fiat_market_standalone'] : $_COOKIE['fiat_market_standalone'] ) );
$btc_fiat_pairing = $fiat_market_standalone[0]; // MUST RUN !BEFORE! btc_market() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR btc_market() CALL
$btc_exchange = btc_market($fiat_market_standalone[1] - 1);
}



// Set BTC / currency_market dynamic value, IF $fiat_market_standalone NOT SET

if ( sizeof($fiat_market_standalone) != 2 && $_SESSION['btc_fiat_pairing'] ) {
$btc_fiat_pairing = $_SESSION['btc_fiat_pairing'];
}

if ( sizeof($fiat_market_standalone) != 2 && $_SESSION['btc_exchange'] ) {
$btc_exchange = $_SESSION['btc_exchange'];
}



// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in config.php)

// Cleaning lowercase alphanumeric string values
$debug_mode = cleanup_config($debug_mode, 'lower');
$btc_fiat_pairing = cleanup_config($btc_fiat_pairing, 'lower');
$btc_exchange = cleanup_config($btc_exchange, 'lower');
$marketcap_site = cleanup_config($marketcap_site, 'lower');
$block_volume_error = cleanup_config($block_volume_error, 'lower');
$api_strict_ssl = cleanup_config($api_strict_ssl, 'lower');
$charts_page = cleanup_config($charts_page, 'lower');
$smtp_secure = cleanup_config($smtp_secure, 'lower');
$proxy_alerts = cleanup_config($proxy_alerts, 'lower');
$proxy_alerts_runtime = cleanup_config($proxy_alerts_runtime, 'lower');
$proxy_checkup_ok = cleanup_config($proxy_checkup_ok, 'lower');

// Cleaning charts/alerts array
$cleaned_asset_charts_and_alerts = array();
foreach ( $asset_charts_and_alerts as $key => $value ) {
$cleaned_key = cleanup_config($key, 'lower');
$cleaned_value = cleanup_config($value, 'lower');
$cleaned_asset_charts_and_alerts[$cleaned_key] = $cleaned_value;
}
$asset_charts_and_alerts = $cleaned_asset_charts_and_alerts;

// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $mobile_networks as $key => $value ) {
$cleaned_key = cleanup_config($key, 'lower');
$cleaned_value = cleanup_config($value, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_value;
}
$mobile_networks = $cleaned_mobile_networks;

// END CONFIG CLEANUP



// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
$selected_pairing_id = $coins_list['BTC']['market_pairing'][$btc_fiat_pairing][$btc_exchange];
$btc_fiat_value = asset_market_data('BTC', $btc_exchange, $selected_pairing_id)['last_trade'];

$charts_alerts_selected_pairing_id = $coins_list['BTC']['market_pairing'][$charts_alerts_btc_fiat_pairing][$charts_alerts_btc_exchange];
$charts_alerts_btc_fiat_value = asset_market_data('BTC', $charts_alerts_btc_exchange, $charts_alerts_selected_pairing_id)['last_trade'];

// Log any Bitcoin market errors
if ( !isset($btc_fiat_value) || $btc_fiat_value == 0 ) {
app_logging('other_error', 'config-init.php Bitcoin fiat value not properly set', 'exchange: ' . $btc_exchange . '; pairing_id: ' . $selected_pairing_id . '; value: ' . $btc_fiat_value );
}

// Log any charts/alerts Bitcoin market errors
if ( !isset($charts_alerts_btc_fiat_value) || $charts_alerts_btc_fiat_value == 0 ) {
app_logging('other_error', 'config-init.php Charts / alerts Bitcoin fiat value not properly set', 'exchange: ' . $charts_alerts_btc_exchange . '; pairing_id: ' . $charts_alerts_selected_pairing_id . '; value: ' . $charts_alerts_btc_fiat_value );
}
	


// Better decimal support for price change percent config
$asset_price_alerts_percent = float_to_string($asset_price_alerts_percent); 



// Only need below logic during UI runtime
if ( $runtime_mode == 'ui' ) {


	// We can safely dismiss alerts with cookies enabled, without losing data
	if ( $_COOKIE['coin_amounts'] != '' ) {
	$dismiss_alert = ' <br /><br /><a href="'.start_page($_GET['start_page'], 'href').'">Dismiss Alert</a>';
	}
		
	
	// If CSV file import is in process, check it
	if ( $_POST['csv_check'] == 1 ) {
		
		
		// Checks and importing
		if ( $_FILES['csv_file']['tmp_name'] != NULL ) {
		$csv_file_array = csv_file_array($_FILES['csv_file']['tmp_name']);
   	}
   	else {
   	$csv_import_fail = 'You forgot to select your CSV import file.' . $dismiss_alert;
   	}
   	
   	
		if ( !$csv_import_fail && !is_array($csv_file_array) ) {
   	$csv_import_fail = 'Your CSV import file does not appear to be formatted correctly. You can <a href="download.php?example_template=1" target="_blank">download this example template</a> to start over with correct formatting.' . $dismiss_alert;
   	}
		elseif ( is_array($csv_file_array) ) {
   	$csv_import_succeed = 'Your CSV import succeeded.' . $dismiss_alert;
   	}
   	
   	if ( !$csv_import_fail && $_POST['csv_check'] == 1 ) {
   	$run_csv_import = 1;
   	}
   
	}
	
	
// Now that $run_csv_import has been determined, we can call our cookie logic
require_once( $base_dir . "/app-lib/php/other/cookies.php");


$marketcap_site = ( $alert_percent[0] != '' ? $alert_percent[0] : $marketcap_site );


}




// Chart data cache directory creation (if needed)
							
// Structure of lite charts sub-directories
$lite_charts_structure = array(
									'1_day',
									'1_week',
									'1_month',
									'3_months',
									'6_months',
									'1_year',
									'2_years',
									'4_years',
									'all',
									);

// ALL CHARTS FOR SPOT PRICE / 24 HOUR VOLUME
foreach ( $asset_charts_and_alerts as $key => $value ) {

	// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
	$asset_dir = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
	$asset_dir = strtoupper($asset_dir);
		
	$asset_cache_params = explode("||", $value);
	
	if ( $asset_cache_params[2] == 'chart' || $asset_cache_params[2] == 'both' ) {
		
		// Archival charts
		if ( dir_structure($base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset_dir.'/') != TRUE ) { // Attempt to create directory if it doesn't exist
		$disabled_caching = 1;
		}
		
		// Lite charts
		foreach( $lite_charts_structure as $lite_chart ) {
			
			if ( dir_structure($base_dir . '/cache/charts/spot_price_24hr_volume/lite/'.$lite_chart.'/'.$asset_dir.'/') != TRUE ) { // Attempt to create directory if it doesn't exist
			$disabled_caching = 1;
			}
			
		}
	
	}
	
}

// LITE CHARTS FOR SYSTEM STATS
foreach( $lite_charts_structure as $lite_chart ) {
			
	if ( dir_structure($base_dir . '/cache/charts/system/lite/'.$lite_chart.'/') != TRUE ) { // Attempt to create directory if it doesn't exist
	$disabled_caching = 1;
	}
			
}

if ( $disabled_caching == 1 ) {
echo "Improper directory permissions on the '/cache/charts/' sub-directories, cannot create new sub-directories. Make sure the folder '/cache/charts/' AND ANY SUB-DIRECTORIES IN IT have read / write permissions (and further sub-directories WITHIN THESE should be created automatically)";
exit;
}



// Configuration checks
require_once( $base_dir . "/app-lib/php/other/config-checks.php");



// User agent
if ( sizeof($proxy_list) > 0 ) {
$user_agent = 'Mozilla/5.0 (compatible; API_Endpoint_Parser;) Gecko Firefox';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Mozilla/5.0 ('.( isset($system_info['operating_system']) ? $system_info['operating_system'] : 'compatible' ).'; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; Curl/' .$curl_setup["version"]. '; DFD_Cryptocoin_Values/' . $app_version . '; API_Endpoint_Parser; +https://github.com/taoteh1221/DFD_Cryptocoin_Values) Gecko Firefox';
}



// SMTP email setup
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $smtp_login != '' && $smtp_server != '' ) {

require_once( dirname(__FILE__) . '/classes/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config file structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = smtp_vars();
global $smtp_vars; // Needed for class compatibility (along with second instance in the class config_smtp.php file)

// Initiation of the 3rd party SMTP class
$smtp = new SMTPMailer();

}



// Re-check the average time interval between chart data points, once every 24 hours
// If we just started collecting data, check frequently
// (placeholder is always set to 1 to keep chart buttons from acting weird until we have enough data)
if ( $charts_page == 'on' && update_cache_file('cache/vars/chart_interval.dat', (60 * 24) ) == true
|| !is_numeric(trim(file_get_contents('cache/vars/chart_interval.dat'))) || trim(file_get_contents('cache/vars/chart_interval.dat')) == 1 ) {  
	
	foreach ( $asset_charts_and_alerts as $key => $value ) {
	
		if ( trim($find_first_filename) == '' ) {
			
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$find_first_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$find_first_asset = strtoupper($find_first_asset);
	
		$find_first_chart = explode("||", $value);

			if ( $find_first_chart[2] == 'both' || $find_first_chart[2] == 'chart' ) {
			$find_first_filename = 'cache/charts/spot_price_24hr_volume/archival/'.$find_first_asset.'/'.$key.'_chart_'.$find_first_chart[1].'.dat';
			}

		}
		
	}

// Dynamically determine average time interval with the last 500 lines (or max available if less), presume an average max characters length of ~40
$charts_update_freq = chart_time_interval($find_first_filename, 500, 40);

store_file_contents($base_dir . '/cache/vars/chart_interval.dat', $charts_update_freq);

}



// Chart update frequency
$charts_update_freq = ( $charts_update_freq != '' ? $charts_update_freq : trim( file_get_contents('cache/vars/chart_interval.dat') ) );



// Unit tests to run in debug mode, !AFTER! loading init / config-init logic
if ( $debug_mode != 'off' ) {
require_once("app-lib/php/debugging/tests.php");
}


?>
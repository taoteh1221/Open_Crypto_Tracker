<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



$app_version = '4.08.0';  // 2020/JANUARY/23RD



// Make sure we have a PHP version id
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}



///////////////////// I N I T /////////////////////////////////////////////////



// Check for runtime mode
if ( !$runtime_mode )  {
echo 'No runtime mode detected, running without runtime mode set is forbidden. <br /><br />';
$force_exit = 1;
}



// PHP v5.5 or higher required for this app
if (PHP_VERSION_ID < 50500) {
echo 'PHP version 5.5 or higher is required (PHP 7.0 OR HIGHER IS ---HIGHLY RECOMMENDED--- FOR UNICODE SUPPORT). Please upgrade your PHP version to run this application. <br /><br />';
$force_exit = 1;
}



// Check for curl
if ( !function_exists('curl_version') ) {
echo "Curl for PHP (version ID ".PHP_VERSION_ID.") is not installed yet. Curl is required to run this application. <br /><br />";
$force_exit = 1;
}
else {
$curl_setup = curl_version();
define('CURL_VERSION_ID', str_replace(".", "", $curl_setup["version"]) );
}



// Check for mbstring
if ( !extension_loaded('mbstring') ) {
echo "PHP extension 'mbstring' not installed. 'mbstring' is required to run this application. <br /><br />";
$force_exit = 1;
}



// Check for required Apache modules
$apache_modules = apache_get_modules(); // Minimize function calls

// Check for mod_rewrite
if ( !in_array('mod_rewrite', $apache_modules) ) {
echo "HTTP server Apache module 'mod_rewrite' not installed. 'mod_rewrite' is required to run this application. <br /><br />";
$force_exit = 1;
}

// Check for mod_ssl
if ( !in_array('mod_ssl', $apache_modules) ) {
echo "HTTP server Apache module 'mod_ssl' not installed. 'mod_ssl' is required to run this application. <br /><br />";
$force_exit = 1;
}



// Exit, if server / app setup requirements not met
if ( $force_exit == 1 ) {
echo 'Server / app setup requirements not met (SEE ABOVE SETUP DEFICIENCIES), exiting application...';
exit;
}



// If debugging is enabled, turn on all PHP error reporting immediately after parsing config.php
if ( $app_config['debug_mode'] != 'off' ) {
error_reporting(1); 
}



// PHP defaults
date_default_timezone_set('UTC'); // Set time as UTC for logs etc ($app_config['local_time_offset'] in config.php can adjust UI / UX timestamps as needed)
ini_set('auto_detect_line_endings',TRUE); // Mac compatibility with CSV spreadsheet importing



hardy_session_clearing(); // Try to avoid edge-case bug where sessions didn't delete last runtime
session_start(); // New session start


// Create a few global runtime vars now
$api_runtime_cache = array();
$_SESSION['proxy_checkup'] = array();



// Register the base directory
$base_dir = preg_replace("/\/app-lib(.*)/i", "", dirname(__FILE__) );



// Get WEBSERVER runtime user (from cache if currently running from CLI)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$http_runtime_user = ( $runtime_mode == 'ui' ? posix_getpwuid(posix_geteuid())['name'] : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );
$http_runtime_user = trim($http_runtime_user);
					

// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$possible_http_users = array(
						'www-data',
						'apache',
						'apache2',
						'httpd',
						'httpd2',
							);



// We can create cache directories (if needed), with $http_runtime_user determined further above 
// (for cache compatibility on certain PHP setups)

// Check for cache sub-directory creation, create if needed...if it fails, exit and alert end-user
if ( dir_structure($base_dir . '/cache/alerts/') != TRUE
|| dir_structure($base_dir . '/cache/apis/') != TRUE
|| dir_structure($base_dir . '/cache/charts/spot_price_24hr_volume/archival/') != TRUE
|| dir_structure($base_dir . '/cache/charts/spot_price_24hr_volume/lite/') != TRUE
|| dir_structure($base_dir . '/cache/charts/system/archival/') != TRUE
|| dir_structure($base_dir . '/cache/charts/system/lite/') != TRUE
|| dir_structure($base_dir . '/cache/events/') != TRUE
|| dir_structure($base_dir . '/cache/logs/debugging/api/') != TRUE
|| dir_structure($base_dir . '/cache/logs/errors/api/') != TRUE
|| dir_structure($base_dir . '/cache/secured/backups/') != TRUE
|| dir_structure($base_dir . '/cache/secured/messages/') != TRUE
|| dir_structure($base_dir . '/cache/vars/') != TRUE ) {
echo "Cannot create cache sub-directories. Please make sure the folder '/cache/' has FULL read / write permissions (chmod 777 on unix / linux systems), so the cache sub-directories can be created automatically.";
exit;
}



// Get system info for debugging / Raspberry Pi system load and temperature
$system_info = system_info(); // MUST RUN AFTER SETTING $base_dir, AND AFTER CREATING CACHE DIRECTORY STRUCTURE!



// Raspberry Pi device?
if ( preg_match("/raspberry/i", $system_info['model']) ) {
$is_raspi = 1;
}



// Only need below logic during UI runtime
if ( $runtime_mode == 'ui' ) {
	

	// Have UI / HTTP runtime mode cache the runtime_user data every 24 hours, since CLI runtime cannot determine the UI / HTTP runtime_user 
	if ( update_cache_file('cache/vars/http_runtime_user.dat', (60 * 24) ) == true ) {
	store_file_contents($base_dir . '/cache/vars/http_runtime_user.dat', $http_runtime_user);
	}

	// Have UI runtime mode cache the app URL data every 24 hours, since CLI runtime cannot determine the app URL (for sending backup link emails during backups, etc)
	if ( update_cache_file('cache/vars/app_url.dat', (60 * 24) ) == true ) {
	$base_url = base_url();
	store_file_contents($base_dir . '/cache/vars/app_url.dat', $base_url);
	}
	

	if ( $_COOKIE['theme_selected'] != NULL ) {
	$theme_selected = $_COOKIE['theme_selected'];
	}
	elseif ( $_POST['theme_selected'] != NULL ) {
	$theme_selected = $_POST['theme_selected'];
	}
	else {
	$theme_selected = 'dark';
	}
	// Sanitizing $theme_selected is very important, as we are calling external files with the value
	if ( $theme_selected != 'light' && $theme_selected != 'dark' ) {
	app_logging('security_error', 'Injected theme path value attack', 'Requested theme value: "' . $theme_selected . '";');
	error_logs();
	exit;
	}


$sort_settings = ( $_COOKIE['sort_by'] ? $_COOKIE['sort_by'] : $_POST['sort_by'] );
$sort_settings = explode("|",$sort_settings);

$sorted_by_col = $sort_settings[0];
$sorted_by_asc_desc = $sort_settings[1];

	if ( !$sorted_by_col ) {
	$sorted_by_col = 0;
	}
	if ( !$sorted_by_asc_desc ) {
	$sorted_by_asc_desc = 0;
	}


$alert_percent = explode("|", ( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] ) );


$show_charts = explode(',', rtrim( ( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] ) , ',') );

	
}
	


// Base URL, that even works during CLI runtime (horray)
$base_url = ( $base_url != '' ? $base_url : trim( file_get_contents('cache/vars/app_url.dat') ) );



// Security (MUST run AFTER directory structure creation check, and AFTER config file)
require_once($base_dir . '/app-lib/php/other/security/directory.php');



// SECURED cache files management (MUST run AFTER directory structure creation check, and AFTER config file)
require_once($base_dir . '/app-lib/php/other/security/secure-cache-files.php');



// Password protection management (MUST run AFTER directory structure creation check, and AFTER config file AND secure cache files)
require_once($base_dir . '/app-lib/php/other/security/password-protection.php');


// Load coinmarketcap supported currencies
require_once("app-lib/php/other/coinmarketcap-currencies.php");



// Scheduled maintenance (run ~daily, or ~hourly if runtime is cron)
if ( update_cache_file('cache/events/scheduled_maintenance.dat', (60 * 24) ) == true 
|| $runtime_mode == 'cron' && update_cache_file('cache/events/scheduled_maintenance.dat', (60 * 1) ) == true ) {
	

	// Stuff to run only if cron is setup and running
	if ( $runtime_mode == 'cron' ) {
	
		// Chart backups...run before any price checks to avoid any potential file lock issues
		if ( $app_config['charts_page'] == 'on' && $app_config['charts_backup_freq'] > 0 ) {
		backup_archive('charts-data', $base_dir . '/cache/charts/', $app_config['charts_backup_freq']);
		}
	
	}

	
// Delete ANY old zip archive backups scheduled to be purged
delete_old_files($base_dir . '/cache/secured/backups/', $app_config['delete_old_backups'], 'zip');


// Stale cache files cleanup
delete_old_files($base_dir . '/cache/apis/', 1, 'dat'); // Delete MARKETS / CHAIN DATA API cache files older than 1 day


// Secondary logs cleanup
$logs_cache_cleanup = array(
									$base_dir . '/cache/logs/debugging/api/',
									$base_dir . '/cache/logs/errors/api/',
									);
									
delete_old_files($logs_cache_cleanup, $app_config['purge_logs'], 'dat'); // Delete LOGS API cache files older than $app_config['purge_logs'] day(s)


// Update the maintenance event tracking
store_file_contents($base_dir . '/cache/events/scheduled_maintenance.dat', time_date_format(false, 'pretty_date_time') );

}



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
$app_config['btc_primary_currency_pairing'] = cleanup_config($app_config['btc_primary_currency_pairing'], 'lower');
$app_config['btc_primary_exchange'] = cleanup_config($app_config['btc_primary_exchange'], 'lower');



// Get chart/alert defaults before default Bitcoin market is dynamically manipulated
// We NEVER change BTC / currency_market value FOR CHARTS/ALERTS, 
// so move the default $app_config['btc_primary_currency_pairing'] / $app_config['btc_primary_exchange'] values into their own chart/alerts related variables,
// before dynamic updating of $app_config['btc_primary_currency_pairing'] / $app_config['btc_primary_exchange']
$default_btc_primary_currency_pairing = $app_config['btc_primary_currency_pairing']; 
$default_btc_primary_exchange = $app_config['btc_primary_exchange'];



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

if ( sizeof($primary_currency_market_standalone) != 2 && $_SESSION['btc_primary_currency_pairing'] ) {
$app_config['btc_primary_currency_pairing'] = $_SESSION['btc_primary_currency_pairing'];
}

if ( sizeof($primary_currency_market_standalone) != 2 && $_SESSION['btc_primary_exchange'] ) {
$app_config['btc_primary_exchange'] = $_SESSION['btc_primary_exchange'];
}



// START CONFIG CLEANUP (auto-correct any basic end user data entry errors in config.php)

// Cleaning lowercase alphanumeric string values
$app_config['debug_mode'] = cleanup_config($app_config['debug_mode'], 'lower');
$app_config['btc_primary_currency_pairing'] = cleanup_config($app_config['btc_primary_currency_pairing'], 'lower');
$app_config['btc_primary_exchange'] = cleanup_config($app_config['btc_primary_exchange'], 'lower');
$app_config['primary_marketcap_site'] = cleanup_config($app_config['primary_marketcap_site'], 'lower');
$app_config['block_alerts_volume_error'] = cleanup_config($app_config['block_alerts_volume_error'], 'lower');
$app_config['api_strict_ssl'] = cleanup_config($app_config['api_strict_ssl'], 'lower');
$app_config['charts_page'] = cleanup_config($app_config['charts_page'], 'lower');
$app_config['smtp_secure'] = cleanup_config($app_config['smtp_secure'], 'lower');
$app_config['proxy_alerts'] = cleanup_config($app_config['proxy_alerts'], 'lower');
$app_config['proxy_alerts_runtime'] = cleanup_config($app_config['proxy_alerts_runtime'], 'lower');
$app_config['proxy_checkup_ok'] = cleanup_config($app_config['proxy_checkup_ok'], 'lower');

// Cleaning charts/alerts array
$cleaned_asset_charts_and_alerts = array();
foreach ( $app_config['asset_charts_and_alerts'] as $key => $value ) {
$cleaned_key = cleanup_config($key, 'lower');
$cleaned_value = cleanup_config($value, 'lower');
$cleaned_asset_charts_and_alerts[$cleaned_key] = $cleaned_value;
}
$app_config['asset_charts_and_alerts'] = $cleaned_asset_charts_and_alerts;

// Cleaning mobile networks array
$cleaned_mobile_networks = array();
foreach ( $app_config['mobile_network_text_gateways'] as $key => $value ) {
$cleaned_key = cleanup_config($key, 'lower');
$cleaned_value = cleanup_config($value, 'lower');
$cleaned_mobile_networks[$cleaned_key] = $cleaned_value;
}
$app_config['mobile_network_text_gateways'] = $cleaned_mobile_networks;

// END CONFIG CLEANUP



// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
$selected_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']][$app_config['btc_primary_exchange']];
$btc_primary_currency_value = asset_market_data('BTC', $app_config['btc_primary_exchange'], $selected_pairing_id)['last_trade'];

$default_btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing][$default_btc_primary_exchange];
$default_btc_primary_currency_value = asset_market_data('BTC', $default_btc_primary_exchange, $default_btc_pairing_id)['last_trade'];


// Log any Bitcoin market errors
if ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']] ) {
app_logging('other_error', 'init.php btc_primary_currency_pairing variable not properly set', 'btc_primary_currency_pairing: ' . $app_config['btc_primary_currency_pairing'] . ';' );
}
elseif ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']][$app_config['btc_primary_exchange']] ) {
app_logging('other_error', 'init.php btc_primary_exchange variable not properly set', 'btc_primary_exchange: ' . $app_config['btc_primary_exchange'] . ';' );
}

if ( !isset($btc_primary_currency_value) || $btc_primary_currency_value == 0 ) {
app_logging('other_error', 'init.php Bitcoin primary currency market value not properly set', 'btc_primary_currency_pairing: ' . $app_config['btc_primary_currency_pairing'] . '; exchange: ' . $app_config['btc_primary_exchange'] . '; pairing_id: ' . $selected_pairing_id . '; value: ' . $btc_primary_currency_value );
}


// Log any charts/alerts Bitcoin market errors
if ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing] ) {
app_logging('other_error', 'init.php Charts / alerts btc_primary_currency_pairing variable not properly set', 'btc_primary_currency_pairing: ' . $default_btc_primary_currency_pairing . ';' );
}
elseif ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing][$default_btc_primary_exchange] ) {
app_logging('other_error', 'init.php Charts / alerts btc_primary_exchange variable not properly set', 'btc_primary_exchange: ' . $default_btc_primary_exchange . ';' );
}

if ( !isset($default_btc_primary_currency_value) || $default_btc_primary_currency_value == 0 ) {
app_logging('other_error', 'init.php Charts / alerts Bitcoin primary currency market value not properly set', 'btc_primary_currency_pairing: ' . $default_btc_primary_currency_pairing . '; exchange: ' . $default_btc_primary_exchange . '; pairing_id: ' . $default_btc_pairing_id . '; value: ' . $default_btc_primary_currency_value );
}



// Better decimal support for price change percent config
$app_config['asset_price_alerts_percent'] = float_to_string($app_config['asset_price_alerts_percent']); 



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


$app_config['primary_marketcap_site'] = ( $alert_percent[0] != '' ? $alert_percent[0] : $app_config['primary_marketcap_site'] );


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
foreach ( $app_config['asset_charts_and_alerts'] as $key => $value ) {

	// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
	$asset_dir = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
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
if ( sizeof($app_config['proxy_list']) > 0 ) {
$user_agent = 'Mozilla/5.0 (compatible; API_Endpoint_Parser;) Gecko Firefox';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Mozilla/5.0 ('.( isset($system_info['operating_system']) ? $system_info['operating_system'] : 'compatible' ).'; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; Curl/' .$curl_setup["version"]. '; DFD_Cryptocoin_Values/' . $app_version . '; API_Endpoint_Parser; +https://github.com/taoteh1221/DFD_Cryptocoin_Values) Gecko Firefox';
}



// SMTP email setup
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $app_config['smtp_login'] != '' && $app_config['smtp_server'] != '' ) {

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
if ( $app_config['charts_page'] == 'on' && update_cache_file('cache/vars/chart_interval.dat', (60 * 24) ) == true
|| !is_numeric(trim(file_get_contents('cache/vars/chart_interval.dat'))) || trim(file_get_contents('cache/vars/chart_interval.dat')) == 1 ) {  
	
	foreach ( $app_config['asset_charts_and_alerts'] as $key => $value ) {
	
		if ( trim($find_first_filename) == '' ) {
			
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$find_first_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
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



// Unit tests to run in debug mode, !AFTER! loading init logic
if ( $app_config['debug_mode'] != 'off' ) {
require_once("app-lib/php/other/debugging/tests.php");
require_once("app-lib/php/other/debugging/exchange-and-pairing-info.php");
}


?>
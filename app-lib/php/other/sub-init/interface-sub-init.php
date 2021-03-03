<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic during UI runtime
//////////////////////////////////////////////////////////////////
if ( $runtime_mode == 'ui' ) {
	

$alert_percent = explode("|", ( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] ) );

$app_config['general']['primary_marketcap_site'] = ( $alert_percent[0] != '' ? $alert_percent[0] : $app_config['general']['primary_marketcap_site'] );


///////////////////////////////////////////////////////////////////////


$show_crypto_value = explode(',', rtrim( ( $_POST['show_crypto_value'] != '' ? $_POST['show_crypto_value'] : $_COOKIE['show_crypto_value'] ) , ',') );

		
		// Remove any stale crypto value
		$temp_show_crypto_value = array();
		$scan_crypto_value = $show_crypto_value;
		$scan_crypto_value = array_map('strip_brackets', $scan_crypto_value); // Strip brackets
		$loop = 0;
		foreach ($scan_crypto_value as $key) {
			if ( array_key_exists($key, $app_config['power_user']['crypto_pairing']) ) {
			$temp_show_crypto_value[$loop] = $show_crypto_value[$loop];
			}
		$loop = $loop + 1;
		}
		$show_crypto_value = $temp_show_crypto_value;
		$implode_crypto_value = implode(',', $show_crypto_value) . ',';
	
		// Update POST and / or COOKIE data too
		if( $_POST['show_crypto_value'] ) {
		$_POST['show_crypto_value'] = $implode_crypto_value;
		}
	
		if( $_COOKIE['show_crypto_value'] ) {
		store_cookie_contents("show_crypto_value", $implode_crypto_value, mktime()+31536000);
		}
	

///////////////////////////////////////////////////////////////////////


$show_secondary_trade_value = ( $_POST['show_secondary_trade_value'] != '' ? $_POST['show_secondary_trade_value'] : $_COOKIE['show_secondary_trade_value'] );

	// Remove any stale secondary trade value
	if ( !array_key_exists($show_secondary_trade_value, $app_config['power_user']['crypto_pairing']) ) {
	$show_secondary_trade_value = null;
	$_POST['show_secondary_trade_value'] = null;  
	store_cookie_contents("show_secondary_trade_value", "", time()-3600);  
	unset($_COOKIE['show_secondary_trade_value']);  
	}


///////////////////////////////////////////////////////////////////////


$show_feeds = explode(',', rtrim( ( $_POST['show_feeds'] != '' ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] ) , ',') );

	// Alphabetically order AND remove stale feeds
	// (since we already alphabetically ordered $app_config['power_user']['news_feeds'] in app-config-management.php BEFOREHAND)
	$temp_show_feeds = array();
	$scan_feeds = $show_feeds;
	$scan_feeds = array_map('strip_brackets', $scan_feeds); // Strip brackets
	foreach ($app_config['power_user']['news_feeds'] as $feed) {
	$feed_id = get_digest($feed["title"], 10);
		if ( in_array($feed_id, $scan_feeds) ) {
		$temp_show_feeds[] = '[' . $feed_id . ']';
		}
	}
	$show_feeds = $temp_show_feeds;
	$implode_feeds = implode(',', $show_feeds) . ',';
	
	// Update POST and / or COOKIE data too
	if( $_POST['show_feeds'] ) {
	$_POST['show_feeds'] = $implode_feeds;
	}
	
	if( $_COOKIE['show_feeds'] ) {
	store_cookie_contents("show_feeds", $implode_feeds, mktime()+31536000);
	}


///////////////////////////////////////////////////////////////////////


	// Only set from cookie / post values if charts are enabled
	if ( $app_config['general']['asset_charts_toggle'] == 'on' ) {
		
	$show_charts = explode(',', rtrim( ( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] ) , ',') );
		
		// Remove stale charts
		$temp_show_charts = array();
		$scan_charts = $show_charts;
		$scan_charts = array_map('strip_brackets', $scan_charts); // Strip brackets
		$scan_charts = array_map('strip_underscore_and_after', $scan_charts); // Strip underscore, and everything after
		$loop = 0;
		foreach ($scan_charts as $market_key) {
			
			// IF asset exists in charts app config, AND $show_charts UI key format is latest iteration (fiat conversion charts USED TO have no underscore)
			if ( array_key_exists($market_key, $app_config['charts_alerts']['tracked_markets']) && stristr($show_charts[$loop], '_') ) {
				
			$chart_params = explode('_', strip_brackets($show_charts[$loop]) );
			
			$chart_config_check = explode('||', $app_config['charts_alerts']['tracked_markets'][$market_key]);
				
				// If pairing properly matches OR it's a conversion chart, we're good to keep this $show_charts array value 
				if ( $chart_params[1] == $chart_config_check[1] || $chart_params[1] == $app_config['general']['btc_primary_currency_pairing'] ) {
				$temp_show_charts[$loop] = $show_charts[$loop];
				}
				
			}
			
		$loop = $loop + 1;
		
		}
		$show_charts = $temp_show_charts;
		$implode_charts = implode(',', $show_charts) . ',';
	
		// Update POST and / or COOKIE data too
		if( $_POST['show_charts'] ) {
		$_POST['show_charts'] = $implode_charts;
		}
	
		if( $_COOKIE['show_charts'] ) {
		store_cookie_contents("show_charts", $implode_charts, mktime()+31536000);
		}
	
	}
	else {
	$show_charts = array();
	}


///////////////////////////////////////////////////////////////////////


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


	// We can safely dismiss alerts with cookies enabled, without losing data
	if ( $_COOKIE['coin_amounts'] != '' ) {
	$dismiss_alert = ' <br /><br /><a href="'.start_page($_GET['start_page'], 'href').'">Dismiss Alert</a>';
	}
	

	if ( $_COOKIE['theme_selected'] != NULL ) {
	$theme_selected = $_COOKIE['theme_selected'];
	}
	elseif ( $_POST['theme_selected'] != NULL ) {
	$theme_selected = $_POST['theme_selected'];
	}
	else {
	$theme_selected = $app_config['general']['default_theme'];
	}
	// Sanitizing $theme_selected is very important, as we are calling external files with the value
	if ( $theme_selected != 'light' && $theme_selected != 'dark' ) {
	app_logging('security_error', 'Injected theme path value attack', 'Requested theme value: "' . $theme_selected . '";');
	error_logs();
	exit;
	}
	

	// If CSV file import is in process, check it
	if ( $_POST['csv_check'] == 1 ) {
		
		
		// Checks and importing
		if ( $_FILES['csv_file']['tmp_name'] != NULL ) {
		$csv_file_array = csv_import_array($_FILES['csv_file']['tmp_name']);
   	}
   	else {
   	$csv_import_fail = 'You forgot to select your CSV import file.' . $dismiss_alert;
   	}
   	
   	
		if ( !$csv_import_fail && !is_array($csv_file_array) ) {
   	$csv_import_fail = 'Your CSV import file does not appear to be formatted correctly.' . $dismiss_alert;
   	}
		elseif ( is_array($csv_file_array) ) {
   	$csv_import_succeed = 'Your CSV import succeeded.' . $dismiss_alert;
   	}
   	
   	if ( !$csv_import_fail ) {
   	$run_csv_import = 1;
   	}
   
	}
	
	
// Now that $run_csv_import has been determined ABOVE, we can call our cookie logic
require_once($base_dir . "/app-lib/php/other/cookies.php");


}
//////////////////////////////////////////////////////////////////
// END UI-ONLY VAR SETTINGS
//////////////////////////////////////////////////////////////////

 
 ?>
<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic IF NOT RUNNING AS CRON
//////////////////////////////////////////////////////////////////
if ( $runtime_mode != 'cron' ) {
	

$alert_percent = explode("|", ( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] ) );

$pt_conf['gen']['prim_mcap_site'] = ( $alert_percent[0] != '' ? $alert_percent[0] : $pt_conf['gen']['prim_mcap_site'] );


///////////////////////////////////////////////////////////////////////


$show_crypto_val = explode(',', rtrim( ( $_POST['show_crypto_val'] != '' ? $_POST['show_crypto_val'] : $_COOKIE['show_crypto_val'] ) , ',') );

		
		// Remove any stale crypto value
		$temp_show_crypto_val = array();
		$scan_crypto_val = $show_crypto_val;
		$scan_crypto_val = array_map( array($pt_var, 'strip_brackets') , $scan_crypto_val); // Strip brackets
		$loop = 0;
		foreach ($scan_crypto_val as $key) {
			if ( array_key_exists($key, $pt_conf['power']['crypto_pairing']) ) {
			$temp_show_crypto_val[$loop] = $show_crypto_val[$loop];
			}
		$loop = $loop + 1;
		}
		$show_crypto_val = $temp_show_crypto_val;
		$implode_crypto_val = implode(',', $show_crypto_val) . ',';
	
		// Update POST and / or COOKIE data too
		if( $_POST['show_crypto_val'] ) {
		$_POST['show_crypto_val'] = $implode_crypto_val;
		}
	
		if( $_COOKIE['show_crypto_val'] ) {
		$pt_gen->store_cookie("show_crypto_val", $implode_crypto_val, mktime()+31536000);
		}

///////////////////////////////////////////////////////////////////////


$show_secondary_trade_val = ( $_POST['show_secondary_trade_val'] != '' ? $_POST['show_secondary_trade_val'] : $_COOKIE['show_secondary_trade_val'] );

	// Remove any stale secondary trade value
	if ( !array_key_exists($show_secondary_trade_val, $pt_conf['power']['crypto_pairing']) ) {
	$show_secondary_trade_val = null;
	$_POST['show_secondary_trade_val'] = null;  
	$pt_gen->store_cookie("show_secondary_trade_val", "", time()-3600);  
	unset($_COOKIE['show_secondary_trade_val']);  
	}


///////////////////////////////////////////////////////////////////////


$show_feeds = explode(',', rtrim( ( $_POST['show_feeds'] != '' ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] ) , ',') );

	// Alphabetically order AND remove stale feeds
	// (since we already alphabetically ordered $pt_conf['power']['news_feed'] in app-config-management.php BEFOREHAND)
	$temp_show_feeds = array();
	$scan_feeds = $show_feeds;
	$scan_feeds = array_map( array($pt_var, 'strip_brackets') , $scan_feeds); // Strip brackets
	foreach ($pt_conf['power']['news_feed'] as $feed) {
	$feed_id = $pt_gen->digest($feed["title"], 10);
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
	$pt_gen->store_cookie("show_feeds", $implode_feeds, mktime()+31536000);
	}


///////////////////////////////////////////////////////////////////////


	// Only set from cookie / post values if charts are enabled
	if ( $pt_conf['gen']['asset_charts_toggle'] == 'on' ) {
		
	$show_charts = explode(',', rtrim( ( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] ) , ',') );
		
		// Remove stale charts
		$temp_show_charts = array();
		$scan_charts = $show_charts;
		$scan_charts = array_map( array($pt_var, 'strip_brackets') , $scan_charts); // Strip brackets
		$scan_charts = array_map( array($pt_var, 'strip_underscore_and_after') , $scan_charts); // Strip underscore, and everything after
		$loop = 0;
		foreach ($scan_charts as $market_key) {
			
			// IF asset exists in charts app config, AND $show_charts UI key format is latest iteration (fiat conversion charts USED TO have no underscore)
			if ( array_key_exists($market_key, $pt_conf['charts_alerts']['tracked_markets']) && stristr($show_charts[$loop], '_') ) {
				
			$chart_params = explode('_', $pt_var->strip_brackets($show_charts[$loop]) );
			
			$chart_conf_check = explode('||', $pt_conf['charts_alerts']['tracked_markets'][$market_key]);
				
				// If pairing properly matches OR it's a conversion chart, we're good to keep this $show_charts array value 
				if ( $chart_params[1] == $chart_conf_check[1] || $chart_params[1] == $default_btc_prim_currency_pairing ) {
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
		$pt_gen->store_cookie("show_charts", $implode_charts, mktime()+31536000);
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
	$dismiss_alert = ' <br /><br /><a href="'.$pt_gen->start_page($_GET['start_page'], 'href').'">Dismiss Alert</a>';
	}
	

	if ( $_COOKIE['theme_selected'] != NULL ) {
	$theme_selected = $_COOKIE['theme_selected'];
	}
	elseif ( $_POST['theme_selected'] != NULL ) {
	$theme_selected = $_POST['theme_selected'];
	}
	else {
	$theme_selected = $pt_conf['gen']['default_theme'];
	}
	// Sanitizing $theme_selected is very important, as we are calling external files with the value
	if ( $theme_selected != 'light' && $theme_selected != 'dark' ) {
	$pt_gen->app_logging('security_error', 'Injected theme path value attack', 'Requested theme value: "' . $theme_selected . '";');
	$pt_cache->error_logs();
	exit;
	}
	

	// If CSV file import is in process, check it
	if ( $_POST['csv_check'] == 1 ) {
		
		
		// Checks and importing
		if ( $_FILES['csv_file']['tmp_name'] != NULL ) {
		$csv_file_array = $pt_gen->csv_import_array($_FILES['csv_file']['tmp_name']);
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
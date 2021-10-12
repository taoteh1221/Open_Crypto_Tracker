<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic IF NOT RUNNING AS CRON
//////////////////////////////////////////////////////////////////
if ( $runtime_mode != 'cron' ) {
	

$sel_opt['alert_percent'] = explode("|", ( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] ) );

$oct_conf['gen']['prim_mcap_site'] = ( $sel_opt['alert_percent'][0] != '' ? $sel_opt['alert_percent'][0] : $oct_conf['gen']['prim_mcap_site'] );


///////////////////////////////////////////////////////////////////////


$sel_opt['show_crypto_val'] = explode(',', rtrim( ( $_POST['show_crypto_val'] != '' ? $_POST['show_crypto_val'] : $_COOKIE['show_crypto_val'] ) , ',') );

		
		// Remove any stale crypto value
		$temp_show_crypto_val = array();
		$scan_crypto_val = $sel_opt['show_crypto_val'];
		$scan_crypto_val = array_map( array($oct_var, 'strip_brackets') , $scan_crypto_val); // Strip brackets
		$loop = 0;
		foreach ($scan_crypto_val as $key) {
			if ( array_key_exists($key, $oct_conf['power']['crypto_pairing']) ) {
			$temp_show_crypto_val[$loop] = $sel_opt['show_crypto_val'][$loop];
			}
		$loop = $loop + 1;
		}
		$sel_opt['show_crypto_val'] = $temp_show_crypto_val;
		$implode_crypto_val = implode(',', $sel_opt['show_crypto_val']) . ',';
	
		// Update POST and / or COOKIE data too
		if( $_POST['show_crypto_val'] ) {
		$_POST['show_crypto_val'] = $implode_crypto_val;
		}
	
		if( $_COOKIE['show_crypto_val'] ) {
		$oct_gen->store_cookie("show_crypto_val", $implode_crypto_val, mktime()+31536000);
		}

///////////////////////////////////////////////////////////////////////


$sel_opt['show_secondary_trade_val'] = ( $_POST['show_secondary_trade_val'] != '' ? $_POST['show_secondary_trade_val'] : $_COOKIE['show_secondary_trade_val'] );

	// Remove any stale secondary trade value
	if ( !array_key_exists($sel_opt['show_secondary_trade_val'], $oct_conf['power']['crypto_pairing']) ) {
	$sel_opt['show_secondary_trade_val'] = null;
	$_POST['show_secondary_trade_val'] = null;  
	$oct_gen->store_cookie("show_secondary_trade_val", "", time()-3600);  
	unset($_COOKIE['show_secondary_trade_val']);  
	}


///////////////////////////////////////////////////////////////////////


$sel_opt['show_feeds'] = explode(',', rtrim( ( $_POST['show_feeds'] != '' ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] ) , ',') );

	// Alphabetically order AND remove stale feeds
	// (since we already alphabetically ordered $oct_conf['power']['news_feed'] in app-config-management.php BEFOREHAND)
	$temp_show_feeds = array();
	$scan_feeds = $sel_opt['show_feeds'];
	$scan_feeds = array_map( array($oct_var, 'strip_brackets') , $scan_feeds); // Strip brackets
	foreach ($oct_conf['power']['news_feed'] as $feed) {
	$feed_id = $oct_gen->digest($feed["title"], 10);
		if ( in_array($feed_id, $scan_feeds) ) {
		$temp_show_feeds[] = '[' . $feed_id . ']';
		}
	}
	$sel_opt['show_feeds'] = $temp_show_feeds;
	$implode_feeds = implode(',', $sel_opt['show_feeds']) . ',';
	
	// Update POST and / or COOKIE data too
	if( $_POST['show_feeds'] ) {
	$_POST['show_feeds'] = $implode_feeds;
	}
	
	if( $_COOKIE['show_feeds'] ) {
	$oct_gen->store_cookie("show_feeds", $implode_feeds, mktime()+31536000);
	}


///////////////////////////////////////////////////////////////////////


	// Only set from cookie / post values if charts are enabled
	if ( $oct_conf['gen']['asset_charts_toggle'] == 'on' ) {
		
	$sel_opt['show_charts'] = explode(',', rtrim( ( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] ) , ',') );
		
		// Remove stale charts
		$temp_show_charts = array();
		$scan_charts = $sel_opt['show_charts'];
		$scan_charts = array_map( array($oct_var, 'strip_brackets') , $scan_charts); // Strip brackets
		$scan_charts = array_map( array($oct_var, 'strip_underscore_and_after') , $scan_charts); // Strip underscore, and everything after
		$loop = 0;
		foreach ($scan_charts as $market_key) {
			
			// IF asset exists in charts app config, AND $sel_opt['show_charts'] UI key format is latest iteration (fiat conversion charts USED TO have no underscore)
			if ( array_key_exists($market_key, $oct_conf['charts_alerts']['tracked_markets']) && stristr($sel_opt['show_charts'][$loop], '_') ) {
				
			$chart_params = explode('_', $oct_var->strip_brackets($sel_opt['show_charts'][$loop]) );
			
			$chart_conf_check = explode('||', $oct_conf['charts_alerts']['tracked_markets'][$market_key]);
				
				// If pairing properly matches OR it's a conversion chart, we're good to keep this $sel_opt['show_charts'] array value 
				if ( $chart_params[1] == $chart_conf_check[1] || $chart_params[1] == $default_btc_prim_currency_pairing ) {
				$temp_show_charts[$loop] = $sel_opt['show_charts'][$loop];
				}
				
			}
			
		$loop = $loop + 1;
		
		}
		$sel_opt['show_charts'] = $temp_show_charts;
		$implode_charts = implode(',', $sel_opt['show_charts']) . ',';
	
		// Update POST and / or COOKIE data too
		if( $_POST['show_charts'] ) {
		$_POST['show_charts'] = $implode_charts;
		}
	
		if( $_COOKIE['show_charts'] ) {
		$oct_gen->store_cookie("show_charts", $implode_charts, mktime()+31536000);
		}
	
	}
	else {
	$sel_opt['show_charts'] = array();
	}


///////////////////////////////////////////////////////////////////////


$sort_array = explode("|", ( $_COOKIE['sort_by'] ? $_COOKIE['sort_by'] : $_POST['sort_by'] ) );

$sel_opt['sorted_by_col'] = $sort_array[0];
$sel_opt['sorted_asc_desc'] = $sort_array[1];


	if ( !$sel_opt['sorted_by_col'] ) {
	$sel_opt['sorted_by_col'] = 0;
	}
	if ( !$sel_opt['sorted_asc_desc'] ) {
	$sel_opt['sorted_asc_desc'] = 0;
	}


	// We can safely dismiss alerts with cookies enabled, without losing data
	if ( $_COOKIE['coin_amounts'] != '' ) {
	$dismiss_alert = ' <br /><br /><a href="'.$oct_gen->start_page($_GET['start_page'], 'href').'">Dismiss Alert</a>';
	}
	

	if ( $_COOKIE['theme_selected'] != NULL ) {
	$sel_opt['theme_selected'] = $_COOKIE['theme_selected'];
	}
	elseif ( $_POST['theme_selected'] != NULL ) {
	$sel_opt['theme_selected'] = $_POST['theme_selected'];
	}
	else {
	$sel_opt['theme_selected'] = $oct_conf['gen']['default_theme'];
	}
	// Sanitizing $sel_opt['theme_selected'] is very important, as we are calling external files with the value
	if ( $sel_opt['theme_selected'] != 'light' && $sel_opt['theme_selected'] != 'dark' ) {
		
	$oct_gen->log(
								'security_error',
								'Injected theme path value attack',
								'Requested theme value: "' . $sel_opt['theme_selected'] . '";'
								);
	
	$oct_cache->error_logs();
	
	exit;
	
	}
	

	// If CSV file import is in process, check it
	if ( $_POST['csv_check'] == 1 ) {
		
		
		// Checks and importing
		if ( $_FILES['csv_file']['tmp_name'] != NULL ) {
		$csv_file_array = $oct_gen->csv_import_array($_FILES['csv_file']['tmp_name']);
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
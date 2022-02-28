<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic IF NOT RUNNING AS CRON
//////////////////////////////////////////////////////////////////
if ( $runtime_mode != 'cron' ) {
	

$sel_opt['alert_percent'] = explode("|", ( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] ) );

$ct_conf['gen']['prim_mcap_site'] = ( $sel_opt['alert_percent'][0] != '' ? $sel_opt['alert_percent'][0] : $ct_conf['gen']['prim_mcap_site'] );


///////////////////////////////////////////////////////////////////////


$sel_opt['show_crypto_val'] = explode(',', rtrim( ( $_POST['show_crypto_val'] != '' ? $_POST['show_crypto_val'] : $_COOKIE['show_crypto_val'] ) , ',') );

		
		// Remove any stale crypto value
		$temp_show_crypto_val = array();
		$scan_crypto_val = $sel_opt['show_crypto_val'];
		$scan_crypto_val = array_map( array($ct_var, 'strip_brackets') , $scan_crypto_val); // Strip brackets
		$loop = 0;
		foreach ($scan_crypto_val as $key) {
			if ( array_key_exists($key, $ct_conf['power']['crypto_pair']) ) {
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
		$ct_gen->store_cookie("show_crypto_val", $implode_crypto_val, time()+31536000);
		}

///////////////////////////////////////////////////////////////////////


$sel_opt['show_secondary_trade_val'] = ( $_POST['show_secondary_trade_val'] != '' ? $_POST['show_secondary_trade_val'] : $_COOKIE['show_secondary_trade_val'] );

	// Remove any stale secondary trade value
	if ( !array_key_exists($sel_opt['show_secondary_trade_val'], $ct_conf['power']['crypto_pair']) ) {
	$sel_opt['show_secondary_trade_val'] = null;
	$_POST['show_secondary_trade_val'] = null;  
	$ct_gen->store_cookie("show_secondary_trade_val", "", time()-3600);  
	}


///////////////////////////////////////////////////////////////////////


$sel_opt['show_feeds'] = explode(',', rtrim( ( $_POST['show_feeds'] != '' ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] ) , ',') );

	// Alphabetically order AND remove stale feeds
	// (since we already alphabetically ordered $ct_conf['power']['news_feed'] in app-config-management.php BEFOREHAND)
	$temp_show_feeds = array();
	$scan_feeds = $sel_opt['show_feeds'];
	$scan_feeds = array_map( array($ct_var, 'strip_brackets') , $scan_feeds); // Strip brackets
	foreach ($ct_conf['power']['news_feed'] as $feed) {
	$feed_id = $ct_gen->digest($feed["title"], 5);
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
	$ct_gen->store_cookie("show_feeds", $implode_feeds, time()+31536000);
	}


///////////////////////////////////////////////////////////////////////


	// Only set from cookie / post values if charts are enabled
	if ( $ct_conf['gen']['asset_charts_toggle'] == 'on' ) {
		
	$sel_opt['show_charts'] = explode(',', rtrim( ( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] ) , ',') );
		
		// Remove stale charts
		$temp_show_charts = array();
		$scan_charts = $sel_opt['show_charts'];
		$scan_charts = array_map( array($ct_var, 'strip_brackets') , $scan_charts); // Strip brackets
		$scan_charts = array_map( array($ct_var, 'strip_underscore_and_after') , $scan_charts); // Strip underscore, and everything after
		$loop = 0;
		foreach ($scan_charts as $market_key) {
			
			// IF asset exists in charts app config, AND $sel_opt['show_charts'] UI key format is latest iteration (fiat conversion charts USED TO have no underscore)
			if ( array_key_exists($market_key, $ct_conf['charts_alerts']['tracked_mrkts']) && stristr($sel_opt['show_charts'][$loop], '_') ) {
				
			$chart_params = explode('_', $ct_var->strip_brackets($sel_opt['show_charts'][$loop]) );
			
			$chart_conf_check = explode('||', $ct_conf['charts_alerts']['tracked_mrkts'][$market_key]);
				
				// If pair properly matches OR it's a conversion chart, we're good to keep this $sel_opt['show_charts'] array value 
				if ( $chart_params[1] == $chart_conf_check[1] || $chart_params[1] == $default_btc_prim_currency_pair ) {
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
		$ct_gen->store_cookie("show_charts", $implode_charts, time()+31536000);
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
	if ( $_COOKIE['coin_amnts'] != '' ) {
	$dismiss_alert = ' <br /><br /><a href="'.$ct_gen->start_page($_GET['start_page'], 'href').'">Dismiss Alert</a>';
	}
	

	if ( $_COOKIE['theme_selected'] != null ) {
	$sel_opt['theme_selected'] = $_COOKIE['theme_selected'];
	}
	elseif ( $_POST['theme_selected'] != null ) {
	$sel_opt['theme_selected'] = $_POST['theme_selected'];
	}
	else {
	$sel_opt['theme_selected'] = $ct_conf['gen']['default_theme'];
	}
	// Sanitizing $sel_opt['theme_selected'] is very important, as we are calling external files with the value
	if ( $sel_opt['theme_selected'] != 'light' && $sel_opt['theme_selected'] != 'dark' ) {
		
	$ct_gen->log(
								'security_error',
								'Injected theme path value attack',
								'Requested theme value: "' . $sel_opt['theme_selected'] . '";'
								);
	
	$ct_cache->error_log();
	
	exit;
	
	}
	

	// If CSV file import is in process, check it
	if ( $_POST['csv_check'] == 1 ) {
		
		
		// Checks and importing
		if ( is_uploaded_file($_FILES['csv_file']['tmp_name']) ) {
		$csv_file_array = $ct_gen->csv_import_array($_FILES['csv_file']['tmp_name']);
   	}
   	else {
   	$csv_import_fail = 'Your CSV import upload failed.' . $dismiss_alert;
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
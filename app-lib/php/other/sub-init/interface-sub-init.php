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

$show_crypto_value = explode(',', rtrim( ( $_POST['show_crypto_value'] != '' ? $_POST['show_crypto_value'] : $_COOKIE['show_crypto_value'] ) , ',') );

$show_feeds = explode(',', rtrim( ( $_POST['show_feeds'] != '' ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] ) , ',') );

	// Alphabetically order AND remove stale feeds
	// (since we already alphabetically ordered $app_config['power_user']['news_feeds'] in app-config-management.php BEFOREHAND)
	$temp_show_feeds = array();
	$scan_feeds = array_map('strip_brackets', $show_feeds); // Strip brackets
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


	// Only set from cookie / post values if charts are enabled
	if ( $app_config['general']['asset_charts_toggle'] == 'on' ) {
	$show_charts = explode(',', rtrim( ( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] ) , ',') );
	}
	else {
	$show_charts = array();
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
		$csv_file_array = csv_file_array($_FILES['csv_file']['tmp_name']);
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
   	
   	if ( !$csv_import_fail && $_POST['csv_check'] == 1 ) {
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
<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic during UI runtime
//////////////////////////////////////////////////////////////////
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
	
	
	
// Now that $run_csv_import has been determined ABOVE, we can call our cookie logic
require_once( $base_dir . "/app-lib/php/other/cookies.php");

		
	
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

$app_config['primary_marketcap_site'] = ( $alert_percent[0] != '' ? $alert_percent[0] : $app_config['primary_marketcap_site'] );


}
//////////////////////////////////////////////////////////////////
// END UI-ONLY VAR SETTINGS
//////////////////////////////////////////////////////////////////

  
 
 ?>
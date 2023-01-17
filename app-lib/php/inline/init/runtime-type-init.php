<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// RUNTIME TYPE INIT
//////////////////////////////////////////////////////////////////


// ALL RUNTIMES

// General preflight security checks (that MUST run for ANY runtime [EVEN IF IT SLOWS DOWN FAST RUNTIMES])
require_once('app-lib/php/inline/security/general-preflight-security-checks.php');


$ct_conf['gen']['prim_mcap_site'] = ( isset($sel_opt['alert_percent'][0]) && $sel_opt['alert_percent'][0] != '' ? $sel_opt['alert_percent'][0] : $ct_conf['gen']['prim_mcap_site'] );


if ( isset($_COOKIE['theme_selected']) ) {
$sel_opt['theme_selected'] = $_COOKIE['theme_selected'];
}
elseif ( isset($_POST['theme_selected']) ) {
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

// Log errors / debugging, send notifications
$ct_cache->error_log();
$ct_cache->debug_log();
$ct_cache->send_notifications();
    
flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache
exit;

}


// END ALL RUNTIMES


// CRON RUNTIMES
if ( $runtime_mode == 'cron' ) {


// Reset feed fetch telemetry 
$_SESSION[$fetched_feeds] = false;
	
$_SESSION['light_charts_updated'] = 0;
    
    
    // EXIT IF CRON IS NOT RUNNING IN THE PROPER CONFIGURATION
    if ( !isset($_GET['cron_emulate']) && php_sapi_name() != 'cli' || isset($_GET['cron_emulate']) && $app_edition == 'server' ) {
    $ct_gen->log('security_error', 'aborted cron job attempt ('.$_SERVER['REQUEST_URI'].'), INVALID CONFIG');
    $ct_cache->error_log();
    echo "Aborted, INVALID CONFIG.";
    exit; // Force exit
    }


    // Emulated cron checks / flag as go or not 
    // (WE ALREADY ADJUST EXECUTION TIME FOR CRON RUNTIMES IN INIT.PHP, SO THAT'S ALREADY OK EVEN EMULATING CRON)
    // (DISABLED if end-user sets $ct_conf['power']['desktop_cron_interval'] to zero)
    if ( isset($_GET['cron_emulate']) && $ct_conf['power']['desktop_cron_interval'] == 0 ) {
        
    $exit_result_text = "EMULATED cron job is disabled in power user config";
    
    $ct_gen->log('conf_error', $exit_result_text);
    
    $exit_result = array('result' => $exit_result_text);
    
    $run_cron = false;
    
    }
    // If end-user did not disable emulated cron, BEFORE setting up and running regular cron
    elseif ( $app_edition == 'desktop' && $ct_conf['power']['desktop_cron_interval'] > 0 && php_sapi_name() == 'cli' ) {
        
    $exit_result_text = 'you must disable EMULATED cron BEFORE running REGULAR cron (set "desktop_cron_interval" to zero in power user config, AND THEN YOU *MUST* RESTART / RELOAD THE APP *AFTERWARDS*)';
    
    $ct_gen->log('conf_error', $exit_result_text);
    
    $exit_result = array('result' => $exit_result_text);
    
    $run_cron = false;
    
    }
    elseif ( isset($_GET['cron_emulate']) && $ct_conf['power']['desktop_cron_interval'] > 0 ) {
    $run_cron = true;
    }
    // Regular cron check (via command line)
    elseif ( php_sapi_name() == 'cli' ) {
    $run_cron = true;
    }
    
    
    // If emulated cron and it's a no go, exit with a json response (for interface / console log)
    if ( $run_cron == false ) {
    $ct_cache->error_log();
    echo json_encode($exit_result, JSON_PRETTY_PRINT);
    exit; // Force exit
    } 
    

}
// UI RUNTIMES NOT DESIGNATED AS A "FAST RUNTIME"
elseif ( $runtime_mode == 'ui' && !$is_fast_runtime ) {


// Final UI-ONLY preflight SECURITY checks (MUST RUN AFTER app config auto-adjust / htaccess user login / user agent)
// (AS WE ARE RUNNING SELF-TESTS WITH $ct_cache->ext_data() ETC)
// (as we may need to refresh MAIN .htaccess / user.ini)
require_once('app-lib/php/inline/security/ui-only-preflight-security-checks.php');
    
    
///////////////////////////////////////////////////////////////////////
	
	
	// Have UI / HTTP runtime mode RE-CACHE the runtime_user data every 24 hours, since CLI runtime cannot determine the UI / HTTP runtime_user 
	if ( $ct_cache->update_cache('cache/vars/http_runtime_user.dat', (60 * 24) ) == true ) {
	$ct_cache->save_file('cache/vars/http_runtime_user.dat', $http_runtime_user); // ALREADY SET FURTHER UP IN INIT.PHP
	}


///////////////////////////////////////////////////////////////////////
	

$sel_opt['alert_percent'] = explode("|", ( isset($_POST['use_alert_percent']) ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] ) );

$sel_opt['show_crypto_val'] = explode(',', rtrim( ( isset($_POST['show_crypto_val']) ? $_POST['show_crypto_val'] : $_COOKIE['show_crypto_val'] ) , ',') );

$sel_opt['show_secondary_trade_val'] = ( isset($_POST['show_secondary_trade_val']) ? $_POST['show_secondary_trade_val'] : $_COOKIE['show_secondary_trade_val'] );

$sel_opt['show_feeds'] = explode(',', rtrim( ( isset($_POST['show_feeds']) ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] ) , ',') );
    
$sort_array = explode("|", ( isset($_POST['sort_by']) ? $_POST['sort_by'] : $_COOKIE['sort_by'] ) );
$sel_opt['sorted_by_col'] = $sort_array[0];
$sel_opt['sorted_asc_desc'] = $sort_array[1];
    
    
    ////////////////////////////////
    
    
	// We can safely dismiss alerts with cookies enabled, without losing data
	if ( isset($_COOKIE['coin_amnts']) ) {
	$dismiss_alert = ' <br /><br /><a href="'.$ct_gen->start_page($_GET['start_page'], 'href').'">Dismiss Alert</a>';
	}
    
    
    ////////////////////////////////
    
    
	if ( !$sel_opt['sorted_by_col'] ) {
	$sel_opt['sorted_by_col'] = 0;
	}
	if ( !$sel_opt['sorted_asc_desc'] ) {
	$sel_opt['sorted_asc_desc'] = 0;
	}
    
    
    ////////////////////////////////
    
    
    	// Remove any stale secondary trade value
    	if ( isset($sel_opt['show_secondary_trade_val']) && !array_key_exists($sel_opt['show_secondary_trade_val'], $ct_conf['power']['crypto_pair']) ) {
    	unset($sel_opt['show_secondary_trade_val']);
    	unset($_POST['show_secondary_trade_val']);  
    	unset($_COOKIE['show_secondary_trade_val']);  
    	$ct_gen->store_cookie("show_secondary_trade_val", "", time()-3600);  // Delete cookie -3600 seconds (expired)
    	}
    
    
    ////////////////////////////////
    
    
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
     if( isset($_POST['show_crypto_val']) ) {
     $_POST['show_crypto_val'] = $implode_crypto_val;
     }
    	
     if( isset($_COOKIE['show_crypto_val']) ) {
     $ct_gen->store_cookie("show_crypto_val", $implode_crypto_val, time()+31536000);
     }
    
    
    ////////////////////////////////
    
    
    	// Alphabetically order AND remove stale feeds
    	// (since we already alphabetically ordered $ct_conf['power']['news_feed'] in config-auto-adjust.php BEFOREHAND)
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
    	if( isset($_POST['show_feeds']) ) {
    	$_POST['show_feeds'] = $implode_feeds;
    	}
    	
    	if( isset($_COOKIE['show_feeds']) ) {
    	$ct_gen->store_cookie("show_feeds", $implode_feeds, time()+31536000);
    	}
    
    
    ////////////////////////////////
    
    
    	// Only set from cookie / post values if charts are enabled
    	if ( $ct_conf['gen']['asset_charts_toggle'] == 'on' ) {
     
    	$sel_opt['show_charts'] = explode(',', rtrim( ( isset($_POST['show_charts']) ? $_POST['show_charts'] : $_COOKIE['show_charts'] ) , ',') );
     
     // Remove stale charts
     $temp_show_charts = array();
     $scan_charts = $sel_opt['show_charts'];
     $scan_charts = array_map( array($ct_var, 'strip_brackets') , $scan_charts); // Strip brackets
     $scan_charts = array_map( array($ct_var, 'strip_underscore_and_after') , $scan_charts); // Strip underscore, and everything after
     $loop = 0;
     foreach ($scan_charts as $mrkt_key) {
     	
     	// IF asset exists in charts app config, AND $sel_opt['show_charts'] UI key format is latest iteration (fiat conversion charts USED TO have no underscore)
     	if ( array_key_exists($mrkt_key, $ct_conf['charts_alerts']['tracked_mrkts']) && stristr($sel_opt['show_charts'][$loop], '_') ) {
     		
     	$chart_params = explode('_', $ct_var->strip_brackets($sel_opt['show_charts'][$loop]) );
     	
     	$chart_conf_check = explode('||', $ct_conf['charts_alerts']['tracked_mrkts'][$mrkt_key]);
     		
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
     if( isset($_POST['show_charts']) ) {
     $_POST['show_charts'] = $implode_charts;
     }
    	
     if( isset($_COOKIE['show_charts']) ) {
     $ct_gen->store_cookie("show_charts", $implode_charts, time()+31536000);
     }
    	
    	}
    	else {
    	$sel_opt['show_charts'] = array();
    	}
	

	// If CSV file import is in process, check it
	if ( $_POST['csv_check'] == 1 ) {
		
		
		// Checks and importing
		if ( is_uploaded_file($_FILES['csv_file']['tmp_name']) ) {
		$csv_file_array = $ct_gen->csv_import_array($_FILES['csv_file']['tmp_name']);
       	}
       	else {
       	$csv_import_fail_alert = 'Your CSV import upload failed (' . $ct_gen->upload_error($_FILES['csv_file']['error']) . ').';
          $ct_gen->log('system_error', $csv_import_fail_alert);
       	$csv_import_fail = $csv_import_fail_alert . $dismiss_alert;
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
	

}


// NON-CRON / NON-FAST RUNTIMES ALLOW COOKIE FEATURES TO BE ENABLED (BY END-USERS)
// (MUST BE CALLED AT END OF "RUNTIME TYPE INIT" BY ITSELF)
if ( $runtime_mode != 'cron' && !$is_fast_runtime ) {
require_once($base_dir . "/app-lib/php/inline/vars/cookies.php");
}


//////////////////////////////////////////////////////////////////
// END RUNTIME TYPE INIT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// CRON SUB-INIT
//////////////////////////////////////////////////////////////////
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
        
    $exit_result_text = 'you must disable EMULATED cron BEFORE running REGULAR cron (set "desktop_cron_interval" to zero in power user config)';
    
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
//////////////////////////////////////////////////////////////////
// END CRON SUB-INIT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
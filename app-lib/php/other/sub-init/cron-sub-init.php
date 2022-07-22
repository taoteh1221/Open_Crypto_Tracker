<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below init logic during cron runtime
//////////////////////////////////////////////////////////////////
if ( $runtime_mode == 'cron' ) {


// Reset feed fetch telemetry 
$_SESSION[$fetched_feeds] = false;
	
$_SESSION['lite_charts_updated'] = 0;
    
    
    // EXIT IF CRON IS NOT RUNNING IN THE PROPER CONFIGURATION
    if ( !isset($_GET['cron_emulate']) && php_sapi_name() != 'cli' || isset($_GET['cron_emulate']) && $app_edition == 'server' ) {
    $ct_gen->log('security_error', 'aborted cron job attempt ('.$_SERVER['REQUEST_URI'].'), INVALID CONFIG');
    $ct_cache->error_log();
    echo "Aborted, INVALID CONFIG.";
    exit;
    }


    // Emulated cron checks / flag as go or not 
    // (WE ALREADY ADJUST EXECUTION TIME FOR CRON RUNTIMES IN INIT.PHP, SO THAT'S ALREADY OK EVEN EMULATING CRON)
    // (DISABLED if end-user sets $ct_conf['power']['desktop_cron_interval'] to zero)
    if ( isset($_SESSION['cron_emulate_run']) && isset($_GET['cron_emulate']) && $ct_conf['power']['desktop_cron_interval'] == 0 ) {
    unset($_SESSION['cron_emulate_run']);
    $run_cron = false;
    }
    elseif ( !isset($_SESSION['cron_emulate_run']) && isset($_GET['cron_emulate']) && $ct_conf['power']['desktop_cron_interval'] > 0 ) {
    $_SESSION['cron_emulate_run'] = time();
    $run_cron = true;
    }
    // +interval time met
    elseif ( isset($_SESSION['cron_emulate_run']) && isset($_GET['cron_emulate']) && ( $_SESSION['cron_emulate_run'] + ($ct_conf['power']['desktop_cron_interval'] * 60) ) <= time() ) {
    $_SESSION['cron_emulate_run'] = time();
    $run_cron = true;
    }
    // If end-user did not disable emulated cron, BEFORE setting up and running regular cron
    elseif ( $app_edition == 'desktop' && $ct_conf['power']['desktop_cron_interval'] > 0 && php_sapi_name() == 'cli' ) {
    $ct_gen->log('conf_error', 'you must disable EMULATED cron BEFORE running REGULAR cron (set "desktop_cron_interval" to zero in power user config)');
    $ct_cache->error_log();
    $run_cron = false;
    }
    // Regular cron check (via command line)
    elseif ( php_sapi_name() == 'cli' ) {
    $run_cron = true;
    }
    else {
    $run_cron = false;
    }
    
    
    // If emulated cron and it's a no go, exit with a json response (for interface / console log)
    if ( isset($_GET['cron_emulate']) && $run_cron == false ) {
        
        if ( isset($_SESSION['cron_emulate_run']) ) {
        $result = array('result' => "Too early to re-run EMULATED cron job");
        }
        else {
        $result = array('result' => "EMULATED cron job is disabled in power user config");
        }
    
    echo json_encode($result, JSON_PRETTY_PRINT);
    exit;
    
    } 
    

}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY INIT LOGIC
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
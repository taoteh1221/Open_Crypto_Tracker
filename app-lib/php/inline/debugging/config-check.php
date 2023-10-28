<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// CHECKING THE HARD-CODED MAIN CONFIG FILE FOR PARSE / FATAL ERRORS
// https://stackoverflow.com/questions/17160851/is-there-any-way-to-skip-fatal-error-from-include-file-in-php


//////////////////////////////////////////////////////////////////////////////////////////


// On fatal error during include, continue script execution from here.
// When this function ends, or if another fatal error occurs,
// the execution will stop.
function ct_redundant_config($ct_config_file, $arg2=false) {
ct_config_error();
}


//////////////////////////////////////////////////////////////////////////////////////////


function ct_include_catch() {
    $error_get_last = error_get_last();
    $output = $GLOBALS['_ct_include_shutdown_handler'][2];
    // Disable shutdown handler:
    $GLOBALS['_ct_include_shutdown_handler'] = NULL;
    // Check unauthorized outputs or if an error occured:
    return ($output ? false : ob_get_clean() !== '')
        || $error_get_last['message'] !== 'error_get_last mark';
}


//////////////////////////////////////////////////////////////////////////////////////////


function ct_include_shutdown_handler() {
    $func = $GLOBALS['_ct_include_shutdown_handler'];
    if($func !== NULL) {
        // Cleanup:
        ct_include_catch();
        // Fix potentially wrong working directory:
        chdir($func[3]);
        // Call continuation function:
        call_user_func_array($func[0], $func[1]);
    }
}


//////////////////////////////////////////////////////////////////////////////////////////


function ct_include_try($cont_func, $cont_param_arr, $output = false) {
    // Setup shutdown function:
    static $run = 0;
    if($run++ === 0) register_shutdown_function('ct_include_shutdown_handler');

    // If output is not allowed, capture it:
    if(!$output) ob_start();
    // Reset error_get_last():
    @user_error('error_get_last mark');
    // Enable shutdown handler and store parameters:
    $params = array($cont_func, $cont_param_arr, $output, getcwd());
    $GLOBALS['_ct_include_shutdown_handler'] = $params;
}


//////////////////////////////////////////////////////////////////////////////////////////


function ct_config_error() {

global $ct;

$config_file = $ct['base_dir'] . '/config.php';

     
     // Backup current config if it exists
     if ( file_exists($config_file) ) {
     
     $bytes = random_bytes(10);
     
     $backup_config_file = copy( $config_file, $ct['base_dir'] . '/config.php.BACKUP-' . $ct['year_month_day'] . '-' . bin2hex($bytes) );
     
     sleep(1);
     
     $desc = 'CONTAINS SIGNIFICANT ERRORS IN FORMATTING';
     $desc2 = 'BACKED UP AS config.php.BACKUP-' . $ct['year_month_day'] . '-XXXXXXXXXXXX, AND ';
     
     }
     else {
     $backup_config_file = true; // Nothing to backup, so just return true
     $desc = 'IS NON-EXISTANT';
     $desc2 = '';
     }
     

?>

<br /><br />

YOUR APP'S config.php FILE <?=$desc?>.<br /><br />

<?php

$reset_config_file = copy($ct['base_dir'] . '/templates/back-end/main-config.template', $config_file);


     if ( $backup_config_file && $reset_config_file ) {
     ?>
     
     IT WAS <?=$desc2?>REPLACED WITH A NEW DEFAULT CONFIG FILE.<br /><br />
     
     PLEASE <a href='javascript:location.reload(true);'>RELOAD / RESTART THIS APP</a> TO CONTINUE.<br /><br />

     <?php
     }
     else {
     ?>
     
     THERE WAS ALSO AN ERROR BACKING UP / REPLACING IT WITH A NEW DEFAULT CONFIG FILE.<br /><br />
     
     PLEASE COMPLETELY RE-INSTALL THIS APP TO CONTINUE.<br /><br />
     
     <?php
     }


exit;
  
}


//////////////////////////////////////////////////////////////////////////////////////////


$ct_config_file = 'config.php';

ct_include_try( 'ct_redundant_config', array($ct_config_file, $arg2) );

$data = include($ct_config_file);

$ct_config_error = ct_include_catch();


if ( $ct_config_error ) {
ct_config_error();
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
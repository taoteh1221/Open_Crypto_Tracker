<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



//////////////////////////////////////////////////////////////////
// SESSION INIT
//////////////////////////////////////////////////////////////////


// If this server does NOT have the PHP Session 'save path' PROPERLY setup,
// create / use OUR OWN SECURED session directory (otherwise the app won't run properly!)
if ( session_save_path() == false ) {

     if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/secured/php_sessions/') != true ) {
     echo 'App server does NOT have PHP Session "save path" support setup properly, AND auto-setup also FAILED, exiting app...<br /><br />';
     exit;
     }
     else {
     session_save_path($ct['base_dir'] . '/cache/secured/php_sessions');
     // Make sure session cleanup occurs (debian sets to zero / prefers custom cron cleanup logic ON *DEFAULT* SAVE PATH)
     ini_set('session.gc_probability', 1); 
     }

}


// Server should keep session data for AT LEAST 6 hours
ini_set('session.gc_maxlifetime', (6 * 3600) );


// PHP session cookie defaults
// each client should remember their session id for EXACTLY 6 hours
$php_sess_time = (6 * 3600);
$php_sess_secure = ( $ct['app_edition'] == 'server' ? true : false );


     if ( PHP_VERSION_ID >= 70300 ) {
     	
     	session_set_cookie_params([
                                     'lifetime' => $php_sess_time,
                                     'path' => $ct['cookie_path'],
                                     'domain' => '',  // LEAVE DOMAIN BLANK, SO session_set_cookie_params AUTO-SETS PROPERLY (IN CASE OF EDGE-CASE REDIRECTS)
                                     'secure' => $php_sess_secure,
                                     'httponly' => false, // (false keeps cookies accessible to browser scripting languages such as JavaScript)
                                     'samesite' => 'Strict',
                         	       ]);
     
     }
     else {
     	
     	session_set_cookie_params([
                                     $php_sess_time,
                                     $ct['cookie_path'] . '; samesite=Strict',
                                     '',  // LEAVE DOMAIN BLANK, SO session_set_cookie_params AUTO-SETS PROPERLY (IN CASE OF EDGE-CASE REDIRECTS)
                                     $php_sess_secure, // secure
                                     false, // httponly (false keeps cookies accessible to browser scripting languages such as JavaScript)
                                   ]);
     
     }


// Give our session a unique name (TO SUPPORT MULTIPLE INSTALLS ON SAME DOMAIN HAVING SEPERATE SESSION DATA SETS)
// MUST BE SET AFTER $ct['app_id'], AND BEFORE session_start()
session_name($ct['app_id']);

// Session start
session_start(); // New session start


//////////////////////////////////////////////////////////////////
// END SESSION INIT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
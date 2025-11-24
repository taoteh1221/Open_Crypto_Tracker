<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Access stats ajax call


// If we are not admin logged in, OR fail the CSRF security token check, exit
if ( !$ct['sec']->admin_logged_in() || !$ct['sec']->pass_sec_check($_GET['gen_nonce'], 'general_csrf_security') ) {
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();
exit;
}


echo $ct['cache']->show_access_stats();


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
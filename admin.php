<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


// Runtime mode
$runtime_mode = 'ui';

$is_admin = true;

require("app-lib/php/init.php");


// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || !is_array($stored_admin_login) ) {
require("templates/interface/desktop/php/admin/admin-login/register.php");
exit;
}
// If NOT logged in
elseif ( $ct_gen->admin_logged_in() == false ) {
require("templates/interface/desktop/php/admin/admin-login/login.php");
exit;
}


// Otherwise, let the admin interface show...

// Main admin page
if ( !isset($_GET['plugin']) && !isset($_GET['iframe']) ) {
require("templates/interface/desktop/php/header.php");
require("templates/interface/desktop/php/admin/admin-elements/admin-page-main.php");
require("templates/interface/desktop/php/footer.php");
}
// Iframe admin pages
elseif (
isset($_GET['section'])
&& trim($_GET['section']) != ''
&& isset($_GET['iframe'])
&& trim($_GET['iframe']) != ''
&& $_GET['iframe'] == $ct_gen->admin_hashed_nonce('iframe_' . $_GET['section'])
|| isset($_GET['plugin'])
&& trim($_GET['plugin']) != ''
&& isset($_GET['iframe'])
&& trim($_GET['iframe']) != ''
&& $_GET['iframe'] == $ct_gen->admin_hashed_nonce('iframe_' . $_GET['plugin'])
) {
require("templates/interface/desktop/php/admin/admin-elements/admin-page-iframe.php");
}
// Security monitoring
else {
$security_error = 'Admin nonce expired / incorrect (' . $remote_ip . '). Try reloading the app.';
$ct_gen->log('security_error', $security_error);
echo $security_error;
// Log errors before exiting
$ct_cache->error_log();
exit;
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
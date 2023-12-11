<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// MAY HELP, SINCE WE USE SAMESITE=STRICT COOKIES (ESPECIALLY ON SERVERS WITH DOMAIN REDIRECTS)
if ( !preg_match("/admin\.php/i", $_SERVER['REQUEST_URI']) ) {
header("Location: admin.php");
exit;
}


// Runtime mode
$runtime_mode = 'ui';

$is_admin = true;


// The $is_iframe flag (if required) has to be toggled before init.php
// (no need for security checks here, we are just saying if this is an iframe)
if (
isset($_GET['section']) && trim($_GET['section']) != ''
|| isset($_GET['plugin']) && trim($_GET['plugin']) != ''
) {
$is_iframe = true;
}


require("app-lib/php/init.php");


// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || !is_array($stored_admin_login) ) {
require("templates/interface/php/admin/admin-login/register.php");
exit;
}
// If NOT logged in
elseif ( $ct['gen']->admin_logged_in() == false ) {
require("templates/interface/php/admin/admin-login/login.php");
exit;
}


// Otherwise, let the admin interface show...

// Main admin page
if ( !isset($_GET['plugin']) && !isset($_GET['iframe']) ) {
require("templates/interface/php/wrap/header.php");
require("templates/interface/php/admin/admin-elements/admin-page-main.php");
require("templates/interface/php/wrap/footer.php");
}
// Iframe admin pages
elseif (
isset($_GET['section']) && trim($_GET['section']) != '' && $ct['gen']->pass_sec_check($_GET['iframe'], 'iframe_' . $_GET['section'])
|| isset($_GET['plugin']) && trim($_GET['plugin']) != '' && $ct['gen']->pass_sec_check($_GET['iframe'], 'iframe_' . $_GET['plugin'])
) {
require("templates/interface/php/admin/admin-elements/admin-page-iframe.php");
}
// Security monitoring
elseif ( $ct['possible_input_injection'] ) {
     
$security_error_ui = 'Possible code injection attack stopped, please DO NOT attempt to inject scripting or HTML into user inputs.<br /><br />Updating of admin section "' . $ct['gen']->key_to_name($_POST['interface_id']) . '" FAILED.<br /><br /><a href="' . $base_url . $_SERVER['REQUEST_URI'] .'" style="font-weight: bold;">Return To The Previous Page</a>';

echo '<style>html,body,a{color:red;}</style>' . $security_error_ui;

// Log errors before exiting
// WE ALREADY QUEUED THE ERROR LOG ENTRY FOR THIS ISSUE IN: $ct['gen']->sanitize_string()
$ct['cache']->app_log();

exit;

}
else {
     
$security_error = 'Admin nonce expired / incorrect (from ' . $ct['remote_ip'] . '), try reloading the app';

$ct['gen']->log('security_error', $security_error);

echo '<style>html,body,a{color:red;}</style>' . $security_error . '.';

// Log errors before exiting
$ct['cache']->app_log();

exit;

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
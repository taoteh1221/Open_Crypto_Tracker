<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */

// Runtime mode
$runtime_mode = '2fa_setup';

// Change directory
chdir("../../../../");

// FOR SPEED, $runtime_mode 'captcha' only gets app config vars, some init.php, then the captcha library
require("app-lib/php/init.php");
 

// Security checks
if ( $admin_area_2fa == 'on' || $ct_gen->admin_logged_in() == false || !is_array($stored_admin_login) || !$app_host ||  !$ct_gen->pass_sec_check($_GET['2fa_setup'], '2fa_setup') ) {
$security_error = '2FA Setup access invalid / expired (' . $remote_ip . '). Try reloading the app.';
$ct_gen->log('security_error', $security_error);
echo $security_error;
// Log errors before exiting
$ct_cache->error_log();
exit;
}
 
 
// Log errors / debugging, send notifications
$ct_cache->debug_log();
$ct_cache->send_notifications();


// Credit to: https://www.rafaelwendel.com/en/2021/05/two-step-verification-with-php-and-google-authenticator/

//the "getUrl" method takes as a parameter: "username", "host", the key "secret",
// AND THE OPTIONAL 'data_only' (to generate QR locally for privacy)
$image_text = $totp_auth->getURL($stored_admin_login[0], $app_host, $auth_secret_2fa, 'data_only');

// outputs image directly into browser, as PNG stream 
QRcode::png($image_text, false, 3, 5);

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
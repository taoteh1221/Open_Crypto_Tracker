<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// QR code images
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/phpqrcode/qrlib.php');


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// 2FA (TOTP...Google Authenticator / Microsoft Authenticator / Authy / etc)

// Credit to: https://www.rafaelwendel.com/en/2021/05/two-step-verification-with-php-and-google-authenticator/
 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/google-authenticator/FixedBitNotation.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/google-authenticator/GoogleAuthenticatorInterface.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/google-authenticator/GoogleAuthenticator.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/google-authenticator/GoogleQrUrl.php');
 
$totp_auth = new \Google\Authenticator\GoogleAuthenticator();

$totp_base32 = new \Google\Authenticator\FixedBitNotation(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', true, true);


     // We don't want to expose the GLOBAL secret key during 2FA setup (via QR code), so when we set the 2FA secret key with
     // base32 encoding (a TOTP required spec), we USE THE (ripemd160) DIGEST of contcantenating the admin username, app server hostname, AND the GLOBAL secret key
     // (since base32 in NOT encrypting, it's encoded AND decoded without a key)
     if ( is_array($stored_admin_login) ) {
     $auth_secret_2fa = $totp_base32->encode( $ct['gen']->digest($stored_admin_login[0] . $ct['app_host'] . $auth_secret) );
     }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>
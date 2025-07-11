<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// 2FA (TOTP...Google Authenticator / Microsoft Authenticator / Authy / etc)

// Credit to: https://www.rafaelwendel.com/en/2021/05/two-step-verification-with-php-and-google-authenticator/
 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/google-authenticator/FixedBitNotation.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/google-authenticator/GoogleAuthenticatorInterface.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/google-authenticator/GoogleAuthenticator.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/google-authenticator/GoogleQrUrl.php');
 
$ct['auth_2fa'] = new \Google\Authenticator\GoogleAuthenticator();

$totp_base32 = new \Google\Authenticator\FixedBitNotation(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', true, true);


     // We don't want to expose the GLOBAL secret key during 2FA setup (via QR code), so when we set the 2FA secret key with
     // base32 encoding (a TOTP required spec), we USE THE (ripemd160) DIGEST of contcantenating the admin username, app server hostname, AND the GLOBAL secret key
     // (since base32 in NOT encrypting, it's encoded AND decoded without a key)
     if ( is_array($stored_admin_login) ) {
     $auth_secret_2fa = $totp_base32->encode( $ct['sec']->digest($stored_admin_login[0] . $ct['app_host'] . $auth_secret) );
     }
     

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// QR code images
if ( $ct['runtime_mode'] == '2fa_setup' || $ct['runtime_mode'] == 'qr_code' ) {
     
// Play it safe, and make sure we have a high enough memory limit
// (64M isn't enough, it makes the script throw an error 500)
ini_set('memory_limit', '128M');

require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-settings-container/SettingsContainerInterface.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-settings-container/SettingsContainerAbstract.php'); 

require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Common/Version.php');  
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Common/EccLevel.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Common/MaskPattern.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Common/Mode.php');  
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Common/BitBuffer.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Common/ReedSolomonEncoder.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Common/GenericGFPoly.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Common/GF256.php'); 

require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/QRCodeException.php');  
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/QROptionsTrait.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/QRCode.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/QROptions.php'); 

require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/QRMatrix.php');  
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/QRDataModeInterface.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/QRDataModeAbstract.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/QRCodeDataException.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/Number.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/AlphaNum.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/Kanji.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/Hanzi.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/Byte.php'); 
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Data/QRData.php'); 

require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Output/QRCodeOutputException.php');  
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Output/QROutputInterface.php');   
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Output/QROutputAbstract.php');   
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/php-qrcode/Output/QRGdImage.php');  

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>
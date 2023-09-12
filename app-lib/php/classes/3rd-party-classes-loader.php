<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// QR code images
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/phpqrcode/qrlib.php');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SMTP email setup (if needed...MUST RUN AFTER dynamic app config auto-adjust)
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $ct['conf']['comms']['smtp_login'] != '' && $ct['conf']['comms']['smtp_server'] != '' ) {

require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = $ct['gen']->smtp_vars();

// Initiation of the 3rd party SMTP class
$smtp = new SMTPMailer();

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////



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



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Telegram messages (alerts etc), with a telegram bot (if needed...MUST RUN AFTER dynamic app config auto-adjust)


// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
// MUST RUN #AS SOON AS POSSIBLE IN APP INIT#, SO TELEGRAM COMMS ARE ENABLED FOR #ALL# FOLLOWING LOGIC!
if ( trim($ct['conf']['ext_apis']['telegram_your_username']) != '' && trim($ct['conf']['ext_apis']['telegram_bot_name']) != '' && trim($ct['conf']['ext_apis']['telegram_bot_username']) != '' && $ct['conf']['ext_apis']['telegram_bot_token'] != '' ) {
    
$telegram_activated = true;

// Load class files
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/telegram-php/src/Autoloader.php');

// Initiate the bot for this chatroom
$telegram_bot = new Telegram\Bot($ct['conf']['ext_apis']['telegram_bot_token'], $ct['conf']['ext_apis']['telegram_bot_username'], $ct['conf']['ext_apis']['telegram_bot_name']);
$telegram_messaging = new Telegram\Receiver($telegram_bot);


        // If telegram messaging is activated, attempt to refresh the bot chat room data via the telegram API
        // (ONLY IF RUNTIME MODE IS #NOT# AJAX [as it slows down chart / news feed rendering significantly])
        if ( sizeof($telegram_user_data) < 1 && $ct['runtime_mode'] != 'ajax' ) {
        	
        $secure_128bit_hash = $ct['gen']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
        	
        	
        	// Halt the process if an issue is detected safely creating a random hash
        	if ( $secure_128bit_hash == false ) {
        		
        	$ct['gen']->log(
        				'security_error', 
        				'Cryptographically secure pseudo-random bytes could not be generated for cached telegram_user_data array (secured cache storage) suffix, cached telegram_user_data array creation aborted to preserve security'
        				);
        	
        	}
        	else {
        	
        	$telegram_user_data = $ct['api']->telegram('updates');
        		
        	$store_cached_telegram_user_data = json_encode($telegram_user_data, JSON_PRETTY_PRINT);
        		
        		// Need to check a few different possible results for no data found ("null" in quotes as the actual value is returned sometimes)
        		if ( $store_cached_telegram_user_data == false || $store_cached_telegram_user_data == null || $store_cached_telegram_user_data == "null" ) {
        		// Keep var num at end of error log
        		$ct['gen']->log('conf_error', 'CURRENT telegram configuration could not be checked, PLEASE RE-ENTER "/start" IN THE BOT CHATROOM, IN THE TELEGRAM APP');
        		}
        		else {
        		$ct['cache']->save_file($ct['base_dir'] . '/cache/secured/telegram_user_data_'.$secure_128bit_hash.'.dat', $store_cached_telegram_user_data);
        		}
        	
        	}
        
        
        }
        

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>
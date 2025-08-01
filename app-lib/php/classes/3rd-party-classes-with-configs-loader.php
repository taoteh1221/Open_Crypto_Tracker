<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// SMTP email setup (if needed...MUST RUN AFTER dynamic app config auto-adjust)
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $ct['conf']['comms']['smtp_login'] != '' && $ct['conf']['comms']['smtp_server'] != '' ) {

// Passing smtp server login vars to config structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = $ct['gen']->smtp_vars();
     
     
     // Only enable IF NOT AJAX RUNTIMES (for runtime optimizations, as ajax is always a secondary runtime),
     // AND THE SMTP SERVER IS VERIFIED AS ONLINE
     // THE ENTIRE APP (ESPECIALLY THE INTERFACE) WILL HANG / FREEZE, IF A SPECIFIED SMTP SERVER IS OFFLINE!!
     // (so we CANCEL using / enabling SMTP mail, if our server check FAILS)
     if ( $ct['runtime_mode'] != 'ajax' && $ct['gen']->smtp_server_online() ) {
          
     require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/smtp-mailer/SMTPMailer.php');
     
     // Initiation of the 3rd party SMTP class
     $smtp = new SMTPMailer();
     
     }
     // Otherwise, we need to flag SMTP is not ok GLOBALLY (as the SMTP server specified by the user has issues of some sort)
     // (we log these server offline issues, so users should see the issue in app logs / UI alerts)
     else {
     $ct['smtp_server_ok'] = false;
     }


}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// Telegram messages (alerts etc), with a telegram bot (if needed...MUST RUN AFTER dynamic app config auto-adjust)
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
// MUST RUN #AS SOON AS POSSIBLE IN APP INIT#, SO TELEGRAM COMMS ARE ENABLED FOR #ALL# FOLLOWING LOGIC!
if ( trim($ct['conf']['ext_apis']['telegram_your_username']) != '' && trim($ct['conf']['ext_apis']['telegram_bot_name']) != '' && trim($ct['conf']['ext_apis']['telegram_bot_username']) != '' && $ct['conf']['ext_apis']['telegram_bot_token'] != '' ) {
    
$ct['telegram_activated'] = true;

// Load class files
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party/telegram-php/src/Autoloader.php');

// Initiate the bot for this chatroom
$telegram_bot = new Telegram\Bot($ct['conf']['ext_apis']['telegram_bot_token'], $ct['conf']['ext_apis']['telegram_bot_username'], $ct['conf']['ext_apis']['telegram_bot_name']);
$ct['telegram_connect'] = new Telegram\Receiver($telegram_bot);


        // IF COMMS ARE ENABLED, AND THE BOT CHATROOM DATA IS NOT STORED, attempt to refresh it via the telegram API
        // (ONLY IF RUNTIME MODE IS UI / CRON [as this logic isn't compatible with any other runtimes])
        if ( $ct['conf']['comms']['allow_comms'] != 'off' && !is_array($ct['telegram_user_data']) || $ct['conf']['comms']['allow_comms'] != 'off' && is_array($ct['telegram_user_data']) && sizeof($ct['telegram_user_data']) < 1 ) {
        
        
            if ( $ct['runtime_mode'] == 'ui' || $ct['runtime_mode'] == 'cron' ) {
            
            $secure_128bit_hash = $ct['sec']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
             	
             	
             	  // Halt the process if an issue is detected safely creating a random hash
             	  if ( $secure_128bit_hash == false ) {
             		
             	  $ct['gen']->log(
             				'security_error', 
             				'Cryptographically secure pseudo-random bytes could not be generated for cached telegram_user_data array (secured cache storage) suffix, cached telegram_user_data array creation aborted to preserve security'
             				);
             	
             	  }
             	  else {
             	
             	  $ct['telegram_user_data'] = $ct['api']->telegram('updates');
             		
             	  $store_cached_telegram_user_data = json_encode($ct['telegram_user_data'], JSON_PRETTY_PRINT);
             		
             		  // Need to check a few different possible results for no data found ("null" in quotes as the actual value is returned sometimes)
             		  if ( $store_cached_telegram_user_data == false || $store_cached_telegram_user_data == null || $store_cached_telegram_user_data == "null" ) {
             		  $ct['gen']->log('conf_error', 'telegram bot data unavailable, PLEASE RE-ENTER "/start" IN BOT CHATROOM (IN TELEGRAM APP)');
             		  }
                      // If checks passed, update cache vars
             		  else {
                         
                      $telegram_conf_md5 = md5($ct['conf']['ext_apis']['telegram_your_username'] . $ct['conf']['ext_apis']['telegram_bot_username'] . $ct['conf']['ext_apis']['telegram_bot_name'] . $ct['conf']['ext_apis']['telegram_bot_token']);   
                      
                      $ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/telegram_conf_md5.dat', $telegram_conf_md5);
                     
             		  $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/telegram_user_data_'.$secure_128bit_hash.'.dat', $store_cached_telegram_user_data);
             		  
             		  }
             	
             	  }
        
        
            }
            
        
        }
        

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>
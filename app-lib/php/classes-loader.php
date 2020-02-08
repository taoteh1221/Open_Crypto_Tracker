<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// SMTP email setup (if needed...MUST RUN AFTER dynamic app config management)
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $app_config['smtp_email_login'] != '' && $app_config['smtp_email_server'] != '' ) {

require_once($base_dir . '/app-lib/php/classes/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config file structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = smtp_vars();
global $smtp_vars; // Needed for class compatibility (along with second instance in the class config_smtp.php file)

// Initiation of the 3rd party SMTP class
$smtp = new SMTPMailer();

}



// Sending yourself telegram messages (alerts etc), with a telegram bot (if needed...MUST RUN AFTER dynamic app config management)
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( trim($app_config['telegram_bot_name']) != '' && trim($app_config['telegram_bot_username']) != '' && $app_config['telegram_bot_token'] != '' ) {

// Load class files
require_once($base_dir . '/app-lib/php/classes/telegram-php/src/Autoloader.php');

// Get LATEST telegram chatroom data
$telegram_chatroom_latest = $telegram_chatroom[(sizeof($telegram_chatroom) - 1)];

// Initiate the bot for this chatroom
$telegram_bot = new Telegram\Bot($app_config['telegram_bot_token'], $app_config['telegram_bot_username'], $app_config['telegram_bot_name']);
$telegram_messaging = new Telegram\Receiver($telegram_bot);

}


 
 ?>
<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SMTP email setup (if needed...MUST RUN AFTER dynamic app config management)
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $ct_conf['comms']['smtp_login'] != '' && $ct_conf['comms']['smtp_server'] != '' ) {

require_once($base_dir . '/app-lib/php/classes/3rd-party/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = $ct_gen->smtp_vars();

// Initiation of the 3rd party SMTP class
$smtp = new SMTPMailer();

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Sending yourself telegram messages (alerts etc), with a telegram bot (if needed...MUST RUN AFTER dynamic app config management)

// https://core.telegram.org/bots/api

// https://core.telegram.org/bots/api#making-requests

// https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_send_updates_to}

// https://api.telegram.org/bot{my_bot_token}/deleteWebhook

// https://api.telegram.org/bot{my_bot_token}/getWebhookInfo


if ( $telegram_activated == 1 ) {

// Load class files
require_once($base_dir . '/app-lib/php/classes/3rd-party/telegram-php/src/Autoloader.php');

// Initiate the bot for this chatroom
$telegram_bot = new Telegram\Bot($ct_conf['comms']['telegram_bot_token'], $ct_conf['comms']['telegram_bot_username'], $ct_conf['comms']['telegram_bot_name']);
$telegram_messaging = new Telegram\Receiver($telegram_bot);

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 
 ?>
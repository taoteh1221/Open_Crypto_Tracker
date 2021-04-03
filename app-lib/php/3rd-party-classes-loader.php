<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SMTP email setup (if needed...MUST RUN AFTER dynamic app config management)
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $ocpt_conf['comms']['smtp_login'] != '' && $ocpt_conf['comms']['smtp_server'] != '' ) {

require_once($base_dir . '/app-lib/php/classes/3rd-party/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = $ocpt_gen->smtp_vars();

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
$telegram_bot = new Telegram\Bot($ocpt_conf['comms']['telegram_bot_token'], $ocpt_conf['comms']['telegram_bot_username'], $ocpt_conf['comms']['telegram_bot_name']);
$telegram_messaging = new Telegram\Receiver($telegram_bot);

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Google API (google home / translate APIs)
if ( $ocpt_conf['comms']['google_application_name'] != '' && $ocpt_conf['comms']['google_client_id'] != '' && $ocpt_conf['comms']['google_client_secret'] != '' && $webhook_key != '' ) {

// Based off: https://www.phpflow.com/php/php-web-application-authentication-using-google-oauth-2-0/

// Load class files
require_once($base_dir . '/app-lib/php/classes/3rd-party/google-api/vendor/autoload.php');
 
// GOOGLE HOME
//Create and Request to access Google API 
// We are using push notifications for the price alerts 
// (after firewalling the raspi with it's own subnetwork / WAP device, 
// and creating an authenticated google API action with our dynamic ip's URI)
// https://developers.google.com/assistant/engagement/notifications
// https://www.reddit.com/r/GoogleAssistantDev/comments/eptoha/need_google_home_api_authentication_locally_for/


// Google client instance
$google_client = new Google_Client();

// Google app details (https://developers.google.com/assistant/engagement/notifications)
$google_client->setApplicationName($ocpt_conf['comms']['google_application_name']);
$google_client->setClientId($ocpt_conf['comms']['google_client_id']);
$google_client->setClientSecret($ocpt_conf['comms']['google_client_secret']);
$google_client->setRedirectUri($base_url . 'webhook/' . $webhook_key);

// Google 0auth instance
$google_0auth = new Google_Service_Oauth2($google_client);


// GOOGLE TRANSLATE
// (NOT BUILT YET)

}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 
 ?>
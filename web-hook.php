<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'webhook';


// Load app config / etc
require("config.php");


// Webhook security check (hash must match our concatenated [service name + webhook key]'s hash, or we abort runtime)
// Using the hash of the concatenated [service name + webhook key] keeps our webhook key a secret, that only we know (for security)!
$webhook_hash = explode('/', $_GET['webhook_hash']); // Remove any data after the webhook hash



///////////////////////////////////////////////////////////////////////////////
// Google 0auth
if ( $webhook_hash[0] == get_digest('google-0auth' . $webhook_key) ) {
require_once($base_dir . '/app-lib/php/other/security/google-0auth.php');
}
///////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
// Telegram
elseif ( $webhook_hash[0] == get_digest('telegram' . $webhook_key) ) {

// https://core.telegram.org/bots/api

// https://core.telegram.org/bots/api#making-requests

// https://api.telegram.org/bot{my_bot_token}/setWebhook?url={url_to_send_updates_to}

// https://api.telegram.org/bot{my_bot_token}/deleteWebhook

// https://api.telegram.org/bot{my_bot_token}/getWebhookInfo


}
///////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
// Test only
elseif ( $webhook_hash[0] == get_digest('test-only' . $webhook_key) ) {

$test_params = array('api_key' => $api_key);
						
$test_data = @external_api_data('array', $test_params, 0, $base_url . 'api/market_conversion/eur/kraken-btc-usd,coinbase-dai-usdc,coinbase-eth-usd', 2);

// Already json-encoded
echo $test_data;

}
///////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
// No service
else {
$result = array('error' => "No service match for webhook: " . $webhook_hash[0]);
}
///////////////////////////////////////////////////////////////////////////////



// Return any results in json format
if ( isset($result) ) {
echo json_encode($result, JSON_PRETTY_PRINT);
}

//echo get_digest('test-only' . $webhook_key) . ' -- ';


// Log errors / debugging, send notifications
error_logs();
debugging_logs();
send_notifications();

?>



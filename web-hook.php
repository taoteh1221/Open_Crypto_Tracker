<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Runtime mode
$runtime_mode = 'webhook';


// Load app config / etc
require("app-lib/php/init.php");


header('Content-type: text/html; charset=' . $ct['dev']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (AS THIS IS A WEBHOOK ACCESS POINT)
header('Access-Control-Allow-Origin: *');

// Seems useful for javascript-based API connects: 
// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Credentials
header('Access-Control-Allow-Credentials: true'); 

// Ip address information
$ip_access_tracking = $ct['base_dir'] . '/cache/events/throttling/internal/local_api_incoming_ip_' . $ct['gen']->safe_name($ct['remote_ip']) . '.dat';


// Throttle ip addresses reconnecting before $ct['conf']['int_api']['api_rate_limit'] interval passes
if ( $ct['cache']->update_cache($ip_access_tracking, ($ct['conf']['int_api']['api_rate_limit'] / 60) ) == false ) {

// Log access event for this ip address (for throttling...no file lock for better performance)
$ct['cache']->save_file($ip_access_tracking, $ct['gen']->time_date_format(false, 'pretty_date_time'), false, false);

$result = array('error' => "Rate limit (maximum of once every " . $ct['conf']['int_api']['api_rate_limit'] . " seconds) reached for ip address: " . $ct['remote_ip']);

$ct['gen']->log(
			'int_webhook_error',
			'From ' . $ct['remote_ip'] . ' (Rate limit reached)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';'
			);

// JSON-encode results
echo json_encode($result, JSON_PRETTY_PRINT);

// Access stats logging
$ct['cache']->log_access_stats();

// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

exit;

}
else {
// Just log access event for this ip address (for throttling...no file lock for better performance)
$ct['cache']->save_file($ip_access_tracking, $ct['gen']->time_date_format(false, 'pretty_date_time'), false, false);
}
        

if ( isset($plug['activated']['webhook']) ) {
     
     
     foreach ( $plug['activated']['webhook'] as $plugin_key => $plugin_init ) {
             		
     $this_plug = $plugin_key;

     // Webhook security check (hash must match our concatenated [service name + webhook key]'s hash, or we abort runtime)
     // Using the hash of the concatenated [service name + webhook key] keeps our webhook key a secret, that only we know (for security)!
     $plug['webhook'][$this_plug]['key'] = preg_replace("/\/(.*)/", '', $_GET['webhook_params']); // Remove any (forwardslash-seperated) data after the webhook hash

             	
         if ( isset($ct['int_webhooks'][$this_plug]) && trim($ct['int_webhooks'][$this_plug]) != '' && $plug['webhook'][$this_plug]['key'] == $ct['sec']->nonce_digest($this_plug, $ct['int_webhooks'][$this_plug] . $webhook_master_key) ) {
              
         $webhook_key_exists = true; // Flag webhook service as found
         
         $plug['webhook'][$this_plug]['params'] = explode("/", $_GET['webhook_params']);
         unset($plug['webhook'][$this_plug]['params'][0]); // Remove webhook key
         $plug['webhook'][$this_plug]['params'] = array_values($plug['webhook'][$this_plug]['params']); // 'reindex' array
         
         // This plugin's plug-init.php file (runs the plugin)
         include($plugin_init);
             	
         }
             	
     // Reset $this_plug at end of loop
     unset($this_plug); 
             
     }

}
else {
$result = array('error' => "No registered webhooks");
echo json_encode($result, JSON_PRETTY_PRINT);
}


// If webhooks exist, BUT no matching webhook KEY was found
if ( isset($plug['activated']['webhook']) && !$webhook_key_exists ) {
$result = array('error' => "Webhook key mismatch: " . $plug['webhook'][$this_plug]['key']);
echo json_encode($result, JSON_PRETTY_PRINT);
}

// Access stats logging / etc
$ct['cache']->log_access_stats();
$ct['cache']->api_throttle_cache();
$ct['cache']->registered_light_charts_cache();

// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
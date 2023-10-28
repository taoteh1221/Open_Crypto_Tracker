<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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


// Webhook security check (hash must match our concatenated [service name + webhook key]'s hash, or we abort runtime)
// Using the hash of the concatenated [service name + webhook key] keeps our webhook key a secret, that only we know (for security)!
$webhook_key = preg_replace("/\/(.*)/", '', $_GET['webhook_params']); // Remove any (forwardslash-seperated) data after the webhook hash
        

if ( !isset($activated_plugins['webhook']) ) {
$result = array('error' => "No service match for webhook: " . $webhook_key);
echo json_encode($result, JSON_PRETTY_PRINT);
}

        
foreach ( $activated_plugins['webhook'] as $plugin_key => $plugin_init ) {
        		
$this_plug = $plugin_key;
        	
    if ( file_exists($plugin_init) && isset($int_webhooks[$this_plug]) && trim($int_webhooks[$this_plug]) != '' && $webhook_key == $ct['gen']->nonce_digest($this_plug, $int_webhooks[$this_plug] . $webhook_master_key) ) {
    
    $webhook_params = explode("/", $_GET['webhook_params']);
    unset($webhook_params[0]); // Remove webhook key
    $webhook_params = array_values($webhook_params); // 'reindex' array
    
    // This plugin's plug-init.php file (runs the plugin)
    include($plugin_init);
        	
    }
    else {
    $result = array('error' => "No service match for webhook: " . $webhook_key);
    echo json_encode($result, JSON_PRETTY_PRINT);
    }
        	
// Reset $this_plug at end of loop
unset($this_plug); 
        
}


// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
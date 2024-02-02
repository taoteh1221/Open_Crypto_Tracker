<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');

$int_webhook_base_endpoint = ( $ct['app_edition'] == 'server' || $ct['app_container'] == 'phpbrowserbox' ? 'hook/' : 'web-hook.php?webhook_params=' );

$test_params = array('api_key' => $int_api_key);


if ( !isset($webhook_params[0]) ) {
$result = array('error' => "No comms channel specified, please include AT LEAST ONE forwardslash-delimited parameter designating the service being used (telegram / discord / etc) like so: /" . $int_webhook_base_endpoint . $webhook_key . "/telegram/PARAM2/PARAM3/ETC");
echo json_encode($result, JSON_PRETTY_PRINT);
}
elseif ( $webhook_params[0] == 'discord' ) {
echo $plug['class'][$this_plug]->discord_data($test_params);
}
elseif ( $webhook_params[0] == 'telegram' ) {
echo $plug['class'][$this_plug]->telegram_data($test_params);
}
else {
$result = array('error' => "No comms channel match for: " . $webhook_params[0]);
echo json_encode($result, JSON_PRETTY_PRINT);
}


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
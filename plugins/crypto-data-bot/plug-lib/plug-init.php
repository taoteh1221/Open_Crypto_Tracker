<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


$test_params = array('api_key' => $int_api_key);


if ( $webhook_params[0] == 'discord' ) {
echo $plug_class[$this_plug]->discord_data($test_params);
}
elseif ( $webhook_params[0] == 'telegram' ) {
echo $plug_class[$this_plug]->telegram_data($test_params);
}
elseif ( !isset($webhook_params[0]) ) {
$result = array('error' => "No service specified, please include AT LEAST ONE forwardslash-delimited parameter designating the service being used (telegram / discord / etc) like so: /hook/" . $webhook_key . "/telegram/PARAM2/PARAM3/ETC");
echo json_encode($result, JSON_PRETTY_PRINT);
}
else {
$result = array('error' => "No service match for: " . $webhook_params[0]);
echo json_encode($result, JSON_PRETTY_PRINT);
}


// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
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
						
$test_data = @$ct_cache->ext_data('params', $test_params, 0, $base_url . 'api/market_conversion/eur/kraken-btc-usd,coinbase-dai-usd,coinbase-eth-usd', 2);

//echo $ct_gen->nonce_digest($this_plug, $webhook_master_key) . ' -- ';

// Already json-encoded
echo $test_data;

// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
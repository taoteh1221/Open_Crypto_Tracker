<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////


function btc_address_balance($address) {
 
global $this_plugin, $app_config;

// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
$recache = ( $plugin_config[$this_plugin]['alerts_freq_max'] >= 3 ? ($plugin_config[$this_plugin]['alerts_freq_max'] - 3) : $plugin_config[$this_plugin]['alerts_freq_max'] );

$json_string = 'https://blockchain.info/rawaddr/' . $address;
     
$jsondata = @external_api_data('url', $json_string, $recache);
     
$data = json_decode($jsondata, true);
   
return number_to_string( $data['final_balance'] / 100000000 ); // Convert sats to BTC

}


////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////


function eth_address_balance($address) {
 
global $this_plugin, $app_config;

// Take into account previous runtime (over start of runtime), and give 3 minutes wiggle room
$recache = ( $plugin_config[$this_plugin]['alerts_freq_max'] >= 3 ? ($plugin_config[$this_plugin]['alerts_freq_max'] - 3) : $plugin_config[$this_plugin]['alerts_freq_max'] );

$json_string = 'https://api.etherscan.io/api?module=account&action=balance&address='.$address.'&tag=latest&apikey=' . $app_config['general']['etherscanio_api_key'];
     
$jsondata = @external_api_data('url', $json_string, $recache);
     
$data = json_decode($jsondata, true);
   
return number_to_string( $data['result'] / 1000000000000000000 ); // Convert wei to ETH

}


////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////


?>



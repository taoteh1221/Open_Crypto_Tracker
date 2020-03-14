<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/CRON-PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// FILL IN YOUR NEW HNS ADDRESS, TO WATCH FOR YOUR AIRDROP
$hns_address = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';


/////////////////////////////////////////////////////////////////

// Get API data
$url_request = 'https://api.hnscan.com/txs?address='.$hns_address.'&limit=10&offset=0';

$api_data = @api_data('url', $url_request, 70); // Cache 70 minutes
    
$hns_data = json_decode($api_data, true);


/////////////////////////////////////////////////////////////////
    

// If airdrop has gone through, alert recipient (if not already alerted before)
if ( $hns_data['total'] >= 1 && !file_exists($base_dir . '/cache/events/hns_airdrop_alert.dat') ) {


	// Get amount airdropped
	foreach ( $hns_data['result'][0]['outputs'] as $key => $value ) {
	
		if ( $hns_data['result'][0]['outputs'][$key]['address'] == $hns_address ) {
		$hns_amount = number_to_string($hns_data['result'][0]['outputs'][$key]['value'] / 1000000); // Amount of HNS received 
		}
	
	}


// Get BTC value per token from namebase API
$hns_btc_value = asset_market_data('HNS', 'namebase', 'HNSBTC')['last_trade'];


// BTC value of amount of HNS received
$hns_amount_btc = $hns_amount * $hns_btc_value;


// Primary fiat currency value of amount of HNS received
$hns_amount_currency = $default_btc_primary_currency_value * $hns_amount_btc;


// Send alerts
$hns_message = 'Your have received ' . number_format($hns_amount, 6, '.', ',') . ' HNS airdrop tokens, worth ' . $app_config['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . number_format($hns_amount_currency, 2, '.', ',') . ' (' . number_format($hns_amount_btc, 8, '.', ',') . ' BTC).';


  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  				// Minimize function calls
  				$encoded_text_message = content_data_encoding($hns_message);
  				
          	$send_params = array(
          								'notifyme' => $hns_message,
          								'telegram' => $hns_message,
          								'text' => array(
          														// Unicode support included for text messages (emojis / asian characters / etc )
          														'message' => $encoded_text_message['content_output'],
          														'charset' => $encoded_text_message['charset']
          														),
          								'email' => array(
          														'subject' => 'HNS Airdrop Received',
          														'message' => $hns_message
          														)
          								);
          	
          	
          	
// Send notifications
@queue_notifications($send_params);

// Update the event tracking for this alert
store_file_contents($base_dir . '/cache/events/hns_airdrop_alert.dat', time_date_format(false, 'pretty_date_time') );

}

?>



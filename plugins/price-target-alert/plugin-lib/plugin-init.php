<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


foreach ( $plugin_config[$this_plugin]['price_targets'] as $target_key => $target_value ) {

$market_config = explode('_', $target_key);

$market_asset = strtoupper($market_config[0]);

$market_pairing = strtolower($market_config[1]);

$market_exchange = strtolower($market_config[2]);

$market_id = $app_config['portfolio_assets'][$market_asset]['market_pairing'][$market_pairing][$market_exchange];

$market_value = number_to_string( asset_market_data($market_asset, $market_exchange, $market_id)['last_trade'] );

$target_value = number_to_string($target_value);

$target_direction_file = $base_dir . '/cache/vars/price-target-direction-' . $target_key . '.dat';

	
	// Get target direction, or set it if not set yet
	if ( file_exists($target_direction_file) ) {
	$target_direction = trim( file_get_contents($target_direction_file) );
	}
	else {
	
		if ( $target_value >= $market_value ) {
		$target_direction = 'increase';
		}
		else {
		$target_direction = 'decrease';
		}
	
	store_file_contents($target_direction_file, $target_direction);
	
	// Skip the rest of this loop, as this was the first run, just setting the target direction var
	continue;
	
	}
	
	
	// If it's too early to re-send an alert again, skip the rest of this loop
	if ( update_cache_file($target_direction_file, ($plugin_config[$this_plugin]['alerts_freq_max'] * 60) ) == false ) {
	continue;
	}
	
	
	// If price target met
	if ( $market_value <= $target_value && $target_direction == 'decrease' || $market_value >= $target_value && $target_direction == 'increase' ) {


	$email_message = "The price target for " . $market_asset . " set to " . $target_value . " has been met at the " . snake_case_to_name($market_exchange) . " exchange. The " . $target_direction . "d market value is: " . $market_value;

	$text_message = $market_asset . " " . $target_value . " price target met @ " . snake_case_to_name($market_exchange) . " (" . $target_direction . "d): " . $market_value;


  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  	// Minimize function calls
  	$encoded_text_message = content_data_encoding($text_message); // Unicode support included for text messages (emojis / asian characters / etc )
  				
   $send_params = array(
          					'notifyme' => $email_message,
          					'telegram' => $email_message,
          					'text' => array(
          										'message' => $encoded_text_message['content_output'],
          										'charset' => $encoded_text_message['charset']
          											),
          					'email' => array(
          											'subject' => $market_asset . ' Price Target Alert',
          											'message' => $email_message
          											)
          					);
          	
          	
          	
	// Send notifications
	@queue_notifications($send_params);


		// Update the target direction file, with the new direction
		if ( $target_direction == 'decrease' ) {
		$target_direction = 'increase';
		}
		else {
		$target_direction = 'decrease';
		}
		
	store_file_contents($target_direction_file, $target_direction);

	}


}


?>



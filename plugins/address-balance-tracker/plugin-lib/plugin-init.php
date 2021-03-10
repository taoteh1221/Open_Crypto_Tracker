<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


foreach ( $plugin_config[$this_plugin]['tracking'] as $target_key => $target_value ) {
	
	
$balance_tracking_cache_file = plugin_vars_cache($target_key . '.dat');


	// If it's too early to re-send an alert again, skip this entry
	if ( update_cache_file($balance_tracking_cache_file, $plugin_config[$this_plugin]['alerts_freq_max']) == false ) {
	continue;
	}


$asset = strtolower($target_value['asset']);
$address = $target_value['address'];
$label = $target_value['label'];

// Only getting BTC value for non-bitcoin assets is supported
// SUPPORTED even for BTC ( pairing_btc_value('btc') ALWAYS = 1 )
$pairing_btc_value = pairing_btc_value($asset); 
  	 
  	 
	if ( $pairing_btc_value == null ) {
	app_logging('market_error', 'pairing_btc_value(\''.$asset.'\') returned null in the \''.$this_plugin.'\' plugin, likely from exchange API request failure');
	}

	
	// Detect which chain the address is on, set CURRENT (not cached) address balance
	if ( $asset == 'btc' ) {
	$address_balance = btc_address_balance($address);
	}
	elseif ( $asset == 'eth' ) {
	$address_balance = eth_address_balance($address);
	}
	

// Get primary currency value of the current address balance
$coin_primary_currency_worth_raw = number_to_string( ($address_balance * $pairing_btc_value) * $selected_btc_primary_currency_value );

$pretty_primary_currency_worth = pretty_numbers($coin_primary_currency_worth_raw, ( $coin_primary_currency_worth_raw >= 1.00 ? 2 : 5 ) );

$pretty_coin_amount = pretty_numbers($address_balance, 8);

	
	// Get cache data, and / or flag a cache reset
	if ( file_exists($balance_tracking_cache_file) ) {
		
	$balance_tracking_cache_data = explode('|', trim( file_get_contents($balance_tracking_cache_file) ) );
	
	$cached_address = $balance_tracking_cache_data[0];
	
	$cached_address_balance = number_to_string($balance_tracking_cache_data[1]);
	
		// If user changed the address in the config, flag a reset
		if ( $address != $cached_address ) {
		$cache_reset = true;
		}
	
	}
	else {
	$cache_reset = true;
	}
	
	
	// If a cache reset was flagged
	if ( $cache_reset ) {
		
	$new_cache_data = $address . '|' . $address_balance;
	
	store_file_contents($balance_tracking_cache_file, $new_cache_data);
	
	// Skip the rest, as this was setting / resetting cache data
	continue;
	
	}
	
	
	// If address balance has changed
	if ( $address_balance != $cached_address_balance ) {
		
		
		if ( $address_balance > $cached_address_balance ) {
		$direction = 'increase';
		}
		else {
		$direction = 'decrease';
		}


	$base_message = "The " . $label . " address balance has " . $direction . "d to " . $pretty_coin_amount . " " . strtoupper($asset) . " (". $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . $pretty_primary_currency_worth . ").";


		// Add blockchain explorer link to email message
		if ( $asset == 'btc' ) {
		$email_message = $base_message . " https://www.blockchain.com/btc/address/" . $address;
		}
		elseif ( $asset == 'eth' ) {
		$email_message = $base_message . " https://etherscan.io/address/" . $address;
		}


	$text_message = $label . " address balance " . $direction . ": " . $pretty_coin_amount . " " . strtoupper($asset) . " (". $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . $pretty_primary_currency_worth . ").";
              
   // Were're just adding a human-readable timestamp to smart home (audio) alerts
   $notifyme_message = $base_message . ' Timestamp: ' . time_date_format($app_config['general']['local_time_offset'], 'pretty_time') . '.';


  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  	// Minimize function calls
  	$encoded_text_message = content_data_encoding($text_message); // Unicode support included for text messages (emojis / asian characters / etc )
  				
   $send_params = array(
          					'notifyme' => $notifyme_message,
          					'telegram' => $email_message,
          					'text' => array(
          										'message' => $encoded_text_message['content_output'],
          										'charset' => $encoded_text_message['charset']
          											),
          					'email' => array(
          											'subject' => strtoupper($asset) . ' Address Balance ' . ucfirst($direction) . ' For: ' . $label,
          											'message' => $email_message
          											)
          					);
          	
          	
          	
	// Send notifications
	@queue_notifications($send_params);
		
	$new_cache_data = $address . '|' . $address_balance;
	
	store_file_contents($balance_tracking_cache_file, $new_cache_data);

	}


}


?>



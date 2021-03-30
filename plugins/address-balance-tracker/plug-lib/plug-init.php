<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


foreach ( $plug_conf[$this_plug]['tracking'] as $target_key => $target_value ) {
	
	
$balance_tracking_cache_file = $ocpt_plug->var_cache($target_key . '.dat');


	// If it's too early to re-send an alert again, skip this entry
	if ( update_cache($balance_tracking_cache_file, $plug_conf[$this_plug]['alerts_freq_max']) == false ) {
	continue;
	}


$asset = strtolower($target_value['asset']);
$address = $target_value['address'];
$label = $target_value['label'];

// Only getting BTC value for non-bitcoin assets is supported
// SUPPORTED even for BTC ( pairing_btc_value('btc') ALWAYS = 1 )
$pairing_btc_value = pairing_btc_value($asset); 
  	 
  	 
	if ( $pairing_btc_value == null ) {
	app_logging('market_error', 'pairing_btc_value(\''.$asset.'\') returned null in the \''.$this_plug.'\' plugin, likely from exchange API request failure');
	}

	
	// Detect which chain the address is on, set CURRENT (not cached) address balance
	if ( $asset == 'btc' ) {
	$address_balance = $plug_class[$this_plug]->btc_addr_bal($address);
	}
	elseif ( $asset == 'eth' ) {
	$address_balance = $plug_class[$this_plug]->eth_addr_bal($address);
	}
	

// Get primary currency value of the current address balance
$coin_prim_curr_worth_raw = $ocpt_var->num_to_str( ($address_balance * $pairing_btc_value) * $selected_btc_prim_curr_value );

$pretty_prim_curr_worth = $ocpt_var->num_pretty($coin_prim_curr_worth_raw, ( $coin_prim_curr_worth_raw >= 1.00 ? 2 : 5 ) );

$pretty_coin_amount = $ocpt_var->num_pretty($address_balance, 8);

	
	// Get cache data, and / or flag a cache reset
	if ( file_exists($balance_tracking_cache_file) ) {
		
	$balance_tracking_cache_data = explode('|', trim( file_get_contents($balance_tracking_cache_file) ) );
	
	$cached_address = $balance_tracking_cache_data[0];
	
	$cached_address_balance = $ocpt_var->num_to_str($balance_tracking_cache_data[1]);
	
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
	
	$ocpt_cache->save_file($balance_tracking_cache_file, $new_cache_data);
	
	// Skip the rest, as this was setting / resetting cache data
	continue;
	
	}
	
	
	// If address balance has changed
	if ( $address_balance != $cached_address_balance ) {
		
	// Balance change amount
	$difference_amount = abs( $ocpt_var->num_to_str($cached_address_balance - $address_balance) );
		
		if ( $address_balance > $cached_address_balance ) {
		$direction = 'increase';
		$plus_minus = '+';
		}
		else {
		$direction = 'decrease';
		$plus_minus = '-';
		}


	$base_message = "The " . $label . " address balance has " . $direction . "d (" . $plus_minus . $difference_amount . " " . strtoupper($asset) . "), to a new balance of " . $pretty_coin_amount . " " . strtoupper($asset) . " (". $ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']] . $pretty_prim_curr_worth . ").";


		// Add blockchain explorer link to email message
		if ( $asset == 'btc' ) {
		$email_message = $base_message . " https://www.blockchain.com/btc/address/" . $address;
		}
		elseif ( $asset == 'eth' ) {
		$email_message = $base_message . " https://etherscan.io/address/" . $address;
		}


	$text_message = $label . " address balance " . $direction . " (" . $plus_minus . $difference_amount . " " . strtoupper($asset) . "): " . $pretty_coin_amount . " " . strtoupper($asset) . " (". $ocpt_conf['power_user']['btc_currency_markets'][$ocpt_conf['general']['btc_prim_curr_pairing']] . $pretty_prim_curr_worth . ").";
              
   // Were're just adding a human-readable timestamp to smart home (audio) alerts
   $notifyme_message = $base_message . ' Timestamp: ' . time_date_format($ocpt_conf['general']['local_time_offset'], 'pretty_time') . '.';


  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  	// Minimize function calls
  	$encoded_text_message = $ocpt_gen->charset_encode($text_message); // Unicode support included for text messages (emojis / asian characters / etc )
  				
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
	@$ocpt_cache->queue_notify($send_params);
	
	
	// Cache new data
	$new_cache_data = $address . '|' . $address_balance;
	
	$ocpt_cache->save_file($balance_tracking_cache_file, $new_cache_data);

	}


}


?>



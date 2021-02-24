<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


if ( dir_structure($base_dir . '/cache/vars/price-target-alert/') != true ) {
app_logging('system_error', 'Could not create directory: /cache/vars/price-target-alert/');
}


foreach ( $plugin_config[$this_plugin]['price_targets'] as $target_key => $target_value ) {


$price_target_cache_file = $base_dir . '/cache/vars/price-target-alert/' . $target_key . '.dat';

$target_value = number_to_string($target_value);

$market_config = explode('-', $target_key);

$market_asset = strtoupper($market_config[0]);

$market_pairing = strtolower($market_config[1]);

$market_exchange = strtolower($market_config[2]);

$market_id = $app_config['portfolio_assets'][$market_asset]['market_pairing'][$market_pairing][$market_exchange];

$market_value = number_to_string( asset_market_data($market_asset, $market_exchange, $market_id)['last_trade'] );

	
	// Get cache data, and / or flag a cache reset
	if ( file_exists($price_target_cache_file) ) {
		
	$price_target_cache_data = explode('|', trim( file_get_contents($price_target_cache_file) ) );
	
	$target_direction = $price_target_cache_data[0];
	
	$cached_target_value = number_to_string($price_target_cache_data[1]);
	
	$cached_market_value = number_to_string($price_target_cache_data[2]);
	
		// If user changed the target value in the config, flag a reset
		if ( $target_value != $cached_target_value ) {
		$cache_reset = true;
		}
	
	}
	else {
	$cache_reset = true;
	}
	
	
	// If it's too early to re-send an alert again, skip the rest of this loop
	if ( update_cache_file($price_target_cache_file, ($plugin_config[$this_plugin]['alerts_freq_max'] * 60) ) == false ) {
	continue;
	}
	// If a cache reset was flagged
	elseif ( $cache_reset ) {
	
		if ( $target_value >= $market_value ) {
		$target_direction = 'increase';
		}
		else {
		$target_direction = 'decrease';
		}
		
	$new_cache_data = $target_direction . '|' . $target_value . '|' . $market_value;
	
	store_file_contents($price_target_cache_file, $new_cache_data);
	
	// Skip the rest of this loop, as this was setting / resetting target cache data
	continue;
	
	}
	
	
	// If price target met
	if ( $market_value <= $target_value && $target_direction == 'decrease' || $market_value >= $target_value && $target_direction == 'increase' ) {
		
		
   $last_cached_days = ( time() - filemtime($price_target_cache_file) ) / 86400;
   $last_cached_days = number_to_string($last_cached_days); // Better decimal support
       
       
   	if ( $last_cached_days >= 365 ) {
      $last_cached_time = number_format( ($last_cached_days / 365) , 2, '.', ',') . ' years';
      }
      elseif ( $last_cached_days >= 30 ) {
      $last_cached_time = number_format( ($last_cached_days / 30) , 2, '.', ',') . ' months';
      }
      elseif ( $last_cached_days >= 7 ) {
      $last_cached_time = number_format( ($last_cached_days / 7) , 2, '.', ',') . ' weeks';
      }
      else {
      $last_cached_time = number_format($last_cached_days, 2, '.', ',') . ' days';
      }
        

   $percent_change = ($market_value - $cached_market_value) / abs($cached_market_value) * 100;
   $percent_change = number_format( number_to_string($percent_change) , 2, '.', ','); // Better decimal support
   

	$email_message = "The " . $market_asset . " price target of " . $target_value . " " . strtoupper($market_pairing) . " has been met at the " . snake_case_to_name($market_exchange) . " exchange, with a " . $percent_change . "% " . $target_direction . " over the past " . $last_cached_time . " in market value to " . $market_value . " " . strtoupper($market_pairing) . ".";

	$text_message = $market_asset . " price target of " . $target_value . " " . strtoupper($market_pairing) . " met @ " . snake_case_to_name($market_exchange) . " (" . $percent_change . "% " . $target_direction . " over " . $last_cached_time . "): " . $market_value . " " . strtoupper($market_pairing);


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


		// Reset the cache data, since we ran an alert
		if ( $target_value >= $market_value ) {
		$target_direction = 'increase';
		}
		else {
		$target_direction = 'decrease';
		}
		
	$new_cache_data = $target_direction . '|' . $target_value . '|' . $market_value;
		
	store_file_contents($price_target_cache_file, $new_cache_data);

	}


}


?>



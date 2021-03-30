<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


foreach ( $plug_conf[$this_plug]['price_targets'] as $target_key => $target_value ) {


$price_target_cache_file = $ocpt_plug->var_cache($target_key . '.dat');


	// If it's too early to re-send an alert again, skip this entry
	if ( update_cache($price_target_cache_file, ($plug_conf[$this_plug]['alerts_freq_max'] * 60) ) == false ) {
	continue;
	}
	

$target_value = $ocpt_var->num_to_str($target_value);

$market_config = explode('-', $target_key);

$market_asset = strtoupper($market_config[0]);

$market_pairing = strtolower($market_config[1]);

$market_exchange = strtolower($market_config[2]);

$market_id = $ocpt_conf['assets'][$market_asset]['pairing'][$market_pairing][$market_exchange];

$market_value = $ocpt_var->num_to_str( $ocpt_api->market($market_asset, $market_exchange, $market_id)['last_trade'] );

	
	// Get cache data, and / or flag a cache reset
	if ( file_exists($price_target_cache_file) ) {
		
	$price_target_cache_data = explode('|', trim( file_get_contents($price_target_cache_file) ) );
	
	$target_direction = $price_target_cache_data[0];
	
	$cached_target_value = $ocpt_var->num_to_str($price_target_cache_data[1]);
	
	$cached_market_value = $ocpt_var->num_to_str($price_target_cache_data[2]);
	
		// If user changed the target value in the config, flag a reset
		if ( $target_value != $cached_target_value ) {
		$cache_reset = true;
		}
	
	}
	else {
	$cache_reset = true;
	}
	
	
	// If a cache reset was flagged
	if ( $cache_reset ) {
	
		if ( $target_value >= $market_value ) {
		$target_direction = 'increase';
		}
		else {
		$target_direction = 'decrease';
		}
		
	$new_cache_data = $target_direction . '|' . $target_value . '|' . $market_value;
	
	$ocpt_cache->save_file($price_target_cache_file, $new_cache_data);
	
	// Skip the rest, as this was setting / resetting cache data
	continue;
	
	}
	
	
	// If price target met
	if ( $market_value <= $target_value && $target_direction == 'decrease' || $market_value >= $target_value && $target_direction == 'increase' ) {
        

   $percent_change = ($market_value - $cached_market_value) / abs($cached_market_value) * 100;
   $percent_change = number_format( $ocpt_var->num_to_str($percent_change) , 2, '.', ','); // Better decimal support
		
		
   $last_cached_days = ( time() - filemtime($price_target_cache_file) ) / 86400;
   $last_cached_days = $ocpt_var->num_to_str($last_cached_days); // Better decimal support
       
       
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
   
   
   	// Pretty numbers UX on target / market values, for alert messages
   	// Fiat-eqiv
   	if ( array_key_exists($market_pairing, $ocpt_conf['power_user']['btc_currency_markets']) && !array_key_exists($market_pairing, $ocpt_conf['power_user']['crypto_pairing']) ) {
   		
		$target_value_text = ( $target_value >= $ocpt_conf['general']['prim_curr_dec_max_thres'] ? $ocpt_var->num_pretty($target_value, 2) : $ocpt_var->num_pretty($target_value, $ocpt_conf['general']['prim_curr_dec_max']) );
		
		$market_value_text = ( $market_value >= $ocpt_conf['general']['prim_curr_dec_max_thres'] ? $ocpt_var->num_pretty($market_value, 2) : $ocpt_var->num_pretty($market_value, $ocpt_conf['general']['prim_curr_dec_max']) );
		
		}
		// Crypto
		else {
		$target_value_text = $ocpt_var->num_pretty($target_value, 8);
		$market_value_text = $ocpt_var->num_pretty($market_value, 8);
		}
   

	$email_message = "The " . $market_asset . " price target of " . $target_value_text . " " . strtoupper($market_pairing) . " has been met at the " . snake_case_to_name($market_exchange) . " exchange, with a " . $percent_change . "% " . $target_direction . " over the past " . $last_cached_time . " in market value to " . $market_value_text . " " . strtoupper($market_pairing) . ".";

	$text_message = $market_asset . " price target of " . $target_value_text . " " . strtoupper($market_pairing) . " met @ " . snake_case_to_name($market_exchange) . " (" . $percent_change . "% " . $target_direction . " over " . $last_cached_time . "): " . $market_value_text . " " . strtoupper($market_pairing);
              
   // Were're just adding a human-readable timestamp to smart home (audio) alerts
   $notifyme_message = $email_message . ' Timestamp: ' . time_date_format($ocpt_conf['general']['local_time_offset'], 'pretty_time') . '.';


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
          											'subject' => $market_asset . ' / ' . strtoupper($market_pairing) . ' Price Target Alert (' . $target_direction . ')',
          											'message' => $email_message
          											)
          					);
          	
          	
          	
	// Send notifications
	@$ocpt_cache->queue_notify($send_params);


		if ( $target_value >= $market_value ) {
		$target_direction = 'increase';
		}
		else {
		$target_direction = 'decrease';
		}
	
	
	// Cache new data
	$new_cache_data = $target_direction . '|' . $target_value . '|' . $market_value;
		
	$ocpt_cache->save_file($price_target_cache_file, $new_cache_data);

	}


}


?>



<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


foreach ( $plug_conf[$this_plug]['price_targets'] as $target_key => $target_val ) {


$price_target_cache_file = $pt_plug->alert_cache($target_key . '.dat');


	// If it's too early to re-send an alert again, skip this entry
	if ( $pt_cache->update_cache($price_target_cache_file, $plug_conf[$this_plug]['alerts_freq_max']) == false ) {
	continue;
	}
	

$target_val = $pt_var->num_to_str($target_val);

$market_conf = explode('-', $target_key);

$market_asset = strtoupper($market_conf[0]);

$market_pairing = strtolower($market_conf[1]);

$market_exchange = strtolower($market_conf[2]);

$market_id = $pt_conf['assets'][$market_asset]['pairing'][$market_pairing][$market_exchange];

$market_val = $pt_var->num_to_str( $pt_api->market($market_asset, $market_exchange, $market_id)['last_trade'] );

	
	if ( $target_val >= $market_val ) {
	$target_direction = 'increase';
	}
	else {
	$target_direction = 'decrease';
	}
		
	
	// Get cache data, and / or flag a cache reset
	if ( file_exists($price_target_cache_file) ) {
		
	$price_target_cache_data = explode('|', trim( file_get_contents($price_target_cache_file) ) );
	
	$target_direction = $price_target_cache_data[0];
	
	$cached_target_val = $pt_var->num_to_str($price_target_cache_data[1]);
	
	$cached_market_val = $pt_var->num_to_str($price_target_cache_data[2]);
	
		// Flag a reset if user changed the target value in the config, 
		// OR the market value is still getting FURTHER from the target value (so we track when the trend reversed, via file timestamp)
		if (
		$target_val != $cached_target_val 
		|| $target_direction == 'increase' && $market_val < $cached_market_val 
		|| $target_direction == 'decrease' && $market_val > $cached_market_val
		) {
		$cache_reset = true;
		}
	
	}
	else {
	$cache_reset = true;
	}
	
	
	// If a cache reset was flagged
	if ( $cache_reset ) {
		
	$new_cache_data = $target_direction . '|' . $target_val . '|' . $market_val;
	
	$pt_cache->save_file($price_target_cache_file, $new_cache_data);
	
	// Skip the rest, as this was setting / resetting cache data
	continue;
	
	}
	
	
	// If price target met, send a notification...
	if ( $market_val <= $target_val && $target_direction == 'decrease' || $market_val >= $target_val && $target_direction == 'increase' ) {
        

   $percent_change = ($market_val - $cached_market_val) / abs($cached_market_val) * 100;
   $percent_change = number_format( $pt_var->num_to_str($percent_change) , 2, '.', ','); // Better decimal support
		
		
   $last_cached_days = ( time() - filemtime($price_target_cache_file) ) / 86400;
   $last_cached_days = $pt_var->num_to_str($last_cached_days); // Better decimal support
       
       
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
   	if ( array_key_exists($market_pairing, $pt_conf['power']['btc_currency_markets']) && !array_key_exists($market_pairing, $pt_conf['power']['crypto_pairing']) ) {
   		
		$target_val_text = ( $target_val >= $pt_conf['gen']['prim_currency_dec_max_thres'] ? $pt_var->num_pretty($target_val, 2) : $pt_var->num_pretty($target_val, $pt_conf['gen']['prim_currency_dec_max']) );
		
		$market_val_text = ( $market_val >= $pt_conf['gen']['prim_currency_dec_max_thres'] ? $pt_var->num_pretty($market_val, 2) : $pt_var->num_pretty($market_val, $pt_conf['gen']['prim_currency_dec_max']) );
		
		}
		// Crypto
		else {
		$target_val_text = $pt_var->num_pretty($target_val, 8);
		$market_val_text = $pt_var->num_pretty($market_val, 8);
		}
   

	$email_msg = "The " . $market_asset . " price target of " . $target_val_text . " " . strtoupper($market_pairing) . " has been met at the " . $pt_gen->key_to_name($market_exchange) . " exchange, with a " . $percent_change . "% " . $target_direction . " over the past " . $last_cached_time . " in market value to " . $market_val_text . " " . strtoupper($market_pairing) . ".";


	$text_msg = $market_asset . " price target of " . $target_val_text . " " . strtoupper($market_pairing) . " met @ " . $pt_gen->key_to_name($market_exchange) . " (" . $percent_change . "% " . $target_direction . " over " . $last_cached_time . "): " . $market_val_text . " " . strtoupper($market_pairing);
              
              
   // Were're just adding a human-readable timestamp to smart home (audio) alerts
   $notifyme_msg = $email_msg . ' Timestamp: ' . $pt_gen->time_date_format($pt_conf['gen']['loc_time_offset'], 'pretty_time') . '.';


  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  	// Minimize function calls
  	$encoded_text_msg = $pt_gen->charset_encode($text_msg); // Unicode support included for text messages (emojis / asian characters / etc )
  				
   $send_params = array(
          					'notifyme' => $notifyme_msg,
          					'telegram' => $email_msg,
          					'text' => array(
          										'message' => $encoded_text_msg['content_output'],
          										'charset' => $encoded_text_msg['charset']
          											),
          					'email' => array(
          											'subject' => $market_asset . ' / ' . strtoupper($market_pairing) . ' Price Target Alert (' . $target_direction . ')',
          											'message' => $email_msg
          											)
          					);
          	
          	
          	
	// Send notifications
	@$pt_cache->queue_notify($send_params);


		if ( $target_val >= $market_val ) {
		$target_direction = 'increase';
		}
		else {
		$target_direction = 'decrease';
		}
	
	
	// Cache new data
	$new_cache_data = $target_direction . '|' . $target_val . '|' . $market_val;
		
	$pt_cache->save_file($price_target_cache_file, $new_cache_data);

	}
	// END sending notification


}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
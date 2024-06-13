<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


// If user blanked out a SINGLE price target alert value via the admin interface,
// we need to unset the blank value to have the app logic run smoothly
// (as we require at least one blank value IN THE INTERFACE WHEN SUBMITTING UPDATES, TO ASSURE THE ARRAY IS NOT EXCLUDED from the CACHED config)
if ( is_array($plug['conf'][$this_plug]['price_targets']) && sizeof($plug['conf'][$this_plug]['price_targets']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $plug['conf'][$this_plug]['price_targets'] as $key => $val ) {
     
          if ( trim($val) == '' ) {
          unset($plug['conf'][$this_plug]['price_targets']);
          }
     
     }
     
}


// Remove any stale cache files
$alert_cache_files = $ct['gen']->sort_files( $ct['plug']->alert_cache(false) , 'dat', 'desc');
if ( is_array($plug['conf'][$this_plug]['price_targets']) && sizeof($plug['conf'][$this_plug]['price_targets']) != sizeof($alert_cache_files) ) {

    foreach ( $alert_cache_files as $check_file ) {
    
        if ( !array_key_exists( basename($check_file, '.dat') , $plug['conf'][$this_plug]['price_targets']) ) {
        unlink( $ct['plug']->alert_cache(false) . '/' . $check_file );
        }    
    
    }

}


// Check each configged price target alert
foreach ( $plug['conf'][$this_plug]['price_targets'] as $val ) {
	
// Clear any previous loop's $cache_reset var
$cache_reset = false;

$parse_attributes = explode('=', $val);
// Cleanup
$parse_attributes = array_map('trim', $parse_attributes);

$target_key = $parse_attributes[0];

$target_val = $parse_attributes[1];

$price_target_cache_file = $ct['plug']->alert_cache($target_key . '.dat');
	

$target_val = $ct['var']->num_to_str($target_val);

$mrkt_conf = explode('-', $target_key);

$mrkt_asset = strtoupper($mrkt_conf[0]);

$mrkt_pair = strtolower($mrkt_conf[1]);

$mrkt_exchange = strtolower($mrkt_conf[2]);

$mrkt_id = $ct['conf']['assets'][$mrkt_asset]['pair'][$mrkt_pair][$mrkt_exchange];

$mrkt_val = $ct['var']->num_to_str( $ct['api']->market($mrkt_asset, $mrkt_exchange, $mrkt_id)['last_trade'] );


	// If market value is zero, or it's too early to re-send an alert again, skip this entry
	if ( $mrkt_val == 0 || $ct['cache']->update_cache($price_target_cache_file, $plug['conf'][$this_plug]['alerts_frequency_maximum']) == false ) {
	continue;
	}
		
	
	// Get cache data, and / or flag a cache reset
	if ( file_exists($price_target_cache_file) ) {
		
	$price_target_cache_data = explode('|', trim( file_get_contents($price_target_cache_file) ) );
	
	$target_direction = $price_target_cache_data[0];
	
	$cached_target_val = $ct['var']->num_to_str($price_target_cache_data[1]);
	
	$cached_mrkt_val = $ct['var']->num_to_str($price_target_cache_data[2]);
	
		// Flag a reset if user changed the target value in the config, 
		// OR the market value is still getting FURTHER from the target value (so we track when the trend reversed, via file timestamp)
		if (
		$target_direction == ''
		|| $cached_target_val == ''
		|| $cached_mrkt_val == ''
		|| $target_val != $cached_target_val 
		|| $target_direction == 'increase' && $mrkt_val < $cached_mrkt_val 
		|| $target_direction == 'decrease' && $mrkt_val > $cached_mrkt_val
		) {
		$cache_reset = true;
		}
	
	}
	else {
	$cache_reset = true;
	}
	
	
	// If a cache reset was flagged
	if ( $cache_reset ) {
	
    	if ( $target_val >= $mrkt_val ) {
    	$reset_target_direction = 'increase';
    	}
    	else {
    	$reset_target_direction = 'decrease';
    	}
		
	$new_cache_data = $reset_target_direction . '|' . $target_val . '|' . $mrkt_val;
	
	$ct['cache']->save_file($price_target_cache_file, $new_cache_data);
	
	// Skip the rest, as this was setting / resetting cache data
	continue;
	
	}
	
	
	// If price target met, send a notification...
	if (
	$mrkt_val <= $target_val && $target_direction == 'decrease'
	|| $mrkt_val >= $target_val && $target_direction == 'increase'
	) {
         
     $divide_by = abs($cached_mrkt_val);


         if ( $divide_by > 0 ) {
         $percent_change = ($mrkt_val - $cached_mrkt_val) / $divide_by * 100;
         $percent_change = number_format( $ct['var']->num_to_str($percent_change) , 2, '.', ','); // Better decimal support
         }
         // Percent change is undefined when the divide by value is 0
         else {
         $percent_change = 0;
         }
         
         
         // If we were unable to determine a percentage change, skip notification
         if ( $percent_change == 0 ) {
         continue;
         }
		
		
     $last_cached_days = ( time() - filemtime($price_target_cache_file) ) / 86400;
     $last_cached_days = $ct['var']->num_to_str($last_cached_days); // Better decimal support
       
       
   	    if ( $last_cached_days >= 365 ) {
         $last_cached_time = number_format( ($last_cached_days / 365) , 2, '.', ',') . ' years';
         }
         elseif ( $last_cached_days >= 30 ) {
         $last_cached_time = number_format( ($last_cached_days / 30) , 2, '.', ',') . ' months';
         }
         elseif ( $last_cached_days >= 7 ) {
         $last_cached_time = number_format( ($last_cached_days / 7) , 2, '.', ',') . ' weeks';
         }
         elseif ( $last_cached_days >= 1 ) {
         $last_cached_time = number_format($last_cached_days, 2, '.', ',') . ' days';
         }
         elseif ( $last_cached_days >= (1 / 24) ) {
         $last_cached_time = number_format( ($last_cached_days * 24) , 2, '.', ',') . ' hours';
         }
         else {
         $last_cached_time = number_format( ($last_cached_days * 1440) , 0, '.', ',') . ' minutes';
         }
   
   
   	    // Pretty numbers UX on target / market values, for alert messages
   	    
   	    // Fiat-eqiv
   	    if ( array_key_exists($mrkt_pair, $ct['conf']['currency']['bitcoin_currency_markets']) ) {
   		$thres_dec_target = $ct['gen']->thres_dec($target_val, 'u', 'fiat'); // Units mode
   		$thres_dec_market = $ct['gen']->thres_dec($mrkt_val, 'u', 'fiat'); // Units mode
		}
		// Crypto
		else {
   		$thres_dec_target = $ct['gen']->thres_dec($target_val, 'u', 'crypto'); // Units mode
   		$thres_dec_market = $ct['gen']->thres_dec($mrkt_val, 'u', 'crypto'); // Units mode
		}
    
    
   	$target_val_text = $ct['var']->num_pretty($target_val, $thres_dec_target['max_dec'], false, $thres_dec_target['min_dec']);
   	$mrkt_val_text = $ct['var']->num_pretty($mrkt_val, $thres_dec_market['max_dec'], false, $thres_dec_market['min_dec']);
    
    
     // Message formatting
               
     // UX on stock symbols in alert messages (especially for alexa speaking alerts)
     $mrkt_asset_text = preg_replace("/stock/i", " STOCK", $mrkt_asset);

	$email_msg = "The " . $mrkt_asset_text . " price target of " . $target_val_text . " " . strtoupper($mrkt_pair) . " has been met at the " . $ct['gen']->key_to_name($mrkt_exchange) . " exchange, with a " . $percent_change . "% " . $target_direction . " over the past " . $last_cached_time . " in market value to " . $mrkt_val_text . " " . strtoupper($mrkt_pair) . ".";


	$text_msg = $mrkt_asset_text . " price target of " . $target_val_text . " " . strtoupper($mrkt_pair) . " met @ " . $ct['gen']->key_to_name($mrkt_exchange) . " (" . $percent_change . "% " . $target_direction . " over " . $last_cached_time . "): " . $mrkt_val_text . " " . strtoupper($mrkt_pair);
              
              
     // Were're just adding a human-readable timestamp to smart home (audio) alerts
     $notifyme_msg = $email_msg . ' Timestamp: ' . $ct['gen']->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';


  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  	// Minimize function calls
  	$text_msg = $ct['gen']->detect_unicode($text_msg); 
  				
     $send_params = array(
          					'notifyme' => $notifyme_msg,
          					'telegram' => $email_msg,
          					'text' => array(
          									'message' => $text_msg['content'],
          									'charset' => $text_msg['charset']
          									),
          					'email' => array(
          									'subject' => $mrkt_asset_text . ' / ' . strtoupper($mrkt_pair) . ' Price Target Alert (' . $target_direction . ')',
          									'message' => $email_msg
          									)
          					);
          	
          	
          	
	// Send notifications
	@$ct['cache']->queue_notify($send_params);


		if ( $target_val >= $mrkt_val ) {
		$reset_target_direction = 'increase';
		}
		else {
		$reset_target_direction = 'decrease';
		}
	
	
	// Cache new data
	$new_cache_data = $reset_target_direction . '|' . $target_val . '|' . $mrkt_val;
		
	$ct['cache']->save_file($price_target_cache_file, $new_cache_data);

	}
	// END sending notification


}


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
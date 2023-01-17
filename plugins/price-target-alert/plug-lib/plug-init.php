<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


// Remove any stale cache files
$alert_cache_files = $ct_gen->sort_files( $ct_plug->alert_cache(false) , 'dat', 'desc');
if ( is_array($plug_conf[$this_plug]['price_targets']) && sizeof($plug_conf[$this_plug]['price_targets']) != sizeof($alert_cache_files) ) {

    foreach ( $alert_cache_files as $check_file ) {
    
        if ( !array_key_exists( basename($check_file, '.dat') , $plug_conf[$this_plug]['price_targets']) ) {
        unlink( $ct_plug->alert_cache(false) . '/' . $check_file );
        }    
    
    }

}


// Check each configged price target alert
foreach ( $plug_conf[$this_plug]['price_targets'] as $target_key => $target_val ) {
	
// Clear any previous loop's $cache_reset var
$cache_reset = false;


$price_target_cache_file = $ct_plug->alert_cache($target_key . '.dat');


	// If it's too early to re-send an alert again, skip this entry
	if ( $ct_cache->update_cache($price_target_cache_file, $plug_conf[$this_plug]['alerts_freq_max']) == false ) {
	continue;
	}
	

$target_val = $ct_var->num_to_str($target_val);

$mrkt_conf = explode('-', $target_key);

$mrkt_asset = strtoupper($mrkt_conf[0]);

$mrkt_pair = strtolower($mrkt_conf[1]);

$mrkt_exchange = strtolower($mrkt_conf[2]);

$mrkt_id = $ct_conf['assets'][$mrkt_asset]['pair'][$mrkt_pair][$mrkt_exchange];

$mrkt_val = $ct_var->num_to_str( $ct_api->market($mrkt_asset, $mrkt_exchange, $mrkt_id)['last_trade'] );
		
	
	// Get cache data, and / or flag a cache reset
	if ( file_exists($price_target_cache_file) ) {
		
	$price_target_cache_data = explode('|', trim( file_get_contents($price_target_cache_file) ) );
	
	$target_direction = $price_target_cache_data[0];
	
	$cached_target_val = $ct_var->num_to_str($price_target_cache_data[1]);
	
	$cached_mrkt_val = $ct_var->num_to_str($price_target_cache_data[2]);
	
		// Flag a reset if user changed the target value in the config, 
		// OR the market value is still getting FURTHER from the target value (so we track when the trend reversed, via file timestamp)
		if (
		$target_val != $cached_target_val 
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
	
	$ct_cache->save_file($price_target_cache_file, $new_cache_data);
	
	// Skip the rest, as this was setting / resetting cache data
	continue;
	
	}
	
	
	// If price target met, send a notification...
	if ( $mrkt_val <= $target_val && $target_direction == 'decrease' || $mrkt_val >= $target_val && $target_direction == 'increase' ) {
        

    $percent_change = ($mrkt_val - $cached_mrkt_val) / abs($cached_mrkt_val) * 100;
    $percent_change = number_format( $ct_var->num_to_str($percent_change) , 2, '.', ','); // Better decimal support
		
		
    $last_cached_days = ( time() - filemtime($price_target_cache_file) ) / 86400;
    $last_cached_days = $ct_var->num_to_str($last_cached_days); // Better decimal support
       
       
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
   	    if ( array_key_exists($mrkt_pair, $ct_conf['power']['btc_currency_mrkts']) ) {
   		$thres_dec_target = $ct_gen->thres_dec($target_val, 'u', 'fiat'); // Units mode
   		$thres_dec_market = $ct_gen->thres_dec($mrkt_val, 'u', 'fiat'); // Units mode
		}
		// Crypto
		else {
   		$thres_dec_target = $ct_gen->thres_dec($target_val, 'u', 'crypto'); // Units mode
   		$thres_dec_market = $ct_gen->thres_dec($mrkt_val, 'u', 'crypto'); // Units mode
		}
    
    
   	$target_val_text = $ct_var->num_pretty($target_val, $thres_dec_target['max_dec'], false, $thres_dec_target['min_dec']);
   	$mrkt_val_text = $ct_var->num_pretty($mrkt_val, $thres_dec_market['max_dec'], false, $thres_dec_market['min_dec']);
    
    
    // Message formatting

	$email_msg = "The " . $mrkt_asset . " price target of " . $target_val_text . " " . strtoupper($mrkt_pair) . " has been met at the " . $ct_gen->key_to_name($mrkt_exchange) . " exchange, with a " . $percent_change . "% " . $target_direction . " over the past " . $last_cached_time . " in market value to " . $mrkt_val_text . " " . strtoupper($mrkt_pair) . ".";


	$text_msg = $mrkt_asset . " price target of " . $target_val_text . " " . strtoupper($mrkt_pair) . " met @ " . $ct_gen->key_to_name($mrkt_exchange) . " (" . $percent_change . "% " . $target_direction . " over " . $last_cached_time . "): " . $mrkt_val_text . " " . strtoupper($mrkt_pair);
              
              
    // Were're just adding a human-readable timestamp to smart home (audio) alerts
    $notifyme_msg = $email_msg . ' Timestamp: ' . $ct_gen->time_date_format($ct_conf['gen']['loc_time_offset'], 'pretty_time') . '.';


  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  	// Minimize function calls
  	$text_msg = $ct_gen->detect_unicode($text_msg); 
  				
    $send_params = array(
          					'notifyme' => $notifyme_msg,
          					'telegram' => $email_msg,
          					'text' => array(
          									'message' => $text_msg['content'],
          									'charset' => $text_msg['charset']
          									),
          					'email' => array(
          									'subject' => $mrkt_asset . ' / ' . strtoupper($mrkt_pair) . ' Price Target Alert (' . $target_direction . ')',
          									'message' => $email_msg
          									)
          					);
          	
          	
          	
	// Send notifications
	@$ct_cache->queue_notify($send_params);


		if ( $target_val >= $mrkt_val ) {
		$reset_target_direction = 'increase';
		}
		else {
		$reset_target_direction = 'decrease';
		}
	
	
	// Cache new data
	$new_cache_data = $reset_target_direction . '|' . $target_val . '|' . $mrkt_val;
		
	$ct_cache->save_file($price_target_cache_file, $new_cache_data);

	}
	// END sending notification


}


// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
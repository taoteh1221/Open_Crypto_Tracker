<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


foreach ( $plug_conf[$this_plug]['tracking'] as $target_key => $target_val ) {
	
// Clear any previous loop's $cache_reset var
$cache_reset = false;
	
$balance_tracking_cache_file = $ct_plug->alert_cache($target_key . '.dat');


	// If it's too early to re-send an alert again, skip this entry
	if ( $ct_cache->update_cache($balance_tracking_cache_file, $plug_conf[$this_plug]['alerts_freq_max']) == false ) {
	continue;
	}


$asset = trim( strtolower($target_val['asset']) );
$address = trim($target_val['address']);
$label = trim($target_val['label']);


// Only getting BTC value for non-bitcoin assets is supported
// SUPPORTED even for BTC ( $ct_asset->pairing_btc_val('btc') ALWAYS = 1 )
$pairing_btc_val = $ct_asset->pairing_btc_val($asset); 
  	 
  	 
	if ( $pairing_btc_val == null ) {
		
	$ct_gen->log(
				'market_error',
				'ct_asset->pairing_btc_val(\''.$asset.'\') returned null in the \''.$this_plug.'\' plugin, likely from exchange API request failure'
				);
	
	}

	
	// Detect which chain the address is on, set CURRENT (not cached) address balance
	if ( $asset == 'btc' ) {
	$address_balance = $plug_class[$this_plug]->btc_addr_bal($address);
	}
	elseif ( $asset == 'eth' ) {
	$address_balance = $plug_class[$this_plug]->eth_addr_bal($address);
	}
	elseif ( $asset == 'hnt' ) {
	$address_balance = $plug_class[$this_plug]->hnt_addr_bal($address);
	}
	elseif ( $asset == 'sol' ) {
	$address_balance = $plug_class[$this_plug]->sol_addr_bal($address);
	}
	
	
	// If we returned 'error' from a detected API error OR no address detected in config, skip this one for now
	if ( !$address || $address_balance == 'error' ) {
	    
	    if ( $address != '' ) {
        // Obfuscate any addresses in error / debug logs
        $plug_class[$this_plug]->obfusc_addr($address);
	    }
	    
	continue;
	
	}

	
	// Get cache data, and / or flag a cache reset
	if ( file_exists($balance_tracking_cache_file) ) {
		
	$balance_tracking_cache_data = explode('|', trim( file_get_contents($balance_tracking_cache_file) ) );
	
	$cached_address = trim($balance_tracking_cache_data[0]);
	
	$cached_address_balance = $ct_var->num_to_str($balance_tracking_cache_data[1]);
	
		// If user changed the address in the config OR no address detected in cache, flag a reset
		if ( !$cached_address || $address != $cached_address ) {
		$cache_reset = true;
		}
	
	}
	else {
	$cache_reset = true;
	}
	
	
// DEBUGGING ONLY
//$ct_cache->save_file( $ct_plug->alert_cache('debugging-' . $target_key . '.dat') , $cached_address_balance . '|' . $address_balance . '|' . $cache_reset );
	
	
	// If a cache reset was flagged
	if ( $cache_reset ) {
		
	$new_cache_data = $address . '|' . $address_balance;
	
	$ct_cache->save_file($balance_tracking_cache_file, $new_cache_data);
	
    // Obfuscate any addresses in error / debug logs
    $plug_class[$this_plug]->obfusc_addr($address);
	
	// Skip the rest, as this was setting / resetting cache data
	continue;
	
	}

	
	// If address balance has changed, send a notification...
	if ( $address_balance != $cached_address_balance ) {
		
		
	// Balance change amount
	$difference_amount = $ct_var->num_to_str( abs($cached_address_balance - $address_balance) );
		
		
		if ( $address_balance > $cached_address_balance ) {
		$direction = 'increase';
		$plus_minus = '+';
		}
		else {
		$direction = 'decrease';
		$plus_minus = '-';
		}

        
        if ( $plug_conf[$this_plug]['privacy_mode'] == 'on' ) {

        // Get primary currency value of the current address INCREASE / DECREASE amount only (for increased privacy in alerts)
        $asset_prim_currency_worth_raw = $ct_var->num_to_str( ($difference_amount * $pairing_btc_val) * $sel_opt['sel_btc_prim_currency_val'] );
        
        $pretty_prim_currency_worth = $ct_var->num_pretty($asset_prim_currency_worth_raw, ( $asset_prim_currency_worth_raw >= 1.00 ? 2 : $ct_conf['gen']['prim_currency_dec_max'] ) );
            
            
	    $base_msg = "The " . $label . " address balance has " . $direction . "d: ". $plus_minus . $ct_conf['power']['btc_currency_markets'][ $ct_conf['gen']['btc_prim_currency_pairing'] ] . $pretty_prim_currency_worth;
	    
	    
        $text_msg = $label . " address balance " . $direction . ": ". $plus_minus . $ct_conf['power']['btc_currency_markets'][ $ct_conf['gen']['btc_prim_currency_pairing'] ] . $pretty_prim_currency_worth;
	    
        }
        else {

        // Get primary currency value of the current address TOTAL balance
        $asset_prim_currency_worth_raw = $ct_var->num_to_str( ($address_balance * $pairing_btc_val) * $sel_opt['sel_btc_prim_currency_val'] );
        
        $pretty_prim_currency_worth = $ct_var->num_pretty($asset_prim_currency_worth_raw, ( $asset_prim_currency_worth_raw >= 1.00 ? 2 : $ct_conf['gen']['prim_currency_dec_max'] ) );
        
        $pretty_asset_amount = $ct_var->num_pretty($address_balance, 8);
            
            
	    $base_msg = "The " . $label . " address balance has " . $direction . "d (" . $plus_minus . $difference_amount . " " . strtoupper($asset) . "), to a new balance of " . $pretty_asset_amount . " " . strtoupper($asset) . " (". $ct_conf['power']['btc_currency_markets'][ $ct_conf['gen']['btc_prim_currency_pairing'] ] . $pretty_prim_currency_worth . ").";
	    
	    
        $text_msg = $label . " address balance " . $direction . " (" . $plus_minus . $difference_amount . " " . strtoupper($asset) . "): " . $pretty_asset_amount . " " . strtoupper($asset) . " (". $ct_conf['power']['btc_currency_markets'][ $ct_conf['gen']['btc_prim_currency_pairing'] ] . $pretty_prim_currency_worth . ").";
	    
        }


		// Add blockchain explorer link to email message
		if ( $asset == 'btc' ) {
		$email_msg = $base_msg . " https://www.blockchain.com/btc/address/" . $address;
		}
		elseif ( $asset == 'eth' ) {
		$email_msg = $base_msg . " https://etherscan.io/address/" . $address;
		}
		elseif ( $asset == 'hnt' ) {
		$email_msg = $base_msg . " https://explorer.helium.com/accounts/" . $address;
		}
		elseif ( $asset == 'sol' ) {
		$email_msg = $base_msg . " https://solscan.io/account/" . $address;
		}
              
              
    // Were're just adding a human-readable timestamp to smart home (audio) alerts
    $notifyme_msg = $base_msg . ' Timestamp: ' . $ct_gen->time_date_format($ct_conf['gen']['loc_time_offset'], 'pretty_time') . '.';


  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  	// Minimize function calls
  	$encoded_text_msg = $ct_gen->charset_encode($text_msg); // Unicode support included for text messages (emojis / asian characters / etc )
  				
    $send_params = array(
   
          				'notifyme' => $notifyme_msg,
          				
          				'telegram' => $email_msg,
          				
          				'text' => array(
          								'message' => $encoded_text_msg['content_output'],
          								'charset' => $encoded_text_msg['charset']
          								),
          								
          				'email' => array(
          								'subject' => strtoupper($asset) . ' Address Balance ' . ucfirst($direction) . ' For: ' . $label,
          								'message' => $email_msg
          								)
          								
          				);
          	
          	
          	
	// Send notifications
	@$ct_cache->queue_notify($send_params);
	
	// Cache new data
	$new_cache_data = $address . '|' . $address_balance;
	
	$ct_cache->save_file($balance_tracking_cache_file, $new_cache_data);

	}
	// END notification


// Obfuscate any addresses in error / debug logs
$plug_class[$this_plug]->obfusc_addr($address);


}


// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
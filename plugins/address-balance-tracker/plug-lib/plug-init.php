<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


// Remove any stale cache files
$loop = ( is_array($plug_conf[$this_plug]['tracking']) ? sizeof($plug_conf[$this_plug]['tracking']) : 0 );
while ( file_exists( $ct['plug']->alert_cache($loop . '.dat') ) ) {
unlink( $ct['plug']->alert_cache($loop . '.dat') );
$loop = $loop + 1;
}
$loop = null;


foreach ( $plug_conf[$this_plug]['tracking'] as $target_key => $target_val ) {
	
// Clear any previous loop's $cache_reset var
$cache_reset = false;
	
$balance_tracking_cache_file = $ct['plug']->alert_cache($target_key . '.dat');


	// If it's too early to re-send an alert again, skip this entry
	if ( $ct['cache']->update_cache($balance_tracking_cache_file, ($plug_conf[$this_plug]['alerts_frequency_maximum'] * 60) ) == false ) {
	continue;
	}


$asset = trim( strtolower($target_val['asset']) );

    if ( stristr($asset, '||') != false ) {
    $sub_asset = explode('||', $asset);
    $chain = $sub_asset[0];
    $asset = $sub_asset[1];
    }

$address = trim($target_val['address']);
$label = trim($target_val['label']);


    // Add this altcoin to $ct['conf']['power']['crypto_pair'] DYNAMICALLY #IF# it doesn't exist there, #IF# it has a BTC market configured
    // (For conversion of it's BTC value to the user's fiat value, set in $ct['conf']['gen']['bitcoin_primary_currency_pair'])
    if ( $asset != 'btc' && !isset($ct['conf']['power']['crypto_pair'][$asset]) && isset($ct['conf']['assets'][strtoupper($asset)]['pair']['btc']) ) {
    $ct['conf']['power']['crypto_pair'][$asset] = strtoupper($asset) . ' ';
    }


// Only getting BTC value for non-bitcoin assets is supported
// SUPPORTED even for BTC ( $ct['asset']->pair_btc_val('btc') ALWAYS = 1 )
$pair_btc_val = $ct['asset']->pair_btc_val($asset); 
  	 
  	 
	if ( $pair_btc_val == null ) {
		
	$ct['gen']->log(
				'market_error',
				'ct_asset->pair_btc_val(\''.$asset.'\') returned null in the \''.$this_plug.'\' plugin, likely from exchange API request failure'
				);
	
	}

	
	// Detect which chain the address is on, set CURRENT (not cached) address balance
	if ( $asset == 'btc' ) {
	$address_balance = $plug_class[$this_plug]->btc_addr_bal($address);
	}
	elseif ( $asset == 'eth' ) {
	$address_balance = $plug_class[$this_plug]->eth_addr_bal($address);
	}
	elseif ( $asset == 'sol' ) {
	$address_balance = $plug_class[$this_plug]->sol_addr_bal($address);
	}
	elseif ( $chain == 'eth' ) {
	$address_balance = $plug_class[$this_plug]->eth_addr_bal($address, $asset);
	}
	elseif ( $chain == 'sol' ) {
	$address_balance = $plug_class[$this_plug]->sol_addr_bal($address, $asset);
	}
	
	
	$address_balance = $ct['var']->num_to_str($address_balance);
	
	
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
	
	$cached_address_balance = $ct['var']->num_to_str($balance_tracking_cache_data[1]);
	
		// If user changed the address in the config OR no address detected in cache, flag a reset
		if ( !$cached_address || $address != $cached_address ) {
		$cache_reset = true;
		}
	
	}
	else {
	$cache_reset = true;
	}
	
	
// DEBUGGING ONLY
//$ct['cache']->save_file( $ct['plug']->alert_cache('debugging-' . $target_key . '.dat') , $cached_address_balance . '|' . $address_balance . '|' . $cache_reset );
	
	
	// If a cache reset was flagged
	if ( $cache_reset ) {
		
	$new_cache_data = $address . '|' . $address_balance;
	
	$ct['cache']->save_file($balance_tracking_cache_file, $new_cache_data);
	
     // Obfuscate any addresses in error / debug logs
     $plug_class[$this_plug]->obfusc_addr($address);
	
	// Skip the rest, as this was setting / resetting cache data
	continue;
	
	}

	
	// If address balance has changed, send a notification...
	if ( $address_balance != $cached_address_balance ) {
		
		
	// Balance change amount
	$difference_amnt = $ct['var']->num_to_str( abs($cached_address_balance - $address_balance) );
		
		
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
        $asset_prim_currency_worth_raw = $ct['var']->num_to_str( ($difference_amnt * $pair_btc_val) * $sel_opt['sel_btc_prim_currency_val'] );
        
        $pretty_prim_currency_worth = $ct['var']->num_pretty($asset_prim_currency_worth_raw, ( $asset_prim_currency_worth_raw >= 1.00 ? 2 : $ct['conf']['gen']['currency_decimals_max'] ) );
            
            
	   $base_msg = "The " . $label . " address balance has " . $direction . "d: ". $plus_minus . $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . $pretty_prim_currency_worth;
	    
	    
        $text_msg = $label . " address balance " . $direction . ": ". $plus_minus . $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . $pretty_prim_currency_worth;
	    
	    
	   $email_msg = $base_msg; // PRIVACY MODE (NO EXPLORER LINK APPENDED)

	    
        }
        else {

        // Get primary currency value of the current address TOTAL balance
        $asset_prim_currency_worth_raw = $ct['var']->num_to_str( ($address_balance * $pair_btc_val) * $sel_opt['sel_btc_prim_currency_val'] );
        
        $pretty_prim_currency_worth = $ct['var']->num_pretty($asset_prim_currency_worth_raw, ( $asset_prim_currency_worth_raw >= 1.00 ? 2 : $ct['conf']['gen']['currency_decimals_max'] ) );
        
        $pretty_asset_amnt = $ct['var']->num_pretty($address_balance, $ct['conf']['gen']['crypto_decimals_max']);
            
            
	    $base_msg = "The " . $label . " address balance has " . $direction . "d (" . $plus_minus . $difference_amnt . " " . strtoupper($asset) . "), to a new balance of " . $pretty_asset_amnt . " " . strtoupper($asset) . " (". $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . $pretty_prim_currency_worth . ").";
	    
	    
        $text_msg = $label . " address balance " . $direction . " (" . $plus_minus . $difference_amnt . " " . strtoupper($asset) . "): " . $pretty_asset_amnt . " " . strtoupper($asset) . " (". $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . $pretty_prim_currency_worth . ")";
	    

    		// Add blockchain explorer link to email message
    		if ( $asset == 'btc' ) {
    		$email_msg = $base_msg . " https://www.blockchain.com/btc/address/" . $address;
    		}
    		elseif ( $asset == 'eth' || $chain == 'eth' ) {
    		$email_msg = $base_msg . " https://etherscan.io/address/" . $address;
    		}
    		elseif ( $asset == 'sol' || $chain == 'sol' ) {
    		$email_msg = $base_msg . " https://solscan.io/account/" . $address;
    		}
		
	    
        }
              
              
    // Were're just adding a human-readable timestamp to smart home (audio) alerts
    // (add a period at end of message before timestamp if it's non-existant, so alexa pauses before speaking the timestamp)
    $notifyme_msg = $base_msg . ( substr( trim($base_msg) , -1) != '.' ? '.' : '' ) . ' Timestamp: ' . $ct['gen']->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';


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
          								'subject' => strtoupper($asset) . ' Address Balance ' . ucfirst($direction) . ' For: ' . $label,
          								'message' => $email_msg
          								)
          								
          				);
          	
          	
          	
	// Send notifications
	@$ct['cache']->queue_notify($send_params);
	
	// Cache new data
	$new_cache_data = $address . '|' . $address_balance;
	
	$ct['cache']->save_file($balance_tracking_cache_file, $new_cache_data);

	}
	// END notification

$chain = null;

// Obfuscate any addresses in error / debug logs
$plug_class[$this_plug]->obfusc_addr($address);


}


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
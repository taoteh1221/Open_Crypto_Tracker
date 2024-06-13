<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



//////////////////////////////////////////////////////////////////
// PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////



// If end user tries to use a FUTURES MARKET as the primary bitcoin exchange name,
// we disable and trigger a warning (SINCE VALUES OFTEN DON'T REFLECT NORMAL MARKETS)
if ( stristr($ct['conf']['gen']['bitcoin_primary_currency_exchange'], 'bitmex_') != false ) {
	
$ct['gen']->log(
			'conf_error',
			'bitcoin_primary_currency_exchange variable not properly set (futures markets are not allowed)',
			'bitcoin_primary_currency_exchange: ' . $ct['conf']['gen']['bitcoin_primary_currency_exchange'] . ';'
			);

$ct['conf']['gen']['bitcoin_primary_currency_exchange'] = 'futures_mrkts_not_allowed'; // DISABLE

}


// Re-set default primary currency 'pref_bitcoin_mrkts' value, ONLY IF THIS VALUE #EXISTS ALREADY#
// (for UX, to override the pre-existing value...if we have set this as the global default currency market, we obviously prefer it)
// SHOULD ONLY BE STATIC, NOT MANIPULATEBLE DYNAMICALLY IN THE INTERFACE...SO WE JUST RUN EARLY HERE ONLY IN INIT.
if ( isset($ct['conf']['currency']['bitcoin_preferred_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ]) ) {
$ct['conf']['currency']['bitcoin_preferred_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] = $ct['conf']['gen']['bitcoin_primary_currency_exchange'];
}



// Set chart/alert default Bitcoin markets
// BEFORE DEFAULT BITCOIN MARKET IS DYNAMICALLY MANIPULATED (during UI runtime)
// We NEVER change BTC / currency_mrkt value FOR CHARTS/ALERTS (during cron runtime), 
// so move the default $ct['conf']['gen']['bitcoin_primary_currency_pair'] / $ct['conf']['gen']['bitcoin_primary_currency_exchange'] values into their own chart/alerts related variables,
// before dynamic updating of $ct['conf']['gen']['bitcoin_primary_currency_pair'] / $ct['conf']['gen']['bitcoin_primary_currency_exchange']
$ct['default_bitcoin_primary_currency_pair'] = $ct['conf']['gen']['bitcoin_primary_currency_pair']; 
$ct['default_bitcoin_primary_currency_exchange'] = $ct['conf']['gen']['bitcoin_primary_currency_exchange'];


$check_default_bitcoin_primary_currency_pair = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/default_bitcoin_primary_currency_pair.dat') );

// RUN AFTER SETTING $ct['default_bitcoin_primary_currency_pair'] ABOVE
// If $ct['default_bitcoin_primary_currency_pair'] has changed, or never been set in cache vars, delete all potentially mismatched data and set in cache vars
if (
!file_exists($ct['base_dir'] . '/cache/vars/state-tracking/default_bitcoin_primary_currency_pair.dat')
|| $ct['default_bitcoin_primary_currency_pair'] != $check_default_bitcoin_primary_currency_pair
) {

// Delete OLD fiat price alerts cache data
$ct['gen']->del_all_files($ct['base_dir'] . '/cache/alerts/fiat_price'); 

// Delete OLD light charts SPOT PRICE data (automatically will trigger a light chart rebuild)
$ct['cache']->remove_dir($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/light');


     // Delete OLD *CONVERSION* price charts ONLY (NOT base-pair market price charts)
     foreach (  $ct['conf']['charts_alerts']['tracked_markets'] as $val ) {
            
     $val = array_map( "trim", explode("||", $val) );
     
     $market_pair_check = $val[2];
     
     $asset_ticker_check = ( stristr($val[0], "-") == false ? $val[0] : substr( $val[0], 0, mb_strpos($val[0], "-", 0, 'utf-8') ) );
     $asset_ticker_check = strtoupper($asset_ticker_check);
   
          if (
          $market_pair_check != $check_default_bitcoin_primary_currency_pair
          && file_exists($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/archival/'.$asset_ticker_check.'/'.$val[0].'_chart_'.$check_default_bitcoin_primary_currency_pair.'.dat')
          ) {
          unlink($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/archival/'.$asset_ticker_check.'/'.$val[0].'_chart_'.$check_default_bitcoin_primary_currency_pair.'.dat');
          }
     
     }


	// Delete show_charts cookie data
	if ( isset($_COOKIE['show_charts']) ) {
	unset($_COOKIE['show_charts']);
     $ct['gen']->store_cookie('show_charts', '', time()-3600); // Delete 
	}

	// Delete show_charts post data
	if ( isset($_POST['show_charts']) ) {
	$_POST['show_charts'] = null;  
	}


// Update cache var
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/default_bitcoin_primary_currency_pair.dat', $ct['default_bitcoin_primary_currency_pair']);

}




// Admin / charts / alerts / etc
if ( $is_admin || $ct['runtime_mode'] == 'cron' || $ct['runtime_mode'] == 'int_api' || $ct['runtime_mode'] == 'webhook' ) {


// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
$default_btc_pair_id = $ct['conf']['assets']['BTC']['pair'][$ct['default_bitcoin_primary_currency_pair']][$ct['default_bitcoin_primary_currency_exchange']];

$ct['default_bitcoin_primary_currency_val'] = $ct['api']->market('BTC', $ct['default_bitcoin_primary_currency_exchange'], $default_btc_pair_id)['last_trade'];
    
    
    // Log any charts/alerts Bitcoin market errors
    if ( !$ct['conf']['assets']['BTC']['pair'][$ct['default_bitcoin_primary_currency_pair']] ) {
    	
    $ct['gen']->log(
    			'conf_error',
    			'Charts / alerts bitcoin_primary_currency_pair variable not properly set: ' . $ct['default_bitcoin_primary_currency_pair']
    			);
    
    }
    elseif ( !$ct['conf']['assets']['BTC']['pair'][$ct['default_bitcoin_primary_currency_pair']][$ct['default_bitcoin_primary_currency_exchange']] ) {
    	
    $ct['gen']->log(
    			'conf_error',
    			'Charts / alerts bitcoin_primary_currency_exchange "' . $ct['gen']->key_to_name($ct['default_bitcoin_primary_currency_exchange']) . '" does NOT have a "' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . '" market'
    			);
    
    }
    
    if ( !isset($ct['default_bitcoin_primary_currency_val']) || $ct['default_bitcoin_primary_currency_val'] == 0 ) {
    	
    $ct['gen']->log(
    			'market_error',
    			'Charts / alerts Bitcoin primary currency market value not properly set: ' . $ct['default_bitcoin_primary_currency_val']
    			);
    
    }


// Set bitcoin market configs THAT ARE USUALLY DYNAMIC IN THE INTERFACE, to be the static default values during admin / cron / int_api / webhook runtimes
// (may change these to be dynamic in cron runtimes someday for a currently unforseen reason,
// so let's keep dynamic and default bitcoin market variables as separate entities for now)
$ct['sel_opt']['sel_btc_pair_id'] = $default_btc_pair_id;
$ct['sel_opt']['sel_btc_prim_currency_val'] = $ct['default_bitcoin_primary_currency_val'];


}
// NON-ADMIN UI etc
else {


    // If Stand-Alone Currency Market has been enabled (Settings page), REPLACE/OVERWRITE Bitcoin market config defaults
    if ( $_POST['prim_currency_mrkt_standalone'] || $_COOKIE['prim_currency_mrkt_standalone'] ) {
    	
    $ct['sel_opt']['prim_currency_mrkt_standalone'] = explode("|", ( $_POST['prim_currency_mrkt_standalone'] != '' ? $_POST['prim_currency_mrkt_standalone'] : $_COOKIE['prim_currency_mrkt_standalone'] ) );
    
    // MUST RUN !BEFORE! $ct['asset']->btc_mrkt() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR $ct['asset']->btc_mrkt() CALL
    $ct['conf']['gen']['bitcoin_primary_currency_pair'] = $ct['sel_opt']['prim_currency_mrkt_standalone'][0]; 
    
    
    	// (we go by array index number here, rather than 1 or higher for html form values)
        if ( $ct['sel_opt']['prim_currency_mrkt_standalone'][1] > 0 ) {
        $ct['conf']['gen']['bitcoin_primary_currency_exchange'] = $ct['asset']->btc_mrkt($ct['sel_opt']['prim_currency_mrkt_standalone'][1] - 1);
        }
        else {
        $ct['conf']['gen']['bitcoin_primary_currency_exchange'] = $ct['asset']->btc_mrkt(0);
        }
                
                
    }
    // Otherwise, just use the user-selected bitcoin market
    elseif ( $_POST['prim_currency_mrkt'] || $_COOKIE['prim_currency_mrkt'] ) {
    	
    $ct['sel_opt']['prim_currency_mrkt'] = explode("|", ( $_POST['prim_currency_mrkt'] != '' ? $_POST['prim_currency_mrkt'] : $_COOKIE['prim_currency_mrkt'] ) );
    
    // MUST RUN !BEFORE! $ct['asset']->btc_mrkt() CALL BELOW, OR INCORRECT VALUE DETERMINED FOR $ct['asset']->btc_mrkt() CALL
    $ct['conf']['gen']['bitcoin_primary_currency_pair'] = $ct['sel_opt']['prim_currency_mrkt'][0]; 
    
    
    	// (we go by array index number here, rather than 1 or higher for html form values)
        if ( $ct['sel_opt']['prim_currency_mrkt'][1] > 0 ) {
        $ct['conf']['gen']['bitcoin_primary_currency_exchange'] = $ct['asset']->btc_mrkt($ct['sel_opt']['prim_currency_mrkt'][1] - 1);
        }
        else {
        $ct['conf']['gen']['bitcoin_primary_currency_exchange'] = $ct['asset']->btc_mrkt(0);
        }
                
                
    }
    
    
// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime

$ct['sel_opt']['sel_btc_pair_id'] = $ct['conf']['assets']['BTC']['pair'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ][ $ct['conf']['gen']['bitcoin_primary_currency_exchange'] ];

$ct['sel_opt']['sel_btc_prim_currency_val'] = $ct['api']->market('BTC', $ct['conf']['gen']['bitcoin_primary_currency_exchange'], $ct['sel_opt']['sel_btc_pair_id'])['last_trade'];

    
    // Log any Bitcoin market errors
    if ( !$ct['conf']['assets']['BTC']['pair'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] ) {
    	
    $ct['gen']->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php bitcoin_primary_currency_pair variable not properly set', 
    			'bitcoin_primary_currency_pair: ' . $ct['conf']['gen']['bitcoin_primary_currency_pair'] . ';'
    			);
    
    }
    elseif ( !$ct['conf']['assets']['BTC']['pair'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ][ $ct['conf']['gen']['bitcoin_primary_currency_exchange'] ] ) {
    	
    $ct['gen']->log(
    			'conf_error',
    			'primary-bitcoin-markets-config.php bitcoin_primary_currency_exchange variable not properly set',
    			'bitcoin_primary_currency_exchange: ' . $ct['conf']['gen']['bitcoin_primary_currency_exchange'] . ';'
    			);
    
    }


}
    
    
// Dynamically modify MISCASSETS / ALTNFTS in $ct['conf']['assets']
// ONLY IF USER HASN'T MESSED UP $ct['conf']['assets'], AS WE DON'T WANT TO CANCEL OUT ANY
// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
if ( is_array($ct['conf']['assets']) ) {
$ct['conf']['assets']['MISCASSETS']['name'] = 'Misc. Assets (' . strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']) . ')';
$ct['conf']['assets']['ALTNFTS']['name'] = 'Alternate NFTs (' . strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']) . ')';
}
    
    
// Log an error if we have no minimum bitcoin primary currency value
if ( isset($ct['sel_opt']['sel_btc_prim_currency_val']) && $ct['var']->num_to_str($ct['sel_opt']['sel_btc_prim_currency_val']) >= $min_crypto_val_test ) {
// Continue
}
else {
    	
$ct['gen']->log(
    			'market_error',
    			'primary currency conversion market minimum value not met (for BTC / '.strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']).' @ '.$ct['conf']['gen']['bitcoin_primary_currency_exchange'].', value = ' . ( isset($ct['sel_opt']['sel_btc_prim_currency_val']) ? $ct['sel_opt']['sel_btc_prim_currency_val'] : 'not set' ) . ')'
			);
    
}


//////////////////////////////////////////////////////////////////
// END PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
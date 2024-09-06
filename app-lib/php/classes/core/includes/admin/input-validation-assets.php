<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

     	     
$update_config_error_seperator = '<br /> ';
        
  
// Make sure we have data, and not just an empty submission
// PHP7.4 NEEDS === HERE INSTEAD OF ==
if (
$_POST['markets_update'] === 'add' && !isset($_POST['assets'])
|| $_POST['markets_update'] === 'add' && is_array($_POST['assets']) && sizeof($_POST['assets']) < 1
) {
$ct['update_config_error'] .= 'Please select at least one asset market to add.';
}
elseif (
$_POST['markets_update'] === 'remove' && !isset($_POST['assets'])
|| $_POST['markets_update'] === 'remove' && is_array($_POST['assets']) && sizeof($_POST['assets']) < 1
) {
$ct['update_config_error'] .= 'Please select '.$_POST['remove_markets_mode'].' to remove.';
}
// PHP7.4 NEEDS === HERE INSTEAD OF ==
elseif ( $_POST['markets_update'] === 'add' && is_array($_POST['assets']) && sizeof($_POST['assets']) > 0 ) {


     foreach( $_POST['assets'] as $asset_key => $asset_val ) {

     
          if ( !isset($asset_val['name']) ) {
          $ct['update_config_error'] .= $update_config_error_seperator . 'Corrupt data ("name" data missing)';
          }
     
     
          if ( !isset($asset_val['mcap_slug']) ) {
          $ct['update_config_error'] .= $update_config_error_seperator . 'Corrupt data ("mcap_slug" data missing)';
          }
     
     
          if ( !isset($asset_val['pair']) ) {
          $ct['update_config_error'] .= $update_config_error_seperator . 'Corrupt data ("pair" data missing)';
          }
          elseif ( is_array($asset_val['pair']) && sizeof($asset_val['pair']) > 0 ) {
          
               foreach( $asset_val['pair'] as $pair_key => $pair_val ) {
                    
                    if ( !is_array($pair_val) || sizeof($pair_val) < 1 ) {
                    $ct['update_config_error'] .= $update_config_error_seperator . 'Corrupt data ("'.strtoupper($pair_key).'" pair data missing)';
                    }
                    elseif ( sizeof($pair_val) > 0 ) {
                    
                         foreach( $pair_val as $exchange_key => $exchange_val ) {
                         
                              if ( trim($exchange_val) == '' ) {
                              $ct['update_config_error'] .= $update_config_error_seperator . 'Corrupt data ("'.$ct['gen']->key_to_name($exchange_key).'" exchange data missing)';
                              }
                         
                         }
                    
                    }
               
               }

          }
          else {
          $ct['update_config_error'] .= $update_config_error_seperator . 'Corrupt data ("pair" data missing)';
          }

     
     }


}
// PHP7.4 NEEDS === HERE INSTEAD OF ==
elseif ( $_POST['markets_update'] === 'remove' && $_POST['remove_markets_mode'] == 'assets' && sizeof($_POST['assets']) > 0 ) {

     
     // ASSET does NOT exist
     foreach ( $_POST['assets'] as $posted_asset_key => $posted_asset_val ) {
     
          if ( !isset($ct['conf']['assets'][$posted_asset_key]) ) {
          $ct['update_config_error'] .= $update_config_error_seperator . 'ASSET "'.$posted_asset_key.'" does NOT EXIST in the current assets config';
          }
     
     }


}
// PHP7.4 NEEDS === HERE INSTEAD OF ==
elseif ( $_POST['markets_update'] === 'remove' && $_POST['remove_markets_mode'] == 'markets' && sizeof($_POST['assets']) > 0 ) {


     foreach ( $_POST['assets'] as $posted_asset_key => $posted_asset_val ) {
     
          
          // ASSET does NOT exist
          if ( !isset($ct['conf']['assets'][$posted_asset_key]) ) {
          $ct['update_config_error'] .= $update_config_error_seperator . 'ASSET "'.$posted_asset_key.'" does NOT EXIST in the current assets config';
          }

          
          foreach ( $posted_asset_val['pair'] as $posted_pair_key => $posted_pair_val ) {

               
               // PAIRING does NOT exist
               if ( !isset($ct['conf']['assets'][$posted_asset_key]['pair'][$posted_pair_key]) ) {
               $ct['update_config_error'] .= $update_config_error_seperator . 'PAIRING "'.strtoupper($posted_pair_key).'" does NOT EXIST for ASSET "'.$posted_asset_key.'", in the current assets config';
               }
               
               
               foreach ( $posted_pair_val as $posted_market_key => $posted_market_val ) {
               
          
                    // MARKET does NOT exist
                    if ( !isset($ct['conf']['assets'][$posted_asset_key]['pair'][$posted_pair_key][$posted_market_key]) ) {
                    $ct['update_config_error'] .= $update_config_error_seperator . 'MARKET "'.$ct['gen']->key_to_name($posted_market_key).'" does NOT EXIST for ASSET / PAIRING "'.$posted_asset_key.' / '.strtoupper($posted_pair_key).'", in the current assets config';
                    }
                    // MARKET ID does NOT match
                    elseif (
                    isset($ct['conf']['assets'][$posted_asset_key]['pair'][$posted_pair_key][$posted_market_key])
                    && $ct['conf']['assets'][$posted_asset_key]['pair'][$posted_pair_key][$posted_market_key] != $_POST['assets'][$posted_asset_key]['pair'][$posted_pair_key][$posted_market_key]
                    ) {
                    $ct['update_config_error'] .= $update_config_error_seperator . 'MARKET ID for "'.$ct['gen']->key_to_name($posted_market_key).'" does NOT MATCH for ASSET / PAIRING "'.$posted_asset_key.' / '.strtoupper($posted_pair_key).'", in the current assets config';
                    }
               
               
               }

          
          }

     
     }


}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
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
$_POST['markets_update'] === 'remove' && !isset($_POST['revised_markets'])
|| $_POST['markets_update'] === 'remove' && !isset($_POST['revised_assets'])
|| $_POST['markets_update'] === 'remove' && is_array($_POST['revised_markets']) && sizeof($_POST['revised_markets']) < 1
|| $_POST['markets_update'] === 'remove' && is_array($_POST['revised_assets']) && sizeof($_POST['revised_assets']) < 1
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
elseif ( $_POST['markets_update'] === 'remove' && is_array($_POST['revised_assets']) && sizeof($_POST['revised_assets']) > 0 ) {
$ct['update_config_error'] .= 'DEBUG TEST for removal of: ' . $_POST['remove_markets_mode'];
}
// PHP7.4 NEEDS === HERE INSTEAD OF ==
elseif ( $_POST['markets_update'] === 'remove' && is_array($_POST['revised_markets']) && sizeof($_POST['revised_markets']) > 0 ) {
$ct['update_config_error'] .= 'DEBUG TEST for removal of: ' . $_POST['remove_markets_mode'];
}

$ct['update_config_error'] .= 'DEBUG TEST for removal of: ' . $_POST['remove_markets_mode'];
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
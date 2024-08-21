<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

     	     
$update_config_error_seperator = '<br /> ';
        
  
// Make sure we have data, and not just an empty submission
if ( !isset($_POST['assets']) || is_array($_POST['assets']) && sizeof($_POST['assets']) < 1 ) {
$ct['update_config_error'] = 'Please select at least one asset market to add.';
}
elseif ( is_array($_POST['assets']) && sizeof($_POST['assets']) > 0 ) {


     foreach( $_POST['assets'] as $asset_key => $asset_val ) {
          
     // Remove everything NOT alphanumeric in NAME
     $_POST['assets'][$asset_key]['name'] = preg_replace("/[^0-9a-zA-Z]+/i", "", $_POST['assets'][$asset_key]['name']);
     // JUST TO BE SAFE, SANITIZE IT AS WELL (FOR INTERFACE RENDERING)
     $_POST['assets'][$asset_key]['name'] = htmlspecialchars($_POST['assets'][$asset_key]['name']);

     
          if ( !isset($asset_val['name']) ) {
          $ct['update_config_error'] = $update_config_error_seperator . 'Corrupt data ("name" data missing)';
          }
     
     
          if ( !isset($asset_val['mcap_slug']) ) {
          $ct['update_config_error'] = $update_config_error_seperator . 'Corrupt data ("mcap_slug" data missing)';
          }
     
     
          if ( !isset($asset_val['pair']) ) {
          $ct['update_config_error'] = $update_config_error_seperator . 'Corrupt data ("pair" data missing)';
          }
          elseif ( is_array($asset_val['pair']) && sizeof($asset_val['pair']) > 0 ) {
          
               foreach( $asset_val['pair'] as $pair_key => $pair_val ) {
                    
                    if ( !is_array($pair_val) || sizeof($pair_val) < 1 ) {
                    $ct['update_config_error'] = $update_config_error_seperator . 'Corrupt data ("'.strtoupper($pair_key).'" pair data missing)';
                    }
                    elseif ( sizeof($pair_val) > 0 ) {
                    
                         foreach( $pair_val as $exchange_key => $exchange_val ) {
                         
                              if ( trim($exchange_val) == '' ) {
                              $ct['update_config_error'] = $update_config_error_seperator . 'Corrupt data ("'.$ct['gen']->key_to_name($exchange_key).'" exchange data missing)';
                              }
                         
                         }
                    
                    }
               
               }

          }
          else {
          $ct['update_config_error'] = $update_config_error_seperator . 'Corrupt data ("pair" data missing)';
          }

     
     }


}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
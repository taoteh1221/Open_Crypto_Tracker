<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

$updated_assets_structure = array();

?>

<h3 class='red input_margins'>STEP #4: Review / Confirm <?=strtoupper($_POST['remove_markets_mode'])?> Removals</h3>   

ADD A CONFIRM JAVASCRIPT INSTANCE, BEFORE LETTING THE SUBMISSION HAPPEN, SINCE WE ARE DELETING THINGS!


<?php


if ( $_POST['remove_markets_mode'] == 'markets' && isset($_POST['remove_markets_asset']) && trim($_POST['remove_markets_asset']) != '' ) {

$updated_assets_structure['revised_markets'][ $_POST['remove_markets_asset'] ] = array();

     foreach ( $_POST as $parse_markets_key => $parse_markets_val ) {
          
          if ( isset($parse_markets_val['text']) && isset($parse_markets_val['children']) && is_array($parse_markets_val['children']) ) {
               
               foreach ( $parse_markets_val['children'] as $exchange ) {
               $updated_assets_structure['revised_markets'][ $_POST['remove_markets_asset'] ]['pair'][ $parse_markets_val['text'] ][ $exchange['text'] ] = true;
               }
          
          }
     
     }
    
}
elseif ( $_POST['remove_markets_mode'] == 'assets' ) {

     foreach ( $_POST as $parse_assets_key => $parse_assets_val ) {
          
          if ( isset($parse_assets_val['text']) ) {
          $updated_assets_structure['revised_assets'][ $parse_assets_val['text'] ] = true;
          }
     
     }
    
}


// DEBUGGING
if ( $ct['conf']['power']['debug_mode'] == 'setup_wizards_io' ) {
$ct['gen']->array_debugging($updated_assets_structure, true);
}


?>
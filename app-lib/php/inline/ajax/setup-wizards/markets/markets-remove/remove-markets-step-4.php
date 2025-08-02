<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

$updated_assets_structure = array();

$skip_assets = array(
          	                     'MISCASSETS',
          	                     'BTCNFTS',
          	                     'ETHNFTS',
          	                     'SOLNFTS',
          	                     'ALTNFTS',
          	                     'BTC',
          	                     'ETH',
          	                     'SOL',
          	                    );
          	                    

?>

<h3 class='red input_margins'>STEP #4: Review / Confirm <?=strtoupper($_POST['remove_markets_mode'])?> Removals</h3>  

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
     

     foreach ( $ct['conf']['assets'] as $existing_assets_key => $existing_assets_val ) {

          
          if ( !array_key_exists($existing_assets_key, $updated_assets_structure['revised_markets']) ) {
          continue;          
          }

     
          foreach ( $existing_assets_val['pair'] as $existing_pairs_key => $existing_pairs_val ) {
               
               
               // If PAIRING removed
               if ( !isset($updated_assets_structure['revised_markets'][$existing_assets_key]['pair'][$existing_pairs_key]) ) {
               $updated_assets_structure['assets'][$existing_assets_key]['pair'][$existing_pairs_key] = $ct['conf']['assets'][$existing_assets_key]['pair'][$existing_pairs_key];
               }
               
               
               foreach ( $existing_pairs_val as $existing_markets_key => $existing_markets_val ) {
               
               
                    // If MARKET removed
                    if ( !isset($updated_assets_structure['revised_markets'][$existing_assets_key]['pair'][$existing_pairs_key][$existing_markets_key]) ) {
                    $updated_assets_structure['assets'][$existing_assets_key]['pair'][$existing_pairs_key][$existing_markets_key] = $ct['conf']['assets'][$existing_assets_key]['pair'][$existing_pairs_key][$existing_markets_key];
                    }

               
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
     

     foreach ( $ct['conf']['assets'] as $existing_assets_key => $existing_assets_val ) {
     
          // If ASSET removed
          if ( !in_array($existing_assets_key, $skip_assets) && !isset($updated_assets_structure['revised_assets'][$existing_assets_key]) ) {
          $updated_assets_structure['assets'][$existing_assets_key] = true;
          }
     
     }
     
}


// If we have detected markets to remove
if (
is_array($updated_assets_structure['assets']) && sizeof($updated_assets_structure['assets']) > 0
) {
?>

     
     	<button class='force_button_style result_margins red' onclick='
     	
     	ct_ajax_load("type=remove_markets&step=3", "#update_markets_ajax", "<?=strtoupper($_POST['remove_markets_mode'])?> removal", prev_post_data, true); // Secured
     	
     	'> Go Back To Change Removed <?=strtoupper($_POST['remove_markets_mode'])?> </button>
     
     
     	<button class='force_button_style result_margins green' onclick='
     	
     	var confirm_removing = confirm("Click OK to continue removing <?=strtoupper($_POST['remove_markets_mode'])?> (this will ALSO remove any prices charts for this asset!).");
     	
               if ( !confirm_removing ) {
               return false;         
               }
     	
     	
     	var post_data = {
     	     
     	                  "conf_id": "assets",

     	                  // Use the PARENT ID, if there are interface subsections (since we are using the parent IFRAME)
     	                  "interface_id": "asset_tracking",
     	                  "admin_nonce": "<?=$ct['sec']->admin_nonce('asset_tracking')?>",

     	                  // Secured flag, for mode (add or remove)
     	                  "markets_update": "remove",
     	                  "markets_nonce": "<?=$ct['sec']->admin_nonce('remove')?>",

     	                  "refresh": "all",
     	                  
     	                  "remove_markets_mode": "<?=$_POST['remove_markets_mode']?>",

     	                   };
     	
     	var merged_data = merge_objects(post_data, updated_markets_post_data);
     	
     	ct_ajax_load("type=remove_markets&step=5", "#update_markets_ajax", "remove <?=strtoupper($_POST['remove_markets_mode'])?> results", merged_data, true, true); // Secured / sort tables
     	
     	'> Remove <?=strtoupper($_POST['remove_markets_mode'])?> </button>
     	
     	
     	<br clear='all' />
     	
     	
<?php
     
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_tickers_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_tickers_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */


     foreach ( $updated_assets_structure['assets'] as $asset_key => $asset_data ) {
     ?>
     
     <div style='font-weight: bold;' class='blue clear_both result_margins'><?=strtoupper($asset_key)?></div>
     
     
     <?php
     if ( $_POST['remove_markets_mode'] == 'markets' ) {
     ?>
     
     
     <div class='align_left clear_both result_margins'>
     
          <?php
          foreach ( $asset_data['pair'] as $pair_key => $pair_data ) {
          ?>
          <div style='font-weight: bold;' class='green clear_both result_margins'><?=strtoupper($pair_key)?></div>
          
          <div class='align_left clear_both result_margins'>
          
               <?php
               foreach ( $pair_data as $market_key => $market_data ) {
               ?>
               
               <div style='margin-left: 1em;'>
                    
                    <a class='bitcoin clear_both' href='javascript:' title='CONFIRM all market details, before removing them from the app.'><?=$ct['gen']->key_to_name($market_key)?></a>
                    
                    <div class='align_left clear_both'>
                    
                    <p>
                    
                    <span class='light_sea_green'>Market ID:</span> <?=$market_data?>
                    
                    </p>
                    
                    </div>
               
               </div>
               
               <?php
               }
               ?>
          
          </div>
          
          <?php
          }
          ?>
     
     </div>
          
     <?php
     }
     ?>
     
     <?php
     }

?>
     
     	<button class='force_button_style result_margins red' onclick='
     	
     	ct_ajax_load("type=remove_markets&step=3", "#update_markets_ajax", "<?=strtoupper($_POST['remove_markets_mode'])?> removal", prev_post_data, true); // Secured
     	
     	'> Go Back To Change Removed <?=strtoupper($_POST['remove_markets_mode'])?> </button>
     
     
     	<button class='force_button_style result_margins green' onclick='
     	
     	var confirm_removing = confirm("Click OK to continue removing <?=strtoupper($_POST['remove_markets_mode'])?> (this will ALSO remove any prices charts for this asset!).");
     	
               if ( !confirm_removing ) {
               return false;         
               }
     	
     	var post_data = {
     	     
     	                  "conf_id": "assets",

     	                  // Use the PARENT ID, if there are interface subsections (since we are using the parent IFRAME)
     	                  "interface_id": "asset_tracking",
     	                  "admin_nonce": "<?=$ct['sec']->admin_nonce('asset_tracking')?>",

     	                  // Secured flag, for mode (add or remove)
     	                  "markets_update": "remove",
     	                  "markets_nonce": "<?=$ct['sec']->admin_nonce('remove')?>",

     	                  "refresh": "all",
     	                  
     	                  "remove_markets_mode": "<?=$_POST['remove_markets_mode']?>",

     	                   };
     	
     	var merged_data = merge_objects(post_data, updated_markets_post_data);
     	
     	ct_ajax_load("type=remove_markets&step=5", "#update_markets_ajax", "remove <?=strtoupper($_POST['remove_markets_mode'])?> results", merged_data, true, true); // Secured / sort tables
     	
     	'> Remove <?=strtoupper($_POST['remove_markets_mode'])?> </button>
     	
     	
     	<br clear='all' />
     	


     <script>
     
     var updated_markets_post_data = <?php echo json_encode( array('assets' => $updated_assets_structure['assets']) ); ?>;
     	                          
     </script>
     

<?php
}
else {
?>
  
  <p class='red result_margins' style='font-weight: bold;'>Please select asset markets to remove.</p>
  
<?php
}


// DEBUGGING
if ( $ct['conf']['power']['debug_mode'] == 'setup_wizards_io' ) {
$ct['gen']->array_debugging($updated_assets_structure, true);
}


?>
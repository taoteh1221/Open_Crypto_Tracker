<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");
     
?>


<h3 class='green input_margins'>STEP #4: Review / Confirm Selected Markets</h3>


<?php
if ( is_array($_POST['assets']) && sizeof($_POST['assets']) > 0 ) {
?>

     
     	<button class='force_button_style result_margins red' onclick='
     	
     	ct_ajax_load("type=add_markets&step=3", "#update_markets_ajax", "market search results", selected_markets_post_data, true); // Secured
     	
     	'> Go Back To Change Selected Markets </button>
     
     
     	<button class='force_button_style result_margins green' onclick='
     	
     	var post_data = {
     	     
     	                  "conf_id": "assets",

     	                  // Use the PARENT ID, if there are interface subsections (since we are using the parent IFRAME)
     	                  "interface_id": "asset_tracking",
     	                  "admin_nonce": "<?=$ct['sec']->admin_nonce('asset_tracking')?>",

     	                  // Secured flag, for mode (add or remove)
     	                  "markets_update": "add",
     	                  "markets_nonce": "<?=$ct['sec']->admin_nonce('add')?>",

     	                  "refresh": "all",

     	                   };
     	
     	var merged_data = merge_objects(post_data, selected_markets_post_data);
     	
     	ct_ajax_load("type=add_markets&step=5", "#update_markets_ajax", "add market results", merged_data, true, true); // Secured / sort tables
     	
     	'> Add Selected Markets </button>
     	
     	
     	<br clear='all' />
     	
     	
<?php
     
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_tickers_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_tickers_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */


     foreach ( $_POST['assets'] as $asset_key => $asset_data ) {
     ?>
     
     <div style='font-weight: bold;' class='blue clear_both result_margins'><?=strtoupper($asset_key)?></div>
     
     <div class='align_left clear_both result_margins'>
     
          <div style='font-weight: bold;' class='green clear_both result_margins'>Name:</div> 
          
          <div class='align_left clear_both result_margins'>
          <?=$asset_data['name']?> <span class='bitcoin'>(EDITABLE after adding [SKIPPED if already exists])</span>
          </div>
     
     
          <?php
          if ( isset($asset_data['mcap_slug']) && trim($asset_data['mcap_slug']) != '' ) {
          ?>
          <div style='font-weight: bold;' class='green clear_both result_margins'>Marketcap Slug (page):</div> 
          
          <div class='align_left clear_both result_margins'>
          <?=$asset_data['mcap_slug']?> <span class='bitcoin'>(EDITABLE after adding [SKIPPED if already exists])</span>
          </div>
          <?php
          }


          foreach ( $asset_data['pair'] as $pair_key => $pair_data ) {
          ?>
          <div style='font-weight: bold;' class='green clear_both result_margins'><?=strtoupper($pair_key)?></div>
          
          <div class='align_left clear_both result_margins'>
          
               <?php
               foreach ( $pair_data as $market_key => $market_data ) {
               ?>
               
               <div style='margin-left: 1em;'>
                    
                    <a class='bitcoin clear_both' href='javascript:' title='CONFIRM all market details, before adding them to the app.'><?=$ct['gen']->key_to_name($market_key)?></a>
                    
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
     
     	<button class='force_button_style result_margins red' onclick='
     	
     	ct_ajax_load("type=add_markets&step=3", "#update_markets_ajax", "market search results", selected_markets_post_data, true); // Secured
     	
     	'> Go Back To Change Selected Markets </button>
     
     
     	<button class='force_button_style result_margins green' onclick='
     	
     	var post_data = {
     	     
     	                  "conf_id": "assets",

     	                  // Use the PARENT ID, if there are interface subsections (since we are using the parent IFRAME)
     	                  "interface_id": "asset_tracking",
     	                  "admin_nonce": "<?=$ct['sec']->admin_nonce('asset_tracking')?>",

     	                  // Secured flag, for mode (add or remove)
     	                  "markets_update": "add",
     	                  "markets_nonce": "<?=$ct['sec']->admin_nonce('add')?>",

     	                  "refresh": "all",

     	                   };
     	
     	var merged_data = merge_objects(post_data, selected_markets_post_data);
     	
     	ct_ajax_load("type=add_markets&step=5", "#update_markets_ajax", "add market results", merged_data, true, true); // Secured / sort tables
     	
     	'> Add Selected Asset Markets </button>
     	
     	
     	<br clear='all' />
     	

     <script>
     
     var selected_markets_post_data = <?php echo json_encode($_POST); ?>;
     	                          
     </script>
     
<?php
}
else {
?>

  <p class='red result_margins' style='font-weight: bold;'>Please select asset markets to add.</p>

<?php
}
?>

     
     
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Assures we are getting cached data for this EXACT user login SESSION only
$recent_search_id = 'add_asset_search_' . md5(session_id() . $ct['remote_ip']);   
   
   
if ( $_POST['add_markets_search'] ) {
     
     // ALL / specific exchange
     if ( $_POST['add_markets_search_exchange'] != 'all_exchanges' ) {
     $specific_exchange = $_POST['add_markets_search_exchange'];
     }
     else {
     $specific_exchange = false;
     }
     
$search_results = $ct['api']->ticker_markets_search($_POST['add_markets_search'], $specific_exchange);

// UX: SAVE results for users hitting the 'Go Back To Previous Step' link
$ct['cache']->other_cached_data('save', $recent_search_id, $ct['base_dir'] . '/cache/secured/other_data', $search_results);

}
else {
// UX: LOAD results for users hitting the 'Go Back To Previous Step' link
$search_results = $ct['cache']->other_cached_data('load', $recent_search_id, $ct['base_dir'] . '/cache/secured/other_data');
}


$included_results = array();

$skipped_results = array();

$not_required = array(
                      'mcap_slug',
                      'already_added',
                     );


     foreach ( $search_results as $exchange_key => $exchange_data ) {
          
          
          foreach ( $exchange_data as $market_data ) {
               
          $missing_required = false; // RESET
               
               
               foreach ( $market_data as $meta_key => $meta_val ) {
               
                    if ( !in_array($meta_key, $not_required) && !is_array($meta_val) && trim($meta_val) == '' ) {
                         
                    $missing_required = $meta_key;

                    $ct['gen']->log( 'market_error', 'No data found for required value "' . $missing_required . '", during asset market search: "' . $_POST['add_markets_search'] . '" (for exchange API '.$exchange_key.')');

                    }
               
               }

               
               if (
               !$missing_required && !$market_data['already_added']
               || !$missing_required && is_bool($market_data['already_added']) !== true
               ) {
                    
               $included_results[ $market_data['asset'] ][ $market_data['pairing'] ][] = array(
                                                                                                          'exchange' => $exchange_key,
                                                                                                          'name' => $market_data['name'],
                                                                                                          'already_added' => $market_data['already_added'],
                                                                                                          'mcap_slug' => $market_data['mcap_slug'],
                                                                                                          'id' => $market_data['id'],
                                                                                                          'data' => $market_data['data'],
                                                                                                         );
                                                                                                         
               }
               elseif ( $missing_required || $market_data['already_added'] ) {
                    
               $skipped_results[] = array(
                                                                                                          'missing_required' => $missing_required,
                                                                                                          'exchange' => $ct['gen']->key_to_name($exchange_key),
                                                                                                          'name' => $market_data['name'],
                                                                                                          'pairing' => $market_data['pairing'],
                                                                                                          'id' => $market_data['id'],
                                                                                                         );
                                                                 
               }
          
          
          }
     
     
     }
     

ksort($included_results); // Sort results by key name


// Sort results subarrays
foreach ( $included_results as $pairing_key => $unused ) {
            
            
    if ( is_array($included_results[$pairing_key]) ) {
    ksort($included_results[$pairing_key]); // Sort by key name
    }
            
            
    foreach ( $included_results[$pairing_key] as $exchange_key => $unused ) {
                 
         if ( is_array($included_results[$pairing_key][$exchange_key]) ) {
         ksort($included_results[$pairing_key][$exchange_key]);
         }
            
    }
            
        
}


// Sort skipped markets by exchange
$ct['usort_alpha'] = 'exchange';
$usort_feeds_results = usort($skipped_results, array($ct['gen'], 'usort_alpha') );


// WE ONLY LOAD STEP 3 IF WE HAVE RESULTS (OTHERWISE WE RELOAD STEP 2)
if ( sizeof($included_results) > 0 || sizeof($skipped_results) > 0 ) {
     
$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

     if ( $_POST['add_markets_search'] ) {
     $search_desc = $_POST['add_markets_search'];
     }
     elseif ( $_POST['saved_search'] ) {
     $search_desc = $_POST['saved_search'];
     }

?>

<h3 class='bitcoin input_margins'>STEP #3: Select Asset Markets You Prefer</h3>

<p style='font-weight: bold;' class='bitcoin bitcoin_dotted input_margins'>

NOTES:<br /><br />

ANY EXCHANGE MARKETS **THAT ALREADY EXIST IN THIS APP** ARE NEVER DISPLAYED IN SEARCH RESULTS HERE.<br /><br />

THIS ASSET SEARCH FEATURE **WILL NEVER FULLY SUPPORT** TICKERS WITH SYMBOLS IN THEM (EG: $WEN IS IGNORED OR CONVERTED TO WEN, WHILE WEN IS ACCEPTED), FOR CONSISTENT / CLEAR FORMATTING OF ALL ASSET TICKERS. THAT SAID, YOU STILL SHOULD ALWAYS **DOUBLE CHECK** THE MARKET DETAILS (BY CLICKING ON THE EXCHANGE NAME), TO MAKE SURE YOU ARE NOT **ACCIDENTALLY ADDING A COPY-CAT COIN** (WITH A SIMILAR TICKER COMPARED TO THE REAL COIN YOU WANT TO ADD).

</p>


<fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Asset Markets Found For Search: "<?=htmlspecialchars($search_desc, ENT_QUOTES)?>"</strong> </legend>

  <?php
  if ( sizeof($skipped_results) > 0 ) {
  ?>

     <a style='font-weight: bold;' class='red clear_both result_margins' href='javascript: show_more("results_skipped");' title='Click to show / hide additional details.'>Skipped Results (already exist in app, OR missing required data)</a>
     
     <div id='results_skipped' style='display: none;' class='red align_left clear_both result_margins'>
     
     <?php
     foreach ( $skipped_results as $skipped_market ) {
     ?>
     
               <p>
               
               <?php
               if ( $skipped_market['missing_required'] ) {
               ?>
               <i><u><b>(Missing Required: <?=$skipped_market['missing_required']?>)</b></u></i><br />
               <?php
               }
               ?>
               Exchange: <?=$skipped_market['exchange']?><br />
               Pairing: <?=$skipped_market['pairing']?><br />
               Name: <?=$skipped_market['name']?><br />
               ID: <?=$skipped_market['id']?>
               
               </p>
               
     <?php
     }
     ?>
     
     </div><br clear='all' />
     
  <?php
  }

  if ( sizeof($included_results) > 0 ) {
  ?>

<p style='font-weight: bold;' class='bitcoin result_margins'>Click on each asset below, to SELECT available exchange markets...</p>

<?php
     
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_id_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_id_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */


     foreach ( $included_results as $asset_key => $asset_data ) {
     ?>
     
     <a style='font-weight: bold;' class='blue clear_both result_margins' href='javascript: show_more("results_<?=md5($asset_key)?>");' title='Click to show / hide additional details.'><?=strtoupper($asset_key)?></a>
     
     <div id='results_<?=md5($asset_key)?>' style='display: none;' class='align_left clear_both result_margins'>
     
          <?php
          foreach ( $asset_data as $pair_key => $pair_data ) {
          ?>
          <a style='font-weight: bold;' class='green clear_both result_margins' href='javascript: show_more("results_<?=md5($asset_key . $pair_key)?>");' title='Click to show / hide additional details.'><?=strtoupper($pair_key)?></a>
          
          <div id='results_<?=md5($asset_key . $pair_key)?>' style='display: none;' class='align_left clear_both result_margins'>
          
               <?php
               foreach ( $pair_data as $market_key => $market_data ) {
               ?>
               
               <div style='margin-left: 1em;'>
               
                    <input type='hidden' dataset-id='<?=md5($asset_key . $pair_key . $market_data['exchange'] . $market_data['id'])?>' name='assets[<?=strtoupper($asset_key)?>][name]' value='<?=$market_data['name']?>' />
                    
                    <input type='hidden' dataset-id='<?=md5($asset_key . $pair_key . $market_data['exchange'] . $market_data['id'])?>' name='assets[<?=strtoupper($asset_key)?>][mcap_slug]' value='<?=$market_data['mcap_slug']?>' />
                    
                    <input type='checkbox' dataset-id='<?=md5($asset_key . $pair_key . $market_data['exchange'] . $market_data['id'])?>' name='assets[<?=strtoupper($asset_key)?>][pair][<?=strtolower($pair_key)?>][<?=strtolower($market_data['exchange'])?>]' value='<?=$market_data['id']?>' <?=( isset($_POST['assets'][strtoupper($asset_key)]['pair'][strtolower($pair_key)][strtolower($market_data['exchange'])]) && $_POST['assets'][strtoupper($asset_key)]['pair'][strtolower($pair_key)][strtolower($market_data['exchange'])] == $market_data['id'] ? 'checked' : '' )?> /> 
                    
                    <a class='<?=( is_bool($market_data['already_added']) !== true ? 'red' : 'bitcoin' )?> clear_both' href='javascript: show_more("results_<?=md5($asset_key . $pair_key . $market_data['exchange'] . $market_data['id'])?>");' title='Click to show / hide additional details.'><?=$ct['gen']->key_to_name($market_data['exchange'])?></a>
                    
                    <div id='results_<?=md5($asset_key . $pair_key . $market_data['exchange'] . $market_data['id'])?>' style='display: none;' class='align_left clear_both'>
                    
                    <p>
                    
                    <span class='light_sea_green'>Name:</span> <?=$market_data['name']?><br />
                    <?php
                    if ( is_bool($market_data['already_added']) !== true ) {
                    ?>
                    <span class='red'>Exchange Already Added:</span> Yes (if selected, <i class='red'>would replace market ID: <?=$market_data['already_added']?></i>)<br />
                    <?php
                    }
                    if ( isset($market_data['mcap_slug']) ) {
                    ?>
                    <span class='light_sea_green'>Marketcap Slug:</span> <?=$market_data['mcap_slug']?><br />
                    <?php
                    }
                    ?>
                    <span class='light_sea_green'>ID:</span> <?=$market_data['id']?><br />
                    <span class='light_sea_green'>Last Trade:</span> <?=$market_data['data']['last_trade']?><br />
                    <span class='light_sea_green'>24 Hour ASSET Volume:</span> <?=$market_data['data']['24hr_asset_vol']?><br />
                    <span class='light_sea_green'>24 Hour PAIR Volume:</span> <?=$market_data['data']['24hr_pair_vol']?>
                    
                    </p>
                    
                    </div><br clear='all' />
               
               </div>
               
               <?php
               }
               ?>
          
          </div><br clear='all' />
          
          <?php
          }
          ?>
     
     </div><br clear='all' /><br clear='all' />
     
     <?php
     }

?>

          <input type='hidden' id='add_markets_review' name='add_markets_review' value='1' />
     	
     	<button class='force_button_style result_margins' onclick='
     	
     	var post_data = {
     	                  "saved_search": "<?=htmlspecialchars($search_desc, ENT_QUOTES)?>",
     	                   };
     	
     	var add_markets_review = checkbox_subarrays_to_ajax("assets");
     	
     	var merged_data = merge_objects(post_data, add_markets_review);
     	
     	ct_ajax_load("type=add_markets&step=4", "#update_markets_ajax", "market search results", merged_data, true); // Secured
     	
     	'> Review Changes </button>
     	
     	<br clear='all' />
     	
  <?php
  }
  ?>

</fieldset>
	
<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {

same_name_checkboxes_to_radio();
     
});

     
</script>  	



<?php


     // DEBUGGING...
     if ( $ct['conf']['power']['debug_mode'] == 'wizard_steps_io' ) {
   
     //$ct['gen']->array_debugging($ct['registered_pairs']);
     
     $ct['gen']->array_debugging($search_results, true);
     
     //$ct['gen']->array_debugging($included_results, true);

     }


}
// IF no results, reload / reset to STEP #2
else {
$no_results = true;
$_GET['step'] = 2;
require($ct['base_dir'] . '/app-lib/php/inline/ajax/wizard-steps/markets/markets-add/add-markets-step-2.php');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
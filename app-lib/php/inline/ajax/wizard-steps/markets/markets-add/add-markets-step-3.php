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


     foreach ( $search_results as $exchange_key => $exchange_data ) {
          
          foreach ( $exchange_data as $market_data ) {
               
               if ( !$market_data['already_added'] ) {
                    
               $included_results[ $market_data['asset'] ][ $market_data['pairing'] ][$exchange_key] = array(
                                                                                                          'name' => $market_data['name'],
                                                                                                          'mcap_slug' => $market_data['mcap_slug'],
                                                                                                          'id' => $market_data['id'],
                                                                                                          'data' => $market_data['data'],
                                                                                                         );
                                                                                                         
               }
               elseif ( $market_data['already_added'] ) {
                    
               $skipped_results[] = array(
                                                                                                          'exchange' => $ct['gen']->key_to_name($exchange_key),
                                                                                                          'pairing' => $market_data['pairing'],
                                                                                                          'name' => $market_data['name'],
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

     <a style='font-weight: bold;' class='red clear_both input_margins' href='javascript: show_more("results_skipped");' title='Click to show / hide additional details.'>Skipped Results (already exist in app!)</a>
     
     <div id='results_skipped' style='display: none;' class='red align_left clear_both input_margins'>
     
     <?php
     foreach ( $skipped_results as $skipped_market ) {
     ?>
     
               <p>
               
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

<p style='font-weight: bold;' class='blue input_margins'>Click on each asset below, to SELECT available exchange markets...</p>

<?php
     
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_id_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_id_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */


     foreach ( $included_results as $asset_key => $asset_data ) {
     ?>
     
     <a style='font-weight: bold;' class='blue clear_both input_margins' href='javascript: show_more("results_asset_<?=$ct['gen']->safe_name($asset_key)?>");' title='Click to show / hide additional details.'><?=strtoupper($asset_key)?></a>
     
     <div id='results_asset_<?=$ct['gen']->safe_name($asset_key)?>' style='display: none;' class='align_left clear_both input_margins'>
     
          <?php
          foreach ( $asset_data as $pair_key => $pair_data ) {
          ?>
          <a style='font-weight: bold;' class='green clear_both input_margins' href='javascript: show_more("results_asset_<?=$ct['gen']->safe_name($asset_key)?>_pairing_<?=$ct['gen']->safe_name($pair_key)?>");' title='Click to show / hide additional details.'><?=strtoupper($pair_key)?></a>
          
          <div id='results_asset_<?=$ct['gen']->safe_name($asset_key)?>_pairing_<?=$ct['gen']->safe_name($pair_key)?>' style='display: none;' class='align_left clear_both input_margins'>
          
               <?php
               foreach ( $pair_data as $market_key => $market_data ) {
               ?>
               
               <input type='hidden' dataset-id='<?=md5($asset_key)?>' name='assets[<?=strtoupper($asset_key)?>][name]' value='<?=$market_data['name']?>' />
               
               <input type='hidden' dataset-id='<?=md5($asset_key)?>' name='assets[<?=strtoupper($asset_key)?>][mcap_slug]' value='<?=$market_data['mcap_slug']?>' />
               
               <input type='checkbox' dataset-id='<?=md5($asset_key)?>' name='assets[<?=strtoupper($asset_key)?>][pair][<?=strtolower($pair_key)?>][<?=strtolower($market_key)?>]' value='<?=$market_data['id']?>' <?=( isset($_POST['assets'][strtoupper($asset_key)]['pair'][strtolower($pair_key)][strtolower($market_key)]) ? 'checked' : '' )?> /> 
               
               <a class='bitcoin clear_both input_margins' href='javascript: show_more("results_asset_<?=$ct['gen']->safe_name($asset_key)?>_pairing_<?=$ct['gen']->safe_name($pair_key)?>_market_<?=$ct['gen']->safe_name($market_key)?>");' title='Click to show / hide additional details.'><?=$ct['gen']->key_to_name($market_key)?></a>
               
               <div id='results_asset_<?=$ct['gen']->safe_name($asset_key)?>_pairing_<?=$ct['gen']->safe_name($pair_key)?>_market_<?=$ct['gen']->safe_name($market_key)?>' style='display: none;' class='align_left clear_both input_margins'>
               
               <p>
               
               Name: <?=$market_data['name']?><br />
               <?php
               if ( isset($market_data['mcap_slug']) ) {
               ?>
               Marketcap Slug: <?=$market_data['mcap_slug']?><br />
               <?php
               }
               ?>
               ID: <?=$market_data['id']?><br />
               Last Trade: <?=$market_data['data']['last_trade']?><br />
               24 Hour ASSET Volume: <?=$market_data['data']['24hr_asset_vol']?><br />
               24 Hour PAIR Volume: <?=$market_data['data']['24hr_pair_vol']?>
               
               </p>
               
               </div><br clear='all' />
               
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
     	
     	<button class='force_button_style input_margins' onclick='
     	
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

<?php

// DEBUGGING...
   
//$ct['gen']->array_debugging($ct['registered_pairs']);

$ct['gen']->array_debugging($search_results, true);

//$ct['gen']->array_debugging($included_results, true);


}
// IF no results, reload / reset to STEP #2
else {
$no_results = true;
$_GET['step'] = 2;
require($ct['base_dir'] . '/app-lib/php/inline/ajax/wizard-steps/markets/markets-add/add-markets-step-2.php');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
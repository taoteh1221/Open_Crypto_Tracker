<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$included_results = array();

$all_results_count = array();

$duplicate_check = array();

$skipped_results = array();

$not_required = array(
                      'mcap_slug',
                      'orig_pairing',
                      'flagged_market',
                     );
                     
                     
// Assures we are getting cached data for this EXACT user login SESSION only
$recent_search_cache_file = 'add_asset_search_' . md5(session_id() . $ct['remote_ip']) . '.dat';   
   
   
if ( isset($_POST['add_markets_search']) ) {
     
     // ALL / specific exchange
     if ( $_POST['add_markets_search_exchange'] != 'all_exchanges' ) {
     $specific_exchange = $_POST['add_markets_search_exchange'];
     }
     else {
     $specific_exchange = false;
     }
     
$search_results = $ct['api']->ticker_markets_search($_POST['add_markets_search'], $specific_exchange);


// Calculate search runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$search_runtime = round( ($time - $start_runtime) , 3);


// UX: SAVE results for users hitting the 'Go Back To Previous Step' link
$ct['cache']->other_cached_data('save', $ct['base_dir'] . '/cache/secured/other_data/' . $recent_search_cache_file, $search_results);

}
elseif ( file_exists($ct['base_dir'] . '/cache/secured/other_data/' . $recent_search_cache_file) ) {
// UX: LOAD results for users hitting the 'Go Back To Previous Step' link
$search_results = $ct['cache']->other_cached_data('load', $ct['base_dir'] . '/cache/secured/other_data/' . $recent_search_cache_file);
}


// For UX, set to zero for every known exchange
foreach ( $ct['api']->exchange_apis as $exchange_key => $exchange_data ) {
$all_results_count[$exchange_key] = 0;
}


foreach ( $search_results as $exchange_key => $exchange_data ) {
     
          
       foreach ( $exchange_data as $market_data ) {
          
       $all_results_count[$exchange_key] = $all_results_count[$exchange_key] + 1;
               
       $missing_required = false; // RESET
               
               
               foreach ( $market_data as $meta_key => $meta_val ) {
               
                    if ( !in_array($meta_key, $not_required) && !is_array($meta_val) && trim($meta_val) == '' ) {
                         
                    $missing_required .= ( $missing_required ? ',' : '' ) . $meta_key;

                    $ct['gen']->log( 'market_error', 'No data determined for required value(s) "' . $missing_required . '", during asset market search: "' . $_POST['add_markets_search'] . '" (for exchange API '.$exchange_key.')');

                    }
               
               }

               
               // We allow REPLACEMENT market ids (that are DIFFERENT from the CURRENT one)
               if (
               !$missing_required && !$market_data['flagged_market']
               || !$missing_required && is_bool($market_data['flagged_market']) !== true && stristr($market_data['flagged_market'], 'replacement_for_')
               ) {
                    
                    
                    if ( isset($duplicate_check[ $market_data['asset'] ][ $market_data['pairing'] ][ $market_data['id'] ][$exchange_key]) ) {
                    continue; // Skip duplicate
                    }
                    // Make sure we don't include duplicates
                    else {
                    $duplicate_check[ $market_data['asset'] ][ $market_data['pairing'] ][ $market_data['id'] ][$exchange_key] = true;
                    }
               
                    
               $included_results[ $market_data['asset'] ][ $market_data['pairing'] ][] = array(
                                                                                               'flagged_market' => ( $market_data['flagged_market'] ? $market_data['flagged_market'] : false ),
                                                                                               'exchange' => $exchange_key,
                                                                                               'name' => $market_data['name'],
                                                                                               'asset' => $market_data['asset'],
                                                                                               'pairing' => $market_data['pairing'],
                                                                                               'orig_pairing' => $market_data['orig_pairing'],
                                                                                               'mcap_slug' => $market_data['mcap_slug'],
                                                                                               'id' => $market_data['id'],
                                                                                               'contract_address' => $market_data['contract_address'],
                                                                                               'data' => $market_data['data'],
                                                                                               );

               }
               elseif ( $missing_required ) {
                    
               $skipped_results[] = array(
                                          'flagged_market' => 'missing_required_' . $missing_required,
                                          'exchange' => $ct['gen']->key_to_name($exchange_key),
                                          'name' => $market_data['name'],
                                          'asset' => $market_data['asset'],
                                          'pairing' => $market_data['pairing'],
                                          'orig_pairing' => $market_data['orig_pairing'],
                                          'id' => $market_data['id'],
                                          );
                                                                 
               }
               elseif ( $market_data['flagged_market'] ) {
                    
               $skipped_results[] = array(
                                          'flagged_market' => $market_data['flagged_market'],
                                          'exchange' => $ct['gen']->key_to_name($exchange_key),
                                          'name' => $market_data['name'],
                                          'asset' => $market_data['asset'],
                                          'pairing' => $market_data['pairing'],
                                          'orig_pairing' => $market_data['orig_pairing'],
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


// ALL results COUNT
$results_count = 0;
foreach ( $all_results_count as $exchange_count ) {
$results_count = $results_count + $exchange_count;
}


// Sort skipped markets by exchange
$ct['sort_by_nested'] = 'root=>exchange';
usort($skipped_results, array($ct['var'], 'usort_asc') );
$ct['sort_by_nested'] = false; // RESET


// WE ONLY LOAD STEP 3 IF WE HAVE RESULTS (OTHERWISE WE RELOAD STEP 2)
if ( sizeof($included_results) > 0 || sizeof($skipped_results) > 0 ) {
     
$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

     if ( $_POST['add_markets_search'] ) {
     $search_desc = $_POST['add_markets_search'];
     }
     elseif ( $_POST['saved_search'] ) {
     $search_desc = $_POST['saved_search'];
     }

     
     if ( isset($_POST['add_markets_search']) ) {
     echo '<p class="align_center ' . ( $search_runtime > 90 ? 'red' : 'green' ) . '"> ' . $ct['var']->num_pretty($results_count, 0) . ' total results <span class="' . ( sizeof($skipped_results) > 0 ? 'red' : '' ) . '">(' . $ct['var']->num_pretty( sizeof($skipped_results) , 0) . ' skipped)</span> in ' . $search_runtime . ' seconds.</p>';
     }


// Specific exchange
if ( $specific_exchange ) {
?>


<h3 class='green input_margins'>STEP #3: Select Asset Markets Found For (EXACT <?=$ct['gen']->key_to_name($specific_exchange)?> Market ID Match) Search "<?=htmlspecialchars($search_desc, ENT_QUOTES)?>"</h3>


<?php
}
// 'all exchanges' search results notices
else {
?>


<h3 class='green input_margins'>STEP #3: Select Asset Markets Found For (<?=( $_POST['strict_search'] == 'yes' ? 'Exact' : 'Similar' )?> Match) Search "<?=htmlspecialchars($search_desc, ENT_QUOTES)?>"</h3>


<p style='font-weight: bold;' class='bitcoin bitcoin_dotted input_margins'>
    
    <?php
    // Search recommedation, IF pairing NOT included
    if ( !stristr($search_desc, '/') ) {
    $ui_example = strtolower( trim($search_desc) );
    ?>
    
    FOR QUICKER / MORE SPECIFIC SEARCH RESULTS, TRY INCLUDING A PAIRING IN YOUR SEARCH PARAMETERS (example: <?=htmlspecialchars($ui_example, ENT_QUOTES)?>/<?=( stristr($search_desc, 'btc') ? 'usd' : 'btc' )?>).<br /><br />
    
    <?php
    }
    ?>
    
<span class='red'>ALWAYS click the exchange name in each result, to VERIFY THE MARKET PRICE / DETAILS ARE CORRECT, for the asset you are looking for! WATCH OUT FOR COPYCAT COINS (pretending to be the coin you are looking for)!</span><br /><br />

<!-- jupiter notices -->
IF YOU DON'T SEE THE JUPITER AGGREGATOR ASSET / PAIRING YOU WANT, WAIT 1 HOUR FOR THE APP'S ASSET CACHE TO REFRESH (OR 3 HOURS FOR THE PAIRING CACHE).<br /><br />

WE LIMIT JUPITER AGGREGATOR SEARCH RESULTS TO <?=($ct['conf']['ext_apis']['jupiter_ag_search_results_max_per_cpu_core'] * $ct['system_info']['cpu_threads'])?> <span class='yellow'>(ADJUSTABLE IN: <span class='light_sea_green'>"APIS => EXTERNAL APIS => JUPITER AGGREGATOR SEARCH RESULTS MAXIMUM PER CPU CORE"</span>, <?=$all_results_count['jupiter_ag']?> results below [including any marked as skipped] are from Jupiter Aggregator)</span>, TO HELP AVOID 504 "GATEWAY TIMEOUT" ERRORS / LONG SEARCH TIMES ON SLOWER DEVICES. <span class='red'>IF YOU SEE A 504 "GATEWAY TIMEOUT" ERROR, ADJUST THIS LIMIT LOWER.</span>

</p>

<?php
}
?>


     <?php
     if ( sizeof($skipped_results) > 0 ) {
     ?>

     
     <br />
     
     <div class='red_dotted'>
     
          <a style='font-weight: bold;' class='red clear_both result_margins' href='javascript: show_more("results_skipped");' title='Click to show / hide additional details.'>Skipped Results (already exist in app, OR missing required data)</a>
     
          <div id='results_skipped' style='display: none;' class='red align_left clear_both result_margins'>
          
               <?php
               foreach ( $skipped_results as $skipped_market ) {
               ?>
          
                    <p>
                    
                    <?php
                    // Missing required value
                    if ( isset($skipped_market['flagged_market']) && stristr($skipped_market['flagged_market'], 'missing_required_') ) {
                    ?>
                    <i><u><b>Missing Required: <?=preg_replace("/missing_required_/i", "", $skipped_market['flagged_market'])?> (Consider ADDING PAIRINGS in: "Asset Tracking => Currency Support => Additional Pairings Search" [that are in the market id])</b></u></i><br />
                    <?php
                    }
                    // Already added
                    elseif ( isset($skipped_market['flagged_market']) && stristr($skipped_market['flagged_market'], 'already_added_') ) {
                    ?>
                    <i><u><b>Already Added: (exists already in current config)</b></u></i><br />
                    <?php
                    }
                    // Pairing not supported
                    elseif ( isset($skipped_market['flagged_market']) && stristr($skipped_market['flagged_market'], 'pairing_not_supported_') ) {
                    ?>
                    <i><u><b>Pairing Not Supported: (ADD BTC / <?=strtoupper( preg_replace("/pairing_not_supported_/i", "", $skipped_market['flagged_market']) )?> MARKET, TO ENABLE SUPPORT [DETERMINED BY MARKET ID])</b></u></i><br />
                    <?php
                    }
                    // Other flag
                    elseif ( $skipped_market['flagged_market'] ) {
                    ?>
                    <i><u><b>Flagged Market: <?=$skipped_market['flagged_market']?></b></u></i><br />
                    <?php
                    }
                    ?>
                    Exchange: <?=$skipped_market['exchange']?><br />
                    Name: <?=$skipped_market['name']?><br />
                    Asset: <?=$skipped_market['asset']?><br />
                    Pairing: <?=$skipped_market['pairing']?><br />
                    
                    <?php
                    // IF we changed the ticker of the base pairing for app UX (wbtc,weth,etc)
                    if ( $skipped_market['orig_pairing'] != '' ) {
                    ?>
                    ORIGINAL Pairing Ticker: <?=$skipped_market['orig_pairing']?><br />
                    <?php
                    }
                    ?>
                    
                    Market ID: <?=$skipped_market['id']?>
                    
                    </p>
                    
               <?php
               }
               ?>
          
          </div><br clear='all' />
     
     </div><br clear='all' />
     
     <?php
     }


     if ( sizeof($included_results) > 0 ) {
     ?>

     	<button class='force_button_style result_margins bitcoin' onclick='
     	
     	var post_data = {
     	                  "saved_search": "<?=htmlspecialchars($search_desc, ENT_QUOTES)?>",
     	                   };
     	
     	var add_markets_review = checkbox_subarrays_to_ajax("assets");
     	
     	var merged_data = merge_objects(post_data, add_markets_review);
     	
     	ct_ajax_load("type=add_markets&step=4", "#update_markets_ajax", "review / confirm selected markets", merged_data, true); // Secured
     	
     	'> Review / Confirm Selected Markets </button>
     	
     	
     	
<p style='font-weight: bold;' class='bitcoin result_margins'>Click on each asset / pairing below, to SELECT available exchange markets...</p>

     	
     	
<?php
     
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_tickers_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_tickers_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
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
                    
                    $unique_market_id = md5($asset_key . $pair_key . $market_data['exchange'] . $market_data['id']);
                    
                    ?>
                    
                    <div style='margin-left: 1em;'>
                    
                         <input type='hidden' dataset-id='<?=$unique_market_id?>' name='assets[<?=strtoupper($asset_key)?>][name]' value='<?=$market_data['name']?>' />
                         
                         <input type='hidden' dataset-id='<?=$unique_market_id?>' name='assets[<?=strtoupper($asset_key)?>][mcap_slug]' value='<?=$market_data['mcap_slug']?>' />
                         
                         <input type='checkbox' dataset-id='<?=$unique_market_id?>' name='assets[<?=strtoupper($asset_key)?>][pair][<?=strtolower($pair_key)?>][<?=strtolower($market_data['exchange'])?>]' value='<?=$market_data['id']?>' <?=( isset($_POST['assets'][strtoupper($asset_key)]['pair'][strtolower($pair_key)][strtolower($market_data['exchange'])]) && $_POST['assets'][strtoupper($asset_key)]['pair'][strtolower($pair_key)][strtolower($market_data['exchange'])] == $market_data['id'] ? 'checked' : '' )?> /> 
                         
                         <a class='<?=( is_bool($market_data['flagged_market']) !== true && stristr($market_data['flagged_market'], 'replacement_for_') ? 'red' : 'bitcoin' )?> clear_both' href='javascript: show_more("results_<?=$unique_market_id?>");' title='Click to show / hide additional details.'><?=$ct['gen']->key_to_name($market_data['exchange'])?></a>
                         
                         <div id='results_<?=$unique_market_id?>' style='display: none;' class='align_left clear_both'>
                         
                         <p>
                         
                         <span class='light_sea_green'>Name:</span> <?=$market_data['name']?><br />
                         <span class='light_sea_green'>Asset:</span> <?=$market_data['asset']?><br />
                         <span class='light_sea_green'>Pairing:</span> <?=$market_data['pairing']?><br />
                         
                         <?php
                         // IF we changed the ticker of the base pairing for app UX (wbtc,weth,etc)
                         if ( $market_data['orig_pairing'] != '' ) {
                         ?>
                         <span class='light_sea_green'>ORIGINAL Pairing Ticker:</span> <?=$market_data['orig_pairing']?><br />
                         <?php
                         }
                         ?>
                         
                         <?php
                         if ( is_bool($market_data['flagged_market']) !== true && stristr($market_data['flagged_market'], 'replacement_for_') ) {
                         ?>
                         <span class='red'>Exchange Already Added:</span> (if selected, <i class='red'>would replace market ID: <?=preg_replace("/replacement_for_/i", "", $market_data['flagged_market'])?></i>)<br />
                         <?php
                         }
                         
                         if ( isset($market_data['mcap_slug']) ) {
                         ?>
                         <span class='light_sea_green'>Marketcap Slug:</span> <?=$market_data['mcap_slug']?><br />
                         <?php
                         }
                         ?>
                         
                         <span class='light_sea_green'>Market ID:</span> <?=$market_data['id']?><br />
                         
                         <?php
                         if ( isset($market_data['contract_address']) ) {
                         ?>
                         <span class='light_sea_green'>Contract Address:</span> <?=$market_data['contract_address']?><br />
                         <?php
                         }
                         ?>
                         
                         <span class='light_sea_green'>Last Trade:</span> <?=$market_data['data']['last_trade']?><br />
                         
                         <?php
                         if ( isset($market_data['data']['24hr_asset_vol']) ) {
                         ?>
                         <span class='light_sea_green'>24 Hour ASSET Volume:</span> <?=(  in_array($market_data['exchange'], $ct['dev']['no_trade_volume_api_data']) && $market_data['data']['24hr_asset_vol'] < 1 ? 'N/A' : $market_data['data']['24hr_asset_vol'] )?><br />
                         <?php
                         }
                         
                         if ( isset($market_data['data']['24hr_pair_vol']) ) {
                         ?>
                         <span class='light_sea_green'>24 Hour PAIR Volume:</span> <?=(  in_array($market_data['exchange'], $ct['dev']['no_trade_volume_api_data']) && $market_data['data']['24hr_pair_vol'] < 1 ? 'N/A' : $market_data['data']['24hr_pair_vol'] )?><br />
                         <?php
                         }
                         
                         if ( isset($market_data['data']['24hr_usd_vol']) ) {
                         ?>
                         <span class='light_sea_green'>24 Hour USD Volume:</span> <?=(  in_array($market_data['exchange'], $ct['dev']['no_trade_volume_api_data']) && $market_data['data']['24hr_usd_vol'] < 1 ? 'N/A' : $market_data['data']['24hr_usd_vol'] )?><br />
                         <?php
                         }
                         ?>
                         
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

     	<button class='force_button_style result_margins bitcoin' onclick='
     	
     	var post_data = {
     	                  "saved_search": "<?=htmlspecialchars($search_desc, ENT_QUOTES)?>",
     	                   };
     	
     	var add_markets_review = checkbox_subarrays_to_ajax("assets");
     	
     	var merged_data = merge_objects(post_data, add_markets_review);
     	
     	ct_ajax_load("type=add_markets&step=4", "#update_markets_ajax", "review / confirm selected markets", merged_data, true); // Secured
     	
     	'> Review / Confirm Selected Markets </button>
     	
     	
     	<br clear='all' />
     	
     <?php
     }
     ?>

	
<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {

same_name_checkboxes_to_radio();
     
});

     
</script>  	


<?php


     // DEBUGGING...
     if ( $ct['conf']['power']['debug_mode'] == 'setup_wizards_io' ) {
   
     //$ct['gen']->array_debugging($ct['registered_pairs']);
     
     $ct['gen']->array_debugging($search_results, true);
     
     //$ct['gen']->array_debugging($included_results, true);

     }


}
// END OF: if ( sizeof($included_results) > 0 || sizeof($skipped_results) > 0 ) {
// IF no results, reload / reset to STEP #2
else {
$no_results = true;
$_GET['step'] = 2;
require($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-add/add-markets-step-2.php');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
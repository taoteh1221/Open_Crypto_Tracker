<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

?>

<h3 class='bitcoin input_margins'>STEP #3: Select Asset Markets You Prefer</h3>

<?php

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


if ( is_array($search_results) && sizeof($search_results) > 0 ) {
     
ksort($search_results); // Sort by key name
     
   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_id_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_id_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
//var_dump($search_results); // DEBUGGING

?>

<!--
     <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%;'>
     
     'registered_pairs' array:
     
     <?=print_r($ct['registered_pairs'])?>
     
     </code></pre>
     
     <br /><br /><br />
-->

     <?php
     foreach ( $search_results as $exchange_key => $exchange_data ) {
     ?>
     
     <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%;'>
     
     <?=$exchange_key?> (<?=sizeof($exchange_data)?> market results):
     
     <?=print_r($exchange_data)?>
     
     </code></pre>
     
     <br /><br /><br />
     
     
     
     <?php
     }

}
// IF no results, reload / reset to STEP #2
else {
$no_results = true;
$_GET['step'] = 2;
require($ct['base_dir'] . '/app-lib/php/inline/ajax/wizard-steps/markets/markets-add/add-markets-step-2.php');
}

?>

          <input type='hidden' id='add_markets_review' name='add_markets_review' value='1' />
     	
     	<button class='force_button_style input_margins' onclick='
     	
     	var add_markets_review = {
     	                          "add_markets_review": $("#add_markets_review").val(),
     	                          };
     	
     	ct_ajax_load("type=add_markets&step=4", "#update_markets_ajax", "market search results", add_markets_review, true); // Secured
     	
     	'> Review Changes </button>

<?php


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


     
// ALL / specific exchange
if ( $_POST['add_markets_search_exchange'] != 'all_exchanges' ) {
$specific_exchange = $_POST['add_markets_search_exchange'];
}
else {
$specific_exchange = false;
}
     
     
$search_results = $ct['api']->ticker_markets_search($_POST['add_markets_search'], $specific_exchange);

ksort($search_results); // Sort by key name

if ( is_array($search_results) && sizeof($search_results) > 0 ) {
$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");
?>

<h3 class='bitcoin input_margins'>STEP #3: Select Asset Markets You Prefer</h3>
          
<?php
     
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



// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
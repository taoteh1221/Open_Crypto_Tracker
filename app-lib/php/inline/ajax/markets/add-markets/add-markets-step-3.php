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


if ( is_array($search_results) && sizeof($search_results) > 0 ) {

require($ct['base_dir'] . '/app-lib/php/inline/ajax/markets/back-button.php');

?>

<h3 class='bitcoin input_margins'>STEP #3: Select Your Preferred Asset Markets</h3>
          
<?php
     
var_dump($search_results); // DEBUGGING

}
else {
$no_results = true;
$_GET['step'] = 2;
require($ct['base_dir'] . '/app-lib/php/inline/ajax/markets/add-markets/add-markets-step-2.php');
}



// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

?>

<h3 class='red input_margins'>STEP #3: Remove <?=strtoupper($_POST['remove_markets_mode'])?></h3>   

<?php

if ( $_POST['remove_markets_mode'] == 'asset' ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-remove/remove-selected-assets.php');
}
elseif ( $_POST['remove_markets_mode'] == 'markets' ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-remove/remove-selected-markets.php');
}
?>











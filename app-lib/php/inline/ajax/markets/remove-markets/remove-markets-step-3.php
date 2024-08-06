<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


require($ct['base_dir'] . '/app-lib/php/inline/ajax/markets/back-button.php');

?>

<h3 class='bitcoin input_margins'>STEP #3: Remove <?=strtoupper($_POST['remove_markets_mode'])?></h3>    	
    	
<?php

if ( $_POST['remove_markets_mode'] == 'asset' ) {

}
elseif ( $_POST['remove_markets_mode'] == 'markets' ) {

}

?>
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( $_GET['type'] == 'add_markets' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/wizard-steps/markets/markets-add/add-markets-init.php');
}
elseif ( $_GET['type'] == 'remove_markets' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/wizard-steps/markets/markets-remove/remove-markets-init.php');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// AFTER LOAD CONFIG
//////////////////////////////////////////////////////////////////


// API configs need to be loaded IMMEADIATELY after loading the cached config
require_once('app-lib/php/inline/config/batched-api-config.php');
require_once('app-lib/php/inline/config/throttled-api-config.php');

// Developer-only configs
$dev_only_configs_mode = 'config-init'; // Flag to only run 'config-init' section
require('developer-config.php');


//////////////////////////////////////////////////////////////////
// END AFTER LOAD CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
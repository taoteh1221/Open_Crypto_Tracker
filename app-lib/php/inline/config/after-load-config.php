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


// Get / check system info for debugging / stats (MUST run IMMEADIATELY AFTER loading the cached config)
require_once($ct['base_dir'] . '/app-lib/php/inline/system/system-info.php');


// STRICT curl user agent (for strict API servers list in proxy mode, etc, etc)
// MUST BE SET IMMEDIATELY AFTER system-info.php (AS EARLY AS POSSIBLE FOR ADMIN INPUT VALIDATION)
$ct['strict_curl_user_agent'] = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $ct['system_info']['software'] . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';


// Developer-only configs
$dev_only_configs_mode = 'after-load-config'; // Flag to only run 'after-load-config' section
require('developer-config.php');


//////////////////////////////////////////////////////////////////
// END AFTER LOAD CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
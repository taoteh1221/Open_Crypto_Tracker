<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// CACHED CONFIG
//////////////////////////////////////////////////////////////////


// Global set in load_cached_config(), if a restore config is backed up
$restore_conf_path = null;

// Load cached config (user-edited via admin interface), unless it's corrupt json 
// (if corrupt, it will reset from hard-coded default config in config.php, OR a restore config if available)
$ct_gen->load_cached_config();


//////////////////////////////////////////////////////////////////
// END CACHED CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// APP CONFIG
//////////////////////////////////////////////////////////////////


// Cached config
$refresh_cached_ct_conf = 0;
////
$upgraded_ct_conf = array();
////
$check_default_ct_conf = trim( file_get_contents('cache/vars/default_ct_conf_md5.dat') );
////
// SET default ct_conf array BEFORE load_cached_config(), and BEFORE dynamic app config management
// (ALSO MUST BE #AFTER# PLUGINS CONFIG)
// #MUST# BE COMPLETELY REMOVED FROM ALL LOGIC, #WHEN WE SWITCH ON USING THE CACHED USER EDITED CONFIG#
$default_ct_conf = $ct_conf; 
////
// Load cached config (user-edited via admin interface), unless it's corrupt json 
// (if corrupt, it will reset from hard-coded default config in config.php)
// SEE upgrade_cache_ct_conf() AND subarray_ct_conf_upgrade(), #WHEN WE SWITCH ON USING THE CACHED USER EDITED CONFIG# 
$ct_gen->load_cached_config();


//////////////////////////////////////////////////////////////////
// END APP CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
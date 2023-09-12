<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


require_once("app-lib/php/classes/core/general.php");
$ct['gen'] = new ct_gen();

require_once("app-lib/php/classes/core/var.php");
$ct['var'] = new ct_var();

require_once("app-lib/php/classes/core/cache.php");
$ct['cache'] = new ct_cache();

require_once("app-lib/php/classes/core/api.php");
$ct['api'] = new ct_api();

require_once("app-lib/php/classes/core/asset.php");
$ct['asset'] = new ct_asset();

require_once("app-lib/php/classes/core/plugin.php");
$ct['plug'] = new ct_plug();
 
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>
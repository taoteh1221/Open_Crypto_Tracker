<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 
 
require("templates/interface/php/wrap/header.php");


// Admin template to use    
if ( !$ct_gen->passed_enhanced_security() ) {
require("templates/interface/php/admin/admin-elements/iframe-security-mode.php");
}
elseif ( isset($_GET['section']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-category.php");
}
elseif ( isset($_GET['plugin']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-plugin.php");
}
    		

require("templates/interface/php/wrap/footer.php");

?>
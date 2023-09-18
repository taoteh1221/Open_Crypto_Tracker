<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// MUST run BEFORE printing out header (for UX on 2FA alerts)
$passed_medium_security_check = $ct['gen']->passed_medium_security_check();
 
 
require("templates/interface/php/wrap/header.php");


// Admin template to use    
if ( !$passed_medium_security_check ) {
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
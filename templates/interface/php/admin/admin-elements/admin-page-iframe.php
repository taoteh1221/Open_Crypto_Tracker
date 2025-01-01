<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// MUST run BEFORE printing out header (for UX on 2FA alerts)
$passed_medium_security_check = $ct['gen']->passed_medium_security_check();
 

// Even if a 2FA error originated on the FULL page while displayed,
// we ALWAYS only have 1 form on the MEDIUM SECURITY CHECK page
if ( !$passed_medium_security_check && $ct['check_2fa_error'] ) {
$ct['check_2fa_id'] = '2fa_code_0';
}

 
require("templates/interface/php/wrap/header.php");


// Admin template to use    
if ( !$passed_medium_security_check ) {
require("templates/interface/php/admin/admin-elements/iframe-security-mode.php");
}
elseif ( isset($_GET['section']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-section.php");
}
elseif ( isset($_GET['subsection']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-subsection.php");
}
elseif ( isset($_GET['plugin']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-plugin.php");
}
    		

require("templates/interface/php/wrap/footer.php");

?>
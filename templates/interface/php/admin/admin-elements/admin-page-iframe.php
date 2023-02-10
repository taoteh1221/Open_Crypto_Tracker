<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 
 
require("templates/interface/php/header.php");


// Admin template to use    
if ( $admin_area_sec_level == 'enhanced' && !$ct_gen->pass_sec_check($_POST['enhanced_security_nonce'], 'enhanced_security_mode') ) {
require("templates/interface/php/admin/admin-elements/iframe-security-mode.php");
}
elseif ( isset($_GET['section']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-category.php");
}
elseif ( isset($_GET['plugin']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-plugin.php");
}
    		

require("templates/interface/php/footer.php");

?>
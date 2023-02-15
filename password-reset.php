<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'ui';

$is_password_reset = 1;


// Load app config / etc
require("app-lib/php/init.php");


// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || !is_array($stored_admin_login) ) {
require($base_dir . '/templates/interface/php/admin/admin-login/register.php');
exit;
}
else {
require($base_dir . '/templates/interface/php/admin/admin-login/reset.php'); 
exit;
}

// NO LOGS / DEBUGGING / MESSAGE SENDING AT RUNTIME END HERE (WE ALWAYS EXIT BEFORE HERE)

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
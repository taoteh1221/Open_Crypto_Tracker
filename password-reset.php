<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;



// Runtime mode
$runtime_mode = 'ui';

$is_password_reset = 1;


// Load app config / etc
require("config.php");


// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || sizeof($stored_admin_login) != 2 ) {
require($base_dir . '/templates/interface/desktop/php/admin/admin-login/register.php');
exit;
}
else {
require($base_dir . '/templates/interface/desktop/php/admin/admin-login/reset.php'); 
exit;
}

// NO LOGS / DEBUGGING / MESSAGE SENDING AT RUNTIME END HERE (WE ALWAYS EXIT BEFORE HERE)

?>



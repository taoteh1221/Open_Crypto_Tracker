<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'webhook';


// Load app config / etc
require("config.php");


// Set a max execution time for interface, TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
ini_set('max_execution_time', $app_config['webhook_max_execution_time']);


// Webhook security check (key request var must match our stored webhook key, or we abort runtime)
if ( $_GET['key'] != $webhook_key ) {
echo "Incorrect security key."
exit;
}


?>



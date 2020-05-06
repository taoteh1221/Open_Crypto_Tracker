<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'logs';

// FOR SPEED, $runtime_mode 'logs' only gets app config vars, some init.php, then EXITS in the logs library
require("config.php");

// NO LOGS / DEBUGGING / MESSAGE SENDING AT RUNTIME END HERE (WE ALWAYS EXIT BEFORE HERE IN INIT.PHP, FOR DESIRED #VERY# RESPONSIVE RUNTIMES)

?>



<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

// Runtime mode
$runtime_mode = 'captcha';

// Change directory
chdir("../../../../");

// FOR SPEED, $ct['runtime_mode'] 'captcha' only gets app config vars, some init.php, then the captcha library
require("app-lib/php/init.php");

?>
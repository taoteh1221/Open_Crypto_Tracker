<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */

// Runtime mode
$runtime_mode = 'captcha';

// Change directory
chdir("../../../../");

// FOR SPEED, $runtime_mode 'captcha' only gets app config vars, some init.php, then the captcha library
require("config.php");

?>
<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

// Runtime mode
$runtime_mode = 'captcha';

// Change directory
chdir("../../../../");

// FOR SPEED, $runtime_mode 'captcha' only gets app config vars, some init.php, then the captcha library
require("config.php");

?>
<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

$app_version = '2.3.1';  // 2019/MARCH/8TH
 
date_default_timezone_set('UTC');

//apc_clear_cache(); apcu_clear_cache(); opcache_reset();  // DEBUGGING ONLY

session_start();

$_SESSION['proxy_checkup'] = array();

// Make sure we have a PHP version id
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

$alert_percent = explode("|", $_COOKIE['alert_percent']);

$curl_setup = curl_version();


?>
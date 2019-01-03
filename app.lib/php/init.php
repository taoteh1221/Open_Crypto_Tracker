<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


date_default_timezone_set('UTC');

// Make sure we have a PHP version id
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

$alert_percent = explode("|", $_COOKIE['alert_percent']);

$curl_setup = curl_version();

$user_agent = $_SERVER['SERVER_SOFTWARE'] . ' HTTP Server; PHP v' .phpversion(). ' and Curl v' .$curl_setup["version"]. '; DFD Cryptocoin Values v' . $version . ' API Endpoint Parser;';

?>
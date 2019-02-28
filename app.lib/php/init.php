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

$user_agents = array(
							'Mozilla/5.0 AppleWebKit (KHTML, like Gecko) Chrome Safari',
							'Mozilla/5.0 Gecko Firefox',
							'Mozilla/5.0 (compatible; API Parser;) AppleWebKit (KHTML, like Gecko) Chrome Safari',
							'Mozilla/5.0 (compatible; API Parser;) Gecko Firefox'
							);

if ( sizeof($proxy_list) > 0 ) {
$user_agent = random_user_agent();  // If proxies in use, preserve privacy
}
else {
$user_agent = 'Mozilla/5.0 (compatible; ' . $_SERVER['SERVER_SOFTWARE'] . ' HTTP Server; PHP v' .phpversion(). '; Curl v' .$curl_setup["version"]. '; DFD Cryptocoin Values v' . $app_version . ' API Endpoint Parser; +https://github.com/taoteh1221/DFD_Cryptocoin_Values) Gecko Firefox';
}

?>
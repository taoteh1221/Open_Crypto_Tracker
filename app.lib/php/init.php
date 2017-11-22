<?php


$alert_percent = explode("|", $_COOKIE['alert_percent']);

$curl_setup = curl_version();

$user_agent = $_SERVER['SERVER_SOFTWARE'] . ' HTTP Server; PHP v' .phpversion(). ' and Curl v' .$curl_setup["version"]. '; DFD Cryptocoin Values v' . $version . ' API Endpoint Parser;';

?>
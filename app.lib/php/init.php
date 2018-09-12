<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



$alert_percent = explode("|", $_COOKIE['alert_percent']);

$curl_setup = curl_version();

$user_agent = $_SERVER['SERVER_SOFTWARE'] . ' HTTP Server; PHP v' .phpversion(). ' and Curl v' .$curl_setup["version"]. '; DFD Cryptocoin Values v' . $version . ' API Endpoint Parser;';

$api_server = NULL; // Unused feature in get_data function, BUT let's set this officially as NULL for stricter server setups

?>
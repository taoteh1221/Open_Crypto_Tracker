<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


if ( sizeof($proxy_list) > 0 ) {
$user_agent = 'Mozilla/5.0 (compatible; API_Endpoint_Parser;) Gecko Firefox';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Mozilla/5.0 (compatible; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; Curl/' .$curl_setup["version"]. '; DFD_Cryptocoin_Values/' . $app_version . '; API_Endpoint_Parser; +https://github.com/taoteh1221/DFD_Cryptocoin_Values) Gecko Firefox';
}


if ( $smtp_login != '' && $smtp_server != '' ) {

require_once( dirname(__FILE__) . '/classes/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config file structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = smtp_vars();
global $smtp_vars; // Needed for class compatibility (along with second instance in the class config_smtp.php file)

// Initiation of the 3rd party SMTP class
$smtp = new SMTPMailer();
$smtp->addTo($to_email); // Add to email here one time...because class adds to an array each call, even if already added

}



// Only need below logic during UI runtime
if ( $runtime_mode == 'ui' ) {

$marketcap_site = ( $alert_percent[0] != '' ? $alert_percent[0] : $marketcap_site );

}


?>
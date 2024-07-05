<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$smtp_login_check = explode("||", $_POST['comms']['smtp_login']);

$smtp_server_check = explode(":", $_POST['comms']['smtp_server']);

$to_mobile_text_check = explode("||", $_POST['comms']['to_mobile_text']);
  
  
// Make sure SMTP emailing params are set properly
if ( isset($_POST['comms']['smtp_login']) && $_POST['comms']['smtp_login'] != '' && sizeof($smtp_login_check) < 2 ) {
$ct['update_config_error'] = 'SMTP Login formatting is NOT valid (format MUST be: username||password)';
}
elseif ( isset($_POST['comms']['smtp_server']) && $_POST['comms']['smtp_server'] != '' && sizeof($smtp_server_check) < 2 ) {
$ct['update_config_error'] = 'SMTP Server formatting is NOT valid (format MUST be: domain_or_ip:port_number)';
}
// Mobile text check
elseif ( isset($_POST['comms']['to_mobile_text']) && $_POST['comms']['to_mobile_text'] != '' && sizeof($to_mobile_text_check) < 2 ) {
$ct['update_config_error'] = 'To Mobile Text formatting is NOT valid (format MUST be: mobile_number||network_name)';
}
// Email FROM service check
elseif ( isset($_POST['comms']['from_email']) && $_POST['comms']['from_email'] != '' && $ct['gen']->valid_email($_POST['comms']['from_email']) != 'valid' ) {
$ct['update_config_error'] = 'FROM Email is NOT valid: ' . $_POST['comms']['from_email'] . ' (' . $ct['gen']->valid_email($_POST['comms']['from_email']) . ')';
}
// Email TO service check
elseif ( isset($_POST['comms']['to_email']) && $_POST['comms']['to_email'] != '' && $ct['gen']->valid_email($_POST['comms']['to_email']) != 'valid' ) {
$ct['update_config_error'] = 'TO Email is NOT valid: ' . $_POST['comms']['to_email'] . ' (' . $ct['gen']->valid_email($_POST['comms']['to_email']) . ')';
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
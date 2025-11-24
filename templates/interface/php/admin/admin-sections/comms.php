<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


<?php
if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {
?>

	<p class='blue blue_dotted'>
	
	More comms-related settings can be found in the "External APIs" section (Telegram / Twilio / Amazon Alexa / etc).
	
	</p>
	
	
	<div style='min-height: 1em;'></div>


<?php


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['allow_comms']['is_radio'] = array(
                                                          'off',
                                                          'on',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['upgrade_alert_channels']['is_select'] = array(
                                                               'off',
                                                               'ui',
                                                               'email',
                                                               'text',
                                                               'notifyme',
                                                               'telegram',
                                                             );


$ct['admin_render_settings']['upgrade_alert_channels']['is_select']['is_assoc'][] = array(
                                                                                   'key' => 'all',
                                                                                   'val' => 'all activated channels',
                                                                                  );


$ct['admin_render_settings']['upgrade_alert_channels']['is_notes'] = 'Checks the <a href="https://api.github.com/repos/taoteh1221/Open_Crypto_Tracker/releases/latest" target="_BLANK">Github.com API</a> for the latest release\'s version number.<br />(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['upgrade_alert_reminder']['is_range'] = true;

$ct['admin_render_settings']['upgrade_alert_reminder']['range_min'] = 1;

$ct['admin_render_settings']['upgrade_alert_reminder']['range_max'] = 30;

$ct['admin_render_settings']['upgrade_alert_reminder']['range_step'] = 1;

$ct['admin_render_settings']['upgrade_alert_reminder']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['upgrade_alert_reminder']['range_ui_suffix'] = ' Days';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['market_error_alert_channels']['is_select'] = array(
                                                               'off',
                                                               'email',
                                                               'text',
                                                               'notifyme',
                                                               'telegram',
                                                             );


$ct['admin_render_settings']['market_error_alert_channels']['is_select']['is_assoc'][] = array(
                                                                                   'key' => 'all',
                                                                                   'val' => 'all activated channels',
                                                                                  );


$ct['admin_render_settings']['market_error_alert_channels']['is_notes'] = '(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['market_error_threshold']['is_range'] = true;

$ct['admin_render_settings']['market_error_threshold']['range_min'] = 5;

$ct['admin_render_settings']['market_error_threshold']['range_max'] = 15;

$ct['admin_render_settings']['market_error_threshold']['range_step'] = 1;

$ct['admin_render_settings']['market_error_threshold']['range_ui_prefix'] = 'After ';

$ct['admin_render_settings']['market_error_threshold']['range_ui_suffix'] = ' Days';


$ct['admin_render_settings']['market_error_threshold']['is_notes'] = 'ALERTS for market data failures, AFTER X CONSECUTIVE DAYS of errors';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['smtp_login']['is_text'] = true;

$ct['admin_render_settings']['smtp_login']['is_password'] = true;

$ct['admin_render_settings']['smtp_login']['text_field_size'] = 40;

$ct['admin_render_settings']['smtp_login']['is_notes'] = 'This format MUST be used: username||password<br /><b class="red">USE A THROW-AWAY EMAIL ACCOUNT HERE, FOR SAFETY!</b>';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['smtp_server']['is_text'] = true;

$ct['admin_render_settings']['smtp_server']['is_password'] = true;

$ct['admin_render_settings']['smtp_server']['is_trim'] = true;

$ct['admin_render_settings']['smtp_server']['text_field_size'] = 40;

$ct['admin_render_settings']['smtp_server']['is_notes'] = 'This format MUST be used: domain_or_ip:port_number<br />Examples: example.com:25 (non-encrypted), example.com:465 (ssl-encrypted), example.com:587 (tls-encrypted)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['from_email']['is_text'] = true;

$ct['admin_render_settings']['from_email']['is_password'] = true;

$ct['admin_render_settings']['from_email']['is_trim'] = true;

$ct['admin_render_settings']['from_email']['text_field_size'] = 40;

$ct['admin_render_settings']['from_email']['is_notes'] = 'FROM Email address MUST be a REAL address on the app server, *UNLESS* you are behind a home internet gateway (with no MATCHING reverse DNS [for the email domain]. In those cases, it should match the Email address associated with an "SMTP Login" account [further above])';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['to_email']['is_text'] = true;

$ct['admin_render_settings']['to_email']['is_password'] = true;

$ct['admin_render_settings']['to_email']['is_trim'] = true;

$ct['admin_render_settings']['to_email']['text_field_size'] = 40;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['to_mobile_text']['is_text'] = true;

$ct['admin_render_settings']['to_mobile_text']['is_password'] = true;

$ct['admin_render_settings']['to_mobile_text']['is_trim'] = true;

$ct['admin_render_settings']['to_mobile_text']['text_field_size'] = 40;

$ct['admin_render_settings']['to_mobile_text']['is_notes'] = 'USE "skip_network_name" IN THE PROVIDER FIELD, IF YOU ARE USING A 3RD PARTY SMS-SENDING SERVICE (TWILIO / TEXTBELT / TEXTLOCAL / ETC)!<br />Examples:<br />12223334444||virgin_us &nbsp; 12223334444||skip_network_name<br />(available gateways can be found in the "Mobile Text Gateways" section, while 3rd party SMS-sending services can be found in the "External APIs" section)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['logs_email']['is_range'] = true;

$ct['admin_render_settings']['logs_email']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['logs_email']['range_min'] = 0;

$ct['admin_render_settings']['logs_email']['range_max'] = 30;

$ct['admin_render_settings']['logs_email']['range_step'] = 1;

$ct['admin_render_settings']['logs_email']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['logs_email']['range_ui_suffix'] = ' Days';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
// SHOULD BE COMMA-DELIMITED: 'iframe_reset_backup_restore,iframe_apis'
$ct['admin_render_settings']['is_refresh_admin'] = 'all';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('comms', 'comms', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
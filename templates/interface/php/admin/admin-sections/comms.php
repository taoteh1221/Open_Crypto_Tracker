<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>


<?php
if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor.
	
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


$admin_render_settings['allow_comms']['is_radio'] = array(
                                                          'off',
                                                          'on',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['upgrade_alert']['is_select'] = array(
                                                          'off',
                                                          'ui',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$admin_render_settings['upgrade_alert']['is_notes'] = 'See "External APIs" section for using any comms-related APIs.';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['upgrade_alert_reminder']['is_select']['is_assoc'] = array();

$loop = 1;
$loop_max = 90;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['upgrade_alert_reminder']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => 'Every ' . $loop . ' Days',
                                                                      );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['news_feed_email_frequency']['is_select']['is_assoc'] = array();

$loop = 0;
$loop_max = 30;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['news_feed_email_frequency']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => ( $loop == 0 ? 'Disabled' : 'Every ' . $loop . ' Days'),
                                                                      );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['news_feed_email_entries_show']['is_select'] = array();

$loop = 1;
$loop_max = 30;
while ( $loop <= $loop_max ) {
$admin_render_settings['news_feed_email_entries_show']['is_select'][] = $loop;
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_alert']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$admin_render_settings['price_alert']['is_notes'] = 'See "External APIs" section for using any comms-related APIs.';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_alert_threshold']['is_select']['is_assoc'] = array();

$loop = 0;
$loop_max = 100;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['price_alert_threshold']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => ( $loop == 0 ? 'Disabled' : $loop . '% Price Change'),
                                                                      );
                                                                      
$loop = $loop + 0.25;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_alert_frequency_maximum']['is_select']['is_assoc'] = array();

$loop = 0;
$loop_max = 72;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['price_alert_frequency_maximum']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => ( $loop == 0 ? 'Unlimited' : 'Every ' . $loop . ' Hours'),
                                                                      );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_alert_block_volume_error']['is_radio'] = array(
                                                          'off',
                                                          'on',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_alert_minimum_volume']['is_select']['is_assoc'] = array();

$loop = 0;
$loop_max = 500000;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['price_alert_minimum_volume']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => ( $loop == 0 ? 'Disabled' : $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . $ct['var']->num_pretty($loop, 0) . ' (' . strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']) . ')'),
                                                                      );
                                                                      
$loop = $loop + 1000;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['logs_email']['is_select']['is_assoc'] = array();

$loop = 0;
$loop_max = 30;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['logs_email']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => ( $loop == 0 ? 'Disabled' : 'Every ' . $loop . ' Days'),
                                                                      );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['proxy_alert']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$admin_render_settings['proxy_alert']['is_notes'] = 'See "External APIs" section for using any comms-related APIs.';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['proxy_alert_frequency_maximum']['is_select']['is_assoc'] = array();

$loop = 0;
$loop_max = 72;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['proxy_alert_frequency_maximum']['is_select']['is_assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => ( $loop == 0 ? 'Unlimited' : 'Every ' . $loop . ' Hours'),
                                                                      );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['proxy_alert_runtime']['is_radio'] = array(
                                                          'cron',
                                                          'ui',
                                                          'all',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['proxy_alert_checkup_ok']['is_radio'] = array(
                                                          'ignore',
                                                          'include',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['smtp_login']['is_password'] = true;

$admin_render_settings['smtp_login']['text_field_size'] = 40;

$admin_render_settings['smtp_login']['is_notes'] = 'This format MUST be used: username||password<br />USE A THROW-AWAY EMAIL ACCOUNT HERE FOR SAFETY!';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['smtp_server']['is_password'] = true;

$admin_render_settings['smtp_server']['is_trim'] = true;

$admin_render_settings['smtp_server']['text_field_size'] = 40;

$admin_render_settings['smtp_server']['is_notes'] = 'This format MUST be used: domain_or_ip:port_number';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['from_email']['is_password'] = true;

$admin_render_settings['from_email']['is_trim'] = true;

$admin_render_settings['from_email']['text_field_size'] = 40;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['to_email']['is_password'] = true;

$admin_render_settings['to_email']['is_trim'] = true;

$admin_render_settings['to_email']['text_field_size'] = 40;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['to_mobile_text']['is_password'] = true;

$admin_render_settings['to_mobile_text']['is_trim'] = true;

$admin_render_settings['to_mobile_text']['text_field_size'] = 40;

$admin_render_settings['to_mobile_text']['is_notes'] = 'Examples: 12223334444||virgin_us / 12223334444||skip_network_name<br />Available gateways can be found in the "Text Gateways" section.';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'all';

// $ct['admin']->settings_form_fields($conf_id, $interface_id)
$ct['admin']->settings_form_fields('comms', 'comms', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
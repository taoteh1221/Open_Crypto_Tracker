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


$admin_render_settings['upgrade_alert']['is_notes'] = 'Checks the <a href="https://api.github.com/repos/taoteh1221/Open_Crypto_Tracker/releases/latest" target="_BLANK">Github.com API</a> for the latest release\'s version number.<br />(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['upgrade_alert_reminder']['is_range'] = true;

$admin_render_settings['upgrade_alert_reminder']['range_min'] = 1;

$admin_render_settings['upgrade_alert_reminder']['range_max'] = 90;

$admin_render_settings['upgrade_alert_reminder']['range_step'] = 1;

$admin_render_settings['upgrade_alert_reminder']['range_ui_prefix'] = 'Every ';

$admin_render_settings['upgrade_alert_reminder']['range_ui_suffix'] = ' Days';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['news_feed_email_frequency']['is_range'] = true;

$admin_render_settings['news_feed_email_frequency']['range_ui_meta_data'] = 'zero_is_disabled';

$admin_render_settings['news_feed_email_frequency']['range_min'] = 0;

$admin_render_settings['news_feed_email_frequency']['range_max'] = 30;

$admin_render_settings['news_feed_email_frequency']['range_step'] = 1;

$admin_render_settings['news_feed_email_frequency']['range_ui_prefix'] = 'Every ';

$admin_render_settings['news_feed_email_frequency']['range_ui_suffix'] = ' Days';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['news_feed_email_entries_show']['is_range'] = true;

$admin_render_settings['news_feed_email_entries_show']['range_min'] = 1;

$admin_render_settings['news_feed_email_entries_show']['range_max'] = 30;

$admin_render_settings['news_feed_email_entries_show']['range_step'] = 1;

$admin_render_settings['news_feed_email_entries_show']['range_ui_suffix'] = ' Entries Per-Feed';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_alert']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$admin_render_settings['price_alert']['is_notes'] = '(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['price_alert_threshold']['is_range'] = true;

$admin_render_settings['price_alert_threshold']['range_ui_meta_data'] = 'zero_is_disabled';

$admin_render_settings['price_alert_threshold']['range_min'] = 0;

$admin_render_settings['price_alert_threshold']['range_max'] = 100;

$admin_render_settings['price_alert_threshold']['range_step'] = 0.25;

$admin_render_settings['price_alert_threshold']['range_ui_suffix'] = '% Price Change';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['price_alert_frequency_maximum']['is_range'] = true;

$admin_render_settings['price_alert_frequency_maximum']['range_ui_meta_data'] = 'zero_is_unlimited';

$admin_render_settings['price_alert_frequency_maximum']['range_min'] = 0;

$admin_render_settings['price_alert_frequency_maximum']['range_max'] = 72;

$admin_render_settings['price_alert_frequency_maximum']['range_step'] = 1;

$admin_render_settings['price_alert_frequency_maximum']['range_ui_prefix'] = 'Every ';

$admin_render_settings['price_alert_frequency_maximum']['range_ui_suffix'] = ' Hours';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_alert_block_volume_error']['is_radio'] = array(
                                                                              'off',
                                                                              'on',
                                                                             );


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['price_alert_minimum_volume']['is_range'] = true;

$admin_render_settings['price_alert_minimum_volume']['range_ui_meta_data'] = 'zero_is_disabled';

$admin_render_settings['price_alert_minimum_volume']['range_min'] = 0;

$admin_render_settings['price_alert_minimum_volume']['range_max'] = 500000;

$admin_render_settings['price_alert_minimum_volume']['range_step'] = 1000;

$admin_render_settings['price_alert_minimum_volume']['range_ui_prefix'] = $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ];

$admin_render_settings['price_alert_minimum_volume']['range_ui_suffix'] = ' (' . strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']) . ')';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['logs_email']['is_range'] = true;

$admin_render_settings['logs_email']['range_ui_meta_data'] = 'zero_is_disabled';

$admin_render_settings['logs_email']['range_min'] = 0;

$admin_render_settings['logs_email']['range_max'] = 30;

$admin_render_settings['logs_email']['range_step'] = 1;

$admin_render_settings['logs_email']['range_ui_prefix'] = 'Every ';

$admin_render_settings['logs_email']['range_ui_suffix'] = ' Days';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['proxy_alert']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$admin_render_settings['proxy_alert']['is_notes'] = '(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['proxy_alert_frequency_maximum']['is_range'] = true;

$admin_render_settings['proxy_alert_frequency_maximum']['range_ui_meta_data'] = 'zero_is_unlimited';

$admin_render_settings['proxy_alert_frequency_maximum']['range_min'] = 0;

$admin_render_settings['proxy_alert_frequency_maximum']['range_max'] = 72;

$admin_render_settings['proxy_alert_frequency_maximum']['range_step'] = 1;

$admin_render_settings['proxy_alert_frequency_maximum']['range_ui_prefix'] = 'Every ';

$admin_render_settings['proxy_alert_frequency_maximum']['range_ui_suffix'] = ' Hours';


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

$admin_render_settings['to_mobile_text']['is_notes'] = 'Examples:<br />12223334444||virgin_us / 12223334444||skip_network_name<br />(available gateways can be found in the "Text Gateways" section)';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'all';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('comms', 'comms', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
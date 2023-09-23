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

	<p class='bitcoin bitcoin_dotted'>
	
	PRO TIP: An easy / reliable way to add your keys here is opposite-clicking over the key AFTER selecting all it's characters, and choosing "Copy". Then opposite-click inside the input field on this page, and choose "Paste". Alternatively, you can also do this with the keyboard combinations: Ctrl + C (copy) / Ctrl + V (paste)
	
	</p>
	
	
	<div style='min-height: 1em;'></div>
	

<?php


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['notifyme_access_code']['is_textarea'] = true;

$admin_render_settings['notifyme_access_code']['is_notes'] = '<a href="https://www.thomptronics.com/about/notify-me" target="_BLANK">Get a FREE NotifyMe (Amazon Alexa) API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['telegram_your_username']['is_password'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['telegram_bot_username']['is_password'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['telegram_bot_name']['is_password'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['telegram_bot_token']['is_password'] = true;

$admin_render_settings['telegram_bot_token']['text_field_size'] = 50;

$admin_render_settings['telegram_bot_token']['is_notes'] = '<a href="https://core.telegram.org/bots/features#creating-a-new-bot" target="_BLANK">Get a FREE Telegram Bot API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['twilio_number']['is_password'] = true;

$admin_render_settings['twilio_number']['is_notes'] = 'Format: "12223334444" (no plus symbol)';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['twilio_sid']['is_password'] = true;

$admin_render_settings['twilio_sid']['text_field_size'] = 40;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['twilio_token']['is_password'] = true;

$admin_render_settings['twilio_token']['text_field_size'] = 40;

$admin_render_settings['twilio_token']['is_notes'] = '<a href="https://twilio.com/" target="_BLANK">Get a PAID FOR Twilio API Key</a><br />ONLY ONE TEXTING SERVICE API MAY BE ENABLED AT A TIME (or NONE will work)<br />YOU MUST SET THE "To Mobile Text" PROVIDER (in the "Communications" section) TO: skip_network_name';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['textbelt_api_key']['is_password'] = true;

$admin_render_settings['textbelt_api_key']['text_field_size'] = 40;

$admin_render_settings['textbelt_api_key']['is_notes'] = '<a href="https://textbelt.com/" target="_BLANK">Get a PAID FOR TextBelt API Key</a><br />ONLY ONE TEXTING SERVICE API MAY BE ENABLED AT A TIME (or NONE will work)<br />YOU MUST SET THE "To Mobile Text" PROVIDER (in the "Communications" section) TO: skip_network_name';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['textlocal_sender']['is_password'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['textlocal_api_key']['is_password'] = true;

$admin_render_settings['textlocal_api_key']['text_field_size'] = 40;

$admin_render_settings['textlocal_api_key']['is_notes'] = '<a href="https://textlocal.com/integrations/api/" target="_BLANK">Get a PAID FOR TextLocal API Key</a><br />ONLY ONE TEXTING SERVICE API MAY BE ENABLED AT A TIME (or NONE will work)<br />YOU MUST SET THE "To Mobile Text" PROVIDER (in the "Communications" section) TO: skip_network_name';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['google_fonts_api_key']['is_password'] = true;

$admin_render_settings['google_fonts_api_key']['text_field_size'] = 40;

$admin_render_settings['google_fonts_api_key']['is_notes'] = '<a href="https://support.google.com/googleapi/answer/6158862?hl=en&ref_topic=7013279" target="_BLANK">Get a FREE Google Fonts API Key</a> (Don\'t forget to ENABLE IT SEPERATELY, *AFTER* CREATION)';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['coinmarketcap_api_key']['is_password'] = true;

$admin_render_settings['coinmarketcap_api_key']['text_field_size'] = 40;

$admin_render_settings['coinmarketcap_api_key']['is_notes'] = '<a href="https://coinmarketcap.com/api" target="_BLANK">Get a FREE CoinMarketCap API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['etherscan_api_key']['is_password'] = true;

$admin_render_settings['etherscan_api_key']['text_field_size'] = 40;

$admin_render_settings['etherscan_api_key']['is_notes'] = '<a href="https://etherscan.io/apis/" target="_BLANK">Get a FREE EtherScan API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['alphavantage_api_key']['is_password'] = true;

$admin_render_settings['alphavantage_api_key']['text_field_size'] = 40;

$admin_render_settings['alphavantage_api_key']['is_notes'] = '<a href="https://www.alphavantage.co/support/#api-key" target="_BLANK">Get a FREE AlphaVantage API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['alphavantage_per_minute_limit']['is_select'] = array(
                                                                         '5',
                                                                         '30',
                                                                         '75',
                                                                         '150',
                                                                         '300',
                                                                         '600',
                                                                         '1200',
                                                                        );

$admin_render_settings['alphavantage_per_minute_limit']['is_notes'] = 'LEAVE SET TO "5" IF YOU USE THE *FREE* PLAN, *OR YOU WILL ENCOUNTER ISSUES*.<br /><a href="https://www.alphavantage.co/premium/" target="_BLANK">See AlphaVantage\'s Premium Plans</a>, to increase your limits.';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'all';

// $ct['admin']->settings_form_fields($conf_id, $interface_id)
$ct['admin']->settings_form_fields('ext_apis', 'ext_apis', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
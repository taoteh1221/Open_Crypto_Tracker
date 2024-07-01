<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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
	
	PRO TIP: An easy / reliable way to add your keys here is opposite-clicking over the key AFTER selecting all it's characters, and choosing "Copy". Then opposite-click inside the input field on this page, and choose "Paste". Alternatively, you can also do this with the keyboard combinations: Ctrl + C (copy) / Ctrl + V (paste)
	
	</p>
	
	
	<div style='min-height: 1em;'></div>
	

<?php


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['remote_api_timeout']['is_range'] = true;

$ct['admin_render_settings']['remote_api_timeout']['range_min'] = 30;

$ct['admin_render_settings']['remote_api_timeout']['range_max'] = 120;

$ct['admin_render_settings']['remote_api_timeout']['range_step'] = 10;

$ct['admin_render_settings']['remote_api_timeout']['range_ui_suffix'] = ' Seconds';

$ct['admin_render_settings']['remote_api_timeout']['is_notes'] = 'MAXIMUM wait time for the FULL response from each external API (before aborting the connection)<br />Set HIGHER if you get alert notices / error logs OFTEN about this behind slow internet connections. DON\'T SET TOO HIGH THOUGH, or it could cause the APP TO LOAD VERY SLOWLY.';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['notifyme_access_code']['is_textarea'] = true;

$ct['admin_render_settings']['notifyme_access_code']['is_password'] = true;

$ct['admin_render_settings']['notifyme_access_code']['is_notes'] = '<a href="https://www.thomptronics.com/about/notify-me" target="_BLANK">Get a FREE NotifyMe (Amazon Alexa) API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['telegram_your_username']['is_text'] = true;

$ct['admin_render_settings']['telegram_your_username']['is_password'] = true;

$ct['admin_render_settings']['telegram_your_username']['is_notes'] = '(WITHOUT the "@" symbol)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['telegram_bot_username']['is_text'] = true;

$ct['admin_render_settings']['telegram_bot_username']['is_password'] = true;

$ct['admin_render_settings']['telegram_bot_username']['is_notes'] = '(WITHOUT the "@" symbol)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['telegram_bot_name']['is_text'] = true;

$ct['admin_render_settings']['telegram_bot_name']['is_password'] = true;

$ct['admin_render_settings']['telegram_bot_name']['is_notes'] = '(any name, spaces allowed)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['telegram_bot_token']['is_text'] = true;

$ct['admin_render_settings']['telegram_bot_token']['is_password'] = true;

$ct['admin_render_settings']['telegram_bot_token']['text_field_size'] = 50;

$ct['admin_render_settings']['telegram_bot_token']['is_notes'] = '<a href="https://core.telegram.org/bots/features#creating-a-new-bot" target="_BLANK">Get a FREE Telegram Bot API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['twilio_number']['is_text'] = true;

$ct['admin_render_settings']['twilio_number']['is_password'] = true;

$ct['admin_render_settings']['twilio_number']['is_notes'] = 'Format: "12223334444" (no plus symbol)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['twilio_sid']['is_text'] = true;

$ct['admin_render_settings']['twilio_sid']['is_password'] = true;

$ct['admin_render_settings']['twilio_sid']['text_field_size'] = 40;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['twilio_token']['is_text'] = true;

$ct['admin_render_settings']['twilio_token']['is_password'] = true;

$ct['admin_render_settings']['twilio_token']['text_field_size'] = 40;

$ct['admin_render_settings']['twilio_token']['is_notes'] = '<a href="https://twilio.com/" target="_BLANK">Get a PAID FOR Twilio API Key</a><br />ONLY ONE TEXTING SERVICE API MAY BE ENABLED AT A TIME (or NONE will work)<br />YOU MUST SET THE "To Mobile Text" PROVIDER (in the "Communications" section) TO: skip_network_name';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['textbelt_api_key']['is_text'] = true;

$ct['admin_render_settings']['textbelt_api_key']['is_password'] = true;

$ct['admin_render_settings']['textbelt_api_key']['text_field_size'] = 40;

$ct['admin_render_settings']['textbelt_api_key']['is_notes'] = '<a href="https://textbelt.com/" target="_BLANK">Get a PAID FOR TextBelt API Key</a><br />ONLY ONE TEXTING SERVICE API MAY BE ENABLED AT A TIME (or NONE will work)<br />YOU MUST SET THE "To Mobile Text" PROVIDER (in the "Communications" section) TO: skip_network_name';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['textlocal_sender']['is_text'] = true;

$ct['admin_render_settings']['textlocal_sender']['is_password'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['textlocal_api_key']['is_text'] = true;

$ct['admin_render_settings']['textlocal_api_key']['is_password'] = true;

$ct['admin_render_settings']['textlocal_api_key']['text_field_size'] = 40;

$ct['admin_render_settings']['textlocal_api_key']['is_notes'] = '<a href="https://textlocal.com/integrations/api/" target="_BLANK">Get a PAID FOR TextLocal API Key</a><br />ONLY ONE TEXTING SERVICE API MAY BE ENABLED AT A TIME (or NONE will work)<br />YOU MUST SET THE "To Mobile Text" PROVIDER (in the "Communications" section) TO: skip_network_name';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['google_fonts_api_key']['is_text'] = true;

$ct['admin_render_settings']['google_fonts_api_key']['is_password'] = true;

$ct['admin_render_settings']['google_fonts_api_key']['text_field_size'] = 40;

$ct['admin_render_settings']['google_fonts_api_key']['is_notes'] = '<a href="https://support.google.com/googleapi/answer/6158862?hl=en&ref_topic=7013279" target="_BLANK">Get a FREE Google Fonts API Key</a><br />(Don\'t forget to <a href="https://console.cloud.google.com/apis/credentials" target="_BLANK">REGISTER / ENABLE IT SEPERATELY</a>, *AFTER* CREATION)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['google_fonts_cache_time']['is_range'] = true;

$ct['admin_render_settings']['google_fonts_cache_time']['range_min'] = 3;

$ct['admin_render_settings']['google_fonts_cache_time']['range_max'] = 24;

$ct['admin_render_settings']['google_fonts_cache_time']['range_step'] = 3;

$ct['admin_render_settings']['google_fonts_cache_time']['range_ui_prefix'] = 'Refresh Font List Every ';

$ct['admin_render_settings']['google_fonts_cache_time']['range_ui_suffix'] = ' Hours';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['solana_rpc_server']['is_text'] = true;

$ct['admin_render_settings']['solana_rpc_server']['is_trim'] = true;

$ct['admin_render_settings']['solana_rpc_server']['text_field_size'] = 50;

$ct['admin_render_settings']['solana_rpc_server']['is_notes'] = 'What <a href="https://solana.com/docs/core/clusters" target="_BLANK">Solana RPC Server</a> to query, for on-chain data.<br />The default one is provided by the Solana Foundation FOR FREE:<br />https://api.mainnet-beta.solana.com';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['coingecko_api_batched_maximum']['is_range'] = true;

$ct['admin_render_settings']['coingecko_api_batched_maximum']['range_min'] = 25;

$ct['admin_render_settings']['coingecko_api_batched_maximum']['range_max'] = 100;

$ct['admin_render_settings']['coingecko_api_batched_maximum']['range_step'] = 25;

$ct['admin_render_settings']['coingecko_api_batched_maximum']['range_ui_prefix'] = 'Batch-Request ';

$ct['admin_render_settings']['coingecko_api_batched_maximum']['range_ui_suffix'] = ' Data Sets MAXIMUM';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['coinmarketcap_api_key']['is_text'] = true;

$ct['admin_render_settings']['coinmarketcap_api_key']['is_password'] = true;

$ct['admin_render_settings']['coinmarketcap_api_key']['text_field_size'] = 40;

$ct['admin_render_settings']['coinmarketcap_api_key']['is_notes'] = '<a href="https://coinmarketcap.com/api" target="_BLANK">Get a FREE CoinMarketCap API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['etherscan_api_key']['is_text'] = true;

$ct['admin_render_settings']['etherscan_api_key']['is_password'] = true;

$ct['admin_render_settings']['etherscan_api_key']['text_field_size'] = 40;

$ct['admin_render_settings']['etherscan_api_key']['is_notes'] = '<a href="https://etherscan.io/apis/" target="_BLANK">Get a FREE EtherScan API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['alphavantage_api_key']['is_text'] = true;

$ct['admin_render_settings']['alphavantage_api_key']['is_password'] = true;

$ct['admin_render_settings']['alphavantage_api_key']['text_field_size'] = 40;

$ct['admin_render_settings']['alphavantage_api_key']['is_notes'] = '<a href="https://www.alphavantage.co/support/#api-key" target="_BLANK">Get a FREE AlphaVantage API Key</a>';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['alphavantage_per_minute_limit']['is_range'] = true;

$ct['admin_render_settings']['alphavantage_per_minute_limit']['range_min'] = 5;

$ct['admin_render_settings']['alphavantage_per_minute_limit']['range_max'] = 1200;

$ct['admin_render_settings']['alphavantage_per_minute_limit']['range_ui_prefix'] = 'MAXIMUM of ';

$ct['admin_render_settings']['alphavantage_per_minute_limit']['range_ui_suffix'] = ' LIVE updates PER MINUTE';

$ct['admin_render_settings']['alphavantage_per_minute_limit']['range_ui_meta_data'] .= 'is_custom_steps;';
     
$ct['admin_render_settings']['alphavantage_per_minute_limit']['is_custom_steps'] = array(
                                                                                   '5',
                                                                                   '30',
                                                                                   '75',
                                                                                   '150',
                                                                                   '300',
                                                                                   '600',
                                                                                   '1200',
                                                                                  );

$ct['admin_render_settings']['alphavantage_per_minute_limit']['range_min'] = $ct['admin_render_settings']['alphavantage_per_minute_limit']['is_custom_steps'][0];

$ct['admin_render_settings']['alphavantage_per_minute_limit']['range_max'] = $ct['admin_render_settings']['alphavantage_per_minute_limit']['is_custom_steps'][ sizeof($ct['admin_render_settings']['alphavantage_per_minute_limit']['is_custom_steps']) - 1 ];

$ct['admin_render_settings']['alphavantage_per_minute_limit']['is_notes'] = 'LEAVE SET TO "5" IF YOU USE THE *FREE* PLAN, *OR YOU WILL ENCOUNTER ISSUES*.<br /><a href="https://www.alphavantage.co/premium/" target="_BLANK">See AlphaVantage\'s PREMUIM Plans</a>, to increase your limits.';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['alphavantage_free_plan_daily_limit']['is_range'] = true;

$ct['admin_render_settings']['alphavantage_free_plan_daily_limit']['range_min'] = 1;

$ct['admin_render_settings']['alphavantage_free_plan_daily_limit']['range_max'] = 25;

$ct['admin_render_settings']['alphavantage_free_plan_daily_limit']['range_step'] = 1;

$ct['admin_render_settings']['alphavantage_free_plan_daily_limit']['range_ui_prefix'] = 'MAXIMUM of ';

$ct['admin_render_settings']['alphavantage_free_plan_daily_limit']['range_ui_suffix'] = ' LIVE updates DAILY';

$ct['admin_render_settings']['alphavantage_free_plan_daily_limit']['is_notes'] = 'If you have price update issues with a FREE PLAN, LOWER THIS NUMBER (and check their website, as they have been known to lower the FREE PLAN daily limits on ocassion).<br /><a href="https://www.alphavantage.co/premium/" target="_BLANK">See AlphaVantage\'s PREMUIM Plans</a>, to increase your limits (there are NO DAILY LIMITS ON PREMIUM PLANS).';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$ct['admin_render_settings']['is_refresh_admin'] = 'all';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('ext_apis', 'ext_apis', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
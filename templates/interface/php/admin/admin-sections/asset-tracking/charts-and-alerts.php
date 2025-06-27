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


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['price_alert_channels']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$ct['admin_render_settings']['price_alert_channels']['is_notes'] = '(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['price_alert_threshold']['is_range'] = true;

$ct['admin_render_settings']['price_alert_threshold']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['price_alert_threshold']['range_min'] = 0;

$ct['admin_render_settings']['price_alert_threshold']['range_max'] = 100;

$ct['admin_render_settings']['price_alert_threshold']['range_step'] = 0.25;

$ct['admin_render_settings']['price_alert_threshold']['range_ui_suffix'] = '% Price Change';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['price_alert_frequency_maximum']['is_range'] = true;

$ct['admin_render_settings']['price_alert_frequency_maximum']['range_ui_meta_data'] .= 'zero_is_unlimited;';

$ct['admin_render_settings']['price_alert_frequency_maximum']['range_min'] = 0;

$ct['admin_render_settings']['price_alert_frequency_maximum']['range_max'] = 72;

$ct['admin_render_settings']['price_alert_frequency_maximum']['range_step'] = 1;

$ct['admin_render_settings']['price_alert_frequency_maximum']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['price_alert_frequency_maximum']['range_ui_suffix'] = ' Hours';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['price_alert_block_volume_error']['is_radio'] = array(
                                                                              'off',
                                                                              'on',
                                                                             );


$ct['admin_render_settings']['price_alert_block_volume_error']['is_notes'] = 'Skip alerts, if 24 hour trade volume DATA IS CORRUPT (only enforced IF exchange supports trade volume data)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['price_alert_minimum_volume']['is_range'] = true;

$ct['admin_render_settings']['price_alert_minimum_volume']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['price_alert_minimum_volume']['range_min'] = 0;

$ct['admin_render_settings']['price_alert_minimum_volume']['range_max'] = 500000;

$ct['admin_render_settings']['price_alert_minimum_volume']['range_step'] = 1000;

$ct['admin_render_settings']['price_alert_minimum_volume']['range_ui_prefix'] = $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ];

$ct['admin_render_settings']['price_alert_minimum_volume']['range_ui_suffix'] = ' (' . strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']) . ')';

$ct['admin_render_settings']['price_alert_minimum_volume']['is_notes'] = 'Skip alerts, if 24 hour trade volume is less than a MINIMUM amount (BUT is greater than zero)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['price_alert_fixed_reset']['is_range'] = true;

$ct['admin_render_settings']['price_alert_fixed_reset']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['price_alert_fixed_reset']['range_min'] = 0;

$ct['admin_render_settings']['price_alert_fixed_reset']['range_max'] = 30;

$ct['admin_render_settings']['price_alert_fixed_reset']['range_step'] = 1;

$ct['admin_render_settings']['price_alert_fixed_reset']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['price_alert_fixed_reset']['range_ui_suffix'] = ' Days';

$ct['admin_render_settings']['price_alert_fixed_reset']['is_notes'] = 'Fixed time interval RESET of CACHED comparison asset prices<br />(also send alerts that reset occurred, with summary of price changes since last reset)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['whale_alert_thresholds']['is_text'] = true;

$ct['admin_render_settings']['whale_alert_thresholds']['text_field_size'] = 35;

$ct['admin_render_settings']['whale_alert_thresholds']['is_notes'] = 'Detect LARGE trade volume swings, that HEAVILY affect trade values.<br />This format MUST be used: max_days_to_24hr_avg_over||min_price_percent_change_24hr_avg||min_vol_percent_increase_24hr_avg||min_vol_currency_increase_24hr_avg';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['enable_price_charts']['is_radio'] = array(
                                                                  'off',
                                                                  'on',
                                                                 );


$ct['admin_render_settings']['enable_price_charts']['is_notes'] = 'Enable / Disable the price charts in this app.';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['chart_crypto_volume_decimals']['is_range'] = true;

$ct['admin_render_settings']['chart_crypto_volume_decimals']['range_min'] = 4;

$ct['admin_render_settings']['chart_crypto_volume_decimals']['range_max'] = 10;

$ct['admin_render_settings']['chart_crypto_volume_decimals']['range_step'] = 1;


$ct['admin_render_settings']['chart_crypto_volume_decimals']['is_notes'] = 'Number of decimals to use IN *CRYPTO* PRICE CHARTS.';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['charts_border']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['charts_background']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['charts_line']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['charts_text']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['charts_link']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['charts_base_gradient']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['charts_tooltip_background']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['charts_tooltip_text']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['asset_performance_chart_defaults']['is_text'] = true;

$ct['admin_render_settings']['asset_performance_chart_defaults']['is_notes'] = 'This format MUST be used: chart_height||menu_size<br />(chart height min/max = 400/900 (increments of 100), menu size min/max = 7/16)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['asset_marketcap_chart_defaults']['is_text'] = true;

$ct['admin_render_settings']['asset_marketcap_chart_defaults']['is_notes'] = 'This format MUST be used: chart_height||menu_size<br />(chart height min/max = 400/900 (increments of 100), menu size min/max = 7/16)';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['tracked_markets']['is_repeatable']['add_button'] = 'Add Chart / Alert (at bottom)';

$ct['admin_render_settings']['tracked_markets']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['tracked_markets']['is_repeatable']['text_field_size'] = 35;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['charts_alerts']['tracked_markets']) > 0 ) {

     foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $key => $val ) {
     $ct['admin_render_settings']['tracked_markets']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['tracked_markets']['is_subarray'][$key]['text_field_size'] = 35;
     }

}
else {
$ct['admin_render_settings']['tracked_markets']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['tracked_markets']['is_subarray'][0]['text_field_size'] = 35;
}


// Get exchange keys for list below
require($ct['base_dir'] . '/app-lib/php/inline/debugging/exchange-and-pair-info.php');

$ct['admin_render_settings']['tracked_markets']['is_notes'] = 'Add price charts / price alerts here (supports MULTIPLE markets per-asset)<br />This format MUST be used:<br />
ticker||exchange||trade_pair||alert<br />
ticker-2||exchange2||trade_pair2||chart<br />
ticker-3||exchange3||trade_pair3||both<br />
ticker-4||exchange4||trade_pair4||none<br />THE FIRST VALUE (ticker[-number]) SETS CHART *FILENAMES*, KEEP THIS THE SAME TO *RESTORE OLD CHART DATA* PROPERLY!<br />' . "<p class='red'>The EXCHANGE value MUST be the <a style='font-weight: bold;' class='red' href='javascript: show_more(\"all_exchange_keys\");' title='Click to show exchange keys.'><b>\"key value\" assigned to that exchange</b></a>.</p><div id='all_exchange_keys' style='display: none;' class='align_left'><p class='red'>\"Under the hood\", this app identifies what exchange to use with \"exchange keys\". Here is the FULL list of all ACTIVE exchange keys (exchanges with configured markets, in the current portfolio assets config, that you can add price alerts / charts for):</p><p class='red'>" . strtolower($all_exchanges_list) . "</p></div>";


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
$ct['admin_render_settings']['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('charts_alerts', 'price_alerts_charts', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
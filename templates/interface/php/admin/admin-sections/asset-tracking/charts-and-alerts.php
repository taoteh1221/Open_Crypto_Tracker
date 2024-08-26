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

$ct['admin_render_settings']['price_alert_minimum_volume']['range_ui_prefix'] = $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ];

$ct['admin_render_settings']['price_alert_minimum_volume']['range_ui_suffix'] = ' (' . strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']) . ')';

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

     
$ct['admin_render_settings']['charts_backup_frequency']['is_range'] = true;

$ct['admin_render_settings']['charts_backup_frequency']['range_ui_meta_data'] .= 'zero_is_disabled;';

$ct['admin_render_settings']['charts_backup_frequency']['range_min'] = 0;

$ct['admin_render_settings']['charts_backup_frequency']['range_max'] = 7;

$ct['admin_render_settings']['charts_backup_frequency']['range_step'] = 1;

$ct['admin_render_settings']['charts_backup_frequency']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['charts_backup_frequency']['range_ui_suffix'] = ' Days';

$ct['admin_render_settings']['charts_backup_frequency']['is_notes'] = 'Backup chart data (AND send a download LINK to the ADMIN email)';


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

     
$ct['admin_render_settings']['system_stats_first_chart_maximum_scale']['is_range'] = true;

$ct['admin_render_settings']['system_stats_first_chart_maximum_scale']['range_min'] = 3;

$ct['admin_render_settings']['system_stats_first_chart_maximum_scale']['range_max'] = 9;

$ct['admin_render_settings']['system_stats_first_chart_maximum_scale']['range_step'] = 0.25;

$ct['admin_render_settings']['system_stats_first_chart_maximum_scale']['is_notes'] = 'Highest allowed sensor value to scale vertical axis for, in the FIRST system information chart';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['system_stats_second_chart_maximum_scale']['is_range'] = true;

$ct['admin_render_settings']['system_stats_second_chart_maximum_scale']['range_min'] = 300;

$ct['admin_render_settings']['system_stats_second_chart_maximum_scale']['range_max'] = 900;

$ct['admin_render_settings']['system_stats_second_chart_maximum_scale']['range_step'] = 25;

$ct['admin_render_settings']['system_stats_second_chart_maximum_scale']['is_notes'] = 'Highest allowed sensor value to scale vertical axis for, in the SECOND system information chart';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['light_chart_day_intervals']['is_text'] = true;

$ct['admin_render_settings']['light_chart_day_intervals']['text_field_size'] = 40;

$ct['admin_render_settings']['light_chart_day_intervals']['is_notes'] = '("Light") time period charts to use (loads quickly for any time period, 7 day / 30 day / 365 day / etc)<br />AUTO-CONVERTS to weeks / months / years (ONLY USE DAYS HERE)<br />This format MUST be used: number_days,number_days,number_days,number_days';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['light_chart_data_points_maximum']['is_range'] = true;

$ct['admin_render_settings']['light_chart_data_points_maximum']['range_min'] = 500;

$ct['admin_render_settings']['light_chart_data_points_maximum']['range_max'] = 1000;

$ct['admin_render_settings']['light_chart_data_points_maximum']['range_step'] = 25;

$ct['admin_render_settings']['light_chart_data_points_maximum']['is_notes'] = 'Maximum number of DATA POINTS allowed inside each light chart\'s DATA SET';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['light_chart_link_spacing']['is_range'] = true;

$ct['admin_render_settings']['light_chart_link_spacing']['range_min'] = 10;

$ct['admin_render_settings']['light_chart_link_spacing']['range_max'] = 100;

$ct['admin_render_settings']['light_chart_link_spacing']['range_step'] = 10;

$ct['admin_render_settings']['light_chart_link_spacing']['is_notes'] = 'Space between light chart LINKS (inside each chart)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['light_chart_link_font_offset']['is_range'] = true;

$ct['admin_render_settings']['light_chart_link_font_offset']['range_min'] = 4;

$ct['admin_render_settings']['light_chart_link_font_offset']['range_max'] = 8;

$ct['admin_render_settings']['light_chart_link_font_offset']['range_step'] = 1;

$ct['admin_render_settings']['light_chart_link_font_offset']['is_notes'] = 'GUESSED offset (width) for light chart LINK fonts';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['light_chart_first_build_hard_limit']['is_range'] = true;

$ct['admin_render_settings']['light_chart_first_build_hard_limit']['range_min'] = 10;

$ct['admin_render_settings']['light_chart_first_build_hard_limit']['range_max'] = 100;

$ct['admin_render_settings']['light_chart_first_build_hard_limit']['range_step'] = 10;

$ct['admin_render_settings']['light_chart_first_build_hard_limit']['is_notes'] = 'Maximum number of light chart NEW BUILDS allowed during background tasks (PER CPU CORE)<br />(LOW POWER DEVICES should NEVER exceed 20 new builds per CPU core)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['light_chart_all_rebuild_min_max']['is_text'] = true;

$ct['admin_render_settings']['light_chart_all_rebuild_min_max']['is_notes'] = 'How often to rebuild each chart\'s "ALL" light chart, IN HOURS (between 3-12).<br />This format MUST be used: number_min,number_max';


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


$ct['admin_render_settings']['tracked_markets']['is_notes'] = 'Add price charts / price alerts here (supports MULTIPLE markets per-asset)<br />This format MUST be used:<br />
ticker||exchange||trade_pair||alert<br />
ticker-2||exchange2||trade_pair2||chart<br />
ticker-3||exchange3||trade_pair3||both<br />
ticker-4||exchange4||trade_pair4||none<br />THE FIRST VALUE (ticker[-number]) SETS CHART *FILENAMES*, KEEP THIS THE SAME TO *RESTORE OLD CHART DATA* PROPERLY!';


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
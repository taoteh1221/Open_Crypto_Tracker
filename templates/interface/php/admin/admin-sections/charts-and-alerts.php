<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


<?php
if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['enable_price_charts']['is_radio'] = array(
                                                                  'off',
                                                                  'on',
                                                                 );


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['chart_crypto_volume_decimals']['is_range'] = true;

$admin_render_settings['chart_crypto_volume_decimals']['range_min'] = 4;

$admin_render_settings['chart_crypto_volume_decimals']['range_max'] = 10;

$admin_render_settings['chart_crypto_volume_decimals']['range_step'] = 1;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_alert_channels']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$admin_render_settings['price_alert_channels']['is_notes'] = '(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
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

     
$admin_render_settings['price_alert_fixed_reset']['is_range'] = true;

$admin_render_settings['price_alert_fixed_reset']['range_ui_meta_data'] = 'zero_is_disabled';

$admin_render_settings['price_alert_fixed_reset']['range_min'] = 0;

$admin_render_settings['price_alert_fixed_reset']['range_max'] = 30;

$admin_render_settings['price_alert_fixed_reset']['range_step'] = 1;

$admin_render_settings['price_alert_fixed_reset']['range_ui_prefix'] = 'Every ';

$admin_render_settings['price_alert_fixed_reset']['range_ui_suffix'] = ' Days';

$admin_render_settings['price_alert_fixed_reset']['is_notes'] = 'Fixed time interval RESET of CACHED comparison asset prices<br />(also send alerts that reset occurred, with summary of price changes since last reset)';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['whale_alert_thresholds']['text_field_size'] = 35;

$admin_render_settings['whale_alert_thresholds']['is_notes'] = 'This format MUST be used: max_days_to_24hr_avg_over||min_price_percent_change_24hr_avg||min_vol_percent_increase_24hr_avg||min_vol_currency_increase_24hr_avg';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_border']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_background']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_line']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_text']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_link']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_base_gradient']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_tooltip_background']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_tooltip_text']['is_color'] = true;


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$admin_render_settings['tracked_markets']['is_repeatable']['add_button'] = 'Add Chart / Alert (at bottom)';

$admin_render_settings['tracked_markets']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$admin_render_settings['tracked_markets']['is_repeatable']['text_field_size'] = 35;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['charts_alerts']['tracked_markets']) > 0 ) {

     foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $key => $val ) {
     $admin_render_settings['tracked_markets']['is_subarray'][$key]['is_text'] = true;
     $admin_render_settings['tracked_markets']['is_subarray'][$key]['text_field_size'] = 35;
     }

}
else {
$admin_render_settings['tracked_markets']['is_subarray'][0]['is_text'] = true;
$admin_render_settings['tracked_markets']['is_subarray'][0]['text_field_size'] = 35;
}


$admin_render_settings['tracked_markets']['is_notes'] = 'Add price charts / price alerts here (supports MULTIPLE markets per-asset)<br />This format MUST be used:<br />
ticker||exchange||trade_pair||alert<br />
ticker-2||exchange2||trade_pair2||chart<br />
ticker-3||exchange3||trade_pair3||both<br />
ticker-4||exchange4||trade_pair4||none<br />THE FIRST VALUE (ticker[-number]) SETS CHART *FILENAMES*, KEEP THIS THE SAME TO *RESTORE OLD CHART DATA* PROPERLY!';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('charts_alerts', 'charts_alerts', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
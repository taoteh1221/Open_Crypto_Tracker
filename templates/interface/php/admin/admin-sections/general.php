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


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['local_time_offset']['is_select']['assoc'] = array();

$loop = -12;
$loop_max = +14;
while ( $loop <= $loop_max ) {
     
$loop_key = ( $loop >= 0 ? '+' . $loop : $loop );
     
$admin_render_settings['local_time_offset']['is_select']['assoc'][] = array(
                                                                       'key' => $loop_key,
                                                                       'val' => $loop_key . ' Hours UTC Time',
                                                                      );
                                                                      
$loop = $loop + 0.25;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['google_font']['is_select'] = array();

// Fallback to just listing the default google font, IF the google font API is NOT configured
if ( isset($ct['conf']['ext_apis']['google_fonts_api_key']) && $ct['conf']['ext_apis']['google_fonts_api_key'] != '' ) {
$all_google_fonts = $ct['api']->google_fonts('list');
}
elseif ( isset($ct['conf']['gen']['google_font']) && $ct['conf']['gen']['google_font'] != '' ) {
$all_google_fonts = array($ct['conf']['gen']['google_font']);
}

foreach ( $all_google_fonts as $font_name ) {
$admin_render_settings['google_font']['is_select'][] = $font_name;
}

$admin_render_settings['google_font']['is_notes'] = '<a href="https://support.google.com/googleapi/answer/6158862?hl=en&ref_topic=7013279" target="_BLANK">Google Font API Key Required</a> to view list (in "External APIs" section).';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['default_font_size']['is_select']['assoc'] = array();

$loop = round($ct['dev']['min_font_resize'] * 100);
$loop_max = round($ct['dev']['max_font_resize'] * 100);
while ( $loop <= $loop_max ) {
     
$admin_render_settings['default_font_size']['is_select']['assoc'][] = array(
                                                                       'key' => $loop,
                                                                       'val' => $loop . '%',
                                                                      );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['default_theme']['is_radio'] = array(
                                                          'dark',
                                                          'light',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['asset_charts_toggle']['is_radio'] = array(
                                                          'off',
                                                          'on',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['bitcoin_primary_currency_pair']['is_select'] = array();


foreach ( $ct['conf']['power']['bitcoin_currency_markets'] as $pair_key => $unused ) {
$admin_render_settings['bitcoin_primary_currency_pair']['is_select'][] = $pair_key;
}

$admin_render_settings['bitcoin_primary_currency_pair']['is_notes'] = 'MUST BE AVAILABLE ON THE CHOSEN "Bitcoin Primary Exchange" BELOW.';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['bitcoin_primary_exchange']['is_select'] = array();


foreach ( $ct['conf']['assets']['BTC']['pair'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] as $exchange_key => $unused ) {
$admin_render_settings['bitcoin_primary_exchange']['is_select'][] = $exchange_key;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['primary_marketcap_site']['is_radio'] = array(
                                                          'coingecko',
                                                          'coinmarketcap',
                                                         );

$admin_render_settings['primary_marketcap_site']['is_notes'] = '<a href="https://coinmarketcap.com/api" target="_BLANK">CoinMarketCap API Key Required</a> (in "External APIs" section)';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['currency_decimals_max']['is_select'] = array();

$loop = 0;
$loop_max = 15;
while ( $loop <= $loop_max ) {
$admin_render_settings['currency_decimals_max']['is_select'][] = $loop;
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['crypto_decimals_max']['is_select'] = array();

$loop = 0;
$loop_max = 15;
while ( $loop <= $loop_max ) {
$admin_render_settings['crypto_decimals_max']['is_select'][] = $loop;
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['chart_crypto_volume_decimals']['is_select'] = array();

$loop = 0;
$loop_max = 15;
while ( $loop <= $loop_max ) {
$admin_render_settings['chart_crypto_volume_decimals']['is_select'][] = $loop;
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_round_percent']['is_select'] = array(
                                                          'one',
                                                          'tenth',
                                                          'hundredth',
                                                          'thousandth',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['price_round_fixed_decimals']['is_radio'] = array(
                                                          'off',
                                                          'on',
                                                         );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['charts_backup_frequency']['is_select'] = array();

$loop = 0;
$loop_max = 30;
while ( $loop <= $loop_max ) {
$admin_render_settings['charts_backup_frequency']['is_select'][] = $loop;
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'all';

// $ct['admin']->settings_form_fields($conf_id, $interface_id)
$ct['admin']->settings_form_fields('gen', 'general', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
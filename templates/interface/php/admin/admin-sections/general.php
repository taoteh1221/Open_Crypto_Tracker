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

	 <?php
	 if ( $admin_general_error != null ) {
	 ?>
	 <div class='red red_dotted' style='font-weight: bold;'><?=$admin_general_error?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 elseif ( $admin_general_success != null ) {
	 ?>
	 <div class='green green_dotted' style='font-weight: bold;'><?=$admin_general_success?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 ?>
	 

	<!-- UPGRADE ct_conf key START -->

	<div style='margin: 25px;'>
	
	<form id='upgrade_ct_conf' action='admin.php?iframe=<?=$ct['gen']->admin_hashed_nonce('iframe_general')?>&section=general&refresh=all' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct['gen']->admin_hashed_nonce('upgrade_ct_conf')?>' />
	
	<input type='hidden' name='upgrade_ct_conf' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='upgrade_ct_conf_button' class='force_button_style' onclick='
	
	var ct_conf_reset = confirm("Scans your configuration database for upgrades. This will ALWAYS happen automatically after upgrading / downgrading this app, but you can double-check here as well if you are having issues.\n\nIMPORTANT NOTE: If things act weird after an app upgrade, it is more likely due to OUTDATED JAVASCRIPT / CSS FILES in the web browser temporary files cache needing to be cleared.");
	
		if ( ct_conf_reset ) {
		document.getElementById("upgrade_ct_conf_button").disable = true;
		$("#upgrade_ct_conf").submit(); // Triggers "app reloading" sequence
		document.getElementById("upgrade_ct_conf_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Scan For Database Upgrades</button>
	
	</div>
				
	<!-- UPGRADE ct_conf key END -->

	
	<?=$ct['gen']->input_2fa('strict')?>
	

<?php

// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['local_time_offset']['is_range'] = true;

$admin_render_settings['local_time_offset']['range_min'] = -12;

$admin_render_settings['local_time_offset']['range_max'] = 14;

$admin_render_settings['local_time_offset']['range_step'] = 0.25;

$admin_render_settings['local_time_offset']['range_ui_prefix'] = '+';

$admin_render_settings['local_time_offset']['range_ui_suffix'] = ' Hours UTC Time';


////////////////////////////////////////////////////////////////////////////////////////////////


// Fallback to just listing the default google font, IF the google font API is NOT configured
if ( isset($ct['conf']['ext_apis']['google_fonts_api_key']) && $ct['conf']['ext_apis']['google_fonts_api_key'] != '' ) {
$all_google_fonts = $ct['api']->google_fonts('list');
}

if (
!is_array($all_google_fonts) && isset($ct['conf']['gen']['google_font']) && trim($ct['conf']['gen']['google_font']) != ''
|| is_array($all_google_fonts) && sizeof($all_google_fonts) < 1 && isset($ct['conf']['gen']['google_font']) && trim($ct['conf']['gen']['google_font']) != ''
) {
$all_google_fonts = array($ct['conf']['gen']['google_font']);
}

foreach ( $all_google_fonts as $font_name ) {
$admin_render_settings['google_font']['is_select'][] = $font_name;
}

$admin_render_settings['google_font']['is_notes'] = '<a href="https://support.google.com/googleapi/answer/6158862?hl=en&ref_topic=7013279" target="_BLANK">Google Font API Key Required</a> to view list (in "External APIs" section)<br /><a href="javascript:app_reloading_check();" target="_PARENT">Reload the entire app</a>, to apply any changes to this setting.';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['default_font_size']['is_range'] = true;

$admin_render_settings['default_font_size']['range_min'] = round($ct['dev']['min_font_resize'] * 100);

$admin_render_settings['default_font_size']['range_max'] = round($ct['dev']['max_font_resize'] * 100);

$admin_render_settings['default_font_size']['range_step'] = 1;

$admin_render_settings['default_font_size']['range_ui_suffix'] = '%';


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


foreach ( $ct['conf']['power']['bitcoin_currency_markets'] as $pair_key => $unused ) {
$admin_render_settings['bitcoin_primary_currency_pair']['is_select'][] = $pair_key;
}

$admin_render_settings['bitcoin_primary_currency_pair']['is_notes'] = 'MUST BE AVAILABLE ON THE CHOSEN "Bitcoin Primary Exchange" BELOW.';


////////////////////////////////////////////////////////////////////////////////////////////////


// List all exchanges that have a Bitcoin market
foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $unused ) {
			
	foreach ( $ct['conf']['assets']['BTC']['pair'][$pair_key] as $exchange_key => $unused ) {
					
		// Detects better with side space included
		if ( stristr($supported_btc_exchange_scan, ' ' . $exchange_key . ' ') == false && stristr($exchange_key, 'bitmex_') == false ) { // Futures markets not allowed
          $admin_render_settings['bitcoin_primary_exchange']['is_select'][] = $exchange_key;
		$supported_btc_exchange_scan .= ' ' . $exchange_key . ' /';
		}
				
	}
	
sort($admin_render_settings['bitcoin_primary_exchange']['is_select']);
				
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['primary_marketcap_site']['is_radio'] = array(
                                                          'coingecko',
                                                          'coinmarketcap',
                                                         );

$admin_render_settings['primary_marketcap_site']['is_notes'] = '<a href="https://coinmarketcap.com/api" target="_BLANK">CoinMarketCap API Key Required</a> (in "External APIs" section)';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['currency_decimals_max']['is_range'] = true;

$admin_render_settings['currency_decimals_max']['range_min'] = 0;

$admin_render_settings['currency_decimals_max']['range_max'] = 15;

$admin_render_settings['currency_decimals_max']['range_step'] = 1;


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['crypto_decimals_max']['is_range'] = true;

$admin_render_settings['crypto_decimals_max']['range_min'] = 0;

$admin_render_settings['crypto_decimals_max']['range_max'] = 15;

$admin_render_settings['crypto_decimals_max']['range_step'] = 1;


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['chart_crypto_volume_decimals']['is_range'] = true;

$admin_render_settings['chart_crypto_volume_decimals']['range_min'] = 0;

$admin_render_settings['chart_crypto_volume_decimals']['range_max'] = 10;

$admin_render_settings['chart_crypto_volume_decimals']['range_step'] = 1;


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

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('gen', 'general', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
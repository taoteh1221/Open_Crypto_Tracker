<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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
	

<?php

// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['local_time_offset']['is_range'] = true;

$ct['admin_render_settings']['local_time_offset']['range_min'] = -12;

$ct['admin_render_settings']['local_time_offset']['range_max'] = 14;

$ct['admin_render_settings']['local_time_offset']['range_step'] = 0.25;

$ct['admin_render_settings']['local_time_offset']['range_ui_prefix'] = '+';

$ct['admin_render_settings']['local_time_offset']['range_ui_suffix'] = ' Hours UTC Time';


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
$ct['admin_render_settings']['google_font']['is_select'][] = $font_name;
}

$ct['admin_render_settings']['google_font']['is_notes'] = '<a href="https://support.google.com/googleapi/answer/6158862?hl=en&ref_topic=7013279" target="_BLANK">Google Font API Key Required</a> to view list (in "External APIs" section)<br /><a href="javascript:app_reloading_check();" target="_PARENT">Reload the entire app</a>, to apply any changes to this setting.';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['default_font_size']['is_range'] = true;

$ct['admin_render_settings']['default_font_size']['range_min'] = round($ct['dev']['min_font_resize'] * 100);

$ct['admin_render_settings']['default_font_size']['range_max'] = round($ct['dev']['max_font_resize'] * 100);

$ct['admin_render_settings']['default_font_size']['range_step'] = 5;

$ct['admin_render_settings']['default_font_size']['range_ui_suffix'] = '%';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['default_theme']['is_radio'] = array(
                                                            'dark',
                                                            'light',
                                                           );


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['primary_marketcap_site']['is_radio'] = array(
                                                          'coingecko',
                                                          'coinmarketcap',
                                                         );

$ct['admin_render_settings']['primary_marketcap_site']['is_notes'] = '<a href="https://coinmarketcap.com/api" target="_BLANK">CoinMarketCap API Key Required</a> (in "External APIs" section)';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
$ct['admin_render_settings']['is_refresh_admin'] = 'all';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('gen', 'general', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
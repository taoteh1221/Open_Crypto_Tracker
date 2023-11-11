<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's subdirectory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {


// Render config settings for this plugin...


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['runtime_mode']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['ui_location']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['ui_name']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['alerts_frequency_maximum']['is_range'] = true;

$admin_render_settings['alerts_frequency_maximum']['range_ui_meta_data'] = 'zero_is_unlimited';

$admin_render_settings['alerts_frequency_maximum']['range_min'] = 0;

$admin_render_settings['alerts_frequency_maximum']['range_max'] = 360;

$admin_render_settings['alerts_frequency_maximum']['range_step'] = 15;

$admin_render_settings['alerts_frequency_maximum']['range_ui_prefix'] = 'Every ';

$admin_render_settings['alerts_frequency_maximum']['range_ui_suffix'] = ' Minutes';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$admin_render_settings['price_targets']['is_repeatable']['add_button'] = 'Add Price Target Alert (at bottom)';
     
$admin_render_settings['price_targets']['is_repeatable']['is_text'] = true; // SINGLE (NON array)

$admin_render_settings['price_targets']['is_repeatable']['text_field_size'] = 45;


// FILLED IN setting values


if ( sizeof($ct['conf']['plug_conf'][$this_plug]['price_targets']) > 0 ) {


// Sort alphabetically
sort($ct['conf']['plug_conf'][$this_plug]['price_targets']);


     foreach ( $ct['conf']['plug_conf'][$this_plug]['price_targets'] as $key => $val ) {
     $admin_render_settings['price_targets']['is_subarray'][$key]['is_text'] = true;
     $admin_render_settings['price_targets']['is_subarray'][$key]['text_field_size'] = 45;
     }


}
else {
$admin_render_settings['price_targets']['is_subarray'][0]['is_text'] = true;
$admin_render_settings['price_targets']['is_subarray'][0]['text_field_size'] = 45;
}


$admin_render_settings['price_targets']['is_notes'] = 'This format MUST be used (NO thousands seperator):<br />asset-pair-exchange_id = 1234.5678';
         

////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('plug_conf|' . $this_plug, $this_plug, $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>
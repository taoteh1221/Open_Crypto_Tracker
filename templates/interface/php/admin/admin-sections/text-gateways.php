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


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$admin_render_settings['text_gateways']['is_repeatable']['add_button'] = 'Add Text Gateway (at bottom)';

$admin_render_settings['text_gateways']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$admin_render_settings['text_gateways']['is_repeatable']['text_field_size'] = 35;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['mobile_network']['text_gateways']) > 0 ) {

     foreach ( $ct['conf']['mobile_network']['text_gateways'] as $key => $val ) {
     $admin_render_settings['text_gateways']['is_subarray'][$key]['is_text'] = true;
     $admin_render_settings['text_gateways']['is_subarray'][$key]['text_field_size'] = 35;
     }

}
else {
$admin_render_settings['text_gateways']['is_subarray'][0]['is_text'] = true;
$admin_render_settings['text_gateways']['is_subarray'][0]['text_field_size'] = 35;
}


$admin_render_settings['text_gateways']['is_notes'] = 'Mobile text gateways, used for emailing mobile texts to phone numbers on these networks<br />This format MUST be used: network_id||network_gateway.com';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('mobile_network', 'text_gateways', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>
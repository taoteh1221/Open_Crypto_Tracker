<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>


<?php
if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's subdirectory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor.
	
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

$loop = 0;
$loop_max = 600;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['alerts_frequency_maximum']['is_select']['is_assoc'][] = array(
                                                                                      'key' => $loop,
                                                                                      'val' => ( $loop == 0 ? 'Unlimited' : 'Every ' . $loop . ' Minutes'),
                                                                                     );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['privacy_mode']['is_radio'] = array(
                                                           'off',
                                                           'on',
                                                          );


////////////////////////////////////////////////////////////////////////////////////////////////
     
     
foreach ( $ct['conf']['plug_conf'][$this_plug]['tracking'] as $key => $val ) {
         
     foreach ( $val as $tracked_key => $tracked_val ) {
     
          if ( $tracked_key === 'asset' ) {
               
          $admin_render_settings['tracking']['is_subarray'][$key]['is_select'][$tracked_key] = array(
                                                                                                      'btc',
                                                                                                      'eth',
                                                                                                      'sol',
                                                                                                      'sol||usdc',
                                                                                                     );
                                                                                                     
          }
          else {                                               
          $admin_render_settings['tracking']['is_subarray'][$key]['is_text'][$tracked_key] = true;
          $admin_render_settings['tracking']['is_subarray'][$key]['text_field_size'] = 50;
          }

     }
                                                                      
}


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->settings_form_fields($conf_id, $interface_id)
$ct['admin']->settings_form_fields('plug_conf|' . $this_plug, $this_plug, $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
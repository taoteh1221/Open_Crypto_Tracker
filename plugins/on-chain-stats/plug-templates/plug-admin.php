<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's subdirectory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor.
	
	</p>

<?php
}
else {
?>
	
<p class='bitcoin'>This plugin is NOT functional yet, as it's still in the early development phase.</p>

<?php

// Render config settings for this plugin...


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['runtime_mode']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['ui_location']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['ui_name']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
//$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
//$ct['admin']->admin_config_interface('plug_conf|' . $this_plug, $this_plug, $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>
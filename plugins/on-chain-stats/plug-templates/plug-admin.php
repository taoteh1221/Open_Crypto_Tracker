<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's subdirectory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {
?>
	
<p class='bitcoin'>This plugin is a work-in-progress, as it's still in the early development phase.</p>

<?php

// Render config settings for this plugin...



////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['solana_node_count_chart_defaults']['is_text'] = true;

$ct['admin_render_settings']['solana_node_count_chart_defaults']['is_notes'] = 'This format MUST be used: chart_height||menu_size<br />(chart height min/max = 400/900 (increments of 100), menu size min/max = 7/16)';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
// SHOULD BE COMMA-DELIMITED: 'iframe_reset_backup_restore,iframe_apis'
$ct['admin_render_settings']['is_refresh_admin'] = 'iframe_reset_backup_restore,iframe_apis';


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>
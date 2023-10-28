<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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
	
	<p> Coming Soon&trade; </p>
	
	<p class='bitcoin bitcoin_dotted'>
	
	These sections / category pages will be INCREMENTALLY populated with the corrisponding admin configuration options, over a period of time AFTER the initial v6.00.x releases (versions 6.00.x will only test the back-end / under-the-hood stability of NORMAL / MEDIUM / HIGH MODES of the Admin Interface security levels). <br /><br />You may need to turn off "Normal" OR "Medium" mode of the Admin Interface security level (at the top of the "Security" section in this admin area), to edit any UNFINISHED SECTIONS by hand in the config files (config.php in the app install folder, and any plug-conf.php files in the plugins folders).
	
	</p>
	
<?php


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['proxy_alert']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$admin_render_settings['proxy_alert']['is_notes'] = '(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$admin_render_settings['proxy_alert_frequency_maximum']['is_range'] = true;

$admin_render_settings['proxy_alert_frequency_maximum']['range_ui_meta_data'] = 'zero_is_unlimited';

$admin_render_settings['proxy_alert_frequency_maximum']['range_min'] = 0;

$admin_render_settings['proxy_alert_frequency_maximum']['range_max'] = 72;

$admin_render_settings['proxy_alert_frequency_maximum']['range_step'] = 1;

$admin_render_settings['proxy_alert_frequency_maximum']['range_ui_prefix'] = 'Every ';

$admin_render_settings['proxy_alert_frequency_maximum']['range_ui_suffix'] = ' Hours';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['proxy_alert_runtime']['is_radio'] = array(
                                                                    'cron',
                                                                    'ui',
                                                                    'all',
                                                                   );


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['proxy_alert_checkup_ok']['is_radio'] = array(
                                                                    'ignore',
                                                                    'include',
                                                                   );


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
//$ct['admin']->admin_config_interface('proxy', 'proxy', $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
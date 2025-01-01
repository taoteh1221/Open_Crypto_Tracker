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


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['allow_proxies']['is_radio'] = array(
                                                          'off',
                                                          'on',
                                                         );

$ct['admin_render_settings']['allow_proxies']['is_notes'] = 'Enable / Disable using any added proxies below for API data requests';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['proxy_login']['is_text'] = true;

$ct['admin_render_settings']['proxy_login']['is_password'] = true;

$ct['admin_render_settings']['proxy_login']['text_field_size'] = 25;

$ct['admin_render_settings']['proxy_login']['is_notes'] = 'This format MUST be used: username||password<br />(leave BLANK if your proxy service uses ip-whitelisting instead)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['proxy_alert_channels']['is_select'] = array(
                                                          'off',
                                                          'email',
                                                          'text',
                                                          'notifyme',
                                                          'telegram',
                                                          'all',
                                                         );

$ct['admin_render_settings']['proxy_alert_channels']['is_notes'] = 'Receive alerts IF a proxy FAILS (per-proxy)<br />(see "External APIs" section for using any comms-related APIs)';
                                                         
                                                         
////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['proxy_alert_frequency_maximum']['is_range'] = true;

$ct['admin_render_settings']['proxy_alert_frequency_maximum']['range_ui_meta_data'] .= 'zero_is_unlimited;';

$ct['admin_render_settings']['proxy_alert_frequency_maximum']['range_min'] = 0;

$ct['admin_render_settings']['proxy_alert_frequency_maximum']['range_max'] = 72;

$ct['admin_render_settings']['proxy_alert_frequency_maximum']['range_step'] = 1;

$ct['admin_render_settings']['proxy_alert_frequency_maximum']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['proxy_alert_frequency_maximum']['range_ui_suffix'] = ' Hours';

$ct['admin_render_settings']['proxy_alert_frequency_maximum']['is_notes'] = 'How often you want to receive alerts on proxy failure (per-proxy)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['proxy_alert_runtime']['is_radio'] = array(
                                                                    'cron',
                                                                    'ui',
                                                                    'all',
                                                                   );

$ct['admin_render_settings']['proxy_alert_runtime']['is_notes'] = 'Only receive alerts on proxy failure for these runtimes (per-proxy)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['proxy_alert_checkup_ok']['is_radio'] = array(
                                                                    'ignore',
                                                                    'include',
                                                                   );

$ct['admin_render_settings']['proxy_alert_checkup_ok']['is_notes'] = 'If a FAILED proxy connects OK (during a checkup) immediately after it fails, what to do with the alert BEFORE ALERTING YOU';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['anti_proxy_servers']['is_repeatable']['add_button'] = 'Add Anti-Proxy Server Domain (at bottom)';

$ct['admin_render_settings']['anti_proxy_servers']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['anti_proxy_servers']['is_repeatable']['text_field_size'] = 25;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['proxy']['anti_proxy_servers']) > 0 ) {

     foreach ( $ct['conf']['proxy']['anti_proxy_servers'] as $key => $val ) {
     $ct['admin_render_settings']['anti_proxy_servers']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['anti_proxy_servers']['is_subarray'][$key]['text_field_size'] = 25;
     }

}
else {
$ct['admin_render_settings']['anti_proxy_servers']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['anti_proxy_servers']['is_subarray'][0]['text_field_size'] = 25;
}


$ct['admin_render_settings']['anti_proxy_servers']['is_notes'] = '(DOMAIN ONLY, API servers set to SKIP USING PROXIES ON [because they block proxies])';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['proxy_list']['is_repeatable']['add_button'] = 'Add Proxy Server (at bottom)';

$ct['admin_render_settings']['proxy_list']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['proxy_list']['is_repeatable']['text_field_size'] = 60;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['proxy']['proxy_list']) > 0 ) {

     foreach ( $ct['conf']['proxy']['proxy_list'] as $key => $unused ) {
     $ct['admin_render_settings']['proxy_list']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['proxy_list']['is_subarray'][$key]['text_field_size'] = 60;
     }

}
else {
$ct['admin_render_settings']['proxy_list']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['proxy_list']['is_subarray'][0]['text_field_size'] = 60;
}


$ct['admin_render_settings']['proxy_list']['is_notes'] = 'This format MUST be used: ip_address:port_number<br />Best proxy service I\'ve tested on: <a href="https://proxyscrape.com/premium-free-trial" target="_BLANK">ProxyScrape.com ("free forever" trial)</a><br />IF THIS APP FREEZES / HANGS A LONG TIME UPON LOADING, ***AFTER ADDING PROXIES HERE***, YOU PROBABLY HAVE A BAD PROXY CONFIGURATION SOMEWHERE!';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
$ct['admin_render_settings']['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('proxy', 'proxy', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	
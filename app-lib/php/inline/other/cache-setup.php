<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Current runtime user (to determine how we want to set directory / file permissions)
if ( function_exists('posix_getpwuid') && function_exists('posix_geteuid') ) {
$ct['current_runtime_user'] = posix_getpwuid(posix_geteuid())['name'];
}
elseif ( function_exists('get_current_user') ) {
$ct['current_runtime_user'] = get_current_user();
}
else {
$ct['current_runtime_user'] = null;
}


// Get WEBSERVER runtime user (from cache if currently running from CLI)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE (IF NEEDED) FOR THIS PARTICULAR SERVER'S SETUP
// WE HAVE FALLBACKS IF THIS IS NULL IN $ct['cache']->save_file() WHEN WE STORE CACHE FILES, SO A BRAND NEW INTALL RUN FIRST VIA CRON IS #OK#
$ct['http_runtime_user'] = ( $ct['runtime_mode'] != 'cron' ? $ct['current_runtime_user'] : trim( file_get_contents($ct['base_dir'] . '/cache/vars/http_runtime_user.dat') ) );

					
// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$ct['possible_http_users'] = array(
    						'www-data',
    						'apache',
    						'apache2',
    						'httpd',
    						'httpd2',
					   );
							

// Check for cache directory path creation, create if needed...if it fails, flag a force exit and alert end-user

if (
$ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/light_chart_rebuilds/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/system/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/throttling/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/upgrading/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/logging/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/first_run/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/market_error_tracking/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/secured/activation/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/secured/other_data/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/secured/backups/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/secured/external_data/cookies') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/secured/messages/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/secured/access_stats/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/archival/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/light/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/charts/system/archival/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/charts/system/light/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/logs/debug/external_data/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/logs/debug/cron/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/logs/error/external_data/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/other/xml_error_parsing/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/other/market_id_parsing/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/alerts/fiat_price/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/assets/stocks/overviews/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/internal_api/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/vars/state-tracking/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/cache/debugging/') != true
|| $ct['gen']->dir_struct($ct['base_dir'] . '/plugins/') != true
) {
    
    foreach ( $ct['change_dir_perm'] as $dir ) {
    $dir_error_detail = explode(':', $dir);
    $dir_errors = $dir_error_detail[0] .  ' (CURRENT permission: '.$dir_error_detail[1].')<br />';
    }
	
$system_error = 'Cannot create these sub-directories WITH THE PROPER PERMISSIONS (chmod 770 on unix / linux systems, "writable/readable" on Windows): <br /><br /> ' . $dir_errors . ' <br /> ADDITIONALLY, please ALSO make sure the primary sub-directories "/cache/" and "/plugins/" are created, and have FULL read / write permissions (chmod 770 on unix / linux systems, "writable/readable" on Windows), so the required files and secondary sub-directories can be created automatically<br /><br />';

echo $system_error;

exit;

}

  
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
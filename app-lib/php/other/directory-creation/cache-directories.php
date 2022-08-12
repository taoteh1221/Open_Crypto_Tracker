<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Current runtime user (to determine how we want to set directory / file permissions)
if ( function_exists('posix_getpwuid') && function_exists('posix_geteuid') ) {
$current_runtime_user = posix_getpwuid(posix_geteuid())['name'];
}
elseif ( function_exists('get_current_user') ) {
$current_runtime_user = get_current_user();
}
else {
$current_runtime_user = null;
}


// Get WEBSERVER runtime user (from cache if currently running from CLI)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE (IF NEEDED) FOR THIS PARTICULAR SERVER'S SETUP
// WE HAVE FALLBACKS IF THIS IS NULL IN $ct_cache->save_file() WHEN WE STORE CACHE FILES, SO A BRAND NEW INTALL RUN FIRST VIA CRON IS #OK#
$http_runtime_user = ( $runtime_mode != 'cron' ? $current_runtime_user : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );

					
// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$possible_http_users = array(
    						'www-data',
    						'apache',
    						'apache2',
    						'httpd',
    						'httpd2',
							);


// Check for cache directory path creation, create if needed...if it fails, flag a force exit and alert end-user

if ( $ct_gen->dir_struct('cache/alerts/fiat_price/') != true
|| $ct_gen->dir_struct('cache/charts/spot_price_24hr_volume/archival/') != true
|| $ct_gen->dir_struct('cache/charts/spot_price_24hr_volume/light/') != true
|| $ct_gen->dir_struct('cache/charts/system/archival/') != true
|| $ct_gen->dir_struct('cache/charts/system/light/') != true
|| $ct_gen->dir_struct('cache/events/light_chart_rebuilds/') != true
|| $ct_gen->dir_struct('cache/events/system/') != true
|| $ct_gen->dir_struct('cache/events/throttling/') != true
|| $ct_gen->dir_struct('cache/internal_api/') != true
|| $ct_gen->dir_struct('cache/logs/debug/external_data/') != true
|| $ct_gen->dir_struct('cache/logs/error/external_data/') != true
|| $ct_gen->dir_struct('cache/secured/activation/') != true
|| $ct_gen->dir_struct('cache/secured/backups/') != true
|| $ct_gen->dir_struct('cache/secured/external_data/') != true
|| $ct_gen->dir_struct('cache/secured/messages/') != true
|| $ct_gen->dir_struct('cache/vars/') != true
|| $ct_gen->dir_struct('plugins/') != true ) {
    
    foreach ( $change_dir_perm as $dir ) {
    $dir_error_detail = explode(':', $dir);
    $dir_errors = $dir_error_detail[0] .  ' (CURRENT permission: '.$dir_error_detail[1].')<br />';
    }
	
$system_error = 'Cannot create these sub-directories WITH THE PROPER PERMISSIONS (chmod 770 on unix / linux systems, "writable/readable" on Windows): <br /><br /> ' . $dir_errors . ' <br /> ADDITIONALLY, please ALSO make sure the primary sub-directories "/cache/" and "/plugins/" are created, and have FULL read / write permissions (chmod 770 on unix / linux systems, "writable/readable" on Windows), so the required files and secondary sub-directories can be created automatically<br /><br />';

$ct_gen->log('system_error', $system_error);

echo $system_error;

$force_exit = 1;

}
  
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
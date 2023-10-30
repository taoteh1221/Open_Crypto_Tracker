<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Check for cache directory path creation, create if needed...if it fails, flag a force exit and alert end-user

if ( $ct['gen']->dir_struct('cache/alerts/fiat_price/') != true
|| $ct['gen']->dir_struct('cache/charts/spot_price_24hr_volume/archival/') != true
|| $ct['gen']->dir_struct('cache/charts/spot_price_24hr_volume/light/') != true
|| $ct['gen']->dir_struct('cache/charts/system/archival/') != true
|| $ct['gen']->dir_struct('cache/charts/system/light/') != true
|| $ct['gen']->dir_struct('cache/events/light_chart_rebuilds/') != true
|| $ct['gen']->dir_struct('cache/events/system/') != true
|| $ct['gen']->dir_struct('cache/events/throttling/') != true
|| $ct['gen']->dir_struct('cache/internal_api/') != true
|| $ct['gen']->dir_struct('cache/logs/debug/external_data/') != true
|| $ct['gen']->dir_struct('cache/logs/debug/cron/') != true
|| $ct['gen']->dir_struct('cache/logs/error/external_data/') != true
|| $ct['gen']->dir_struct('cache/secured/activation/') != true
|| $ct['gen']->dir_struct('cache/secured/backups/') != true
|| $ct['gen']->dir_struct('cache/secured/external_data/') != true
|| $ct['gen']->dir_struct('cache/secured/messages/') != true
|| $ct['gen']->dir_struct('cache/vars/state-tracking/') != true
|| $ct['gen']->dir_struct('cache/other/') != true
|| $ct['gen']->dir_struct('plugins/') != true ) {
    
    foreach ( $change_dir_perm as $dir ) {
    $dir_error_detail = explode(':', $dir);
    $dir_errors = $dir_error_detail[0] .  ' (CURRENT permission: '.$dir_error_detail[1].')<br />';
    }
	
$system_error = 'Cannot create these sub-directories WITH THE PROPER PERMISSIONS (chmod 770 on unix / linux systems, "writable/readable" on Windows): <br /><br /> ' . $dir_errors . ' <br /> ADDITIONALLY, please ALSO make sure the primary sub-directories "/cache/" and "/plugins/" are created, and have FULL read / write permissions (chmod 770 on unix / linux systems, "writable/readable" on Windows), so the required files and secondary sub-directories can be created automatically<br /><br />';

$ct['gen']->log('system_error', $system_error);

echo $system_error;

$force_exit = 1;

}
  
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
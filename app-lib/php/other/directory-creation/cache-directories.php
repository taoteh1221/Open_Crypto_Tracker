<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Check for cache directory path creation, create if needed...if it fails, flag a force exit and alert end-user

if ( $oct_gen->dir_struct('cache/alerts/fiat_price/') != true
|| $oct_gen->dir_struct('cache/charts/spot_price_24hr_volume/archival/') != true
|| $oct_gen->dir_struct('cache/charts/spot_price_24hr_volume/lite/') != true
|| $oct_gen->dir_struct('cache/charts/system/archival/') != true
|| $oct_gen->dir_struct('cache/charts/system/lite/') != true
|| $oct_gen->dir_struct('cache/events/lite_chart_rebuilds/') != true
|| $oct_gen->dir_struct('cache/events/throttling/') != true
|| $oct_gen->dir_struct('cache/internal_api/') != true
|| $oct_gen->dir_struct('cache/logs/debug/external_data/') != true
|| $oct_gen->dir_struct('cache/logs/error/external_data/') != true
|| $oct_gen->dir_struct('cache/secured/activation/') != true
|| $oct_gen->dir_struct('cache/secured/backups/') != true
|| $oct_gen->dir_struct('cache/secured/external_data/') != true
|| $oct_gen->dir_struct('cache/secured/messages/') != true
|| $oct_gen->dir_struct('cache/vars/') != true
|| $oct_gen->dir_struct('plugins/') != true ) {
	
$system_error = 'Cannot create cache or cron-plugin sub-directories. Please make sure the primary sub-directories "/cache/" and "/plugins/" are created, and have FULL read / write permissions (chmod 777 on unix / linux systems), so the required files and secondary sub-directories can be created automatically. <br /><br />';

$oct_gen->log('system_error', $system_error);

echo $system_error;

$force_exit = 1;

}
  
 
?>
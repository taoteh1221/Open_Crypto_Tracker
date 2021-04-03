<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// Check for cache directory path creation, create if needed...if it fails, flag a force exit and alert end-user

if ( $ocpt_gen->dir_struct('cache/alerts/') != true
|| $ocpt_gen->dir_struct('cache/charts/spot_price_24hr_volume/archival/') != true
|| $ocpt_gen->dir_struct('cache/charts/spot_price_24hr_volume/lite/') != true
|| $ocpt_gen->dir_struct('cache/charts/system/archival/') != true
|| $ocpt_gen->dir_struct('cache/charts/system/lite/') != true
|| $ocpt_gen->dir_struct('cache/events/lite_chart_rebuilds/') != true
|| $ocpt_gen->dir_struct('cache/events/throttling/') != true
|| $ocpt_gen->dir_struct('cache/internal-api/') != true
|| $ocpt_gen->dir_struct('cache/logs/debugging/external_api/') != true
|| $ocpt_gen->dir_struct('cache/logs/errors/external_api/') != true
|| $ocpt_gen->dir_struct('cache/secured/activation/') != true
|| $ocpt_gen->dir_struct('cache/secured/backups/') != true
|| $ocpt_gen->dir_struct('cache/secured/external_api/') != true
|| $ocpt_gen->dir_struct('cache/secured/messages/') != true
|| $ocpt_gen->dir_struct('cache/vars/') != true
|| $ocpt_gen->dir_struct('plugins/') != true ) {
$system_error = 'Cannot create cache or cron-plugin sub-directories. Please make sure the primary sub-directories "/cache/" and "/plugins/" are created, and have FULL read / write permissions (chmod 777 on unix / linux systems), so the required files and secondary sub-directories can be created automatically. <br /><br />';
$ocpt_gen->app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}
  
 
?>
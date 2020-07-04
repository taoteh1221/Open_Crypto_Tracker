<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Check for cache directory path creation, create if needed...if it fails, flag a force exit and alert end-user

if ( dir_structure('cache/alerts/') != true
|| dir_structure('cache/charts/spot_price_24hr_volume/archival/') != true
|| dir_structure('cache/charts/spot_price_24hr_volume/lite/') != true
|| dir_structure('cache/charts/system/archival/') != true
|| dir_structure('cache/charts/system/lite/') != true
|| dir_structure('cache/events/lite_chart_rebuilds/') != true
|| dir_structure('cache/events/throttling/') != true
|| dir_structure('cache/internal-api/') != true
|| dir_structure('cache/logs/debugging/external_api/') != true
|| dir_structure('cache/logs/errors/external_api/') != true
|| dir_structure('cache/secured/activation/') != true
|| dir_structure('cache/secured/backups/') != true
|| dir_structure('cache/secured/external_api/') != true
|| dir_structure('cache/secured/messages/') != true
|| dir_structure('cache/vars/') != true
|| dir_structure('cron-plugins/') != true ) {
$system_error = 'Cannot create cache or cron-plugin sub-directories. Please make sure the primary sub-directories "/cache/" and "/cron-plugins/" are created, and have FULL read / write permissions (chmod 777 on unix / linux systems), so the required files and secondary sub-directories can be created automatically. <br /><br />';
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}
  
 
?>
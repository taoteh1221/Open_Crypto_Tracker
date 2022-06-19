<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



// ALL CHARTS FOR SPOT PRICE / 24 HOUR VOLUME
foreach ( $ct_conf['charts_alerts']['tracked_mrkts'] as $key => $val ) {

	// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
	$asset_dir = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
	$asset_dir = strtoupper($asset_dir);
		
	$asset_cache_params = explode("||", $val);
	
	if ( $asset_cache_params[2] == 'chart' || $asset_cache_params[2] == 'both' ) {
		
		// Archival charts
		if ( $ct_gen->dir_struct($base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset_dir.'/') != true ) { // Attempt to create directory if it doesn't exist
		$disabled_caching = 1;
		}
		
		// Lite charts
		foreach( $ct_conf['power']['lite_chart_day_intervals'] as $lite_chart_days ) {
			
			if ( $ct_gen->dir_struct($base_dir . '/cache/charts/spot_price_24hr_volume/lite/'.$lite_chart_days.'_days/'.$asset_dir.'/') != true ) { // Attempt to create directory if it doesn't exist
			$disabled_caching = 1;
			}
			
		}
	
	}
	
}

// LITE CHARTS FOR SYSTEM STATS
foreach( $ct_conf['power']['lite_chart_day_intervals'] as $lite_chart_days ) {
			
	if ( $ct_gen->dir_struct($base_dir . '/cache/charts/system/lite/'.$lite_chart_days.'_days/') != true ) { // Attempt to create directory if it doesn't exist
	$disabled_caching = 1;
	}
			
}

if ( $disabled_caching == 1 ) {
    
    foreach ( $change_dir_perm as $dir ) {
    $dir_error_detail = explode(':', $dir);
    $dir_errors = $dir_error_detail[0] .  ' (CURRENT permission: '.$dir_error_detail[1].')<br />';
    }
	
	
$system_error = 'Cannot create these sub-directories WITH THE PROPER PERMISSIONS (chmod 770 on unix / linux systems, "writable/readable" on Windows): <br /><br /> ' . $dir_errors . ' <br /> ADDITIONALLY, please ALSO make sure the primary sub-directories "/cache/" and "/plugins/" are created, and have FULL read / write permissions (chmod 770 on unix / linux systems, "writable/readable" on Windows), so the required files and secondary sub-directories can be created automatically. <br /><br />';

$ct_gen->log('system_error', $system_error);

echo $system_error;

// Log errors before exiting
$ct_cache->error_log();

exit;
}
///////////////////////////////////////////////////////////////////////////
// END chart sub-directory creation
///////////////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>
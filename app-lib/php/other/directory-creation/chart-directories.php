<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



// ALL CHARTS FOR SPOT PRICE / 24 HOUR VOLUME
foreach ( $ct_conf['charts_alerts']['tracked_markets'] as $key => $val ) {

	// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
	$asset_dir = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
	$asset_dir = strtoupper($asset_dir);
		
	$asset_cache_params = explode("||", $val);
	
	if ( $asset_cache_params[2] == 'chart' || $asset_cache_params[2] == 'both' ) {
		
		// Archival charts
		if ( $ct_gen->dir_struct($base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset_dir.'/') != TRUE ) { // Attempt to create directory if it doesn't exist
		$disabled_caching = 1;
		}
		
		// Lite charts
		foreach( $ct_conf['power']['lite_chart_day_intervals'] as $lite_chart_days ) {
			
			if ( $ct_gen->dir_struct($base_dir . '/cache/charts/spot_price_24hr_volume/lite/'.$lite_chart_days.'_days/'.$asset_dir.'/') != TRUE ) { // Attempt to create directory if it doesn't exist
			$disabled_caching = 1;
			}
			
		}
	
	}
	
}

// LITE CHARTS FOR SYSTEM STATS
foreach( $ct_conf['power']['lite_chart_day_intervals'] as $lite_chart_days ) {
			
	if ( $ct_gen->dir_struct($base_dir . '/cache/charts/system/lite/'.$lite_chart_days.'_days/') != TRUE ) { // Attempt to create directory if it doesn't exist
	$disabled_caching = 1;
	}
			
}

if ( $disabled_caching == 1 ) {
echo "Improper directory permissions on the '/cache/charts/' sub-directories, cannot create new sub-directories. Make sure the folder '/cache/charts/' AND ANY SUB-DIRECTORIES IN IT have read / write permissions (and further sub-directories WITHIN THESE should be created automatically)";
exit;
}
///////////////////////////////////////////////////////////////////////////
// END chart sub-directory creation
///////////////////////////////////////////////////////////////////////////

  
 
 ?>
<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Check for cache directory path creation, create if needed...if it fails, log the error

if (
$ct['gen']->dir_struct( $ct['plug']->chart_cache('/'.$network_name_key.'/archival/') ) != true
|| $ct['gen']->dir_struct( $ct['plug']->chart_cache('/'.$network_name_key.'/overwrites/') ) != true
|| $ct['gen']->dir_struct( $ct['plug']->chart_cache('/'.$network_name_key.'/temp/'.$network_name_key.'_nodes_geolocation/') ) != true
) {

$ct['gen']->log(
        			'system_error',
        			'cannot create "'.$this_plug.'" plugin directories'
        			);
        
}

		
// Light charts

// Make sure light chart path is registered
$ct['cache']->manage_light_charts( $ct['plug']->chart_cache('/'.$network_name_key.'/light') );


// Light chart structure
foreach( $ct['light_chart_day_intervals'] as $light_chart_days ) {
			
	// Attempt to create directory if it doesn't exist
	if ( $ct['gen']->dir_struct( $ct['plug']->chart_cache('/'.$network_name_key.'/light/'.$light_chart_days.'_days/') ) != true ) { 
	$no_onchain_stat_light_charts = true;
	}
			
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
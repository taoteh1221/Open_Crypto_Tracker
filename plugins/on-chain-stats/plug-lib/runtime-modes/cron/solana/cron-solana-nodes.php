<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// In case a rare error occurred from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
// (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
$now = time();

$solana_node_stats_tracking_file = $ct['plug']->event_cache('update_solana_node_stats.dat');
        
        
// Only update once daily, AND only if timestamp is NOT corrupt (indicating LIKELY system stability)
if ( $now > 0 && $ct['cache']->update_cache($solana_node_stats_tracking_file, 1440) == true ) {


// Get / process on-chain Solana nodes data
$solana_nodes_data = $plug['class'][$this_plug]->solana_nodes_data();


     // NODE COUNT
     
     if ( isset($solana_nodes_data['all_nodes']) ) {
     $solana_nodes_count_data_set .= '||' . sizeof($solana_nodes_data['all_nodes']);
     }
     else {
     $solana_nodes_count_data_set .= '||NO_DATA';
     }
     
     
     if ( isset($solana_nodes_data['validators']) ) {
     $solana_nodes_count_data_set .= '||' . sizeof($solana_nodes_data['validators']);
     }
     else {
     $solana_nodes_count_data_set .= '||NO_DATA';
     }
     
     
$solana_nodes_count_chart_file = $ct['plug']->chart_cache('solana_nodes_count.dat');
     
$solana_nodes_count_data_set = $now . $solana_nodes_count_data_set;
     
// WITH newline (UNLOCKED file write)
$ct['cache']->save_file($solana_nodes_count_chart_file, $solana_nodes_count_data_set . "\n", "append", false);  
     

////////////////////////////////////////////


     // NODE GEOLOCATION


     if ( isset($solana_nodes_data['geolocation']) ) {
     
     $solana_nodes_geolocation_data_set = json_encode($solana_nodes_data['geolocation'], JSON_PRETTY_PRINT);
     
     // WITH newline (UNLOCKED file write)
     $ct['cache']->save_file( $ct['plug']->chart_cache('solana_nodes_geolocation.dat') , $solana_nodes_geolocation_data_set); 
     
     }


////////////////////////////////////////////


     // NODE VERSION
     
     
     if ( isset($solana_nodes_data['version']) ) {
     
     $solana_node_version_data_set = json_encode($solana_nodes_data['version'], JSON_PRETTY_PRINT);
     
     // WITH newline (UNLOCKED file write)
     $ct['cache']->save_file( $ct['plug']->chart_cache('solana_nodes_version.dat') , $solana_node_version_data_set); 
     
     }


// Update the event tracking
$ct['cache']->save_file($solana_node_stats_tracking_file, $ct['gen']->time_date_format(false, 'pretty_date_time') );

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
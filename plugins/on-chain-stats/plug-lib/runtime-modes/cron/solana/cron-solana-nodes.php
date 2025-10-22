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


// Get on-chain Solana nodes data
$solana_nodes_onchain = $plug['class'][$this_plug]->solana_nodes_onchain();


     // ALL NODE COUNT
     if ( isset($solana_nodes_onchain['all_nodes']) ) {
     $solana_nodes_count_data_set .= '||' . sizeof($solana_nodes_onchain['all_nodes']);
     }
     else {
     $solana_nodes_count_data_set .= '||NO_DATA';
     }
     
     
     // VALIDATOR COUNT
     if ( isset($solana_nodes_onchain['validators']) ) {
     $solana_nodes_count_data_set .= '||' . sizeof($solana_nodes_onchain['validators']);
     }
     else {
     $solana_nodes_count_data_set .= '||NO_DATA';
     }


// SAVE CHART DATA 
$solana_nodes_count_chart_file = $ct['plug']->chart_cache('solana_nodes_count.dat');

$solana_nodes_count_data_set = $now . $solana_nodes_count_data_set;

// WITH newline (UNLOCKED file write)
$ct['cache']->save_file($solana_nodes_count_chart_file, $solana_nodes_count_data_set . "\n", "append", false);  


     ////////////////////////////////////////////
     
     
     // NODE GEOLOCATION
     if ( isset($solana_nodes_onchain['geolocation']) ) {
     
     // Save IPs to cache, in case we need to finish up later (because of API throttling)
     $solana_nodes_ip_data_set = json_encode($solana_nodes_onchain['geolocation'], JSON_PRETTY_PRINT);
     $ct['cache']->save_file( $ct['plug']->chart_cache('solana_nodes_ips.dat') , $solana_nodes_ip_data_set);
     
     // SAVE CHART DATA 
     $plug['class'][$this_plug]->solana_node_geolocation_cache($solana_nodes_onchain['geolocation']); 
     
     }
     
     
     ////////////////////////////////////////////
     
     
     // NODE VERSION, SAVE CHART DATA 
     if ( isset($solana_nodes_onchain['version']) ) {
     $solana_node_version_data_set = json_encode($solana_nodes_onchain['version'], JSON_PRETTY_PRINT);
     $ct['cache']->save_file( $ct['plug']->chart_cache('solana_nodes_version.dat') , $solana_node_version_data_set); 
     }
     
     
     ////////////////////////////////////////////
     
     
// Update the event tracking
$ct['cache']->save_file($solana_node_stats_tracking_file, $ct['gen']->time_date_format(false, 'pretty_date_time') );

}
// Otherwise, see if API throttling prevented FULL caching of the geolocation data set
// SAVE CHART DATA 
else {
$plug['class'][$this_plug]->solana_node_geolocation_cache();
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
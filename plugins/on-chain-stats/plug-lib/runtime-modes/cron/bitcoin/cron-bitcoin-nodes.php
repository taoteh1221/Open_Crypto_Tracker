<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
		

// DEBUGGING
//$debug_data = $this_plug . ' cron BITCOIN init successful';
//$debug_cache_file = $ct['plug']->debug_cache($this_plug . '_cron_bitcoin_init.dat', $this_plug);
//$ct['cache']->save_file($debug_cache_file, $debug_data);


// In case a rare error occurred from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
// (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
$now = time();

$bitcoin_tps_stats_tracking_file = $ct['plug']->event_cache('update_bitcoin_tps_stats.dat');
   
   
// TPS update every 15+ minutes, AND only if timestamp is NOT corrupt (indicating LIKELY system stability)
if ( $now > 0 && $ct['cache']->update_cache($bitcoin_tps_stats_tracking_file, 15) == true ) {

// Bitcoin get latest block hash (5 minute cache)
$bitcoin_last_block_hash = $ct['api']->blockchain_rpc('bitcoin', 'getbestblockhash', false, 5)['result'];

// Bitcoin get latest block stats (5 minute cache)
$bitcoin_last_block_stats = $ct['api']->blockchain_rpc('bitcoin', 'getblockstats', array($bitcoin_last_block_hash), 5)['result'];

//var_dump($bitcoin_last_block_stats);
    
    
     if ( isset($bitcoin_last_block_stats['txs']) && is_numeric($bitcoin_last_block_stats['txs']) ) {
     $bitcoin_tps = round( ($bitcoin_last_block_stats['txs'] / 600) , 2 );
     }


     // ALL TPS
     if ( isset($bitcoin_tps) && is_numeric($bitcoin_tps) ) {
     $bitcoin_tps_data_set .= '||' . $bitcoin_tps;
     }
     else {
     $bitcoin_tps_data_set .= '||NO_DATA';
     }


// SAVE CHART DATA 
$bitcoin_tps_chart_file = $ct['plug']->chart_cache('/bitcoin/archival/bitcoin_tps.dat');

$bitcoin_tps_data_set = $now . $bitcoin_tps_data_set;

// WITH newline (UNLOCKED file write)
$ct['cache']->save_file($bitcoin_tps_chart_file, $bitcoin_tps_data_set . "\n", "append", false);  
        
// Light charts (update time dynamically determined in $ct['cache']->update_light_chart() logic)
// Wait 0.05 seconds before updating light charts (which reads archival data)
usleep(50000); // Wait 0.05 seconds
        
        
     foreach ( $ct['light_chart_day_intervals'] as $light_chart_days ) {
           
	     // If we reset light charts, just skip the rest of this update session
	     // (we already delete light chart data in plug-init.php, IF a reset is flagged)
	     if ( $ct['light_chart_reset'] ) {
	     continue;
	     }
	           
     // Light charts, WITHOUT newline (var passing)
     $ct['cache']->update_light_chart($bitcoin_tps_chart_file, $bitcoin_tps_data_set, $light_chart_days); 
         
     }

     
// Update the event tracking
$ct['cache']->save_file($bitcoin_tps_stats_tracking_file, $ct['gen']->time_date_format(false, 'pretty_date_time') );

}
   


$bitcoin_node_stats_tracking_file = $ct['plug']->event_cache('update_bitcoin_node_stats.dat');

   
// Everything else only update every 24+ hours (1440 minutes), AND only if timestamp is NOT corrupt (indicating LIKELY system stability)
if ( $now > 0 && $ct['cache']->update_cache($bitcoin_node_stats_tracking_file, 1440) == true ) {

// Get on-chain Bitcoin nodes data
$bitcoin_nodes_onchain = $plug['class'][$this_plug]->bitcoin_nodes_onchain();


     // ALL NODES
     // (skipped if no results, so end-user can switch to a better default
     // Bitcoin RPC server, and not have to wait 24 hours for it to run again)
     if ( is_array($bitcoin_nodes_onchain['active_nodes']) ) {
     
     // Save IPs to cache, in case we need to finish up later (because of API throttling)
     $bitcoin_nodes_ip_data_set = json_encode($bitcoin_nodes_onchain['active_nodes'], JSON_PRETTY_PRINT);
     $ct['cache']->save_file( $ct['plug']->chart_cache('/bitcoin/overwrites/bitcoin_nodes_info.dat') , $bitcoin_nodes_ip_data_set);
     
     // SAVE CHART DATA 
     $plug['class'][$this_plug]->node_geolocation_cache('bitcoin', $bitcoin_nodes_onchain['active_nodes']); 
     
     $bitcoin_nodes_count_data_set .= '||' . sizeof($bitcoin_nodes_onchain['active_nodes']);
     
     // SAVE CHART DATA 
     $bitcoin_nodes_count_chart_file = $ct['plug']->chart_cache('/bitcoin/archival/bitcoin_nodes_count.dat');
     
     $bitcoin_nodes_count_data_set = $now . $bitcoin_nodes_count_data_set;
     
     // WITH newline (UNLOCKED file write)
     $ct['cache']->save_file($bitcoin_nodes_count_chart_file, $bitcoin_nodes_count_data_set . "\n", "append", false);  
             
     // Light charts (update time dynamically determined in $ct['cache']->update_light_chart() logic)
     // Wait 0.05 seconds before updating light charts (which reads archival data)
     usleep(50000); // Wait 0.05 seconds
             
             
          foreach ( $ct['light_chart_day_intervals'] as $light_chart_days ) {
                
     	     // If we reset light charts, just skip the rest of this update session
     	     // (we already delete light chart data in plug-init.php, IF a reset is flagged)
     	     if ( $ct['light_chart_reset'] ) {
     	     continue;
     	     }
     	           
          // Light charts, WITHOUT newline (var passing)
          $ct['cache']->update_light_chart($bitcoin_nodes_count_chart_file, $bitcoin_nodes_count_data_set, $light_chart_days); 
              
          }
          
          
     // Update the event tracking
     $ct['cache']->save_file($bitcoin_node_stats_tracking_file, $ct['gen']->time_date_format(false, 'pretty_date_time') );
     
     }
     
     
}
// Otherwise, see if API throttling prevented FULL caching of the geolocation data set
// SAVE CHART DATA 
else {
$plug['class'][$this_plug]->node_geolocation_cache('bitcoin');
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
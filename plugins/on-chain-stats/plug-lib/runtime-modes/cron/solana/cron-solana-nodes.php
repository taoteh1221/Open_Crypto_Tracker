<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
		

// DEBUGGING
$debug_data = $this_plug . ' cron SOLANA init successful';
$debug_cache_file = $ct['plug']->debug_cache($this_plug . '_cron_solana_init.dat', $this_plug);
$ct['cache']->save_file($debug_cache_file, $debug_data);


// In case a rare error occurred from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
// (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
$now = time();

$solana_tps_stats_tracking_file = $ct['plug']->event_cache('update_solana_tps_stats.dat');
   
   
// TPS update every 15+ minutes, AND only if timestamp is NOT corrupt (indicating LIKELY system stability)
if ( $now > 0 && $ct['cache']->update_cache($solana_tps_stats_tracking_file, 15) == true ) {

// Get on-chain Solana TPS
$solana_tps = $plug['class'][$this_plug]->solana_performance('tps');


     // ALL TPS
     if ( isset($solana_tps['all_tps']) && is_numeric($solana_tps['all_tps']) ) {
     $solana_tps_data_set .= '||' . $solana_tps['all_tps'];
     }
     else {
     $solana_tps_data_set .= '||NO_DATA';
     }


     // REAL TPS
     if ( isset($solana_tps['real_tps']) && is_numeric($solana_tps['real_tps']) ) {
     $solana_tps_data_set .= '||' . $solana_tps['real_tps'];
     }
     else {
     $solana_tps_data_set .= '||NO_DATA';
     }


     // VOTE TPS
     if ( isset($solana_tps['vote_tps']) && is_numeric($solana_tps['vote_tps']) ) {
     $solana_tps_data_set .= '||' . $solana_tps['vote_tps'];
     }
     else {
     $solana_tps_data_set .= '||NO_DATA';
     }


// SAVE CHART DATA 
$solana_tps_chart_file = $ct['plug']->chart_cache('/solana/archival/solana_tps.dat');

$solana_tps_data_set = $now . $solana_tps_data_set;

// WITH newline (UNLOCKED file write)
$ct['cache']->save_file($solana_tps_chart_file, $solana_tps_data_set . "\n", "append", false);  
        
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
     $ct['cache']->update_light_chart($solana_tps_chart_file, $solana_tps_data_set, $light_chart_days); 
         
     }

     
// Update the event tracking
$ct['cache']->save_file($solana_tps_stats_tracking_file, $ct['gen']->time_date_format(false, 'pretty_date_time') );

}
   

$solana_node_stats_tracking_file = $ct['plug']->event_cache('update_solana_node_stats.dat');

   
// Everything else only update every 12+ hours (720 minutes), AND only if timestamp is NOT corrupt (indicating LIKELY system stability)
if ( $now > 0 && $ct['cache']->update_cache($solana_node_stats_tracking_file, 720) == true ) {

// Get on-chain Solana nodes data
$solana_nodes_onchain = $plug['class'][$this_plug]->solana_nodes_onchain();


     // ALL NODE COUNT
     if ( is_array($solana_nodes_onchain['all_nodes']) ) {
     $solana_nodes_count_data_set .= '||' . sizeof($solana_nodes_onchain['all_nodes']);
     }
     else {
     $solana_nodes_count_data_set .= '||NO_DATA';
     }


     // RPC NODE COUNT
     if ( is_array($solana_nodes_onchain['all_nodes']) && is_array($solana_nodes_onchain['validators']) ) {
     $solana_nodes_count_data_set .= '||' . ( sizeof($solana_nodes_onchain['all_nodes']) - sizeof($solana_nodes_onchain['validators']) );
     }
     else {
     $solana_nodes_count_data_set .= '||NO_DATA';
     }
     
     
     // VALIDATOR COUNT
     if ( is_array($solana_nodes_onchain['validators']) ) {
          
          
          $validators_mapped_by_pubkeys = array();
          foreach ( $solana_nodes_onchain['validators'] as $validator ) {
          $validators_mapped_by_pubkeys[ md5($validator['nodePubkey']) ] = $validator;
          }

     
     // Save to cache, to flag validators in the interface
     $solana_validators_data_set = json_encode($validators_mapped_by_pubkeys, JSON_PRETTY_PRINT);
     $ct['cache']->save_file( $ct['plug']->chart_cache('/solana/overwrites/solana_validators_info.dat') , $solana_validators_data_set);
     
     $solana_nodes_count_data_set .= '||' . sizeof($solana_nodes_onchain['validators']);
     
     }
     else {
     $solana_nodes_count_data_set .= '||NO_DATA';
     }
     
     
     // RECENTLY OFFLINE VALIDATOR COUNT
     if ( is_array($solana_nodes_onchain['validators_without_epoch_votes']) ) {
          
          
          $validators_mapped_by_pubkeys = array();
          foreach ( $solana_nodes_onchain['validators_without_epoch_votes'] as $validator ) {
          $validators_mapped_by_pubkeys[ md5($validator['nodePubkey']) ] = $validator;
          }

     
     // Save to cache, to flag validators in the interface
     $solana_validators_without_epoch_votes_data_set = json_encode($validators_mapped_by_pubkeys, JSON_PRETTY_PRINT);
     $ct['cache']->save_file( $ct['plug']->chart_cache('/solana/overwrites/solana_validators_without_epoch_votes_info.dat') , $solana_validators_without_epoch_votes_data_set);
     
     $solana_nodes_count_data_set .= '||' . sizeof($solana_nodes_onchain['validators_without_epoch_votes']);
     
     }
     // Set to ZERO if no results found, as VERY OFTEN THERE ARE NOT ANY NON-VOTING VALIDATORS
     else {
     $solana_nodes_count_data_set .= '||0';
     }


// SAVE CHART DATA 
$solana_nodes_count_chart_file = $ct['plug']->chart_cache('/solana/archival/solana_nodes_count.dat');

$solana_nodes_count_data_set = $now . $solana_nodes_count_data_set;

// WITH newline (UNLOCKED file write)
$ct['cache']->save_file($solana_nodes_count_chart_file, $solana_nodes_count_data_set . "\n", "append", false);  
        
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
     $ct['cache']->update_light_chart($solana_nodes_count_chart_file, $solana_nodes_count_data_set, $light_chart_days); 
         
     }


     ////////////////////////////////////////////
     
     
     // NODE GEOLOCATION
     if ( isset($solana_nodes_onchain['geolocation']) ) {
     
     // Save IPs to cache, in case we need to finish up later (because of API throttling)
     $solana_nodes_ip_data_set = json_encode($solana_nodes_onchain['geolocation'], JSON_PRETTY_PRINT);
     $ct['cache']->save_file( $ct['plug']->chart_cache('/solana/overwrites/solana_nodes_info.dat') , $solana_nodes_ip_data_set);
     
     // SAVE CHART DATA 
     $plug['class'][$this_plug]->solana_node_geolocation_cache($solana_nodes_onchain['geolocation']); 
     
     }
     
     
     ////////////////////////////////////////////
     
     
     // NODE VERSION, SAVE CHART DATA 
     if ( isset($solana_nodes_onchain['version']) ) {
     $solana_node_version_data_set = json_encode($solana_nodes_onchain['version'], JSON_PRETTY_PRINT);
     $ct['cache']->save_file( $ct['plug']->chart_cache('/solana/overwrites/solana_nodes_version.dat') , $solana_node_version_data_set); 
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
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$solana_nodes_geolocation_file = $ct['plug']->chart_cache('solana_nodes_info_with_geolocation.dat', 'on-chain-stats');
$solana_nodes_geolocation = json_decode( trim( file_get_contents( $solana_nodes_geolocation_file ) ) , true);

gc_collect_cycles(); // Clean memory cache

$results = array();

foreach ( $solana_nodes_geolocation as $unused => &$node_data ) {

// RESETS
$is_validator = false;
$no_epoch_vote_validator = false;
unset($solana_validator_info);
     
     
     // IF active validator this epoch, mark as a validator
     if ( isset($node_data['solanaNodeInfo']['validator_data']) ) {
     $is_validator = true;
     $solana_validator_info = $node_data['solanaNodeInfo']['validator_data'];
     }
     elseif ( isset($node_data['solanaNodeInfo']['no_epoch_vote_validator_data']) ) {
     $is_validator = true;
     $no_epoch_vote_validator = true;
     $solana_validator_info = $node_data['solanaNodeInfo']['no_epoch_vote_validator_data'];
     }


      // Results filters
      if (
      $_GET['filter'] == 'all'
      || $_GET['filter'] == 'rpc' && !$is_validator
      || $_GET['filter'] == 'validators' && $is_validator
      || $_GET['filter'] == 'validators_without_epoch_votes' && $no_epoch_vote_validator
      ) {
      // All good, do nothing
      }
      // Skip this loop
      else {
      continue; 
      }
      
      
      // Address filter
      if (
      !isset($_GET['address'])
      || trim($_GET['address']) == ''
      || trim($_GET['address']) == $node_data['solanaNodeInfo']['pubkey']
      || isset($solana_validator_info['votePubkey']) && trim($_GET['address']) == $solana_validator_info['votePubkey']
      ) {
      // All good, do nothing
      }
      // Skip this loop
      else {
      continue; 
      }


$results[] = array(

                   'description' => '<div class="map_point_data"> <b>IP Address:</b> ' . $node_data['query'] . '</div>' .
                   
                   //'<div class="map_point_data"> <b>DEBUGGING:</b> ' . $solana_validator_info['votePubkey'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Location:</b> ' . $node_data['city'] . ', ' . $node_data['country'] . ( isset($node_data['zip']) && trim($node_data['zip']) != '' ? ' (' . trim($node_data['zip']) . ')' : '' ) . '</div>' .
                   
                   '<div class="map_point_data"> <b>Time Zone:</b> ' . $node_data['timezone'] . '</div>' .

                   '<div class="map_point_data"> <b>ISP:</b> ' . $node_data['isp'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Latitude:</b> ' . $node_data['lat'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Longitude:</b> ' . $node_data['lon'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Version:</b> v' . $node_data['solanaNodeInfo']['version'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Shred Version:</b> v' . $node_data['solanaNodeInfo']['shredVersion'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Feature Set:</b> ' . $node_data['solanaNodeInfo']['featureSet'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Gossip:</b> ' . $node_data['solanaNodeInfo']['gossip'] . '</div>' .
                   
                   '<div class="map_point_data" style="overflow-wrap: break-word !important;"> <b>Node Public Key:</b><br />' . $node_data['solanaNodeInfo']['pubkey'] . '</div>' .
                   
                   ( $is_validator ? '<div data_flag="is_validator" class="map_point_data bitcoin" style="overflow-wrap: break-word !important;"> <b>Validator Voting Public Key:</b><br />' . $solana_validator_info['votePubkey'] . '</div>' : '' ) .
                   
                   ( $is_validator ? '<div class="map_point_data bitcoin" style="overflow-wrap: break-word !important;"> <b>Validator Activated Stake:</b> ' . $ct['var']->num_pretty( ($solana_validator_info['activatedStake'] / 1000000000) , 0) . ' SOL</div>' : '' ) .
                   
                   ( $no_epoch_vote_validator ? '<div data_flag="no_epoch_vote" class="map_point_data red"> <b>Validator Alert:</b> No votes yet for current epoch!</div>' : '' ),
                   
                   'latitude' => $node_data['lat'],

                   'longitude' => $node_data['lon'],

                   );
  
}

echo json_encode($results, JSON_PRETTY_PRINT);

gc_collect_cycles(); // Clean memory cache

?>
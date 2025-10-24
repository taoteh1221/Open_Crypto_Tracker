<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

$solana_nodes_geolocation_file = $ct['plug']->chart_cache('solana_nodes_info_with_geolocation.dat', 'on-chain-stats');
$solana_nodes_geolocation = json_decode( trim( file_get_contents( $solana_nodes_geolocation_file ) ) , true);

$results = array();

foreach ( $solana_nodes_geolocation as $node_data ) {
     
     
     // IF active validator this epoch, mark as a validator
     if ( isset($node_data['solanaNodeInfo']['validator_data']) ) {
     $active_validator = true;
     }
     elseif ( isset($node_data['solanaNodeInfo']['recently_offline_validator_data']) ) {
     $recently_offline_validator = true;
     }
     else {
     $active_validator = false;
     $recently_offline_validator = false;
     }


      // Results filters
      if (
      $active_validator && $_GET['filter'] == 'rpc'
      || $recently_offline_validator && $_GET['filter'] == 'rpc'
      || !$active_validator && !$recently_offline_validator && $_GET['filter'] == 'validators'
      || !$recently_offline_validator && $_GET['filter'] == 'recently_offline_validators'
      ) {
      continue; // Skip this loop
      }


$results[] = array(

                   'description' => '<div class="map_point_data"> <b>IP Address:</b> ' . $node_data['query'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Location / Zip:</b> ' . $node_data['city'] . ', ' . $node_data['country'] .  ' (' . $node_data['zip'] . ')' . '</div>' .
                   
                   '<div class="map_point_data"> <b>Time Zone:</b> ' . $node_data['timezone'] . '</div>' .

                   '<div class="map_point_data"> <b>ISP:</b> ' . $node_data['isp'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Latitude:</b> ' . $node_data['lat'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Longitude:</b> ' . $node_data['lon'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Version:</b> v' . $node_data['solanaNodeInfo']['version'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Shred Version:</b> v' . $node_data['solanaNodeInfo']['shredVersion'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Feature Set:</b> ' . $node_data['solanaNodeInfo']['featureSet'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Gossip:</b> ' . $node_data['solanaNodeInfo']['gossip'] . '</div>' .
                   
                   '<div class="map_point_data" style="overflow-wrap: break-word !important;"> <b>Node Public Key:</b><br />' . $node_data['solanaNodeInfo']['pubkey'] . '</div>' .
                   
                   ( $active_validator || $recently_offline_validator ? '<div class="map_point_data bitcoin" style="overflow-wrap: break-word !important;"> <b>Validator Voting Public Key:</b><br />' . $node_data['solanaNodeInfo']['validator_data']['votePubkey'] . '</div>' : '' ) .
                   
                   ( $active_validator || $recently_offline_validator ? '<div class="map_point_data bitcoin" style="overflow-wrap: break-word !important;"> <b>Validator Activated Stake:</b> ' . $ct['var']->num_pretty( ($node_data['solanaNodeInfo']['validator_data']['activatedStake'] / 1000000000) , 0) . ' SOL</div>' : '' ) .
                   
                   ( $recently_offline_validator ? '<div class="map_point_data red"> <b>Validator Recently Offline:</b> No Votes Yet This Epoch!</div>' : '' ),
                   
                   'latitude' => $node_data['lat'],

                   'longitude' => $node_data['lon'],

                   );

}

echo json_encode($results, JSON_PRETTY_PRINT);

?>
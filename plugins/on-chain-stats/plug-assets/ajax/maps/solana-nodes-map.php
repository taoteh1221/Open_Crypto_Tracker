<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$solana_nodes_geolocation_file = $ct['plug']->chart_cache('solana_nodes_info_with_geolocation.dat', 'on-chain-stats');
$solana_nodes_geolocation = json_decode( trim( file_get_contents( $solana_nodes_geolocation_file ) ) , true);

$results = array();

foreach ( $solana_nodes_geolocation as $node_data ) {

$results[] = array(

                   'description' => '<div class="map_point_data"> <b>IP Address:</b> ' . $node_data['query'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Location / ZipCode:</b> ' . $node_data['city'] . ', ' . $node_data['country'] .  ' (' . $node_data['zip'] . ')' . '</div>' .
                   
                   '<div class="map_point_data"> <b>Time Zone:</b> ' . $node_data['timezone'] . '</div>' .

                   '<div class="map_point_data"> <b>ISP:</b> ' . $node_data['isp'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Latitude:</b> ' . $node_data['lat'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Longitude:</b> ' . $node_data['lon'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Public Key:</b> ' . $node_data['solanaNodeInfo']['pubkey'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Version:</b> v' . $node_data['solanaNodeInfo']['version'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Shred Version:</b> v' . $node_data['solanaNodeInfo']['shredVersion'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Feature Set:</b> ' . $node_data['solanaNodeInfo']['featureSet'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Node Gossip:</b> ' . $node_data['solanaNodeInfo']['gossip'] . '</div>',
                   
                   'latitude' => $node_data['lat'],

                   'longitude' => $node_data['lon'],

                   );

}

echo json_encode($results, JSON_PRETTY_PRINT);

?>
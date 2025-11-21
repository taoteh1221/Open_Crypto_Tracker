<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$bitcoin_nodes_geolocation_file = $ct['plug']->chart_cache('/bitcoin/overwrites/bitcoin_nodes_info_with_geolocation.dat', 'on-chain-stats');
$bitcoin_nodes_geolocation = json_decode( trim( file_get_contents( $bitcoin_nodes_geolocation_file ) ) , true);

gc_collect_cycles(); // Clean memory cache

$results = array();

foreach ( $bitcoin_nodes_geolocation as $unused => &$node_data ) {
      
      
      // Secondary filters
      if (
      isset($_GET['results_filter'])
      && trim($_GET['results_filter']) != ''
      ) {
      
      
          if ( $_GET['results_filter_type'] == 'country' ) {
          
              if ( preg_match("/" . preg_quote( trim($_GET['results_filter']) , '/') . "/i", $node_data['country']) ) {
              // Do nothing, all set
              }
              else {
              continue; // Skip this loop
              }
              
          }
          elseif ( $_GET['results_filter_type'] == 'city' ) {
          
              if ( preg_match("/" . preg_quote( trim($_GET['results_filter']) , '/') . "/i", $node_data['city']) ) {
              // Do nothing, all set
              }
              else {
              continue; // Skip this loop
              }
              
          }
          elseif ( $_GET['results_filter_type'] == 'time_zone' ) {
          
              if ( preg_match("/" . preg_quote( trim($_GET['results_filter']) , '/') . "/i", $node_data['timezone']) ) {
              // Do nothing, all set
              }
              else {
              continue; // Skip this loop
              }
              
          }
          elseif ( $_GET['results_filter_type'] == 'isp' ) {
          
              if ( preg_match("/" . preg_quote( trim($_GET['results_filter']) , '/') . "/i", $node_data['isp']) ) {
              // Do nothing, all set
              }
              else {
              continue; // Skip this loop
              }
              
          }
      
      
      }


      if ( $ct['gen']->test_ipv6($node_data['networkNodeInfo']['address']) ) {
      $p2p_address = '[' . $node_data['networkNodeInfo']['address'] . ']:' . $node_data['networkNodeInfo']['port'];
      }
      else {
      $p2p_address = $node_data['networkNodeInfo']['address'] . ':' . $node_data['networkNodeInfo']['port'];
      }


$results[] = array(

                   'description' => '<div class="map_point_data"> <b>IP Address:</b> ' . $node_data['query'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Location:</b> ' . $node_data['city'] . ', ' . $node_data['country'] . ( isset($node_data['zip']) && trim($node_data['zip']) != '' ? ' (' . trim($node_data['zip']) . ')' : '' ) . '</div>' .
                   
                   '<div class="map_point_data"> <b>Time Zone:</b> ' . $node_data['timezone'] . '</div>' .

                   '<div class="map_point_data"> <b>ISP:</b> ' . $node_data['isp'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Latitude:</b> ' . $node_data['lat'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>Longitude:</b> ' . $node_data['lon'] . '</div>' .
                   
                   '<div class="map_point_data"> <b>P2P Network Address:</b> ' . $p2p_address . '</div>' .
                   
                   '<div class="map_point_data"> <b>Last Seen Online:</b> ' . date("Y-m-d H:i:s", $node_data['networkNodeInfo']['time']) . ' (UTC)</div>',
                   
                   'latitude' => $node_data['lat'],

                   'longitude' => $node_data['lon'],

                   );
  
}

echo json_encode($results, JSON_PRETTY_PRINT);

gc_collect_cycles(); // Clean memory cache

?>
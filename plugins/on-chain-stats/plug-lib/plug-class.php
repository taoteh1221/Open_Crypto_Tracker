<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 
 
// CREATE THIS PLUGIN'S CLASS OBJECT DYNAMICALLY AS: $plug['class'][$this_plug]
$plug['class'][$this_plug] = new class() {
				
	
// Class variables / arrays

var $var1;
var $var2;
var $var3;
var $array1 = array();

	
	// Class functions
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
   
   
   
   function solana_node_version_chart() {
   
   global $ct, $this_plug;
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
     
     // Validating user input in the admin interface
	function admin_input_validation() {
		 
	global $ct, $plug, $this_plug;
     
     $update_config_error_seperator = '<br /> ';
		
     $node_count_chart_defaults = array_map('trim', explode('||', $_POST[$this_plug]['node_count_chart_defaults']) );
  
  
          // Make sure Node Count chart config is set
          if ( isset($_POST[$this_plug]['node_count_chart_defaults']) && trim($_POST[$this_plug]['node_count_chart_defaults']) == '' ) {
          $ct['update_config_error'] .= $update_config_error_seperator . '"Node Count Chart Defaults" MUST be filled in';
          }
          else if (
          !isset($node_count_chart_defaults[0]) || !$ct['var']->whole_int($node_count_chart_defaults[0]) || $node_count_chart_defaults[0] < 400 || $node_count_chart_defaults[0] > 900 
          || !isset($node_count_chart_defaults[1]) || !$ct['var']->whole_int($node_count_chart_defaults[1]) || $node_count_chart_defaults[1] < 7 || $node_count_chart_defaults[1] > 16
          || !$ct['var']->whole_int($node_count_chart_defaults[0] / 100)
          ) {
          $ct['update_config_error'] .= $update_config_error_seperator . '"Node Count Chart Defaults" FORMATTING incorrect (see corresponding setting\'s NOTES section)';
          }
		
		
     $tps_chart_defaults = array_map('trim', explode('||', $_POST[$this_plug]['tps_chart_defaults']) );
  
  
          // Make sure TPS Count chart config is set
          if ( isset($_POST[$this_plug]['tps_chart_defaults']) && trim($_POST[$this_plug]['tps_chart_defaults']) == '' ) {
          $ct['update_config_error'] .= $update_config_error_seperator . '"TPS Chart Defaults" MUST be filled in';
          }
          else if (
          !isset($tps_chart_defaults[0]) || !$ct['var']->whole_int($tps_chart_defaults[0]) || $tps_chart_defaults[0] < 400 || $tps_chart_defaults[0] > 900 
          || !isset($tps_chart_defaults[1]) || !$ct['var']->whole_int($tps_chart_defaults[1]) || $tps_chart_defaults[1] < 7 || $tps_chart_defaults[1] > 16
          || !$ct['var']->whole_int($tps_chart_defaults[0] / 100)
          ) {
          $ct['update_config_error'] .= $update_config_error_seperator . '"TPS Chart Defaults" FORMATTING incorrect (see corresponding setting\'s NOTES section)';
          }
		
		
     $selected_networks = array_map('trim', explode(',', $_POST[$this_plug]['selected_networks']) );
     
     $valid_networks = array(
                            'bitcoin',
                            'solana',
                            );
  
  
          // Make enabled networks are valid
          if (
          !isset($_POST[$this_plug]['selected_networks'])
          || trim($_POST[$this_plug]['selected_networks']) == '' ) {
          $ct['update_config_error'] .= $update_config_error_seperator . '"Selected Networks" MUST be filled in';
          }
          else {
          
               foreach ( $selected_networks as $enabled_network ) {
               
                    if ( !in_array($enabled_network, $valid_networks) ) {
                    $ct['update_config_error'] .= $update_config_error_seperator . 'The network "'.$enabled_network.'" is NOT a valid option';
                    }
               
               }
          
          }
		
     
     return $ct['update_config_error'];
		
	}
	
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
   
   
   
     function solana_performance($telemetry_for) {
     
     global $ct, $this_plug;
     
     $temp = array();     
     
     $results = array();     
     
     // 5 MINUTE CACHE, OF 3 SAMPLES
     $network_performance = $ct['api']->blockchain_rpc('solana', 'getRecentPerformanceSamples', array(3), 5); 
     
     // DEBUGGING
	//$debug_data = json_encode($network_performance, JSON_PRETTY_PRINT);
	//$debug_cache_file = $ct['plug']->debug_cache('network_performance_solana.dat', $this_plug);
	//$ct['cache']->save_file($debug_cache_file, $debug_data);
		   
          
          // IF no data, return false
          if (
          !is_array($network_performance['result'])
          || sizeof($network_performance['result']) < 1
          ) {
          return false;
          }
          // TPS data
          elseif ( $telemetry_for == 'tps' ) {
          
          
              $loop = 0;
              foreach ( $network_performance['result'] as $val ) {
                   
                   
                   if ( 
                   isset($val['numTransactions']) && is_numeric( trim($val['numTransactions']) )
                   && isset($val['samplePeriodSecs']) && is_numeric( trim($val['samplePeriodSecs']) )
                   && isset($val['numNonVoteTransactions']) && is_numeric( trim($val['numNonVoteTransactions']) )
                   ) {
     
                   $temp['numTransactions'] = ( isset($temp['numTransactions']) ? ($temp['numTransactions'] + $val['numTransactions']) : $val['numTransactions'] );
                   
                   $temp['samplePeriodSecs'] = ( isset($temp['samplePeriodSecs']) ? ($temp['samplePeriodSecs'] + $val['samplePeriodSecs']) : $val['samplePeriodSecs'] );
     
                   $temp['numNonVoteTransactions'] = ( isset($temp['numNonVoteTransactions']) ? ($temp['numNonVoteTransactions'] + $val['numNonVoteTransactions']) : $val['numNonVoteTransactions'] );
                   
                   $loop = $loop + 1;
                   
                   }
                   
              
              }
          

          // Averages
          $temp['numTransactions'] = $temp['numTransactions'] / $loop; 
          
          $temp['samplePeriodSecs'] = $temp['samplePeriodSecs'] / $loop; 

          $temp['numNonVoteTransactions'] = $temp['numNonVoteTransactions'] / $loop; 
          
          // Results     
          $results['all_tps'] = round( $ct['var']->num_to_str($temp['numTransactions'] / $temp['samplePeriodSecs']) );
               
          $results['real_tps'] = round( $ct['var']->num_to_str($temp['numNonVoteTransactions'] / $temp['samplePeriodSecs']) );
               
          $results['vote_tps'] = round( $ct['var']->num_to_str($results['all_tps'] - $results['real_tps']) );
               
          }
          // Slot time data
          elseif ( $telemetry_for == 'slot_time' ) {
              
              
              $loop = 0;
              foreach ( $network_performance['result'] as $val ) {

                   
                   if ( 
                   isset($val['samplePeriodSecs']) && is_numeric( trim($val['samplePeriodSecs']) )
                   && isset($val['numSlots']) && is_numeric( trim($val['numSlots']) )
                   ) {
                         
                   $temp['samplePeriodSecs'] = ( isset($temp['samplePeriodSecs']) ? ( $temp['samplePeriodSecs'] + trim($val['samplePeriodSecs']) ) : trim($val['samplePeriodSecs']) );
     
                   $temp['numSlots'] = ( isset($temp['numSlots']) ? ( $temp['numSlots'] + trim($val['numSlots']) ) : trim($val['numSlots']) );
                   
                   $loop = $loop + 1;
                   
                   }

              
              }

              
          // Averages
          $temp['samplePeriodSecs'] = $temp['samplePeriodSecs'] / $loop; 
          
          $temp['numSlots'] = $temp['numSlots'] / $loop; 
          
          // Results     
          $results['slot_time_seconds'] = $ct['var']->num_to_str($temp['samplePeriodSecs'] / $temp['numSlots']);
               
          }
     
     
     gc_collect_cycles(); // Clean memory cache
     
     return $results;
   
     }
	
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
   
   
   function node_geolocation_cleanup($network_name) {
   
   global $ct, $this_plug;
   
   $results = array();
   
   $validators_info_file = $ct['plug']->chart_cache('/'.$network_name.'/overwrites/'.$network_name.'_validators_info.dat', $this_plug);
   
   
      if ( file_exists($validators_info_file) ) {
      $validators_info = json_decode( trim( file_get_contents( $validators_info_file ) ) , true);
      }
   
   
   $validators_without_epoch_votes_info_file = $ct['plug']->chart_cache('/'.$network_name.'/overwrites/'.$network_name.'_validators_without_epoch_votes_info.dat', $this_plug);
   
   
      if ( file_exists($validators_without_epoch_votes_info_file) ) {
      $validators_without_epoch_votes_info = json_decode( trim( file_get_contents( $validators_without_epoch_votes_info_file ) ) , true);
      }
      
   
   $nodes_info_file = $ct['plug']->chart_cache('/'.$network_name.'/overwrites/'.$network_name.'_nodes_info.dat', $this_plug);
   $nodes_info = json_decode( trim( file_get_contents( $nodes_info_file ) ) , true);
   
   // Geolocation TEMPORARY cache files
   $files = $ct['gen']->sort_files( $ct['plug']->chart_cache($network_name.'/temp/'.$network_name.'_nodes_geolocation', $this_plug) , 'dat', 'asc');
        
       
      // Combine batched files
      foreach( $files as $geolocation_file ) {
        
        	if ( preg_match("/_locations_processed/i", $geolocation_file) ) {
        	
          $temp_array = json_decode( trim( file_get_contents($ct['plug']->chart_cache(''.$network_name.'/temp/'.$network_name.'_nodes_geolocation', $this_plug) . '/' . $geolocation_file) ) , true);
          
               if ( is_array($temp_array) ) {
               $results = array_merge($results, $temp_array);
               }
          
        	}
        	
      }
      
      
      // Remove unneeded data, and merge in network data
      foreach ( $results as $key => $unused ) {
           
      unset($results[$key]['status']);
      unset($results[$key]['countryCode']);
      unset($results[$key]['region']);
      unset($results[$key]['regionName']);
      unset($results[$key]['org']);
      unset($results[$key]['as']);
      
      $results[$key]['networkNodeInfo'] = $nodes_info[ $results[$key]['query'] ];
      
      
          if ( isset($validators_info[ md5($results[$key]['networkNodeInfo']['pubkey']) ]) ) {
          $results[$key]['networkNodeInfo']['validator_data'] = $validators_info[ md5($results[$key]['networkNodeInfo']['pubkey']) ];
          }
          
          
          // We strip non-voting validators out of $validators_info, so this cannot be an elseif
          if ( isset($validators_without_epoch_votes_info[ md5($results[$key]['networkNodeInfo']['pubkey']) ]) ) {
          $results[$key]['networkNodeInfo']['no_epoch_vote_validator_data'] = $validators_without_epoch_votes_info[ md5($results[$key]['networkNodeInfo']['pubkey']) ];
          }


      }

   
   // Save node data, with geolocation included
   $results_data_set = json_encode($results, JSON_PRETTY_PRINT);
   $ct['cache']->save_file( $ct['plug']->chart_cache('/'.$network_name.'/overwrites/'.$network_name.'_nodes_info_with_geolocation.dat', $this_plug) , $results_data_set);
   
   // Delete temp batch files
   $ct['cache']->remove_dir( $ct['plug']->chart_cache(''.$network_name.'/temp/'.$network_name.'_nodes_geolocation', $this_plug) );
   
   // Update the event tracking
   $ct['cache']->save_file( $ct['plug']->event_cache(''.$network_name.'_node_geolocation_cleanup.dat', $this_plug) , $ct['gen']->time_date_format(false, 'pretty_date_time') );    
   
   gc_collect_cycles(); // Clean memory cache
     
   
   }
		
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
     function solana_nodes_onchain() {
     
     global $ct, $this_plug;
     
     $results = array();     
     
     $validators = $ct['api']->blockchain_rpc('solana', 'getVoteAccounts', array(), 480); // 8 HOUR (480 MINUTE) CACHE
     
     $all_nodes = $ct['api']->blockchain_rpc('solana', 'getClusterNodes', array(), 480); // 8 HOUR (480 MINUTE) CACHE
     
     // Target results array paths
     
     $validators = $validators['result']['current'];
     
     $all_nodes = $all_nodes['result'];
	
	
	     if ( is_array($validators) && sizeof($validators) > 0 ) {
	          
	     $results['validators'] = $validators;


               foreach( $results['validators'] as $validator_key => $validator_val ) {
               
               
                    // If NOT an active validator this epoch, remove as a validator,
                    // after adding to recent offline validators
                    if (
                    !isset($validator_val['epochVoteAccount'])
                    || $validator_val['epochVoteAccount'] == false
                    ) {
                         
                    $results['validators_without_epoch_votes'][] = $results['validators'][$validator_key];

                    unset($results['validators'][$validator_key]);

                    }

               
               }


	     }
	
	
	     if ( is_array($all_nodes) && sizeof($all_nodes) > 0 ) {
	          
	     $results['all_nodes'] = $all_nodes;
          
          
               foreach ( $all_nodes as $node ) {
                    
               $parse_ip = explode(":", $node['gossip']);
                    
                    
                    // Don't count any duplicate ip addresses as another node
                    if (
                    !is_array($results['geolocation'])
                    || !array_key_exists($parse_ip[0], $results['geolocation'])
                    ) {
                    
                    // Map by IP, for easier geolocation processing later
                    $results['geolocation'][ $parse_ip[0] ] = $node;
               
               
                         if ( isset($results['version'][ md5($node['version']) ]['count']) ) {
                         $results['version'][ md5($node['version']) ]['count'] = $results['version'][ md5($node['version']) ]['count'] + 1;
                         }
                         else {
                         $results['version'][ md5($node['version']) ]['version'] = $node['version'];
                         $results['version'][ md5($node['version']) ]['count'] = 1;
                         }


                    }


               }


	     }
     
  
     gc_collect_cycles(); // Clean memory cache
     
     return $results;
    
     }
		
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
     function bitcoin_nodes_onchain() {
     
     global $ct, $this_plug;
     
     $results = array();  
     
     $now = time();  


          // Make sure we're not having an edge-case low-voltage issue
          // on low power devices, and assure we have a current timestamp     
          if ( $now > 0 ) {
          // All set, do nothing
          }
          // Skip
          else {
          return false;
          }
     
     
     // 0 param asks for ALL known, 8 HOUR (480 MINUTE) CACHE
     // https://developer.bitcoin.org/reference/rpc/getnodeaddresses.html
     $all_nodes = $ct['api']->blockchain_rpc('bitcoin', 'getnodeaddresses', array(0), 480); 
     
     // Target results array paths
     
     $all_nodes = $all_nodes['result'];
	
	
	     if ( is_array($all_nodes) && sizeof($all_nodes) > 0 ) {
          
               foreach ( $all_nodes as $node ) {
                    
                    // IF see in the past 24 hours, included it
                    if ( $ct['var']->num_to_str($node['time'] + 86400) >= $now ) {
                    
                         // Don't count any duplicate ip addresses as another node
                         if (
                         !is_array($results['geolocation'])
                         || !array_key_exists($node['address'], $results['geolocation'])
                         ) {

                         // Register as an active node                         
                         $results['active_nodes'][ $node['address'] ] = $node;
     
                         }
                         
                    }

               }

	     }
     
  
     gc_collect_cycles(); // Clean memory cache
     
     return $results;
    
     }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function tps_chart($file, $node_type, $start_timestamp=0) {
   
   global $ct;
   
   $data = array();
   
   
   $fn = fopen($file,"r");
     
     while( !feof($fn) )  {
      
     $result = explode("||", fgets($fn) );
     
     
         if ( $node_type == 'all_tps' ) {
         $tps_data = $result[1];
         }
         elseif ( $node_type == 'real_tps' ) {
         $tps_data = $result[2];
         }
         elseif ( $node_type == 'vote_tps' ) {
         $tps_data = $result[3];
         }
      
      
         // If the data set on this line is NOT valid, skip it
         if ( !isset($result[0]) || !isset($tps_data) ) {
         continue;
         }
      
      
     $result = array_map('trim', $result); // Trim whitespace out of all array values
      
      
         if ( trim($result[0]) != '' && trim($result[0]) >= $start_timestamp ) {
            
         $data['time'] .= trim($result[0]) . '000,';  // Zingchart wants 3 more zeros with unix time (milliseconds)
         
         
            if (
            trim($tps_data) != 'NO_DATA'
            && trim($tps_data) != ''
            && is_numeric($tps_data)
            ) {
            // Zingchart wants 3 more zeros with unix time (milliseconds)
            $data['tps'] .= '[' . trim($result[0]) . '000' . ', ' . trim($tps_data) . '],';  
            }
         
         
         }
      
     }
   
   fclose($fn);
   
   // Trim away extra commas
   $data['time'] = rtrim($data['time'],',');
   $data['tps'] = rtrim($data['tps'],',');
  
   gc_collect_cycles(); // Clean memory cache
   
   return $data;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function node_count_chart($file, $node_type, $start_timestamp=0) {
   
   global $ct;
   
   $data = array();
   
   
   $fn = fopen($file,"r");
     
     while( !feof($fn) )  {
      
     $result = explode("||", fgets($fn) );
     
     
         if ( $node_type == 'all_nodes' ) {
         $node_data = $result[1];
         }
         elseif ( $node_type == 'rpcs' ) {
         $node_data = $result[2];
         }
         elseif ( $node_type == 'validators' ) {
         $node_data = $result[3];
         }
         elseif ( $node_type == 'validators_without_epoch_votes' ) {
         $node_data = $result[4];
         }
      
      
         // If the data set on this line is NOT valid, skip it
         if ( !isset($result[0]) || !isset($node_data) ) {
         continue;
         }
      
      
     $result = array_map('trim', $result); // Trim whitespace out of all array values
      
      
         if ( trim($result[0]) != '' && trim($result[0]) >= $start_timestamp ) {
            
         $data['time'] .= trim($result[0]) . '000,';  // Zingchart wants 3 more zeros with unix time (milliseconds)
         
         
            if (
            trim($node_data) != 'NO_DATA'
            && trim($node_data) != ''
            && is_numeric($node_data)
            ) {
            // Zingchart wants 3 more zeros with unix time (milliseconds)
            $data['count'] .= '[' . trim($result[0]) . '000' . ', ' . trim($node_data) . '],';  
            }
         
         
         }
      
     }
   
   fclose($fn);
   
   // Trim away extra commas
   $data['time'] = rtrim($data['time'],',');
   $data['count'] = rtrim($data['count'],',');
  
   gc_collect_cycles(); // Clean memory cache
   
   return $data;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function node_geolocation_cache($network_name, $nodes_info=false) {
   
   global $ct, $this_plug;
   
   $results = array();
   
   $params = array();

   
       // Make sure we haven't run the geolocation cleanup routine in a day + 1 hour (1500 minutes)
       if ( $ct['cache']->update_cache( $ct['plug']->event_cache($network_name . '_node_geolocation_cleanup.dat', $this_plug) , 1500) == false ) {
       return false;
       }
   
       
       // IF API throttling prevented FULL caching of the geolocation data set,
       // we use the CACHED ip address data set
       if ( !is_array($nodes_info) ) {
       $data_file = $ct['plug']->chart_cache('/'.$network_name.'/overwrites/'.$network_name.'_nodes_info.dat', $this_plug);
       $nodes_info = json_decode( trim( file_get_contents( $data_file ) ) , true);
       }
               
       
       // Geolocation cache files
       // (MUST be batched, to build over MULTIPLE runtimes [to avoid ip-api.com API limits])
       $batch_count = 0;
       $processed = 0;
       foreach( $nodes_info as $ip => $unused ) {
            
       $params[] = $ip;

       $batch_count = $batch_count + 1;
       
           
           // IF we are ready to batch to cache file
           if (
           $batch_count == 100 // 100 max per batch limit for ip-api.com
           || ($processed + $batch_count) >= sizeof($nodes_info) // IF last few are under 100 total
           ) {
           
           $processed = $processed + $batch_count;
           
           $cache_path = $network_name . '/temp/'.$network_name.'_nodes_geolocation/' . $processed . '_locations_processed.dat';
           
               
               // IF it's been at least 12 hours (720 minutes), then we update the geolocation cache file(s)
               if ( $ct['cache']->update_cache( $ct['plug']->chart_cache($cache_path, $this_plug) , 720) == true ) {
               
               // Cache results for 7 days (10080 minutes, IF ip addresses are EXACTLY the same as prev. request)
               $response = @$ct['cache']->ext_data('params', $params, 10080, 'http://ip-api.com/batch');
           
               $data = json_decode($response, true);
                
                    
                    // IF we have data
                    if ( is_array($data) && sizeof($data) > 0 ) {
               
                    
                         foreach( $data as $geolocation ) {
                         $results[] = $geolocation;
                         }
               
               
                    $results_json = json_encode($results, JSON_PRETTY_PRINT);
                    
                    $ct['cache']->save_file( $ct['plug']->chart_cache($cache_path, $this_plug) , $results_json);
                    
                         
                         // IF last few have been processed, flag merging / cleanup
                         if ( ($processed + $batch_count) >= sizeof($nodes_info) ) {
                         $run_node_geolocation_cleanup = true;
                         }

                    
                    }
                    
                
               }
           
           
           // LOOP RESETS
           $batch_count = 0;
           $results = array();
           $params = array();
           
           }

       
       }


   gc_collect_cycles(); // Clean memory cache
     
                         
       // IF we are done getting all geolocation data,
       // run processing batched files / cleanup
       if ( $run_node_geolocation_cleanup ) {
       $this->node_geolocation_cleanup($network_name);
       }

   
   }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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
   
   
   function solana_node_geolocation_map() {
   
   global $ct, $this_plug;
   
   // Geolocation cache files
   $files = $ct['gen']->sort_files( $ct['plug']->chart_cache('solana_nodes_geolocation', $this_plug) , 'dat', 'desc');
        
      foreach( $files as $geolocation_file ) {
        
        	if ( preg_match("/_locations_processed/i", $geolocation_file) ) {
        	// LOGIC HERE
        	}
        	
      }
   
   }
		
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
     
     // Validating user input in the admin interface
	function admin_input_validation() {
		 
	global $ct, $plug, $this_plug;
     
     $update_config_error_seperator = '<br /> ';
		
     $solana_node_count_chart_defaults = array_map('trim', explode('||', $_POST[$this_plug]['solana_node_count_chart_defaults']) );
  
  
          // Make sure Solana Node Count chart config is set
          if ( isset($_POST[$this_plug]['solana_node_count_chart_defaults']) && trim($_POST[$this_plug]['solana_node_count_chart_defaults']) == '' ) {
          $ct['update_config_error'] .= $update_config_error_seperator . '"Solana Node Count Chart Defaults" MUST be filled in';
          }
          else if (
          !isset($solana_node_count_chart_defaults[0]) || !$ct['var']->whole_int($solana_node_count_chart_defaults[0]) || $solana_node_count_chart_defaults[0] < 400 || $solana_node_count_chart_defaults[0] > 900 
          || !isset($solana_node_count_chart_defaults[1]) || !$ct['var']->whole_int($solana_node_count_chart_defaults[1]) || $solana_node_count_chart_defaults[1] < 7 || $solana_node_count_chart_defaults[1] > 16
          || !$ct['var']->whole_int($solana_node_count_chart_defaults[0] / 100)
          ) {
          $ct['update_config_error'] .= $update_config_error_seperator . '"Solana Node Count Chart Defaults" FORMATTING incorrect (see corresponding setting\'s NOTES section)';
          }
		
     
     return $ct['update_config_error'];
		
	}
	
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
   
   
     function solana_nodes_onchain() {
     
     global $ct, $this_plug;
     
     $results = array();     
     
     $validators = $ct['api']->solana_rpc('getVoteAccounts', array(), 720); // 12 HOUR (720 MINUTE) CACHE
     
     $all_nodes = $ct['api']->solana_rpc('getClusterNodes', array(), 720); // 12 HOUR (720 MINUTE) CACHE
     
     // Target results array paths
     
     $validators = $validators['result']['current'];
     
     $all_nodes = $all_nodes['result'];
	
	
	     if ( is_array($validators) && sizeof($validators) > 0 ) {
	     $results['validators'] = $validators;
	     }
	
	
	     if ( is_array($all_nodes) && sizeof($all_nodes) > 0 ) {
	          
	     $results['all_nodes'] = $all_nodes;
          
          
               foreach ( $all_nodes as $node ) {
                    
               $parse_ip = explode(":", $node['gossip']);
                    
                    
                    // Don't count any duplicate ip addresses as another node
                    if (
                    !is_array($results['geolocation'])
                    || !in_array($parse_ip[0], $results['geolocation'])
                    ) {
                         
                    $results['geolocation'][] = $parse_ip[0];
               
               
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
   
   
   function solana_node_count_chart($file, $node_type, $start_timestamp=0) {
   
   global $ct;
   
   $data = array();
   
   
   $fn = fopen($file,"r");
     
     while( !feof($fn) )  {
      
     $result = explode("||", fgets($fn) );
     
     
         if ( $node_type == 'all_nodes' ) {
         $node_data = $result[1];
         }
         elseif ( $node_type == 'validators' ) {
         $node_data = $result[2];
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
   
   
   function solana_node_geolocation_cache($solana_nodes_ips=false) {
   
   global $ct, $this_plug;
   
   $results = array();
   
   $params = array();

   
       // Make sure we setup our subdirectory 'geolocation'
       if ( !$ct['gen']->dir_struct( $ct['plug']->chart_cache('solana_nodes_geolocation', $this_plug) ) ) {
       return false;
       }
   
       
       // IF API throttling prevented FULL caching of the geolocation data set,
       // we use the CACHED ip address data set
       if ( !is_array($solana_nodes_ips) ) {
       $data_file = $ct['plug']->chart_cache('solana_nodes_ips.dat', $this_plug);
       $solana_nodes_ips = json_decode( trim( file_get_contents( $data_file ) ) , true);
       }
               
       
       // Geolocation cache files
       // (MUST be batched, to build over MULTIPLE runtimes [to avoid ip-api.com API limits])
       $batch_count = 0;
       $processed = 0;
       foreach( $solana_nodes_ips as $ip ) {
            
       $params[] = $ip;

       $batch_count = $batch_count + 1;
       
           
           // IF we are ready to batch to cache file
           if (
           $batch_count == 100
           || ($processed + $batch_count) >= sizeof($solana_nodes_ips)
           ) {
           
           $processed = $processed + $batch_count;
           
           $cache_path = 'solana_nodes_geolocation/' . $processed . '_locations_processed.dat';
           
               
               // IF it's been at least 12 hours (720 minutes), then we update the geolocation cache file(s)
               if ( $ct['cache']->update_cache( $ct['plug']->chart_cache($cache_path, $this_plug) , 720) == true ) {
               
               // Cache results for 30 days (43200 minutes, IF ip addresses are EXACTLY the same as prev. request)
               $response = @$ct['cache']->ext_data('params', $params, 43200, 'http://ip-api.com/batch');
           
               $data = json_decode($response, true);
                
                    
                    // IF we have data
                    if ( is_array($data) && sizeof($data) > 0 ) {
               
                    
                         foreach( $data as $geolocation ) {
                         $results[] = $geolocation;
                         }
               
                    $results_json = json_encode($results, JSON_PRETTY_PRINT);
                    
                    $ct['cache']->save_file( $ct['plug']->chart_cache($cache_path, $this_plug) , $results_json);
                    
                    }
                    
                
               }
           
           
           // LOOP RESETS
           $batch_count = 0;
           $results = array();
           $params = array();
           
           gc_collect_cycles(); // Clean memory cache                  
           
           }

       
       }

   
   }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
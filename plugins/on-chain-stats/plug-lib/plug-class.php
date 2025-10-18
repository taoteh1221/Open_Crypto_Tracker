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
   
   
     function solana_nodes_data($params=false) {
     
     global $ct, $this_plug;
     
     $results = array();     
     
     $validators = $ct['api']->solana_rpc('getVoteAccounts', array(), 0);
     
     $all_nodes = $ct['api']->solana_rpc('getClusterNodes', array(), 0);
     
     
     // DEBUGGING
             
     //$solana_validators_debug_file = $ct['plug']->debug_cache('solana_validators_debug.dat', $this_plug);
     
     //$solana_all_nodes_debug_file = $ct['plug']->debug_cache('solana_all_nodes_debug.dat', $this_plug);
     
     //$validators_debugging = json_encode($validators, JSON_PRETTY_PRINT);
     
     //$all_nodes_debugging = json_encode($all_nodes, JSON_PRETTY_PRINT);
     
     //$ct['cache']->save_file($solana_validators_debug_file, $validators_debugging);
     
     //$ct['cache']->save_file($solana_all_nodes_debug_file, $all_nodes_debugging);
     
     
     // Simplify results array paths (AFTER THE DEBUGGING LOGIC ABOVE [IN CASE ACTIVATED TO DEBUG])
     
     $validators = $validators['result']['current'];
     
     $all_nodes = $all_nodes['result'];
	
	
	     if ( is_array($validators) && sizeof($validators) > 0 ) {
	     $results['validators'] = $validators;
	     }
	
	
	     if ( is_array($all_nodes) && sizeof($all_nodes) > 0 ) {
	          
	     $results['all_nodes'] = $all_nodes;
          
          
               foreach ( $all_nodes as $node ) {
                    
               $results['geolocation'][] = $node['gossip'];
               
               
                    if ( isset($results['version'][ md5($node['version']) ]['count']) ) {
                    $results['version'][ md5($node['version']) ]['count'] = $results['version'][ md5($node['version']) ]['count'] + 1;
                    }
                    else {
                    $results['version'][ md5($node['version']) ]['version'] = $node['version'];
                    $results['version'][ md5($node['version']) ]['count'] = 1;
                    }


               }


	     }
     
     
     return $results;
    
     }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function solana_node_chart_data($file, $node_type, $start_timestamp=0) {
   
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
   
   gc_collect_cycles(); // Clean memory cache
   
   // Trim away extra commas
   $data['time'] = rtrim($data['time'],',');
   $data['count'] = rtrim($data['count'],',');
   
   return $data;
   
   }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
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
		
     // Logic here
     $ct['update_config_error'] = '';
     
     return $ct['update_config_error'];
		
	}
		
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
   
	
     function excluded_backup_dirs() {
     
     global $ct;
     
     $results = 'system,light,'; // Don't include system and any light charts as well
     						
          foreach( $ct['conf']['assets'] as $key => $unused ) {
          
               if ( !isset($ct['default_conf']['assets'][$key]) ) {
               $results .= $key . ',';
               }
          
          }
          
     return substr($results, 0, -1);
    
     }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
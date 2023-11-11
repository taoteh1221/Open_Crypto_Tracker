<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 
 
// CREATE THIS PLUGIN'S CLASS OBJECT DYNAMICALLY AS: $plug_class[$this_plug]
$plug_class[$this_plug] = new class() {
				
	
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
		 
	global $ct, $this_plug, $plug_conf;
		
          
         // Make sure do not disturb on/off is set properly (IF filled in, CAN BE BLANK TO DISABLE)
         
         if (
         isset($_POST['recurring-reminder']['do_not_disturb']['on'])
         && $_POST['recurring-reminder']['do_not_disturb']['on'] != ''
         && !preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9])$/', $_POST['recurring-reminder']['do_not_disturb']['on'])
         ) {
         $update_config_error .= '"Do Not Disturb => On" value MUST be between 00:00 and 23:59 (ALWAYS TWO DIGIT HOURS AND MINUTES)';
         $error_seperator = '; ';
         }
         
         
         if (
         isset($_POST['recurring-reminder']['do_not_disturb']['off'])
         && $_POST['recurring-reminder']['do_not_disturb']['off'] != ''
         && !preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9])$/', $_POST['recurring-reminder']['do_not_disturb']['off'])
         ) {
         $update_config_error .= $error_seperator . '"Do Not Disturb => Off" value MUST be between 00:00 and 23:59 (ALWAYS TWO DIGIT HOURS AND MINUTES)';
         }
     
     
     return $update_config_error;
		
	}
		
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
   
	
   function valid_time_format($value) {

   $check_1 = '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/i';
    
      if ( preg_match($check_1, $value) ) {
      return true;
      }
      else {
      return false;
      }
    
   }
		
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
		
		
   function time_dec_hours($var, $mode) {
   	
   global $ct;
   
   
   	if ( $mode == 'to' ) {
   	
   	$hours_minutes = explode(':', $var);
    
    // PHP8 is strict with math here...convert str to int
   	$hours = intval($hours_minutes[0]);
   
   	$minutes = intval($hours_minutes[1]);
   
  	return $ct['var']->num_to_str( $hours + round( ($minutes / 60) , 2 ) );
   	
   	}
   	else if ( $mode == 'from' ) {
   
   	$var = abs($var);
   	
   	$dec = explode('.', $ct['var']->num_to_str($var) );
   	
    // PHP8 is strict with math here...convert str to int
   	$dec[0] = intval($dec[0]);
   	
   	$dec[1] = intval($dec[1]);
   
   	$hours = intval( strlen($dec[0]) < 2 ? '0' . $dec[0] : $dec[0] );
   
   	$minutes = round( ('0.' . $dec[1]) * 60);
   	
   	$minutes = intval( strlen($minutes) < 2 ? '0' . $minutes : $minutes );
   
  	return $hours . ':' . $minutes;
   	
   	}
   	
   
   }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
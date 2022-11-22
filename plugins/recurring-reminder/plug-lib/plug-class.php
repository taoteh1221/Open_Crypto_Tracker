<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
   	
   global $ct_var;
   
   
   	if ( $mode == 'to' ) {
   	
   	$hours_minutes = explode(':', $var);
    
    // PHP8 is strict with math here...convert str to int
   	$hours = intval($hours_minutes[0]);
   
   	$minutes = intval($hours_minutes[1]);
   
  	return $ct_var->num_to_str( $hours + round( ($minutes / 60) , 2 ) );
   	
   	}
   	else if ( $mode == 'from' ) {
   
   	$var = abs($var);
   	
   	$dec = explode('.', $ct_var->num_to_str($var) );
   	
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
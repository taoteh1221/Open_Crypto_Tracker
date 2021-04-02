<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */
 
 
// CREATE THIS PLUGIN'S CLASS OBJECT DYNAMICALLY AS: $plug_class[$this_plug]
$plug_class[$this_plug] = new class() {
				
	
// Class variables / arrays

var $var1;
var $var2;
var $var3;
var $array1 = array();

	
	// Class functions
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function time_dec_hours($var, $mode) {
   	
   global $ocpt_var;
   
   
   	if ( $mode == 'to' ) {
   	
   	$hours_minutes = explode(':', $var);
   
   	$hours = $hours_minutes[0];
   
   	$minutes = $hours_minutes[1];
   
  		return $ocpt_var->num_to_str( $hours + round( ($minutes / 60) , 2 ) );
   	
   	}
   	else if ( $mode == 'from' ) {
   
   	$var = abs($var);
   	
   	$dec = explode('.', $ocpt_var->num_to_str($var) );
   
   	$hours = ( strlen($dec[0]) < 2 ? '0' . $dec[0] : $dec[0] );
   
   	$minutes = round( ('0.' . $dec[1]) * 60);
   	
   	$minutes = ( strlen($minutes) < 2 ? '0' . $minutes : $minutes );
   
  		return $hours . ':' . $minutes;
   	
   	}
   	
   
   }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
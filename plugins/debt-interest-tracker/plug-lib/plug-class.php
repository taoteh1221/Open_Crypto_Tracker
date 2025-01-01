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
		
     // Logic here
     $ct['update_config_error'] = '';
     
     return $ct['update_config_error'];
		
	}
		
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
   
	
   function apr_calc($account, $amount, $apr) {
       
   global $ct;
   
   $result = array();
   
       if ( is_numeric($amount) && is_numeric($apr) ) {
                                
       $apr = round( ($apr / 100) , 2); // Change to decimal (25.5 to 0.255)
                                
       $result['yearly_interest'] = round( ($amount * $apr) , 2);
                                
       $result['monthly_interest'] = round( ($result['yearly_interest'] / 12) , 2);
                                
       $result['summary'] = '<fieldset class="debt_results"><legend>' . $account . '</legend>Monthly Interest: ' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . number_format($result['monthly_interest'], 2, '.', ',') . '<br />Yearly Interest: ' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . number_format($result['yearly_interest'], 2, '.', ',') . '</fieldset>';
       
       return $result;
        
       }
       else {
       return false;
       }
    
   }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
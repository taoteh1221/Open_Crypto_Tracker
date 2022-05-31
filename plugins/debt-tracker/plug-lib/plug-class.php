<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
   
	
   function apr_calc($account, $amount, $apr) {
       
   global $ct_conf;
   
   $result = array();
                                
   $apr = round( ($apr / 100) , 2); // Change to decimal (25.5 to 0.255)
                            
   $result['yearly_interest'] = round( ($amount * $apr) , 2);
                            
   $result['monthly_interest'] = round( ($result['yearly_interest'] / 12) , 2);
                            
   $result['summary'] = '<fieldset class="debt_results"><legend>' . $account . '</legend>Monthly Interest: ' . $ct_conf['power']['btc_currency_mrkts'][ $ct_conf['gen']['btc_prim_currency_pair'] ] . number_format($result['monthly_interest'], 2, '.', ',') . '<br />Yearly Interest: ' . $ct_conf['power']['btc_currency_mrkts'][ $ct_conf['gen']['btc_prim_currency_pair'] ] . number_format($result['yearly_interest'], 2, '.', ',') . '</fieldset>';
   
   return $result;
    
   }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
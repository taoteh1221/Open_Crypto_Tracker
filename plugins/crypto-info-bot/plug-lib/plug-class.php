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
   
	
     function discord_data($params) {
     
     global $ct;
     						
     $test_data = @$ct['cache']->ext_data('params', $params, 0, $ct['base_url'] . $ct['int_api_base_endpoint'] . 'market_conversion/eur/kraken-btc-usd,coinbase-dai-usd,coinbase-eth-usd', 2);
     
     // Already json-encoded
     return $test_data;
    
     }
	
		
   ////////////////////////////////////////////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////////////////////////
   
   
     function telegram_data($params) {
     
     global $ct;
     						
     $test_data = @$ct['cache']->ext_data('params', $params, 0, $ct['base_url'] . $ct['int_api_base_endpoint'] . 'market_conversion/eur/kraken-btc-usd,coinbase-dai-usd,coinbase-eth-usd', 2);
     
     // Already json-encoded
     return $test_data;
    
     }
		
		
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

				
};
// END class
		

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
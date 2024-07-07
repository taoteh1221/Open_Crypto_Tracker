<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


        
foreach ( $_POST['mobile_network']['text_gateways'] as $val ) {
    
$gateway_data = array_map( "trim", explode("||", $val) );
			
$test_result = $ct['gen']->valid_email( 'test@' . $gateway_data[1] );
		
                    
     if ( sizeof($_POST['mobile_network']['text_gateways']) == 1 && trim($val) == '' ) {
     // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
     }
	elseif ( $ct['var']->begins_with_in_array($_POST['mobile_network']['text_gateways'], $gateway_data[0] . '||')['count'] > 1 ) {
                $ct['update_config_error'] .= '<br />Mobile text gateway KEY was USED TWICE (DUPLICATE): "'.$gateway_data[0].'" (in "'.$val.'", no duplicate keys allowed)';
	}
     elseif ( $test_result != 'valid' ) {
                $ct['update_config_error'] .= '<br />Mobile text gateway seems INVALID: "'.$gateway_data[1].'" ('.$test_result.')';
	}
	
		
}
           
        
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>

<p align='center' style='margin: 15px;'><a href='javascript:scroll(0,0);'>Back To Top</a></p>


    <!-- footer START -->
<?php

	foreach ( $logs_array['cache_error'] as $error ) {
	$other_error_logs .= $error;
	}
	
	$other_error_logs .= $logs_array['security_error'];
	
	$other_error_logs .= $logs_array['cmc_config_error'];
	
	$other_error_logs .= $logs_array['other_error'];
	
	
	if ( $app_config['debug_mode'] != 'off' ) {
	
	
		foreach ( $logs_array['cache_debugging'] as $error ) {
		$other_error_logs .= $error;
		}
		
		$other_error_logs .= $logs_array['security_debugging'];
		
		$other_error_logs .= $logs_array['cmc_config_debugging'];
		
		$other_error_logs .= $logs_array['other_debugging'];
		
	
	}


?>
            	
    <div id="api_error_alert"><?php echo $logs_array['config_error'] . $logs_array['api_data_error'] . $other_error_logs . $logs_array['cmc_error']; ?></div>
            	
    <p align='center'><a href='https://dfd-cryptocoin-values.sourceforge.io/' target='_blank' title='Download the latest version here.'>Latest Releases (running v<?=$app_version?>)</a>
    

    <p align='center'><a class='show' id='donate' href='#show_donation_addresses' title='Click to show donation addresses.' onclick='return false;'>Donations Support Development</a></p>
    
            	<div style='display: none;' class='show_donate' align='center'>
            	
            	<b>Github:</b> <br /><a href='https://github.com/sponsors/taoteh1221' target='_blank'>https://github.com/sponsors/taoteh1221</a>
            	
            	<br /><br /><b>Coinbase:</b> <br /><a href='https://commerce.coinbase.com/checkout/5e72fe35-752e-4a65-a4c3-2d49d73f2c36' target='_blank'>https://commerce.coinbase.com/checkout</a>
            	
            	<br /><br /><b>PayPal:</b> <br /><a href='https://www.paypal.me/dragonfrugal' target='_blank'>https://www.paypal.me/dragonfrugal</a>
            	
            	<br /><br /><b>Patreon:</b> <br /><a href='https://www.patreon.com/dragonfrugal' target='_blank'>https://www.patreon.com/dragonfrugal</a>
            	
            	<br /><br /><b>Monero (XMR):</b> <br /><span class='long_linebreak'>47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu</span>
            	
            	<br /><b>Monero Address QR Code (for phones)</b><br /><img src='templates/interface/media/images/xmr-donations-qr-code.png' border='0' />
            	
            	<br /><br />
            	</div>
     
    <?php
    	
    	// Proxy alerts (if setup by user, and any of them failed, test the failed proxies and log/alert if they seem offline)
		if ( $app_config['proxy_alerts'] != 'none' ) {
	
			foreach ( $proxy_checkup as $problem_proxy ) {
			test_proxy($problem_proxy);
			sleep(1);
			}

		}

      
      ////START DEBUGGING ///////////////////////////////////////////////////////////////
          	
    	// DEBUGGING UNICODE EMAIL-TO-MOBILE-TEXT GATEWAY MESSAGE FORMATTING
    	
    	//echo '<br /> ------ <br />';
    	
    	//echo character_unicode_to_utf8('x1f433', 'hexadecimal');
    	
    	//echo '<br /> ------ <br />';
    	
    	//echo character_utf8_to_unicode('🐳', 'hexadecimal');
    	
    	//echo '<br /> ------ <br />';
    	
    	//$test_phrase = 'UNICODE MESSAGE SUPPORT TEST ONLY: Твоје зелене очи су ми памет помутиле... 🐳... END';
    	
    	//$test_phrase = '🐳';
    	
    	//$test_phrase = 'ASCII MESSAGE SUPPORT TEST ONLY... END';
          	
  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  				// Minimize function calls
  				//$encoded_text_message = content_data_encoding($test_phrase);
  				
  				/*
          	$send_params = array(
          								'text' => array(
          														// Unicode support included for text messages (emojis / asian characters / etc )
          														'message' => $encoded_text_message['content_output'],
          														'charset' => $encoded_text_message['charset']
          														),
          								'email' => array(
          														'subject' => 'UNICODE SUPPORT TEST',
          														'message' => $encoded_text_message['content_output'],
          														'charset' => $encoded_text_message['charset'] 
          														)
          								);
          	
          	
          	
          	// Send notifications
          	@queue_notifications($send_params);
          	*/
    	
    	//var_dump($encoded_text_message);
    	
          	
      ////END DEBUGGING ///////////////////////////////////////////////////////////////
          	
          	

		// Log errors, send notifications BEFORE runtime stats
		error_logs();
		send_notifications();
		
		
		// Calculate script runtime length
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$total_runtime = round( ($time - $start_runtime) , 3);


		// If debug mode is on
		if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' || $app_config['debug_mode'] == 'stats' ) {
		
			foreach ( $system_info as $key => $value ) {
			$system_telemetry .= $key . ': ' . $value . '; ';
			}
			
		// Log system stats
		app_logging('other_debugging', 'Stats for hardware / software', $system_telemetry);
			
		// Log user agent
		app_logging('other_debugging', 'User agent', 'user_agent: "' . $_SERVER['HTTP_USER_AGENT'] . '"' );
			
		// Log runtime stats
		app_logging('other_debugging', 'Stats for '.$runtime_mode.' runtime', $runtime_mode.'_runtime: ' . $total_runtime . ' seconds');
		
		}
		
		
		// Process debugging logs / destroy session data AFTER runtime stats
		debugging_logs();
		hardy_session_clearing();
    		
    		
    	echo '<p align="center" class="'.( $total_runtime > 10 ? 'red' : 'green' ).'"> Interface Runtime: '.$total_runtime.' seconds</p>';
    	
    	
  
    	
    ?>
        
        
        
   		 </div>
    </div>
     <br /> <br />
     
 


<!-- https://v4-alpha.getbootstrap.com/getting-started/introduction/#starter-template -->
<script src="app-lib/js/jquery/bootstrap.min.js"></script>

</body>
</html>

<!-- /*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->
 
 
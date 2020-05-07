
    <!-- footer.php START -->
    
<br class='clear_both' />

<p class='align_center' style='margin: 15px;'><a href='javascript:scroll(0,0);'>Back To Top</a></p>


<?php


	foreach ( $logs_array['cache_error'] as $error ) {
	$bundle_error_logs .= $error;
	}
	
	$bundle_error_logs .= $logs_array['system_error'];
	
	$bundle_error_logs .= $logs_array['config_error'];
	
	$bundle_error_logs .= $logs_array['security_error'];
	
	$bundle_error_logs .= $logs_array['ext_api_error'];
	
	$bundle_error_logs .= $logs_array['market_error'];
	
	$bundle_error_logs .= $logs_array['other_error'];
	
	
	if ( $app_config['developer']['debug_mode'] != 'off' ) {
	
	
		foreach ( $logs_array['cache_debugging'] as $error ) {
		$bundle_error_logs .= $error;
		}
	
	$bundle_error_logs .= $logs_array['system_debugging'];
	
	$bundle_error_logs .= $logs_array['config_debugging'];
	
	$bundle_error_logs .= $logs_array['security_debugging'];
	
	$bundle_error_logs .= $logs_array['ext_api_debugging'];
	
	$bundle_error_logs .= $logs_array['market_debugging'];
	
	$bundle_error_logs .= $logs_array['other_debugging'];
		
	
	}


?>
            	
    <div id="app_error_alert"><?=$bundle_error_logs?></div>
            	
    <p class='align_center'><a href='https://taoteh1221.github.io' target='_blank' title='Download the latest version here.'>Latest Releases (running v<?=$app_version?>)</a>
    

    <p class='align_center'><a class='show' id='donate' href='#show_donation_addresses' title='Click to show donation addresses.' onclick='return false;'>Donations Support Development</a></p>
    
            	<div style='display: none;' class='align_center show_donate'>
            	
            	<b>Github:</b> <br /><a href='https://github.com/sponsors/taoteh1221' target='_blank'>https://github.com/sponsors/taoteh1221</a>
            	
            	<br /><br /><b>Coinbase:</b> <br /><a href='https://commerce.coinbase.com/checkout/5e72fe35-752e-4a65-a4c3-2d49d73f2c36' target='_blank'>https://commerce.coinbase.com/checkout</a>
            	
            	<br /><br /><b>PayPal:</b> <br /><a href='https://www.paypal.me/dragonfrugal' target='_blank'>https://www.paypal.me/dragonfrugal</a>
            	
            	<br /><br /><b>Patreon:</b> <br /><a href='https://www.patreon.com/dragonfrugal' target='_blank'>https://www.patreon.com/dragonfrugal</a>
            	
            	<br /><br /><b>Monero (XMR):</b> <br /><span class='align_center long_linebreak'>47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu</span>
            	
            	<br /><br /><b>Monero Address QR Code (for phones)</b><br /><img src='templates/interface/media/images/xmr-donations-qr-code.png' alt='' />
            	
            	<br /><br />
            	</div>
     
    <?php
          
    	
    	// Proxy alerts (if setup by user, and any of them failed, test the failed proxies and log/alert if they seem offline)
		if ( $app_config['comms']['proxy_alerts'] != 'off' ) {
	
			foreach ( $proxy_checkup as $problem_proxy ) {
			test_proxy($problem_proxy);
			sleep(1);
			}

		}
          	
          	
          	
		// Log errors, send notifications BEFORE runtime stats
		$error_logs = error_logs();
		send_notifications();
		
		if ( $error_logs != true ) {
		?>
		<div class="red" style='font-weight: bold;'><?=$error_logs?></div>
		<?php
		}
		
		
		
		// Calculate script runtime length
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$total_runtime = round( ($time - $start_runtime) , 3);



		// If debug mode is on
		if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' || $app_config['developer']['debug_mode'] == 'stats' ) {
		
			foreach ( $system_info as $key => $value ) {
			$system_telemetry .= $key . ': ' . $value . '; ';
			}
			
		// Log system stats
		app_logging('system_debugging', 'Stats for hardware / software', $system_telemetry);
			
		// Log user agent
		app_logging('system_debugging', 'User agent', 'user_agent: "' . $_SERVER['HTTP_USER_AGENT'] . '"' );
			
		// Log runtime stats
		app_logging('system_debugging', 'Stats for '.$runtime_mode.' runtime', $runtime_mode.'_runtime: ' . $total_runtime . ' seconds');
		
		}
		
		
		// Process debugging logs AFTER runtime stats
		$debugging_logs = debugging_logs();
    		
		if ( $app_config['developer']['debug_mode'] != 'off' && $debugging_logs != true ) {
		?>
		<div class="red" style='font-weight: bold;'><?=$debugging_logs?></div>
		<?php
		}
    		
    	echo '<p class="align_center '.( $total_runtime > 25 ? 'red' : 'green' ).'"> Runtime: '.$total_runtime.' seconds</p>';
    	
    
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
 
 
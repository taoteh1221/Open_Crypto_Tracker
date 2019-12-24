
<p align='center' style='margin: 15px;'><a href='javascript:scroll(0,0);'>Back To Top</a></p>


    <!-- footer START -->
<?php

	foreach ( $_SESSION['cache_error'] as $error ) {
	$other_error_logs .= $error;
	}
	
	$other_error_logs .= $_SESSION['security_error'];
	
	$other_error_logs .= $_SESSION['cmc_config_error'];
	
	$other_error_logs .= $_SESSION['other_error'];
	

?>
            	
    <div id="api_error_alert"><?php echo $_SESSION['config_error'] . $_SESSION['api_data_error'] . $other_error_logs . $_SESSION['cmc_error']; ?></div>
            	
    <p align='center'><a href='https://dfd-cryptocoin-values.sourceforge.io/' target='_blank' title='Download the latest version here.'>Latest Releases (running v<?=$app_version?>)</a>
    

    <p align='center'><a class='show' id='donate' href='#show_donation_addresses' title='Click to show donation addresses.' onclick='return false;'>Donations Support Development</a></p>
    
            	<div style='display: none;' class='show_donate' align='center'>
            	
            	<b>Github:</b> <br /><a href='https://github.com/sponsors/taoteh1221' target='_blank'>https://github.com/sponsors/taoteh1221</a>
            	
            	<br /><br /><b>Coinbase:</b> <br /><a href='https://commerce.coinbase.com/checkout/5e72fe35-752e-4a65-a4c3-2d49d73f2c36' target='_blank'>https://commerce.coinbase.com/checkout</a>
            	
            	<br /><br /><b>PayPal:</b> <br /><a href='https://www.paypal.me/dragonfrugal' target='_blank'>https://www.paypal.me/dragonfrugal</a>
            	
            	<br /><br /><b>Patreon:</b> <br /><a href='https://www.patreon.com/dragonfrugal' target='_blank'>https://www.patreon.com/dragonfrugal</a>
            	
            	<br /><br /><b>Monero (XMR):</b> <br /><span class='long_linebreak'>47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu</span>
            	
            	<br /><b>Monero Address QR Code (for phones)</b><br /><img src='ui-templates/media/images/xmr-donations-qr-code.png' border='0' />
            	
            	<br /><br />
            	</div>
     
    <?php
    	
    	// Proxy alerts (if setup by user, and any of them failed, test the failed proxies and log/alert if they seem offline)
		if ( $proxy_alerts != 'none' ) {
	
			foreach ( $_SESSION['proxy_checkup'] as $problem_proxy ) {
			test_proxy($problem_proxy);
			sleep(1);
			}

		}


		// Log errors, send notifications BEFORE runtime stats
		error_logs();
		send_notifications();
		
		
			// If this is running on a Raspberry Pi, display the load avg / temperature / free partition space
    		if ( preg_match("/raspberry/i", $system_info['model']) ) {
    			
    		// Raspi system data
    		$raspi_load = $system_info['system_load'];
    		$raspi_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $raspi_load);
    		$raspi_load = preg_replace("/(.*)\(5 min avg\) /i", "", $raspi_load); // Use 15 minute average
    		
    		$raspi_temp = preg_replace("/° Celsius/i", "", $system_info['system_temp']);
    		
    		$raspi_memory_total = preg_replace("/ (.*)/i", "", $system_info['memory_total']);
    		
    		$raspi_memory_free = preg_replace("/ (.*)/i", "", $system_info['memory_free']);
    		
    		
  			// Percent difference (!MUST BE! absolute value)
         $memory_percent_free = round( 100 - abs( ($raspi_memory_free - $raspi_memory_total) / abs($raspi_memory_total) * 100 ) , 2);
    		
    		
			$raspi_free_space = preg_replace("/ (.*)/i", "", $system_info['free_partition_space']);
			
				// Always in megabytes
				if ( preg_match("/kilo/i", $system_info['free_partition_space']) ) {
				$raspi_free_space = $raspi_free_space / 1000;
				}
				elseif ( preg_match("/mega/i", $system_info['free_partition_space']) ) {
				$raspi_free_space = $raspi_free_space / 1;
				}
				elseif ( preg_match("/giga/i", $system_info['free_partition_space']) ) {
				$raspi_free_space = $raspi_free_space / 0.001;
				}
				elseif ( preg_match("/tera/i", $system_info['free_partition_space']) ) {
				$raspi_free_space = $raspi_free_space / 0.000001;
				}
				elseif ( preg_match("/peta/i", $system_info['free_partition_space']) ) {
				$raspi_free_space = $raspi_free_space / 0.000000001;
				}
				elseif ( preg_match("/exa/i", $system_info['free_partition_space']) ) {
				$raspi_free_space = $raspi_free_space / 0.000000000001;
				}
				elseif ( preg_match("/zetta/i", $system_info['free_partition_space']) ) {
				$raspi_free_space = $raspi_free_space / 0.000000000000001;
				}
				elseif ( preg_match("/yotta/i", $system_info['free_partition_space']) ) {
				$raspi_free_space = $raspi_free_space / 0.000000000000000001;
				}
	
	
    		// Footer output
    		echo '<p align="center" class="'.( $raspi_load <= 1.07 ? 'green' : 'red' ).'"> System Load: '.$system_info['system_load'].'</p>';
    		echo '<p align="center" class="'.( $raspi_temp <= 79 ? 'green' : 'red' ).'"> Temperature: '.$system_info['system_temp'].'</p>';
    		echo '<p align="center" class="'.( $raspi_free_space >= 500 ? 'green' : 'red' ).'"> Free Space: '.$system_info['free_partition_space'].'</p>';
    		echo '<p align="center" class="'.( $memory_percent_free >= 8.5 ? 'green' : 'red' ).'"> Free Memory: '.$system_info['memory_free'].' ('.$memory_percent_free.'%)</p>';
    		
    		}
		
		
		// Calculate script runtime length
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$total_runtime = round( ($time - $start_runtime) , 3);


		// If debug mode is on
		if ( $debug_mode == 'all' || $debug_mode == 'telemetry' || $debug_mode == 'stats' ) {
		
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
    		
    		
    	echo '<p align="center" class="'.( $total_runtime <= 10 ? 'green' : 'red' ).'"> Runtime: '.$total_runtime.' seconds</p>';
    	
		
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
 
 
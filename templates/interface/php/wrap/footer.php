<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Proxy alerts (if setup by user, and any of them failed, test the failed proxies and log/alert if they seem offline)
if ( $ct['conf']['proxy']['proxy_alert_channels'] != 'off' ) {
	
	foreach ( $ct['proxy_checkup'] as $problem_proxy ) {
	$ct['gen']->test_proxy($problem_proxy);
	sleep(1);
	}

}
          	

// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$total_runtime = round( ($time - $start_runtime) , 3);


// If debug mode is 'all_telemetry' / 'stats'
if ( $ct['conf']['power']['debug_mode'] == 'all_telemetry' || $ct['conf']['power']['debug_mode'] == 'stats' ) {


	foreach ( $ct['system_info'] as $key => $val ) {
	$system_telemetry .= $key . ': ' . $val . '; ';
	}
	
	
// Log system stats
$ct['gen']->log(
  'system_debug',
  'Hardware / software stats (requires log_verbosity set to verbose)',
  $system_telemetry
  );
	
	
// Log user agent
$ct['gen']->log('system_debug', 'USER AGENT is "' . $_SERVER['HTTP_USER_AGENT'] . '"');
	
// Log runtime stats
$ct['gen']->log('system_debug', strtoupper($ct['runtime_mode']).' runtime was ' . $total_runtime . ' seconds');

}


// Process logs / notification AFTER runtime stats
$app_log = $ct['cache']->app_log();
$ct['cache']->send_notifications();
        

// Iframe footer code
if ( $is_iframe ) {


     // Get exchange keys for info bubble
     if ( $is_admin && $_GET['subsection'] == 'price_alerts_charts' ) {
     require($ct['base_dir'] . '/app-lib/php/inline/debugging/exchange-and-pair-info.php');
     }

     
     // IF WE HAVE A LOG WRITE ERROR FOR ANY LOGS, PRINT IT IN THE FOOTER HERE
     		
     if ( $app_log != true ) {
     ?>
     <div class="red" style='font-weight: bold;'><?=$app_log?></div>
     <?php
     }
     ?>


<!-- IFRAME footer.php START -->
    

<div id="iframe_error_alert" style='display: none;'><?php echo $ct['alerts_gui_logs']; ?></div>

	
<script>
		
		
			var exchange_keys_info = '<h5 class="align_center red tooltip_title">Using "Exchange Keys" For Price Alerts / Charts</h5>'
			
			
			+'<p class="coin_info extra_margins bitcoin" style="white-space: normal; ">"Under the hood", this app identifies what exchange to use with "exchange keys". Here is the FULL list of all ACTIVE exchange keys (exchanges with configured markets, in the current portfolio assets config):</p>'
			
			+'<p class="coin_info extra_margins red" style="white-space: normal; "><?=strtolower($all_exchanges_list)?></p>'
			
			
			+'<p> </p>';

	
			$('#exchange_keys_info').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: exchange_keys_info,
			css: {
					fontSize: "<?=$set_font_size?>em",
					minWidth: "350px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {


     <?php
     if ( $is_admin ) {
     $reload_function_name = 'primary_refresh_iframes';
     require("templates/interface/php/wrap/wrap-elements/admin-refresh.php");
     } // END admin
     // NON ADMIN
     else {
     ?>
     
     // Non-admin script goes here...
     
     <?php
     }
     ?>
     
     
});

     
</script>  	


<?php
} // END iframe
// If NOT iframe
else {
?>


<!-- PRIMARY footer.php START -->

    
    <br class='clear_both' />
    
           <div class='footer_content'>
    
              <p class='align_center' style='margin: 15px;'>
              
              <a class='bitcoin' href='javascript:scroll(0,0);' title='Return to the top of the page.'>Back To Top</a>
              
              &nbsp;&nbsp; || &nbsp;&nbsp; 
              
              <a title='Let me know (anonymously OR otherwise) you enjoy my apps.' href='javascript: show_more("donate");'>Show Appreciation</a>
              
              &nbsp;&nbsp; || &nbsp;&nbsp; 
              
              <a href='https://taoteh1221.github.io' target='_blank' title='Check for upgrades to the latest version here.'>Running <?=ucfirst($ct['app_edition'])?> Edition<?=( $ct['gen']->admin_logged_in() ? ' v' . $ct['app_version'] : '' )?></a>
              
              </p>
                      	
              <div id="app_error_alert" style='display: none;'><?php echo $ct['alerts_gui_logs']; ?></div>

<?php

require("templates/interface/php/wrap/wrap-elements/donation-links.php");
require("templates/interface/php/wrap/wrap-elements/report-issues-modal.php");

          
        // IF WE HAVE A LOG WRITE ERROR FOR ANY LOGS, PRINT IT IN THE FOOTER HERE
		
		if ( $app_log != true ) {
		?>
		<div class="red" style='font-weight: bold;'><?=$app_log?></div>
		<?php
		}
    		
    	echo '<p class="align_center '.( $total_runtime > 25 ? 'red' : 'green' ).'"> Runtime: '.$total_runtime.' seconds</p>';
    	
    ?>
        
        
        
   		 </div> <!-- .footer_content -->
   		 
        
   		 </div>
   		 
   		 
    </div> <!-- #secondary_wrapper -->


</div> <!-- #primary_wrapper.wrapper -->

<br clear='all' /> <br />
     
            	
<!--
Workaround for #VERY ODD# PHP v8.0.1 BUG, WHEN TRYING TO ECHO $ui_upgrade_alert['message'] IN HEADER.PHP
(so we render it in footer.php, near the end of rendering)
-->
<div id="app_upgrade_alert" style='display: none;'>
<?php
// For security, only display if a UI upgrade alert notice was triggered
if ( $display_upgrade_alert ) {
echo nl2br($ui_upgrade_alert['message']);
}
?>
</div>


<script>


// Creates Cookie notice footer banner
footer_banner(cookies_notice_storage, 'This web app requires cookies for admin logins (browser cookie / server session). The option to ENABLE ADDITIONAL FEATURES requiring cookies is also available on the SETTINGS page.');


// Donations reminder (if has ALREADY ACKNOWLEDGED the cookies banner)
if ( localStorage.getItem(cookies_notice_storage) == "understood" ) {

footer_banner(donations_notice_storage, 'You can <a style="font-weight: bold; color: red !important;" href="https://github.com/taoteh1221/Open_Crypto_Tracker/issues" target="_BLANK">report issues</a> (YES, I actually fix the REAL ones, if you submit a DETAILED report).<br />Please show your appreciation for my apps IF you enjoy using them. Buying me a coffee / beer means WAY MORE to me than large donations. It\'s about <a href="https://taoteh1221.github.io/#donations" target="_BLANK">letting me know</a> you find them useful, NOT about making money. Think of it as a PRIVATE app usage survey anon! :) Crypto addresses are bot-monitored (for balance changes) on active / well-secured / backed-up HD wallets...<br /><a href="https://taoteh1221.github.io/#donations" target="_BLANK"><img height="175" src="templates/interface/media/images/donate-banner.png" alt="" class="image_border" style="margin: 0.3em;" /></a>');

}


// Otherwise, creates Safari notice footer banner (if using safari / has ALREADY ACKNOWLEDGED the cookies / donations banners)
if ( is_safari && localStorage.getItem(cookies_notice_storage) == "understood" && localStorage.getItem(donations_notice_storage) == "understood" ) {

footer_banner(safari_notice_storage, 'This web app MAY NOT WORK PROPERLY on the Apple Safari web browser. FireFox OR Chromium-based browsers (Chrome / Edge / Brave / Opera, etc) are highly recommended for the best user experience.');

}
// OR creates windows phpdesktop notice footer banner (if using Desktop on Windows / has ALREADY ACKNOWLEDGED the cookies / donations banners)
else if ( app_edition == 'desktop' && app_platform == 'windows' && app_container == 'phpdesktop' && localStorage.getItem(cookies_notice_storage) == "understood" && localStorage.getItem(donations_notice_storage) == "understood" ) {

footer_banner(desktop_windows_notice_storage, 'This web app *SOMETIMES* MAY NOT WORK PROPERLY for this "PHPdesktop"-based WINDOWS DESKTOP EDITION (all other Editions work fine). Try installing the <a href="https://github.com/taoteh1221/Open_Crypto_Tracker/releases" target="_BLANK">Newest Windows Desktop Edition of this app</a>, as we now use "PHPbrowserBox" instead of "PHPdesktop", which makes the Windows Edition RUN WAY BETTER.');

}


</script>


<?php
} // END of NOT iframe
?>


<!-- https://getbootstrap.com/docs/5.3/getting-started/download/ -->
<script src="app-lib/js/bootstrap/bootstrap.min.js"></script>


</body>
</html>


<!-- /*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */ -->

 
 <?php

// Access stats logging
$ct['cache']->log_access_stats();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

 // In case we are redirected to a login template, we include this exit...
 // IN #ANY# CASE, WE SHOULD BE COMPLETELY DONE RENDERING AT THIS POINT
 exit;
 ?>
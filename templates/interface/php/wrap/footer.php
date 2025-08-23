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
        

// If debug mode is 'stats'
if ( $ct['conf']['power']['debug_mode'] == 'stats' ) {


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

}


// Process logs AFTER above debug stats, but BEFORE any UI alerts output
// (sets $ct['alerts_gui_logs'] global, to output below for UI alerts)
$app_log = $ct['cache']->app_log();


// Iframe footer code
if ( $is_iframe ) {

     
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
              
              <a href='https://taoteh1221.github.io' target='_blank' title='Check for upgrades to the latest version here.'>Running <?=ucfirst($ct['app_edition'])?> Edition<?=( $ct['sec']->admin_logged_in() ? ' v' . $ct['app_version'] : '' )?></a>
              
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
    	
?>

        <div id="app_runtime" class='align_center'></div>
        
        
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

// FOOTER NOTICES ARE ORDERED AS YOU WANT THEM TO APPEAR!
// (IF ONE IS SHOWING ALREADY, THE REST WILL BE SUPPRESSED, UNTIL NOTHING IS ALREADY SHOWING [ON INITIAL PAGE LOAD])


// Creates Cookie notice footer banner
footer_banner(cookies_notice_storage, 'This web app requires cookies for admin logins (browser cookie / server session). The option to ENABLE ADDITIONAL FEATURES requiring cookies is also available on the SETTINGS page.');


// Donations reminder
footer_banner(donations_notice_storage, 'You can <a style="font-weight: bold; color: red !important;" href="https://github.com/taoteh1221/Open_Crypto_Tracker/issues" target="_BLANK">report issues</a> (YES, I actually fix the REAL ones, if you submit a DETAILED report).<br />Please show your appreciation for my apps IF you enjoy using them. Buying me a coffee / beer means WAY MORE to me than large donations. It\'s about <a href="https://taoteh1221.github.io/#donations" target="_BLANK">letting me know</a> you find them useful, NOT about making money. Think of it as a PRIVATE app usage survey anon! :) Crypto addresses are bot-monitored (for balance changes) on active / well-secured / backed-up HD wallets...<br /><a href="https://taoteh1221.github.io/#donations" target="_BLANK"><img height="175" src="templates/interface/media/images/donate-banner.png" alt="" class="image_border" style="margin: 0.3em;" /></a>');


// LINUX Desktop Edition SUCKS HARD (as of 2025/5/25, beyond our control [as we use SEVERELY OUTDATED 3rd party container PHPdesktop])
if ( Base64.decode(app_platform) == 'linux' && Base64.decode(app_container) == 'phpdesktop' ) {

footer_banner(linux_phpdesktop_notice_storage, 'This web app MAY NOT WORK PROPERLY on the LINUX Desktop Edition (as of May 25th 2025, the 3rd party "PHPdesktop" container we use has not been updated for LINUX since February 8th 2019). Automatically setting up the Server Edition by running the "FOLIO-INSTALL.bash" script (inside the Desktop Edition subdirectory "INSTALL_CRYPTO_TRACKER_HERE") is highly recommended for the best user experience.');

}
// Creates Safari notice footer banner (Safari on OLDER Macs SUCKS HARD)
else if ( is_safari ) {

footer_banner(safari_notice_storage, 'This web app MAY NOT FULLY FUNCTION / DISPLAY PROPERLY, on some WebKit or Apple Safari web browsers. IF YOU ENCOUNTER ISSUES, FireFox OR Chromium-based browsers (Chrome / Edge / Brave / Opera, etc) are HIGHLY RECOMMENDED for the best user experience.');

}


</script>


<?php
} // END of NOT iframe
?>


<!-- https://getbootstrap.com/docs/5.3/getting-started/download/ -->
<script src="app-lib/js/bootstrap/bootstrap.min.js"></script>

<?php


// Access stats / notifications / etc
$ct['cache']->log_access_stats();
$ct['cache']->api_throttle_cache();
$ct['cache']->send_notifications();
          	

// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$total_runtime = round( ($time - $start_runtime) , 3);


// If debug mode is 'stats', add runtime minutes stats to logs
// (to get accurate runtime seconds, we can't included this at top of this file,
// to be included in the UI alerts, BUT the UI always shows the runtime seconds anyway)
if ( $ct['conf']['power']['debug_mode'] == 'stats' ) {
	
// Log runtime stats
$ct['gen']->log('system_debug', strtoupper($ct['runtime_mode']).' runtime was ' . $total_runtime . ' seconds');

// Process logs AGAIN, AFTER runtime minutes stats
$app_log = $ct['cache']->app_log();

}


?>


<div id="app_runtime_hidden" style='display: none;'>

<?php
echo '<p class="align_center '.( $total_runtime > 25 ? 'red' : 'green' ).'"> Runtime: '.$total_runtime.' seconds</p>';
?>

</div>


<script>
$('#app_runtime').html( $('#app_runtime_hidden').html() );
</script>


</body>
</html>


<!-- /*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */ -->

 
 <?php

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

 // In case we are redirected to a login template, we include this exit...
 // IN #ANY# CASE, WE SHOULD BE COMPLETELY DONE RENDERING AT THIS POINT
 exit;
 ?>
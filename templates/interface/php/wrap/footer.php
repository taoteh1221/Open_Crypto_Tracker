<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Proxy alerts (if setup by user, and any of them failed, test the failed proxies and log/alert if they seem offline)
if ( $ct_conf['comms']['proxy_alert'] != 'off' ) {
	
	foreach ( $proxy_checkup as $problem_proxy ) {
	$ct_gen->test_proxy($problem_proxy);
	sleep(1);
	}

}
          	
          	
// Log errors, send notifications BEFORE runtime stats
$error_log = $ct_cache->error_log();
$ct_cache->send_notifications();


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$total_runtime = round( ($time - $start_runtime) , 3);


// If debug mode is 'all' / 'all_telemetry' / 'stats'
if ( $ct_conf['dev']['debug_mode'] == 'all' || $ct_conf['dev']['debug_mode'] == 'all_telemetry' || $ct_conf['dev']['debug_mode'] == 'stats' ) {


	foreach ( $system_info as $key => $val ) {
	$system_telemetry .= $key . ': ' . $val . '; ';
	}
	
	
// Log system stats
$ct_gen->log(
  'system_debug',
  'Hardware / software stats (requires log_verbosity set to verbose)',
  $system_telemetry
  );
	
	
// Log user agent
$ct_gen->log('system_debug', 'USER AGENT is "' . $_SERVER['HTTP_USER_AGENT'] . '"');
	
// Log runtime stats
$ct_gen->log('system_debug', strtoupper($runtime_mode).' runtime was ' . $total_runtime . ' seconds');

}


// Process debugging logs AFTER runtime stats
$debug_log = $ct_cache->debug_log();
        

// Iframe footer code
if ( $is_iframe ) {
     
     
     // IF WE HAVE A LOG WRITE ERROR FOR ANY LOGS, PRINT IT IN THE FOOTER HERE
     		
     if ( $error_log != true ) {
     ?>
     <div class="red" style='font-weight: bold;'><?=$error_log?></div>
     <?php
     }
     		
     if ( $ct_conf['dev']['debug_mode'] != 'off' && $debug_log != true ) {
     ?>
     <div class="red" style='font-weight: bold;'><?=$debug_log?></div>
     <?php
     }
     ?>


<!-- IFRAME footer.php START -->
    

<div id="iframe_error_alert" style='display: none;'><?php echo $alerts_gui_errors . ( isset($alerts_gui_debugging) && $alerts_gui_debugging != '' ? '============<br />DEBUGGING:<br />============<br />' . $alerts_gui_debugging : '' ); ?></div>

	
<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {


     <?php
     if ( $is_admin ) {
     ?>
     
     //console.log('admin iframe "<?=$_GET['section']?>" loaded.'); // DEBUGGING
         
     function reload_iframes() {
         
         <?php
         // If we need to refresh an admin iframe, to show the updated data
         if ( $_GET['refresh'] ) {
             
             
             if ( $_GET['refresh'] == 'all' ) {
             
             $refresh_admin = array(
                                     'iframe_comms',
                                     'iframe_other_api',
                                     'iframe_general',
                                     'iframe_portfolio_assets',
                                     'iframe_charts_alerts',
                                     'iframe_plugins',
                                     'iframe_power_user',
                                     'iframe_text_gateways',
                                     'iframe_proxy',
                                     'iframe_developer',
                                     'iframe_int_api',
                                     'iframe_webhook',
                                     'iframe_system_stats',
                                     'iframe_access_stats',
                                     'iframe_logs',
                                     'iframe_backup_restore',
                                     'iframe_reset',
                                    );
                                    
             }
             else {
             $refresh_admin = explode(',', $_GET['refresh']);
             }
             
         
             foreach ( $refresh_admin as $refresh ) {
         
                 // DONT INCLUDE CURRENT PAGE (OR IT WILL *ENDLESS LOOP* RELOAD IT) 
                 if ( isset($refresh) && trim($refresh) != '' && $refresh != 'iframe_' . $_GET['section'] ) {
                 ?>
                 parent.document.getElementById('<?=$refresh?>').contentWindow.location.reload(true);
                 <?php
                 }
             
             }
             
         
         }
         ?>
         
     }
     
     // Reload all flagged iframes after 3.5 seconds (to give any newly-revised ct_conf time to re-cache)
     setTimeout(reload_iframes, 3500); 
     
     <?php
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
    
    <p class='align_center' style='margin: 15px;'><a href='javascript:scroll(0,0);' title='Return to the top of the page.'>Back To Top</a></p>
            	
    <div id="app_error_alert" style='display: none;'><?php echo $alerts_gui_errors . ( isset($alerts_gui_debugging) && $alerts_gui_debugging != '' ? '============<br />DEBUGGING:<br />============<br />' . $alerts_gui_debugging : '' ); ?></div>
            	
    <p class='align_center'><a href='https://taoteh1221.github.io' target='_blank' title='Check for upgrades to the latest version here.'>Running <?=ucfirst($app_edition)?> Edition<?=( $ct_gen->admin_logged_in() ? ' v' . $app_version : '' )?></a>
    

<?php

require("templates/interface/php/wrap/wrap-elements/donation-links.php");
require("templates/interface/php/wrap/wrap-elements/help-faq-modal.php");

          
        // IF WE HAVE A LOG WRITE ERROR FOR ANY LOGS, PRINT IT IN THE FOOTER HERE
		
		if ( $error_log != true ) {
		?>
		<div class="red" style='font-weight: bold;'><?=$error_log?></div>
		<?php
		}
		
		if ( $ct_conf['dev']['debug_mode'] != 'off' && $debug_log != true ) {
		?>
		<div class="red" style='font-weight: bold;'><?=$debug_log?></div>
		<?php
		}
    		
    	echo '<p class="align_center '.( $total_runtime > 25 ? 'red' : 'green' ).'"> Runtime: '.$total_runtime.' seconds</p>';
    	
    ?>
        
        
        
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


<!-- https://jsfiddle.net/TheAL/ednxgwrj/ -->

<style>

.cookies_button {
    background: none;
    padding: 0;
    border: none;
}
.cookies_button:hover {
    text-decoration: underline;
    cursor: pointer;
}
.cookie-notice {
    font-size: 15px;
    line-height: 30px;
    padding: 10px 5px;
    position: fixed;
    bottom: 0;
    left: 0;
    display: none;
    width: 100%;
    text-align: center;
    color: #000;
    background: #efc551;
    z-index: 9999;
}
.cookie-notice .cookies_button {
    display: inline-block;
    line-height: 30px;
    margin-left: 10px;
    padding: 0 15px;
    color: #000;
    background: #b9ff35;
}

</style>

<div class="cookie-notice">This web app requires cookies for admin logins (browser cookie / server session). The option to ENABLE ADDITIONAL FEATURES requiring cookies is also available on the SETTINGS page.<button class='cookies_button'>I Understand</button></div>

<script>

/**
 * Creates Cookie notice
 */

var cookies_notice = $('.cookie-notice');

if ( localStorage.getItem(cookies_notice_storage) != "agreed" ) {
    cookies_notice.slideDown(500);
}

$('.cookie-notice .cookies_button').click(function () {
    cookies_notice.slideUp(500);
    localStorage.setItem(cookies_notice_storage, "agreed");
});

</script>

<?php
} // END of NOT iframe
?>


<!-- https://getbootstrap.com/docs/5.3/getting-started/download/ -->
<script src="app-lib/js/bootstrap/bootstrap.min.js"></script>

</body>
</html>

<!-- /*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */ -->
 
 <?php
 
flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

 // In case we are redirected to a login template, we include this exit...
 // IN #ANY# CASE, WE SHOULD BE COMPLETELY DONE RENDERING AT THIS POINT
 exit;
 ?>
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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


// If debug mode is 'all' / 'all_telemetry' / 'stats'
if ( $ct['conf']['power']['debug_mode'] == 'all' || $ct['conf']['power']['debug_mode'] == 'all_telemetry' || $ct['conf']['power']['debug_mode'] == 'stats' ) {


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
     
     
          if ( isset($_GET['plugin']) ) {
          $iframe_id = 'plugins'; // PLURAL HERE, AS THAT'S THE PARENT IFRAME ID SUFFIX FOR THE PLUGINS SUBSECTION
          }
          elseif ( isset($_GET['parent']) ) {
          $iframe_id = $_GET['parent']; // PARENT HERE, AS THAT'S THE PARENT IFRAME ID SUFFIX FOR GENERIC SUBSECTIONS
          }
          // MUST BE LAST, AS 'section' IS ALWAYS OVERRIDDEN IF INSIDE A SUBSECTION, REGARDING IFRAME IDS
          elseif ( isset($_GET['section']) ) {
          $iframe_id = $_GET['section'];
          }
     
     ?>
     
     console.log('admin iframe "<?=$iframe_id?>" loaded.'); // DEBUGGING
         
     console.log('parent.admin_settings_save_init ("<?=$iframe_id?>") = ' + parent.admin_settings_save_init);
                 
     //console.log('CURRENT URI: <?=$_SERVER['REQUEST_URI']?>');
         
         
     function refresh_iframes() {
    

         if ( is_admin && is_iframe ) {

    
              // Wait until admin_settings_save_init == true (in init.js)
              if ( !parent.admin_settings_save_init ) {
              reload_recheck = setTimeout(refresh_iframes, 1000);  // Re-check every 1 seconds (in milliseconds)
              return;
              }
	
              
              // RESET ONLY AFTER CONFIRMED AS RUNNING FROM BEING SET!
              parent.admin_settings_save_init = false; 
              console.log('RESET parent.admin_settings_save_init ("<?=$iframe_id?>") = ' + parent.admin_settings_save_init);
                  
              //console.log(parent.admin_interface_check);                
                 
              
              // Add any corrupted config sections to blacklist
              for (var hashed_id in parent.admin_interface_check) {
              skip_corrupt_sections.push( 'iframe_' + parent.admin_interface_check[hashed_id]['interface_id'] );
              console.log('corrupt section = ' + 'iframe_' + parent.admin_interface_check[hashed_id]['interface_id'] );
              }
              
              
         <?php
         // If we need to refresh an admin iframe, to show the updated data
         if ( isset($_GET['refresh']) && $_GET['refresh'] != 'none' && $_GET['refresh'] != 'auto' ) {
              
              
             // Flag as config NOT updated if it was halted (so we skip refreshing any other admin sections)
             if ( !$ct['app_upgrade_check'] && !$ct['reset_config'] && !$ct['update_config'] ) {
             
                 if ( $ct['check_2fa_error'] != null || $ct['update_config_error'] != null || $admin_general_error != null || $admin_reset_error != null ) {
                 $halt_iframe_refreshing = true;
                 ?>
                 console.log('halt_iframe_refreshing = "<?=$halt_iframe_refreshing?>"');
                 <?php
                 }
             
             }
             
             
             // 'auto' is the 'refresh' param value we set further down here in footer.php,
             // so we never get stuck in endless loops with refresh=all when refreshing here
             if ( $halt_iframe_refreshing ) {
             ?>
             selected_admin_iframe_ids = new Array(); // SET TO BLANK (no iframe refreshing)
             <?php
             }
             // Refreshing ALL admin sections
             elseif ( $_GET['refresh'] == 'all' ) {
             ?>
             selected_admin_iframe_ids = all_admin_iframe_ids; // ALL admin iframes refreshed
             <?php                   
             }
             // Refreshing the passed list of admin sections
             else {
             
             $refresh_admin = explode(',', $_GET['refresh']);
             $refresh_admin = array_map("trim", $refresh_admin);
             
                  foreach ( $refresh_admin as $refresh ) {
                  ?>
                  selected_admin_iframe_ids.push("<?=$refresh?>"); // SELECTED admin iframes refreshed
                  <?php 
                  }
             
             }
         
         ?>
         

             // DONT INCLUDE CURRENT PAGE (OR IT WILL *ENDLESS LOOP* RELOAD IT) 
             var excluded_iframe = selected_admin_iframe_ids.indexOf("iframe_<?=$iframe_id?>");
             if ( excluded_iframe > -1 ) {
             selected_admin_iframe_ids.splice(excluded_iframe, 1); // 2nd parameter means remove one item only
             console.log('SKIPPING auto-refresh of current page iframe: "iframe_<?=$iframe_id?>" (array index = ' + excluded_iframe + ')');
             }

             
             selected_admin_iframe_ids.forEach(function(refresh_iframe) {
                  
                 
                 // Skip any corrupt interface config sections
                 if ( skip_corrupt_sections.includes(refresh_iframe) ) {
                 console.log('SKIPPING CORRUPT CONFIG SECTION IFRAME: ' + refresh_iframe + ' (in "<?=$iframe_id?>")');
                 }
                 // Skip any about:blank pages
                 else if ( parent.document.getElementById(refresh_iframe).contentWindow.location.href == 'about:blank' ) {
                 console.log('SKIPPING ABOUT:BLANK IFRAME: ' + refresh_iframe + ' (in "<?=$iframe_id?>")');
                 }
                 else {
                      
                 var refresh_url = update_url_param(parent.document.getElementById(refresh_iframe).contentWindow.location.href, 'refresh', 'auto');
                 
                 console.log('AUTO-REFRESHING (safely avoiding data submissions / runaway loops) CONFIG SECTION IFRAME: ' + refresh_iframe + ' ( ' + refresh_url + ' ) (in "<?=$iframe_id?>")');
                 
                 // Remove any POST data (so we don't get endless loops under certain conditions)
                 parent.document.getElementById(refresh_iframe).contentWindow.location.replace(refresh_url);
                 
                 
                      // Remove any POST data AGAIN, IN A DIFFERENT WAY (JUST TO BE SURE!)
                      if ( parent.document.getElementById(refresh_iframe).contentWindow.history.replaceState ) {
                      parent.document.getElementById(refresh_iframe).contentWindow.history.replaceState(null, null, refresh_url);
                      }

                 
                 }
                 
      
             });
                 
         <?php
         }
         ?>
         
         }
         
     }

         
         <?php
         // If we need to refresh an admin iframe, to show the updated data
         if ( isset($_GET['refresh']) && $_GET['refresh'] != 'none' && $_GET['refresh'] != 'auto' ) {
         ?>

         console.log('refreshing: <?=$_GET['refresh']?>');
         
         // Reload all flagged iframes after 3 seconds (to give any newly-revised ct_conf re-cache time to 'settle in')
         setTimeout(refresh_iframes, 3000);          
         
         <?php
         }
         elseif ( isset($_GET['refresh']) && $_GET['refresh'] == 'none' ) {
         ?>
         
         console.log('refreshing: <?=$_GET['refresh']?>');
         
         // RESET, SINCE REFRESH IS SET TO 'none'
         parent.admin_settings_save_init = false; 
         console.log('RESET parent.admin_settings_save_init ("<?=$iframe_id?>") = ' + parent.admin_settings_save_init);
              
         <?php
         }
         ?>
     
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
    
           <div class='footer_content'>
    
    <p class='align_center' style='margin: 15px;'><a href='javascript:scroll(0,0);' title='Return to the top of the page.'>Back To Top</a></p>
            	
    <div id="app_error_alert" style='display: none;'><?php echo $ct['alerts_gui_logs']; ?></div>
            	
    <p class='align_center'><a href='https://taoteh1221.github.io' target='_blank' title='Check for upgrades to the latest version here.'>Running <?=ucfirst($ct['app_edition'])?> Edition<?=( $ct['gen']->admin_logged_in() ? ' v' . $ct['app_version'] : '' )?></a>
    

<?php

require("templates/interface/php/wrap/wrap-elements/donation-links.php");
require("templates/interface/php/wrap/wrap-elements/help-faq-modal.php");

          
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

footer_banner(donations_notice_storage, 'Please show your appreciation for my crypto apps, IF you enjoy using them. Buying me a coffee / beer means WAY MORE to me than large donations. It\'s about <a href="https://taoteh1221.github.io/#donations" target="_BLANK">letting me know</a> you appreciate them / find them useful, NOT about me making money. Think of it as a PRIVATE app usage survey anon! :) All crypto addresses are bot-monitored (for balance changes) on active / well-secured / backed-up HD wallets...<a href="https://taoteh1221.github.io/#donations" target="_BLANK"><img height="200" src="templates/interface/media/images/donate-banner.png" alt="" class="image_border" /></a>');

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
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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
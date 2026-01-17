<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 
header('Content-type: text/html; charset=' . $ct['dev']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct['conf']['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $ct['app_host_address']);
}

?><!DOCTYPE html>

<html lang="en">

<!-- /*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */ -->

<?=( isset($ct['system_info']['portfolio_cookies']) ? '<!-- CURRENT COOKIES SIZE TOTAL: ' . $ct['var']->num_pretty( ($ct['system_info']['portfolio_cookies'] / 1000) , 2) . ' kilobytes -->' : '' )?>	

<head>
<?php
require("templates/interface/php/wrap/wrap-elements/head-tag-contents.php");
?>
</head>

<?php
if ( $is_iframe ) {
?>
<body class='iframe_wrapper'>
	      
<!-- IFRAME header.php END -->
<?php
}
else {
?>
<body>


<audio preload="metadata" id="audio_alert">
      <source src="templates/interface/media/audio/Intruder_Alert-SoundBible.com-867759995.mp3">
      <source src="templates/interface/media/audio/Intruder_Alert-SoundBible.com-867759995.ogg">
</audio>


<div id="primary_wrapper" class="wrapper">


<?php
require("templates/interface/php/wrap/wrap-elements/navigation-bars.php");
?>

    
    <!-- content body -->
    <div class='align_center' id='secondary_wrapper'>

    
    <span class='bitcoin local_storage_saved_notice'></span>

    
    <span class='red countdown_notice'></span>
				

    <script>
    
    // If the user had the sidebar closed last app load
    // MUST RUN IMMEDIATELY AFTER LOADING #secondary_wrapper START TAG,
    // AND BEFORE INIT.JS (so there is no 'flickering' closing the sidebar)
    if ( localStorage.getItem(sidebar_toggle_storage) == "closed" ) {
    toggle_sidebar();    
    }    
    
    </script>
        
        
        <div id='header_size_warning'></div>
  						
  						
  	   <!-- Alerts div centering wrapper -->
  	   <div id='alert_bell_wrapper' style='position:absolute; left: 0px; top: 0px; width: 100%; margin: 0px; padding: 0px;'>
  
  			<div id='alert_bell_area' class='hidden'>
 			<!-- alerts output dynamically here -->
  		     </div>
  	
  	   </div>
		
    
	 	<div class='align_center loading bitcoin' id='app_loading'>
	 	<img src="templates/interface/media/images/auto-preloaded/loader.gif" height='<?=round($set_ajax_loading_size * 20)?>' alt="" style='vertical-align: middle;' /> <span id='app_loading_span'>Loading...</span>
	 	</div>
	 
		
		<div class='align_left' id='content_wrapper'>
				
				<?php
				 

                     // If we are queued to run a UI alert that an upgrade is available, IF ADMIN LOGGED IN
                     // VAR MUST BE SET RIGHT BEFORE CHECK ON DATA FROM THIS CACHE FILE, AS IT CAN BE UPDATED #AFTER# APP INIT!
                     if ( file_exists($ct['base_dir'] . '/cache/events/upgrading/admin_ui_app_upgrade_alert.dat') && $ct['sec']->admin_logged_in() ) {
                     $admin_ui_app_upgrade_alert = json_decode( file_get_contents($ct['base_dir'] . '/cache/events/upgrading/admin_ui_app_upgrade_alert.dat') , true);
                     }    
                     
                     
                     // WAS upgraded recently UI alerts
                     if ( file_exists($ct['base_dir'] . '/cache/events/upgrading/any_ui_any_upgrade_alert.dat') ) {
                     $any_ui_any_upgrade_alert = json_decode( file_get_contents($ct['base_dir'] . '/cache/events/upgrading/any_ui_any_upgrade_alert.dat') , true);
                     }
                     
                
			      // Show the upgrade notice one time until the next reminder period
				 if ( isset($admin_ui_app_upgrade_alert) && $admin_ui_app_upgrade_alert['run'] == 'yes' ) {
				    
                     // Workaround for #VERY ODD# PHP v8.0.1 BUG, WHEN TRYING TO ECHO $admin_ui_app_upgrade_alert['message'] IN HEADER.PHP
                     // (so we render it in footer.php, near the end of rendering)
         			 $display_upgrade_alert = true;
    			
				?>
				    
                     <script>
                     // Render after page loads
                     $(document).ready(function(){
                     $('#ui_upgrade_message').html( $('#app_upgrade_alert').html() );
                     });
                     </script>
     	
         			<div class="alert alert-warning" role="alert">
           				<button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
             				<span aria-hidden="true">&times;</span>
           				</button>
         			  	<div id='ui_upgrade_message'></div>
         			</div>
				
			    <?php
				
         			 // Set to 'run' => 'no' 
         			 // (will automatically re-activate in upgrade-check.php at a later date, if another reminder is needed after X days)
         			 $admin_ui_app_upgrade_alert['run'] = 'no';
         						
         			 $ct['cache']->save_file($ct['base_dir'] . '/cache/events/upgrading/admin_ui_app_upgrade_alert.dat', json_encode($admin_ui_app_upgrade_alert, JSON_PRETTY_PRINT) );
     					
				 }
				 // Otherwise, IF we just upgraded to a new version, show an alert to user that they may need to
				 // refresh the page or clear the browser cache for any upgraded JS / CSS files to load properly
				 // (as long as this page visit isn't a major search engine, crawling the app pages)     
				 else if ( isset($any_ui_any_upgrade_alert) && $any_ui_any_upgrade_alert['run'] == 'yes' && stristr($_SERVER['HTTP_USER_AGENT'], 'googlebot') == false && stristr($_SERVER['HTTP_USER_AGENT'], 'bingbot') == false ) {
				?>
     	
     	
         			<div id='refresh_cache_upgrade_message' class="alert alert-warning" role="alert" style='display: none;'>
           				<button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
             				<span aria-hidden="true">&times;</span>
           				</button>
         			  	<div>
         			  	
         			  	Open Crypto Tracker OR some of it's plugins were recently upgraded / downgraded. <?=( $ct['app_container'] == 'phpdesktop' || $ct['app_container'] == 'phpbrowserbox' ? 'Please RESTART this app (shut down / start back up), if you have not done this already since the upgrade' : 'You MAY need to refresh / reload this page (with the recycle arrow button at the top of your browser), OR clear your browser temporary files cache within it\'s settings area' )?>, so any UPGRADED Javascript / CSS files can properly reload and run this app's interface. Otherwise, you MAY encounter visual styling / app functionality errors (until your browser cache refreshes on it's own).
         			  	
         			  	</div>
         			</div>
				 
                     <script>
                     // Render after page loads
                     $(document).ready(function(){
                     
                     // Make sure local storage data is parsed as an integer (for javascript to run math on it)
                     var upgrade_cache_refresh_last_notice = parseInt( localStorage.getItem(refresh_cache_upgrade_notice_storage) , 10);
                     
                     
                         // If it's been 3 days since last notice (or never), then show it / set time shown to local storage  
                         if ( isNaN(upgrade_cache_refresh_last_notice) || Date.now() >= (upgrade_cache_refresh_last_notice + 259200000) ) {
                         
                         <?php
                         // RESET info / bug report / donations banner, AND security notice banner to show again,
                         // IF this was an APP upgrade (so we re-show any NEW general notices,
                         // and remind on bug reports / donations)
				     if ( $any_ui_any_upgrade_alert['upgrade_mode'] == 'app' ) {
                         ?>
                         
                         localStorage.setItem(general_notice_storage, "upgraded");
                         localStorage.setItem(security_notice_storage, "upgraded");

                         <?php
				     }
                         ?>

                         $('#refresh_cache_upgrade_message').show();

                         localStorage.setItem(refresh_cache_upgrade_notice_storage, Date.now() );

                         }

                     
                     });
                     </script>
				
			    <?php
				
         			 // Set 'run' => 'no'
         			 $any_ui_any_upgrade_alert['run'] = 'no';
         			 $ct['cache']->save_file($ct['base_dir'] . '/cache/events/upgrading/any_ui_any_upgrade_alert.dat', json_encode($any_ui_any_upgrade_alert, JSON_PRETTY_PRINT) );
     					
				 }
				
				?>
		 
				<div id='background_loading' class='align_center loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <span id='background_loading_span' style='font-weight: bold !important;'></span></div>
		
					
<!-- PRIMARY header.php END -->
			

<?php
				 
	// If INITIAL CRON-RELATED cache setup has just started FOR NEW INSTALLS, BUT has NOT completed yet,
	// show a UI notice that background tasks take awhile, for the first few runs on new installs
	if (
	file_exists($ct['base_dir'] . '/cache/events/first_run/cron-first-run.dat')
	&& !file_exists($ct['base_dir'] . '/cache/events/first_run/charts-first-run.dat')
	) {
	?>
               				 
       <div style='margin-left: 3em !important;' class='bitcoin bitcoin_dotted'>
               	
               	
             <p>Initial data caching is being setup. This may take awhile during the first few <?=( $ct['app_edition'] == 'desktop' && $ct['conf']['power']['desktop_cron_interval'] > 0 ? 'emulated ' : '' )?><?=( $ct['ms_windows_server'] ? 'scheduled task' : 'cron job' )?> instances. This notice will disappear, when the data caching setup has been completed.</p> 

             
             <?php
             if ( $ct['app_edition'] == 'desktop' && $ct['conf']['power']['desktop_cron_interval'] > 0 ) {
             ?>
             <p>PRO TIP: ALWAYS stay in whatever area you're in (Admin / User / Plugin), when background tasks show as running near the top of this interface (as a separate notice from this one).</p>
             <?php
             }
             ?>

               	
       </div>

	<?php
	}
				 
}
?>
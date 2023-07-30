<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 
header('Content-type: text/html; charset=' . $ct_conf['power']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct_conf['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $app_host_address);
}

?><!DOCTYPE html>

<html lang="en">

<!-- /*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */ -->

<?=( isset($system_info['portfolio_cookies']) ? '<!-- CURRENT COOKIES SIZE TOTAL: ' . $ct_var->num_pretty( ($system_info['portfolio_cookies'] / 1000) , 2) . ' kilobytes -->' : '' )?>	

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
	 	<img src="templates/interface/media/images/auto-preloaded/loader.gif" height='57' alt="" style='vertical-align: middle;' /> <span id='app_loading_span'>Loading...</span>
	 	</div>
	 
		
		<div class='align_left' id='content_wrapper'>
				
				<?php

                     // If we are queued to run a UI alert that an upgrade is available
                     // VAR MUST BE SET RIGHT BEFORE CHECK ON DATA FROM THIS CACHE FILE, AS IT CAN BE UPDATED #AFTER# APP INIT!
                     if ( file_exists($base_dir . '/cache/events/ui_upgrade_alert.dat') ) {
                     $ui_upgrade_alert = json_decode( file_get_contents($base_dir . '/cache/events/ui_upgrade_alert.dat') , true);
                     }
                
                
			      // show the upgrade notice one time until the next reminder period, IF ADMIN LOGGED IN
				 if ( isset($ui_upgrade_alert) && $ui_upgrade_alert['run'] == 'yes' && $ct_gen->admin_logged_in() ) {
				    
                     // Workaround for #VERY ODD# PHP v8.0.1 BUG, WHEN TRYING TO ECHO $ui_upgrade_alert['message'] IN HEADER.PHP
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
				
         			 // Set back to 'run' => 'no' 
         			 // (will automatically re-activate in upgrade-check.php at a later date, if another reminder is needed after X days)
         			 $ui_upgrade_alert['run'] = 'no';
         						
         			 $ct_cache->save_file($base_dir . '/cache/events/ui_upgrade_alert.dat', json_encode($ui_upgrade_alert, JSON_PRETTY_PRINT) );
     					
				 }
				 // Otherwise, IF we just upgraded to a new version, show an alert to user that they may need to
				 // refresh the page or clear the browser cache for any upgraded JS / CSS files to load properly
				 // (as long as this page visit isn't a major search engine, crawling the app pages)
				 elseif (
				 isset($cached_app_version)
				 && trim($cached_app_version) != ''
				 && trim($cached_app_version) != $app_version
				 && stristr($_SERVER['HTTP_USER_AGENT'], 'googlebot') == false
				 && stristr($_SERVER['HTTP_USER_AGENT'], 'bingbot') == false
				 ) {
				 ?>
				 
                     <script>
                     // Render after page loads
                     $(document).ready(function(){
                     
                     // Make sure local storage data is parsed as an integer (for javascript to run math on it)
                     var upgrade_cache_refresh_last_notice = parseInt( localStorage.getItem(refresh_cache_upgrade_notice_storage) , 10);
                     
                         // If it's been 3 days since last notice (or never), then show it    
                         if ( Date.now() > ( upgrade_cache_refresh_last_notice + 259200000 ) ) {
                         $('#refresh_cache_upgrade_message').show();
                         localStorage.setItem(refresh_cache_upgrade_notice_storage, Date.now() );
                         }
                     
                     });
                     </script>
     	
     	
         			<div id='refresh_cache_upgrade_message' class="alert alert-warning" role="alert" style='display: none;'>
           				<button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
             				<span aria-hidden="true">&times;</span>
           				</button>
         			  	<div>
         			  	
         			  	It appears you recently upgraded Open Crypto Tracker. You MAY need to refresh / reload this page (with the recycle arrow button at the top of your browser), OR clear your browser temporary files cache within it's settings area, so any UPGRADED Javascript / CSS files can properly display and run this app's interface. Otherwise, you MAY encounter errors (until your browser cache refreshes on it's own).
         			  	
         			  	</div>
         			</div>
				
			     <?php
				}
				
				?>
		 
				<div id='background_loading' class='align_center loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <span id='background_loading_span'></span></div>
		
					
<!-- PRIMARY header.php END -->
			

<?php
}
?>
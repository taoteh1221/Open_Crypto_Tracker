<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 
header('Content-type: text/html; charset=' . $ct_conf['dev']['charset_default']);

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
    

        <?php
        if ( $app_edition == 'desktop' ) {
        ?>
        
        <div class='blue' id='change_font_size'>
        
        <img id="zoom_info" src="templates/interface/media/images/info-red.png" alt="" width="30" style="position: relative; right: -5px;" />
        
        Zoom (<span id='zoom_show_ui'></span>): <span id='minusBtn' class='red'>-</span> <span id='plusBtn' class='green'>+</span>
        
        </div>
  
        
        <script>
        
        
        		
        			var zoom_info_content = '<h5 class="yellow tooltip_title">Desktop Edition Page Zoom</h5>'
        			
        			+'<p class="coin_info" style="max-width: 600px; white-space: normal;">This zoom feature allows Desktop Editions to zoom the app interface to be larger or smaller.</p>'
        			
        			+'<p class="coin_info bitcoin" style="max-width: 600px; white-space: normal;">Chart crosshairs and tooltip windows may be significantly off-center, if you go too far above or below the 100% zoom level. Hopefully someday we will have a fix for this, but for now just be aware of what effects the current zoom feature has on the app.</p>'
        			
        			+'<p class="coin_info red" style="max-width: 600px; white-space: normal;">We depend on the 3rd-party Open Source project <a href="https://github.com/cztomczak/phpdesktop" target="_blank">PHPdesktop</a>, for the Desktop Editions.</p>'
        			
        			+'<?=( $app_platform == 'windows' ? '<p class="coin_info red" style="max-width: 600px; white-space: normal;">The Windows Desktop Edition depends on a very outdated (March 2017) version of <a href="https://github.com/cztomczak/phpdesktop" target="_blank">PHPdesktop</a>. It is HIGHLY RECOMMENDED to install <a href="https://www.apachefriends.org/" target="_blank">XAMPP for Windows</a> INSTEAD, and then unzip the <a href="https://github.com/taoteh1221/Open_Crypto_Tracker/releases/" target="_blank">Server Edition of Open Crypto Tracker</a> into "C\:/xampp/htdocs" (and visit "https://localhost" in your web browser). <br /><br />Additionally, if you double-click the file located at "C\:/xampp/htdocs/ADD-WIN10-SCHEDULER-JOB.bat", you can automatically setup a scheduled task to enable price charts / price alerts (see <a href="README.txt" target="_blank">README.txt</a> OR the Help section of this app for more information).</p>' : '' )?>';
        			
        		
        			$('#zoom_info').balloon({
        			html: true,
        			position: "left",
          			classname: 'balloon-tooltips',
        			contents: zoom_info_content,
        			css: {
        					fontSize: "<?=$default_font_size?>em",
        					minWidth: "450px",
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
        		
        
        
        </script>


        
        <?php
        }
        ?>
        
        
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
	 	
	 	<script>
	 	
        // For UX, set proper page zoom for 'loading...' and zoom GUI on desktop editions
        // (we can't set body zoom until it's fully loaded, which we do via init.js)
        if ( app_edition == 'desktop' ) {
            
             // Page zoom logic
             if ( localStorage.getItem(desktop_zoom_storage) && localStorage.getItem(desktop_zoom_storage) > 0 ) {
             currzoom = localStorage.getItem(desktop_zoom_storage);
             }
             else {
             currzoom = 100;
             }
            
        // Just zoom #app_loading and #change_font_size / show zoom level in GUI
        // (we'll reset them to 100% before we zoom the whole body in init.js)
        $('#change_font_size').css('zoom', ' ' + currzoom + '%');
        $('#app_loading').css('zoom', ' ' + currzoom + '%');
        $("#zoom_show_ui").html(currzoom + '%');
                         
        }
    
	 	</script>
	 
		
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
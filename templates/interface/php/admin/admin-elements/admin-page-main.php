<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

<div class='full_width_wrapper align_center'>

	<div id='admin_wrapper' class='align_center' style='margin: auto;'>
		
		
		<!-- #admin_tab_content START -->
		<div id='admin_tab_content'>
		  
		  
		  <div id="admin_general">
                	
                <script>
                original_page_title['admin_general'] = 'General'; // Nav logic
                </script>
	            
	            <h2 class='bitcoin page_title'>General</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_general_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_general" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_general')?>&section=general" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		
		
		  <div id="admin_asset_tracking">
                	
                <script>
                original_page_title['admin_asset_tracking'] = 'Asset Tracking'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>Asset Tracking</h2>

                <div class='full_width_wrapper'>

                   <div id='iframe_asset_tracking_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_asset_tracking" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_asset_tracking')?>&section=asset_tracking" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_reset_backup_restore">
                	
                <script>
                original_page_title['admin_reset_backup_restore'] = 'Reset / Backup & Restore'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>Reset / Backup & Restore</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_reset_backup_restore_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_reset_backup_restore" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_reset_backup_restore')?>&section=reset_backup_restore" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		
		
		  <div id="admin_security">
                	
                <script>
                original_page_title['admin_security'] = 'Security'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>Security</h2>

                <div class='full_width_wrapper'>

                   <div id='iframe_security_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_security" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_security')?>&section=security" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		
		
		  <div id="admin_comms">
                	
                <script>
                original_page_title['admin_comms'] = 'Communications'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>Communications</h2>

                <div class='full_width_wrapper'>

                   <div id='iframe_comms_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_comms" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_comms')?>&section=comms" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_apis">
                	
                <script>
                original_page_title['admin_apis'] = 'APIs'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>APIs</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_apis_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_apis" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_apis')?>&section=apis" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_plugins">
                	
                <script>
                original_page_title['admin_plugins'] = 'Plugins'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>Plugins</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_plugins_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_plugins" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_plugins')?>&section=plugins" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_news_feeds">
                	
                <script>
                original_page_title['admin_news_feeds'] = 'News Feeds'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>News Feeds</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_news_feeds_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_news_feeds" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_news_feeds')?>&section=news_feeds" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_power_user">
                	
                <script>
                original_page_title['admin_power_user'] = 'Power User'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>Power User</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_power_user_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_power_user" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_power_user')?>&section=power_user" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_text_gateways">
                	
                <script>
                original_page_title['admin_text_gateways'] = 'Mobile Text Gateways'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>Mobile Text Gateways</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_text_gateways_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_text_gateways" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_text_gateways')?>&section=text_gateways" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_proxy">
                	
                <script>
                original_page_title['admin_proxy'] = 'Proxies'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>Proxies</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_proxy_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_proxy" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_proxy')?>&section=proxy" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_system_monitoring">
                	
                <script>
                original_page_title['admin_system_monitoring'] = 'System Monitoring'; // Nav logic
                </script>
	
	            <h2 class='bitcoin page_title'>System Monitoring</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_system_monitoring_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_system_monitoring" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_system_monitoring')?>&section=system_monitoring" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
                	<script>
                	
                	// IF a specific subsection page was flagged to show, show it instead of the index
                	if ( iframe_url(admin_iframe_url) != null ) {
                	     
                	var section_id = window.location.href.split('#')[1];
                	
                	var iframe_section_id = $("#" + section_id + " iframe").attr('id');
                	
                	console.log('iframe ID = ' + iframe_section_id);
     
                    console.log('iframe URL = ' + iframe_url(admin_iframe_url) );
                	     
                	load_iframe( iframe_section_id, iframe_url(admin_iframe_url) );
                	
                	}
                	
                	</script>  
                	
		  
		</div><!-- #admin_tab_content END -->


	</div> <!-- wrapper END -->
	
	
</div> <!-- admin index full_width_wrapper END -->


<br clear="all" />



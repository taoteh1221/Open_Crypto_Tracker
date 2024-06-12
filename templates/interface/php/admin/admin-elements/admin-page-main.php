<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

<div class='full_width_wrapper align_center'>

	<div id='admin_wrapper' class='align_center' style='margin: auto;'>
		
		
		<!-- #admin_tab_content START -->
		<div id='admin_tab_content'>
		  
		  
		  <div id="admin_general">
	            
	            <h2 class='bitcoin page_title'>General</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_general_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_general" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_general')?>&section=general" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		
		
		  <div id="admin_comms">
	
	            <h2 class='bitcoin page_title'>Communications</h2>

                <div class='full_width_wrapper'>

                   <div id='iframe_comms_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_comms" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_comms')?>&section=comms" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_ext_apis">
	
	            <h2 class='bitcoin page_title'>External APIs</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_ext_apis_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_ext_apis" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_ext_apis')?>&section=ext_apis" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_webhook_int_api">
	
	            <h2 class='bitcoin page_title'>Internal API / Webhook</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_webhook_int_api_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_webhook_int_api" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_webhook_int_api')?>&section=webhook_int_api" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_proxy">
	
	            <h2 class='bitcoin page_title'>Proxies</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_proxy_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_proxy" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_proxy')?>&section=proxy" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		
		
		  <div id="admin_security">
	
	            <h2 class='bitcoin page_title'>Security</h2>

                <div class='full_width_wrapper'>

                   <div id='iframe_security_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_security" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_security')?>&section=security" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_portfolio_assets">
	
	            <h2 class='bitcoin page_title'>Portfolio Assets</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_portfolio_assets_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_portfolio_assets" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_portfolio_assets')?>&section=portfolio_assets" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_charts_alerts">
	
	            <h2 class='bitcoin page_title'>Price Alerts / Charts</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_charts_alerts_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_charts_alerts" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_charts_alerts')?>&section=charts_alerts" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_plugins">
	
	            <h2 class='bitcoin page_title'>Plugins</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_plugins_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_plugins" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_plugins')?>&section=plugins" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_power_user">
	
	            <h2 class='bitcoin page_title'>Power User</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_power_user_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_power_user" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_power_user')?>&section=power_user" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_news_feeds">
	
	            <h2 class='bitcoin page_title'>News Feeds</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_news_feeds_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_news_feeds" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_news_feeds')?>&section=news_feeds" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_text_gateways">
	
	            <h2 class='bitcoin page_title'>Mobile Text Gateways</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_text_gateways_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_text_gateways" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_text_gateways')?>&section=text_gateways" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_system_stats">
	
	            <h2 class='bitcoin page_title'>System Stats</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_system_stats_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_system_stats" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_system_stats')?>&section=system_stats" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_access_stats">
	
	            <h2 class='bitcoin page_title'>Access Stats</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_access_stats_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_access_stats" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_access_stats')?>&section=access_stats" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_logs">
	
	            <h2 class='bitcoin page_title'>App Logs</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_logs_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_logs" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_logs')?>&section=logs" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div id="admin_reset_backup_restore">
	
	            <h2 class='bitcoin page_title'>Reset / Backup & Restore</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_reset_backup_restore_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_reset_backup_restore" src="admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_reset_backup_restore')?>&section=reset_backup_restore" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
                	<script>
                	
                	// IF a specific subsection page was flagged to show, show it instead of the index
                	if ( iframe_url(admin_iframe_url) != null ) {
                	     
                	var section_id = window.location.href.split('#')[1];
                	
                	var iframe_section_id = $("#" + section_id + " iframe").attr('id');
                	
                	//console.log('iframe ID = ' + iframe_section_id);
     
                    //console.log('iframe URL = ' + iframe_url(admin_iframe_url) );
                	     
                	load_iframe( iframe_section_id, iframe_url(admin_iframe_url) );
                	
                	}
                	
                	</script>  
                	
		  
		</div><!-- #admin_tab_content END -->


	</div> <!-- wrapper END -->
	
	
</div> <!-- admin index full_width_wrapper END -->


<br clear="all" />



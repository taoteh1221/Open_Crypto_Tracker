<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>

<div class='full_width_wrapper align_center'>

	<div id='admin_wrapper' class='align_center' style='margin: auto;'>
		
		
		<!-- #admin_tab_content START -->
		<div id='admin_tab_content' class="tab-content">
		  
		  
		  <div class="tab-pane" id="admin_general" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>General</h2>

                <div>
                	
                   <div id='iframe_general_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_general" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_general')?>&section=general" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		
		
		  <div class="tab-pane" id="admin_comms" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Communications</h2>

                <div>

                   <div id='iframe_comms_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_comms" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_comms')?>&section=comms" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_other_api" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Other APIs</h2>

                <div>
                	
                   <div id='iframe_other_api_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_other_api" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_other_api')?>&section=other_api" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_proxy" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Proxies</h2>

                <div>
                	
                   <div id='iframe_proxy_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_proxy" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_proxy')?>&section=proxy" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		
		
		  <div class="tab-pane" id="admin_security" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Security</h2>

                <div>

                   <div id='iframe_security_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_security" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_security')?>&section=security" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_portfolio_assets" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Portfolio Assets</h2>

                <div>
                	
                   <div id='iframe_portfolio_assets_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_portfolio_assets" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_portfolio_assets')?>&section=portfolio_assets" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_charts_alerts" role="tabpanel">
	
	            <h2 class='bitcoin page_title'><?=( $ct_conf['gen']['asset_charts_toggle'] == 'on' ? 'Charts and ' : 'Price ' )?>Alerts</h2>

                <div>
                	
                   <div id='iframe_charts_alerts_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_charts_alerts" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_charts_alerts')?>&section=charts_alerts" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_plugins" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Plugins</h2>

                <div>
                	
                   <div id='iframe_plugins_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_plugins" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_plugins')?>&section=plugins" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_power_user" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Power User</h2>

                <div>
                	
                   <div id='iframe_power_user_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_power_user" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_power_user')?>&section=power_user" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_int_api" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Internal API</h2>

                <div>
                	
                   <div id='iframe_int_api_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_int_api" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_int_api')?>&section=int_api" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_webhook" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Webhook</h2>

                <div>
                	
                   <div id='iframe_webhook_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_webhook" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_webhook')?>&section=webhook" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_text_gateways" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Text Gateways</h2>

                <div>
                	
                   <div id='iframe_text_gateways_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_text_gateways" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_text_gateways')?>&section=text_gateways" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_developer_only" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Developer Only</h2>

                <div>
                	
                   <div id='iframe_developer_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_developer" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_developer')?>&section=developer" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_system_stats" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>System Stats</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_system_stats_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_system_stats" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_system_stats')?>&section=system_stats" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_access_stats" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Access Stats</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_access_stats_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_access_stats" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_access_stats')?>&section=access_stats" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_logs" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>App Logs</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_logs_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_logs" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_logs')?>&section=logs" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_backup_restore" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Backup / Restore</h2>

                <div>
                	
                   <div id='iframe_backup_restore_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_backup_restore" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_backup_restore')?>&section=backup_restore" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_reset" role="tabpanel">
	
	            <h2 class='bitcoin page_title'>Reset</h2>

                <div>
                	
                   <div id='iframe_reset_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_reset" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_reset')?>&section=reset" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		</div><!-- #admin_tab_content END -->


	</div> <!-- wrapper END -->
	
	
</div> <!-- admin index full_width_wrapper END -->


<br clear="all" />



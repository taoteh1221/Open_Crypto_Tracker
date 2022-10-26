<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>

<div class='full_width_wrapper align_center'>

	<div id='admin_wrapper' class='align_center' style='margin: auto;'>
	

		<!-- set data-width="full", to have the tab width be 100% of the screen -->
		<ul class="nav nav-tabs-vertical align_center" id="admin_tabs" role="tablist">
		  <li class="nav-item">
			<a class="nav-link admin_change_width active" data-toggle="tab" data-width="fixed_max" href="#admin_security" role="tab" aria-controls="admin_security">Security</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_comms" role="tab" aria-controls="admin_comms">Communications</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_general" role="tab" aria-controls="admin_general">General</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_portfolio_assets" role="tab" aria-controls="admin_portfolio_assets">Portfolio Assets</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_charts_alerts" role="tab" aria-controls="admin_charts_alerts"><?=( $ct_conf['gen']['asset_charts_toggle'] == 'on' ? 'Charts and ' : 'Price ' )?>Alerts</a>
		  </li>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_plugins" role="tab" aria-controls="admin_plugins">Plugins</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_power_user" role="tab" aria-controls="admin_power_user">Power User</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_text_gateways" role="tab" aria-controls="admin_text_gateways">Text Gateways</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_proxy" role="tab" aria-controls="admin_proxy">Proxy</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_developer_only" role="tab" aria-controls="admin_developer_only">Developer Only</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_api" role="tab" aria-controls="admin_api">API</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_webhook" role="tab" aria-controls="admin_webhook">Webhook</a>
		  </li>
		  <li class="nav-item" id="sys_stats_admin_link">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="full" href="#admin_system_stats" role="tab" aria-controls="admin_system_stats">System Stats<img id='sys_stats_admin_link_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' /></a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="full" href="#admin_access_stats" role="tab" aria-controls="admin_access_stats">Access Stats</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="full" href="#admin_logs" role="tab" aria-controls="admin_logs">App Logs</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_backup_restore" role="tab" aria-controls="admin_backup_restore">Backup / Restore</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_reset" role="tab" aria-controls="admin_reset">Reset</a>
		  </li>
		</ul>
		
		
		<!-- #admin_tab_content START -->
		<div id='admin_tab_content' class="tab-content align_left">
		
		  <div class="tab-pane active" id="admin_security" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Security</h2>

                <div class='max_1200px_wrapper'>

                   <div id='iframe_security_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_security" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_security')?>&section=security" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		
		  <div class="tab-pane" id="admin_comms" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Communications</h2>

                <div class='max_1200px_wrapper'>

                   <div id='iframe_comms_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_comms" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_comms')?>&section=comms" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_general" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>General</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_general_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_general" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_general')?>&section=general" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_portfolio_assets" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Portfolio Assets</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_portfolio_assets_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_portfolio_assets" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_portfolio_assets')?>&section=portfolio_assets" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_charts_alerts" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Charts and Alerts</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_charts_alerts_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_charts_alerts" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_charts_alerts')?>&section=charts_alerts" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_plugins" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Plugins</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_plugins_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_plugins" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_plugins')?>&section=plugins" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_power_user" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Power User</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_power_user_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_power_user" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_power_user')?>&section=power_user" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_text_gateways" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Text Gateways</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_text_gateways_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_text_gateways" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_text_gateways')?>&section=text_gateways" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_proxy" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Proxy</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_proxy_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_proxy" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_proxy')?>&section=proxy" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_developer_only" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Developer Only</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_developer_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_developer" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_developer')?>&section=developer" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_api" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>API</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_api_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_api" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_api')?>&section=api" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_webhook" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Webhook</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_webhook_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_webhook" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_webhook')?>&section=webhook" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_system_stats" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>System Stats</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_system_stats_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_system_stats" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_system_stats')?>&section=system_stats" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_access_stats" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Access Stats</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_access_stats_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_access_stats" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_access_stats')?>&section=access_stats" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_logs" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>App Logs</h2>

                <div class='full_width_wrapper'>
                	
                   <div id='iframe_logs_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_logs" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_logs')?>&section=logs" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_backup_restore" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Backup / Restore</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_backup_restore_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_backup_restore" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_backup_restore')?>&section=backup_restore" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		  <div class="tab-pane" id="admin_reset" role="tabpanel">
	
	            <h2 class='bitcoin admin_title'>Reset</h2>

                <div class='max_1200px_wrapper'>
                	
                   <div id='iframe_reset_loading' class='align_center loading iframe_loading_placeholder bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> Loading...<span id='background_loading_span'></span></div>
                
                	<iframe id="iframe_reset" src="admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_reset')?>&section=reset" loading="lazy" frameborder="0" class="admin_iframe"></iframe>
                	
                </div> 
		  
		  </div>
		  
		  
		</div><!-- #admin_tab_content END -->


	</div> <!-- wrapper END -->
	
	
</div> <!-- admin index full_width_wrapper END -->


<br clear="all" />



<?php
			
// Warning (minimal, just as link title on the 'refresh' link) if price data caching set too low
if ( $ct['conf']['power']['last_trade_cache_time'] < 4 ) {
$refresh_link_documentation = 'Use this to Refresh / Reload the app data. Refreshing data too frequently may cause API request refusals, especially if request caching settings are too low. It is recommended to use this refresh feature sparingly with lower or disabled cache settings. The current real-time exchange data re-cache (refresh from live data instead of cached data) setting in the Admin Config GENERAL section is set to '. $ct['conf']['power']['last_trade_cache_time'] . ' minute(s). A setting of 4 or higher assists in avoiding temporary IP blocking / throttling by exchanges.';
}
else {
$refresh_link_documentation = 'The current real-time exchange data re-cache (refresh from live data instead of cached data) setting in the Admin Config GENERAL section is set to '. $ct['conf']['power']['last_trade_cache_time'] . ' minute(s).';
}
			
?>  


<!-- collapsed sidebar -->
<div id="collapsed_sidebar">
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-hamburger-menu-96-<?=$sel_opt['theme_selected']?>.png' width='45' class='nav-image sidebar_toggle' id="sidebar_hamburger" title='Show FULL SIZED side bar.' /></div>
   
   
   <div class="smallnav_spacer"></div>

   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/notification-<?=$sel_opt['theme_selected']?>-line.png' width='45' border='0' class='nav-image toggle_alerts' title='View app alerts.' /></div>

   
   <div class="smallnav_spacer"></div>
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-add-96.png' width='45' border='0' id='' class='nav-image btn-number' data-type="plus" data-field="quant_font_percent" title='Increase text size.' /></div>

   
   <div class="smallnav_spacer"></div>
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-minus-96.png' width='45' border='0' id='' class='nav-image btn-number' data-type="minus" data-field="quant_font_percent" title='Decrease text size.' /></div>

   
   <div class="smallnav_spacer"></div>

   
   <div class="align_center"><a href='javascript:app_reloading_check();' class='bitcoin' style='font-weight: bold;' title='Use this to Refresh / Reload the app data. <?=$refresh_link_documentation?>'><img src='templates/interface/media/images/auto-preloaded/icons8-refresh-64-<?=$sel_opt['theme_selected']?>.png' class='nav-image' width='45' border='0' /></a></div>

   
   <div class="smallnav_spacer"></div>

   
   <div class="align_center" id='pm_link_icon_div'><a id='pm_link2' class='bitcoin' onclick='privacy_mode(true);' title='Turn privacy mode ON. This encrypts / hides RENDERED personal portfolio data with the PIN you setup (BUT DOES #NOT# encrypt RAW source code). It ALSO disables opposite-clicking / data submission, and logs out any active admin login.'><img src='templates/interface/media/images/auto-preloaded/icons8-eye-100-<?=$sel_opt['theme_selected']?>.png' class='nav-image' width='45' border='0' /></a></div>
        
        
   <?php
   if ( $ct['gen']->admin_logged_in() ) {
   ?>

   
   <div class="smallnav_spacer"></div>
   
   <div class="align_center"><a href="?logout=1&admin_hashed_nonce=<?=$ct['gen']->admin_hashed_nonce('logout')?>"><img src='templates/interface/media/images/auto-preloaded/icons8-logout-58-<?=$sel_opt['theme_selected']?>.png' class='nav-image' width='45' border='0' title='Logout of Admin Config area.' /></a></div>
   
   <?php
   }
   ?>

   
   <div class="smallnav_spacer"></div>
   
   
   <!-- Admin area -->
     <div class="admin-nav-wrapper btn-group dropend">
     
          <a href="admin.php" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><img src='templates/interface/media/images/auto-preloaded/icons8-services-100-<?=$sel_opt['theme_selected']?>.png' class='nav-image' width='45' border='0' title='Admin Config area.' /></a>
     
          <ul class="admin-nav all-nav dropdown-menu" style="" role="tablist">

        
                    <?php
                    if ( $ct['gen']->admin_logged_in() ) {
                         
                    // Links won't work in NON-ADMIN AREAS without this logic
                    $content_toggle = ( preg_match("/admin\.php/i", $_SERVER['REQUEST_URI']) ? 'data-bs-toggle="tab"' : '' );
                    
                    ?>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_general" href="admin.php#admin_general" title='General admin settings.'>General</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_comms" href="admin.php#admin_comms" title='Configure email / text / Alexa / Telegram communications, and more.'>Communications</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_ext_apis" href="admin.php#admin_ext_apis" title='Configure options for external third party APIs.'>External APIs</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_proxy" href="admin.php#admin_proxy" title='Enable / disable proxy services (for privacy connecting to third party APIs).'>Proxies</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_security" href="admin.php#admin_security" title='Admin area for all security-related settings.'>Security</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_portfolio_assets" href="admin.php#admin_portfolio_assets" title='Add / remove / update the available assets for portfolio tracking.'>Portfolio Assets</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_charts_alerts" href="admin.php#admin_charts_alerts" title='Configure <?=( $ct['conf']['gen']['asset_charts_toggle'] == 'on' ? 'Charts / Price' : 'Price' )?> Alerts'><?=( $ct['conf']['gen']['asset_charts_toggle'] == 'on' ? 'Charts / Price' : 'Price' )?> Alerts</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_plugins" href="admin.php#admin_plugins" title='Manage plugin addons for this app.'>Plugins</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_power_user" href="admin.php#admin_power_user" title='Power user settings (for advanced users).'>Power User</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_news_feeds" href="admin.php#admin_news_feeds" title='Edit the news feeds for the news page.'>News Feeds</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_webhook_int_api" href="admin.php#admin_webhook_int_api" title='Documentation / keys for using the built-in API to connect to other apps.'>Webhook / Internal API</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_text_gateways" href="admin.php#admin_text_gateways" title='Add / remove / update the mobile text gateways available, to use for mobile text communications.'>Text Gateways</a>
                    </li>


                    <li class='sys_stats_admin_link'>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_system_stats" href="admin.php#admin_system_stats" title='View system stats, to keep track of your app server system health.'>System Stats<img class='tooltip_style_control sys_stats_admin_link_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' /></a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_access_stats" href="admin.php#admin_access_stats" title='View user access stats, to track IP addresses / Browser versions of who has been using your app.'>Access Stats</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_logs" href="admin.php#admin_logs" title='View app logs, to check for potential issues with your app configuration.'>App Logs</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_reset_backup_restore" href="admin.php#admin_reset_backup_restore" title='Reset, backup, or restore your app configuration settings / chart data / etc.'>Reset / Backup & Restore</a>
                    </li>


                    <?php
                    }
                    else {
                    ?>
                    
                    <li>
                    <a class="dropdown-item" href="admin.php"title='Login to the admin area.'>Login</a>
                    </li>

                    <?php
                    }
                    ?>
                    

          </ul>
      
     </div>

   
   <div class="smallnav_spacer"></div>
   
   
   <!-- User area -->
     <div class="user-nav-wrapper btn-group dropend">
     
          <a href="index.php" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><img src='templates/interface/media/images/auto-preloaded/icons8-user-96-<?=$sel_opt['theme_selected']?>.png' class='nav-image' width='45' border='0' title='User area.' /></a>
     
          <ul class="user-nav all-nav dropdown-menu" style="">
          
           <li><a class="dropdown-item" href='index.php#portfolio' title='View your portfolio.'>My Portfolio</a></li>
           
           <li class='update_portfolio_link'><a class="dropdown-item update_portfolio_link" id='update_link_1' href='index.php#update' title='Update your portfolio data.'>Update Portfolio</a></li>

           <li><a class="dropdown-item" href='index.php#settings' title='Update your user settings.'>User Settings</a></li>			
           
           <?php
		 if ( $ct['conf']['gen']['asset_charts_toggle'] == 'on' ) {
		 ?>
           <li><a class="dropdown-item" href='index.php#charts' title='View price charts.'>Price Charts</a></li>
		 <?php
		 }
		 ?>
		 
           <li><a class="dropdown-item" href='index.php#news' title='View News Feeds.'>News Feeds</a></li>
           
           <li><a class="dropdown-item" href='index.php#tools' title='Use various crypto tools.'>Tools</a></li>

           <li><a class="dropdown-item" href='index.php#mining' title='Calculate coin mining profits.'>Staking / Mining</a></li>

           <li><a class="dropdown-item" href='index.php#resources' title='View 3rd party resources.'>Other Resources</a></li>
           
          </ul>
      
     </div>
   
   
   <div class="smallnav_spacer"></div>
   
   
   <div class="align_center"><a href="javascript: return false;" class="modal_style_control show_help_faq" title='Get help with running and setting up this app.'><img src='templates/interface/media/images/auto-preloaded/icons8-questions-100-<?=$sel_opt['theme_selected']?>.png' class='nav-image' width='45' border='0' title='Get help with running and setting up this app.' /></a></div>


   <br clear='all' />

</div>
<!-- END collapsed sidebar -->
   

<!-- Regular sidebar -->
<nav id="sidebar">
    
    
        <!-- alerts toggle icon -->
        <img src='templates/interface/media/images/auto-preloaded/notification-<?=$sel_opt['theme_selected']?>-line.png' height='45' border='0' id='sb_alerts' class='nav-image toggle_alerts' title='View app alerts.' />
        
        <!-- close sidebar toggle icon -->
        <img src='templates/interface/media/images/auto-preloaded/icons8-close-window-50-<?=$sel_opt['theme_selected']?>.png' class='nav-image sidebar_toggle' id="dismiss" title='Show COMPACT side bar.' />


        <div class="sidebar-top">
        
             <div class="plus_minus">
              <div class="input-group" style='width: 120px;'>
                    <span class="input-group-btn">
                       <button type="button" class="btn btn-danger btn-number"  data-type="minus" data-field="quant_font_percent" title='Decrease text size.'>
                          <span class="plus_minus_buttons"> - </span>
                        </button>
                    </span>
                    
                    
                    <div class="form-floating">
                    
                    <input type="text" name="quant_font_percent" id="quant_font_percent" class="form-control input-number" value="<?=($set_font_size * 100)?>" min="<?=($ct['dev']['min_font_resize'] * 100)?>" max="<?=($ct['dev']['max_font_resize'] * 100)?>" onchange='
                    
               	if ( !get_cookie("font_size") ) {
               	font_size_cookie = confirm("This feature requires using cookie data.\n\nPRO TIP: If your web browser has a \"zoom\" feature, try that first for better results.");
               	}
               	else {
               	font_size_cookie = true;
               	}
               			
               	if ( font_size_cookie == true && is_int(this.value) != false ) {
                    interface_font_percent(this.value);
               	}
               	else {
                    $(this).val(pref_font_size);
                    return false;
               	}
                    
                    '>
                    <label class='pl_mn_lab' for="quant_font_percent">Text %</label>
                    
                    </div>
                   
                   
         
                   <script>
                   var pref_font_size = $('#quant_font_percent').val();
                   </script>
                   
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="quant_font_percent" title='Increase text size.'>
                            <span class="plus_minus_buttons"> + </span>
                        </button>
                    </span>
                </div>
          	<p></p>
             </div>

        </div>
        

        <div class="sidebar-header">
            <h1 class='align_center' style='margin: 0px;'>Open Crypto Tracker</h1>
        </div>
        
        <div class="sidebar-slogan align_center">
        
        <i>Privately</i> track <i>ANY</i> Crypto on your home network or internet website, for <a class='sidebar_secondary_link' href='https://taoteh1221.github.io/' target='_blank'><i>FREE</i></a>.
        
        </div>


        <ul id='sidebar_menu' class="list-unstyled components">
        
            
            <li class='sidebar-item'>
                <a href='javascript:app_reloading_check();' class='bitcoin' title='Use this to Refresh / Reload the app data. <?=$refresh_link_documentation?>'>Refresh Data</a>
            </li>
        
            
            <li class='sidebar-item'>
                <a id='pm_link' class='bitcoin pm_link' onclick='privacy_mode(true);' title='Turn privacy mode ON. This encrypts / hides RENDERED personal portfolio data with the PIN you setup (BUT DOES #NOT# encrypt RAW source code). It ALSO disables opposite-clicking / data submission, and logs out any active admin login.'>Privacy Mode: Off</a>
            </li>
            
            
            <?php
            if ( $ct['gen']->admin_logged_in() ) {
            ?>
   
            <li class='sidebar-item'>
                <a href="?logout=1&admin_hashed_nonce=<?=$ct['gen']->admin_hashed_nonce('logout')?>" class="bitcoin" title='Logout from the admin area.'>Admin Logout</a>
            </li>
            
            <?php
            }
            ?>
            
            
            <!-- Admin area -->
            <li class="admin-nav-wrapper">
                
                <a href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle <?=( preg_match("/admin\.php/i", $_SERVER['REQUEST_URI']) ? 'active' : '' )?>">Admin Area</a>
                
                <ul class="admin-nav all-nav collapse list-unstyled" id="adminSubmenu" role="tablist">
                
        
                    <?php
                    if ( $ct['gen']->admin_logged_in() ) {
                         
                    // Links won't work in NON-ADMIN AREAS without this logic
                    $content_toggle = ( preg_match("/admin\.php/i", $_SERVER['REQUEST_URI']) ? 'data-bs-toggle="tab"' : '' );
                    
                    ?>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_general" href="admin.php#admin_general" title='General admin settings.'>General</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_comms" href="admin.php#admin_comms" title='Configure email / text / Alexa / Telegram communications, and more.'>Communications</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_ext_apis" href="admin.php#admin_ext_apis" title='Configure options for external third party APIs.'>External APIs</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_proxy" href="admin.php#admin_proxy" title='Enable / disable proxy services (for privacy connecting to third party APIs).'>Proxies</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_security" href="admin.php#admin_security" title='Admin area for all security-related settings.'>Security</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_portfolio_assets" href="admin.php#admin_portfolio_assets" title='Add / remove / update the available assets for portfolio tracking.'>Portfolio Assets</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_charts_alerts" href="admin.php#admin_charts_alerts" title='Configure <?=( $ct['conf']['gen']['asset_charts_toggle'] == 'on' ? 'Charts / Price' : 'Price' )?> Alerts'><?=( $ct['conf']['gen']['asset_charts_toggle'] == 'on' ? 'Charts / Price' : 'Price' )?> Alerts</a>
                    </li>
                    
                    
                        <?php
                        
                        // Plugin link(s)
                        $navbar_plugins = array();

                        foreach ( $activated_plugins['ui'] as $plugin_key => $unused ) {
                        $navbar_plugins[$plugin_key] = 1;
                        }

                        foreach ( $activated_plugins['cron'] as $plugin_key => $unused ) {
                        $navbar_plugins[$plugin_key] = 1;
                        }

                        foreach ( $activated_plugins['webhook'] as $plugin_key => $unused ) {
                        $navbar_plugins[$plugin_key] = 1;
                        }
                        
                        if ( sizeof($navbar_plugins) > 0 ) {
                        ksort($navbar_plugins); // Alphabetical order (for admin UI)
                    ?>
                    
                    <!-- START custom 3-deep config -->
                    <li class="nav-item dropdown custom-3deep open-first">
                        
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="admin.php#admin_plugins" onclick='javascript:load_iframe("iframe_plugins")' title='Manage plugin addons for this app.'>Plugins</a>
                        
                        <ul class="dropdown-menu">
                        
                    <?php
                        }

                        foreach ( $navbar_plugins as $plugin_key => $unused ) {
                        ?>
                        
                        <li>
                        
                        <a class="dropdown-item" href="admin.php#admin_plugins" submenu-id="admin_plugins_<?=$plugin_key?>" onclick='javascript:load_iframe("iframe_plugins", "admin.php?iframe=<?=$ct['gen']->admin_hashed_nonce('iframe_' . $plugin_key)?>&plugin=<?=$plugin_key?>")' title='<?=$plug_conf[$plugin_key]['ui_name']?> plugin settings and documentation.'><?=$plug_conf[$plugin_key]['ui_name']?></a>
                        
                        </li>
                          <!-- <li><hr class="dropdown-divider"></li> -->
                          
                        <?php
                        }
                        
                        if ( sizeof($navbar_plugins) > 0 ) {
                        ?>
                        
                        </ul>
                        
                    </li>
                    <!-- END custom 3-deep config -->
                    
                    <?php
                        }
                        else {
                    ?>
                    
                    <!-- NO PLUGINS activated -->
                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_plugins" href="admin.php#admin_plugins" title='Manage plugin addons for this app.'>Plugins</a>
                    </li>

                    <?php
                    }
                    ?>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_power_user" href="admin.php#admin_power_user" title='Power user settings (for advanced users).'>Power User</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_news_feeds" href="admin.php#admin_news_feeds" title='Edit the news feeds for the news page.'>News Feeds</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_webhook_int_api" href="admin.php#admin_webhook_int_api" title='Documentation / keys for using the built-in API to connect to other apps.'>Webhook / Internal API</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_text_gateways" href="admin.php#admin_text_gateways" title='Add / remove / update the mobile text gateways available, to use for mobile text communications.'>Text Gateways</a>
                    </li>


                    <li class='sidebar-item nav-item sys_stats_admin_link'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_system_stats" href="admin.php#admin_system_stats" title='View system stats, to keep track of your app server system health.'>System Stats<img class='tooltip_style_control sys_stats_admin_link_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' /></a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_access_stats" href="admin.php#admin_access_stats" title='View user access stats, to track IP addresses / Browser versions of who has been using your app.'>Access Stats</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_logs" href="admin.php#admin_logs" title='View app logs, to check for potential issues with your app configuration.'>App Logs</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_reset_backup_restore" href="admin.php#admin_reset_backup_restore" title='Reset, backup, or restore your app configuration settings / chart data / etc.'>Reset / Backup & Restore</a>
                    </li>


                    <?php
                    }
                    else {
                    ?>
                    
                    <li class='sidebar-item'>
                        <a href="admin.php" title='Login to the admin area.'>Login</a>
                    </li>

                    <?php
                    }
                    ?>
                    
                    
                </ul>
                
            </li>
            
            
            <!-- User area -->
            <li class="user-nav-wrapper">
            
                <a href="#userSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle <?=( preg_match("/index\.php/i", $_SERVER['REQUEST_URI']) ? 'active' : '' )?>">User Area</a>
                
                <ul class="user-nav all-nav collapse list-unstyled" id="userSubmenu">
          
                <li class='sidebar-item'><a href='index.php#portfolio' title='View your portfolio.'>My Portfolio</a></li>
                
                <li class='sidebar-item update_portfolio_link'><a class='update_portfolio_link' id='update_link_2' href='index.php#update' title='Update your portfolio data.'>Update Portfolio</a></li>
     
                <li class='sidebar-item'><a href='index.php#settings' title='Update your user settings.'>User Settings</a></li>			
                
                <?php
     		 if ( $ct['conf']['gen']['asset_charts_toggle'] == 'on' ) {
     		 ?>
                <li class='sidebar-item'><a href='index.php#charts' title='View price charts.'>Price Charts</a></li>
     		 <?php
     		 }
     		 ?>
     		 
                <li class='sidebar-item'><a href='index.php#news' title='View News Feeds.'>News Feeds</a></li>
                
                <li class='sidebar-item'><a href='index.php#tools' title='Use various crypto tools.'>Tools</a></li>
     
                <li class='sidebar-item'><a href='index.php#mining' title='Calculate coin mining profits.'>Staking / Mining</a></li>
     
                <li class='sidebar-item'><a href='index.php#resources' title='View 3rd party resources.'>Other Resources</a></li>
                
                </ul>
                
            </li>
            
            
            <li class='sidebar-item'>
                <a href="javascript: return false;" class="modal_style_control show_help_faq blue" title='Get help with running and setting up this app.'>Help? / FAQ</a>
            </li>
            
            
        </ul>


</nav>
<!-- END regular sidebar -->


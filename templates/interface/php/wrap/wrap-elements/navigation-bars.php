<?php
			
// Warning (minimal, just as link title on the 'refresh' link) if price data caching set too low
if ( $ct['conf']['power']['last_trade_cache_time'] < 4 ) {
$refresh_link_documentation = 'Use this to Refresh / Reload the app data. Refreshing data too frequently may cause API request refusals, especially if request caching settings are too low. It is recommended to use this refresh feature sparingly with lower or disabled cache settings. The current real-time exchange data re-cache (refresh from live data instead of cached data) setting in the Admin Config POWER USER section is set to '. $ct['conf']['power']['last_trade_cache_time'] . ' minute(s). A setting of 4 or higher assists in avoiding temporary IP blocking / throttling by exchanges.';
}
else {
$refresh_link_documentation = 'The current real-time exchange data re-cache (refresh from live data instead of cached data) setting in the Admin Config POWER USER section is set to '. $ct['conf']['power']['last_trade_cache_time'] . ' minute(s).';
}
			
?>  


<!-- collapsed sidebar -->
<div id="collapsed_sidebar">
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-hamburger-menu-96-<?=$ct['sel_opt']['theme_selected']?>.png' width='45' class='nav-image sidebar_toggle' id="sidebar_hamburger" title='Show FULL SIZED side bar.' /></div>
   
   
   <div class="smallnav_spacer"></div>

   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/notification-<?=$ct['sel_opt']['theme_selected']?>-line.png' width='45' border='0' class='nav-image toggle_alerts' title='View app alerts.' /></div>

   
   <div class="smallnav_spacer"></div>
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-add-96.png' width='45' border='0' id='' class='nav-image btn-number' data-type="plus" data-field="quant_font_percent" title='Increase text size.' /></div>

   
   <div class="smallnav_spacer"></div>
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-minus-96.png' width='45' border='0' id='' class='nav-image btn-number' data-type="minus" data-field="quant_font_percent" title='Decrease text size.' /></div>

   
   <div class="smallnav_spacer"></div>
   
   
   <div class="align_center"><a href='javascript:app_reloading_check();' class='bitcoin' style='font-weight: bold;' title='Use this to Refresh / Reload the app data. <?=$refresh_link_documentation?>'><img src='templates/interface/media/images/auto-preloaded/icons8-refresh-64-<?=$ct['sel_opt']['theme_selected']?>.png' class='nav-image' width='45' border='0' /></a></div>

   
   <div class="smallnav_spacer"></div>
   
   <?php
   if ( $ct['gen']->admin_logged_in() && $is_admin ) {
   ?>
   
   <div class="align_center"><a href='javascript:' class='admin_settings_save settings_save bitcoin' style='font-weight: bold;' title='Save settings for this admin section.'><img src='templates/interface/media/images/auto-preloaded/icons8-save-100-<?=$ct['sel_opt']['theme_selected']?>.png' class='nav-image' width='45' border='0' /></a></div>

   
   <div class="smallnav_spacer"></div>
   
   <?php
   }
   else if ( !$is_admin ) {
   ?>
   
   <div class="align_center"><a href='javascript:' class='user_settings_save settings_save bitcoin' style='font-weight: bold;' title='Save settings for the user area.'><img src='templates/interface/media/images/auto-preloaded/icons8-save-100-<?=$ct['sel_opt']['theme_selected']?>.png' class='nav-image' width='45' border='0' /></a></div>

   
   <div class="smallnav_spacer"></div>
   
   <?php
   }
   ?>
   
   
   <div class="align_center" id='pm_link_icon_div'><a id='pm_link2' class='bitcoin' onclick='privacy_mode(true);' title='Turn privacy mode ON. This encrypts / hides RENDERED personal portfolio data with the PIN you setup (BUT DOES #NOT# encrypt RAW source code). It ALSO disables opposite-clicking / data submission, and logs out any active admin login.'><img src='templates/interface/media/images/auto-preloaded/icons8-eye-100-<?=$ct['sel_opt']['theme_selected']?>.png' class='nav-image' width='45' border='0' /></a></div>

   
   <div class="smallnav_spacer"></div>
   
        
   <?php
   if ( $ct['gen']->admin_logged_in() ) {
   ?>

   
   <div class="align_center"><a href="?logout=1&admin_nonce=<?=$ct['gen']->admin_nonce('logout')?>"><img src='templates/interface/media/images/auto-preloaded/icons8-logout-58-<?=$ct['sel_opt']['theme_selected']?>.png' class='admin_logout nav-image' width='45' border='0' title='Logout of Admin Config area.' /></a></div>
   
   <div class="smallnav_spacer"></div>
   
   <?php
   }
   ?>
   
   
   <!-- Admin area -->
     <div class="admin-nav-wrapper btn-group dropend">
     
          <a href="admin.php" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><img src='templates/interface/media/images/auto-preloaded/icons8-services-100-<?=$ct['sel_opt']['theme_selected']?>.png' class='nav-image' width='45' border='0' title='Admin Config area.' /></a>
     
          <ul class="admin-nav all-nav dropdown-menu" style="">

        
                    <?php
                    if ( $ct['gen']->admin_logged_in() ) {
                    ?>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_general" title='General admin settings.'>General</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_asset_tracking" title='Admin area for adding / removing currencies and markets.'>Asset Tracking</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_reset_backup_restore" title='Reset, backup, or restore your app configuration settings / chart data / etc.'>Reset / Backup & Restore</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_security" title='Admin area for all security-related settings.'>Security</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_comms" title='Configure email / text / Alexa / Telegram communications, and more.'>Communications</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_apis" title='Configure options for external third party APIs, and available internal APIs / Webhooks.'>APIs</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_plugins" title='Manage plugin addons for this app.'>Plugins</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_news_feeds" title='Edit the news feeds for the news page.'>News Feeds</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_power_user" title='Power user settings (for advanced users).'>Power User</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_text_gateways" title='Add / remove / update the mobile text gateways available, to use for mobile text communications.'>Mobile Text Gateways</a>
                    </li>


                    <li>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_proxy" title='Enable / disable proxy services (for privacy connecting to third party APIs).'>Proxies</a>
                    </li>


                    <li class='sys_stats_admin_link'>
                        <a class="dropdown-item admin_change_width" data-width="fixed_max" href="admin.php#admin_system_monitoring" title='View system / access stats, and app logs.'>System Monitoring<img class='tooltip_style_control sys_stats_admin_link_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' /></a>
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
     
          <a href="index.php" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><img src='templates/interface/media/images/auto-preloaded/icons8-user-96-<?=$ct['sel_opt']['theme_selected']?>.png' class='nav-image' width='45' border='0' title='User area.' /></a>
     
          <ul class="user-nav all-nav dropdown-menu" style="">
          
           <li><a class="dropdown-item" href='index.php#portfolio' title='View your portfolio.'>My Portfolio</a></li>
           
           <li class='update_portfolio_link'><a class="dropdown-item update_portfolio_link" id='update_link_1' href='index.php#update' title='Update your portfolio data.'>Update Portfolio</a></li>

           <li><a class="dropdown-item" href='index.php#settings' title='Update your user settings.'>User Settings</a></li>			
           
           <?php
		 if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' ) {
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
   
   
   <div class="align_center"><a href="javascript: return false;" class="modal_style_control show_report_issues" title='Report issues with this app.'><img src='templates/interface/media/images/auto-preloaded/icons8-questions-100-<?=$ct['sel_opt']['theme_selected']?>.png' class='nav-image' width='45' border='0' title='Report issues with this app, view answers to common issues in FAQ help format, AND check Development Status (for info about UPCOMING fixes / features, that are not released yet).' /></a></div>


   <br clear='all' />

</div>
<!-- END collapsed sidebar -->
   

<!-- Regular sidebar -->
<nav id="sidebar">
    
    
        <!-- alerts toggle icon -->
        <img src='templates/interface/media/images/auto-preloaded/notification-<?=$ct['sel_opt']['theme_selected']?>-line.png' height='45' border='0' id='sb_alerts' class='nav-image toggle_alerts' title='View app alerts.' />
        
        <!-- close sidebar toggle icon -->
        <img src='templates/interface/media/images/auto-preloaded/icons8-close-window-50-<?=$ct['sel_opt']['theme_selected']?>.png' class='nav-image sidebar_toggle' id="dismiss" title='Show COMPACT side bar.' />


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
               	font_size_cookie = confirm("This feature REQUIRES using cookie data.\n\nPRO TIP: If your web browser has a \"zoom\" feature, try that first for better results.");
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
                <a href='javascript:app_reloading_check();' class='blue' title='Use this to Refresh / Reload the app data. <?=$refresh_link_documentation?>'>Refresh Data</a>
            </li>
   
            <?php
            if ( $ct['gen']->admin_logged_in() && $is_admin ) {
            ?>
        
            <li class='sidebar-item'>
                <a href='javascript:' class='admin_settings_save settings_save blue' title='Save settings for this admin section.'>Save Admin Changes</a>
            </li>
        
            <?php
            }
            else if ( !$is_admin ) {
            ?>
   
            <li class='sidebar-item'>
                <a href='javascript:' class='user_settings_save settings_save blue' title='Save settings for the user area.'>Save User Changes</a>
            </li>
        
            <?php
            }
            ?>
        
            <li class='sidebar-item'>
                <a id='pm_link' class='bitcoin pm_link' onclick='privacy_mode(true);' title='Turn privacy mode ON. This encrypts / hides RENDERED personal portfolio data with the PIN you setup (BUT DOES #NOT# encrypt RAW source code). It ALSO disables opposite-clicking / data submission, and logs out any active admin login.'>Privacy Mode: Off</a>
            </li>
        
            <?php
            if ( $ct['gen']->admin_logged_in() ) {
            ?>
   
            <li class='sidebar-item'>
                <a href="?logout=1&admin_nonce=<?=$ct['gen']->admin_nonce('logout')?>" class="admin_logout bitcoin" title='Logout from the admin area.'>Admin Logout</a>
            </li>
            
            <?php
            }
            ?>
            
            <!-- Admin area -->
            <li class="admin-nav-wrapper">
                
                <a href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle <?=( preg_match("/admin\.php/i", $_SERVER['REQUEST_URI']) ? 'active' : '' )?>">Admin Area</a>
                
                <ul class="admin-nav all-nav collapse list-unstyled" id="adminSubmenu">
                
        
                    <?php
                    if ( $ct['gen']->admin_logged_in() ) {
                    ?>


                    <li class='sidebar-item nav-item'>
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_general" title='General admin settings.'>General</a>
                    </li>
                    
                    
                    <!-- START custom 3-deep config -->
                    <li class="nav-item dropdown custom-3deep open-first">
                        
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="admin.php#admin_asset_tracking" onclick='javascript:load_iframe("iframe_asset_tracking")' title='Admin area for adding / removing currencies and markets.'>Asset Tracking</a>
                        
                        <ul class="dropdown-menu">
                        
                        
                        <li>
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_asset_tracking" submenu-id="admin_asset_tracking_currency_support" onclick='javascript: setTimeout(function(){ load_iframe("iframe_asset_tracking", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_currency_support')?>&parent=asset_tracking&subsection=currency_support") }, <?=( $is_admin ? '1000' : '0' )?>);' title='Admin area for adding / removing currencies.'>Currency Support</a>
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_asset_tracking" submenu-id="admin_asset_tracking_portfolio_assets" onclick='javascript: setTimeout(function(){ load_iframe("iframe_asset_tracking", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_portfolio_assets')?>&parent=asset_tracking&subsection=portfolio_assets") }, <?=( $is_admin ? '1000' : '0' )?>);' title='Add / remove / update the available assets for portfolio tracking.'>Portfolio Assets</a>
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_asset_tracking" submenu-id="admin_asset_tracking_price_alerts_charts" onclick='javascript: setTimeout(function(){ load_iframe("iframe_asset_tracking", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_price_alerts_charts')?>&parent=asset_tracking&subsection=price_alerts_charts") }, <?=( $is_admin ? '1000' : '0' )?>);' title='Configure charts and price alerts.'>Price Alerts / Charts</a>
                        
                        </li>
                          <!-- <li><hr class="dropdown-divider"></li> -->
                        
                        </ul>
                        
                    </li>
                    <!-- END custom 3-deep config -->


                    <li class='sidebar-item nav-item'>
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_reset_backup_restore" title='Reset, backup, or restore your app configuration settings / chart data / etc.'>Reset / Backup & Restore</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_security" title='Admin area for all security-related settings.'>Security</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_comms" title='Configure email / text / Alexa / Telegram communications, and more.'>Communications</a>
                    </li>
                    
                    
                    <!-- START custom 3-deep config -->
                    <li class="nav-item dropdown custom-3deep open-first">
                        
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="admin.php#admin_apis" onclick='javascript:load_iframe("iframe_apis")' title='Configure options for external third party APIs, and available internal APIs / Webhooks.'>APIs</a>
                        
                        <ul class="dropdown-menu">
                        
                        
                        <li>
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_apis" submenu-id="admin_apis_ext_apis" onclick='javascript: setTimeout(function(){ load_iframe("iframe_apis", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_ext_apis')?>&parent=apis&subsection=ext_apis") }, <?=( $is_admin ? '1000' : '0' )?>);' title='Configure options for external third party APIs.'>External APIs</a>
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_apis" submenu-id="admin_apis_webhook_int_api" onclick='javascript: setTimeout(function(){ load_iframe("iframe_apis", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_webhook_int_api')?>&parent=apis&subsection=webhook_int_api") }, <?=( $is_admin ? '1000' : '0' )?>);' title='Documentation / keys for using the built-in API to connect to other apps.'>Internal API / Webhook</a>
                        
                        </li>
                          <!-- <li><hr class="dropdown-divider"></li> -->
                        
                        </ul>
                        
                    </li>
                    <!-- END custom 3-deep config -->
                    
                    
                        <?php
                        
                        // Plugin link(s)
                        $navbar_plugins = array();
                        
                        
                        // Active plugins subnav, IF NOT high security mode
                        if ( $ct['admin_area_sec_level'] != 'high' ) {

                             foreach ( $plug['activated']['ui'] as $plugin_key => $unused ) {
                             $navbar_plugins[$plugin_key] = 1;
                             }
     
                             foreach ( $plug['activated']['cron'] as $plugin_key => $unused ) {
                             $navbar_plugins[$plugin_key] = 1;
                             }
     
                             foreach ( $plug['activated']['webhook'] as $plugin_key => $unused ) {
                             $navbar_plugins[$plugin_key] = 1;
                             }
                        
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
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_plugins" submenu-id="admin_plugins_<?=$plugin_key?>" onclick='javascript: setTimeout(function(){ load_iframe("iframe_plugins", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_' . $plugin_key)?>&plugin=<?=$plugin_key?>") }, <?=( $is_admin ? '1000' : '0' )?>);' title='<?=$plug['conf'][$plugin_key]['ui_name']?> plugin settings and documentation.'><?=$plug['conf'][$plugin_key]['ui_name']?></a>
                        
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
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_plugins" title='Manage plugin addons for this app.'>Plugins</a>
                    </li>

                    <?php
                    }
                    ?>


                    <li class='sidebar-item nav-item'>
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_news_feeds" title='Edit the news feeds for the news page.'>News Feeds</a>
                    </li>
                    

                    <li class='sidebar-item nav-item'>
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_power_user" title='Power user settings (for advanced users).'>Power User</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_text_gateways" title='Add / remove / update the mobile text gateways available, to use for mobile text communications.'>Mobile Text Gateways</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a class="nav-link admin_change_width" data-width="fixed_max" href="admin.php#admin_proxy" title='Enable / disable proxy services (for privacy connecting to third party APIs).'>Proxies</a>
                    </li>
                    
                    
                    <!-- START custom 3-deep config -->
                    <li class="nav-item dropdown custom-3deep open-first">
                        
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="admin.php#admin_system_monitoring" onclick='javascript:load_iframe("iframe_system_monitoring")' title='View system / access stats, and app logs.'>System Monitoring</a>
                        
                        <ul class="dropdown-menu">
                        
                        
                        <li>
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_system_monitoring" submenu-id="admin_system_monitoring_system_stats" onclick='javascript: setTimeout(function(){ load_iframe("iframe_system_monitoring", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_system_stats')?>&parent=system_monitoring&subsection=system_stats") }, <?=( $is_admin ? '1000' : '0' )?>);' title='View system stats, to keep track of your app server system health.'>System Stats</a>
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_system_monitoring" submenu-id="admin_system_monitoring_access_stats" onclick='javascript: setTimeout(function(){ load_iframe("iframe_system_monitoring", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_access_stats')?>&parent=system_monitoring&subsection=access_stats") }, <?=( $is_admin ? '1000' : '0' )?>);' title='View user access stats, to track IP addresses / Browser versions / page views of who has been accessing this app.'>Access Stats</a>
                        
                        <!-- WE ONLY NEED A 1000 MILLISECOND DELAY IF WE ARE IN THE ADMIN AREA (FOR UNSAVED SETTING CHANGES CHECKING) -->
                        <a class="dropdown-item" href="admin.php#admin_system_monitoring" submenu-id="admin_system_monitoring_logs" onclick='javascript: setTimeout(function(){ load_iframe("iframe_system_monitoring", "admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_app_logs')?>&parent=system_monitoring&subsection=app_logs") }, <?=( $is_admin ? '1000' : '0' )?>);' title='View logs for this app.'>App Logs</a>
                        
                        </li>
                          <!-- <li><hr class="dropdown-divider"></li> -->
                        
                        </ul>
                        
                    </li>
                    <!-- END custom 3-deep config -->


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
     		 if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' ) {
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
                <a href="javascript: return false;" class="modal_style_control show_report_issues red" title='Report issues with this app, view answers to common issues in FAQ help format, AND check Development Status (for info about UPCOMING fixes / features, that are not released yet).'>Issues Help & Status</a>
            </li>
            
            
        </ul>


</nav>
<!-- END regular sidebar -->


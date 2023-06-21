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

	<title>Open Crypto Tracker<?=( $is_admin ? ' - Admin Config' : '' )?></title>
    

     <meta charset="<?=$ct_conf['dev']['charset_default']?>">
   
     <meta name="viewport" content="width=device-width"> <!-- Mobile compatibility -->
   
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
	
	<meta name="referrer" content="same-origin"> <!-- Keeps this URL private (BROWSER referral data won't be sent when clicking external links) -->
	
	
	<!-- Preload a few UI-related files -->
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/login-<?=$sel_opt['theme_selected']?>-theme.png" as="image">
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/notification-<?=$sel_opt['theme_selected']?>-line.png" as="image">
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/loader.gif" as="image">
    
    
	<link rel="preload" href="templates/interface/css/bootstrap/bootstrap.min.css" as="style" />

	<link rel="preload" href="templates/interface/css/modaal.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/jquery-ui/jquery-ui.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/jquery.mCustomScrollbar.min.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/style.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/<?=$sel_opt['theme_selected']?>.style.css" as="style" />

	<link rel="preload" href="templates/interface/css/highlightjs.min.css" as="style" />
	
	
	<?php
	if ( $is_admin ) {
	?>
	
	<link rel="preload" href="templates/interface/css/admin.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/<?=$sel_opt['theme_selected']?>.admin.css" as="style" />
	
	<?php
	}
	?>


	<link rel="preload" href="app-lib/js/jquery/jquery-3.6.3.min.js" as="script" />
	
	<link rel="preload" href="app-lib/js/jquery/jquery-ui/jquery-ui.js" as="script" />

	<link rel="preload" href="app-lib/js/jquery/jquery.tablesorter.min.js" as="script" />

	<link rel="preload" href="app-lib/js/jquery/jquery.tablesorter.widgets.min.js" as="script" />

	<link rel="preload" href="app-lib/js/jquery/jquery.balloon.min.js" as="script" />

     <link rel="preload" href="app-lib/js/jquery/jquery.repeatable.js" as="script" />

     <link rel="preload" href="app-lib/js/jquery/jquery.mCustomScrollbar.concat.min.js" as="script" />

	<link rel="preload" href="app-lib/js/bootstrap/bootstrap.min.js" as="script" />

	<link rel="preload" href="app-lib/js/modaal.js" as="script" />

	<link rel="preload" href="app-lib/js/base64-decode.js" as="script" />

	<link rel="preload" href="app-lib/js/autosize.min.js" as="script" />

	<link rel="preload" href="app-lib/js/popper.min.js" as="script" />
	
	<link rel="preload" href="app-lib/js/zingchart.min.js" as="script" />
	
	<link rel="preload" href="app-lib/js/crypto-js.js" as="script" />

	<link rel="preload" href="app-lib/js/var_defaults.js" as="script" />

	<link rel="preload" href="app-lib/js/functions.js" as="script" />

	<link rel="preload" href="app-lib/js/random-tips.js" as="script" />

	<link rel="preload" href="app-lib/js/init.js" as="script" />

	<link rel="preload" href="app-lib/js/highlight.min.js" as="script" />
	
	
	<!-- END Preload a few UI-related files -->


	<script src="app-lib/js/jquery/jquery-3.6.3.min.js"></script>
	
	<script src="app-lib/js/jquery/jquery-ui/jquery-ui.js"></script>

	<script src="app-lib/js/jquery/jquery.tablesorter.min.js"></script>

	<script src="app-lib/js/jquery/jquery.tablesorter.widgets.min.js"></script>

	<script src="app-lib/js/jquery/jquery.balloon.min.js"></script>

     <script src="app-lib/js/jquery/jquery.repeatable.js"></script>

	<script src="app-lib/js/jquery/jquery.mCustomScrollbar.concat.min.js"></script>

	<script src="app-lib/js/modaal.js"></script>

	<script src="app-lib/js/base64-decode.js"></script>

	<script src="app-lib/js/autosize.min.js"></script>

	<script src="app-lib/js/popper.min.js"></script>
	
	<script src="app-lib/js/zingchart.min.js"></script>
	
	<script src="app-lib/js/crypto-js.js"></script>

	<script src="app-lib/js/var_defaults.js"></script>

	<script src="app-lib/js/functions.js"></script>
	
	
	<script>
	

	// Set the global JSON config to asynchronous 
	// (so JSON requests run in the background, without pausing any of the page render scripting)
	$.ajaxSetup({
     async: true
	});
	
	
	// Javascript var inits / configs
	
	ct_id = '<?=base64_encode( $ct_gen->id() )?>';
	
	app_edition = '<?=$app_edition?>';
	
	theme_selected = '<?=$sel_opt['theme_selected']?>';
	
	min_fiat_val_test = '<?=$min_fiat_val_test?>';
	
	min_crypto_val_test = '<?=$min_crypto_val_test?>';
	
	watch_only_flag_val = '<?=$watch_only_flag_val?>';
	
	charts_background = '<?=$ct_conf['power']['charts_background']?>';
	
	charts_border = '<?=$ct_conf['power']['charts_border']?>';
	
	btc_prim_currency_val = '<?=number_format( $sel_opt['sel_btc_prim_currency_val'], 2, '.', '' )?>';
	
	btc_prim_currency_pair = '<?=strtoupper($ct_conf['gen']['btc_prim_currency_pair'])?>';
	
	cookies_size_warning = '<?=( isset($system_warnings['portfolio_cookies_size']) ? $system_warnings['portfolio_cookies_size'] : 'none' )?>';
	
	feeds_num = <?=( isset($sel_opt['show_feeds'][0]) && $sel_opt['show_feeds'][0] != '' ? sizeof($sel_opt['show_feeds']) : 0 )?>;
	
	charts_num = <?=( isset($sel_opt['show_charts'][0]) && $sel_opt['show_charts'][0] != '' ? sizeof($sel_opt['show_charts']) : 0 )?>;
	
	sorted_by_col = <?=( $sel_opt['sorted_by_col'] ? $sel_opt['sorted_by_col'] : 0 )?>;
	
	sorted_asc_desc = <?=( $sel_opt['sorted_asc_desc'] ? $sel_opt['sorted_asc_desc'] : 0 )?>;
	
	
	<?php
	if ( $is_iframe ) {
	?>
	
	is_iframe = true;
	
	<?php
	}
	else {
	?>
	
	sidebar_toggle_storage = storage_app_id("sidebar_toggle");
	
	scroll_position_storage = storage_app_id("scroll_position");
	
	cookies_notice_storage = storage_app_id("cookies_notice");
	
	refresh_cache_upgrade_notice_storage = storage_app_id("refresh_cache_upgrade_notice_storage");
	
	notes_storage = storage_app_id("notes");
	
	<?php
	}
	
	if ( $is_admin ) {
	?>
     
     is_admin = true;
     
          <?php
          // DON'T SHOW ON LOGIN FORMS!
          if ( !$is_login_form ) {
          ?>
          
     	admin_area_sec_level = '<?=base64_encode( $admin_area_sec_level )?>';
          
     	enhanced_sec_token = "<?=base64_encode( $ct_gen->admin_hashed_nonce('enhanced_security_mode') )?>";
     	
          <?php
          }
          ?>
	
	<?php
	}
	
	// Include any admin-logged-in stuff in ANY area
	if ( $ct_gen->admin_logged_in() == true ) {
	?>
	
	logs_csrf_sec_token = '<?=base64_encode( $ct_gen->admin_hashed_nonce('logs_csrf_security') )?>';
	
	<?php
	}
	?>
	
	font_size_css_selector = "<?=$font_size_css_selector?>";
	
	medium_font_size_css_selector = "<?=$medium_font_size_css_selector?>";
	
	small_font_size_css_selector = "<?=$small_font_size_css_selector?>";
	
	
	// Preload /images/auto-preloaded/ images VIA JAVASCRIPT TOO (WAY MORE RELIABLE THAN META TAG PRELOAD)
	
	<?php
	$preloaded_files_dir = 'templates/interface/media/images/auto-preloaded';
	$preloaded_files = $ct_gen->list_files($preloaded_files_dir);
	
	$loop = 0;
	foreach ( $preloaded_files as $preload_file ) {
	?>
	
	var loader_image_<?=$loop?> = new Image();
	loader_image_<?=$loop?>.src = '<?=$preloaded_files_dir?>/<?=$preload_file?>';
	
	<?php
	$loop = $loop + 1;
	}
	?>
	
	
	<?php
	foreach ( $ct_conf['dev']['limited_apis'] as $api ) {
	$js_limited_apis .= '"'.strtolower( preg_replace("/\.(.*)/i", "", $api) ).'", ';
	}
	$js_limited_apis = trim($js_limited_apis);
	$js_limited_apis = rtrim($js_limited_apis,',');
	$js_limited_apis = trim($js_limited_apis);
	$js_limited_apis = '['.$js_limited_apis.']';
	?>

	limited_apis = <?=$js_limited_apis?>;
	
	<?php
	foreach ( $ct_conf['power']['crypto_pair_pref_mrkts'] as $key => $unused ) {
	$secondary_mrkt_currencies .= '"'.strtolower($key).'", ';
	}
	foreach ( $ct_conf['power']['btc_currency_mrkts'] as $key => $unused ) {
	$secondary_mrkt_currencies .= '"'.strtolower($key).'", ';
	}
	$secondary_mrkt_currencies = trim($secondary_mrkt_currencies);
	$secondary_mrkt_currencies = rtrim($secondary_mrkt_currencies,',');
	$secondary_mrkt_currencies = trim($secondary_mrkt_currencies);
	$secondary_mrkt_currencies = '['.$secondary_mrkt_currencies.']';
	?>

	secondary_mrkt_currencies = <?=$secondary_mrkt_currencies?>;
	
	<?php
	foreach ( $ct_conf['power']['btc_pref_currency_mrkts'] as $pref_bitcoin_mrkts_key => $pref_bitcoin_mrkts_val ) {
	?>
	pref_bitcoin_mrkts["<?=strtolower( $pref_bitcoin_mrkts_key )?>"] = "<?=strtolower( $pref_bitcoin_mrkts_val )?>";
	<?php
	}
	// If desktop edition, cron emulation is enabled, and NOT on login form submission pages, run emulated cron
	if ( $app_edition == 'desktop' && $ct_conf['power']['desktop_cron_interval'] > 0 && !$is_login_form ) {
	?>	
	
     emulated_cron_enabled = true;
     
	<?php
	}
	elseif ( $app_edition == 'desktop' ) {
	?>
	
	desktop_zoom_storage = storage_app_id("zoom");
	
	<?php
	}
	?>
    
	</script>


     <!-- ALL CORE JAVASCRIPT VARS MUST BE INIT'D / CONFIG'D BEFORE LOADING INIT.JS AND RANDOM-TIPS.JS! -->
     
	<script src="app-lib/js/init.js"></script>

	<script src="app-lib/js/random-tips.js"></script>
    
    
	<link rel="stylesheet" href="templates/interface/css/bootstrap/bootstrap.min.css" type="text/css" />

	<link rel="stylesheet" href="templates/interface/css/modaal.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/jquery-ui/jquery-ui.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/jquery.mCustomScrollbar.min.css" type="text/css" />
	
	<!-- Load theme styling last to over rule -->
	<link rel="stylesheet" href="templates/interface/css/style.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/<?=$sel_opt['theme_selected']?>.style.css" type="text/css" />
	
	
	<?php
	if ( $is_admin ) {
	?>
	
	<link rel="stylesheet" href="templates/interface/css/admin.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/<?=$sel_opt['theme_selected']?>.admin.css" type="text/css" />
	
	<?php
	}
	?>
	
	
	<style>

	@import "templates/interface/css/tablesorter/theme.<?=$sel_opt['theme_selected']?>.css";
	
	.tablesorter-<?=$sel_opt['theme_selected']?> .header, .tablesorter-<?=$sel_opt['theme_selected']?> .tablesorter-header {
     white-space: nowrap;
	}


     <?php
     // IF there is a configged google font
     if ( isset($google_font_name) ) {
     ?>
     
     @import "https://fonts.googleapis.com/css?family=<?=$font_name_url_formatting?>&display=swap";

     html, body {	
         font-family: '<?=$google_font_name?>', sans-serif !important;	
         font-weight: 300 !important;
     }
     
     <?php
     }
     else {
     ?>
     
     html, body {	
         font-family: sans-serif !important;	
         font-weight: 300 !important;
     }
     
     <?php
     }
     ?>
     
     /* standard font size CSS selector (we skip sidebar HEADER area) */
     <?=$font_size_css_selector?> {
     font-size: <?=$default_font_size?>em !important;
     line-height: <?=$default_font_line_height?>em !important;
     }

     /* medium font size CSS selector (we skip sidebar HEADER area) */
     <?=$medium_font_size_css_selector?> {
     font-size: <?=$default_medium_font_size?>em !important;
     line-height: <?=$default_medium_font_line_height?>em !important;
     }

     /* small font size CSS selector (we skip sidebar HEADER area) */
     <?=$small_font_size_css_selector?> {
     font-size: <?=$default_tiny_font_size?>em !important;
     line-height: <?=$default_tiny_font_line_height?>em !important;
     }
     
	
	<?php
	if ( $is_iframe ) {
	?>
	
	/* iframes */
     html, body {
     margin: 0px;
     padding: 0px;
     }

	<?php
	}
	?>
	
     </style>
     
     
     <!-- ONLY RUN HIGHTLIGHTJS SCRIPT / CSS #AFTER# ALL OTHER SCRIPT / CSS! -->
	<link rel="stylesheet" href="templates/interface/css/highlightjs.min.css" type="text/css" />
	
	<script src="app-lib/js/highlight.min.js"></script>
	
	<script>
	// Highlightjs configs
	hljs.configure({useBR: false}); // Don't use  <br /> between lines
	hljs.initHighlightingOnLoad(); // Load on page load
	</script>
	

	<link rel="shortcut icon" href="templates/interface/media/images/favicon.png">
	<link rel="icon" href="templates/interface/media/images/favicon.png">


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


   <!-- collapsed sidebar -->
   <div id="collapsed_sidebar">
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-hamburger-menu-96-<?=$sel_opt['theme_selected']?>.png' width='45' class='sidebar_toggle' id="sidebar_hamburger" title='Show FULL SIZED side bar.' /></div>
   
   
   <div class="smallnav_spacer"></div>

   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/notification-<?=$sel_opt['theme_selected']?>-line.png' width='45' border='0' class='toggle_alerts' title='View app alerts.' /></div>

   
   <div class="smallnav_spacer"></div>
   
   
   <!-- Admin area -->
     <div class="admin-nav-wrapper btn-group dropend">
     
          <a href="admin.php" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><img src='templates/interface/media/images/auto-preloaded/icons8-services-100-<?=$sel_opt['theme_selected']?>.png' width='45' border='0' title='Admin Config area.' /></a>
     
          <ul class="admin-nav all-nav dropdown-menu" style="" role="tablist">

        
                    <?php
                    if ( $ct_gen->admin_logged_in() ) {
                         
                    // Links won't work in NON-ADMIN AREAS without this logic
                    $content_toggle = ( preg_match("/admin\.php/i", $_SERVER['REQUEST_URI']) ? 'data-bs-toggle="tab"' : '' );
                    
                    ?>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_general" href="admin.php#admin_general">General</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_comms" href="admin.php#admin_comms">Communications</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_other_api" href="admin.php#admin_other_api">Other APIs</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_proxy" href="admin.php#admin_proxy">Proxies</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_security" href="admin.php#admin_security">Security</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_portfolio_assets" href="admin.php#admin_portfolio_assets">Portfolio Assets</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_charts_alerts" href="admin.php#admin_charts_alerts"><?=( $ct_conf['gen']['asset_charts_toggle'] == 'on' ? 'Charts and ' : 'Price ' )?>Alerts</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_plugins" href="admin.php#admin_plugins">Plugins</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_power_user" href="admin.php#admin_power_user">Power User</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_int_api" href="admin.php#admin_int_api">Internal API</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_webhook" href="admin.php#admin_webhook">Webhook</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_text_gateways" href="admin.php#admin_text_gateways">Text Gateways</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_developer_only" href="admin.php#admin_developer_only">Developer Only</a>
                    </li>


                    <li class='sys_stats_admin_link'>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_system_stats" href="admin.php#admin_system_stats">System Stats<img class='sys_stats_admin_link_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' /></a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_access_stats" href="admin.php#admin_access_stats">Access Stats</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_logs" href="admin.php#admin_logs">App Logs</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_backup_restore" href="admin.php#admin_backup_restore">Backup / Restore</a>
                    </li>


                    <li>
                        <a <?=$content_toggle?> class="dropdown-item admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_reset" href="admin.php#admin_reset">Reset</a>
                    </li>
                    
                    
                    <li>
                    <a class="dropdown-item" href="?logout=1&admin_hashed_nonce=<?=$ct_gen->admin_hashed_nonce('logout')?>">Logout</a>
                    </li>


                    <?php
                    }
                    else {
                    ?>
                    
                    <li>
                    <a class="dropdown-item" href="admin.php">Admin Login</a>
                    </li>

                    <?php
                    }
                    ?>
                    

          </ul>
      
     </div>

   
   <div class="smallnav_spacer"></div>
   
   
   <!-- User area -->
     <div class="user-nav-wrapper btn-group dropend">
     
          <a href="index.php" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><img src='templates/interface/media/images/auto-preloaded/icons8-user-96-<?=$sel_opt['theme_selected']?>.png' width='45' border='0' title='User area.' /></a>
     
          <ul class="user-nav all-nav dropdown-menu" style="">
          
           <li><a class="dropdown-item" href='index.php#portfolio' title='View your portfolio.'>My Portfolio</a></li>
           
           <li class='update_portfolio_link'><a class="dropdown-item update_portfolio_link" id='update_link_1' href='index.php#update' title='Update your portfolio data.'>Update Portfolio</a></li>

           <li><a class="dropdown-item" href='index.php#settings' title='Update your user settings.'>User Settings</a></li>			
           
           <?php
		 if ( $ct_conf['gen']['asset_charts_toggle'] == 'on' ) {
		 ?>
           <li><a class="dropdown-item" href='index.php#charts' title='View price charts.'>Price Charts</a></li>
		 <?php
		 }
		 ?>
		 
           <li><a class="dropdown-item" href='index.php#news' title='View News Feeds.'>News Feeds</a></li>
           
           <li><a class="dropdown-item" href='index.php#tools' title='Use various crypto tools.'>Tools</a></li>

           <li><a class="dropdown-item" href='index.php#mining' title='Calculate coin mining profits.'>Staking / Mining</a></li>

           <li><a class="dropdown-item" href='index.php#resources' title='View 3rd party resources.'>Other Resources</a></li>

           <li><a class="dropdown-item" href='index.php#help' title='Get help with running and setting up this app.'>Help</a></li>
           
          </ul>
      
     </div>

   
   <div class="smallnav_spacer"></div>
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-questions-100-<?=$sel_opt['theme_selected']?>.png' width='45' border='0' title='Get help with running and setting up this app.' /></div>

        
   <?php
   if ( $ct_gen->admin_logged_in() ) {
   ?>
   
   <div class="align_center"><a href="?logout=1&admin_hashed_nonce=<?=$ct_gen->admin_hashed_nonce('logout')?>"><img src='templates/interface/media/images/auto-preloaded/icons8-logout-58-<?=$sel_opt['theme_selected']?>.png' width='45' border='0' title='Logout of Admin Config area.' /></a></div>

   
   <div class="smallnav_spacer"></div>
   
   <?php
   }
   ?>
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-add-96.png' width='45' border='0' id='' class='btn-number' data-type="plus" data-field="quant_font_percent" title='Increase text size.' /></div>

   
   <div class="smallnav_spacer"></div>
   
   
   <div class="align_center"><img src='templates/interface/media/images/auto-preloaded/icons8-minus-96.png' width='45' border='0' id='' class='btn-number' data-type="minus" data-field="quant_font_percent" title='Decrease text size.' /></div>
   

   </div>
   

    <!-- Sidebar -->
    <nav id="sidebar">
    
    
        <!-- alerts toggle icon -->
        <img src='templates/interface/media/images/auto-preloaded/notification-<?=$sel_opt['theme_selected']?>-line.png' height='45' border='0' id='sb_alerts' class='toggle_alerts' title='View app alerts.' />
        
        <!-- close sidebar toggle icon -->
        <img src='templates/interface/media/images/auto-preloaded/icons8-close-window-50-<?=$sel_opt['theme_selected']?>.png' class='sidebar_toggle' id="dismiss" title='Show COMPACT side bar.' />


        <div class="sidebar-top">
        
             <div class="plus_minus">
              <div class="input-group" style='width: 120px;'>
                    <span class="input-group-btn">
                       <button type="button" class="btn btn-danger btn-number"  data-type="minus" data-field="quant_font_percent" title='Decrease text size.'>
                          <span class="plus_minus_buttons"> - </span>
                        </button>
                    </span>
                    
                    
                    <div class="form-floating">
                    
                    <input type="text" name="quant_font_percent" id="quant_font_percent" class="form-control input-number" value="<?=($default_font_size * 100)?>" min="30" max="300" onchange='
                    
               	if ( !get_cookie("font_size") ) {
               	font_size_cookie = confirm("This feature requires using cookie data.");
               	}
               	else {
               	font_size_cookie = true;
               	}
               			
               	if ( font_size_cookie == true && is_int(this.value) != false && this.value >= 30 && this.value <= 300 ) {
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
            <h4 class='align_center' style='margin-bottom: 0px;'>Open Crypto Tracker</h4>
        </div>
        
        <div class="sidebar-slogan align_center">
        
        <i>Privately</i> track <i>ANY</i> Crypto on your home network or internet website, for <a class='sidebar_secondary_link' href='https://taoteh1221.github.io/' target='_blank'><i>FREE</i></a>.
        
        </div>


        <ul id='sidebar_menu' class="list-unstyled components">
        
            <!-- Admin area -->
            <li class="admin-nav-wrapper">
                
                <a href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle <?=( preg_match("/admin\.php/i", $_SERVER['REQUEST_URI']) ? 'active' : '' )?>">Admin Area</a>
                
                <ul class="admin-nav all-nav collapse list-unstyled" id="adminSubmenu" role="tablist">
                
        
                    <?php
                    if ( $ct_gen->admin_logged_in() ) {
                         
                    // Links won't work in NON-ADMIN AREAS without this logic
                    $content_toggle = ( preg_match("/admin\.php/i", $_SERVER['REQUEST_URI']) ? 'data-bs-toggle="tab"' : '' );
                    
                    ?>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_general" href="admin.php#admin_general">General</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_comms" href="admin.php#admin_comms">Communications</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_other_api" href="admin.php#admin_other_api">Other APIs</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_proxy" href="admin.php#admin_proxy">Proxies</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_security" href="admin.php#admin_security">Security</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_portfolio_assets" href="admin.php#admin_portfolio_assets">Portfolio Assets</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_charts_alerts" href="admin.php#admin_charts_alerts"><?=( $ct_conf['gen']['asset_charts_toggle'] == 'on' ? 'Charts and ' : 'Price ' )?>Alerts</a>
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
                        
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="admin.php#admin_plugins">Plugins</a>
                        
                        <ul class="dropdown-menu">
                        
                    <?php
                        }

                        foreach ( $navbar_plugins as $plugin_key => $unused ) {
                        ?>
                        
                        <li>
                        
                        <a class="dropdown-item" href="admin.php#admin_plugins" onclick='javascript:load_iframe("iframe_plugins", "admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_' . $plugin_key)?>&plugin=<?=$plugin_key?>")'><?=$plug_conf[$plugin_key]['ui_name']?></a>
                        
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
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_plugins" href="admin.php#admin_plugins">Plugins</a>
                    </li>

                    <?php
                    }
                    ?>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_power_user" href="admin.php#admin_power_user">Power User</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_int_api" href="admin.php#admin_int_api">Internal API</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_webhook" href="admin.php#admin_webhook">Webhook</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_text_gateways" href="admin.php#admin_text_gateways">Text Gateways</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_developer_only" href="admin.php#admin_developer_only">Developer Only</a>
                    </li>


                    <li class='sidebar-item nav-item sys_stats_admin_link'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_system_stats" href="admin.php#admin_system_stats">System Stats<img class='sys_stats_admin_link_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' /></a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_access_stats" href="admin.php#admin_access_stats">Access Stats</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_logs" href="admin.php#admin_logs">App Logs</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_backup_restore" href="admin.php#admin_backup_restore">Backup / Restore</a>
                    </li>


                    <li class='sidebar-item nav-item'>
                        <a <?=$content_toggle?> class="nav-link admin_change_width" data-width="fixed_max" role="tab" aria-controls="admin_reset" href="admin.php#admin_reset">Reset</a>
                    </li>
                    
                    
                    <li class='sidebar-item'>
                        <a href="?logout=1&admin_hashed_nonce=<?=$ct_gen->admin_hashed_nonce('logout')?>">Logout</a>
                    </li>


                    <?php
                    }
                    else {
                    ?>
                    
                    <li class='sidebar-item'>
                        <a href="admin.php">Login</a>
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
     		 if ( $ct_conf['gen']['asset_charts_toggle'] == 'on' ) {
     		 ?>
                <li class='sidebar-item'><a href='index.php#charts' title='View price charts.'>Price Charts</a></li>
     		 <?php
     		 }
     		 ?>
     		 
                <li class='sidebar-item'><a href='index.php#news' title='View News Feeds.'>News Feeds</a></li>
                
                <li class='sidebar-item'><a href='index.php#tools' title='Use various crypto tools.'>Tools</a></li>
     
                <li class='sidebar-item'><a href='index.php#mining' title='Calculate coin mining profits.'>Staking / Mining</a></li>
     
                <li class='sidebar-item'><a href='index.php#resources' title='View 3rd party resources.'>Other Resources</a></li>
     
                <li class='sidebar-item'><a href='index.php#help' title='Get help with running and setting up this app.'>Help</a></li>
                
                </ul>
                
            </li>
            
            <li class='sidebar-item'>
                <a href="#12" title='Get help with running and setting up this app.'>Help</a>
            </li>

            <li class='sidebar-item'>
                <a href='javascript:' title='Return to the top of the menu.'>Back To Top</a>
            </li>
            
        </ul>


    </nav>

    
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
             if ( localStorage.getItem(desktop_zoom_storage) ) {
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
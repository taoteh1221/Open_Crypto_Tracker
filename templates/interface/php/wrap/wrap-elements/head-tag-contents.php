
     <!-- !!DEBUGGING!!: (debugging logic here) -->

	<title>Open Crypto Tracker<?=( $is_admin ? ' - Admin Config' : '' )?></title>
    

     <meta charset="<?=$ct['dev']['charset_default']?>">
   
     <meta name="viewport" content="width=device-width"> <!-- Mobile compatibility -->
   
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
	
	<meta name="referrer" content="same-origin"> <!-- Keeps this URL private (BROWSER referral data won't be sent when clicking external links) -->
	
	
	<!-- Preload a few UI-related files -->
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/login-<?=$ct['sel_opt']['theme_selected']?>-theme.png" as="image">
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/notification-<?=$ct['sel_opt']['theme_selected']?>-line.png" as="image">
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/loader.gif" as="image">
    
    
	<link rel="preload" href="templates/interface/css/bootstrap/bootstrap.min.css" as="style" />

	<link rel="preload" href="templates/interface/css/modaal.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/jquery-ui/jquery-ui.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/jquery.mCustomScrollbar.min.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/style.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/responsive-menus.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/<?=$ct['sel_opt']['theme_selected']?>.style.css" as="style" />

	<link rel="preload" href="templates/interface/css/highlightjs.min.css" as="style" />

	<link rel="preload" href="//fonts.googleapis.com/css?family=<?=$font_name_url_formatting?>&display=swap" as="style" />
	
	
	<?php
	if ( $is_admin ) {
	?>
	
	<link rel="preload" href="templates/interface/css/admin.css" as="style" />
	
	<link rel="preload" href="templates/interface/css/<?=$ct['sel_opt']['theme_selected']?>.admin.css" as="style" />
	
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

	<link rel="preload" href="app-lib/js/insQ.min.js" as="script" />
	
	
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
	
	<script src="app-lib/js/insQ.min.js"></script>
	
	<script src="app-lib/js/crypto-js.js"></script>

	<script src="app-lib/js/var_defaults.js"></script>

	<script src="app-lib/js/functions.js"></script>
	
	
	<script>
	
	
	// Javascript var inits / configs
	
	ct_id = '<?=base64_encode( $ct['gen']->id() )?>';
	
	app_edition = '<?=$ct['app_edition']?>';
	
	app_platform = '<?=$ct['app_platform']?>';
	
	theme_selected = '<?=$ct['sel_opt']['theme_selected']?>';
	
	// Opposite of app theme, for better contrast
     scrollbar_theme = theme_selected == 'dark' ? 'minimal' : 'minimal-dark';
	
	font_name_url_formatting = "<?=$font_name_url_formatting?>";
	
	global_line_height_percent = Number("<?=$ct['dev']['global_line_height_percent']?>");
	
	set_font_size = Number("<?=$set_font_size?>");
	
	info_icon_size_css_selector = "<?=$ct['dev']['info_icon_size_css_selector']?>";
	
	ajax_loading_size_css_selector = "<?=$ct['dev']['ajax_loading_size_css_selector']?>";
	
	password_eye_size_css_selector = "<?=$ct['dev']['password_eye_size_css_selector']?>";
	
	font_size_css_selector = "<?=$ct['dev']['font_size_css_selector']?>";
	
	medium_font_size_css_selector = "<?=$ct['dev']['medium_font_size_css_selector']?>";
	
	small_font_size_css_selector = "<?=$ct['dev']['small_font_size_css_selector']?>";
	
	tiny_font_size_css_selector = "<?=$ct['dev']['tiny_font_size_css_selector']?>";
	
	medium_font_size_css_percent = Number(<?=$ct['dev']['medium_font_size_css_percent']?>);
	
	small_font_size_css_percent = Number(<?=$ct['dev']['small_font_size_css_percent']?>);
	
	tiny_font_size_css_percent = Number(<?=$ct['dev']['tiny_font_size_css_percent']?>);
	
	min_fiat_val_test = '<?=$min_fiat_val_test?>';
	
	min_crypto_val_test = '<?=$min_crypto_val_test?>';
	
	watch_only_flag_val = '<?=$watch_only_flag_val?>';
	
	charts_background = '<?=$ct['conf']['charts_alerts']['charts_background']?>';
	
	charts_border = '<?=$ct['conf']['charts_alerts']['charts_border']?>';
	
	btc_prim_currency_val = '<?=number_format( $ct['sel_opt']['sel_btc_prim_currency_val'], 2, '.', '' )?>';
	
	bitcoin_primary_currency_pair = '<?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?>';
	
	cookies_size_warning = '<?=( isset($ct['system_warnings']['portfolio_cookies_size']) ? $ct['system_warnings']['portfolio_cookies_size'] : 'none' )?>';
	
	feeds_num = <?=( isset($ct['sel_opt']['show_feeds'][0]) && $ct['sel_opt']['show_feeds'][0] != '' ? sizeof($ct['sel_opt']['show_feeds']) : 0 )?>;
	
	charts_num = <?=( isset($ct['sel_opt']['show_charts'][0]) && $ct['sel_opt']['show_charts'][0] != '' ? sizeof($ct['sel_opt']['show_charts']) : 0 )?>;
	
	sorted_by_col = <?=( $ct['sel_opt']['sorted_by_col'] ? $ct['sel_opt']['sorted_by_col'] : 0 )?>;
	
	sorted_asc_desc = <?=( $ct['sel_opt']['sorted_asc_desc'] ? $ct['sel_opt']['sorted_asc_desc'] : 0 )?>;
	
	
	<?php
     if ( isset($ct['app_container']) ) {
     ?>
     
	app_container = '<?=$ct['app_container']?>';
	
     <?php
     }
     
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
	
	safari_notice_storage = storage_app_id("safari_notice");
	
	desktop_windows_notice_storage = storage_app_id("desktop_windows_notice");
	
	refresh_cache_upgrade_notice_storage = storage_app_id("refresh_cache_upgrade_notice_storage");
	
	notes_storage = storage_app_id("notes");
	
	<?php
	}
	
	
	if ( $is_admin ) {
	?>
     
     is_admin = true;
     
          <?php
          // DON'T SHOW ON LOGIN FORMS!
          if ( $ct['is_login_form'] ) {
          ?>
          
          is_login_form = true;
     	
          <?php
          }
          else {
          ?>
	
	     admin_area_2fa = '<?=base64_encode($ct['admin_area_2fa'])?>';
          
     	admin_area_sec_level = '<?=base64_encode($ct['admin_area_sec_level'])?>';
          
     	medium_sec_token = "<?=base64_encode( $ct['gen']->admin_nonce('medium_security_mode') )?>";
          
          <?php
          }
          ?>
	
	<?php
	}
	
	
	// Include any admin-logged-in stuff in ANY area
	if ( $ct['gen']->admin_logged_in() == true ) {
	?>
	
	admin_logged_in = true;
	
	gen_csrf_sec_token = '<?=base64_encode( $ct['gen']->admin_nonce('general_csrf_security') )?>';
	
	logs_csrf_sec_token = '<?=base64_encode( $ct['gen']->admin_nonce('logs_csrf_security') )?>';
	
	admin_iframe_url = storage_app_id("admin_iframe_url");
	
	<?php
	}
	
	
	// If desktop edition, cron emulation is enabled, and NOT on login form submission pages, run emulated cron
	if ( $ct['app_edition'] == 'desktop' && $ct['conf']['power']['desktop_cron_interval'] > 0 && !$ct['is_login_form'] ) {
	?>	
	
     emulated_cron_enabled = true;
     
	<?php
	}


     // Preload /images/auto-preloaded/ images VIA JAVASCRIPT TOO (WAY MORE RELIABLE THAN META TAG PRELOAD)
	
	$preloaded_files_dir = 'templates/interface/media/images/auto-preloaded';
	$preloaded_files = $ct['gen']->list_files($preloaded_files_dir);
	
	$loop = 0;
	foreach ( $preloaded_files as $preload_file ) {
	?>
	
	var loader_image_<?=$loop?> = new Image();
	loader_image_<?=$loop?>.src = '<?=$preloaded_files_dir?>/<?=$preload_file?>';
	
	<?php
	$loop = $loop + 1;
	}
	
	
	foreach ( $ct['dev']['limited_apis'] as $api ) {
	$js_limited_apis .= '"'.strtolower( preg_replace("/\.(.*)/i", "", $api) ).'", ';
	}

	$js_limited_apis = trim($js_limited_apis);
	$js_limited_apis = rtrim($js_limited_apis,',');
	$js_limited_apis = trim($js_limited_apis);
	$js_limited_apis = '['.$js_limited_apis.']';

	?>

	limited_apis = <?=$js_limited_apis?>;
	
	// Add secondary bitmex perp markets (as we just derive from TLD domains for the base names)
	limited_apis.push("bitmex_u20");
	limited_apis.push("bitmex_z20");
	
	<?php
	
	foreach ( $ct['conf']['power']['crypto_pair_preferred_markets'] as $key => $unused ) {
	$secondary_mrkt_currencies .= '"'.strtolower($key).'", ';
	}
	
	foreach ( $ct['opt_conf']['bitcoin_currency_markets'] as $key => $unused ) {
	$secondary_mrkt_currencies .= '"'.strtolower($key).'", ';
	}
	
	$secondary_mrkt_currencies = trim($secondary_mrkt_currencies);
	$secondary_mrkt_currencies = rtrim($secondary_mrkt_currencies,',');
	$secondary_mrkt_currencies = trim($secondary_mrkt_currencies);
	$secondary_mrkt_currencies = '['.$secondary_mrkt_currencies.']';
	
	?>

	secondary_mrkt_currencies = <?=$secondary_mrkt_currencies?>;
	
	<?php
	foreach ( $ct['opt_conf']['bitcoin_preferred_currency_markets'] as $pref_bitcoin_mrkts_key => $pref_bitcoin_mrkts_val ) {
	?>

	pref_bitcoin_mrkts["<?=strtolower( $pref_bitcoin_mrkts_key )?>"] = "<?=strtolower( $pref_bitcoin_mrkts_val )?>";
	
	<?php
	}
	?>
	

	// Set the global JSON config to asynchronous 
	// (so JSON requests run in the background, without pausing any of the page render scripting)
	$.ajaxSetup({
     async: true
	});
	

     // Load external google font CSS file
     load_google_font();
	
	
     // Wait until the DOM has loaded before running DOM-related scripting
     $(document).ready(function(){ 
	
	
	<?php
	// If a 2FA feild needs to be highlighted (due to invalid input)
	if ( $ct['check_2fa_id'] != null ) {
	?>
	
	$("#<?=$ct['check_2fa_id']?>").css('background','#ff4747');
	
	    <?php
	    // We already print out login form error alerts
	    if ( $ct['is_login_form'] == false ) {
	    ?>
	    
	    $('#notice_<?=$ct['check_2fa_id']?>').removeClass("hidden");
	
	<?php
	    }
	    
	}
	?>
	
	 
     });
	
    
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
	
	<link rel="stylesheet" href="templates/interface/css/responsive-menus.css" type="text/css" title="responsive-menus" />
	
	<link rel="stylesheet" href="templates/interface/css/<?=$ct['sel_opt']['theme_selected']?>.style.css" type="text/css" />
	
	
	<?php
	if ( $is_admin ) {
	?>
	
	<link rel="stylesheet" href="templates/interface/css/admin.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/<?=$ct['sel_opt']['theme_selected']?>.admin.css" type="text/css" />
	
	<?php
	}
	?>
	
	
	<style>

	@import "templates/interface/css/tablesorter/theme.<?=$ct['sel_opt']['theme_selected']?>.css";
	
	.tablesorter-<?=$ct['sel_opt']['theme_selected']?> .header, .tablesorter-<?=$ct['sel_opt']['theme_selected']?> .tablesorter-header {
     white-space: nowrap;
	}


     <?php
     // IF there is a configged google font
     if ( isset($google_font_name) ) {
     ?>
     
     @import "//fonts.googleapis.com/css?family=<?=$font_name_url_formatting?>&display=swap";

     html, body {	
         font-family: '<?=$google_font_name?>', sans-serif !important;	
         font-weight: <?=$ct['dev']['global_font_weight']?> !important;
     }
     
     <?php
     }
     else {
     ?>
     
     html, body {	
         font-family: sans-serif !important;	
         font-weight: <?=$ct['dev']['global_font_weight']?> !important;
     }
     
     <?php
     }
     ?>
     
     /* info icon size CSS selector */
     <?php
     // iframe info icon sizes are wonky for some reason in LINUX PHPDESKTOP (but works fine in modern browsers)
     if ( $ct['app_container'] == 'phpdesktop' ) {
     $set_info_icon_size = $set_font_size * 1.6;
     }
     else {
     $set_info_icon_size = $set_font_size * 2.0;
     }
     ?>
     <?=$ct['dev']['info_icon_size_css_selector']?> {
     height: <?=round($set_info_icon_size, 3)?>em !important;
     width: auto !important;
     }
     
     /* ajax loading size CSS selector */
     <?php
     // Run a multiplier, to slightly increase image size
     $set_ajax_loading_size = $set_font_size * 1.3;
     ?>
     <?=$ct['dev']['ajax_loading_size_css_selector']?> {
     height: <?=round($set_ajax_loading_size, 3)?>em !important;
     width: auto !important;
     }

     /* password eye icon selector */
     <?php
     // Run a multiplier, to adjust password eye placement
     $eye_top_right = $set_font_line_height * 0.22;
     ?>
     <?=$ct['dev']['password_eye_size_css_selector']?> {
     top: <?=round($eye_top_right, 3)?>em;
     right: <?=round($eye_top_right, 3)?>em;
     transform: scale(var(--ggs,<?=$set_font_size?>));
     }
     
     /* standard font size CSS selector */
     <?=$ct['dev']['font_size_css_selector']?> {
     font-size: <?=$set_font_size?>em !important;
     line-height: <?=$set_font_line_height?>em !important;
     font-weight: <?=$ct['dev']['global_font_weight']?> !important;
     }

     /* medium font size CSS selector */
     <?=$ct['dev']['medium_font_size_css_selector']?> {
     font-size: <?=$set_medium_font_size?>em !important;
     line-height: <?=$set_medium_font_line_height?>em !important;
     font-weight: <?=$ct['dev']['global_font_weight']?> !important;
     }

     /* small font size CSS selector */
     <?=$ct['dev']['small_font_size_css_selector']?> {
     font-size: <?=$set_small_font_size?>em !important;
     line-height: <?=$set_small_font_line_height?>em !important;
     font-weight: <?=$ct['dev']['global_font_weight']?> !important;
     }

     /* tiny font size CSS selector */
     <?=$ct['dev']['tiny_font_size_css_selector']?> {
     font-size: <?=$set_tiny_font_size?>em !important;
     line-height: <?=$set_tiny_font_line_height?>em !important;
     font-weight: <?=$ct['dev']['global_font_weight']?> !important;
     }
	

     /* When printing the page from a browser, make it look good and fit nicely */
     @media print {
        
        
        @page {
        size: landscape;
        }
        
        
        #coins_table, #secondary_wrapper, #secondary_wrapper, #secondary_wrapper.active, .full_width_wrapper {
        padding: 0px !important;
        margin: 0px !important;
              left: 0px !important;
              text-align: left !important;
        }
        
        #coins_table th, #coins_table td.data span, #secondary_wrapper, #secondary_wrapper.active, .crypto_worth {
              color: black;
        }
        
        
        .btc, .eth, .sol {
        background: unset !important;
        -webkit-background-clip: unset !important;
        -webkit-text-fill-color: unset !important;
        }
        
        
        .blue, td.blue, td.blue span.blue, .green, .red, .btc, .eth, .sol, td select, .btn-link, .btn-link a, .btn-link:hover, #sidebar ul.list-unstyled li a.blue:link {
        color: blue !important;
        }
        
        
        body, .black {
        color: black !important;
        }
     
     
        #secondary_wrapper, #secondary_wrapper.active {
              width: unset;
        }
     
     
        #sidebar, #collapsed_sidebar, .countdown_notice, .page_title {
        display: none;
        }
        
      
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
	

	<link rel="shortcut icon" href="templates/interface/media/images/favicon.png">
	<link rel="icon" href="templates/interface/media/images/favicon.png">


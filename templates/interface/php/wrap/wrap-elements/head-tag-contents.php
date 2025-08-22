
     <!-- !!DEBUGGING!!: (debugging logic here) -->
     

	<title>Open Crypto Tracker<?=( $is_admin ? ' - Admin Config' : '' )?></title>
    

     <meta charset="<?=$ct['dev']['charset_default']?>">
   
     <meta name="viewport" content="width=device-width"> <!-- Mobile compatibility -->
   
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
	
	<meta name="referrer" content="same-origin"> <!-- Keeps this URL private (BROWSER referral data won't be sent when clicking external links) -->
	
    
	<link rel="stylesheet" href="templates/interface/css/bootstrap/bootstrap.min.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/jquery-ui/jquery-ui.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/jstree/default/style.min.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/tablesorter/jquery.tablesorter.pager.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/root-css-combined.php?theme=<?=$ct['sel_opt']['theme_selected']?>&admin=<?=( $is_admin ? 'yes' : 'no' )?>" type="text/css" />
	
	<!-- responsive-menus NEEDS TO LOAD SEPARATELY! -->
	
	<link rel="stylesheet" href="templates/interface/css/responsive-menus.css" type="text/css" title="responsive-menus" />

	<script src="app-lib/js/jquery/jquery-javascript-combined.php"></script>
	
	<!-- jquery UI NEEDS TO LOAD SEPARATELY AFTER JQUERY! -->
	
	<script src="app-lib/js/jquery/jquery-ui/jquery-ui.js"></script>

	<script src="app-lib/js/root-javascript-combined.php"></script>
	
	
	<script>
	
	
	// Javascript var inits / configs
	
	ct_id = '<?=base64_encode( $ct['sec']->id() )?>';
	
	// Are we running windows?
	<?php
	if ( $ct['ms_windows_server'] ) {
     $is_windows = 'yes';
     }
     else {
     $is_windows = 'no';
     }
	?>
	
	is_windows = '<?=base64_encode($is_windows)?>';
	
	app_edition = '<?=base64_encode($ct['app_edition'])?>';
	
	app_platform = '<?=base64_encode($ct['app_platform'])?>';
	
	cookie_path = '<?=$ct['cookie_path']?>';
	
	theme_selected = '<?=$ct['sel_opt']['theme_selected']?>';
	
	news_feed_batched_maximum = Number("<?=$ct['conf']['news']['news_feed_batched_maximum']?>");
         
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
	
	currency_decimals_max = Number(<?=$ct['conf']['currency']['currency_decimals_max']?>);
	
	crypto_decimals_max = Number(<?=$ct['conf']['currency']['crypto_decimals_max']?>);
	
	min_fiat_val_test = '<?=$ct['min_fiat_val_test']?>';
	
	min_crypto_val_test = '<?=$ct['min_crypto_val_test']?>';
	
	watch_only_flag_val = '<?=$watch_only_flag_val?>';
	
	charts_background = '<?=$ct['conf']['charts_alerts']['charts_background']?>';
	
	charts_border = '<?=$ct['conf']['charts_alerts']['charts_border']?>';
	
	btc_prim_currency_val = '<?=number_format( $ct['sel_opt']['sel_btc_prim_currency_val'], 2, '.', '' )?>';
	
	bitcoin_primary_currency_pair = '<?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>';
	
	cookies_size_warning = "<?=( isset($ct['system_warnings']['portfolio_cookies_size']) && isset($ct['system_info']['portfolio_cookies']) ? "HIGH cookie usage (" . $ct['var']->num_pretty( ($ct['system_info']['portfolio_cookies'] / 1000) , 2) . "kb) risks CRASHING app! <img class='tooltip_style_control' id='cookies_size_warning_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative; left: -5px;' />" : 'none' )?>";
	
	
	<?php
     if ( isset($ct['app_container']) ) {
     ?>
     
	app_container = '<?=base64_encode($ct['app_container'])?>';
	
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
	
	linux_phpdesktop_notice_storage = storage_app_id("linux_phpdesktop_notice");
	
	desktop_windows_notice_storage = storage_app_id("desktop_windows_notice");
	
	donations_notice_storage = storage_app_id("donations_notice");
	
	refresh_cache_upgrade_notice_storage = storage_app_id("refresh_cache_upgrade_notice_storage");
	
	issues_page_visit_tracking_storage = storage_app_id("issues_page_visit_tracking_storage");
	
	notes_storage = storage_app_id("notes");
	
	auto_reload_storage = storage_app_id("auto_reload");
	
	folio_sorting_storage = storage_app_id("folio_sorting");
	
	number_format_storage = storage_app_id("number_format");
	
	priv_toggle_storage = storage_app_id("priv_toggle");
	
	priv_sec_storage = storage_app_id("priv_sec");
	
	show_charts_storage = storage_app_id("show_charts");
	
	show_feeds_storage = storage_app_id("show_feeds");
	
	
	    // v6.01.01 MIGRATIONS...
	    
	    
	    // (privacy TOGGLE to js local storage)
	    if ( get_cookie('priv_sec') ) {
	         
	         // ONLY migrate IF the var key has NOT been set yet!
	         // https://developer.mozilla.org/en-US/docs/Web/API/Storage/getItem
	         if ( localStorage.getItem(priv_sec_storage) === null ) {
	         localStorage.setItem(priv_sec_storage, decodeURIComponent( get_cookie('priv_sec') ) );
	         }

	    delete_cookie('priv_sec');

	    }
	    
	    
	    // (privacy TOGGLE to js local storage)
	    if ( get_cookie('priv_toggle') ) {
	         
	         // ONLY migrate IF the var key has NOT been set yet!
	         // https://developer.mozilla.org/en-US/docs/Web/API/Storage/getItem
	         if ( localStorage.getItem(priv_toggle_storage) === null ) {
	         localStorage.setItem(priv_toggle_storage, decodeURIComponent( get_cookie('priv_toggle') ) );
	         }

	    delete_cookie('priv_toggle');

	    }
	    
	    
	    // (portfolio sorting to js local storage)
	    if ( get_cookie('pref_number_format') ) {
	         
	         // ONLY migrate IF the var key has NOT been set yet!
	         // https://developer.mozilla.org/en-US/docs/Web/API/Storage/getItem
	         if ( localStorage.getItem(number_format_storage) === null ) {
	         localStorage.setItem(number_format_storage, decodeURIComponent( get_cookie('pref_number_format') ) );
	         }

	    delete_cookie('pref_number_format');

	    }
	    
	    
	    // (portfolio sorting to js local storage)
	    if ( get_cookie('sort_by') ) {
	         
	         // ONLY migrate IF the var key has NOT been set yet!
	         // https://developer.mozilla.org/en-US/docs/Web/API/Storage/getItem
	         if ( localStorage.getItem(folio_sorting_storage) === null ) {
	         localStorage.setItem(folio_sorting_storage, decodeURIComponent( get_cookie('sort_by') ) );
	         }

	    delete_cookie('sort_by');

	    }
	    
	    
	    // (auto-reload to js local storage)
	    if ( get_cookie('coin_reload') ) {
	         
	         // ONLY migrate IF the var key has NOT been set yet!
	         // https://developer.mozilla.org/en-US/docs/Web/API/Storage/getItem
	         if ( localStorage.getItem(auto_reload_storage) === null ) {
	         localStorage.setItem(auto_reload_storage, Number( decodeURIComponent( get_cookie('coin_reload') ) ) );
	         }

	    delete_cookie('coin_reload');

	    }
	         
	         
	    //console.log('localStorage.getItem(show_charts_storage) = ' + localStorage.getItem(show_charts_storage) );
	    
	    // (user-selected charts moved from cookie to js local storage)
	    if ( get_cookie('show_charts') ) {
	         
	         // ONLY migrate IF the var key has NOT been set yet!
	         // https://developer.mozilla.org/en-US/docs/Web/API/Storage/getItem
	         if ( localStorage.getItem(show_charts_storage) === null ) {
	         localStorage.setItem(show_charts_storage, decodeURIComponent( get_cookie('show_charts') ) );
	         }

	    delete_cookie('show_charts');

	    }
	    
	         
	    //console.log('localStorage.getItem(show_feeds_storage) = ' + localStorage.getItem(show_feeds_storage) );
	    
	    // (user-selected news feeds moved from cookie to js local storage)
	    if ( get_cookie('show_feeds') ) {
	         
	         // ONLY migrate IF the var key has NOT been set yet!
	         // https://developer.mozilla.org/en-US/docs/Web/API/Storage/getItem
	         if ( localStorage.getItem(show_feeds_storage) === null ) {
	         localStorage.setItem(show_feeds_storage, decodeURIComponent( get_cookie('show_feeds') ) );
	         }

	    delete_cookie('show_feeds');

	    }
	    
	
	// Logic based on vars in localStorage...
	
     pref_number_format = not_empty( localStorage.getItem(number_format_storage) ) ? localStorage.getItem(number_format_storage) : 'automatic';
	
	<?php
	if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' ) {
	?>
	charts_num = Number( str_search_count( localStorage.getItem(show_charts_storage) , '[') );
	<?php
	}
	// IF charts are NOT enabled, set to zero
	else {
	?>
	charts_num = Number(0);
	<?php
	}
	?>
	
	feeds_num = Number( str_search_count( localStorage.getItem(show_feeds_storage) , '[') );
     
     // Portfolio sorting
     var folio_sort_array = str_to_array( localStorage.getItem(folio_sorting_storage) , "|", false);
	
	sorted_by_col = not_empty( localStorage.getItem(folio_sorting_storage) ) ? folio_sort_array[0] : 0;
	
	sorted_direction = not_empty( localStorage.getItem(folio_sorting_storage) ) ? folio_sort_array[1] : 0;
     
	
	    <?php
	    if ( isset($ct['dev']['latest_important_dev_alerts_timestamp']) ) {
	    ?>
	    // JS-Compatible timestamp (as milliseconds)
	    latest_important_dev_alerts_timestamp = Number(<?=$ct['dev']['latest_important_dev_alerts_timestamp']?>000);
	    <?php
	    }
	    ?>
	
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
          
     	medium_sec_token = "<?=base64_encode( $ct['sec']->admin_nonce('medium_security_mode') )?>";
          
          <?php
          }
          ?>
	
	<?php
	}
	
	
	// Include any admin-logged-in stuff in ANY area
	if ( $ct['sec']->admin_logged_in() == true ) {
	?>
	
	admin_logged_in = true;
	
	gen_csrf_sec_token = '<?=base64_encode( $ct['sec']->admin_nonce('general_csrf_security') )?>';
	
	logs_csrf_sec_token = '<?=base64_encode( $ct['sec']->admin_nonce('logs_csrf_security') )?>';
	
	admin_iframe_url = storage_app_id("admin_iframe_url");
	
	<?php
	}
	
	
	// If desktop edition, cron emulation is enabled, and NOT on login form submission pages, run emulated cron
	if ( $ct['app_edition'] == 'desktop' && $ct['conf']['power']['desktop_cron_interval'] > 0 && !$ct['is_login_form'] ) {
	?>	
	
     emulated_cron_enabled = true;
     
	<?php
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
	
	foreach ( $ct['opt_conf']['crypto_pair_preferred_markets'] as $key => $unused ) {
	$secondary_mrkt_currencies .= '"'.strtolower($key).'", ';
	}
	
	foreach ( $ct['conf']['assets']['BTC']['pair'] as $key => $unused ) {
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
	

	// If a 2FA field needs to be highlighted (due to invalid input)
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
	
	
	<style>

	@import "templates/interface/css/tablesorter/theme.<?=$ct['sel_opt']['theme_selected']?>.css";
	
	
     <?php
     // ALL @IMPORT MUST BE AT VERY TOP OF ANY STYLE SECTION (BEFORE ANYTHING ELSE), SO BROWSERS WON'T IGNORE IT
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
     
	
	.tablesorter-<?=$ct['sel_opt']['theme_selected']?> .header, .tablesorter-<?=$ct['sel_opt']['theme_selected']?> .tablesorter-header {
     white-space: nowrap;
	}


     /* info icon size CSS selector */
     <?php
     // iframe info icon sizes are wonky for some reason in LINUX PHPDESKTOP (but works fine in modern browsers)
     if ( $ct['app_container'] == 'phpdesktop' && $ct['app_platform'] == 'linux' ) {
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


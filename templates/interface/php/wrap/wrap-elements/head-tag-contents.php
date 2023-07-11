
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
	
	admin_iframe_url = storage_app_id("admin_iframe_url");
	
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
	</script>
	

	<link rel="shortcut icon" href="templates/interface/media/images/favicon.png">
	<link rel="icon" href="templates/interface/media/images/favicon.png">

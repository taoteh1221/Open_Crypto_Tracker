<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 

header('Content-type: text/html; charset=' . $ct_conf['dev']['charset_default']);
?><!DOCTYPE html>
<html lang="en">

<!-- /*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */ -->

<head>

	<title></title>


   <base target="_parent">	<!-- ALL href links in the iframe will open in the main window -->
	
   <meta charset="<?=$ct_conf['dev']['charset_default']?>">
   
   <meta name="viewport" content="width=device-width"> <!-- Mobile compatibility -->
   
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
	
	<meta name="referrer" content="same-origin"> <!-- Keeps this URL private (BROWSER referral data won't be sent when clicking external links) -->
	
	
	<!-- Preload a few UI-related images -->
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/login-<?=$sel_opt['theme_selected']?>-theme.png" as="image">
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/notification-<?=$sel_opt['theme_selected']?>-line.png" as="image">
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/loader.gif" as="image">
	
	
	<script>
	
	// Install ID (derived from this app's server path)
	var ct_id = '<?=$ct_gen->id()?>';
	
	var app_edition = '<?=$app_edition?>';
	
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
	
	</script>
    
    
	<link rel="stylesheet" href="templates/interface/desktop/css/bootstrap/bootstrap.min.css" type="text/css" />

	<link rel="stylesheet" href="templates/interface/desktop/css/modaal.css" type="text/css" />
	
	<link  href="templates/interface/desktop/css/jquery-ui/jquery-ui.css" rel="stylesheet">
	
	<!-- Load theme styling last to over rule -->
	<link rel="stylesheet" href="templates/interface/desktop/css/style.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/desktop/css/<?=$sel_opt['theme_selected']?>.style.css" type="text/css" />
	
	<?php
	if ( $is_admin ) {
	?>
	<link rel="stylesheet" href="templates/interface/desktop/css/admin.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/desktop/css/<?=$sel_opt['theme_selected']?>.admin.css" type="text/css" />
	<?php
	}
	?>
	
	<style>

	@import "templates/interface/desktop/css/tablesorter/theme.<?=$sel_opt['theme_selected']?>.css";
	
	.tablesorter-<?=$sel_opt['theme_selected']?> .header, .tablesorter-<?=$sel_opt['theme_selected']?> .tablesorter-header {
    white-space: nowrap;
	}
	
	</style>


	<script src="app-lib/js/jquery/jquery-3.6.0.min.js"></script>

	<script src="app-lib/js/jquery/jquery.tablesorter.min.js"></script>

	<script src="app-lib/js/jquery/jquery.tablesorter.widgets.min.js"></script>

	<script src="app-lib/js/jquery/jquery.balloon.min.js"></script>
	
	<script src="app-lib/js/jquery/jquery-ui/jquery-ui.js"></script>

    <script src="app-lib/js/jquery/jquery.repeatable.js"></script>

	<script src="app-lib/js/modaal.js"></script>

	<script src="app-lib/js/autosize.min.js"></script>
	
	<script src="app-lib/js/zingchart.min.js"></script>
	
	<script src="app-lib/js/crypto-js.js"></script>

	<script src="app-lib/js/functions.js"></script>
	
	<?php
	// MSIE doesn't like highlightjs (LOL)
	if ( $ct_gen->is_msie() == false ) {
	?>
	
	<link rel="stylesheet" href="templates/interface/desktop/css/highlightjs.min.css" type="text/css" />
	
	<script src="app-lib/js/highlight.min.js"></script>
	
	<script>
	// Highlightjs configs
	hljs.configure({useBR: false}); // Don't use  <br /> between lines
	hljs.initHighlightingOnLoad(); // Load on page load
	</script>
	
	<?php
	}
	// Fix some minor MSIE CSS stuff
	else {
	?>
	<style>
	
	pre {
	color: #808080;
	}
	
	</style>
	<?php
	}
	?>
	
	<script>

    
    window.is_admin = false; // Default
	
	<?php
	// Flag admin area in js
	if ( $is_admin == true ) {
	?>	
	
    window.is_admin = true;
	
	<?php
	}
	?>
	
	// Set the global JSON config to asynchronous 
	// (so JSON requests run in the background, without pausing any of the page render scripting)
	$.ajaxSetup({
    async: true
	});
	
	var theme_selected = '<?=$sel_opt['theme_selected']?>';
	
	var sorted_by_col = <?=( $sel_opt['sorted_by_col'] ? $sel_opt['sorted_by_col'] : 0 )?>;
	var sorted_asc_desc = <?=( $sel_opt['sorted_asc_desc'] ? $sel_opt['sorted_asc_desc'] : 0 )?>;
	
	var charts_background = '<?=$ct_conf['power']['charts_background']?>';
	var charts_border = '<?=$ct_conf['power']['charts_border']?>';
	
	var btc_prim_currency_val = '<?=number_format( $sel_opt['sel_btc_prim_currency_val'], 2, '.', '' )?>';
	var btc_prim_currency_pair = '<?=strtoupper($ct_conf['gen']['btc_prim_currency_pair'])?>';
	
	
	<?php
	foreach ( $ct_conf['dev']['limited_apis'] as $api ) {
	$js_limited_apis .= '"'.strtolower( preg_replace("/\.(.*)/i", "", $api) ).'", ';
	}
	$js_limited_apis = trim($js_limited_apis);
	$js_limited_apis = rtrim($js_limited_apis,',');
	$js_limited_apis = trim($js_limited_apis);
	$js_limited_apis = '['.$js_limited_apis.']';
	?>

	var limited_apis = <?=$js_limited_apis?>;
	
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

	var secondary_mrkt_currencies = <?=$secondary_mrkt_currencies?>;
	
	var pref_bitcoin_mrkts = []; // Set the array
	
	<?php
	foreach ( $ct_conf['power']['btc_pref_currency_mrkts'] as $pref_bitcoin_mrkts_key => $pref_bitcoin_mrkts_val ) {
	?>
	pref_bitcoin_mrkts["<?=strtolower( $pref_bitcoin_mrkts_key )?>"] = "<?=strtolower( $pref_bitcoin_mrkts_val )?>";
	<?php
	}
	?>
    
	
	</script>

	<script src="app-lib/js/init.js"></script>



<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function(){   

$("#<?=$_GET['section']?>_loading").css({ "display": "none" });

section = '<?=$_GET['section']?>';

// Workaround for stubborn detection of iframe height once charts are loaded
iframe_height_extra = section == 'system_stats' ? 1000 : 100;

iframe_adjust_height('iframe_<?=$_GET['section']?>', iframe_height_extra);
 
});

</script>


<style>

html, body {
margin: 0px;
padding: 0px;
}

</style>


</head>
<body class='iframe_wrapper'>


    <div id='<?=$_GET['section']?>_loading' class='align_center loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <span id='background_loading_span'></span></div>
    
    <?php
    if ( $_GET['section'] == 'comms' ) {
    require("templates/interface/desktop/php/admin/admin-sections/comms.php");
    }
    elseif ( $_GET['section'] == 'general' ) {
    require("templates/interface/desktop/php/admin/admin-sections/general.php");
    }
    elseif ( $_GET['section'] == 'portfolio_assets' ) {
    require("templates/interface/desktop/php/admin/admin-sections/portfolio-assets.php");
    }
    elseif ( $_GET['section'] == 'charts_alerts' ) {
    require("templates/interface/desktop/php/admin/admin-sections/charts-and-alerts.php");
    }
    elseif ( $_GET['section'] == 'plugins' ) {
    require("templates/interface/desktop/php/admin/admin-sections/plugins.php");
    }
    elseif ( $_GET['section'] == 'power_user' ) {
    require("templates/interface/desktop/php/admin/admin-sections/power-user.php");
    }
    elseif ( $_GET['section'] == 'text_gateways' ) {
    require("templates/interface/desktop/php/admin/admin-sections/text-gateways.php");
    }
    elseif ( $_GET['section'] == 'proxy' ) {
    require("templates/interface/desktop/php/admin/admin-sections/proxy.php");
    }
    elseif ( $_GET['section'] == 'developer' ) {
    require("templates/interface/desktop/php/admin/admin-sections/developer.php");
    }
    elseif ( $_GET['section'] == 'api' ) {
    require("templates/interface/desktop/php/admin/admin-sections/api.php");
    }
    elseif ( $_GET['section'] == 'webhook' ) {
    require("templates/interface/desktop/php/admin/admin-sections/webhook.php");
    }
    elseif ( $_GET['section'] == 'system_stats' ) {
    require("templates/interface/desktop/php/admin/admin-sections/system-stats.php");
    }
    elseif ( $_GET['section'] == 'access_stats' ) {
    require("templates/interface/desktop/php/admin/admin-sections/access-stats.php");
    }
    elseif ( $_GET['section'] == 'logs' ) {
    require("templates/interface/desktop/php/admin/admin-sections/app-logs.php");
    }
    elseif ( $_GET['section'] == 'backup_restore' ) {
    require("templates/interface/desktop/php/admin/admin-sections/backup-restore.php");
    }
    elseif ( $_GET['section'] == 'reset' ) {
    require("templates/interface/desktop/php/admin/admin-sections/reset.php");
    }
    ?>


</body>
</html>
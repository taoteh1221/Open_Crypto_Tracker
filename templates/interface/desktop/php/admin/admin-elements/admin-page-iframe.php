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

<head>

	<title></title>
	
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
	
	var admin_area_sec_level = '<?=$admin_area_sec_level?>';
	
	var logs_csrf_sec_token = '<?=base64_encode( $ct_gen->admin_hashed_nonce('logs_csrf_security') )?>';
	
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

	<script src="app-lib/js/base64-decode.js"></script>

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

	
	window.is_iframe = true;
    
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


<style>

html, body {
margin: 0px;
padding: 0px;
}

</style>

<script>

// Dynamically add 'enhanced_security_nonce' to ALL admin forms, IF enhanced security mode is being used
$(document).ready(function(){


    if ( admin_area_sec_level == 'enhanced' ) {

    var forms_array = document.getElementsByTagName("form");
    
    
        for (var form_count = 0; form_count < forms_array.length; form_count++) {
                
        has_enhanced_security_nonce = false;
            
        inputs_array = forms_array[form_count].getElementsByTagName("input");
            
            
            for (var input_count = 0; input_count < inputs_array.length; input_count++) {
                
                if ( inputs_array[input_count].name == 'enhanced_security_nonce' ) {
                has_enhanced_security_nonce = true;
                }
            
            }
            
            
            if ( has_enhanced_security_nonce == false ) {
                
            new_input = document.createElement("input");
        
            new_input.setAttribute("type", "hidden");
            
            new_input.setAttribute("name", "enhanced_security_nonce");
            
            new_input.setAttribute("value", "<?=$ct_gen->admin_hashed_nonce('enhanced_security_mode')?>");
            
            forms_array[form_count].appendChild(new_input);
            
            }
            
        
        }
        
    
    }
    
	
});

</script>

</head>
<body class='iframe_wrapper'>
    
    
<?php

// Admin template to use    
if ( $admin_area_sec_level == 'enhanced' && !$ct_gen->pass_sec_check($_POST['enhanced_security_nonce'], 'enhanced_security_mode') ) {
require("templates/interface/desktop/php/admin/admin-elements/iframe-security-mode.php");
}
elseif ( isset($_GET['section']) ) {
require("templates/interface/desktop/php/admin/admin-elements/iframe-content-category.php");
}
elseif ( isset($_GET['plugin']) ) {
require("templates/interface/desktop/php/admin/admin-elements/iframe-content-plugin.php");
}
    	
    	
// Proxy alerts (if setup by user, and any of them failed, test the failed proxies and log/alert if they seem offline)
if ( $ct_conf['comms']['proxy_alert'] != 'off' ) {
	
	foreach ( $proxy_checkup as $problem_proxy ) {
	$ct_gen->test_proxy($problem_proxy);
	sleep(1);
	}

}
          	
// Log errors, send notifications
$error_log = $ct_cache->error_log();
$debug_log = $ct_cache->debug_log();
$ct_cache->send_notifications();


// IF WE HAVE A LOG WRITE ERROR FOR ANY LOGS, PRINT IT IN THE FOOTER HERE
		
if ( $error_log != true ) {
?>
<div class="red" style='font-weight: bold;'><?=$error_log?></div>
<?php
}
		
if ( $ct_conf['dev']['debug'] != 'off' && $debug_log != true ) {
?>
<div class="red" style='font-weight: bold;'><?=$debug_log?></div>
<?php
}
    		

?>

            	
<div id="iframe_error_alert" style='display: none;'><?php echo $alerts_gui_errors . ( isset($alerts_gui_debugging) && $alerts_gui_debugging != '' ? '============<br />DEBUGGING:<br />============<br />' . $alerts_gui_debugging : '' ); ?></div>

	
<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {
    
//console.log('admin iframe "<?=$_GET['section']?>" loaded.'); // DEBUGGING
    
<?php

// If we need to refresh an admin iframe, to show the updated data
if ( $_GET['refresh'] ) {
    
    
    if ( $_GET['refresh'] == 'all' ) {
    
    $refresh_admin = array(
                            'iframe_comms',
                            'iframe_ext_api',
                            'iframe_general',
                            'iframe_portfolio_assets',
                            'iframe_charts_alerts',
                            'iframe_plugins',
                            'iframe_power_user',
                            'iframe_text_gateways',
                            'iframe_proxy',
                            'iframe_developer',
                            'iframe_int_api',
                            'iframe_webhook',
                            'iframe_system_stats',
                            'iframe_access_stats',
                            'iframe_logs',
                            'iframe_backup_restore',
                            'iframe_reset',
                           );
    
    }
    else {
    $refresh_admin = explode(',', $_GET['refresh']);
    }
    

    foreach ( $refresh_admin as $refresh ) {

        if ( isset($refresh) && trim($refresh) != '' ) {
        ?>
        parent.document.getElementById('<?=$refresh?>').contentWindow.location.reload(true);
        <?php
        }
    
    }
    

}

?>

});

</script>  	

<!-- https://v4-alpha.getbootstrap.com/getting-started/introduction/#starter-template -->
<script src="app-lib/js/jquery/bootstrap.min.js"></script>
</body>
</html>

<!-- /*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */ -->
 
 <?php
 
flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

 // In case we are redirected to a login template, we include this exit...
 // IN #ANY# CASE, WE SHOULD BE COMPLETELY DONE RENDERING AT THIS POINT
 exit;
 ?>
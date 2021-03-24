<?php
header('Content-type: text/html; charset=' . $app_config['developer']['charset_default']);
?><!DOCTYPE html>
<html lang="en">

<!-- /*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */ -->

<head>


	<title>Open Crypto Portfolio Tracker<?=( $is_admin ? ' - Admin Config' : '' )?></title>
    

   <meta charset="<?=$app_config['developer']['charset_default']?>">
   
   <meta name="viewport" content="width=device-width"> <!-- Mobile compatibility -->
   
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
	
	<meta name="referrer" content="same-origin"> <!-- Keeps this URL private (BROWSER referral data won't be sent when clicking external links) -->
	
	
	<!-- Preload a few UI-related images -->
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/login-<?=$theme_selected?>-theme.png" as="image">
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/notification-<?=$theme_selected?>-line.png" as="image">
	
	<link rel="preload" href="templates/interface/media/images/auto-preloaded/loader.gif" as="image">
	
	
	<script>
	
	// Preload /images/auto-preloaded/ images VIA JAVASCRIPT TOO (WAY MORE RELIABLE THAN META TAG PRELOAD)
	
	<?php
	$preloaded_files_dir = 'templates/interface/media/images/auto-preloaded';
	$preloaded_files = list_files($preloaded_files_dir);
	
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
    
    
	<link rel="stylesheet" href="templates/interface/css/bootstrap/bootstrap.min.css" type="text/css" />

	<link rel="stylesheet" href="templates/interface/css/modaal.css" type="text/css" />
	
	<link  href="templates/interface/css/jquery-ui/jquery-ui.css" rel="stylesheet">
	
	<!-- Load theme styling last to over rule -->
	<link rel="stylesheet" href="templates/interface/css/style.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/<?=$theme_selected?>.style.css" type="text/css" />
	
	<?php
	if ( $is_admin ) {
	?>
	<link rel="stylesheet" href="templates/interface/css/admin.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/<?=$theme_selected?>.admin.css" type="text/css" />
	<?php
	}
	?>
	
	<style>

	@import "templates/interface/css/tablesorter/theme.<?=$theme_selected?>.css";
	
	.tablesorter-<?=$theme_selected?> .header, .tablesorter-<?=$theme_selected?> .tablesorter-header {
    white-space: nowrap;
	}
	
	</style>


	<script src="app-lib/js/jquery/jquery-3.4.1.min.js"></script>

	<script src="app-lib/js/jquery/jquery.tablesorter.min.js"></script>

	<script src="app-lib/js/jquery/jquery.tablesorter.widgets.min.js"></script>

	<script src="app-lib/js/jquery/jquery.balloon.min.js"></script>
	
	<script src="app-lib/js/jquery/jquery-ui/jquery-ui.js"></script>

	<script src="app-lib/js/modaal.js"></script>

	<script src="app-lib/js/autosize.min.js"></script>
    
   <!-- USAGE: http://underscorejs.org -->
   <script src="app-lib/js/underscore-min.js"></script>
	
	<script src="app-lib/js/zingchart.min.js"></script>

	<script src="app-lib/js/functions.js"></script>
	
	<?php
	// MSIE doesn't like highlightjs (LOL)
	if ( is_msie() == false ) {
	?>
	
	<link rel="stylesheet" href="templates/interface/css/highlightjs.min.css" type="text/css" />
	
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

	// Set the global JSON config to asynchronous 
	// (so JSON requests run in the background, without pausing any of the page render scripting)
	$.ajaxSetup({
    async: true
	});
	
	// Main js vars
	var theme_selected = '<?=$theme_selected?>';
	
	var feeds_num = <?=( $show_feeds[0] != '' ? sizeof($show_feeds) : 0 )?>;
	var feeds_loaded = new Array();
	
	var charts_num = <?=( $show_charts[0] != '' ? sizeof($show_charts) : 0 )?>;
	var charts_loaded = new Array();
	
	var sorted_by_col = <?=$sorted_by_col?>;
	var sorted_by_asc_desc = <?=$sorted_by_asc_desc?>;
	
	var charts_background = '<?=$app_config['power_user']['charts_background']?>';
	var charts_border = '<?=$app_config['power_user']['charts_border']?>';
	
	var btc_primary_currency_value = '<?=number_format( $selected_btc_primary_currency_value, 2, '.', '' )?>';
	var btc_primary_currency_pairing = '<?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?>';
	
	// 'Loading X...' UI notices
	feeds_loading_check(window.feeds_loaded);
	charts_loading_check(window.charts_loaded);
	
	<?php
	foreach ( $app_config['developer']['limited_apis'] as $api ) {
	$js_limited_apis .= '"'.strtolower( preg_replace("/\.(.*)/i", "", $api) ).'", ';
	}
	$js_limited_apis = trim($js_limited_apis);
	$js_limited_apis = rtrim($js_limited_apis,',');
	$js_limited_apis = trim($js_limited_apis);
	$js_limited_apis = '['.$js_limited_apis.']';
	?>

	var limited_apis = <?=$js_limited_apis?>;
	
	var preferred_bitcoin_markets = []; // Set the array
	<?php
	foreach ( $app_config['power_user']['bitcoin_preferred_currency_markets'] as $preferred_bitcoin_markets_key => $preferred_bitcoin_markets_value ) {
	?>
	preferred_bitcoin_markets["<?=strtolower( $preferred_bitcoin_markets_key )?>"] = "<?=strtolower( $preferred_bitcoin_markets_value )?>";
	<?php
	}
	?>
	
	</script>

	<script src="app-lib/js/init.js"></script>

	<script src="app-lib/js/random-tips.js"></script>


	<link rel="shortcut icon" href="templates/interface/media/images/favicon.png">
	<link rel="icon" href="templates/interface/media/images/favicon.png">

</head>
<body onbeforeunload="store_scroll_position();">

    
    <audio preload="metadata" id="audio_alert">
      <source src="templates/interface/media/audio/Intruder_Alert-SoundBible.com-867759995.mp3">
      <source src="templates/interface/media/audio/Intruder_Alert-SoundBible.com-867759995.ogg">
    </audio>
    
	 
    <div class='align_center' id='body_wrapper' style='<?=( $login_template == 1 ? 'min-width: 720px; max-width: 800px;' : '' )?>'>
    
    
		<div class='align_center' id='body_top_nav' style='<?=( $login_template == 1 ? 'min-width: 720px; max-width: 800px;' : '' )?>'>
		
		
			<!-- START #topnav-content -->
			<nav id='topnav' class="navbar navbar-expand align_center" style='<?=( $login_template == 1 ? 'min-width: 720px; max-width: 800px;' : '' )?>'>
			   
				<?php
				// Filename info, to dynamically render active menu link displaying
			   $script_file_info = pathinfo($_SERVER['SCRIPT_FILENAME']);
				?>
				
			  	<div class="collapse navbar-collapse" id="navbarSupportedContent">
			  
					<ul id='admin_nav' class="navbar-nav" style='right: 4px; bottom: 4px;'>
					
				  		<li class="nav-item dropdown align_center">
					
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src='templates/interface/media/images/auto-preloaded/login-<?=$theme_selected?>-theme.png' height='27' border='0' /></a>
					
							<div class="dropdown-menu shadow-lg p-3 mb-5 bg-white rounded" aria-labelledby="navbarDropdown">
							
					  			<a class="dropdown-item<?=( $script_file_info['basename'] == 'admin.php' ? ' active' : '' )?>" href="admin.php">Admin Config</a>
					  			
					  			<a class="dropdown-item<?=( $script_file_info['basename'] == 'index.php' ? ' active' : '' )?>" href="index.php">Portfolio</a>
					  			
					  			<?php
					  			if ( isset( $_SESSION['admin_logged_in'][md5($base_url)] ) ) {
					  			?>
					  			<a class="dropdown-item" href="?logout=1&admin_hashed_nonce=<?=admin_hashed_nonce('logout')?>">Logout</a>
					  			<?php
					  			}
					 			?>
					  
							</div>
					
				  		</li>
				  		
					</ul>
				
				
				<h2>Open Crypto Portfolio Tracker<?=( $is_admin ? ' - Admin Config' : '' )?></h2>
				
				
					<div id="navbarDropdownBell" class="navbar-nav dropleft" style='left: 12px;'>
				
  						<a class="nav-link" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img id='alert_bell_image' src='templates/interface/media/images/auto-preloaded/notification-<?=$theme_selected?>-line.png' height='30' border='0' /></a>
  
  						<!-- Alerts div centering wrapper -->
  						<div id='alert_bell_wrapper' style='position:absolute; top: 46px; left: 50%;'>
  
  							<div id='alert_bell_area' class="dropdown-menu red" aria-labelledby="navbarDropdown2">
 							<!-- alerts output dynamically here -->
  							</div>
  	
  						</div>
  
					</div>
				
			  	</div>
				
			</nav>
  	
  	
  	<script>
  	
  	// Get how far from left edge of PARENT containers we are, on relevent containers
  	var pos_topnav = document.getElementById('topnav').getBoundingClientRect();
  	var pos_bell = document.getElementById('navbarDropdownBell').getBoundingClientRect();
	
	 // Amount left of #navbarDropdownBell from #topnav edge, MINUS amount left of #topnav from page edge
	var alerts_window_to_left = Math.round(pos_bell.left - pos_topnav.left);
	
	// Dynamically move alerts window further left (using left position, MINUS left MORE)
	$("#alert_bell_wrapper").css({ "left": '-' + alerts_window_to_left + 'px' });
	
  	</script>
  	
  	
				<!-- END #topnav-content -->
		
		
		</div>
		 
    
	 	<div class='align_center loading bitcoin' id='app_loading'>
	 	<img src="templates/interface/media/images/auto-preloaded/loader.gif" height='57' alt="" style='vertical-align: middle;' /> <span id='app_loading_span'>Loading...</span>
	 	</div>
	 
		
		<div class='align_left' id='content_wrapper'>
				
				<?php
				if ( $ui_upgrade_alert['run'] == 'yes' ) {
					
					// If this isn't google or bing spidering the web, show the upgrade notice one time until the next reminder period
					if ( stristr($_SERVER['HTTP_USER_AGENT'], 'googlebot') == false && stristr($_SERVER['HTTP_USER_AGENT'], 'bingbot') == false ) {
				?>
				<div class="alert alert-warning" role="alert">
  					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    					<span aria-hidden="true">&times;</span>
  					</button>
				  	<strong>Upgrade Notice:</strong> <?=$ui_upgrade_alert['message']?> 
				</div>
				<?php
				
				// Set back to 'run' => 'no' 
				// (will automatically re-activate in upgrade-check.php at a later date, if another reminder is needed after X days)
				$ui_upgrade_alert = array(
														'run' => 'no',
														'message' => null
														);
						
				store_file_contents($base_dir . '/cache/events/ui_upgrade_alert.dat', json_encode($ui_upgrade_alert, JSON_PRETTY_PRINT) );
				
					}
					
				}
				?>
		 
				<div id='loading_subsections' class='align_center loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <span id='loading_subsections_span'></span></div>
		
					
		<!-- header.php END -->
			
	

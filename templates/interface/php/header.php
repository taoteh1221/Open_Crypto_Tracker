<?php
header('Content-type: text/html; charset=' . $app_config['developer']['charset_default']);
?><!DOCTYPE html>
<html lang="en">

<!-- /*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->

<head>

	<title>DFD Cryptocoin Values - <?=( $is_admin ? 'Admin Configuration' : 'Portfolio Tracker' )?></title>
    
   <meta charset="<?=$app_config['developer']['charset_default']?>">
   
   <meta name="viewport" content="width=device-width"> <!-- Mobile compatibility -->
   
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
	
	<meta name="referrer" content="same-origin"> <!-- Keeps this URL private (referral data won't be sent when clicking external links) -->
    
	<link rel="stylesheet" href="templates/interface/css/bootstrap/bootstrap.min.css" type="text/css" />

	<link rel="stylesheet" href="templates/interface/css/modaal.css" type="text/css" />
	
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

	<script src="app-lib/js/modaal.js"></script>

	<script src="app-lib/js/autosize.min.js"></script>

	<script src="app-lib/js/functions.js"></script>
	
	<script src="app-lib/js/zingchart.min.js"></script>
	
	<?php
	// MSIE doesn't like highlightjs
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
	
	
	var feeds_num = <?=( $show_feeds[0] != '' ? sizeof($show_feeds) : 0 )?>;
	var feeds_loaded = new Array();
	
	var charts_num = <?=( $show_charts[0] != '' ? sizeof($show_charts) : 0 )?>;
	var charts_loaded = new Array();
	
	feeds_loading_check(window.feeds_loaded);
	charts_loading_check(window.charts_loaded);
	
	var sorted_by_col = <?=$sorted_by_col?>;
	var sorted_by_asc_desc = <?=$sorted_by_asc_desc?>;
	var theme_selected = '<?=$theme_selected?>';

	// Preload ajax placeholder image
	var loader_image = new Image();
	loader_image.src = 'templates/interface/media/images/loader.gif';
	var loader_image_2 = new Image();
	loader_image.src_2 = 'templates/interface/media/images/notification-' + theme_selected + '-fill.png';
	
	
	var charts_background = '<?=$app_config['power_user']['charts_background']?>';
	
	var charts_border = '<?=$app_config['power_user']['charts_border']?>';
	
	var btc_primary_currency_value = '<?=number_format( $selected_btc_primary_currency_value, 2, '.', '' )?>';
	
	var btc_primary_currency_pairing = '<?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?>';
	
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
			   <nav id='topnav' class="navbar navbar-expand align_center">
				<?php
				// Filename info, to dynamically render active menu link displaying
			   $script_file_info = pathinfo($_SERVER['SCRIPT_FILENAME']);
				?>
			  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			  
				<ul id='admin_nav' class="navbar-nav" style='right: 3px;'>
				  <li class="nav-item dropdown align_center">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src='templates/interface/media/images/login-<?=$theme_selected?>-theme.png' width='30' border='0' /></a>
					<div class="dropdown-menu shadow-lg p-3 mb-5 bg-white rounded" aria-labelledby="navbarDropdown">
					  <a class="dropdown-item<?=( $script_file_info['basename'] == 'admin.php' ? ' active' : '' )?>" href="admin.php">Admin Configuration</a>
					  <a class="dropdown-item<?=( $script_file_info['basename'] == 'index.php' ? ' active' : '' )?>" href="index.php">Portfolio Tracker</a>
					  <?php
					  if ( sizeof($stored_admin_login) == 2 && isset($_SESSION['admin_logged_in']) ) {
					  ?>
					  <a class="dropdown-item" href="?logout=1&admin_hashed_nonce=<?=admin_hashed_nonce('logout')?>">Logout</a>
					  <?php
					  }
					  ?>
					</div>
				  </li>
				</ul>
				
				<h2>DFD Cryptocoin Values - <?=( $is_admin ? 'Admin Configuration' : 'Portfolio Tracker' )?></h2>
				
				<div class="navbar-nav dropleft" style='left: 10px;'>
  <a class="nav-link" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img id='alert_bell_image' src='templates/interface/media/images/notification-<?=$theme_selected?>-line.png' width='30' border='0' /></a>
  <div id='alert_bell_area' class="dropdown-menu red" aria-labelledby="navbarDropdown2">
 <!-- alerts output dynamically here -->
  </div>
				</div>
				
			  </div>
				
				</nav>
				<!-- END #topnav-content -->
		
		
		</div>
		 
    
	 	<div class='align_center loading bitcoin' id='app_loading'>
	 	<img src="templates/interface/media/images/loader.gif" height='57' alt="" style='vertical-align: middle;' /> <span id='app_loading_span'>Loading...</span>
	 	</div>
	 
		
		<div class='align_left' id='content_wrapper'>
				
				<?php
				if ( $ui_upgrade_alert['run'] == 'yes' ) {
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
				?>
		 
				<div id='loading_subsections' class='align_center loading bitcoin'><img src="templates/interface/media/images/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <span id='loading_subsections_span'></span></div>
		
					
		<!-- header.php END -->
			
	

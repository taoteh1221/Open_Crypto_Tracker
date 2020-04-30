<?php

	
// Set a max execution time, TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $app_config['developer']['debug_mode'] != 'off' ) {
ini_set('max_execution_time', 350);
}
else {
ini_set('max_execution_time', $app_config['developer']['ui_max_execution_time']);
}


header('Content-type: text/html; charset=' . $app_config['developer']['charset_default']);


?><!DOCTYPE html>
<html lang="en">

<!-- /*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->

<head>

	<title>DFD Cryptocoin Values - <?=( $is_admin == 1 ? 'Admin Configuration' : 'Portfolio Tracker' )?></title>
    
   <meta charset="<?=$app_config['developer']['charset_default']?>">
   
   <meta name="viewport" content="width=device-width">
   
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
    
	<link rel="stylesheet" href="templates/interface/css/bootstrap/bootstrap.min.css" type="text/css" />

	<link rel="stylesheet" href="templates/interface/css/modaal.css" type="text/css" />
	
	<!-- Load theme styling last to over rule -->
	<link rel="stylesheet" href="templates/interface/css/style.css" type="text/css" />
	
	<link rel="stylesheet" href="templates/interface/css/<?=$theme_selected?>.style.css" type="text/css" />
	
	<?php
	if ( $is_admin == 1 ) {
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
	if ( $app_config['general']['charts_toggle'] == 'on' ) {
	?>
	<script>
	var charts_num = <?=( sizeof($show_charts) > 0 ? sizeof($show_charts) : 0 )?>;
	var charts_loaded = new Array();
	charts_loading_check(charts_loaded);
	</script>
	<?php
	}
	?>

	<script src="app-lib/js/init.js"></script>
	
	<script>
	
	// Preload ajax placeholder image
	var loader_image = new Image();
	loader_image.src = 'templates/interface/media/images/loader.gif';
	
	var sorted_by_col = <?=$sorted_by_col?>;
	var sorted_by_asc_desc = <?=$sorted_by_asc_desc?>;
	var tablesort_theme = '<?=$theme_selected?>';
	
	var charts_background = '<?=$app_config['charts_price_alerts']['charts_background']?>';
	
	var charts_border = '<?=$app_config['charts_price_alerts']['charts_border']?>';
	
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
	
	var preferred_bitcoin_markets = [];
	<?php
	foreach ( $app_config['power_user']['bitcoin_preferred_currency_markets'] as $preferred_bitcoin_markets_key => $preferred_bitcoin_markets_value ) {
	?>
	preferred_bitcoin_markets["<?=strtolower( $preferred_bitcoin_markets_key )?>"] = "<?=strtolower( $preferred_bitcoin_markets_value )?>";
	<?php
	}
	?>
	
	</script>


	<link rel="shortcut icon" href="templates/interface/media/images/favicon.png">
	<link rel="icon" href="templates/interface/media/images/favicon.png">

</head>
<body>

    
    <audio preload="metadata" id="audio_alert">
      <source src="templates/interface/media/audio/Smoke-Alarm-SoundBible-1551222038.mp3">
      <source src="templates/interface/media/audio/Smoke-Alarm-SoundBible-1551222038.ogg">
    </audio>
    
	 
    <div class='align_center' id='body_wrapper'>
    
    
		<div class='align_center' id='body_top_nav'>
		
		
				<!-- START #topnav-content -->
			   <nav id='topnav' class="navbar navbar-expand align_center">
				<?php
				// Filename info, to dynamically render active menu link displaying
			   $script_file_info = pathinfo($_SERVER['SCRIPT_FILENAME']);
				?>
			  <div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav">
				  <li class="nav-item dropdown align_center">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src='templates/interface/media/images/login-<?=$theme_selected?>-theme.png' width='30' border='0' /></a>
					<div class="dropdown-menu shadow-lg p-3 mb-5 bg-white rounded" aria-labelledby="navbarDropdown">
					  <a class="dropdown-item<?=( $script_file_info['basename'] == 'admin.php' ? ' active' : '' )?>" href="admin.php">Admin Configuration</a>
					  <a class="dropdown-item<?=( $script_file_info['basename'] == 'index.php' ? ' active' : '' )?>" href="index.php">Portfolio Tracker</a>
					  <?php
					  if ( isset($admin_login) && isset($_SESSION['admin_login']) ) {
					  ?>
					  <a class="dropdown-item" href="?logout=1&nonce=<?=$_SESSION['nonce']?>">Logout</a>
					  <?php
					  }
					  ?>
					</div>
				  </li>
				</ul>
				<h2>DFD Cryptocoin Values - <?=( $is_admin == 1 ? 'Admin Configuration' : 'Portfolio Tracker' )?></h2>
			  </div>
				
				</nav>
				<!-- END #topnav-content -->
		
		
		</div>
		 
    
	 	<div class='align_center loading bitcoin' id='app_loading'>
	 	<img src="templates/interface/media/images/loader.gif" height='60' alt="" style='vertical-align: middle;' /> <span id='app_loading_span'>Loading App...</span>
	 	</div>
	 
		
		<div class='align_left' id='content_wrapper'>
				
		 
				<div id='loading_subsections' class='align_center loading bitcoin'><img src="templates/interface/media/images/loader.gif" height='20' alt="" style='vertical-align: middle;' /> <span id='loading_subsections_span'></span></div>
		
					
		<!-- header.php END -->
			
	

<?php

// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;

// Runtime mode
$runtime_mode = 'ui';

require("config.php");

header('Content-type: text/html; charset=' . $app_config['charset_default']);

?><!DOCTYPE html>
<html lang="en">

<!-- /*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->

<head>

	<title>DFD Cryptocoin Values</title>
    
    <meta charset="<?=$app_config['charset_default']?>">
    <meta name="viewport" content="width=device-width">
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
    
	<link rel="stylesheet" href="templates/interface/css/bootstrap/bootstrap.min.css" type="text/css" />

	<link rel="stylesheet" href="templates/interface/css/modaal.css" type="text/css" />
	
	<!-- Load theme styling last to over rule -->
	<link rel="stylesheet" href="templates/interface/css/style.css" type="text/css" />
	<link rel="stylesheet" href="templates/interface/css/<?=$theme_selected?>.style.css" type="text/css" />


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

	<script src="app-lib/js/functions.js"></script>

	<?php
	if ( $app_config['charts_page'] == 'on' || $app_config['system_stats'] != 'off' ) {
	?>
	<script src="app-lib/js/zingchart.min.js"></script>
	<?php
	}
	if ( $app_config['charts_page'] == 'on' ) {
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
	
	var sorted_by_col = <?=$sorted_by_col?>;
	var sorted_by_asc_desc = <?=$sorted_by_asc_desc?>;
	var tablesort_theme = '<?=$theme_selected?>';
	
	var charts_background = '<?=$app_config['charts_background']?>';
	
	var charts_border = '<?=$app_config['charts_border']?>';
	
	var btc_primary_currency_value = '<?=number_format( $btc_primary_currency_value, 2, '.', '' )?>';
	
	var btc_primary_currency_pairing = '<?=strtoupper($app_config['btc_primary_currency_pairing'])?>';
	
	<?php
	foreach ( $app_config['limited_apis'] as $api ) {
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
	foreach ( $app_config['preferred_bitcoin_markets'] as $preferred_bitcoin_markets_key => $preferred_bitcoin_markets_value ) {
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

    <div align='center' style='width: 100%; min-width: 1200px; margin: auto;'>
    <h2>DFD Cryptocoin Values - Cryptocurrency Portfolio Tracker</h2>
            <div align='left' style=' margin: 0px; min-width: 1200px; display: inline;'>
            
					<?php
					if ( $app_config['charts_page'] == 'on' ) {
					?>
            	<div align='center' id='loading_charts' class='red'>Loading charts...</div>
					<?php
					}
					?>
					
        <!-- header END -->
        



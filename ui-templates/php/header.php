<?php

$runtime_mode = 'ui';

require("config.php");

?><!DOCTYPE html>
<html lang="en">

<!-- /*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->

<head>

	<title>DFD Cryptocoin Values - Open source / free private cryptocurrency investment portfolio tracker, with email / text / Alexa alerts, charts, mining calculators, leverage / gain / loss / balance stats, and other crypto tools</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
	<meta name="robots" content="noindex,nofollow"> <!-- Keeps this URL private (search engines won't add this URL to their search indexes) -->
    
	<link rel="stylesheet" href="ui-templates/css/bootstrap/bootstrap.min.css" type="text/css" />

	<link rel="stylesheet" href="ui-templates/css/modaal.css" type="text/css" />
	
	<!-- Load theme styling last to over rule -->
	<link rel="stylesheet" href="ui-templates/css/style.css" type="text/css" />
	<link rel="stylesheet" href="ui-templates/css/<?=$theme_selected?>.style.css" type="text/css" />


	<style>

	@import "ui-templates/css/tablesorter/theme.<?=$theme_selected?>.css";
	
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
	if ( $charts_page == 'on' ) {
	?>
	<script src="app-lib/js/zingchart.min.js"></script>
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
	
	var charts_background = '<?=$charts_background?>';
	
	var charts_border = '<?=$charts_border?>';
	
	var btc_fiat_value = '<?=number_format( $btc_fiat_value, 2, '.', '' )?>';
	
	var btc_fiat_pairing = '<?=strtoupper($btc_fiat_pairing)?>';
	
	</script>


	<link rel="shortcut icon" href="ui-templates/media/images/favicon.png">
	<link rel="icon" href="ui-templates/media/images/favicon.png">

</head>
<body>
    
    <audio preload="metadata" id="audio_alert">
      <source src="ui-templates/media/audio/Smoke-Alarm-SoundBible-1551222038.mp3">
      <source src="ui-templates/media/audio/Smoke-Alarm-SoundBible-1551222038.ogg">
    </audio>

    <div align='center' style='width: 100%; min-width: 1200px; margin: auto;'>
    <h2>DFD Cryptocoin Values - Cryptocurrency Portfolio Tracker</h2>
            <div align='left' style=' margin: 0px; min-width: 1200px; display: inline;'>
            
					<?php
					if ( $charts_page == 'on' ) {
					?>
            	<div align='center' id='loading_charts' class='red'>Loading charts...</div>
					<?php
					}
					?>
					
        <!-- header END -->
        



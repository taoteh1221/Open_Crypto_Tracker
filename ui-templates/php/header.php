<?php

// Start measuring page load time
$load_time = microtime();
$load_time = explode(' ', $load_time);
$load_time = $load_time[1] + $load_time[0];
$start = $load_time;

$runtime_mode = 'ui';

$tablesort_theme = 'default';

require("config.php");

?><!DOCTYPE html>
<html>
    <!-- /*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->

<head>
<meta charset="UTF-8">
    <title>DFD Cryptocoin Values</title>
<link rel="stylesheet" href="ui-templates/css/style.css" type="text/css" />

<script type="text/javascript" src="app-lib/js/jquery/jquery1.8.3.min.js"></script>

<script type="text/javascript" src="app-lib/js/jquery/jquery.tablesorter.min.js"></script>

<script type="text/javascript" src="app-lib/js/jquery/jquery.tablesorter.widgets.min.js"></script>

<script type="text/javascript" src="app-lib/js/jquery/jquery.balloon.min.js"></script>

<script type="text/javascript" src="app-lib/js/functions.js"></script>

<script>
var sorted_by_col = <?=$sorted_by_col?>;
var sorted_by_asc_desc = <?=$sorted_by_asc_desc?>;
var tablesort_theme = '<?=$tablesort_theme?>';
</script>

<script type="text/javascript" src="app-lib/js/init.js"></script>

<style>
    
.tablesorter-<?=$tablesort_theme?> .header, .tablesorter-default .tablesorter-header {
    white-space: nowrap;
}

</style>

</head>
<body>
    
    <audio preload="metadata" id="audio_alert">
      <source src="ui-templates/media/audio/Smoke-Alarm-SoundBible-1551222038.mp3">
      <source src="ui-templates/media/audio/Smoke-Alarm-SoundBible-1551222038.ogg">
    </audio>

    <div align='center' style='width: 98%; margin: auto;'>
    <p> &nbsp; </p>
        <div align='center' style='min-width: 1320px; margin: auto;'>
            <div align='left' style=' width: 1320px; margin: auto;'>
        <!- header END -->



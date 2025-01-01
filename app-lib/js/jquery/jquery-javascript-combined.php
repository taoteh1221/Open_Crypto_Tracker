<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// First of all send javascript header
header("Content-type: application/javascript");

// Array of JS files
$js = array(
    'jquery-3.6.3.min.js',
    'jquery.tablesorter.min.js',
    'jquery.tablesorter.widgets.min.js',
    'jquery.tablesorter.pager.js',
    'jquery.tablesorter.pager-custom-controls.js',
    'jquery.balloon.min.js',
    'jquery.repeatable.js',
    'jstree.min.js',
    'jquery.mCustomScrollbar.concat.min.js',
);

// Prevent a notice
$js_content = '';

// Loop the js Array
foreach ($js as $js_file) {
     
$file_contents = file_get_contents($js_file);

// Load the content of the js file 
$js_content .= "\n\n\n\n" . '/******* COMBINED JAVASCRIPT FILE: '.$js_file.' *******/ ' . "\n\n\n\n" . $file_contents;

}

// print the js content
echo $js_content;

gc_collect_cycles(); // Clean memory cache

?>
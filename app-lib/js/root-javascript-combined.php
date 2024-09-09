<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// First of all send javascript header
header("Content-type: application/javascript");

// Array of JS files
$js = array(
    'modaal.js',
    'base64-decode.js',
    'autosize.min.js',
    'popper.min.js',
    'zingchart.min.js',
    'insQ.min.js',
    'crypto-js.js',
    'var_defaults.js',
    'functions.js',
    'highlight.min.js',
);

// Prevent a notice
$js_content = '';

// Loop the js Array
foreach ($js as $js_file) {
    // Load the content of the js file 
    $js_content .= "\n\n\n\n" . '/******* COMBINED JAVASCRIPT FILE: '.$js_file.' *******/ ' . "\n\n\n\n" . file_get_contents($js_file);
}

// print the js content
echo $js_content;

gc_collect_cycles(); // Clean memory cache

?>
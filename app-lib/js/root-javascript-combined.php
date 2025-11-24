<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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
    'crypto-js-v4.2.0.min.js',
    'var_defaults.js',
    'functions.js',
    'highlight.min.js',
);

// Prevent a notice
$js_content = '';

// Loop the js Array
foreach ($js as $js_file) {
     
$file_contents = file_get_contents($js_file);

     // Force zingchart BRANDING LINK to ALWAYS SHOW, even on localhost / 127.0.0.1 (local user's machine)
     // (DESKTOP EDITIONS RUN AS LOCALHOST, BUT WE WANT TO DISPLAY THEIR BRANDING FOR LICENSING REQUIREMENTS)
     if ( $js_file == 'zingchart.min.js' ) {
     $file_contents = preg_replace("/localhost/i", "sameforlocalhost", $file_contents);
     $file_contents = preg_replace("/127\.0\.0\.1/i", "0.0.0.0", $file_contents);
     }


// Load the content of the js file 
$js_content .= "\n\n\n\n" . '/******* COMBINED JAVASCRIPT FILE: '.$js_file.' *******/ ' . "\n\n\n\n" . $file_contents;

}

// print the js content
echo $js_content;

gc_collect_cycles(); // Clean memory cache

?>
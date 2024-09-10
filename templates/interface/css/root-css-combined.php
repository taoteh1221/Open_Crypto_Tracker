<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// First of all send css header
header("Content-type: text/css");

// Array of css files
$css = array(
    'modaal.css',
    'jquery.mCustomScrollbar.min.css',
    'style.css',
    'admin.css',
    ''.$_GET['theme'].'.style.css',
    ''.$_GET['theme'].'.admin.css',
    'highlightjs.min.css',
);

// Prevent a notice
$css_content = '';

// Loop the css Array
foreach ($css as $css_file) {
     
$file_contents = file_get_contents($css_file);

// Load the content of the css file 
$css_content .= "\n\n\n\n" . '/******* COMBINED CSS FILE: '.$css_file.' *******/ ' . "\n\n\n\n" . $file_contents;

}

// print the css content
echo $css_content;

gc_collect_cycles(); // Clean memory cache

?>
<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$filename_sections = explode("_", $_GET['backup']);


foreach ( $filename_sections as $section ) {
	
$section = preg_replace("/\.zip/", "", $section);

	// Detect whether or not the filename requested is privately secured 
	// with a hexidecimal hash suffix of 32 characters or higher.
	// If not then cancel allowing the download
	if ( ctype_xdigit($section) && strlen($section) >= 32 ) {
	$private_filename = 1;
	}

}

if ( $private_filename != 1 ) {
die('Non-private filename request detected, download aborted.');
}


$path = "cache/secured/backups/";

$fullPath = $path.$_GET['backup'];


if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
    
    switch ($ext) {
        case "pdf":
        header("Content-type: application/pdf"); // add here more headers for diff. extensions
        header("Content-disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
        break;
        default;
        header("Content-type: application/octet-stream");
        header("Content-disposition: filename=\"".$path_parts["basename"]."\"");
    }
    
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    
    // Clear output buffer, or it may corrupt download
    ob_clean();
    flush();
      
    while(!feof($fd)) {
        $buffer = fread($fd, 2048);
        echo $buffer;
    }
    
}


// Access stats logging / etc
$ct['cache']->log_access_stats();
$ct['cache']->api_throttle_cache();
$ct['cache']->registered_light_charts_cache();

// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

fclose ($fd);
gc_collect_cycles(); // Clean memory cache
exit;

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!


?>
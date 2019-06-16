<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


$runtime_mode = 'backups_download';

// Change directory
chdir("../");
require("config.php");

$filename_sections = explode("_", $_GET['file']);

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

$path = "backups/";

$fullPath = $path.$_GET['file'];
 
if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);
    
    switch ($ext) {
        case "pdf":
        header("Content-type: application/pdf"); // add here more headers for diff. extensions
        header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
        break;
        default;
        header("Content-type: application/octet-stream");
        header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
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

fclose ($fd);

// Email alert here maybe??

exit;

?>
<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

if ( trim($_GET['data']) == '' ) {
exit;
}

$original = trim($_GET['data']);
$sanitized = trim($_GET['data']);

// Check if sanitized input matches original input (we want to use original input to play it safe or cancel the QR code output, since this is crypto-related)

// FLAG tabs, spaces, and new lines
$sanitized = preg_replace("/[\s\W]+/", "FLAG", $sanitized);

// FLAG non-alphanumeric
$sanitized = preg_replace("/[^A-Za-z0-9 ]/", "FLAG", $sanitized);

// Remove HTML
$sanitized = filter_var($sanitized, FILTER_SANITIZE_STRING);

include('../../../app-lib/php/other/qr-code-generator/phpqrcode/qrlib.php'); 


     if (  $original == $sanitized ) {
		// outputs image directly into browser, as PNG stream 
		QRcode::png($original, false, 3, 5);
	  }
	  else {
		$image = imagecreatefrompng('qr-error.png');
	   header('Content-type: image/png');
		imagepng($image);
		die;
	  }


?>
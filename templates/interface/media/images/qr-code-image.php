<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$original = trim($_GET['data']);

$sanitized = $original;

// FLAG tabs, spaces, and new lines
$sanitized = preg_replace("/[\s\W]+/", "FLAG", $sanitized);

// FLAG non-alphanumeric
$sanitized = preg_replace("/[^A-Za-z0-9 ]/", "FLAG", $sanitized);

// Remove HTML
$sanitized = strip_tags($sanitized);


// Check if sanitized input matches original input (we want to use original input to play it safe or cancel the QR code output, since this is crypto-related)
if ( trim($_GET['data']) == '' || $original != $sanitized ) {
$image = imagecreatefrompng('qr-error.png');
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
exit;
}


// If checks above pass, we continue and render the QR code...

// Runtime mode
$runtime_mode = 'qr_code';

// Change directory
chdir("../../../../");

// FOR SPEED, $ct['runtime_mode'] 'captcha' only gets app config vars, some init.php, then the captcha library
require("app-lib/php/init.php");


// MUST BE NEAR TOP OF ORIGINAL SOURCE FILE, AND NOT INSIDE A STATEMENT!
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;


$options = new QROptions([
	'version'             => 12, // The higher the version, the more data the QR code can hold
	'outputType'          => QROutputInterface::GDIMAGE_PNG,
	'eccLevel'            => EccLevel::L,
	'scale'               => 6,
	'imageBase64'         => false,
	'bgColor'             => [255, 255, 255],
	'imageTransparent'    => false,
#	'transparencyColor'   => [233, 233, 233],
	'drawCircularModules' => true,
	'drawLightModules'    => true,
	'circleRadius'        => 0.4,
	'keepAsSquare'        => [
		QRMatrix::M_FINDER_DARK,
		QRMatrix::M_FINDER_DOT,
		QRMatrix::M_ALIGNMENT_DARK,
	],
	'moduleValues'        => [
		// finder
		QRMatrix::M_FINDER_DARK    => [247, 147, 26], // dark (true)
		QRMatrix::M_FINDER_DOT     => [247, 147, 26], // finder dot, dark (true)
		QRMatrix::M_FINDER         => [233, 233, 233], // light (false), white is the transparency color and is enabled by default
		// alignment
		QRMatrix::M_ALIGNMENT_DARK => [142, 110, 222],
		QRMatrix::M_ALIGNMENT      => [233, 233, 233],
		// timing
		QRMatrix::M_TIMING_DARK    => [255, 0, 0],
		QRMatrix::M_TIMING         => [233, 233, 233],
		// format
		QRMatrix::M_FORMAT_DARK    => [67, 159, 84],
		QRMatrix::M_FORMAT         => [233, 233, 233],
		// version
		QRMatrix::M_VERSION_DARK   => [62, 174, 190],
		QRMatrix::M_VERSION        => [233, 233, 233],
		// data
		QRMatrix::M_DATA_DARK      => [0, 0, 0],
		QRMatrix::M_DATA           => [233, 233, 233],
		// darkmodule
		QRMatrix::M_DARKMODULE     => [0, 0, 0],
		// separator
		QRMatrix::M_SEPARATOR      => [233, 233, 233],
		// quietzone
		QRMatrix::M_QUIETZONE      => [233, 233, 233],
		// logo (requires a call to QRMatrix::setLogoSpace()), see QRImageWithLogo
		QRMatrix::M_LOGO           => [233, 233, 233],
	],
]);


try{
$image = (new QRCode($options))->render($sanitized);
}
catch(Throwable $e){
exit($e->getMessage());
}
     
     
header('Content-type: image/png');

echo $image;

exit;

?>
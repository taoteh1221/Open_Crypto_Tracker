<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

// Runtime mode
$runtime_mode = '2fa_setup';

// Change directory
chdir("../../../../");


require("app-lib/php/init.php");
 

// Security checks
if ( $ct['admin_area_2fa'] != 'off' || $ct['gen']->admin_logged_in() == false || !is_array($stored_admin_login) || !$ct['app_host'] || !$ct['gen']->pass_sec_check($_GET['2fa_setup'], '2fa_setup') ) {
$security_error = '2FA Setup access invalid / expired (' . $ct['remote_ip'] . '), try reloading the app';
$ct['gen']->log('security_error', $security_error);
echo $security_error . '.';
// Log errors before exiting
$ct['cache']->app_log();
exit;
}


// MUST BE NEAR TOP OF ORIGINAL SOURCE FILE, AND NOT INSIDE A STATEMENT!
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;


// Credit to: https://www.rafaelwendel.com/en/2021/05/two-step-verification-with-php-and-google-authenticator/

//the "getUrl" method takes as a parameter: "username", "host", the key "secret",
// AND THE OPTIONAL 'data_only' (to generate QR locally for privacy)
$image_text = $ct['auth_2fa']->getURL($stored_admin_login[0], $ct['app_host'], $auth_secret_2fa, 'data_only');


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
$image = (new QRCode($options))->render($image_text);
}
catch(Throwable $e){
exit($e->getMessage());
}
     
     
header('Content-type: image/png');
 
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

echo $image;

exit;

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
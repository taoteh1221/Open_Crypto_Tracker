<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Captcha image library...
// Credit to: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
 

$image = imagecreatetruecolor($ct['dev']['captcha_image_width'], $ct['dev']['captcha_image_height']);
imageantialias($image, true);
 
$colors = [];
$red = rand(125, 175);
$green = rand(125, 175);
$blue = rand(125, 175);
 
 
for($i = 0; $i < 5; $i++) {
  $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
}
 
imagefill($image, 0, 0, $colors[0]);


// Background noise pass #1
for($i = 0; $i < round($ct['dev']['captcha_text_size'] * 3.15); $i++) {
  imagesetthickness($image, rand(2, 10));
  $line_color = $colors[rand(1, 4)];
  imagerectangle($image, rand(0, $ct['dev']['captcha_image_width']), rand(0, $ct['dev']['captcha_image_height']), rand(0, $ct['dev']['captcha_image_width']), rand(40, 60), $line_color);
}

// Background noise pass #2
for ( $i = 0; $i < round($ct['dev']['captcha_text_size'] / 1.35); $i++ ) {
  imageline($image,mt_rand(0,$ct['dev']['captcha_image_width']),mt_rand(0,$ct['dev']['captcha_image_height']),mt_rand(0,$ct['dev']['captcha_image_width']),mt_rand(0,$ct['dev']['captcha_image_height']),imagecolorallocate($image,rand(50,85),rand(50,85),rand(50,85)));
}


$font_dir = $ct['base_dir'] . '/templates/interface/media/fonts/';
$font_files = array_diff(scandir($font_dir), array('.', '..'));

$fonts = array();
foreach( $font_files as $ttf_file ) {
	if ( strpos($ttf_file, '.ttf') !== false ) {
	$fonts[] = $font_dir . $ttf_file;
	}
}


$captcha_str = $ct['sec']->captcha_str($ct['dev']['captcha_permitted_chars'], $ct['dev']['captcha_chars_length']);
 
$_SESSION['captcha_code'] = strtolower($captcha_str);
 
 
for($i = 0; $i < $ct['dev']['captcha_chars_length']; $i++) {
	
	// Random off black/white, with contrast adjustment
	if ( $ct['conf']['sec']['captcha_text_contrast'] >= 0 ) {
	$black_rand = rand( (37 - $ct['conf']['sec']['captcha_text_contrast']) , (46 - $ct['conf']['sec']['captcha_text_contrast']) );
	$white_rand = rand( (173 + $ct['conf']['sec']['captcha_text_contrast']) , (181 + $ct['conf']['sec']['captcha_text_contrast']) );
	}
	else {
	$black_rand = rand( (37 + abs($ct['conf']['sec']['captcha_text_contrast']) ) , (46 + abs($ct['conf']['sec']['captcha_text_contrast']) ) );
	$white_rand = rand( (173 - abs($ct['conf']['sec']['captcha_text_contrast']) ) , (181 - abs($ct['conf']['sec']['captcha_text_contrast']) ) );
	}
 
$black = imagecolorallocate($image, $black_rand, $black_rand, $black_rand);
$white = imagecolorallocate($image, $white_rand, $white_rand, $white_rand);
$textcolors = [$black, $white];
	
$letter_space = round( ( $ct['dev']['captcha_image_width'] - ($ct['dev']['captcha_text_margin'] * 2) ) / $ct['dev']['captcha_chars_length'] ) + 2;
$initial = rand($ct['dev']['captcha_text_margin'], ($ct['dev']['captcha_text_margin'] * 2) ) + $ct['dev']['captcha_text_margin'] + 4;

$angle = random_int( (0 - $ct['conf']['sec']['captcha_text_angle']) , $ct['conf']['sec']['captcha_text_angle']);
   
imagettftext($image, $ct['dev']['captcha_text_size'], $angle, $initial + round($i * $letter_space), rand( ($ct['dev']['captcha_text_size'] + ($ct['dev']['captcha_text_margin'] * 4) ), ($ct['dev']['captcha_image_height'] - ($ct['dev']['captcha_text_margin'] * 5) ) ), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_str[$i]);

}


header('Content-type: image/png');

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct['conf']['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $ct['app_host_address']);
}
 
 
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

imagepng($image);
imagedestroy($image);

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Captcha image library...
// Credit to: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
 

$image = imagecreatetruecolor($ct_conf['sec']['captcha_image_width'], $ct_conf['sec']['captcha_image_height']);
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
for($i = 0; $i < round($ct_conf['sec']['captcha_text_size'] * 3.15); $i++) {
  imagesetthickness($image, rand(2, 10));
  $line_color = $colors[rand(1, 4)];
  imagerectangle($image, rand(0, $ct_conf['sec']['captcha_image_width']), rand(0, $ct_conf['sec']['captcha_image_height']), rand(0, $ct_conf['sec']['captcha_image_width']), rand(40, 60), $line_color);
}

// Background noise pass #2
for ( $i = 0; $i < round($ct_conf['sec']['captcha_text_size'] / 1.35); $i++ ) {
  imageline($image,mt_rand(0,$ct_conf['sec']['captcha_image_width']),mt_rand(0,$ct_conf['sec']['captcha_image_height']),mt_rand(0,$ct_conf['sec']['captcha_image_width']),mt_rand(0,$ct_conf['sec']['captcha_image_height']),imagecolorallocate($image,rand(50,85),rand(50,85),rand(50,85)));
}


$font_dir = $base_dir . '/templates/interface/media/fonts/';
$font_files = array_diff(scandir($font_dir), array('.', '..'));

$fonts = array();
foreach( $font_files as $ttf_file ) {
	if ( strpos($ttf_file, '.ttf') !== false ) {
	$fonts[] = $font_dir . $ttf_file;
	}
}


$captcha_str = $ct_gen->captcha_str($ct_conf['sec']['captcha_permitted_chars'], $ct_conf['sec']['captcha_chars_length']);
 
$_SESSION['captcha_code'] = strtolower($captcha_str);
 
 
for($i = 0; $i < $ct_conf['sec']['captcha_chars_length']; $i++) {
	
	// Random off black/white, with contrast adjustment
	if ( $ct_conf['sec']['captcha_text_contrast'] >= 0 ) {
	$black_rand = rand( (37 - $ct_conf['sec']['captcha_text_contrast']) , (46 - $ct_conf['sec']['captcha_text_contrast']) );
	$white_rand = rand( (173 + $ct_conf['sec']['captcha_text_contrast']) , (181 + $ct_conf['sec']['captcha_text_contrast']) );
	}
	else {
	$black_rand = rand( (37 + abs($ct_conf['sec']['captcha_text_contrast']) ) , (46 + abs($ct_conf['sec']['captcha_text_contrast']) ) );
	$white_rand = rand( (173 - abs($ct_conf['sec']['captcha_text_contrast']) ) , (181 - abs($ct_conf['sec']['captcha_text_contrast']) ) );
	}
 
$black = imagecolorallocate($image, $black_rand, $black_rand, $black_rand);
$white = imagecolorallocate($image, $white_rand, $white_rand, $white_rand);
$textcolors = [$black, $white];
	
$letter_space = round( ( $ct_conf['sec']['captcha_image_width'] - ($ct_conf['sec']['captcha_text_margin'] * 2) ) / $ct_conf['sec']['captcha_chars_length'] ) + 2;
$initial = rand($ct_conf['sec']['captcha_text_margin'], ($ct_conf['sec']['captcha_text_margin'] * 2) ) + $ct_conf['sec']['captcha_text_margin'] + 4;

$angle = random_int( (0 - $ct_conf['sec']['captcha_text_angle']) , $ct_conf['sec']['captcha_text_angle']);
   
imagettftext($image, $ct_conf['sec']['captcha_text_size'], $angle, $initial + round($i * $letter_space), rand( ($ct_conf['sec']['captcha_text_size'] + ($ct_conf['sec']['captcha_text_margin'] * 4) ), ($ct_conf['sec']['captcha_image_height'] - ($ct_conf['sec']['captcha_text_margin'] * 5) ) ), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_str[$i]);

}


header('Content-type: image/png');

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct_conf['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $app_host_address);
}
 
 
// Log errors / debugging, send notifications
$ct_cache->error_log();
$ct_cache->debug_log();
$ct_cache->send_notifications();

imagepng($image);
imagedestroy($image);

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


// Captcha image library...
// Credit to: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
 

$image = imagecreatetruecolor($pt_conf['dev']['captcha_image_width'], $pt_conf['dev']['captcha_image_height']);
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
for($i = 0; $i < round($pt_conf['dev']['captcha_text_size'] * 3.15); $i++) {
  imagesetthickness($image, rand(2, 10));
  $line_color = $colors[rand(1, 4)];
  imagerectangle($image, rand(0, $pt_conf['dev']['captcha_image_width']), rand(0, $pt_conf['dev']['captcha_image_height']), rand(0, $pt_conf['dev']['captcha_image_width']), rand(40, 60), $line_color);
}

// Background noise pass #2
for ( $i = 0; $i < round($pt_conf['dev']['captcha_text_size'] / 1.35); $i++ ) {
  imageline($image,mt_rand(0,$pt_conf['dev']['captcha_image_width']),mt_rand(0,$pt_conf['dev']['captcha_image_height']),mt_rand(0,$pt_conf['dev']['captcha_image_width']),mt_rand(0,$pt_conf['dev']['captcha_image_height']),imagecolorallocate($image,rand(50,85),rand(50,85),rand(50,85)));
}


$font_dir = $base_dir . '/templates/interface/fonts/';
$font_files = array_diff(scandir($font_dir), array('.', '..'));

$fonts = array();
foreach( $font_files as $ttf_file ) {
	if ( strpos($ttf_file, '.ttf') !== false ) {
	$fonts[] = $font_dir . $ttf_file;
	}
}


$captcha_str = $pt_gen->captcha_str($pt_conf['dev']['captcha_permitted_chars'], $pt_conf['dev']['captcha_chars_length']);
 
$_SESSION['captcha_code'] = strtolower($captcha_str);
 
 
for($i = 0; $i < $pt_conf['dev']['captcha_chars_length']; $i++) {
	
	// Random off black/white, with contrast adjustment
	if ( $pt_conf['power']['captcha_text_contrast'] >= 0 ) {
	$black_rand = rand( (37 - $pt_conf['power']['captcha_text_contrast']) , (46 - $pt_conf['power']['captcha_text_contrast']) );
	$white_rand = rand( (173 + $pt_conf['power']['captcha_text_contrast']) , (181 + $pt_conf['power']['captcha_text_contrast']) );
	}
	else {
	$black_rand = rand( (37 + abs($pt_conf['power']['captcha_text_contrast']) ) , (46 + abs($pt_conf['power']['captcha_text_contrast']) ) );
	$white_rand = rand( (173 - abs($pt_conf['power']['captcha_text_contrast']) ) , (181 - abs($pt_conf['power']['captcha_text_contrast']) ) );
	}
 
$black = imagecolorallocate($image, $black_rand, $black_rand, $black_rand);
$white = imagecolorallocate($image, $white_rand, $white_rand, $white_rand);
$textcolors = [$black, $white];
	
$letter_space = round( ( $pt_conf['dev']['captcha_image_width'] - ($pt_conf['dev']['captcha_text_margin'] * 2) ) / $pt_conf['dev']['captcha_chars_length'] ) + 2;
$initial = rand($pt_conf['dev']['captcha_text_margin'], ($pt_conf['dev']['captcha_text_margin'] * 2) ) + $pt_conf['dev']['captcha_text_margin'] + 4;

$angle = random_int( (0 - $pt_conf['power']['captcha_text_angle']) , $pt_conf['power']['captcha_text_angle']);
   
imagettftext($image, $pt_conf['dev']['captcha_text_size'], $angle, $initial + round($i * $letter_space), rand( ($pt_conf['dev']['captcha_text_size'] + ($pt_conf['dev']['captcha_text_margin'] * 4) ), ($pt_conf['dev']['captcha_image_height'] - ($pt_conf['dev']['captcha_text_margin'] * 5) ) ), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_str[$i]);

}


header('Content-type: image/png');
imagepng($image);
imagedestroy($image);

?>
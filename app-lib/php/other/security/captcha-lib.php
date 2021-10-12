<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


// Captcha image library...
// Credit to: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
 

$image = imagecreatetruecolor($oct_conf['dev']['captcha_image_width'], $oct_conf['dev']['captcha_image_height']);
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
for($i = 0; $i < round($oct_conf['dev']['captcha_text_size'] * 3.15); $i++) {
  imagesetthickness($image, rand(2, 10));
  $line_color = $colors[rand(1, 4)];
  imagerectangle($image, rand(0, $oct_conf['dev']['captcha_image_width']), rand(0, $oct_conf['dev']['captcha_image_height']), rand(0, $oct_conf['dev']['captcha_image_width']), rand(40, 60), $line_color);
}

// Background noise pass #2
for ( $i = 0; $i < round($oct_conf['dev']['captcha_text_size'] / 1.35); $i++ ) {
  imageline($image,mt_rand(0,$oct_conf['dev']['captcha_image_width']),mt_rand(0,$oct_conf['dev']['captcha_image_height']),mt_rand(0,$oct_conf['dev']['captcha_image_width']),mt_rand(0,$oct_conf['dev']['captcha_image_height']),imagecolorallocate($image,rand(50,85),rand(50,85),rand(50,85)));
}


$font_dir = $base_dir . '/templates/interface/fonts/';
$font_files = array_diff(scandir($font_dir), array('.', '..'));

$fonts = array();
foreach( $font_files as $ttf_file ) {
	if ( strpos($ttf_file, '.ttf') !== false ) {
	$fonts[] = $font_dir . $ttf_file;
	}
}


$captcha_str = $oct_gen->captcha_str($oct_conf['dev']['captcha_permitted_chars'], $oct_conf['dev']['captcha_chars_length']);
 
$_SESSION['captcha_code'] = strtolower($captcha_str);
 
 
for($i = 0; $i < $oct_conf['dev']['captcha_chars_length']; $i++) {
	
	// Random off black/white, with contrast adjustment
	if ( $oct_conf['power']['captcha_text_contrast'] >= 0 ) {
	$black_rand = rand( (37 - $oct_conf['power']['captcha_text_contrast']) , (46 - $oct_conf['power']['captcha_text_contrast']) );
	$white_rand = rand( (173 + $oct_conf['power']['captcha_text_contrast']) , (181 + $oct_conf['power']['captcha_text_contrast']) );
	}
	else {
	$black_rand = rand( (37 + abs($oct_conf['power']['captcha_text_contrast']) ) , (46 + abs($oct_conf['power']['captcha_text_contrast']) ) );
	$white_rand = rand( (173 - abs($oct_conf['power']['captcha_text_contrast']) ) , (181 - abs($oct_conf['power']['captcha_text_contrast']) ) );
	}
 
$black = imagecolorallocate($image, $black_rand, $black_rand, $black_rand);
$white = imagecolorallocate($image, $white_rand, $white_rand, $white_rand);
$textcolors = [$black, $white];
	
$letter_space = round( ( $oct_conf['dev']['captcha_image_width'] - ($oct_conf['dev']['captcha_text_margin'] * 2) ) / $oct_conf['dev']['captcha_chars_length'] ) + 2;
$initial = rand($oct_conf['dev']['captcha_text_margin'], ($oct_conf['dev']['captcha_text_margin'] * 2) ) + $oct_conf['dev']['captcha_text_margin'] + 4;

$angle = random_int( (0 - $oct_conf['power']['captcha_text_angle']) , $oct_conf['power']['captcha_text_angle']);
   
imagettftext($image, $oct_conf['dev']['captcha_text_size'], $angle, $initial + round($i * $letter_space), rand( ($oct_conf['dev']['captcha_text_size'] + ($oct_conf['dev']['captcha_text_margin'] * 4) ), ($oct_conf['dev']['captcha_image_height'] - ($oct_conf['dev']['captcha_text_margin'] * 5) ) ), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_str[$i]);

}


header('Content-type: image/png');
imagepng($image);
imagedestroy($image);

?>
<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



///////////////////////////////////////////////////////////////////


// Secured cache files global variable for app config (getting capcha settings)
$secured_cache_files = sort_files($base_dir . '/cache/secured', 'dat', 'desc');


foreach( $secured_cache_files as $secured_file ) {

	// App config
	if ( preg_match("/app_config_/i", $secured_file) ) {
		
		$cached_app_config = json_decode( trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) , TRUE);
			
			if ( $cached_app_config == true ) {
			$app_config = $cached_app_config; // Use cached app_config if it exists, seems intact, and config.php hasn't been revised since last check
			}
			else {
			app_logging('config_error', 'Cached app_config data appears corrupted (fetching within captcha library)');
			}
			
	}
	
}


///////////////////////////////////////////////////////////////////


// Captcha image library...
// Credit to: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
 

$image = imagecreatetruecolor($app_config['power_user']['captcha_image_width'], $app_config['power_user']['captcha_image_height']);
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
for($i = 0; $i < round($app_config['power_user']['captcha_text_size'] * 2); $i++) {
  imagesetthickness($image, rand(2, 10));
  $line_color = $colors[rand(1, 4)];
  imagerectangle($image, rand(0, $app_config['power_user']['captcha_image_width']), rand(0, $app_config['power_user']['captcha_image_height']), rand(0, $app_config['power_user']['captcha_image_width']), rand(40, 60), $line_color);
}

// Background noise pass #2
for ( $i = 0; $i < round($app_config['power_user']['captcha_text_size'] / 2); $i++ ) {
  imageline($image,mt_rand(0,$app_config['power_user']['captcha_image_width']),mt_rand(0,$app_config['power_user']['captcha_image_height']),mt_rand(0,$app_config['power_user']['captcha_image_width']),mt_rand(0,$app_config['power_user']['captcha_image_height']),imagecolorallocate($image,rand(50,85),rand(50,85),rand(50,85)));
}


$font_dir = $base_dir . '/templates/interface/fonts/';
$font_files = array_diff(scandir($font_dir), array('.', '..'));

$fonts = array();
foreach( $font_files as $ttf_file ) {
$fonts[] = $font_dir . $ttf_file;
}


$captcha_string = captcha_string($app_config['power_user']['captcha_permitted_chars'], $app_config['power_user']['captcha_chars_length']);
 
$_SESSION['captcha_code'] = strtolower($captcha_string);
 
 
for($i = 0; $i < $app_config['power_user']['captcha_chars_length']; $i++) {
	
	// Random off black/white, with contrast adjustment
	if ( $app_config['power_user']['captcha_text_contrast'] >= 0 ) {
	$black_rand = rand( (37 - $app_config['power_user']['captcha_text_contrast']) , (46 - $app_config['power_user']['captcha_text_contrast']) );
	$white_rand = rand( (173 + $app_config['power_user']['captcha_text_contrast']) , (181 + $app_config['power_user']['captcha_text_contrast']) );
	}
	else {
	$black_rand = rand( (37 + abs($app_config['power_user']['captcha_text_contrast']) ) , (46 + abs($app_config['power_user']['captcha_text_contrast']) ) );
	$white_rand = rand( (173 - abs($app_config['power_user']['captcha_text_contrast']) ) , (181 - abs($app_config['power_user']['captcha_text_contrast']) ) );
	}
 
$black = imagecolorallocate($image, $black_rand, $black_rand, $black_rand);
$white = imagecolorallocate($image, $white_rand, $white_rand, $white_rand);
$textcolors = [$black, $white];
	
$letter_space = round( ( $app_config['power_user']['captcha_image_width'] - ($app_config['power_user']['captcha_text_margin'] * 2) ) / $app_config['power_user']['captcha_chars_length'] ) + 1;
$initial = rand($app_config['power_user']['captcha_text_margin'], ($app_config['power_user']['captcha_text_margin'] * 2) ) + $app_config['power_user']['captcha_text_margin'] + 2;
   
imagettftext($image, $app_config['power_user']['captcha_text_size'], rand(0, 10), $initial + round($i * $letter_space), rand( ($app_config['power_user']['captcha_text_size'] + ($app_config['power_user']['captcha_text_margin'] * 4) ), ($app_config['power_user']['captcha_image_height'] - ($app_config['power_user']['captcha_text_margin'] * 5) ) ), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);

}


header('Content-type: image/png');
imagepng($image);
imagedestroy($image);

?>
<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

// Credit to: https://code.tutsplus.com/tutorials/build-your-own-captcha-and-contact-form-in-php--net-5362
 
session_start();
 
$permitted_chars = 'CFHMNPRY49';
  
function generate_string($input, $strength=10) {
	
    $input_length = strlen($input);
    $random_string = '';
    
    	
        
        $count = 0;
        	while ( $count < $strength ) {
        		
        		   if( $count % 2 == 0 ){ 
        			// Even number  
        			$random_character = strtoupper( $input[mt_rand(0, $input_length - 1)] );
    				} 
    				else { 
        			// Odd number
        			$random_character = strtolower( $input[mt_rand(0, $input_length - 1)] );
    				} 
        	
        		if ( stristr($random_string, $random_character) == false ) {
        		//echo $random_character . ' -- ';
        		$random_string .= $random_character;
            $count = $count + 1;
        		}
        	
        	}
        
  
    return $random_string;
}


$captcha_text_length = 6;

$width = 430;

$height = 80;

$text_size = 50;

$text_margin = 4;
 
$image = imagecreatetruecolor($width, $height);
 
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
for($i = 0; $i < round($text_size * 2); $i++) {
  imagesetthickness($image, rand(2, 10));
  $line_color = $colors[rand(1, 4)];
  imagerectangle($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(40, 60), $line_color);
}

// Background noise pass #2
for ( $i = 0; $i < round($text_size / 2); $i++ ) {
  imageline($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),imagecolorallocate($image,rand(50,85),rand(50,85),rand(50,85)));
}


$font_dir = '../../fonts/';

$font_files = array_diff(scandir($font_dir), array('.', '..'));

$fonts = array();
foreach( $font_files as $ttf_file ) {
$fonts[] = $font_dir . $ttf_file;
}


$captcha_string = generate_string($permitted_chars, $captcha_text_length);
 
$_SESSION['captcha_code'] = strtolower($captcha_string);
 
for($i = 0; $i < $captcha_text_length; $i++) {
	
// Random off black/white
$black_rand = rand(26, 33);
$white_rand = rand(190, 205);
 
$black = imagecolorallocate($image, $black_rand, $black_rand, $black_rand);
$white = imagecolorallocate($image, $white_rand, $white_rand, $white_rand);
$textcolors = [$black, $white];
	
  $letter_space = round( ( $width - ($text_margin * 2) ) / $captcha_text_length  ) + 1;
  $initial = rand($text_margin, ($text_margin * 2) ) + $text_margin;
   
  imagettftext($image, $text_size, rand(0, 10), $initial + round($i * $letter_space), rand( ($text_size + ($text_margin * 4) ), ($height - ($text_margin * 5) ) ), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
}

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
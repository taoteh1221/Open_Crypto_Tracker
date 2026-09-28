<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

 
// Runtime mode
$runtime_mode = 'osm_tiles';

// Change directory
chdir("../../../");

require('app-lib/php/init.php');


// Running AFTER calling init.php


// Security nonce check
if ( $_GET['tiles_nonce'] != $ct['sec']->nonce_digest('osm_tiles') ) {

header ('Content-Type: image/png');

echo file_get_contents($ct['plug']->plug_dir(false, 'on-chain-stats') . "/plug-assets/images/security-error.png");

exit;

}


/* Original source https://wiki.openstreetmap.org/wiki/ProxySimplePHP
*	Modified to use directory structure matching the OSM urls and retries on a failure
*/

$ttl = 86400; // cache timeout in seconds (1 day)

$x = intval($_GET['x']);
$y = intval($_GET['y']);
$z = intval($_GET['z']);


    if (isset($_GET['r'])) {
		$r = strip_tags($_GET['r']);
	} else {
		$r = 'mapnik';
	}


    switch ($r) {
      case 'mapnik':
        $r = 'mapnik';
        break;

      case 'osma':
      default:
        $r = 'osma';
        break;
    }


$file = $ct['plug']->plug_dir(false, 'on-chain-stats') . "/plug-assets/images/tiles/$r/$z/$x/$y.png";
$img = null;
$tries = 0;
    
    
    // Use cache for 30 days
    if ( !is_file($file) || filemtime($file) < time() - (86400*30) ) {
		do {
			$server = array();
			switch ($r) {
				case 'mapnik':
					$server[] = 'a.tile.openstreetmap.org';
					$server[] = 'b.tile.openstreetmap.org';
					$server[] = 'c.tile.openstreetmap.org';

					$url = 'http://'.$server[array_rand($server)];
					$url .= "/".$z."/".$x."/".$y.".png";
					break;

				case 'osma':
				default:
					$server[] = 'a.tah.openstreetmap.org';
					$server[] = 'b.tah.openstreetmap.org';
					$server[] = 'c.tah.openstreetmap.org';

					$url = 'http://'.$server[array_rand($server)].'/Tiles/tile.php';
					$url .= "/".$z."/".$x."/".$y.".png";
					break;
			}

			@mkdir(dirname($file), 0755, true);

            $opts = array('http'=>array('header' => "User-Agent:TileProxy/1.0\r\n"));
            $context = stream_context_create($opts);
            $img = file_get_contents($url,false,$context);

			if ($img) {
				$fp = fopen($file, "w");
				fwrite($fp, $img);
				fclose($fp);
			}

			if ($tries++ > 5) exit();	// Give up after five tries
		} while (!$img); 	// If curl has returned a broken file, then try downloading again
	} else {
		$img = file_get_contents($file);
	}


$exp_gmt = gmdate("D, d M Y H:i:s", time() + $ttl * 60) ." GMT";
$mod_gmt = gmdate("D, d M Y H:i:s", filemtime($file)) ." GMT";
    
header("Expires: " . $exp_gmt);
header("Last-Modified: " . $mod_gmt);
header("Cache-Control: public, max-age=" . $ttl * 60);

// for MSIE 5
header("Cache-Control: pre-check=" . $ttl * 60, FALSE);
header ('Content-Type: image/png');

//readfile($file);

// Access control headers MUST be AFTER init.php!!!

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct['conf']['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $ct['app_host_address']);
}

    
echo $img;

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
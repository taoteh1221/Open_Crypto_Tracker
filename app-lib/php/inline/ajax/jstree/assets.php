<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

[

<?php

$restricted_assets = array('btc', 'eth', 'sol');


$skip_assets = array(
          	                     'MISCASSETS',
          	                     'BTCNFTS',
          	                     'ETHNFTS',
          	                     'SOLNFTS',
          	                     'ALTNFTS',
          	                    );
          	                    
     	
$asset_key = $_GET['asset_markets'];
     	
$asset_data = $ct['conf']['assets'][$asset_key];
     	
$asset_count = 1;

     	     
     	     // DISALLOW REMOVING ANY BITCOIN PAIRINGS / MARKETS THAT ********WOULD REMOVE A PAIRING BECAUSE IT'S THE ONLY MARKET IN THAT PAIRING**********
     	     // (SO COUNTRY CURRENCY PRICE CONVERSION CAPACITY IS NOT AFFECTED IN THE APP)
     	     foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {
     	          
          	     
          	     if ( !in_array($asset_key, $skip_assets) ) {
     	     ?>
               	 
     {
          
	 "text" : "<?=$asset_key?>",
	 "state" : { "opened" : true, "disabled" : <?=( in_array(strtolower($asset_key), $restricted_assets) ? 'true' : 'false' )?> }
				
     }<?=( ( sizeof($ct['conf']['assets']) - sizeof($skip_assets) ) > $asset_count  ? ',' : '' )?>

     	     <?php
     	          
     	          $asset_count = $asset_count + 1;

     	          }

     	     
     	     }
     	     
     	?>
     	
]


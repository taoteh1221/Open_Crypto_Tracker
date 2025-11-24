<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

[

<?php

$restricted_assets = array('btc', 'eth', 'sol');
     	
$asset_key = $_GET['asset_markets'];
     	
$asset_data = $ct['conf']['assets'][$asset_key];
     	
$pair_count = 1;

     	     
     	     // DISALLOW REMOVING ANY BITCOIN PAIRINGS / MARKETS THAT ********WOULD REMOVE A PAIRING BECAUSE IT'S THE ONLY MARKET IN THAT PAIRING**********
     	     // (SO COUNTRY CURRENCY PRICE CONVERSION CAPACITY IS NOT AFFECTED IN THE APP)
     	     foreach ( $asset_data['pair'] as $pairing_key => $pairing_data ) {
     	          
     	     $exchange_count = 1;

     	     ?>
               	 
     {
          
	 "text" : "<?=$pairing_key?>",
	 "state" : { "opened" : true, "disabled" : <?=( in_array(strtolower($asset_key), $restricted_assets) || in_array(strtolower($pairing_key), $restricted_assets) ? 'true' : 'false' )?> },
               	 
               	 "children" : [
	 
                              	 <?php
                              	 foreach ( $pairing_data as $exchange_key => $market_id ) {
                              	 ?>
               
                              				{
                              			      
                              			      "text" : "<?=$exchange_key?>",
                              				 "state" : { "selected" : false, "disabled" : <?=( in_array(strtolower($asset_key), $restricted_assets) && $exchange_count == 1 || in_array(strtolower($pairing_key), $restricted_assets) && $exchange_count == 1 ? 'true' : 'false' )?> },
                              				 "icon" : "jstree-file",
                              				 "a_attr": { "title" : "Market ID: <?=$market_id?>" }
                              				 
                              				 }<?=( sizeof($pairing_data) > $exchange_count  ? ',' : '' )?>
                              				 
                              	 <?php
               
                              	 $exchange_count = $exchange_count + 1;
               
                              	 }
                              	 ?>
               
               				]
				
     }<?=( sizeof($asset_data['pair']) > $pair_count  ? ',' : '' )?>




     	     <?php

     	     $pair_count = $pair_count + 1;
     	     
     	     }
     	     
     	?>
     	
]


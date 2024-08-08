<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


require($ct['base_dir'] . '/app-lib/php/inline/ajax/markets/back-button.php');

?>

<h3 class='bitcoin input_margins'>STEP #3: Remove <?=strtoupper($_POST['remove_markets_mode'])?></h3>   

<?php

if ( $_POST['remove_markets_mode'] == 'asset' ) {

}
elseif ( $_POST['remove_markets_mode'] == 'markets' ) {
?> 
	
     	
     	<select class='input_margins' id='remove_markets_search_asset' onchange='
     	
     	if ( this.value != "" ) {

     	load_jstree("asset_markets", this.value);
     	
     	    if ( this.value == "BTC" ) {
     	    $("#asset_markets_alerts").show(250, "linear"); // 0.25 seconds
     	    $("#asset_markets_alerts").html("BTC requires AT LEAST ONE EXCHANGE PER-PAIRING (for currency conversions), SO THE FIRST EXCHANGE INSIDE EACH PAIRING CANNOT BE DELETED.");
     	    }
     	    else {
     	    $("#asset_markets_alerts").hide(250, "linear"); // 0.25 seconds
     	    $("#asset_markets_alerts").html("");
     	    }
     	
     	}
     	else {
     	$("#markets_delete_selected").hide(250, "linear"); // 0.25 seconds
     	$("#asset_markets_alerts").hide(250, "linear"); // 0.25 seconds
     	$("#asset_markets").hide(250, "linear"); // 0.25 seconds
     	}
     	
     	'>
     	     
     	     <option value=''> Select An Asset </option>
     	
     	<?php
     	foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {
     	     
     	$skip_assets = array(
     	                     'MISCASSETS',
     	                     'BTCNFTS',
     	                     'ETHNFTS',
     	                     'SOLNFTS',
     	                     'ALTNFTS',
     	                    );
     	     
     	     if ( !in_array($asset_key, $skip_assets) ) {
     	     ?>
     	     <option value='<?=$asset_key?>'> <?=$asset_key?> </option>
     	     <?php
     	     }
     	}
     	?>
     	
     	</select><br />	
    	

<p id="markets_delete_selected"><button class='input_margins' type="button" onclick="jstree_delete('asset_markets');">Delete Selected Markets</button></p>


<div id="asset_markets_alerts" class='red red_dotted input_margins' style='display: none; font-weight: bold;'></div>


<div class='ct_jstree' id="asset_markets"></div>


<script>


</script>

<?php
}
?>











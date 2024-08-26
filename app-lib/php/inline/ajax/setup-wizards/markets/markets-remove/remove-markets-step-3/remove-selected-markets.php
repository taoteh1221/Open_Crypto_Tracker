<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>
	
     	<select class='input_margins' id='remove_markets_asset' name='remove_markets_asset' onchange='
     	
     	if ( this.value != "" ) {

     	jstree_json_ajax("type=jstree&asset_markets=" + this.value, "asset_markets", true); // Secured
     	
     	    if ( this.value == "BTC" || this.value == "ETH" || this.value == "SOL" ) {
     	    $("#asset_markets_alerts").show(250, "linear"); // 0.25 seconds
     	    $("#asset_markets_alerts").html("BTC / ETH / SOL assets require AT LEAST ONE EXCHANGE PER-PAIRING (for currency conversions / other PRIMARY features), SO THE FIRST EXCHANGE INSIDE EACH PAIRING CANNOT BE DELETED.");
     	    }
     	    else {
     	    $("#asset_markets_alerts").hide(250, "linear"); // 0.25 seconds
     	    $("#asset_markets_alerts").html("");
     	    }
     	
     	}
     	else {
     	$(".jstree_delete_selected").hide(250, "linear"); // 0.25 seconds
     	$("#asset_markets_alerts").hide(250, "linear"); // 0.25 seconds
     	$("#asset_markets").hide(250, "linear"); // 0.25 seconds
     	}
     	
     	'>
     	     
     	     <option value=''> Select An Asset </option>
     	
     	<?php
     	
     	$skip_assets = array(
     	                     'MISCASSETS',
     	                     'BTCNFTS',
     	                     'ETHNFTS',
     	                     'SOLNFTS',
     	                     'ALTNFTS',
     	                    );
     	                    
     	foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {
     	     
     	     
     	     if ( !in_array($asset_key, $skip_assets) ) {
     	     ?>
     	     <option value='<?=$asset_key?>'> <?=$asset_key?> </option>
     	     <?php
     	     }
     	}
     	?>
     	
     	</select><br />	
    	

<p class='jstree_delete_selected'>


<button class='red input_margins' type="button" onclick="jstree_delete('asset_markets');">Delete Selected Markets</button> &nbsp; &nbsp; &nbsp; 

     	
<button class='bitcoin force_button_style input_margins' onclick='


          if ( getObjectLength(jstree_json_data) < 1 ) {
          alert("Nothing deleted yet, no changes to save.");
          return false;
          }
          else {
     	
     	var post_data = {
     	                  "remove_markets_mode": "<?=$_POST['remove_markets_mode']?>",
     	                  "remove_markets_asset": $("#remove_markets_asset").val(),
     	                   };
     	
     	var merged_data = merge_objects(post_data, jstree_json_data);
     	
     	ct_ajax_load("type=remove_markets&step=4", "#update_markets_ajax", "review / confirm markets removal", merged_data, true); // Secured
          
          }
     	
     	'> Review / Confirm Changes </button>
     	
     	
</p>


<div id="asset_markets_alerts" class='red red_dotted input_margins' style='display: none; font-weight: bold;'></div>


<div class='ct_jstree' id="asset_markets"></div>














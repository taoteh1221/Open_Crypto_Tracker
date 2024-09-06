<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>
	

<p class='jstree_remove_selected'>


<button class='red input_margins' type="button" onclick="jstree_remove('assets');">Remove Selected Assets</button> &nbsp; &nbsp; &nbsp; 

     	
<button class='bitcoin force_button_style input_margins' onclick='


          if ( getObjectLength(jstree_json_data) < 1 ) {
          alert("Nothing removed yet, no changes to save.");
          return false;
          }
          else {
     	
     	var post_data = {
     	                  "remove_markets_mode": "<?=$_POST['remove_markets_mode']?>",
     	                   };
     	
     	var merged_data = merge_objects(post_data, jstree_json_data);
     	
     	ct_ajax_load("type=remove_markets&step=4", "#update_markets_ajax", "review / confirm assets removal", merged_data, true); // Secured
          
          }
     	
     	'> Review / Confirm Changes </button>
     	
     	
</p>
     	

<div id="assets_alerts" class='red red_dotted input_margins' style='font-weight: bold;'>BTC / ETH / SOL assets are required (for currency conversions / other PRIMARY features), SO THEY CANNOT BE REMOVED.</div>


<div class='ct_jstree' id="assets"></div>


<script>

jstree_json_ajax("type=jstree&assets=true", "assets", true); // Secured

</script>


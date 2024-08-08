<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>
	

<p id="assets_delete_selected"><button class='input_margins' type="button" onclick="jstree_delete('assets');">Delete Selected Assets</button></p>


<div id="assets_alerts" class='red red_dotted input_margins' style='font-weight: bold;'>BTC / ETH / SOL assets are required (for currency conversions / other PRIMARY features), SO THEY CANNOT BE DELETED.</div>


<div class='ct_jstree' id="assets"></div>


<script>

jstree_json_ajax("type=jstree&assets=true", "assets", true); // Secured

</script>
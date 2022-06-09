<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>


	<!-- RESET API key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_api' id='reset_api' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_reset')?>&section=reset&refresh=iframe_api' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('reset_api_key')?>' />
	
	<input type='hidden' name='reset_api_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_api_button' class='force_button_style' onclick='
	
	var int_api_key_reset = confirm("Resetting the API key will stop any external \napps from accessing the Internal API with the current key. \n\nPress OK to reset the API key, or CANCEL to keep the current API key. ");
	
		if ( int_api_key_reset ) {
		document.getElementById("reset_api_button").disable = true;
		$("#reset_api").submit(); // Triggers "app reloading" sequence
		document.getElementById("reset_api_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset API Key</button>
	
	</div>
				
	<!-- RESET API key END -->


	<!-- RESET webhook key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_webhook' id='reset_webhook' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_reset')?>&section=reset&refresh=iframe_webhook' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('reset_webhook_key')?>' />
	
	<input type='hidden' name='reset_webhook_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_webhook_button' class='force_button_style' onclick='
	
	var webhook_key_reset = confirm("Resetting the webhook secret key will stop \nany external apps from accessing webhooks \nwith their webhook app key. \n\nPress OK to reset the webhook secret key, or CANCEL to keep the current webhook secret key. ");
	
		if ( webhook_key_reset ) {
		document.getElementById("reset_webhook_button").disable = true;
		$("#reset_webhook").submit(); // Triggers "app reloading" sequence
		document.getElementById("reset_webhook_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset Webhook Keys</button>
	
	</div>
				
	<!-- RESET webhook key END -->


	<!-- RESET lite_charts key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_lite_charts' id='reset_lite_charts' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_reset')?>&section=reset&refresh=iframe_charts_alerts' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('reset_lite_charts')?>' />
	
	<input type='hidden' name='reset_lite_charts' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_lite_charts_button' class='force_button_style' onclick='
	
	var lite_charts_reset = confirm("Resetting the \"light charts\" will rebuild any existing \ntime interval chart data from the archival charts. \n\nPress OK to reset light chart data, or CANCEL to keep the current light charts. ");
	
		if ( lite_charts_reset ) {
		document.getElementById("reset_lite_charts_button").disable = true;
		$("#reset_lite_charts").submit(); // Triggers "app reloading" sequence
		document.getElementById("reset_lite_charts_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset Light (time interval) Charts</button>
	
	</div>
				
	<!-- RESET lite_charts key END -->


		    
<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>
	
				<h3 class='align_center'>Reset</h3>


	<!-- RESET API key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_api' id='reset_api' action='' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=admin_hashed_nonce('reset_api_key')?>' />
	
	<input type='hidden' name='reset_api_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_api_button' class='force_button_style' onclick='
	
	var api_key_reset = confirm("Resetting the API key will stop any external \napps from accessing the API with the current API key. \n\nPress OK to reset the API key, or CANCEL to keep the current API key. ");
	
		if ( api_key_reset ) {
		document.getElementById("reset_api_button").innerHTML = ajax_placeholder(15, "Submitting...");
		document.getElementById("reset_api_button").disable = true;
		$("#reset_api").submit(); // Triggers "app reloading" sequence
		}
	
	'>Reset API Key</button>
	
	</div>
				
	<!-- RESET API key END -->


	<!-- RESET webhook key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_webhook' id='reset_webhook' action='' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=admin_hashed_nonce('reset_webhook_key')?>' />
	
	<input type='hidden' name='reset_webhook_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_webhook_button' class='force_button_style' onclick='
	
	var webhook_key_reset = confirm("Resetting the webhook secret key will stop \nany external apps from accessing webhooks \nwith their webhook app key. \n\nPress OK to reset the webhook secret key, or CANCEL to keep the current webhook secret key. ");
	
		if ( webhook_key_reset ) {
		document.getElementById("reset_webhook_button").innerHTML = ajax_placeholder(15, "Submitting...");
		document.getElementById("reset_webhook_button").disable = true;
		$("#reset_webhook").submit(); // Triggers "app reloading" sequence
		}
	
	'>Reset Webhook Keys</button>
	
	</div>
				
	<!-- RESET webhook key END -->
	
	
			    
</div> <!-- max_1200px_wrapper END -->




		    
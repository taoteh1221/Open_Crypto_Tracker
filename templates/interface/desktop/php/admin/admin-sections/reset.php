<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>


	<!-- RESET internal API key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_int_api' id='reset_int_api' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_reset')?>&section=reset&refresh=iframe_int_api' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('reset_int_api_key')?>' />
	
	<input type='hidden' name='reset_int_api_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_int_api_button' class='force_button_style' onclick='
	
	var int_api_key_reset = confirm("Resetting the internal API key will stop any external apps \nfrom accessing the internal API with the current key. \n\nPress OK to reset, or CANCEL to keep the current key. ");
	
		if ( int_api_key_reset ) {
		document.getElementById("reset_int_api_button").disable = true;
		$("#reset_int_api").submit(); // Triggers "app reloading" sequence
		document.getElementById("reset_int_api_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset Internal API Key</button>
	
	</div>
				
	<!-- RESET internal API key END -->


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


	<!-- RESET light_charts key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_light_charts' id='reset_light_charts' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_reset')?>&section=reset&refresh=iframe_charts_alerts' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('reset_light_charts')?>' />
	
	<input type='hidden' name='reset_light_charts' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_light_charts_button' class='force_button_style' onclick='
	
	var light_charts_reset = confirm("Resetting the \"light charts\" will rebuild any existing \ntime period chart data from the archival charts. \n\nPress OK to reset light chart data, or CANCEL to keep the current light charts. ");
	
		if ( light_charts_reset ) {
		document.getElementById("reset_light_charts_button").disable = true;
		$("#reset_light_charts").submit(); // Triggers "app reloading" sequence
		document.getElementById("reset_light_charts_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset Light (time period) Charts</button>
	
	</div>
				
	<!-- RESET light_charts key END -->


	<!-- RESET ct_conf key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_ct_conf' id='reset_ct_conf' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_reset')?>&section=reset&refresh=all' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('reset_ct_conf')?>' />
	
	<input type='hidden' name='reset_ct_conf' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_ct_conf_button' class='force_button_style' onclick='
	
	var ct_conf_reset = confirm("Resetting the ENTIRE Admin Config will erase ALL customized settings you revised in the admin interface, and reset them to the default settings (found in the hard-coded configuration files). \n\nPress OK to reset the ENTIRE Admin Config, or CANCEL to keep your current settings. ");
	
		if ( ct_conf_reset ) {
		document.getElementById("reset_ct_conf_button").disable = true;
		$("#reset_ct_conf").submit(); // Triggers "app reloading" sequence
		document.getElementById("reset_ct_conf_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset ENTIRE Admin Config (to default settings)</button>
	
	</div>
				
	<!-- RESET ct_conf key END -->


		    
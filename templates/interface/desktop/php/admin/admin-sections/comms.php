<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>
	

	<!-- ENABLE V6 ADMIN PAGES START -->

	<div class='' style='margin: 25px;'>
	
	<form name='toggle_v6_beta' id='toggle_v6_beta' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_comms')?>&section=comms&refresh=iframe_general,iframe_portfolio_assets,iframe_charts_alerts,iframe_plugins,iframe_power_user,iframe_text_gateways,iframe_proxy,iframe_developer,iframe_api,iframe_webhook,iframe_system_stats,iframe_access_stats,iframe_logs,iframe_backup_restore,iframe_reset' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('toggle_v6_beta')?>' />
	
	<input type='hidden' name='set_v6_beta' id='set_v6_beta' value='<?=( $beta_v6_admin_pages == 'on' ? 'off' : 'on' )?>' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='toggle_v6_beta_button' class='force_button_style' onclick='
	
		if ( document.getElementById("set_v6_beta").value == "on" ) {
	    var int_api_key_reset = confirm("Activating these BETA features MAY LEAD TO ISSUES UPDATING YOUR APP CONFIGURATION (editing from the PHP config files will be DISABLED).\n\nYou can RE-disable these BETA features AFTER activating them, and you will be able to update your app configuration from the PHP config files again.");
		}
		else {
	    var int_api_key_reset = confirm("If you disable the BETA features, you will have to update your app configuration from the PHP config files.");
		}
		
		if ( int_api_key_reset ) {
		document.getElementById("toggle_v6_beta_button").disable = true;
		$("#toggle_v6_beta").submit(); // Triggers "app reloading" sequence
		document.getElementById("toggle_v6_beta_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'><?=( $beta_v6_admin_pages == 'on' ? 'Disable' : 'Activate' )?> BETA (experimental / unfinished) v6 Admin Interface</button>
	
	</div>
				
	<!-- ENABLE V6 ADMIN PAGES END -->

<?php
if ( $beta_v6_admin_pages != 'on' ) {
?>
	<p> Coming Soon&trade; </p>
				
	<p class='bitcoin'> Editing these settings is <i>currently only available manually</i>, by updating the file config.php (in this app's main directory: <?=$base_dir?>) with a text editor.</p>

<?php
}
?>	
	
		    
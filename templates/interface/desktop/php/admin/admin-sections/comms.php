<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>
	

	<!-- ENABLE V6 ADMIN PAGES START -->

	<div class='red red_dotted' style='font-size: 20px; margin-bottom: 20px;'>
	
	<form name='toggle_v6_beta' id='toggle_v6_beta' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_comms')?>&section=comms&refresh=iframe_general,iframe_portfolio_assets,iframe_charts_alerts,iframe_plugins,iframe_power_user,iframe_text_gateways,iframe_proxy,iframe_developer,iframe_api,iframe_webhook,iframe_system_stats,iframe_access_stats,iframe_logs,iframe_backup_restore,iframe_reset' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('toggle_v6_beta')?>' />
	
	<input type='hidden' name='sel_v6_beta' id='sel_v6_beta' value='<?=$beta_v6_admin_pages?>' />
	
	<b>BETA (experimental / unfinished) v6 Admin Interface:</b><br /> <input type='radio' name='opt_v6_beta' id='opt_v6_beta_off' value='off' onclick='set_v6_beta(this);' <?=( $beta_v6_admin_pages == 'on' ? '' : 'checked' )?> /> Off &nbsp; <input type='radio' name='opt_v6_beta' id='opt_v6_beta_on' value='on' onclick='set_v6_beta(this);' <?=( $beta_v6_admin_pages == 'on' ? 'checked' : '' )?> /> On
	
	</form>
	
	</div>
				
	<!-- ENABLE V6 ADMIN PAGES END -->
	
	
	<p class='bitcoin bitcoin_dotted' style='display: <?=( $beta_v6_admin_pages == 'on' ? 'block' : 'none' )?>;'>
	
	These sections / category pages will be INCREMENTALLY populated with the corrisponding admin configuration options, over a period of time AFTER the initial v6.00.1 release (v6.00.1 will only test the back-end / under-the-hood stability of THE ON / OFF MODES OF THE BETA v6 Admin Interface). <br /><br />You may need to turn off the BETA v6 Admin Interface to edit any UNFINISHED SECTIONS by hand in the config files (config.php in the app install folder, and any plug-conf.php files in the plugins folder).
	
	</p>
	

<?php
if ( $beta_v6_admin_pages != 'on' ) {
?>
	<p> Coming Soon&trade; </p>
				
	<p class='bitcoin'> Editing these settings is <i>currently only available manually (UNLESS you turn on the BETA v6 Admin Interface)</i>, by updating the file config.php (in this app's main directory: <?=$base_dir?>) with a text editor.</p>

<?php
}
?>	
	
		    
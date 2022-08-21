<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>
	

	<!-- ADMIN PAGES SECURITY LEVEL START -->

	<div class='red red_dotted' style='font-size: 20px; margin-bottom: 20px;'>
	
	<form name='toggle_admin_security' id='toggle_admin_security' action='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_security')?>&section=security&refresh=iframe_comms,iframe_general,iframe_portfolio_assets,iframe_charts_alerts,iframe_plugins,iframe_power_user,iframe_text_gateways,iframe_proxy,iframe_developer,iframe_api,iframe_webhook,iframe_system_stats,iframe_access_stats,iframe_logs,iframe_backup_restore,iframe_reset' method='post'>
	
	<input type='hidden' name='admin_hashed_nonce' value='<?=$ct_gen->admin_hashed_nonce('toggle_admin_security')?>' />
	
	<input type='hidden' name='sel_v6_beta' id='sel_v6_beta' value='<?=$admin_area_sec_level?>' />
	
	<b>Admin Interface Security Level:</b><br /> <input type='radio' name='opt_admin_sec' id='opt_admin_sec_high' value='high' onclick='set_admin_security(this);' <?=( $admin_area_sec_level == 'normal' ? '' : 'checked' )?> /> High &nbsp; <input type='radio' name='opt_admin_sec' id='opt_admin_sec_normal' value='normal' onclick='set_admin_security(this);' <?=( $admin_area_sec_level == 'normal' ? 'checked' : '' )?> /> Normal
	
	</form>
	
	</div>
				
	<!-- ADMIN PAGES SECURITY LEVEL END -->
	

<?php
if ( $admin_area_sec_level == 'normal' ) {
?>
	
	<p> Coming Soon&trade; </p>
	
	<p class='bitcoin bitcoin_dotted'>
	
	These sections / category pages will be INCREMENTALLY populated with the corrisponding admin configuration options, over a period of time AFTER the initial v6.00.x releases (versions 6.00.x will only test the back-end / under-the-hood stability of HIGH / NORMAL MODES of the Admin Interface security levels). <br /><br />You may need to turn off "Normal" mode of the Admin Interface security level (at the top of the "Security" section in this admin area), to edit any UNFINISHED SECTIONS by hand in the config files (config.php in the app install folder, and any plug-conf.php files in the plugins folders).
	
	</p>
	
<?php
}
else {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$base_dir?>) with a text editor.
	
	</p>

<?php
}
?>	
	
		    
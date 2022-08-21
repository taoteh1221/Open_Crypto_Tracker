<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>
	

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
	
		    
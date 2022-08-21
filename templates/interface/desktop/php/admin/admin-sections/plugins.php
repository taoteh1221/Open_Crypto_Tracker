<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>

	
    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Currently Activated Plugins</strong> </legend>
    
    <div class='bitcoin' style='padding: 10px;'>Graphical Interface Plugins<br />
    <ul>
	<?php
	if ( !isset($activated_plugins['ui']) ) {
	echo '<li><span class="black">None</span></li>';
	}
	else {
		foreach ( $activated_plugins['ui'] as $plugin_key => $unused ) {
    	?>
        <li><a href='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_' . $plugin_key)?>&plugin=<?=$plugin_key?>'><?=$plugin_key?></a></li>
    	<?php
    	}
	}
	?>
	</ul>
	</div>
	
    <div class='bitcoin' style='padding: 10px;'>Cron / Task Scheduler Plugins<br />
    <ul>
	<?php
	if ( !isset($activated_plugins['cron']) ) {
	echo '<li><span class="black">None</span></li>';
	}
	else {
		foreach ( $activated_plugins['cron'] as $plugin_key => $unused ) {
    	?>
        <li><a href='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_' . $plugin_key)?>&plugin=<?=$plugin_key?>'><?=$plugin_key?></a></li>
    	<?php
    	}
	}
	?>
	</ul>
	</div>
	
	</fieldset>
				    

	
    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Activate / Deactivate Installed Plugins</strong> </legend>
    

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
				
	
	</fieldset>
	
			    



		    
<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>
	
	
	<p class='bitcoin bitcoin_dotted' style='display: <?=( $beta_v6_admin_pages == 'on' ? 'block' : 'none' )?>;'>
	
	These sections / category pages will be INCREMENTALLY populated with the corrisponding admin configuration options, over a period of time AFTER the initial v6.00.1 release (v6.00.1 will only test the back-end / under-the-hood stability of THE ON / OFF MODES OF THE BETA v6 Admin Interface). <br /><br />You may need to turn off the BETA v6 Admin Interface to edit any UNFINISHED SECTIONS by hand in the config files (config.php in the app install folder, and any plug-conf.php files in the plugins folder).
	
	</p>

	
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
    

	<p> Coming Soon&trade; </p>
				
	<p class='bitcoin'> Editing these settings is <i>currently only available manually (UNLESS you turn on the BETA v6 Admin Interface)</i>, by updating the file config.php (in this app's main directory: <?=$base_dir?>) with a text editor.</p>
				
	
	</fieldset>
	
			    



		    
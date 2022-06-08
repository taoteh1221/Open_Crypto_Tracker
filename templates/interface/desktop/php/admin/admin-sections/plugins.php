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


	<p> Coming Soon&trade; </p>
				
	<p class='bitcoin'> Editing these settings is <i>currently only available manually</i>, by updating the file config.php (in this app's main directory: <?=$base_dir?>) with a text editor.</p>
				
	
	</fieldset>
	
			    



		    
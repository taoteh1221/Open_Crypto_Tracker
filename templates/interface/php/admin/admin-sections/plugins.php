<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>
	 <?php
	 if ( $update_config_success != null ) {
	 ?>
	 <div class='red red_dotted' style='font-weight: bold;'><a href="javascript:app_reloading_check();" target="_PARENT">Reload this page</a>, to show your updated plugin settings below.</div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 ?>
	
	
    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Activate / Deactivate Installed Plugins</strong> </legend>


    <?php
    if ( $admin_area_sec_level == 'high' ) {
    ?>
    	
    	<p class='bitcoin bitcoin_dotted'>
    	
    	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor.
    	
    	</p>
    
    <?php
    }
    else {

     // Render config settings for this section...


     ////////////////////////////////////////////////////////////////////////////////////////////////
     
     
         foreach ( $ct['conf']['plugins']['plugin_status'] as $key => $val ) {
         
         $admin_render_settings['plugin_status']['is_subarray'][$key]['is_radio'] = array(
                                                                                          'off',
                                                                                          'on',
                                                                                         );
                                                                      
         }
         

     ////////////////////////////////////////////////////////////////////////////////////////////////
     
     
     // What OTHER admin pages should be refreshed AFTER this settings update runs
     // (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
     $admin_render_settings['is_refresh_admin'] = 'all';
     
     // $ct['admin']->admin_config_interface($conf_id, $interface_id)
     $ct['admin']->admin_config_interface('plugins', 'plugins', $admin_render_settings);
     
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
     
    }
    ?>	
				
	
	</fieldset>
				    

    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Plugin Settings</strong> </legend>
    
	<?php
	
	$currently_activated_plugins = array();
	
	foreach ( $activated_plugins['ui'] as $plugin_key => $unused ) {
	$currently_activated_plugins[$plugin_key] = true;
	}
	
	foreach ( $activated_plugins['cron'] as $plugin_key => $unused ) {
	$currently_activated_plugins[$plugin_key] = true;
	}
	
	foreach ( $activated_plugins['webhook'] as $plugin_key => $unused ) {
	$currently_activated_plugins[$plugin_key] = true;
	}
	
	if ( sizeof($currently_activated_plugins) < 1 ) {
	echo '<span class="bitcoin">No plugins activated yet.</span>';
	}
	else {
	     
	ksort($currently_activated_plugins);
	
	?>
	   
    <ul>  
    
	     <?php
		foreach ( $currently_activated_plugins as $plugin_key => $unused ) {
    	     ?>
    	     
        <li><a href='admin.php?iframe=<?=$ct['gen']->admin_hashed_nonce('iframe_' . $plugin_key)?>&plugin=<?=$plugin_key?>'><?=$plug_conf[$plugin_key]['ui_name']?></a></li>
        
    	     <?php
    	     }
    	     ?>
    	     
	</ul>
    	
    	<?php
	}
	?>
	
	</div>
	
	</fieldset>

		    
<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


<?php
if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {
?>

	<p class='bitcoin bitcoin_dotted'>
	
	NEVER <i>BLINDLY</i> &nbsp;TRUST any 3rd-party plugins, as they *MAY* contain malicious malware or viruses! Review the plugin source code, OR have somebody you trust review it.
	
	</p>
	
	
	<div style='min-height: 1em;'></div>



    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Plugin Settings</strong> </legend>
    
    <?php

    $currently_activated_plugins = array();
	
     	foreach ( $plug['activated']['ui'] as $plugin_key => $unused ) {
     	$currently_activated_plugins[$plugin_key] = true;
     	}
     	
     	foreach ( $plug['activated']['cron'] as $plugin_key => $unused ) {
     	$currently_activated_plugins[$plugin_key] = true;
     	}
     	
     	foreach ( $plug['activated']['webhook'] as $plugin_key => $unused ) {
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
         	     
             <li><a href='admin.php?iframe_nonce=<?=$ct['sec']->admin_nonce('iframe_' . $plugin_key)?>&plugin=<?=$plugin_key?>'><?=$plug['conf'][$plugin_key]['ui_name']?></a></li>
             
         	     <?php
         	     }
         	     ?>
         	     
     	</ul>
         	
         	<?php
     	}
          ?>
	
	</fieldset>

	
    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Activate / Deactivate Installed Plugins</strong> </legend>


    <?php
    
    // Render config settings for this section...


     ////////////////////////////////////////////////////////////////////////////////////////////////
     
     $ct['admin_render_settings']['plugin_status']['is_confirm']['specific_unselected'] = 'on||DISABLING a plugin will DELETE ANY CUSTOM SETTINGS you added in NORMAL / MEDIUM Security Mode. Do you want to proceed?';
     
     
         foreach ( $ct['conf']['plugins']['plugin_status'] as $key => $val ) {
         
         $ct['admin_render_settings']['plugin_status']['is_subarray'][$key]['is_radio'] = array(
                                                                                          'off',
                                                                                          'on',
                                                                                         );
                                                                      
         }
         

     ////////////////////////////////////////////////////////////////////////////////////////////////
     
     
          if ( sizeof($ct['conf']['plugins']['plugin_status']) > 0 ) {
     
          // What OTHER admin pages should be refreshed AFTER this settings update runs
          // CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
          // (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
          $ct['admin_render_settings']['is_refresh_admin'] = 'all';
          
          // $ct['admin']->admin_config_interface($conf_id, $interface_id)
          $ct['admin']->admin_config_interface('plugins', 'plugins', $ct['admin_render_settings']);
          
          }
          else {
          echo '<p class="bitcoin">No plugins installed.</p>';
          }
     
     ////////////////////////////////////////////////////////////////////////////////////////////////
     
    ?>	
				
	
	</fieldset>


<?php
}
?>	


<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {

section_ids['<?=$_GET['section']?>'] = window.parent.location.href.split('#')[1];

// Change page title

$('#' + section_ids['<?=$_GET['section']?>'] + ' h2.page_title', window.parent.document).html(parent.original_page_title[ section_ids['<?=$_GET['section']?>'] ]); // Restore previous page title
     
});

</script>
				    
				    
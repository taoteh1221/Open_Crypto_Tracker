<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


	 <?php
	 if ( $admin_reset_error != null ) {
	 ?>
	 <div class='red red_dotted' style='font-weight: bold;'><?=$admin_reset_error?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 elseif ( $admin_reset_success != null ) {
	 ?>
	 <div class='green green_dotted' style='font-weight: bold;'><?=$admin_reset_success?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 ?>
	 

<!-- RESET GENERAL settings START -->

<fieldset class='subsection_fieldset'>

<legend class='subsection_legend'> General Reset </legend>

	<!-- RESET internal API key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_int_api' id='reset_int_api' action='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_reset_backup_restore')?>&section=reset_backup_restore&refresh=iframe_webhook_int_api' method='post'>
	
	<input type='hidden' name='admin_nonce' value='<?=$ct['gen']->admin_nonce('reset_int_api_key')?>' />
	
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
	

	<!-- RESET light_charts key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_light_charts' id='reset_light_charts' action='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_reset_backup_restore')?>&section=reset_backup_restore&refresh=none' method='post'>
	
	<input type='hidden' name='admin_nonce' value='<?=$ct['gen']->admin_nonce('reset_light_charts')?>' />
	
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
	
	<form id='reset_ct_conf' action='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_reset_backup_restore')?>&section=reset_backup_restore&refresh=all' method='post'>
	
	<input type='hidden' name='admin_nonce' value='<?=$ct['gen']->admin_nonce('reset_ct_conf')?>' />
	
	<input type='hidden' name='reset_ct_conf' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_ct_conf_button' class='force_button_style' onclick='
	
	var ct_conf_reset = confirm("Resetting the ENTIRE Admin Config will erase ALL setting changes you made in normal / medium admin security modes, and reset them to the hard-coded default settings (found in the PHP configuration files). \n\nPress OK to reset the ENTIRE Admin Config, or CANCEL to keep your current settings. ");
	
		if ( ct_conf_reset ) {
		document.getElementById("reset_ct_conf_button").disable = true;
		$("#reset_ct_conf").submit(); // Triggers "app reloading" sequence
		document.getElementById("reset_ct_conf_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset ENTIRE Admin Config (to default settings)</button>
	
	</div>
				
	<!-- RESET ct_conf key END -->

	
	<?=$ct['gen']->input_2fa('strict')?>
	
</fieldset>

<!-- RESET GENERAL settings END -->



<!-- RESET DIFFERENT webhook keys START -->

<fieldset class='subsection_fieldset'>

<legend class='subsection_legend'> Webhook Keys Reset </legend>

	<!-- RESET webhook MASTER key START -->

	<div style='margin: 25px;'>
	
	<form name='reset_webhook_master' id='reset_webhook_master' action='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_reset_backup_restore')?>&section=reset_backup_restore&refresh=iframe_webhook_int_api' method='post'>
	
	<input type='hidden' name='admin_nonce' value='<?=$ct['gen']->admin_nonce('reset_webhook_master_key')?>' />
	
	<input type='hidden' name='reset_webhook_master_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='reset_webhook_master_button' class='force_button_style' onclick='
	
	var webhook_master_key_reset = confirm("Resetting the MASTER webhook secret key will stop ALL external apps from accessing ALL webhooks with their current webhook app key(s). \n\nPress OK to reset this webhook secret key, or CANCEL to keep the current one. ");
	
		if ( webhook_master_key_reset ) {
		document.getElementById("reset_webhook_master_button").disable = true;
		$("#reset_webhook_master").submit(); // Triggers "app reloading" sequence
		document.getElementById("reset_webhook_master_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset MASTER Webhook Key</button>
	
	</div>
				
	<!-- RESET webhook MASTER key END -->
	
	<?php
	foreach ( $plug['activated']['webhook'] as $plugin_key => $plugin_init ) {
        		
     $webhook_plug = $plugin_key;
     
     $js_safe_var = 'a_' . preg_replace("/[^A-Za-z0-9 ]/", '', $webhook_plug);
        	
          if ( file_exists($plugin_init) && isset($ct['int_webhooks'][$webhook_plug]) ) {
	     ?>
	

	<!-- RESET <?=$webhook_plug?> webhook key START -->

	<div style='margin: 25px;'>
	
	<form name='<?=$webhook_plug?>_webhook' id='<?=$webhook_plug?>_webhook' action='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_reset_backup_restore')?>&section=reset_backup_restore&refresh=iframe_webhook_int_api' method='post'>
	
	<input type='hidden' name='admin_nonce' value='<?=$ct['gen']->admin_nonce('reset_' . $webhook_plug . '_webhook_key')?>' />
	
	<input type='hidden' name='reset_<?=$webhook_plug?>_webhook_key' value='1' />
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='<?=$webhook_plug?>_webhook_button' class='force_button_style' onclick='
	
	var a_<?=$js_safe_var?>_webhook_key_reset = confirm("Resetting the \"<?=$plug['conf'][$webhook_plug]['ui_name']?>\" plugin webhook secret key will stop ALL external apps from accessing its plugin webhook with their current webhook app key. \n\nPress OK to reset this webhook secret key, or CANCEL to keep the current one. ");
	
		if ( a_<?=$js_safe_var?>_webhook_key_reset ) {
		document.getElementById("<?=$webhook_plug?>_webhook_button").disable = true;
		$("#<?=$webhook_plug?>_webhook").submit(); // Triggers "app reloading" sequence
		document.getElementById("<?=$webhook_plug?>_webhook_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
		}
	
	'>Reset "<?=$plug['conf'][$webhook_plug]['ui_name']?>" Plugin Webhook Key</button>
	
	</div>
				
	<!-- RESET <?=$webhook_plug?> webhook key END -->
	
	     <?php
	     }
        	
        	
     // Reset $webhook_plug at end of loop
     unset($webhook_plug); 
             
     }
	?>

	
	<?=$ct['gen']->input_2fa('strict')?>
	
</fieldset>

<!-- RESET DIFFERENT webhook keys END -->



<!-- backup / restore START -->

<fieldset class='subsection_fieldset'>

<legend class='subsection_legend'> Backup & Restore </legend>

<?php

$backup_files = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured/backups', 'zip', 'asc');


if ( is_array($backup_files) && sizeof($backup_files) > 0 ) {

$backup_links = array();

     foreach( $backup_files as $back_file ) {
     
          if ( preg_match("/config-data/i", $back_file) ) {
          $backup_links['config-data'][] = $back_file;
          }
          elseif ( preg_match("/charts-data/i", $back_file) ) {
          $backup_links['charts-data'][] = $back_file;
          }
     
     }

$backup_count_max = ( sizeof($backup_links['charts-data']) > sizeof($backup_links['config-data']) ? sizeof($backup_links['charts-data']) : sizeof($backup_links['config-data']) );

}


?>	

    		
   <ul style='font-weight: bold;'>
	
	<li class='red' style='font-weight: bold;'>CONFIGURATION backups are password-protected, IF you set a password in the "Security => Backup Archive Password" section (HIGHLY RECOMMENDED, for protection of any personal data).</li>	
   
   </ul>
               
               <?=$ct['gen']->table_pager_nav('backup_restore')?>
               
               <table id='backup_restore' border='0' cellpadding='10' cellspacing='0' class="data_table align_center" style='width: 100% !important;'>
                <thead>
                   <tr>
                    <th class="filter-match" data-placeholder="Filter Results">Configuration Backups <span class='bitcoin'>(RESTORE feature soon&trade;)</span></th>
                    <th class="filter-match" data-placeholder="Filter Results">Chart Backups <span class='bitcoin'>(RESTORE feature soon&trade;)</span></th>
                   </tr>
                 </thead>
                 
                <tbody>
                   
                   <?php
                   
                   if ( isset($backup_count_max) ) {
                        
                      $loop = 0;
                      while ( $loop < $backup_count_max ) {
                        
                   ?>
                   
                   <tr>
                   
                     <td><?=( isset($backup_links['config-data'][$loop]) ? '<a href="download.php?backup='. $backup_links['config-data'][$loop] . '" target="_BLANK">' . $backup_links['config-data'][$loop] . '</a>' : '' )?></td>
                     <td><?=( isset($backup_links['charts-data'][$loop]) ? '<a href="download.php?backup='. $backup_links['charts-data'][$loop] . '" target="_BLANK">' . $backup_links['charts-data'][$loop] . '</a>' : '' )?></td>
                   
                   </tr>
                   
                   <?php
                      
                      $loop = $loop + 1;
                      }
                      
                   }
                   else {
                   ?>
                   
                   <tr>
                   
                     <td class='bitcoin'>No backups yet, please check back later.</td>
                     <td class='bitcoin'></td>
                   
                   </tr>
                   
                   <?php
                   }
                   ?>

                </tbody>
                </table>

	
</fieldset>


<!-- backup / restore END -->


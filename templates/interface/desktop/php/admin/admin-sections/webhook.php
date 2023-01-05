<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


$webhook_base_endpoint = ( $app_edition == 'server' ? 'hook/' : 'web-hook.php?webhook_hash=' );

?>

	<p> Webhooks are added via the plugin system built into this app (when a specific plugin's "runtime_mode" is set to "webhook" OR "all"). See <a href='https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/DOCUMENTATION-ETC/PLUGINS-README.txt' target='_blank'>/DOCUMENTATION-ETC/PLUGINS-README.txt</a> for more information on plugin creation / development.</p>
	
	<p>
	
	<b class='bitcoin'>PRO TIP:</b> <br />
	
	You can include ADDITIONAL PARAMETERS *AFTER* THE WEBOOK KEY, USING FORWARD SLASHES: <br /><?=$base_url?><?=$webhook_base_endpoint?>WEBHOOK_KEY/PARAM1/PARAM2/PARAM3
	
	</p>
	
<fieldset class='subsection_fieldset'>
<legend class='subsection_legend'> Active Webhook Plugins  </legend>
<?php

if ( !isset($activated_plugins['webhook']) ) {
echo '<p><span class="black">None</span></p>';
}
	
foreach ( $activated_plugins['webhook'] as $plugin_key => $plugin_init ) {
        		
$webhook_plug = $plugin_key;
        	
    if ( file_exists($plugin_init) && isset($int_webhooks[$webhook_plug]) ) {
    ?>
       
     <p><b class='bitcoin'>Webhook endpoint for "<?=$webhook_plug?>" plugin:</b> <br /><?=$base_url?><?=$webhook_base_endpoint?><?=$ct_gen->nonce_digest($webhook_plug, $int_webhooks[$webhook_plug] . $webhook_master_key)?></p>
     <br /> &nbsp; <br />
     
     <?php
     }
        	
// Reset $webhook_plug at end of loop
unset($webhook_plug); 
        
}
?>
</fieldset>


		    
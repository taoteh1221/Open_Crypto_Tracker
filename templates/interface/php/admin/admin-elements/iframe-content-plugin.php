<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 

$this_plug = $_GET['plugin'];

if ( isset($_GET['plugin_docs']) ) {
$header_link = "<a class='bitcoin' href='admin.php?iframe_nonce=" . $ct['gen']->admin_nonce('iframe_' . $this_plug) . "&plugin=" . $this_plug . "'>" . $plug['conf'][$this_plug]['ui_name'] . "</a> -> Documentation";
}
else {
$header_link = $plug['conf'][$this_plug]['ui_name'];
}

?>
        
        <h3 style='padding-bottom: 10px;' class='bitcoin align_center'><a class='bitcoin custom-unstyle-dropdown-item' href='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_plugins')?>&section=plugins'>Plugins</a>: <?=$header_link?></h3>
        
        
        <?php
        
        // If requested plugin does not exist, log / warn
        if ( !file_exists("plugins/" . $this_plug . "/plug-conf.php") ) {
        $system_error = "Admin area for plugin '" . $this_plug . "' (requested from ".$ct['remote_ip'].") does not exist (no config file found).";
        $ct['gen']->log('system_error', $system_error);
        echo '<p class="red">' . $system_error . '</p>';
        }
        // If requested plugin is not activated, log / warn
        elseif ( $ct['conf']['plugins']['plugin_status'][$this_plug] != 'on' ) {
        $system_error = "Admin area for plugin '" . $this_plug . "' (requested from ".$ct['remote_ip'].") is not enabled (plugin not activated yet).";
        $ct['gen']->log('system_error', $system_error);
        echo '<p class="red">' . $system_error . '</p>';
        }
        else {
        /////////////////////////////////////////////////////////////////////////////////////////////////
        //// P L U G I N   A D M I N   #S T A R T#
        /////////////////////////////////////////////////////////////////////////////////////////////////
        
        
             if ( $ct['admin_area_sec_level'] != 'high' ) {
             ?>
             
          	   <p>
          	   <b class='yellow'>Plugin Version:</b> <?=$ct['plug_version'][$this_plug]?>
          	   </p>
             
             <?php
             }
             
             
             if ( $ct['admin_area_sec_level'] != 'high' && !isset($_GET['plugin_docs']) && file_exists("plugins/" . $this_plug . "/plug-templates/plug-docs.php") ) {
             ?>
     	   <p><a style='font-weight: bold; font-size: 20px;' href='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_' . $this_plug)?>&plugin=<?=$this_plug?>&plugin_docs=1'>Plugin Usage / Documentation</a></p>
             <?php
             }

        
             // Docs (can always show, as it's only documentation [no settings])
             if ( $ct['admin_area_sec_level'] != 'high' && isset($_GET['plugin_docs']) && file_exists("plugins/" . $this_plug . "/plug-templates/plug-docs.php") ) {
             require("plugins/" . $this_plug . "/plug-templates/plug-docs.php");
             }
             // Admin high security notice
             elseif ( $ct['admin_area_sec_level'] == 'high' ) {
             ?>
             	
             	<p class='bitcoin bitcoin_dotted'>
             	
             	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's directory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor. You can change the security level in the "Security" section.
             	
             	</p>
             
             <?php
             }
             // Admin (normal / medium security mode)
             elseif ( $ct['admin_area_sec_level'] != 'high' && !isset($_GET['plugin_docs']) && file_exists("plugins/" . $this_plug . "/plug-templates/plug-admin.php") ) {
     
             $ct['admin_render_settings'] = array();
     
             require("plugins/" . $this_plug . "/plug-templates/plug-admin.php");
             
             ?>
             
     
          	 <?php
          	 if ( $admin_general_error != null ) {
          	 ?>
          	 <div class='red red_dotted' style='font-weight: bold;'><?=$admin_general_error?></div>
          	 <div style='min-height: 1em;'></div>
          	 <?php
          	 }
          	 elseif ( $admin_general_success != null ) {
          	 ?>
          	 <div class='green green_dotted' style='font-weight: bold;'><?=$admin_general_success?></div>
          	 <div style='min-height: 1em;'></div>
          	 <?php
          	 }
          	 ?>
          	 
             
             <?php
             
             // $ct['admin']->admin_config_interface($conf_id, $interface_id)
             $ct['admin']->admin_config_interface('plug_conf|' . $this_plug, $this_plug, $ct['admin_render_settings']);
             
             }
             else {
             ?>
             	
             	<p> No admin interface available for this plugin. </p>
             	
             <?php
             }
             ?>	
        
        				
        <?php
        
        /////////////////////////////////////////////////////////////////////////////////////////////////
        //// P L U G I N   A D M I N   #E N D#
        /////////////////////////////////////////////////////////////////////////////////////////////////
        }


?>

<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {

section_ids['plugins'] = window.parent.location.href.split('#')[1];


     // Change page title
     if ( 'admin_plugins' == section_ids['plugins'] ) {
     
     $('#' + section_ids['plugins'] + ' h2.page_title', window.parent.document).html("<?=$ct['gen']->key_to_name($_GET['plugin'])?>");
     
     
         // Highlight corresponding sidebar menu entry AFTER 3 SECONDS (for any core DOM manipulation to complete first)
         setTimeout(function(){
         $('a[submenu-id="' + section_ids['plugins'] + '_<?=$this_plug?>"]', window.parent.document).addClass("secondary-select");
         }, 3000);
     
     }
     
     
});

</script>

<?php
unset($this_plug);
?>
<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 

$this_plug = $_GET['plugin'];
?>
        
        <h3 style='padding-bottom: 10px;' class='bitcoin align_center'><a class='bitcoin' href='admin.php?iframe=<?=$ct_gen->admin_hashed_nonce('iframe_plugins')?>&section=plugins'>Plugins</a>: <?=$this_plug?></h3>
        
        
        <?php
        
        // If requested plugin does not exist, log / warn
        if ( !file_exists("plugins/" . $this_plug . "/plug-conf.php") ) {
        $system_error = "Admin area for plugin '" . $this_plug . "' (requested from ".$remote_ip.") does not exist (no config file found).";
        $ct_gen->log('system_error', $system_error);
        echo '<p class="red">' . $system_error . '</p>';
        }
        // If requested plugin is not activated, log / warn
        elseif ( $ct_conf['power']['activate_plugins'][$this_plug] != 'on' ) {
        $system_error = "Admin area for plugin '" . $this_plug . "' (requested from ".$remote_ip.") is not enabled (plugin not activated yet).";
        $ct_gen->log('system_error', $system_error);
        echo '<p class="red">' . $system_error . '</p>';
        }
        else {
        /////////////////////////////////////////////////////////////////////////////////////////////////
        //// P L U G I N   A D M I N   #S T A R T#
        /////////////////////////////////////////////////////////////////////////////////////////////////
        
        ?>
        
        
        <p> Coming Soon&trade; </p>
        				
        <p class='bitcoin'> Editing these settings is <i>currently only available manually</i>, by updating the file plug-conf.php (in this plugin's directory: <?=$base_dir?>/plugins/<?=$this_plug?>) with a text editor.</p>
        
        				
        <?php
        
        /////////////////////////////////////////////////////////////////////////////////////////////////
        //// P L U G I N   A D M I N   #E N D#
        /////////////////////////////////////////////////////////////////////////////////////////////////
        }


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
unset($this_plug);
?>
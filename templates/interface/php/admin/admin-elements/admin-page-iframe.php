<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 
require("templates/interface/php/header.php");

// Admin template to use    
if ( $admin_area_sec_level == 'enhanced' && !$ct_gen->pass_sec_check($_POST['enhanced_security_nonce'], 'enhanced_security_mode') ) {
require("templates/interface/php/admin/admin-elements/iframe-security-mode.php");
}
elseif ( isset($_GET['section']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-category.php");
}
elseif ( isset($_GET['plugin']) ) {
require("templates/interface/php/admin/admin-elements/iframe-content-plugin.php");
}
    	
    	
// Proxy alerts (if setup by user, and any of them failed, test the failed proxies and log/alert if they seem offline)
if ( $ct_conf['comms']['proxy_alert'] != 'off' ) {
	
	foreach ( $proxy_checkup as $problem_proxy ) {
	$ct_gen->test_proxy($problem_proxy);
	sleep(1);
	}

}
          	
// Log errors, send notifications
$error_log = $ct_cache->error_log();
$debug_log = $ct_cache->debug_log();
$ct_cache->send_notifications();


// IF WE HAVE A LOG WRITE ERROR FOR ANY LOGS, PRINT IT IN THE FOOTER HERE
		
if ( $error_log != true ) {
?>
<div class="red" style='font-weight: bold;'><?=$error_log?></div>
<?php
}
		
if ( $ct_conf['dev']['debug_mode'] != 'off' && $debug_log != true ) {
?>
<div class="red" style='font-weight: bold;'><?=$debug_log?></div>
<?php
}
    		

?>

            	
<div id="iframe_error_alert" style='display: none;'><?php echo $alerts_gui_errors . ( isset($alerts_gui_debugging) && $alerts_gui_debugging != '' ? '============<br />DEBUGGING:<br />============<br />' . $alerts_gui_debugging : '' ); ?></div>

	
<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {
    
//console.log('admin iframe "<?=$_GET['section']?>" loaded.'); // DEBUGGING
    
    function reload_iframes() {
    
    <?php
    // If we need to refresh an admin iframe, to show the updated data
    if ( $_GET['refresh'] ) {
        
        
        if ( $_GET['refresh'] == 'all' ) {
        
        $refresh_admin = array(
                                'iframe_comms',
                                'iframe_ext_api',
                                'iframe_general',
                                'iframe_portfolio_assets',
                                'iframe_charts_alerts',
                                'iframe_plugins',
                                'iframe_power_user',
                                'iframe_text_gateways',
                                'iframe_proxy',
                                'iframe_developer',
                                'iframe_int_api',
                                'iframe_webhook',
                                'iframe_system_stats',
                                'iframe_access_stats',
                                'iframe_logs',
                                'iframe_backup_restore',
                                'iframe_reset',
                               );
                               
        }
        else {
        $refresh_admin = explode(',', $_GET['refresh']);
        }
        
    
        foreach ( $refresh_admin as $refresh ) {
    
            // DONT INCLUDE CURRENT PAGE (OR IT WILL *ENDLESS LOOP* RELOAD IT) 
            if ( isset($refresh) && trim($refresh) != '' && $refresh != 'iframe_' . $_GET['section'] ) {
            ?>
            parent.document.getElementById('<?=$refresh?>').contentWindow.location.reload(true);
            <?php
            }
        
        }
        
    
    }
    ?>
    
    }

// Reload all flagged iframes after 3.5 seconds (to give any newly-revised ct_conf time to re-cache)
setTimeout(reload_iframes, 3500); 

});

</script>  	

<!-- https://getbootstrap.com/docs/5.3/getting-started/download/ -->
<script src="app-lib/js/bootstrap/bootstrap.min.js"></script>
</body>
</html>

<!-- /*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */ -->
 
 <?php
 
flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

 // In case we are redirected to a login template, we include this exit...
 // IN #ANY# CASE, WE SHOULD BE COMPLETELY DONE RENDERING AT THIS POINT
 exit;
 ?>
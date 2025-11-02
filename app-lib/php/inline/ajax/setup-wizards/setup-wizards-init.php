<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Security monitoring
if ( $ct['possible_input_injection'] ) {
     
$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

$security_error_ui = '<br /><br /><span class="red">Possible code injection attack stopped, please DO NOT attempt to inject scripting or HTML into user inputs.</span>';

echo $security_error_ui;

// Log errors before exiting
// WE ALREADY QUEUED THE ERROR LOG ENTRY FOR THIS ISSUE IN: $ct['sec']->malware_scan_string()
$ct['cache']->app_log();
$ct['cache']->send_notifications();

exit;

}
// If we are not admin logged in, OR fail the CSRF security token check, exit
elseif ( !$ct['sec']->admin_logged_in() || !$ct['sec']->pass_sec_check($_GET['gen_nonce'], 'general_csrf_security') ) {
     
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

?>

<p class='red' style='font-weight: bold;'>Invalid security token, please <a href='admin.php' target='_parent'>login again</a>.</p>

<?php

exit; // Exit for security!

}

?>


<script>

running_setup_wizard = true;

disable_nav_save_buttons = 'Disabled when using the setup wizard system.';

console.log('disable_nav_save_buttons = ' + disable_nav_save_buttons);

</script>


<?php
if ( $_GET['type'] == 'add_markets' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-add/add-markets-init.php');
}
elseif ( $_GET['type'] == 'remove_markets' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-remove/remove-markets-init.php');
}


// DEBUGGING POST DATA...
if ( $ct['conf']['power']['debug_mode'] == 'setup_wizards_io' ) {
    
    if ( isset($_POST) && is_array($_POST) ) {
    $ct['gen']->array_debugging($_POST, true);
    }

}
?>

<div id="setup_wizard_error_alert" style='display: none;'><?php echo $ct['alerts_gui_logs']; ?></div>

<script>

// UI alerts for error / notice logs
ui_log_alerts();

running_setup_wizard = false;

</script>


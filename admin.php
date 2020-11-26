<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


// Runtime mode
$runtime_mode = 'ui';

$is_admin = true;

require("config.php");
	

// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || sizeof($stored_admin_login) != 2 ) {
require("templates/interface/php/admin/admin-login/register.php");
exit;
}
// If not logged in
elseif ( sizeof($stored_admin_login) == 2 && !isset($_SESSION['admin_logged_in']) ) {
require("templates/interface/php/admin/admin-login/login.php");
exit;
}
// If logged in
elseif ( sizeof($stored_admin_login) == 2 && isset($_SESSION['admin_logged_in']) ) {
require("templates/interface/php/header.php");
}


?>

<div class='full_width_wrapper align_center'>

	<div id='admin_wrapper' class='align_center' style='margin: auto;'>
	
		<!-- set data-width="full", to have the tab width be 100% of the screen -->
		<ul class="nav nav-tabs-vertical align_center" id="admin_tabs" role="tablist">
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_comms" role="tab" aria-controls="admin_comms">Communications</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_general" role="tab" aria-controls="admin_general">General</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_portfolio_assets" role="tab" aria-controls="admin_portfolio_assets">Portfolio Assets</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_charts_alerts" role="tab" aria-controls="admin_charts_alerts"><?=( $app_config['general']['asset_charts_toggle'] == 'on' ? 'Charts and ' : 'Price ' )?>Alerts</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_power_user" role="tab" aria-controls="admin_power_user">Power User</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_text_gateways" role="tab" aria-controls="admin_text_gateways">Text Gateways</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_proxy" role="tab" aria-controls="admin_proxy">Proxy</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_developer_only" role="tab" aria-controls="admin_developer_only">Developer Only</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width active" data-toggle="tab" data-width="fixed_max" href="#admin_api" role="tab" aria-controls="admin_api">API</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_webook" role="tab" aria-controls="admin_webook">Webhook</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="full" href="#system_charts" role="tab" aria-controls="system_charts">System Charts</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="full" href="#visitor_stats" role="tab" aria-controls="visitor_stats">Visitor Stats</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="full" href="#admin_logs" role="tab" aria-controls="admin_logs">App Logs</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_backup_restore" role="tab" aria-controls="admin_backup_restore">Backup / Restore</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link admin_change_width" data-toggle="tab" data-width="fixed_max" href="#admin_reset" role="tab" aria-controls="admin_reset">Reset</a>
		  </li>
		</ul>
		
		
		<div id='admin_tab_content' class="tab-content align_left">
		
		  <div class="tab-pane" id="admin_comms" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/communications.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_general" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/general.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_portfolio_assets" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/portfolio-assets.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_charts_alerts" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/charts-and-alerts.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_power_user" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/power-user.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_text_gateways" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/text-gateways.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_proxy" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/proxy.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_developer_only" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/developer-only.php"); ?>
		  </div>
		  
		  <div class="tab-pane active" id="admin_api" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/api.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_webook" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/webhook.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="system_charts" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/system-charts.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="visitor_stats" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/visitor-stats.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_logs" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/app-logs.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_backup_restore" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/backup-restore.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_reset" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/reset.php"); ?>
		  </div>
		  
		</div>



	</div> <!-- wrapper END -->
	
</div> <!-- admin index full_width_wrapper END -->


<br clear="all" />


<?php
require("templates/interface/php/footer.php");
?>


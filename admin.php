<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


// Runtime mode
$runtime_mode = 'ui';

$is_admin = 1;

require("config.php");
	

// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_activated || !$admin_login ) {
require("templates/interface/php/admin/admin-login/register.php");
exit;
}
// If not logged in
elseif ( isset($admin_login) && !isset($_SESSION['admin_login']) ) {
require("templates/interface/php/admin/admin-login/login.php");
exit;
}
// If logged in
elseif ( isset($admin_login) && isset($_SESSION['admin_login']) ) {
require("templates/interface/php/header.php");
}


?>

<div class='full_width_wrapper align_center'>

	<div class='max_1350px_wrapper align_center' style='margin: auto;'>
	

		<ul class="nav nav-tabs-vertical align_center" id="admin_tabs" role="tablist">
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_comms" role="tab" aria-controls="admin_comms">Communications</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_general" role="tab" aria-controls="admin_general">General</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_charts_alerts" role="tab" aria-controls="admin_charts_alerts"><?=( $app_config['general']['charts_toggle'] == 'on' ? 'Charts and ' : 'Price ' )?>Alerts</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_portfolio_assets" role="tab" aria-controls="admin_portfolio_assets">Portfolio Assets</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#admin_api_webook" role="tab" aria-controls="admin_api_webook">API / Webhook</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_text_gateways" role="tab" aria-controls="admin_text_gateways">Text Gateways</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_proxy" role="tab" aria-controls="admin_proxy">Proxy</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_power_user" role="tab" aria-controls="admin_power_user">Power User</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_developer_only" role="tab" aria-controls="admin_developer_only">Developer Only</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_backup_restore" role="tab" aria-controls="admin_backup_restore">Backup / Restore</a>
		  </li>
		  <li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#admin_reset" role="tab" aria-controls="admin_reset">Reset</a>
		  </li>
		</ul>
		
		
		<div class="tab-content align_left">
		
		  <div class="tab-pane" id="admin_comms" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/communications.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_general" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/general.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_charts_alerts" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/charts-and-alerts.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_portfolio_assets" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/portfolio-assets.php"); ?>
		  </div>
		  
		  <div class="tab-pane active" id="admin_api_webook" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/api-webhook.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_text_gateways" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/text-gateways.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_proxy" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/proxy.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_power_user" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/power-user.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_developer_only" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/developer-only.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_backup_restore" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/backup-restore.php"); ?>
		  </div>
		  
		  <div class="tab-pane" id="admin_reset" role="tabpanel">
			<?php require("templates/interface/php/admin/admin-sections/reset.php"); ?>
		  </div>
		  
		</div>



	</div> <!-- max_1350px_wrapper END -->
	
</div> <!-- admin index full_width_wrapper END -->


<br clear="all" />


<?php
require("templates/interface/php/footer.php");
?>


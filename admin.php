<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'ui';

$is_admin = 1;

require("templates/interface/php/header.php");

// ADD AUTHENTICATION LOGIC HERE

?>


<ul class="nav nav-tabs-vertical" id="admin_tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#admin_comms" role="tab" aria-controls="admin_comms">Communications</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_general" role="tab" aria-controls="admin_general">General</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_proxy" role="tab" aria-controls="admin_proxy">Proxy</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_charts_alerts" role="tab" aria-controls="admin_charts_alerts">Charts and Alerts</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_power_user" role="tab" aria-controls="admin_power_user">Power User</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_developer_only" role="tab" aria-controls="admin_developer_only">Developer Only</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_text_gateways" role="tab" aria-controls="admin_text_gateways">Text Gateways</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_portfolio_assets" role="tab" aria-controls="admin_portfolio_assets">Portfolio Assets</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_api_webook" role="tab" aria-controls="admin_api_webook">API / Webhook</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#admin_reset" role="tab" aria-controls="admin_reset">Reset</a>
  </li>
</ul>


<div class="tab-content">

  <div class="tab-pane active" id="admin_comms" role="tabpanel">
  Communications
  </div>
  
  <div class="tab-pane" id="admin_general" role="tabpanel">
  General
  </div>
  
  <div class="tab-pane" id="admin_proxy" role="tabpanel">
  Proxy
  </div>
  
  <div class="tab-pane" id="admin_charts_alerts" role="tabpanel">
  Charts and Alerts
  </div>
  
  <div class="tab-pane" id="admin_power_user" role="tabpanel">
  Power User
  </div>
  
  <div class="tab-pane" id="admin_developer_only" role="tabpanel">
  Developer Only
  </div>
  
  <div class="tab-pane" id="admin_text_gateways" role="tabpanel">
  Text Gateways
  </div>
  
  <div class="tab-pane" id="admin_portfolio_assets" role="tabpanel">
  Portfolio Assets
  </div>
  
  <div class="tab-pane" id="admin_api_webook" role="tabpanel">
  API / Webhook
  </div>
  
  <div class="tab-pane" id="admin_reset" role="tabpanel">
  Reset
  </div>
  
</div>


<br clear="all" />


<?php
require("templates/interface/php/footer.php");
?>


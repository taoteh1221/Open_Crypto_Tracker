<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


    if ( $_GET['section'] == 'security' ) {
    require("templates/interface/desktop/php/admin/admin-sections/security.php");
    }
    elseif ( $_GET['section'] == 'comms' ) {
    require("templates/interface/desktop/php/admin/admin-sections/comms.php");
    }
    elseif ( $_GET['section'] == 'general' ) {
    require("templates/interface/desktop/php/admin/admin-sections/general.php");
    }
    elseif ( $_GET['section'] == 'portfolio_assets' ) {
    require("templates/interface/desktop/php/admin/admin-sections/portfolio-assets.php");
    }
    elseif ( $_GET['section'] == 'charts_alerts' ) {
    require("templates/interface/desktop/php/admin/admin-sections/charts-and-alerts.php");
    }
    elseif ( $_GET['section'] == 'plugins' ) {
    require("templates/interface/desktop/php/admin/admin-sections/plugins.php");
    }
    elseif ( $_GET['section'] == 'power_user' ) {
    require("templates/interface/desktop/php/admin/admin-sections/power-user.php");
    }
    elseif ( $_GET['section'] == 'text_gateways' ) {
    require("templates/interface/desktop/php/admin/admin-sections/text-gateways.php");
    }
    elseif ( $_GET['section'] == 'proxy' ) {
    require("templates/interface/desktop/php/admin/admin-sections/proxy.php");
    }
    elseif ( $_GET['section'] == 'developer' ) {
    require("templates/interface/desktop/php/admin/admin-sections/developer.php");
    }
    elseif ( $_GET['section'] == 'api' ) {
    require("templates/interface/desktop/php/admin/admin-sections/api.php");
    }
    elseif ( $_GET['section'] == 'webhook' ) {
    require("templates/interface/desktop/php/admin/admin-sections/webhook.php");
    }
    elseif ( $_GET['section'] == 'system_stats' ) {
    require("templates/interface/desktop/php/admin/admin-sections/system-stats.php");
    }
    elseif ( $_GET['section'] == 'access_stats' ) {
    require("templates/interface/desktop/php/admin/admin-sections/access-stats.php");
    }
    elseif ( $_GET['section'] == 'logs' ) {
    require("templates/interface/desktop/php/admin/admin-sections/app-logs.php");
    }
    elseif ( $_GET['section'] == 'backup_restore' ) {
    require("templates/interface/desktop/php/admin/admin-sections/backup-restore.php");
    }
    elseif ( $_GET['section'] == 'reset' ) {
    require("templates/interface/desktop/php/admin/admin-sections/reset.php");
    }

?>
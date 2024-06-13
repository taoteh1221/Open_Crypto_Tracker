<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


    if ( $_GET['section'] == 'general' ) {
    require("templates/interface/php/admin/admin-sections/general.php");
    }
    elseif ( $_GET['section'] == 'comms' ) {
    require("templates/interface/php/admin/admin-sections/comms.php");
    }
    elseif ( $_GET['section'] == 'ext_apis' ) {
    require("templates/interface/php/admin/admin-sections/ext-apis.php");
    }
    elseif ( $_GET['section'] == 'proxy' ) {
    require("templates/interface/php/admin/admin-sections/proxy.php");
    }
    elseif ( $_GET['section'] == 'security' ) {
    require("templates/interface/php/admin/admin-sections/security.php");
    }
    elseif ( $_GET['section'] == 'currency' ) {
    require("templates/interface/php/admin/admin-sections/currency-support.php");
    }
    elseif ( $_GET['section'] == 'portfolio_assets' ) {
    require("templates/interface/php/admin/admin-sections/portfolio-assets.php");
    }
    elseif ( $_GET['section'] == 'charts_alerts' ) {
    require("templates/interface/php/admin/admin-sections/charts-and-alerts.php");
    }
    elseif ( $_GET['section'] == 'plugins' ) {
    require("templates/interface/php/admin/admin-sections/plugins.php");
    }
    elseif ( $_GET['section'] == 'power_user' ) {
    require("templates/interface/php/admin/admin-sections/power-user.php");
    }
    elseif ( $_GET['section'] == 'news_feeds' ) {
    require("templates/interface/php/admin/admin-sections/news-feeds.php");
    }
    elseif ( $_GET['section'] == 'webhook_int_api' ) {
    require("templates/interface/php/admin/admin-sections/webhook-int-api.php");
    }
    elseif ( $_GET['section'] == 'text_gateways' ) {
    require("templates/interface/php/admin/admin-sections/text-gateways.php");
    }
    elseif ( $_GET['section'] == 'system_stats' ) {
    require("templates/interface/php/admin/admin-sections/system-stats.php");
    }
    elseif ( $_GET['section'] == 'access_stats' ) {
    require("templates/interface/php/admin/admin-sections/access-stats.php");
    }
    elseif ( $_GET['section'] == 'logs' ) {
    require("templates/interface/php/admin/admin-sections/app-logs.php");
    }
    elseif ( $_GET['section'] == 'reset_backup_restore' ) {
    require("templates/interface/php/admin/admin-sections/reset-backup-restore.php");
    }

?>
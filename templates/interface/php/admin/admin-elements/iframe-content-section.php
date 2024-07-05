<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


    if ( $_GET['section'] == 'general' ) {
    require("templates/interface/php/admin/admin-sections/general.php");
    }
    elseif ( $_GET['section'] == 'asset_tracking' ) {
    require("templates/interface/php/admin/admin-sections/asset-tracking.php");
    }
    elseif ( $_GET['section'] == 'reset_backup_restore' ) {
    require("templates/interface/php/admin/admin-sections/reset-backup-restore.php");
    }
    elseif ( $_GET['section'] == 'security' ) {
    require("templates/interface/php/admin/admin-sections/security.php");
    }
    elseif ( $_GET['section'] == 'comms' ) {
    require("templates/interface/php/admin/admin-sections/comms.php");
    }
    elseif ( $_GET['section'] == 'apis' ) {
    require("templates/interface/php/admin/admin-sections/apis.php");
    }
    elseif ( $_GET['section'] == 'plugins' ) {
    require("templates/interface/php/admin/admin-sections/plugins.php");
    }
    elseif ( $_GET['section'] == 'news_feeds' ) {
    require("templates/interface/php/admin/admin-sections/news-feeds.php");
    }
    elseif ( $_GET['section'] == 'power_user' ) {
    require("templates/interface/php/admin/admin-sections/power-user.php");
    }
    elseif ( $_GET['section'] == 'text_gateways' ) {
    require("templates/interface/php/admin/admin-sections/text-gateways.php");
    }
    elseif ( $_GET['section'] == 'proxy' ) {
    require("templates/interface/php/admin/admin-sections/proxy.php");
    }
    elseif ( $_GET['section'] == 'system_monitoring' ) {
    require("templates/interface/php/admin/admin-sections/system-monitoring.php");
    }

?>
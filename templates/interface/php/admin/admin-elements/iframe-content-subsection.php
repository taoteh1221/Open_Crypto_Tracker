<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['is_subsection_config'] = true;

?>
        
        <h3 style='padding-bottom: 10px;' class='bitcoin align_center'><a class='bitcoin custom-unstyle-dropdown-item' id='parent_url' href='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_' . $_GET['parent'])?>&section=<?=$_GET['parent']?>'><?=$ct['gen']->key_to_name($_GET['parent'])?></a>: <?=$ct['gen']->key_to_name($_GET['subsection'])?></h3>


<?php

    if ( $_GET['subsection'] == 'currency_support' ) {
    require("templates/interface/php/admin/admin-sections/asset-tracking/currency-support.php");
    }
    elseif ( $_GET['subsection'] == 'portfolio_assets' ) {
    require("templates/interface/php/admin/admin-sections/asset-tracking/portfolio-assets.php");
    }
    elseif ( $_GET['subsection'] == 'price_alerts_charts' ) {
    require("templates/interface/php/admin/admin-sections/asset-tracking/charts-and-alerts.php");
    }
    elseif ( $_GET['subsection'] == 'system_stats' ) {
    require("templates/interface/php/admin/admin-sections/system-monitoring/system-stats.php");
    }
    elseif ( $_GET['subsection'] == 'access_stats' ) {
    require("templates/interface/php/admin/admin-sections/system-monitoring/access-stats.php");
    }
    elseif ( $_GET['subsection'] == 'logs' ) {
    require("templates/interface/php/admin/admin-sections/system-monitoring/app-logs.php");
    }
    elseif ( $_GET['subsection'] == 'ext_apis' ) {
    require("templates/interface/php/admin/admin-sections/apis/ext-apis.php");
    }
    elseif ( $_GET['subsection'] == 'webhook_int_api' ) {
    require("templates/interface/php/admin/admin-sections/apis/webhook-int-api.php");
    }
    
?>
        
<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {


var section_id = window.parent.location.href.split('#')[1];

// Change page titles etc

$('#' + section_id + ' h2.page_title', window.parent.document).html("<?=$ct['gen']->key_to_name($_GET['subsection'])?>");


    // Highlight corresponding sidebar menu entry AFTER 3 SECONDS (for any core DOM manipulation to complete first)
    setTimeout(function(){
    $('a[submenu-id="' + section_id + '_<?=$_GET['subsection']?>"]', window.parent.document).addClass("secondary-select");
    }, 3000);
     
     
});

</script>


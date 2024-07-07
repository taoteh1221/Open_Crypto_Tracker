<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Asset Tracking</strong> </legend>
    
	   
    <ul>  
    
        <li><a href='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_currency_support')?>&parent=asset_tracking&subsection=currency_support'>Currency Support</a></li>
        
        <li><a href='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_portfolio_assets')?>&parent=asset_tracking&subsection=portfolio_assets'>Portfolio Assets</a></li>
        
        <li><a href='admin.php?iframe_nonce=<?=$ct['gen']->admin_nonce('iframe_price_alerts_charts')?>&parent=asset_tracking&subsection=price_alerts_charts'>Price Alerts / Charts</a></li>
        
	</ul>
    	
	
	</fieldset>


<script>

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function() {


var section_id = window.parent.location.href.split('#')[1];

// Change page title

$('#' + section_id + ' h2.page_title', window.parent.document).html(parent.original_page_title[section_id]); // Restore previous page title
     
});

</script>
				    
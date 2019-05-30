<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>


		<p><a style='color: red; font-weight: bold;' class='show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice</b></a></p>
    
            <div style='display: none;' class='show_chartsnotice' align='left'>
            	
     
					<p style='font-weight: bold; color: red;'>Charts are only generated here for each price alert that is properly configured in the configuration file (config.php). Price alerts must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a> or they will not work, <i>and the charts here will not work either</i> (they will remain blank). The charts page, chart caching, and chart javascript can be disabled in the configuration file (config.php).</p>

            
            </div>

  
  
<?php
foreach ( $exchange_price_alerts as $key => $value ) {
?>
<div style='background-color: #515050; border: 1px solid #808080; border-radius: 5px;' id='<?=$key?>_chart'></div>
<script src='app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>' async></script>
<br/><br/><br/>
<?php
}
?>
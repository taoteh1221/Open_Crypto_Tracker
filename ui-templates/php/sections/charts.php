<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

?>

			<h3 style='display: inline;'>Charts</h3>

<p><?=start_page_html('charts')?></p>			
			
<p><button class="show_chart_settings force_button_style">Chart Settings</button></p>


<div id="show_chart_settings" style="display:none;">

	<h3>Activated Charts</h3>
	
<?php
foreach ( $exchange_price_alerts as $key => $value ) {
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$show_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$show_asset = strtoupper($show_asset);
		
		$show_asset_params = explode("||", $value);
		
			if ( $show_asset == 'BTC' ) {
			$show_asset_params[1] = 'USD';
			}
?>
	<p><input type='checkbox' value='<?=$key?>' onchange='

	var show_charts = document.getElementById("show_charts").value;
	
		if ( this.checked == true ) {
		document.getElementById("show_charts").value = show_charts + this.value + ",";
		}
		else {
		document.getElementById("show_charts").value = show_charts.replace("<?=$key?>,", "");
		}
	
' <?=( in_array($key, $show_charts) ? 'checked' : '' )?> /> <?=$show_asset?> / <?=strtoupper($show_asset_params[1])?> @ <?=ucfirst($show_asset_params[0])?></p>
<?php
}
?>

	<p><button class='force_button_style' onclick='javascript:document.coin_amounts.submit();'>Update Activated Charts</button></p>
	
	<p style='color: red;'>*Charts are not activated by default to increase page loading speed. <i>If you enable "Use cookie data to save values between sessions" on the Settings page before activating your charts, they will stay visible between sessions</i>.</p>

</div>


<script>
$('.show_chart_settings').modaal({
	content_source: '#show_chart_settings'
});
</script>

  
<p><a style='color: red; font-weight: bold;' class='show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice</b></a></p>
    
<div style='display: none;' class='show_chartsnotice' align='left'>
            	
     
	<p style='font-weight: bold; color: red;'>Charts are only available to show for each price alert properly configured in config.php. Price alerts must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a>, or <i>the charts here will not work</i> (this page will remain blank). The chart's tab, page, caching, and javascript can be disabled in config.php.</p>

            
</div>


<?php

// Render the charts
foreach ( $exchange_price_alerts as $key => $value ) {
	$charts_available = 1;
	if ( in_array($key, $show_charts) ) {
	$charts_shown = 1;
?>

<div style='background-color: #515050; border: 1px solid #808080; border-radius: 5px;' id='<?=$key?>_chart'></div>
<script src='app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>' async></script>
<br/><br/><br/>

<?php
	}
	
}

if ( $charts_available == 1 && $charts_shown != 1 ) {
?>
<div align='center' style='min-height: 100px;'>

	<p><img src='ui-templates/media/images/favicon.png' border='0' /></p>
	<p style='font-weight: bold; color: red; position: relative; margin: 15px;'>Click the Chart Settings button to activate charts.</p>
</div>
<?php
}
?>




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

<p style='color: red;'>*Charts are not activated by default to increase page loading speed. <i>If you enable "Use cookie data to save values between sessions" on the Settings page before activating your charts, they will stay visible between sessions</i>.</p>

<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
<p><button class='force_button_style' onclick='document.coin_amounts.submit();'>Update Activated Charts</button></p>

<p><input type='checkbox' onclick='selectAll(this, "activate_charts");' /> Select / Unselect All</p>
	
	<form id='activate_charts' name='activate_charts'>
	
<?php
foreach ( $exchange_price_alerts as $key => $value ) {
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$show_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$show_asset = strtoupper($show_asset);
		
		$show_asset_params = explode("||", $value);
		
			if ( $show_asset == 'BTC' ) {
			$show_asset_params[1] = 'usd';
			}
		
?>

    <div class='long_list'>
    
    	<b><?=$show_asset?> / <?=strtoupper($show_asset_params[1])?> @ <?=ucfirst($show_asset_params[0])?>:</b> &nbsp; &nbsp; 
    	
			<?php
			if ( $show_asset == 'BTC' ) {
			?>

			<input type='checkbox' value='<?=$key?>' onchange='chart_toggle(this);' <?=( in_array("[".$key."]", $show_charts) ? 'checked' : '' )?> /> USD Chart

			<?php
			}
			else {
			?>
	 			
	 		<input type='checkbox' value='<?=$key?>' onchange='chart_toggle(this);' <?=( in_array("[".$key."]", $show_charts) ? 'checked' : '' )?> /> USD Chart &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
			
		   <input type='checkbox' value='<?=$key?>_<?=$show_asset_params[1]?>' onchange='chart_toggle(this);' <?=( in_array("[".$key . '_' . $show_asset_params[1]."]", $show_charts) ? 'checked' : '' )?> /> <?=strtoupper($show_asset_params[1])?> Chart 

			<?php
			}?>

		</div>
		    
<?php
}
?>

	</form>

	<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
	<p><button class='force_button_style' onclick='document.coin_amounts.submit();'>Update Activated Charts</button></p>
	
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
	$alerts_market_parse = explode("||", $exchange_price_alerts[$key] );	
	
	if ( in_array('['.$key.']', $show_charts) ) {
	$charts_shown = 1;
?>

<div style='background-color: #515050; border: 1px solid #808080; border-radius: 5px;' id='<?=$key?>_usd_chart'></div>
<script src='app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>&charted_value=usd' async></script>
<br/><br/><br/>

<?php
	}
	if ( in_array('['.$key.'_'.$alerts_market_parse[1].']', $show_charts) ) {
	$charts_shown = 1;
?>

<div style='background-color: #515050; border: 1px solid #808080; border-radius: 5px;' id='<?=$key?>_<?=$alerts_market_parse[1]?>_chart'></div>
<script src='app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>&charted_value=pairing' async></script>
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




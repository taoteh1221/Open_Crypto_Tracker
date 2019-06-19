<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

?>

<div class='charts_wrapper'>
	
	<h4 style='display: inline;'>Charts</h4>
				
				<span id='reload_countdown4' class='red countdown_notice'></span>
			
	
	<p style='margin-top: 15px; margin-bottom: 15px;'><?=start_page_html('charts')?></p>			
				
	<p><button class="show_chart_settings force_button_style">Activate Charts</button></p>
	
	
	<div id="show_chart_settings" style="display:none;">
	
		
		<h3>Activate Charts</h3>
	
	<p class='red'>*Charts are not activated by default to increase page loading speed. <i>If you enable "Use cookie data to save values between sessions" on the Settings page before activating your charts, they will stay visible between sessions</i>.</p>
	
	<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
	<p><button class='force_button_style' onclick='document.coin_amounts.submit();'>Update Activated Charts</button></p>
	
	<p><input type='checkbox' onclick='selectAll(this, "activate_charts");' /> Select / Unselect All</p>
		
		<form id='activate_charts' name='activate_charts'>
		
	<?php
	
	$zebra_stripe = 'e8e8e8';
	foreach ( $asset_charts_and_alerts as $key => $value ) {
		
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$show_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$show_asset = strtoupper($show_asset);
		
		$show_asset_params = explode("||", $value);
		
			if ( $show_asset == 'BTC' ) {
			$show_asset_params[1] = 'usd';
			}
				
			if ( $show_asset_params[2] == 'chart' || $show_asset_params[2] == 'both' ) {
	?>
	
		<div class='long_list' style='background-color: #<?=$zebra_stripe?>;'>
		
			<b><span class='blue'><?=$show_asset?></span> / <?=strtoupper($show_asset_params[1])?> @ <?=ucfirst($show_asset_params[0])?>:</b> &nbsp; &nbsp; &nbsp; 
			
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
	    
		 		if ( $zebra_stripe == 'e8e8e8' ) {
			 	$zebra_stripe = 'ffffff';
			 	}
			 	else {
			 	$zebra_stripe = 'e8e8e8';
			 	}
		 	
		 	}
		 	
	}
	    
	?>
	<div class='long_list_end'> &nbsp; </div>
	
		</form>
	
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<p><button class='force_button_style' onclick='document.coin_amounts.submit();'>Update Activated Charts</button></p>
		
	</div>
	
	
	<script>
	$('.show_chart_settings').modaal({
		content_source: '#show_chart_settings'
	});
	</script>
	
	  
	<p><a style='font-weight: bold;' class='red show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice</b></a></p>
		
	<div style='display: none;' class='show_chartsnotice' align='left'>
					
		 
		<p class='red' style='font-weight: bold;'>Charts are only available to show for each price alert properly configured in config.php. Price alerts must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a>, or <i>the charts here will not work</i> (this page will remain blank). The chart's tab, page, caching, and javascript can be disabled in config.php.</p>
	
				
	</div>
	
	
	<?php
	
	// Render the charts
	foreach ( $asset_charts_and_alerts as $key => $value ) {
		
		$charts_available = 1;
		$alerts_market_parse = explode("||", $value );	
		
		if ( in_array('['.$key.']', $show_charts) && $alerts_market_parse[2] == 'chart' 
		|| in_array('['.$key.']', $show_charts) && $alerts_market_parse[2] == 'both'  ) {
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
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>Click the Activate Charts button (top left) to add charts.</p>
	</div>
	<?php
	}
	?>
	
				
				
				
</div> <!-- charts_wrapper END -->




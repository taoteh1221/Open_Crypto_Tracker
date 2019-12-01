<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

?>

<div class='charts_page_wrapper'>
	
	<h4 style='display: inline;'>Charts</h4>
				
				<span id='reload_countdown4' class='red countdown_notice'></span>
			
	
	<p style='margin-top: 15px; margin-bottom: 15px;'><?=start_page_html('charts')?></p>			
				
	<p><button class="show_chart_settings force_button_style">Activate Charts</button></p>
	
	
	<div id="show_chart_settings">
	
		
		<h3>Activate Charts</h3>
	
	<p class='red'>*Charts are not activated by default to increase page loading speed / responsiveness. It's recommended to avoid activating too many charts at the same time, to keep your page load times quick. You can enable "Use cookie data to save values between sessions" on the Settings page <i>before activating your charts</i>, if you want them to stay activated between browser sessions.</p>
	
	<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
	<p><button class='force_button_style' onclick='
	document.coin_amounts.submit();
	'>Update Activated Charts</button></p>
	
	<p><input type='checkbox' onclick='selectAll(this, "activate_charts");' /> Select / Unselect All</p>
		
		<form id='activate_charts' name='activate_charts'>
		
	<?php
	
	$zebra_stripe = 'long_list_odd';
	foreach ( $asset_charts_and_alerts as $key => $value ) {
		
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$show_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$show_asset = strtoupper($show_asset);
		
		$show_asset_params = explode("||", $value);
		
				
			if ( $show_asset_params[2] == 'chart' || $show_asset_params[2] == 'both' ) {
	?>
	
		<div class='<?=$zebra_stripe?> long_list <?=( $last_rendered != $show_asset ? 'activate_chart_sections' : '' )?>'>
		
			<b><span class='blue'><?=$show_asset?></span> / <?=strtoupper($show_asset_params[1])?> @ <?=name_rendering($show_asset_params[0])?>:</b> &nbsp; &nbsp; &nbsp; 
			
				<?php
				// USD-paired markets
				if ( $show_asset_params[1] == 'usd' ) {
				?>
	
			   <input type='checkbox' value='<?=$key?>_<?=$show_asset_params[1]?>' onchange='chart_toggle(this);' <?=( in_array("[".$key . '_' . $show_asset_params[1]."]", $show_charts) ? 'checked' : '' )?> /> <?=strtoupper($show_asset_params[1])?> Chart 
	
				<?php
				}
				// CRYPTO-paired markets (WITH USD EQUIV CHARTS INCLUDED)
				else {
				?>
					
				<input type='checkbox' value='<?=$key?>' onchange='chart_toggle(this);' <?=( in_array("[".$key."]", $show_charts) ? 'checked' : '' )?> /> USD Chart &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				
			   <input type='checkbox' value='<?=$key?>_<?=$show_asset_params[1]?>' onchange='chart_toggle(this);' <?=( in_array("[".$key . '_' . $show_asset_params[1]."]", $show_charts) ? 'checked' : '' )?> /> <?=strtoupper($show_asset_params[1])?> Chart 
	
				<?php
				}?>
	
			</div>
				
	<?php
	    
		 		if ( $zebra_stripe == 'long_list_odd' ) {
			 	$zebra_stripe = 'long_list_even';
			 	}
			 	else {
			 	$zebra_stripe = 'long_list_odd';
			 	}
		 	
		 	}
	
	$last_rendered = $show_asset;
	}
	    
	?>
	<div class='long_list_end' style='border-top: 2px solid black;'> &nbsp; </div>
	
		</form>
	
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<p><button class='force_button_style' onclick='
		document.coin_amounts.submit();
		'>Update Activated Charts</button></p>
		
	</div>
	
	
	<script>
	$('.show_chart_settings').modaal({
		content_source: '#show_chart_settings'
	});
	</script>
	
	  
	<p><a style='font-weight: bold;' class='red show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice</b></a></p>
		
	<div style='display: none;' class='show_chartsnotice' align='left'>
					
		 
		<p class='red' style='font-weight: bold;'>Charts are only available to show for each asset properly configured in the charts / asset price alerts configuration section in the file config.php (located in the primary directory of this app). Charts (and asset price alerts) must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a>, or <i>they will not work</i>. The chart's tab, page, caching, and javascript can be disabled in config.php if you choose to not setup a cron job.</p>
		 
		<p class='red' style='font-weight: bold;'>For charts based on crypto price (instead of USD), 24 hour volume is calculated from the asset volume (not the volume of the pairing), because a large percentage of crypto exchanges only provide asset volume data (with no pairing volume data included). For USD-based charts, the 24 hour USD volume is also calculated from the asset volume (and converted to the USD value of that asset volume).</p>
	
				
	</div>
	
	<div class='red' id='charts_error'></div>
	
	<?php
	
	// Render the charts
	foreach ( $asset_charts_and_alerts as $key => $value ) {
		
		$chart_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$chart_asset = strtoupper($chart_asset);
		
		$charts_available = 1;
		$alerts_market_parse = explode("||", $value );	
		
		if ( in_array('['.$key.']', $show_charts) && $alerts_market_parse[2] == 'chart' 
		|| in_array('['.$key.']', $show_charts) && $alerts_market_parse[2] == 'both'  ) {
		$charts_shown = 1;
	?>
	
	<div class='chart_wrapper' id='<?=$key?>_usd_chart'><span class='loading' style='color: <?=$charts_text?>;'> &nbsp; Loading chart for <?=strtoupper($chart_asset)?> / USD @ <?=name_rendering($alerts_market_parse[0])?>...</span></div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>&charted_value=usd");
	});
	
	</script>
	
	<br/><br/><br/>
	
	<?php
		}
		if ( in_array('['.$key.'_'.$alerts_market_parse[1].']', $show_charts) ) {
		$charts_shown = 1;
	?>
	
	<div class='chart_wrapper' id='<?=$key?>_<?=$alerts_market_parse[1]?>_chart'><span class='loading' style='color: <?=$charts_text?>;'> &nbsp; Loading chart for <?=strtoupper($chart_asset)?> / <?=strtoupper($alerts_market_parse[1])?> @ <?=name_rendering($alerts_market_parse[0])?>...</span></div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>&charted_value=pairing");
	});
	
	</script>
	
	<br/><br/><br/>
	
	<?php
		}
		
	}
	
	if ( $charts_available == 1 && $charts_shown != 1 ) {
	?>
	<div align='center' style='min-height: 100px;'>
	
		<p><img src='ui-templates/media/images/favicon.png' alt='' border='0' style='border: 2px solid #d4d8d3; border-radius: 8px;' /></p>
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>Click the Activate Charts button (top left) to add charts.</p>
	</div>
	<?php
	}
	?>
	
				
				
				
</div> <!-- charts_page_wrapper END -->




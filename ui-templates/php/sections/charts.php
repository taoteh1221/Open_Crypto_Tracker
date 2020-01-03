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
				// Markets that are the same as DEFAULT FIAT CONFIG setting
				if ( $show_asset_params[1] == $charts_alerts_btc_fiat_pairing ) {
				?>
	
			   <input type='checkbox' value='<?=$key?>_<?=$show_asset_params[1]?>' onchange='chart_toggle(this);' <?=( in_array("[".$key . '_' . $show_asset_params[1]."]", $show_charts) ? 'checked' : '' )?> /> <?=strtoupper($show_asset_params[1])?> Chart 
	
				<?php
				}
				// All other paired markets (WITH DEFAULT FIAT CONFIG EQUIV CHARTS INCLUDED)
				else {
				?>
					
				<input type='checkbox' value='<?=$key?>' onchange='chart_toggle(this);' <?=( in_array("[".$key."]", $show_charts) ? 'checked' : '' )?> /> <?=strtoupper($charts_alerts_btc_fiat_pairing)?> Chart &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				
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
	
	  
	<p><a style='font-weight: bold;' class='red show' id='chartsnotice' href='#show_chartsnotice' title='Click to show charts notice.' onclick='return false;'><b>Charts Notice / Information</b></a></p>
		
		
	<div style='display: none;' class='show_chartsnotice' align='left'>
		
		<?php
		foreach ( $fiat_currencies as $key => $unused ) {
		$supported_fiat_list .= strtoupper($key) . ' / ';
		}
		$supported_fiat_list = trim($supported_fiat_list);
		$supported_fiat_list = rtrim($supported_fiat_list,'/');
		$supported_fiat_list = trim($supported_fiat_list);
		
		foreach ( $coins_list['BTC']['market_pairing'][$charts_alerts_btc_fiat_pairing] as $key => $unused ) {
		$supported_exchange_list .= name_rendering($key) . ' / ';
		}
		$supported_exchange_list = trim($supported_exchange_list);
		$supported_exchange_list = rtrim($supported_exchange_list,'/');
		$supported_exchange_list = trim($supported_exchange_list);
		?>
					
		<p class='red' style='font-weight: bold;'>The charts <i>primary fiat market</i> is set to: &nbsp; <span class='bitcoin'><?=strtoupper($charts_alerts_btc_fiat_pairing)?> / <?=name_rendering($charts_alerts_btc_exchange)?></span></p>
		
		<p class='red' style='font-weight: bold;'> Other <?=strtoupper($charts_alerts_btc_fiat_pairing)?>-paired exchanges supported in this app are: <?=$supported_exchange_list?>. Other fiat pairings (that are supported in config.php in the "$btc_fiat_pairing" setting) are: <?=$supported_fiat_list?>. !NOT! ALL EXCHANGES SUPPORT ALL FIAT PAIRS, double check any setting changes you make (and check the error log at /cache/logs/errors.log for any reported issues).</p>
		 
		<p class='red' style='font-weight: bold;'>A few crypto exchanges only provide asset volume data (with no pairing volume data included). If 24 hour pair volume is NOT available for a market, it will be emulated via the asset volume multiplied by the <i>current</i> asset market value (which gives us the rough pairing volume for a better chart user experience).</p>
		 
		<p class='red' style='font-weight: bold;'>Charts are only available to show for each asset properly configured in the charts / price alerts configuration section in the file config.php (located in the primary directory of this app). Charts (and price alerts) must be <a href='README.txt' target='_blank'>setup as a cron job on your web server</a>, or <i>they will not work</i>. The chart's tab, page, caching, and javascript can be disabled in config.php if you choose to not setup a cron job.</p>
	
				<br /><br />
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
	
	<div class='chart_wrapper' id='<?=$key?>_<?=strtolower($charts_alerts_btc_fiat_pairing)?>_chart'><span class='loading' style='color: <?=$charts_text?>;'> &nbsp; Loading chart for <?=strtoupper($chart_asset)?> / <?=strtoupper($alerts_market_parse[1])?> @ <?=name_rendering($alerts_market_parse[0])?> (<?=strtoupper($charts_alerts_btc_fiat_pairing)?> Chart)...</span></div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart.js.php?type=asset&asset_data=<?=urlencode($key)?>&charted_value=<?=strtolower($charts_alerts_btc_fiat_pairing)?>");
	});
	
	</script>
	
	<br/><br/><br/>
	
	<?php
		}
		if ( in_array('['.$key.'_'.$alerts_market_parse[1].']', $show_charts) ) {
		$charts_shown = 1;
	?>
	
	<div class='chart_wrapper' id='<?=$key?>_<?=$alerts_market_parse[1]?>_chart'><span class='loading' style='color: <?=$charts_text?>;'> &nbsp; Loading chart for <?=strtoupper($chart_asset)?> / <?=strtoupper($alerts_market_parse[1])?> @ <?=name_rendering($alerts_market_parse[0])?> (<?=strtoupper($alerts_market_parse[1])?> Chart)...</span></div>
	
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
	
		<p><img src='ui-templates/media/images/favicon.png' alt='' border='0' style='border: 2px solid #d4d8d3; border-radius: 15px;' /></p>
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>Click the Activate Charts button (top left) to add charts.</p>
	</div>
	<?php
	}
	?>
	
				
				
				
</div> <!-- charts_page_wrapper END -->




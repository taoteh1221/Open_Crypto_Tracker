<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


	
// Have this script not load any code if asset charts are not turned on
if ( $app_config['general']['asset_charts_toggle'] == 'on' ) {

$charted_value = ( $chart_mode == 'pairing' ? $alerts_market_parse[1] : $default_btc_primary_currency_pairing );
		
// Strip non-alphanumeric characters to use in js vars, to isolate logic for each separate chart
$js_key = preg_replace("/-/", "", $key) . '_' . $charted_value;
		
		
	// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
	if ( file_exists('cache/charts/spot_price_24hr_volume/lite/all_days/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat') != 1
	|| $alerts_market_parse[2] != 'chart' && $alerts_market_parse[2] != 'both' ) {
		
		// If we have disabled this chart AFTER adding it at some point earlier (fixes "loading charts" not closing)
		if ( $alerts_market_parse[2] != 'chart' && $alerts_market_parse[2] != 'both' ) {
		$chart_error_notice = 'Chart data is no longer configured for:';
		}
		else {
		$chart_error_notice = 'No lite chart data built / re-built yet for:';
		}
	
	?>
			
			$("#<?=$key?>_<?=$charted_value?>_chart span.chart_loading").html(' &nbsp; <?=$chart_error_notice?> <?=$chart_asset?> / <?=strtoupper($alerts_market_parse[1])?> @ <?=snake_case_to_name($alerts_market_parse[0])?><?=( $chart_mode != 'pairing' ? ' \(' . strtoupper($charted_value) . ' Value\)' : '' )?>');
			
			$("#<?=$key?>_<?=$charted_value?>_chart span.chart_loading").css({ "background-color": "#9b4b26" });
			
			$("#charts_error").show();
			
			$("#charts_error").html('<p>One or more charts could not be loaded.</p> <p>If you recently installed this app / enabled charts for the first time OR re-configured your lite charts structure, it may take awhile for fully updated charts to appear. "lite charts" need to be built / re-built from archival chart data, so charts always load quickly regardless of time span...this may take a few days to begin to populate longer time period charts.</p> <p>If you updated the charts or primary currency settings in the Admin Config, you may need to click "Select Charts" (top left of this page) and check / uncheck "Select All", and then click "Update Selected Charts" to clear old chart selections (which may remove this notice).</p> <p>Please make sure you have a cron job running (see <a href="README.txt" target="_blank">README.txt</a> for how-to setup a cron job), or charts cannot be activated. Check app error logs too, for write errors (which would indicate improper cache directory permissions).</p>');
			
			window.charts_loaded.push("chart_<?=$js_key?>");
			charts_loading_check(window.charts_loaded);
			
	<?php
	}
	else {		
	?>


var lite_state_<?=$js_key?> = {
  current: 'all'
};
 

$("#<?=$key?>_<?=$charted_value?>_chart span.chart_loading").html(' &nbsp; <img src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading ALL chart for <?=$chart_asset?> / <?=strtoupper($alerts_market_parse[1])?> @ <?=snake_case_to_name($alerts_market_parse[0])?><?=( $chart_mode != 'pairing' ? ' \(' . strtoupper($charted_value) . ' Value\)' : '' )?>...');
	
  
zingchart.bind('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'load', function() {
$("#<?=$key?>_<?=$charted_value?>_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "ajax.php?type=chart&mode=asset_price&asset_data=<?=$key?>&charted_value=<?=$chart_mode?>&days=all", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'complete', function() {
	$("#<?=$key?>_<?=$charted_value?>_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
	window.charts_loaded.push("chart_<?=$js_key?>");
	charts_loading_check(window.charts_loaded);
	});

	zingchart.render({
  	id: '<?=strtolower($key)?>_<?=$charted_value?>_chart',
  	width: '100%',
  	data: json_data
	});

 
});


zingchart.bind('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'label_click', function(e){
	
  if(lite_state_<?=$js_key?>.current === e.labelid){
    return;
  }
  
  // Reset any user-adjusted zoom
  zingchart.exec('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'viewall', {
    graphid: 0
  });
  
  var cut = 0;
  switch(e.labelid) {
  	
  	<?php
	foreach ($app_config['power_user']['lite_chart_day_intervals'] as $lite_chart_days) {
	?>	
	
    case '<?=$lite_chart_days?>':
    	<?php
    	if ( $lite_chart_days == 'all' ) {
    	?>
      var days = '<?=$lite_chart_days?>';
    	<?php
    	}
    	else {
    	?>
      var days = <?=$lite_chart_days?>;
    	<?php
    	}
    	?>
    break;
    
	<?php
	}
	?>
	
    default: 
      var days = 'all';
    break;
    
  }
  
  
		if ( days == 'all' ) {
		lite_chart_text = days.toUpperCase();
		}
		else if ( days == 7 ) {
		lite_chart_text = '1 week';
		}
		else if ( days == 14 ) {
		lite_chart_text = '2 week';
		}
		else if ( days == 30 ) {
		lite_chart_text = '1 month';
		}
		else if ( days == 60 ) {
		lite_chart_text = '2 month';
		}
		else if ( days == 90 ) {
		lite_chart_text = '3 month';
		}
		else if ( days == 180 ) {
		lite_chart_text = '6 month';
		}
		else if ( days == 365 ) {
		lite_chart_text = '1 year';
		}
		else if ( days == 730 ) {
		lite_chart_text = '2 year';
		}
		else if ( days == 1095 ) {
		lite_chart_text = '3 year';
		}
		else if ( days == 1460 ) {
		lite_chart_text = '4 year';
		}
		else {
		lite_chart_text = days + ' day';
		}
		
  
  $("#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload div.chart_reload_message").html("Loading " + lite_chart_text + " chart for <?=$chart_asset?> / <?=strtoupper($alerts_market_parse[1])?> @ <?=snake_case_to_name($alerts_market_parse[0])?><?=( $chart_mode != 'pairing' ? ' \(' . strtoupper($charted_value) . ' Value\)' : '' )?>...");
  
	$("#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload").fadeIn(100); // 0.1 seconds
	
  zingchart.bind('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'complete', function() {
	$( "#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload" ).fadeOut(2500); // 2.5 seconds
	});
  
  zingchart.exec('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'load', {
  	dataurl: "ajax.php?type=chart&mode=asset_price&asset_data=<?=$key?>&charted_value=<?=$chart_mode?>&days=" + days,
    cache: {
        data: true
    }
  });
  
  lite_state_<?=$js_key?>.current = e.labelid;
  
});



<?php
	}

}
	
$chart_mode = null; 
 ?>
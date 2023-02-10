<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


	
// Have this script not load any code if asset charts are not turned on
if ( $ct_conf['gen']['asset_charts_toggle'] == 'on' ) {

$charted_val = ( $chart_mode == 'pair' ? $alerts_mrkt_parse[1] : $default_btc_prim_currency_pair );
		
// Strip non-alphanumeric characters to use in js vars, to isolate logic for each separate chart
$js_key = preg_replace("/-/", "", $key) . '_' . $charted_val;

$pref_chart_time_period = ( isset($_COOKIE['pref_chart_time_period']) ? $_COOKIE['pref_chart_time_period'] : 'all' );
		
	// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
	if ( file_exists('cache/charts/spot_price_24hr_volume/light/'.$pref_chart_time_period.'_days/'.$chart_asset.'/'.$key.'_chart_'.$charted_val.'.dat') != 1
	|| $alerts_mrkt_parse[2] != 'chart' && $alerts_mrkt_parse[2] != 'both' ) {
		
		// If we have disabled this chart AFTER adding it at some point earlier (fixes "loading charts" not closing)
		if ( $alerts_mrkt_parse[2] != 'chart' && $alerts_mrkt_parse[2] != 'both' ) {
		$chart_error_notice = 'Chart data is no longer configured for:';
		}
		else {
		$chart_error_notice = 'No light chart data built / re-built yet for:';
		}
	
	?>
			
			$("#<?=$key?>_<?=$charted_val?>_chart span.chart_loading").html(' &nbsp; <?=$chart_error_notice?> <?=$chart_asset?> / <?=strtoupper($alerts_mrkt_parse[1])?> @ <?=$ct_gen->key_to_name($alerts_mrkt_parse[0])?><?=( $chart_mode != 'pair' ? ' \(' . strtoupper($charted_val) . ' Value\)' : '' )?>');
			
			$("#<?=$key?>_<?=$charted_val?>_chart span.chart_loading").css({ "background-color": "#9b4b26" });
			
			$("#charts_error").show();
			
			$("#charts_error").html('<p class="bitcoin" style="font-weight: bold;"><span class="red">Did you just install this app?</span> If you would like to bootstrap the demo price chart data (get many months of spot price data already pre-populated), <a href="https://github.com/taoteh1221/bootstrapping/raw/main/bootstrap-price-charts-data.zip" target="_blank">download it from github</a>. Just replace your existing /cache/charts/spot_price_24hr_volume/archival folder with the one inside this download archive, and wait until the next background task runs fully (the app will detect the change and rebuild the [light] time period charts with the new chart data). It may take a few additional cron job / scheduled task runs (a couple hours for slower machines), for a full rebuild of all (light) time period charts.</p> <p>One or more charts could not be loaded, make sure price data exists for these markets.</p> <p>If you recently installed this app / enabled charts for the first time, OR re-configured your light charts structure, it may take awhile for fully updated charts to appear. "light charts" need to be built / re-built from archival chart data, so charts always load quickly regardless of time span...this may take a few days to begin to populate longer time period charts.</p> <p>If you updated the charts or primary currency settings in the Admin Config, you may need to click "Select Charts" (top left of this page) and check / uncheck "Select All", and then click "Update Selected Charts" to clear old chart selections (which may remove this notice).</p> <p>If you are using the "Server Edition" of this app, please make sure you have a cron job (or scheduled task) running (see <a href="README.txt" target="_blank">README.txt</a> for how-to setup a cron job), or charts cannot be activated. Check app error logs too, for write errors (which would indicate improper cache directory permissions, or that disk quota / space is completely filled up).</p>');
			
			charts_loaded.push("chart_<?=$js_key?>");
			charts_loading_check();
			
	<?php
	}
	else {		
	?>


var light_state_<?=$js_key?> = {
  current: '<?=$pref_chart_time_period?>'
};
 

$("#<?=$key?>_<?=$charted_val?>_chart span.chart_loading").html(' &nbsp; <img src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading <?=$ct_gen->light_chart_time_period($pref_chart_time_period, 'long')?> chart for <?=$chart_asset?> / <?=strtoupper($alerts_mrkt_parse[1])?> @ <?=$ct_gen->key_to_name($alerts_mrkt_parse[0])?><?=( $chart_mode != 'pair' ? ' \(' . strtoupper($charted_val) . ' Value\)' : '' )?>...');
	
  
zingchart.bind('<?=strtolower($key)?>_<?=$charted_val?>_chart', 'load', function() {
$("#<?=$key?>_<?=$charted_val?>_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "ajax.php?type=chart&mode=asset_price&asset_data=<?=$key?>&charted_val=<?=$chart_mode?>&days=<?=$pref_chart_time_period?>", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('<?=strtolower($key)?>_<?=$charted_val?>_chart', 'complete', function() {
	$("#<?=$key?>_<?=$charted_val?>_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
	charts_loaded.push("chart_<?=$js_key?>");
	charts_loading_check();
	});

	zingchart.render({
  	id: '<?=strtolower($key)?>_<?=$charted_val?>_chart',
  	width: '100%',
  	data: json_data
	});

 
});


zingchart.bind('<?=strtolower($key)?>_<?=$charted_val?>_chart', 'label_click', function(e){
    
// Set scroll position upon chart link clicks, to avoid page jumping from other zingchart bindings
// when the charts page is set as the start page
store_scroll_position(); 
	
  if(light_state_<?=$js_key?>.current === e.labelid){
    return;
  }
  
  // Reset any user-adjusted zoom
  zingchart.exec('<?=strtolower($key)?>_<?=$charted_val?>_chart', 'viewall', {
    graphid: 0
  });
  
  var cut = 0;
  switch(e.labelid) {
  	
  	<?php
	foreach ($ct_conf['power']['light_chart_day_intervals'] as $light_chart_days) {
	?>	
	
    case '<?=$light_chart_days?>':
    var days = '<?=$light_chart_days?>';
    break;
    
	<?php
	}
	?>
	
    default: 
      var days = 'all';
    break;
    
  }
  
  
  light_chart_text = light_chart_time_period(days, 'long');	
  
  
  $("#<?=strtolower($key)?>_<?=$charted_val?>_chart div.chart_reload div.chart_reload_msg").html("Loading " + light_chart_text + " chart for <?=$chart_asset?> / <?=strtoupper($alerts_mrkt_parse[1])?> @ <?=$ct_gen->key_to_name($alerts_mrkt_parse[0])?><?=( $chart_mode != 'pair' ? ' \(' . strtoupper($charted_val) . ' Value\)' : '' )?>...");
  
	$("#<?=strtolower($key)?>_<?=$charted_val?>_chart div.chart_reload").fadeIn(100); // 0.1 seconds
	
  zingchart.bind('<?=strtolower($key)?>_<?=$charted_val?>_chart', 'complete', function() {
	$( "#<?=strtolower($key)?>_<?=$charted_val?>_chart div.chart_reload" ).fadeOut(2500); // 2.5 seconds
	});
  
  zingchart.exec('<?=strtolower($key)?>_<?=$charted_val?>_chart', 'load', {
  	dataurl: "ajax.php?type=chart&mode=asset_price&asset_data=<?=$key?>&charted_val=<?=$chart_mode?>&days=" + days,
    cache: {
        data: true
    }
  });
  
  light_state_<?=$js_key?>.current = e.labelid;
  
});



<?php
	}

}
	
$chart_mode = null; 
 ?>
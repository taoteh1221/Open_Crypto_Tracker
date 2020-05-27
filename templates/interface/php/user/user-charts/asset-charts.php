<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


	
	// Have this script not load any code if asset charts are not turned on
	if ( $app_config['general']['charts_toggle'] != 'on' ) {
	exit;
	}


	foreach ( $app_config['charts_alerts']['tracked_markets'] as $key => $value ) {
		
 
		if ( $_GET['asset_data'] == $key ) {
			
 
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$chart_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$chart_asset = strtoupper($chart_asset);
		
		$market_parse = explode("||", $value );


		$charted_value = ( $_GET['charted_value'] == 'pairing' ? $market_parse[1] : $default_btc_primary_currency_pairing );
		
		
		// Strip non-alphanumeric characters to use in js vars, to isolate logic for each separate chart
		$js_key = preg_replace("/-/", "", $key) . '_' . $charted_value;
		
		
			// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
			if ( file_exists('cache/charts/spot_price_24hr_volume/lite/all_day/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat') != 1
			|| $market_parse[2] != 'chart' && $market_parse[2] != 'both' ) {
			?>
			
			$("#<?=$key?>_<?=$charted_value?>_chart span.chart_loading").html(' &nbsp; No chart data activated yet for: <?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=snake_case_to_name($market_parse[0])?><?=( $_GET['charted_value'] != 'pairing' ? ' \(' . strtoupper($charted_value) . ' Value\)' : '' )?>');
			
			$("#<?=$key?>_<?=$charted_value?>_chart").css({ "background-color": "#9b4b26" });
			
			$("#charts_error").show();
			
			$("#charts_error").html('One or more charts could not be loaded. If you recently updated the charts or primary currency settings in the admin configuration, it may take up to 90 minutes or more for changes to appear ("lite charts" need to be created from archival chart data, so charts always load quickly regardless of time span). Please make sure you have a cron job running (see <a href="README.txt" target="_blank">README.txt</a> for how-to setup a cron job), or charts cannot be activated. Check app error logs too, for write errors (which would indicate improper cache directory permissions).');
			
			window.charts_loaded.push("chart_<?=$js_key?>");
			
			charts_loading_check(window.charts_loaded);
			
			<?php
			exit;
			}
			

header('Content-type: text/html; charset=' . $app_config['developer']['charset_default']);

?>


var stockState_<?=$js_key?> = {
  current: 'ALL'
};
 
 
  $("#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload div").html("Loading all days chart for <?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=snake_case_to_name($market_parse[0])?><?=( $_GET['charted_value'] != 'pairing' ? ' \(' . strtoupper($charted_value) . ' Value\)' : '' )?>...");
  $("#<?=strtolower($key)?>_<?=$charted_value?>_chart").css('min-height', '65px');
	$("#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload").show(250); // 0.25 seconds
	
  zingchart.bind('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'load', function() {
	$( "#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload" ).fadeOut( "slow" );
	});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "json.php?asset_data=<?=$_GET['asset_data']?>&charted_value=<?=$_GET['charted_value']?>&days=all", function( json_data ) {
 
	zingchart.render({
  	id: '<?=strtolower($key)?>_<?=$charted_value?>_chart',
  	width: '100%',
  	data: json_data
	});
 
});
 
 
zingchart.bind('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'label_click', function(e){
	
  if(stockState_<?=$js_key?>.current === e.labelid && e.labelid != 'RESET'){
    return;
  }
  
  
  	if ( e.labelid === 'RESET' ) {
  	e.labelid = 'ALL';
  	}
  
  var cut = 0;
  switch(e.labelid) {
  	
    case '3D':
      var days = 3;
    break;
    
    case '1W': 
      var days = 7;
    break;
    
    case '1M':
      var days = 30;
    break;
    
    case '3M':
      var days = 90;
    break;
    
    case '6M': 
      var days = 180;
    break;
    
    case '1Y': 
      var days = 365;
    break;
    
    case '2Y': 
      var days = 730;
    break;
    
    case '4Y': 
      var days = 1460;
    break;
    
    case 'ALL': 
      var days = 'all';
    break;
    
    default: 
      var days = 'all';
    break;
    
  }
  
  
  $("#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload div").html("Loading " + days + " days chart for <?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=snake_case_to_name($market_parse[0])?><?=( $_GET['charted_value'] != 'pairing' ? ' \(' . strtoupper($charted_value) . ' Value\)' : '' )?>...");
	$("#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload").show(250); // 0.25 seconds
	
  zingchart.bind('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'load', function() {
	$( "#<?=strtolower($key)?>_<?=$charted_value?>_chart div.chart_reload" ).fadeOut( "slow" );
	});
  
  zingchart.exec('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'load', {
  	dataurl: "json.php?asset_data=<?=$_GET['asset_data']?>&charted_value=<?=$_GET['charted_value']?>&days=" + days,
    cache: {
        data: true
    }
  });
  
  stockState_<?=$js_key?>.current = e.labelid;
  
});


$("#<?=$key?>_<?=$charted_value?>_chart span").hide(); // Hide "Loading chart X..." after it loads
			
window.charts_loaded.push("chart_<?=$js_key?>");

charts_loading_check(window.charts_loaded);



<?php
		

		}
	
	}
	
 
 ?>
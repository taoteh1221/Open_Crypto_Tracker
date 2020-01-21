<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


	
	// Have this script not load any code if asset charts are not turned on
	if ( $app_config['charts_page'] != 'on' ) {
	exit;
	}


	foreach ( $app_config['asset_charts_and_alerts'] as $key => $value ) {
		
 
		if ( $_GET['asset_data'] == $key ) {
			
 
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$chart_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, $app_config['charset_array']['standard']) ) );
		$chart_asset = strtoupper($chart_asset);
		
		$market_parse = explode("||", $value );


		$charted_value = ( $_GET['charted_value'] == 'pairing' ? $market_parse[1] : $default_btc_primary_currency_pairing );
		
		
		// Strip non-alphanumeric characters to use in js vars, to isolate logic for each separate chart
		$js_key = preg_replace("/-/", "", $key) . '_' . $charted_value;
		

			
			// Unicode asset symbols
			if ( array_key_exists($charted_value, $app_config['bitcoin_market_currencies']) ) {
			$currency_symbol = $app_config['bitcoin_market_currencies'][$charted_value];
			$fiat_equiv = 1;
			}
			elseif ( array_key_exists($charted_value, $app_config['crypto_to_crypto_pairing']) ) {
			$currency_symbol = $app_config['crypto_to_crypto_pairing'][$charted_value];
			}
			// Fallback for currency symbol config errors
			else {
			$currency_symbol = strtoupper($charted_value) . ' ';
			}
			
		
			// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
			if ( file_exists('cache/charts/spot_price_24hr_volume/archival/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat') != 1
			|| $market_parse[2] != 'chart' && $market_parse[2] != 'both' ) {
			?>
			
			$("#<?=$key?>_<?=$charted_value?>_chart span.loading").html(' &nbsp; No chart data activated for: <?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=name_rendering($market_parse[0])?> \(<?=strtoupper($charted_value)?> Chart\)');
			
			$("#charts_error").show();
			
			$("#charts_error").html('One or more charts could not be loaded. Please make sure you have a cron job running (see <a href="README.txt" target="_blank">README.txt</a> for how-to setup a cron job), or charts cannot be activated. Check app error logs too, for write errors (which would indicate improper cache directory permissions).');
			
			window.charts_loaded.push("chart_<?=$js_key?>");
			
			charts_loading_check(charts_loaded);
			
			<?php
			exit;
			}
			
		
		$chart_data = chart_data('cache/charts/spot_price_24hr_volume/archival/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat', $market_parse[1]);
		
		
		$price_sample = substr( $chart_data['spot'] , 0, mb_strpos( $chart_data['spot'] , ",", 0, $app_config['charset_array']['standard'] ) );
		
		
		$spot_price_decimals = ( $fiat_equiv == 1 ? $app_config['primary_currency_decimals_max'] : 8 );
		
			
			// Force decimals under certain conditions
			if ( float_to_string($price_sample) < 0.000001 ) {
			$force_decimals = 'decimals: ' . $spot_price_decimals . ',';
			}
			elseif ( float_to_string($price_sample) >= 1.00 ) {
			$force_decimals = 'decimals: ' . 2 . ',';
			}
		

header('Content-type: text/html; charset=' . $app_config['charset_array']['standard']);

?>



var dates_<?=$js_key?> = [<?=$chart_data['time']?>];
var spots_<?=$js_key?> = [<?=$chart_data['spot']?>];
var volumes_<?=$js_key?> = [<?=$chart_data['volume']?>];

var stockState_<?=$js_key?> = {
  current: 'ALL',
  dates: dates_<?=$js_key?>,
  spots: spots_<?=$js_key?>,
  volumes: volumes_<?=$js_key?>
};
 
function getspotConfig_<?=$js_key?>(dates, values, current) {
  return {
  type: 'area',
  "preview":{
  		label: {
      color: '<?=$app_config['charts_text']?>',
      fontSize: '10px',
      lineWidth: '1px',
      lineColor: '<?=$app_config['charts_line']?>',
     	},
 	  live: true,
 	  "adjust-layout": true,
 	  "alpha-area": 0.5,
 	  	height: 30
  },
  backgroundColor: "<?=$app_config['charts_background']?>",
  height: 420,
  x: 0, 
  y: 0,
  globals: {
  	fontSize: 20,
  	fontColor: "<?=$app_config['charts_text']?>"
  },
  crosshairX:{
    shared: true,
    exact: true,
    plotLabel:{
      backgroundColor: "<?=$app_config['charts_tooltip_background']?>",
      fontColor: "<?=$app_config['charts_tooltip_text']?>",
      text: "Spot Price: <?=$currency_symbol?>%v",
	 	fontSize: "20",
      fontFamily: "Open Sans",
      <?=$force_decimals?> /* -- price_sample: <?=$price_sample?> -- */ 
      y:0,
      "thousands-separator":",",
    },
    scaleLabel:{
    	alpha: 1.0,
      fontColor: "<?=$app_config['charts_tooltip_text']?>",
      fontSize: 20,
      fontFamily: "Open Sans",
      backgroundColor: "<?=$app_config['charts_tooltip_background']?>",
    }
  },
  title: {
    text: "<?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=name_rendering($market_parse[0])?> (<?=strtoupper($charted_value)?> Chart)",
    fontColor: "<?=$app_config['charts_text']?>",
    fontFamily: 'Open Sans',
    fontSize: 23,
    align: 'right',
    offsetX: -18,
    offsetY: 4
  },
  zoom: {
    shared: true
  },
  plotarea: {
    margin: "60 65 55 115"
  },
  plot: {
    marker:{
      visible: false
    },
    tooltip: {
    	fontSize: 20
    }
  },
  tooltip:{
    text: "Spot Price: <?=$currency_symbol?>%v",
    fontColor: "<?=$app_config['charts_tooltip_text']?>",
	 fontSize: "20",
    backgroundColor: "<?=$app_config['charts_tooltip_background']?>",
    "thousands-separator":","
  },
  scaleY: {
    "format":"<?=$currency_symbol?>%v",
    "thousands-separator":",",
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$app_config['charts_line']?>"
    },
    item: {
      fontColor: "<?=$app_config['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "14",
    }
  },
  scaleX: {
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$app_config['charts_line']?>"
    },
    values: dates,
 	  transform: {
 	    type: 'date',
 	    all: '%Y/%m/%d<br />%g:%i%a'
 	  },
   	zooming:{
      shared: true
    },
    item: {
	 fontSize: "14",
      fontColor: "<?=$app_config['charts_text']?>",
      fontFamily: "Open Sans"
    }
  },
	series : [
		{
			values: values,
			lineColor: "<?=$app_config['charts_text']?>",
			lineWidth: 1,
			backgroundColor:"<?=$app_config['charts_text']?> <?=$app_config['charts_price_gradient']?>", /* background gradient on graphed price area in main chart (NOT the chart background) */
			alpha: 0.5,
				previewState: {
      		backgroundColor: "<?=$app_config['charts_price_gradient']?>" /* background color on graphed price area in preview below chart (NOT the preview area background) */
				}
		}
	],
	labels: [
	  {
	    x: 80,
	    y: 10,
	    id: '1D',
	    fontColor: (current === '1D') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1D"
	  },
	  {
	    x: 130,
	    y: 10,
	    id: '1W',
	    fontColor: (current === '1W') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1W"
	  },
	  {
	    x: 180,
	    y: 10,
	    id: '1M',
	    fontColor: (current === '1M') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1M"
	  },
	  {
	    x: 230,
	    y: 10,
	    id: '3M',
	    fontColor: (current === '3M') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "3M"
	  },
	  {
	    x: 280,
	    y: 10,
	    id: '6M',
	    fontColor: (current === '6M') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "6M"
	  },
	  {
	    x: 330,
	    y: 10,
	    id: '1Y',
	    fontColor: (current === '1Y') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1Y"
	  },
	  {
	    x: 380,
	    y: 10,
	    id: '2Y',
	    fontColor: (current === '2Y') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "2Y"
	  },
	  {
	    x: 430,
	    y: 10,
	    id: '4Y',
	    fontColor: (current === '4Y') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "4Y"
	  },
	  {
	    x: 480,
	    y: 10,
	    id: 'ALL',
	    fontColor: (current === 'ALL') ? "<?=$app_config['charts_text']?>" : "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "ALL"
	  },
	  {
	    x: 547,
	    y: 10,
	    id: 'RESET',
	    fontColor: "<?=$app_config['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "RESET"
	  }
	]
};
}
 
function getVolumeConfig_<?=$js_key?>(dates, values) {
  return {
  type: 'bar',
  height: 70,
  x: 0, 
  y: 400,
  backgroundColor: "<?=$app_config['charts_background']?>",
  plotarea: {
    margin: "11 63 5 112"
  },
  plot: {
  	barSpace: "0px",
  	barsSpaceLeft: "0px",
  	barsSpaceRight: "0px"
  },
  source: {
    text: "24 Hour Volume",
    fontColor:"<?=$app_config['charts_text']?>",
	 fontSize: "13",
    fontFamily: "Open Sans",
    offsetX: 106,
    offsetY: 13,
    align: 'left'
  },
  tooltip:{
    visible: false,
    text: "24 Hour Volume: <?=$currency_symbol?>%v",
    fontColor: "<?=$app_config['charts_tooltip_text']?>",
	 fontSize: "20",
    backgroundColor: "<?=$app_config['charts_tooltip_background']?>",
    fontFamily: "Open Sans",
    "thousands-separator":","
  },
  zoom: {
    shared: true
  },
  crosshairX:{
    shared: true,
    exact: true,
    scaleLabel:{
      visible: false
    },
    plotLabel:{
      backgroundColor: "<?=$app_config['charts_tooltip_background']?>",
      fontColor: "<?=$app_config['charts_tooltip_text']?>",
      fontFamily: "Open Sans",
      text: "24 Hour Volume: <?=$currency_symbol?>%v",
	 	fontSize: "20",
      y:0,
      "thousands-separator":","
    }
  },
  scaleX: {
    visible: false,
    zooming: true
  },
  scaleY: {
    "format":"<?=$currency_symbol?>%v",
    "thousands-separator":",",
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$app_config['charts_line']?>"
    },
    item: {
      fontColor: "<?=$app_config['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "12",
    }
  },
	series : [
		{
			values: values,
			text: "24hr Volume",
			backgroundColor: "<?=$app_config['charts_text']?>",
    		offsetX: 0
		}
	]
};
}

 zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

zingchart.render({
  id: '<?=strtolower($key)?>_<?=$charted_value?>_chart',
  data: {
    graphset:[
      getspotConfig_<?=$js_key?>(stockState_<?=$js_key?>.dates, stockState_<?=$js_key?>.spots, 'ALL'),
      getVolumeConfig_<?=$js_key?>(stockState_<?=$js_key?>.dates, stockState_<?=$js_key?>.volumes)
    ]
  },
  height: 500, 
	width: '100%'
});
 
 
zingchart.bind('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'label_click', function(e){
  if(stockState_<?=$js_key?>.current === e.labelid && e.labelid != 'RESET'){
    return;
  }
  
  var windowspot_<?=$js_key?> = [];
  var windowVolume_<?=$js_key?> = [];
  var windowDates_<?=$js_key?> = [];
  
  
  	if ( e.labelid === 'RESET' ) {
  	e.labelid = 'ALL';
  	}
  
  var cut = 0;
  switch(e.labelid) {
    case '1D':
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(1)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(1)?>;
    break;
    case '1W': 
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(7)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(7)?>;
    break;
    case '1M':
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(30)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(30)?>;
    break;
    case '3M':
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(91)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(91)?>;
    break;
    case '6M': 
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(183)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(183)?>;
    break;
    case '1Y': 
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(365)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(365)?>;
    break;
    case '2Y': 
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(730)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(730)?>;
    break;
    case '4Y': 
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(1460)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(1460)?>;
    break;
    case 'ALL': 
      cut = stockState_<?=$js_key?>.dates.length;
    break;
    default: 
      cut = stockState_<?=$js_key?>.dates.length;
    break;
  }
    windowspot_<?=$js_key?> = stockState_<?=$js_key?>.spots.slice(stockState_<?=$js_key?>.spots.length-cut);
    windowDates_<?=$js_key?> = stockState_<?=$js_key?>.dates.slice(stockState_<?=$js_key?>.dates.length-cut);
    windowVolume_<?=$js_key?> = stockState_<?=$js_key?>.volumes.slice(stockState_<?=$js_key?>.volumes.length-cut);
    
  zingchart.exec('<?=strtolower($key)?>_<?=$charted_value?>_chart', 'setdata', {
    
    data: {
      graphset:[
        getspotConfig_<?=$js_key?>(windowDates_<?=$js_key?>, windowspot_<?=$js_key?>, e.labelid),
        getVolumeConfig_<?=$js_key?>(windowDates_<?=$js_key?>, windowVolume_<?=$js_key?>)
      ]
    }
  });
 	
  stockState_<?=$js_key?>.current = e.labelid;
  
});


$("#<?=$key?>_<?=$charted_value?>_chart span").hide(); // Hide "Loading chart X..." after it loads
			
charts_loaded.push("chart_<?=$js_key?>");

charts_loading_check(charts_loaded);



<?php
		

		}
	
	}
	
 
 ?>
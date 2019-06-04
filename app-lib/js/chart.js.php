<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
$runtime_mode = 'chart_output';

// Change directory
chdir("../../");
require("config.php");


// Have this script not load any code if charts are not turned on
if ( $charts_page != 'on' ) {
exit;
}

if ( $_GET['type'] == 'asset' ) {

	foreach ( $exchange_price_alerts as $key => $value ) {
 
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$chart_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$chart_asset = strtoupper($chart_asset);
		
		// Strip non-alphanumeric characters to use in js vars, to isolate logic for each separate chart
		$js_key = preg_replace("/-/", "", $key);
		
		$market_parse = explode("||", $exchange_price_alerts[$key] );
		
			if ( $chart_asset == 'BTC' ) {
			$market_parse[1] = 'USD';
			}

 
		if ( $_GET['asset_data'] == $key ) {
			
		// Have this script not load any code (and not leave page endlessly loading) if cache data is not present
		if ( file_exists('cache/charts/'.$chart_asset.'/'.$key.'_chart.dat') != 1 ) {
		exit;
		}
		
		$chart_data = chart_data('cache/charts/'.$chart_asset.'/'.$key.'_chart.dat');
		
		
?>
var dates_<?=$js_key?> = [<?=$chart_data['time']?>];
var spots_<?=$js_key?> = [<?=$chart_data['spot']?>];
var volumes_<?=$js_key?> =[<?=$chart_data['volume']?>];

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
 	  live: true,
 	  "adjust-layout": true,
 	  "alpha-area": 0.5,
		backgroundColor:"#777676 #3D3C3C",
 	  	height: 30
  },
  backgroundColor: "#515050",
  height: 420,
  x: 0, 
  y: 0,
  crosshairX:{
    shared: true,
    exact: true,
    plotLabel:{
      backgroundColor: "#bbb",
      fontColor: "#222",
      text: "Spot Price: $%v",
	 	fontSize: "20",
      fontFamily: "Open Sans",
      y:0,
      "thousands-separator":",",
    },
    scaleLabel:{
      fontColor: "#222",
      fontFamily: "Open Sans",
      backgroundColor: "#bbb",
    }
  },
  title: {
    text: "<?=$chart_asset?> / <?=strtoupper($market_parse[1])?> (USD Value) @ <?=ucfirst($market_parse[0])?>",
    fontColor: "#fff",
    fontFamily: 'Open Sans',
    fontSize: 28,
    align: 'right',
    offsetX: -10
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
    }
  },
  tooltip:{
    text: "Spot Price: $%v",
	 fontSize: "20",
    backgroundColor: "#BBB",
    borderColor:"transparent",
    "thousands-separator":","
  },
  scaleY: {
    "format":"$%v",
    "thousands-separator":",",
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "#444"
    },
    item: {
      fontColor: "#ddd",
      fontFamily: "Open Sans",
      fontSize: "16",
    }
  },
  scaleX: {
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "#444"
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
	 fontSize: "16",
      fontColor: "#ddd",
      fontFamily: "Open Sans"
    }
  },
	series : [
		{
			values: values,
			lineColor: "#fff",
			lineWidth: 1,
			backgroundColor:"#fff #000",
			alpha: 0.5
		}
	],
	labels: [
	  {
	    x: 110,
	    y: 10,
	    id: '1W',
	    fontColor: (current === '1W') ? "#FFF" : "#b5b5b5",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1W"
	  },
	  {
	    x: 160,
	    y: 10,
	    id: '1M',
	    fontColor: (current === '1M') ? "#FFF" : "#b5b5b5",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1M"
	  },
	  {
	    x: 210,
	    y: 10,
	    id: '6M',
	    fontColor: (current === '6M') ? "#FFF" : "#b5b5b5",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "6M"
	  },
	  {
	    x: 260,
	    y: 10,
	    id: '1Y',
	    fontColor: (current === '1Y') ? "#FFF" : "#b5b5b5",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1Y"
	  },
	  {
	    x: 310,
	    y: 10,
	    id: '2Y',
	    fontColor: (current === '2Y') ? "#FFF" : "#b5b5b5",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "2Y"
	  },
	  {
	    x: 360,
	    y: 10,
	    id: 'ALL',
	    fontColor: (current === 'ALL') ? "#FFF" : "#b5b5b5",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "ALL"
	  },
	  {
	    x: 427,
	    y: 10,
	    id: 'RESETZOOM',
	    fontColor: "#b5b5b5",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "RESET ZOOM"
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
  backgroundColor: "#515050",
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
    fontColor:"#fff",
	 fontSize: "15",
    fontFamily: "Open Sans",
    offsetX: 108,
    offsetY: 13,
    align: 'left'
  },
  tooltip:{
    visible: false,
    text: "24 Hour Volume: $%v",
	 fontSize: "20",
    fontFamily: "Open Sans",
    borderColor:"transparent",
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
      fontFamily: "Open Sans",
      backgroundColor:"#BBB",
      text: "24 Hour Volume: $%v",
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
    "format":"$%v",
    "thousands-separator":",",
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "#444"
    },
    item: {
      fontColor: "#ddd",
      fontFamily: "Open Sans",
      fontSize: "12",
    }
  },
	series : [
		{
			values: values,
			text: "24hr Volume",
			backgroundColor: "#bbb",
    		offsetX: 0
		}
	]
};
}
 
zingchart.render({
  id: '<?=strtolower($key)?>_chart',
  data: {
    graphset:[
      getspotConfig_<?=$js_key?>(stockState_<?=$js_key?>.dates, stockState_<?=$js_key?>.spots, 'ALL'),
      getVolumeConfig_<?=$js_key?>(stockState_<?=$js_key?>.dates, stockState_<?=$js_key?>.volumes)
    ]
  },
  height: 500, 
	width: '100%'
});
 
 
zingchart.bind('<?=strtolower($key)?>_chart', 'label_click', function(e){
  if(stockState_<?=$js_key?>.current === e.labelid && e.labelid != 'RESETZOOM'){
    return;
  }
  
  var windowspot_<?=$js_key?> = [];
  var windowVolume_<?=$js_key?> = [];
  var windowDates_<?=$js_key?> = [];
  
  
  	if ( e.labelid === 'RESETZOOM' ) {
  	e.labelid = 'ALL';
  	}
  
  var cut = 0;
  switch(e.labelid) {
    case '1W': 
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(7)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(7)?>;
    break;
    case '1M':
      cut = stockState_<?=$js_key?>.dates.length <= <?=chart_range(30)?> ? stockState_<?=$js_key?>.dates.length : <?=chart_range(30)?>;
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
    
  zingchart.exec('<?=strtolower($key)?>_chart', 'setdata', {
    
    data: {
      graphset:[
        getspotConfig_<?=$js_key?>(windowDates_<?=$js_key?>, windowspot_<?=$js_key?>, e.labelid),
        getVolumeConfig_<?=$js_key?>(windowDates_<?=$js_key?>, windowVolume_<?=$js_key?>)
      ]
    }
  });
 	
  stockState_<?=$js_key?>.current = e.labelid;
  
});

<?php
		}
	
	}
	
}
?>

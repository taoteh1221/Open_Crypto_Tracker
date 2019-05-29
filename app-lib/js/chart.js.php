<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
$runtime_mode = 'chart_output';

// Change directory
chdir("../../");
require("config.php");

if ( $charts_page != 'enable' ) {
exit;
}

if ( $_GET['type'] == 'asset' ) {

	foreach ( $exchange_price_alerts as $key => $value ) {
 
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
		$asset = strtoupper($asset);

 
		if ( $_GET['asset_data'] == $key ) {
			
		$chart_data = chart_data('cache/charts/'.$asset.'/'.$key.'_chart.dat');
		
		
?>
var dates = [<?=$chart_data['time']?>];
var closes = [<?=$chart_data['close']?>];
var volumes =[<?=$chart_data['volume']?>];

var stockState = {
  current: '2Y',
  dates: dates,
  closes: closes,
  volumes: volumes
};
 
function getCloseConfig(dates, values, current) {
  return {
  type: 'area',
  backgroundColor: "#333",
  height: 420,
  x: 0, 
  y: 0,
  crosshairX:{
    shared: true,
    plotLabel:{
      backgroundColor: "#bbb",
      fontColor: "#222",
      text: "Close: $%v",
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
    text: "<?=$asset?> / <?=strtoupper($chart_data['pairing'])?> (USD Value)",
    fontColor: "#fff",
    fontFamily: 'Open Sans',
    fontSize: 30,
    align: 'left',
    offsetX: 10
  },
  zoom: {
    shared: true
  },
  plotarea: {
    margin: "60 50 40 50"
  },
  plot: {
    marker:{
      visible: false
    }
  },
  tooltip:{
    text: "Close: %v",
    backgroundColor: "#BBB",
    borderColor:"transparent",
            "thousands-separator":","
  },
  scaleY: {
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "#444"
    },
    item: {
      fontColor: "#ddd",
      fontFamily: "Open Sans"
    },
    "thousands-separator":","
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
 	    all: '%Y/%m/%d<br />%H:%i'  
 	  },
   	zooming:{
      shared: true
    },
    item: {
      fontColor: "#ddd",
      fontFamily: "Open Sans"
    }
  },
	series : [
		{
			values: values,
			lineColor: "#fff",
			lineWidth: 1,
			backgroundColor:"#909090 #313131"
		}
	],
	labels: [
	  {
	    x: 490,
	    y: 10,
	    id: '1W',
	    fontColor: (current === '1W') ? "#FFF" : "#777",
	    fontSize: "16",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1W"
	  },
	  {
	    x: 530,
	    y: 10,
	    id: '1M',
	    fontColor: (current === '1M') ? "#FFF" : "#777",
	    fontSize: "16",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1M"
	  },
	  {
	    x: 570,
	    y: 10,
	    id: '6M',
	    fontColor: (current === '6M') ? "#FFF" : "#777",
	    fontSize: "16",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "6M"
	  },
	  {
	    x: 610,
	    y: 10,
	    id: '1Y',
	    fontColor: (current === '1Y') ? "#FFF" : "#777",
	    fontSize: "16",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1Y"
	  },
	  {
	    x: 650,
	    y: 10,
	    id: '2Y',
	    fontColor: (current === '2Y') ? "#FFF" : "#777",
	    fontSize: "16",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "2Y"
	  }
	]
};
}
 
function getVolumeConfig(dates, values) {
  return {
  type: 'bar',
  height: 80,
  x: 0, 
  y: 400,
  backgroundColor: "#333",
  plotarea: {
    margin: "20 50 20 50"
  },
  source: {
    text: "Exchange: <?=ucfirst($chart_data['exchange'])?>",
    fontColor:"#ddd",
	 fontSize: "16",
    fontFamily: "Open Sans"
  },
  tooltip:{
    visible: false,
    text: "Volume: $%v",
    fontFamily: "Open Sans",
    borderColor:"transparent",
    "thousands-separator":","
  },
  zoom: {
    shared: true
  },
  crosshairX:{
    shared: true,
    scaleLabel:{
      visible: false
    },
    plotLabel:{
      fontFamily: "Open Sans",
      backgroundColor:"#BBB",
      text: "Volume: $%v",
      y:0,
      "thousands-separator":","
    }
  },
  scaleX: {
    visible: false,
    zooming: true
  },
  scaleY: {
    visible: false
  },
	series : [
		{
			values: values,
			text: "Volume",
			backgroundColor: "#bbb"
		}
	]
};
}
 
zingchart.render({
  id: '<?=strtolower($key)?>_chart',
  data: {
    graphset:[
      getCloseConfig(stockState.dates, stockState.closes, '2Y'),
      getVolumeConfig(stockState.dates, stockState.volumes)
    ]
  },
  height: 500, 
	width: '100%'
});
 
 
zingchart.bind('<?=strtolower($key)?>_chart', 'label_click', function(e){
  if(stockState.current === e.labelid){
    return;
  }
  
  var windowClose = [];
  var windowVolume = [];
  var windowDates = [];
  var cut = 0;
  switch(e.labelid) {
    case '1W': 
      cut = 5;
    break;
    case '1M':
      cut = 20;
    break;
    case '6M': 
      cut = 130;
    break;
    case '1Y': 
      cut = 260;
    break;
    default: 
      cut = stockState.dates.length;
    break;
  }
    windowClose = stockState.closes.slice(stockState.closes.length-cut);
    windowDates = stockState.dates.slice(stockState.dates.length-cut);
    windowVolume = stockState.volumes.slice(stockState.volumes.length-cut);
    
  zingchart.exec('<?=strtolower($key)?>_chart', 'setdata', {
    
    data: {
      graphset:[
        getCloseConfig(windowDates, windowClose, e.labelid),
        getVolumeConfig(windowDates, windowVolume)
      ]
    }
  });
 
  stockState.current = e.labelid;
  
});

<?php
		}
	
	}
	
}
?>

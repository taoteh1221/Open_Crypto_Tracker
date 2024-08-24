<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 

	// Have this script not load any code if asset charts are not turned on
	if ( $ct['conf']['charts_alerts']['enable_price_charts'] != 'on' ) {
	exit;
	}
	
	// Have this script not load any code if $_GET['days'] is invalid
	if ( $_GET['days'] != 'all' && !is_numeric($_GET['days']) ) {
	exit;
	}
	
	
$x_coord = 55; // Start position (absolute) for light chart links
	

	foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $val ) {
		
     $mrkt_parse = array_map( "trim", explode("||", $val) );
		
 
		if ( $_GET['asset_data'] == $mrkt_parse[0] ) {
			
 
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$chart_asset = ( stristr($mrkt_parse[0], "-") == false ? $mrkt_parse[0] : substr( $mrkt_parse[0], 0, mb_strpos($mrkt_parse[0], "-", 0, 'utf-8') ) );
		$chart_asset = strtoupper($chart_asset);


		$charted_val = ( $_GET['charted_val'] == 'pair' ? $mrkt_parse[2] : $ct['default_bitcoin_primary_currency_pair'] );
		
		
		// Strip non-alphanumeric characters to use in js vars, to isolate logic for each separate chart
		$js_key = preg_replace("/-/", "", $mrkt_parse[0]) . '_' . $charted_val;
		

			
			// Unicode symbols for an asset
			// Crypto
			if ( array_key_exists($charted_val, $ct['opt_conf']['crypto_pair']) ) {
			$currency_symb = $ct['opt_conf']['crypto_pair'][$charted_val];
			}
			// Fiat-equiv
			// RUN AFTER CRYPTO MARKETS...WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
			elseif ( array_key_exists($charted_val, $ct['opt_conf']['conversion_currency_symbols']) && !array_key_exists($charted_val, $ct['opt_conf']['crypto_pair']) ) {
			$currency_symb = $ct['opt_conf']['conversion_currency_symbols'][$charted_val];
			$fiat_equiv = 1;
			}
			// Fallback for currency symbol config errors
			else {
			$currency_symb = strtoupper($charted_val) . ' ';
			}

		
			// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
			if ( !file_exists('cache/charts/spot_price_24hr_volume/light/' . $_GET['days'] . '_days/'.$chart_asset.'/'.$mrkt_parse[0].'_chart_'.$charted_val.'.dat') ) {
			?>
			
{

gui: {
    contextMenu: {
      customItems: [
        {
          text: 'PRIVACY ALERT!',
          function: 'zingAlert()',
          id: 'showAlert'
        }
      ],
      alpha: 0.9,
      button: {
        visible: true
      },
      docked: true,
      item: {
        textAlpha: 1
      },
      position: 'left'
    },
    behaviors: [
      {
        id: 'showAlert',
        enabled: 'all'
      }
    ]
},
   type: "area",
   noData: {
     text: "No data for this '<?=$ct['gen']->light_chart_time_period($_GET['days'], 'long')?>' light chart yet, please check back in awhile.",
  	fontColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>",
     backgroundColor: "#808080",
     fontSize: 20,
     textAlpha: .9,
     alpha: .6,
     bold: true
   },
  	backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_background']?>",
  	height: 420,
  	x: 0, 
  	y: 0,
  	title: {
  	  text: "<?=$chart_asset?> / <?=strtoupper($mrkt_parse[2])?> @ <?=$ct['gen']->key_to_name($mrkt_parse[1])?> <?=( $_GET['charted_val'] != 'pair' ? '(' . strtoupper($charted_val) . ' Value)' : '' )?>",
  	  fontColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>",
  	  fontFamily: 'Open Sans',
  	  fontSize: 25,
  	  align: 'right',
  	  offsetX: -18,
  	  offsetY: 4
  	},
   series: [{
     values: []
   }],
	labels: [
	<?php
	foreach ($ct['light_chart_day_intervals'] as $light_chart_days) {
	$light_chart_text = $ct['gen']->light_chart_time_period($light_chart_days, 'short');
	?>
		{
	    x: <?=$x_coord?>,
	    y: 11,
	    id: '<?=$light_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $light_chart_days ? $ct['conf']['charts_alerts']['charts_text'] : $ct['conf']['charts_alerts']['charts_link'] )?>",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$light_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits with absolute positioning
		// Take into account INCREASE OR DECREASE of characters in $light_chart_text
		if ( isset($last_light_chart_text) && strlen($last_light_chart_text) != strlen($light_chart_text) ) {
		$difference = $difference + ( strlen($light_chart_text) - strlen($last_light_chart_text) );  
		$x_coord = $x_coord + ( $difference * $ct['conf']['charts_alerts']['light_chart_link_font_offset'] ); 
		}
	
	$x_coord = $x_coord + $ct['conf']['charts_alerts']['light_chart_link_spacing'];
	$last_light_chart_text = $light_chart_text;
	}
	?>
	]
        
}
			
			<?php
			exit;
			}
			
		
		$chart_data = $ct['gen']->chart_data('cache/charts/spot_price_24hr_volume/light/' . $_GET['days'] . '_days/'.$chart_asset.'/'.$mrkt_parse[0].'_chart_'.$charted_val.'.dat', $mrkt_parse[2]);
		
		
		$price_sample_oldest = $ct['var']->num_to_str( $ct['var']->delimited_str_sample($chart_data['spot'], ',', 'first') );
		
		$price_sample_newest = $ct['var']->num_to_str( $ct['var']->delimited_str_sample($chart_data['spot'], ',', 'last') );
		
		
		     if ( !is_numeric($price_sample_oldest) || !is_numeric($price_sample_newest) ) {
		     $price_sample_avg = 100; // Default (when install is new, and no data available yet)
		     }
		     else {
		     $price_sample_avg = ($price_sample_oldest + $price_sample_newest) / 2;
		     }
		
		
		// Force decimals dynamically
		$thres_dec_target = $ct['gen']->thres_dec($price_sample_avg, 'u', ( $fiat_equiv == 1 ? 'fiat' : 'crypto' ) );		
		$force_dec = 'decimals: ' . $thres_dec_target['max_dec'] . ',';
		

			if ( $chart_asset ) {
?>

{ 


gui: {
    contextMenu: {
      customItems: [
        {
          text: 'PRIVACY ALERT!',
          function: 'zingAlert()',
          id: 'showAlert'
        }
      ],
      alpha: 0.9,
      button: {
        visible: true
      },
      docked: true,
      item: {
        textAlpha: 1
      },
      position: 'left'
    },
    behaviors: [
      {
        id: 'showAlert',
        enabled: 'all'
      }
    ]
},
   
graphset:[
		
		
{
  type: 'area',
  "preview":{
  	  label: {
          color: '<?=$ct['conf']['charts_alerts']['charts_text']?>',
          fontSize: '10px',
          lineWidth: '1px',
          lineColor: '<?=$ct['conf']['charts_alerts']['charts_line']?>',
       },
 	  live: true,
 	  "adjust-layout": true,
 	  "alpha-area": 0.5,
 	  height: 30
  },
  backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_background']?>",
  height: 420,
  x: 0, 
  y: 0,
  globals: {
  	fontSize: 20,
  	fontColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>"
  },
  crosshairX:{
    shared: true,
    exact: true,
    plotLabel:{
      backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_tooltip_background']?>",
      fontColor: "<?=$ct['conf']['charts_alerts']['charts_tooltip_text']?>",
      text: "Spot Price: <?=$currency_symb?>%v",
	 fontSize: "20",
      fontFamily: "Open Sans",
    	"thousands-separator":",",
      <?=$force_dec?>
      y:0,
      "thousands-separator":",",
    },
    scaleLabel:{
    	 alpha: 1.0,
      fontColor: "<?=$ct['conf']['charts_alerts']['charts_tooltip_text']?>",
      fontSize: 20,
      fontFamily: "Open Sans",
      backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_tooltip_background']?>",
    }
  },
  crosshairY:{
    exact: true
  },
  title: {
    text: "<?=$chart_asset?> / <?=strtoupper($mrkt_parse[2])?> @ <?=$ct['gen']->key_to_name($mrkt_parse[1])?> <?=( $_GET['charted_val'] != 'pair' ? '(' . strtoupper($charted_val) . ' Value)' : '' )?>",
    fontColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>",
    fontFamily: 'Open Sans',
    fontSize: 25,
    align: 'right',
    offsetX: -18,
    offsetY: 4
  },
  source: {
    text: "Select area to zoom in chart, or use zoom grab bars in preview area (only horizontal axis zooming supported).",
    fontColor:"<?=$ct['conf']['charts_alerts']['charts_text']?>",
    fontSize: "13",
    fontFamily: "Open Sans",
    offsetX: 110,
    offsetY: -48,
    align: 'left'
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
        visible:false
  },
  scaleY: {
    "format":"<?=$currency_symb?>%v",
    "thousands-separator":",",
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$ct['conf']['charts_alerts']['charts_line']?>"
    },
    item: {
      fontColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "14",
    }
  },
  scaleX: {
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$ct['conf']['charts_alerts']['charts_line']?>"
    },
    values: [<?=$chart_data['time']?>],
    transform: {
 	    type: 'date',
 	    all: '%Y/%m/%d<br />%g:%i%a'
    },
    zooming:{
      shared: true
    },
    item: {
	 fontSize: "14",
      fontColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>",
      fontFamily: "Open Sans"
    }
  },
	series : [
		{
			values: [<?=$chart_data['spot']?>],
			lineColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>",
			lineWidth: 1,
			backgroundColor:"<?=$ct['conf']['charts_alerts']['charts_text']?> <?=$ct['conf']['charts_alerts']['charts_base_gradient']?>", /* background gradient on graphed price area in main chart (NOT the chart background) */
			alpha: 0.5,
			previewState: {
      		   backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_base_gradient']?>" /* background color on graphed price area in preview below chart (NOT the preview area background) */
			}
		}
	],
	labels: [
	<?php
	foreach ($ct['light_chart_day_intervals'] as $light_chart_days) {
	$light_chart_text = $ct['gen']->light_chart_time_period($light_chart_days, 'short');
	?>
		{
	    x: <?=$x_coord?>,
	    y: 11,
	    id: '<?=$light_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $light_chart_days ? $ct['conf']['charts_alerts']['charts_text'] : $ct['conf']['charts_alerts']['charts_link'] )?>",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$light_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits with absolute positioning
		// Take into account INCREASE OR DECREASE of characters in $light_chart_text
		if ( isset($last_light_chart_text) && strlen($last_light_chart_text) != strlen($light_chart_text) ) {
		$difference = $difference + ( strlen($light_chart_text) - strlen($last_light_chart_text) ); 
		$x_coord = $x_coord + ( $difference * $ct['conf']['charts_alerts']['light_chart_link_font_offset'] ); 
		}
	
	$x_coord = $x_coord + $ct['conf']['charts_alerts']['light_chart_link_spacing'];
	$last_light_chart_text = $light_chart_text;
	}
	?>
	]
},
        


{
  type: 'bar',
  height: 75,
  x: 0, 
  y: 400,
  backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_background']?>",
  plotarea: {
    margin: "11 63 20 112"
  },
  plot: {
  	barSpace: "0px",
  	barsSpaceLeft: "0px",
  	barsSpaceRight: "0px"
  },
  source: {
    text: "24 Hour Volume",
    fontColor:"<?=$ct['conf']['charts_alerts']['charts_text']?>",
    fontSize: "13",
    fontFamily: "Open Sans",
    offsetX: 106,
    offsetY: -2,
    align: 'left'
  },
  tooltip:{
    visible: false,
    text: "24 Hour Volume: <?=$currency_symb?>%v",
    fontColor: "<?=$ct['conf']['charts_alerts']['charts_tooltip_text']?>",
    fontSize: "20",
    backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_tooltip_background']?>",
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
      backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_tooltip_background']?>",
      fontColor: "<?=$ct['conf']['charts_alerts']['charts_tooltip_text']?>",
      fontFamily: "Open Sans",
      text: "24 Hour Volume: <?=$currency_symb?>%v",
	 fontSize: "20",
      y:0,
      "thousands-separator":","
    }
  },
  crosshairY:{
    exact: true
  },
  scaleX: {
    visible: false,
    zooming: true
  },
  scaleY: {
    "format":"<?=$currency_symb?>%v",
    "thousands-separator":",",
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$ct['conf']['charts_alerts']['charts_line']?>"
    },
    item: {
      fontColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "12",
    }
  },
	series : [
		{
			values: [<?=$chart_data['volume']?>],
			text: "24hr Volume",
			backgroundColor: "<?=$ct['conf']['charts_alerts']['charts_text']?>",
    		     offsetX: 0
		}
	]
}
        
        
] 


}


			<?php
			
			}

		}
	
	}

?>
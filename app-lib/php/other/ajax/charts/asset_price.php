<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


	// Have this script not load any code if asset charts are not turned on
	if ( $oct_conf['gen']['asset_charts_toggle'] != 'on' ) {
	exit;
	}
	
	// Have this script not load any code if $_GET['days'] is invalid
	if ( $_GET['days'] != 'all' && !is_numeric($_GET['days']) ) {
	exit;
	}
	
	
$x_coord = 120; // Start position (absolute) for lite chart links
	

	foreach ( $oct_conf['charts_alerts']['tracked_markets'] as $key => $val ) {
		
 
		if ( $_GET['asset_data'] == $key ) {
			
 
		// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
		$chart_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
		$chart_asset = strtoupper($chart_asset);
		
		$market_parse = explode("||", $val );


		$charted_val = ( $_GET['charted_val'] == 'pairing' ? $market_parse[1] : $default_btc_prim_currency_pairing );
		
		
		// Strip non-alphanumeric characters to use in js vars, to isolate logic for each separate chart
		$js_key = preg_replace("/-/", "", $key) . '_' . $charted_val;
		

			
			// Unicode asset symbols
			// Crypto
			if ( array_key_exists($charted_val, $oct_conf['power']['crypto_pairing']) ) {
			$currency_symb = $oct_conf['power']['crypto_pairing'][$charted_val];
			}
			// Fiat-equiv
			// RUN AFTER CRYPTO MARKETS...WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
			elseif ( array_key_exists($charted_val, $oct_conf['power']['btc_currency_markets']) && !array_key_exists($charted_val, $oct_conf['power']['crypto_pairing']) ) {
			$currency_symb = $oct_conf['power']['btc_currency_markets'][$charted_val];
			$fiat_equiv = 1;
			}
			// Fallback for currency symbol config errors
			else {
			$currency_symb = strtoupper($charted_val) . ' ';
			}
			
		
			// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
			if ( !file_exists('cache/charts/spot_price_24hr_volume/lite/' . $_GET['days'] . '_days/'.$chart_asset.'/'.$key.'_chart_'.$charted_val.'.dat') ) {
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
     text: "No data for the '<?=ucfirst($_GET['days'])?> day(s)' lite chart yet, please check back in awhile.",
  	  fontColor: "<?=$oct_conf['power']['charts_text']?>",
     backgroundColor: "#808080",
     fontSize: 20,
     textAlpha: .9,
     alpha: .6,
     bold: true
   },
  	backgroundColor: "<?=$oct_conf['power']['charts_background']?>",
  	height: 420,
  	x: 0, 
  	y: 0,
  	title: {
  	  text: "<?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=$oct_gen->key_to_name($market_parse[0])?> <?=( $_GET['charted_val'] != 'pairing' ? '(' . strtoupper($charted_val) . ' Value)' : '' )?>",
  	  fontColor: "<?=$oct_conf['power']['charts_text']?>",
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
	foreach ($oct_conf['power']['lite_chart_day_intervals'] as $lite_chart_days) {
	$lite_chart_text = $oct_gen->light_chart_time_period($lite_chart_days, 'short');
	?>
		{
	    x: <?=$x_coord?>,
	    y: 11,
	    id: '<?=$lite_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $lite_chart_days ? $oct_conf['power']['charts_text'] : $oct_conf['power']['charts_link'] )?>",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$lite_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits with absolute positioning
		// Take into account INCREASE OR DECREASE of characters in $lite_chart_text
		if ( strlen($last_lite_chart_text) > 0 && strlen($last_lite_chart_text) != strlen($lite_chart_text) ) {
		$difference = $difference + ( strlen($lite_chart_text) - strlen($last_lite_chart_text) ); 
		$x_coord = $x_coord + ( $difference * $font_width ); 
		}
		elseif ( isset($difference) ) {
		$x_coord = $x_coord + ( $difference * $font_width ); 
		}
	
	$x_coord = $x_coord + $link_spacer;
	$last_lite_chart_text = $lite_chart_text;
	}
	?>
	]
        
}
			
			<?php
			exit;
			}
			
		
		$chart_data = $oct_gen->chart_data('cache/charts/spot_price_24hr_volume/lite/' . $_GET['days'] . '_days/'.$chart_asset.'/'.$key.'_chart_'.$charted_val.'.dat', $market_parse[1]);
		
		
		$price_sample_oldest = $oct_var->num_to_str( $oct_var->delimited_str_sample($chart_data['spot'], ',', 'first') );
		
		$price_sample_newest = $oct_var->num_to_str( $oct_var->delimited_str_sample($chart_data['spot'], ',', 'last') );
		
		$price_sample_avg = ( $price_sample_oldest + $price_sample_newest ) / 2;
		
		
		$spot_price_dec = ( $fiat_equiv == 1 ? $oct_conf['gen']['prim_currency_dec_max'] : 8 );
		
			
			// Force decimals under certain conditions
			if ( $oct_var->num_to_str($price_sample_avg) >= 1 ) {
			$force_dec = 'decimals: ' . 2 . ',';
			}
			elseif ( $oct_var->num_to_str($price_sample_avg) < 1 ) {
			$force_dec = 'decimals: ' . $spot_price_dec . ',';
			}
		

header('Content-type: text/html; charset=' . $oct_conf['dev']['charset_default']);

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
      color: '<?=$oct_conf['power']['charts_text']?>',
      fontSize: '10px',
      lineWidth: '1px',
      lineColor: '<?=$oct_conf['power']['charts_line']?>',
     	},
 	  live: true,
 	  "adjust-layout": true,
 	  "alpha-area": 0.5,
 	  	height: 30
  },
  backgroundColor: "<?=$oct_conf['power']['charts_background']?>",
  height: 420,
  x: 0, 
  y: 0,
  globals: {
  	fontSize: 20,
  	fontColor: "<?=$oct_conf['power']['charts_text']?>"
  },
  crosshairX:{
    shared: true,
    exact: true,
    plotLabel:{
      backgroundColor: "<?=$oct_conf['power']['charts_tooltip_background']?>",
      fontColor: "<?=$oct_conf['power']['charts_tooltip_text']?>",
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
      fontColor: "<?=$oct_conf['power']['charts_tooltip_text']?>",
      fontSize: 20,
      fontFamily: "Open Sans",
      backgroundColor: "<?=$oct_conf['power']['charts_tooltip_background']?>",
    }
  },
  crosshairY:{
    exact: true
  },
  title: {
    text: "<?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=$oct_gen->key_to_name($market_parse[0])?> <?=( $_GET['charted_val'] != 'pairing' ? '(' . strtoupper($charted_val) . ' Value)' : '' )?>",
    fontColor: "<?=$oct_conf['power']['charts_text']?>",
    fontFamily: 'Open Sans',
    fontSize: 25,
    align: 'right',
    offsetX: -18,
    offsetY: 4
  },
  source: {
    text: "Select area to zoom in chart, or use zoom grab bars in preview area (only horizontal axis zooming supported).",
    fontColor:"<?=$oct_conf['power']['charts_text']?>",
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
      lineColor: "<?=$oct_conf['power']['charts_line']?>"
    },
    item: {
      fontColor: "<?=$oct_conf['power']['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "14",
    }
  },
  scaleX: {
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$oct_conf['power']['charts_line']?>"
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
      fontColor: "<?=$oct_conf['power']['charts_text']?>",
      fontFamily: "Open Sans"
    }
  },
	series : [
		{
			values: [<?=$chart_data['spot']?>],
			lineColor: "<?=$oct_conf['power']['charts_text']?>",
			lineWidth: 1,
			backgroundColor:"<?=$oct_conf['power']['charts_text']?> <?=$oct_conf['power']['charts_price_gradient']?>", /* background gradient on graphed price area in main chart (NOT the chart background) */
			alpha: 0.5,
				previewState: {
      		backgroundColor: "<?=$oct_conf['power']['charts_price_gradient']?>" /* background color on graphed price area in preview below chart (NOT the preview area background) */
				}
		}
	],
	labels: [
	<?php
	foreach ($oct_conf['power']['lite_chart_day_intervals'] as $lite_chart_days) {
	$lite_chart_text = $oct_gen->light_chart_time_period($lite_chart_days, 'short');
	?>
		{
	    x: <?=$x_coord?>,
	    y: 11,
	    id: '<?=$lite_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $lite_chart_days ? $oct_conf['power']['charts_text'] : $oct_conf['power']['charts_link'] )?>",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$lite_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits with absolute positioning
		// Take into account INCREASE OR DECREASE of characters in $lite_chart_text
		if ( strlen($last_lite_chart_text) > 0 && strlen($last_lite_chart_text) != strlen($lite_chart_text) ) {
		$difference = $difference + ( strlen($lite_chart_text) - strlen($last_lite_chart_text) ); 
		$x_coord = $x_coord + ( $difference * $font_width ); 
		}
		elseif ( isset($difference) ) {
		$x_coord = $x_coord + ( $difference * $font_width ); 
		}
	
	$x_coord = $x_coord + $link_spacer;
	$last_lite_chart_text = $lite_chart_text;
	}
	?>
	]
},
        


{
  type: 'bar',
  height: 75,
  x: 0, 
  y: 400,
  backgroundColor: "<?=$oct_conf['power']['charts_background']?>",
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
    fontColor:"<?=$oct_conf['power']['charts_text']?>",
	 fontSize: "13",
    fontFamily: "Open Sans",
    offsetX: 106,
    offsetY: -2,
    align: 'left'
  },
  tooltip:{
    visible: false,
    text: "24 Hour Volume: <?=$currency_symb?>%v",
    fontColor: "<?=$oct_conf['power']['charts_tooltip_text']?>",
	 fontSize: "20",
    backgroundColor: "<?=$oct_conf['power']['charts_tooltip_background']?>",
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
      backgroundColor: "<?=$oct_conf['power']['charts_tooltip_background']?>",
      fontColor: "<?=$oct_conf['power']['charts_tooltip_text']?>",
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
      lineColor: "<?=$oct_conf['power']['charts_line']?>"
    },
    item: {
      fontColor: "<?=$oct_conf['power']['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "12",
    }
  },
	series : [
		{
			values: [<?=$chart_data['volume']?>],
			text: "24hr Volume",
			backgroundColor: "<?=$oct_conf['power']['charts_text']?>",
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
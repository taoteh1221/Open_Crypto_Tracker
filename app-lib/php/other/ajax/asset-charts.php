<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

// Market charts
	
	// Have this script not load any code if asset charts are not turned on
	if ( $app_config['general']['charts_toggle'] != 'on' ) {
	exit;
	}
	
	// Have this script not load any code if $_GET['days'] is invalid
	if ( $_GET['days'] != 'all' && !is_numeric($_GET['days']) ) {
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
		

			
			// Unicode asset symbols
			// Crypto
			if ( array_key_exists($charted_value, $app_config['power_user']['crypto_pairing']) ) {
			$currency_symbol = $app_config['power_user']['crypto_pairing'][$charted_value];
			}
			// Fiat-equiv
			// RUN AFTER CRYPTO MARKETS...WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
			elseif ( array_key_exists($charted_value, $app_config['power_user']['bitcoin_currency_markets']) && !array_key_exists($charted_value, $app_config['power_user']['crypto_pairing']) ) {
			$currency_symbol = $app_config['power_user']['bitcoin_currency_markets'][$charted_value];
			$fiat_equiv = 1;
			}
			// Fallback for currency symbol config errors
			else {
			$currency_symbol = strtoupper($charted_value) . ' ';
			}
			
		
			// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
			if ( file_exists('cache/charts/spot_price_24hr_volume/lite/' . $_GET['days'] . '_days/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat') != 1
			|| $market_parse[2] != 'chart' && $market_parse[2] != 'both' ) {
			?>
			
			{"error": "no data"}
			
			<?php
			exit;
			}
			
		
		$chart_data = chart_data('cache/charts/spot_price_24hr_volume/lite/' . $_GET['days'] . '_days/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat', $market_parse[1]);
		
		
		$price_sample_oldest = number_to_string( delimited_string_sample($chart_data['spot'], ',', 'first') );
		
		$price_sample_newest = number_to_string( delimited_string_sample($chart_data['spot'], ',', 'last') );
		
		$price_sample_average = ( $price_sample_oldest + $price_sample_newest ) / 2;
		
		
		$spot_price_decimals = ( $fiat_equiv == 1 ? $app_config['general']['primary_currency_decimals_max'] : 8 );
		
			
			// Force decimals under certain conditions
			if ( number_to_string($price_sample_average) >= $app_config['general']['primary_currency_decimals_max_threshold'] ) {
			$force_decimals = 'decimals: ' . 2 . ',';
			}
			elseif ( number_to_string($price_sample_average) < $app_config['general']['primary_currency_decimals_max_threshold'] ) {
			$force_decimals = 'decimals: ' . $spot_price_decimals . ',';
			}
		

header('Content-type: text/html; charset=' . $app_config['developer']['charset_default']);

			if ( $chart_asset ) {
?>

{ graphset:[
		
		
{
  type: 'area',
  "preview":{
  		label: {
      color: '<?=$app_config['power_user']['charts_text']?>',
      fontSize: '10px',
      lineWidth: '1px',
      lineColor: '<?=$app_config['power_user']['charts_line']?>',
     	},
 	  live: true,
 	  "adjust-layout": true,
 	  "alpha-area": 0.5,
 	  	height: 30
  },
  backgroundColor: "<?=$app_config['power_user']['charts_background']?>",
  height: 420,
  x: 0, 
  y: 0,
  globals: {
  	fontSize: 20,
  	fontColor: "<?=$app_config['power_user']['charts_text']?>"
  },
  crosshairX:{
    shared: true,
    exact: true,
    plotLabel:{
      backgroundColor: "<?=$app_config['power_user']['charts_tooltip_background']?>",
      fontColor: "<?=$app_config['power_user']['charts_tooltip_text']?>",
      text: "Spot Price: <?=$currency_symbol?>%v",
	 	fontSize: "20",
      fontFamily: "Open Sans",
      <?=$force_decimals?>
      
      y:0,
      "thousands-separator":",",
    },
    scaleLabel:{
    	alpha: 1.0,
      fontColor: "<?=$app_config['power_user']['charts_tooltip_text']?>",
      fontSize: 20,
      fontFamily: "Open Sans",
      backgroundColor: "<?=$app_config['power_user']['charts_tooltip_background']?>",
    }
  },
  crosshairY:{
    exact: true
  },
  title: {
    text: "<?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=snake_case_to_name($market_parse[0])?> <?=( $_GET['charted_value'] != 'pairing' ? '(' . strtoupper($charted_value) . ' Value)' : '' )?>",
    fontColor: "<?=$app_config['power_user']['charts_text']?>",
    fontFamily: 'Open Sans',
    fontSize: 25,
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
    fontColor: "<?=$app_config['power_user']['charts_tooltip_text']?>",
	 fontSize: "20",
    backgroundColor: "<?=$app_config['power_user']['charts_tooltip_background']?>",
    "thousands-separator":","
  },
  scaleY: {
    "format":"<?=$currency_symbol?>%v",
    "thousands-separator":",",
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$app_config['power_user']['charts_line']?>"
    },
    item: {
      fontColor: "<?=$app_config['power_user']['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "14",
    }
  },
  scaleX: {
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$app_config['power_user']['charts_line']?>"
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
      fontColor: "<?=$app_config['power_user']['charts_text']?>",
      fontFamily: "Open Sans"
    }
  },
	series : [
		{
			values: [<?=$chart_data['spot']?>],
			lineColor: "<?=$app_config['power_user']['charts_text']?>",
			lineWidth: 1,
			backgroundColor:"<?=$app_config['power_user']['charts_text']?> <?=$app_config['power_user']['charts_price_gradient']?>", /* background gradient on graphed price area in main chart (NOT the chart background) */
			alpha: 0.5,
				previewState: {
      		backgroundColor: "<?=$app_config['power_user']['charts_price_gradient']?>" /* background color on graphed price area in preview below chart (NOT the preview area background) */
				}
		}
	],
	labels: [
	<?php
	$x_coord = 120; // Start position (absolute)
	$font_width = 17; // NOT MONOSPACE, SO WE GUESS AN AVERAGE
	foreach ($app_config['power_user']['lite_chart_day_intervals'] as $lite_chart_days) {
		
		if ( $lite_chart_days == 'all' ) {
		$lite_chart_text = strtoupper($lite_chart_days);
		}
		elseif ( $lite_chart_days == 7 ) {
		$lite_chart_text = '1W';
		}
		elseif ( $lite_chart_days == 30 ) {
		$lite_chart_text = '1M';
		}
		elseif ( $lite_chart_days == 60 ) {
		$lite_chart_text = '2M';
		}
		elseif ( $lite_chart_days == 90 ) {
		$lite_chart_text = '3M';
		}
		elseif ( $lite_chart_days == 180 ) {
		$lite_chart_text = '6M';
		}
		elseif ( $lite_chart_days == 365 ) {
		$lite_chart_text = '1Y';
		}
		elseif ( $lite_chart_days == 730 ) {
		$lite_chart_text = '2Y';
		}
		elseif ( $lite_chart_days == 1095 ) {
		$lite_chart_text = '3Y';
		}
		elseif ( $lite_chart_days == 1460 ) {
		$lite_chart_text = '4Y';
		}
		else {
		$lite_chart_text = $lite_chart_days . 'D';
		}
	?>
		{
	    x: <?=$x_coord?>,
	    y: 11,
	    id: '<?=$lite_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $lite_chart_days ? $app_config['power_user']['charts_text'] : $app_config['power_user']['charts_link'] )?>",
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
	
	$x_coord = $x_coord + 70;
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
  backgroundColor: "<?=$app_config['power_user']['charts_background']?>",
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
    fontColor:"<?=$app_config['power_user']['charts_text']?>",
	 fontSize: "13",
    fontFamily: "Open Sans",
    offsetX: 106,
    offsetY: -2,
    align: 'left'
  },
  tooltip:{
    visible: false,
    text: "24 Hour Volume: <?=$currency_symbol?>%v",
    fontColor: "<?=$app_config['power_user']['charts_tooltip_text']?>",
	 fontSize: "20",
    backgroundColor: "<?=$app_config['power_user']['charts_tooltip_background']?>",
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
      backgroundColor: "<?=$app_config['power_user']['charts_tooltip_background']?>",
      fontColor: "<?=$app_config['power_user']['charts_tooltip_text']?>",
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
      lineColor: "<?=$app_config['power_user']['charts_line']?>"
    },
    item: {
      fontColor: "<?=$app_config['power_user']['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "12",
    }
  },
	series : [
		{
			values: [<?=$chart_data['volume']?>],
			text: "24hr Volume",
			backgroundColor: "<?=$app_config['power_user']['charts_text']?>",
    		offsetX: 0
		}
	]
}
        
        
] }


			<?php
			}

		}
	
	}


?>
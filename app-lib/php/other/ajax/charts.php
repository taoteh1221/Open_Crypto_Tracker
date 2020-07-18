<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


// Charts library

$font_width = 9; // NOT MONOSPACE, SO WE GUESS AN AVERAGE
$link_spacer = 75; // Space beetween lite chart links


// ASSET CHARTS START
if ( $_GET['type'] == 'asset' ) {

require_once('app-lib/php/other/primary-bitcoin-markets.php');	
	
	// Have this script not load any code if asset charts are not turned on
	if ( $app_config['general']['asset_charts_toggle'] != 'on' ) {
	exit;
	}
	
	// Have this script not load any code if $_GET['days'] is invalid
	if ( $_GET['days'] != 'all' && !is_numeric($_GET['days']) ) {
	exit;
	}
	
	
$x_coord = 120; // Start position (absolute) for lite chart links
	

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
			if ( !file_exists('cache/charts/spot_price_24hr_volume/lite/' . $_GET['days'] . '_days/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat') ) {
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
     text: "No data for the <?=$_GET['days']?> day chart yet, please check back in awhile.",
  	  fontColor: "<?=$app_config['power_user']['charts_text']?>",
     backgroundColor: "#808080",
     fontSize: 20,
     textAlpha: .9,
     alpha: .6,
     bold: true
   },
  	backgroundColor: "<?=$app_config['power_user']['charts_background']?>",
  	height: 420,
  	x: 0, 
  	y: 0,
  	title: {
  	  text: "<?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=snake_case_to_name($market_parse[0])?> <?=( $_GET['charted_value'] != 'pairing' ? '(' . strtoupper($charted_value) . ' Value)' : '' )?>",
  	  fontColor: "<?=$app_config['power_user']['charts_text']?>",
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
	foreach ($app_config['power_user']['lite_chart_day_intervals'] as $lite_chart_days) {
		
		if ( $lite_chart_days == 'all' ) {
		$lite_chart_text = strtoupper($lite_chart_days);
		}
		elseif ( $lite_chart_days == 7 ) {
		$lite_chart_text = '1W';
		}
		elseif ( $lite_chart_days == 14 ) {
		$lite_chart_text = '2W';
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
	
	$x_coord = $x_coord + $link_spacer;
	$last_lite_chart_text = $lite_chart_text;
	}
	?>
	]
        
}
			
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
	foreach ($app_config['power_user']['lite_chart_day_intervals'] as $lite_chart_days) {
		
		if ( $lite_chart_days == 'all' ) {
		$lite_chart_text = strtoupper($lite_chart_days);
		}
		elseif ( $lite_chart_days == 7 ) {
		$lite_chart_text = '1W';
		}
		elseif ( $lite_chart_days == 14 ) {
		$lite_chart_text = '2W';
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
        
        
] 


}


			<?php
			}

		}
	
	}

}
// ASSET CHARTS END



// SYSTEM CHARTS START
elseif ( $_GET['type'] == 'system' ) {
	
	// Have this script not load any code if system stats are not turned on, or key GET request corrupt
	if ( !isset($_SESSION['admin_logged_in']) || !is_numeric($_GET['key']) ) {
	exit;
	}

$key = $_GET['key'];

$x_coord = 70; // Start position (absolute) for lite chart links
			
		
// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
if ( !file_exists('cache/charts/system/lite/' . $_GET['days'] . '_days/system_stats.dat') ) {
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
     text: "No data for the <?=$_GET['days']?> day chart yet, please check back in awhile.",
  	  fontColor: "black",
     backgroundColor: "#808080",
     fontSize: 20,
     textAlpha: .9,
     alpha: .6,
     bold: true
   },
  	backgroundColor: "#f2f2f2",
  	height: 420,
  	x: 0, 
  	y: 0,
  	title: {
        text: 'System Chart #<?=$key?>',
        adjustLayout: true,
    	  align: 'right',
    	  offsetX: -20,
    	  offsetY: 9
  	},
   series: [{
     values: []
   }],
	labels: [
	<?php
	foreach ($app_config['power_user']['lite_chart_day_intervals'] as $lite_chart_days) {
		
		if ( $lite_chart_days == 'all' ) {
		$lite_chart_text = strtoupper($lite_chart_days);
		}
		elseif ( $lite_chart_days == 7 ) {
		$lite_chart_text = '1W';
		}
		elseif ( $lite_chart_days == 14 ) {
		$lite_chart_text = '2W';
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
	    y: 12,
	    id: '<?=$lite_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $lite_chart_days ? 'black' : '#9b9b9b' )?>",
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
			
		
$chart_data = chart_data('cache/charts/system/lite/' . $_GET['days'] . '_days/system_stats.dat', 'system');

// Colors for different data in charts
$color_array = array(
							'blank',
							'#29A2CC',
							'#209910',
							'#1d4ba5',
							'#48ad9e',
							'#D31E1E',
							'#a73aad',
							'#bc5210',
							);



// Determine how many data sensors to include in first chart
$num_in_first_chart = 0;
foreach ( $chart_data as $chart_key => $chart_value ) {

// Average for first / last value
//$check_chart_value = number_to_string( delimited_string_sample($chart_value, ',', 'first') + delimited_string_sample($chart_value, ',', 'last') / 2 );
// Just last value
$check_chart_value = number_to_string( delimited_string_sample($chart_value, ',', 'last') );
	
	// Include load average no matter what (it can be zero on a low-load setup, and should be supported by nearly every linux system?)
	// Also always include free disk space (WE WANT TO KNOW IF IT'S ZERO)
	if ( $chart_key != 'time' && $check_chart_value != 'NO_DATA' && $check_chart_value > 0.000000 || $chart_key == 'load_average_15_minutes' || $chart_key == 'free_disk_space_terabtyes' ) {
		
	$check_chart_value_key = $check_chart_value * 100000000; // To RELIABLY sort integers AND decimals, via ksort()
		
	$sorted_by_last_chart_data[number_to_string($check_chart_value_key)] = array($chart_key => $chart_value);
	
		if ( number_to_string($check_chart_value) <= number_to_string($app_config['power_user']['system_stats_first_chart_highest_value']) ) {
		$num_in_first_chart = $num_in_first_chart + 1;
		//echo $check_chart_value . ' --- '; // DEBUGGING ONLY
		}
	
	}
	
}


// Sort array keys by lowest numeric value to highest 
// (newest/last chart sensors data sorts lowest value to highest, for populating the 2 shared charts)
ksort($sorted_by_last_chart_data);

//var_dump($sorted_by_last_chart_data); // DEBUGGING ONLY

// Render chart data
if ( $key == 1 ) {
	
	$loop = 1;
	$counted = 0;
	foreach ( $sorted_by_last_chart_data as $chart_array ) {
		
		foreach ( $chart_array as $chart_key => $chart_value ) {
		
			if ( $counted < $num_in_first_chart && $chart_key != 'time' ) {
			$counted = $counted + 1;
			
				// If there are no data retrieval errors
				// WE STILL COUNT THIS, SO LET COUNT RUN ABOVE
				if ( !preg_match("/NO_DATA/i", $chart_value, $matches) ) {
					
				$chart_config = "{
			  text: '".snake_case_to_name($chart_key)."',
			  values: [".$chart_value."],
			  lineColor: '".$color_array[$counted]."',
				 marker: {
			 backgroundColor: '".$color_array[$counted]."',
			 borderColor: '".$color_array[$counted]."'
				 },
			  legendItem: {
				  fontColor: 'white',
			   fontSize: 20,
			   fontFamily: 'Open Sans',
				backgroundColor: '".$color_array[$counted]."',
				borderRadius: '2px'
			  }
			},
			" . $chart_config;
			
				}
			
			}
	
		}
		
   $loop = $loop + 1;
	}

}
elseif ( $key == 2 ) {
	
	$loop = 1;
	$counted = 0;
	foreach ( $sorted_by_last_chart_data as $chart_array ) {
		
		foreach ( $chart_array as $chart_key => $chart_value ) {
		
			if ( $counted >= $num_in_first_chart && $chart_key != 'time' ) {
			$counted = $counted + 1;
			
				// If there are no data retrieval errors
				// WE STILL COUNT THIS, SO LET COUNT RUN ABOVE
				if ( !preg_match("/NO_DATA/i", $chart_value, $matches) ) {
					
			$chart_config = "{
			  text: '".snake_case_to_name($chart_key)."',
			  values: [".$chart_value."],
			  lineColor: '".$color_array[$counted]."',
				 marker: {
			 backgroundColor: '".$color_array[$counted]."',
			 borderColor: '".$color_array[$counted]."'
				 },
			  legendItem: {
				  fontColor: 'white',
			   fontSize: 20,
			   fontFamily: 'Open Sans',
				backgroundColor: '".$color_array[$counted]."',
				borderRadius: '2px'
			  }
			},
			" . $chart_config;
				
				}
		  
			}
			elseif ( $chart_key != 'time' ) {
			$counted = $counted + 1;
			}
	
		}
		
   $loop = $loop + 1;
	}

}

$chart_config = trim($chart_config);
$chart_config = rtrim($chart_config,',');

header('Content-type: text/html; charset=' . $app_config['developer']['charset_default']);

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
   
   graphset: [
    {
      type: 'line',
      borderColor: '#cccccc',
      borderRadius: '2px',
      borderWidth: '1px',
      title: {
        text: 'System Chart #<?=$key?>',
        adjustLayout: true,
    	  align: 'right',
    	  offsetX: -20,
    	  offsetY: 9
      },  
  		source: {
  		   text: "Select an area to zoom inside the chart itself, or use the zoom grab bars in the preview area (X and Y axis zooming are both supported).",
    		fontColor:"black",
	      fontSize: "13",
    		fontFamily: "Open Sans",
    		offsetX: 60,
    		offsetY: -2,
    		align: 'left'
  		},
      legend: {
        backgroundColor: 'transparent',
        borderWidth: '0px',
    	  offsetX: -40,
    	  offsetY: -20,
        draggable: false,
        header: {
          text: 'Telemetry (click to hide)',
    		 offsetX: -8,
    	    offsetY: -20,
      	 fontColor: "black",
	 		 fontSize: "20",
      	 fontFamily: "Open Sans",
        },
        item: {
          margin: '5 17 2 0',
          padding: '3 3 3 3',
          cursor: 'hand',
          fontColor: '#fff'
        },
        marker: {
          visible: false
        },
        verticalAlign: 'middle'
      },
      plot: {
    		marker:{
      		visible: false
    		},
    		tooltip: {
    			fontSize: 20
    		}
      },
      plotarea: {
        margin: 'dynamic'
      },
      scaleX: {
        guide: {
      	visible: true,
     		lineStyle: 'solid',
      	lineColor: "#444444"
        },
        values: [<?=$chart_data['time']?>],
        transform: {
 	     type: 'date',
 	     all: '%Y/%m/%d<br />%g:%i%a'
        },
        zooming: true
      },
      scaleY: {
        guide: {
      	visible: true,
     		lineStyle: 'solid',
      	lineColor: "#444444"
        },
        label: {
          text: 'Telemetry'
        },
    	zooming: true
      },
      crosshairX: {
    	  exact: true,
        lineColor: '#555',
        marker: {
          borderColor: '#fff',
          borderWidth: '1px',
          size: '5px'
        },
        plotLabel: {
      	 backgroundColor: "white",
      	 fontColor: "black",
	 		 fontSize: "20",
      	 fontFamily: "Open Sans",
          borderRadius: '2px',
          borderWidth: '2px',
          multiple: true
        },
    	  scaleLabel:{
   	  	 alpha: 1.0,
    	    fontColor: "black",
      	 fontSize: 20,
      	 fontFamily: "Open Sans",
      	 backgroundColor: "white",
   	  }
      },
      crosshairY: {
    	  exact: true
      },
      tooltip: {
        visible: false
      },
  		"preview":{
  				label: {
   		   color: 'black',
  		    	fontSize: '10px',
  		    	lineWidth: '1px',
   		   lineColor: '#444444',
  		   	},
 			  live: true,
 			  "adjust-layout": true,
 			  "alpha-area": 0.5
 		},
  		backgroundColor: "#f2f2f2",
      series: [
        <?php echo $chart_config . "\n" ?>
      ],
		labels: [
	<?php
	foreach ($app_config['power_user']['lite_chart_day_intervals'] as $lite_chart_days) {
		
		if ( $lite_chart_days == 'all' ) {
		$lite_chart_text = strtoupper($lite_chart_days);
		}
		elseif ( $lite_chart_days == 7 ) {
		$lite_chart_text = '1W';
		}
		elseif ( $lite_chart_days == 14 ) {
		$lite_chart_text = '2W';
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
	    y: 12,
	    id: '<?=$lite_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $lite_chart_days ? 'black' : '#9b9b9b' )?>",
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
  ]
  
}

<?php


}
// SYSTEM CHARTS END



// BALANCE CHARTS START
elseif ( $_GET['type'] == 'balance_stats' ) {
?>

 <h5 class="align_center yellow" style="position: relative; white-space: nowrap;">Portfolio Balance Stats:</h5>
 
  <div id='balance_chart' class='chart_wrapper' style='min-width: 700px; background: white; border: 2px solid #918e8e;'></div>
  
  <script>
    var balance_chart_config = {
    "gui": {
    	"behaviors": [
    	],
    	"contextMenu": {
      	"customItems": [
        	{
          	"text": 'PRIVACY ALERT!',
          	"function": 'zingAlert()',
          	"id": 'showAlert'
        	}
      	],
    	  "alpha": 0.9,
    	  "button": {
     	   "visible": true
     	 },
     	 "docked": true,
     	 "item": {
     	   "textAlpha": 1
     	 },
      	"position": 'left'
    	},
    	"behaviors": [
     	 {
        	"id": 'showAlert',
        	"enabled": 'all'
      	}
    	]
	},
   "type": "pie",
  		backgroundColor: "white",
  		width: 700,
      "plot": {
        "tooltip": {
          "text": "%t (%npv%)",
          decimals: 2,
          "font-color": "black",
          "font-size": 22,
          "text-alpha": 1,
          "background-color": "white",
          "alpha": 0.7,
          "border-width": 1,
          "border-color": "#cccccc",
          "line-style": "dotted",
          "border-radius": "10px",
          "margin": "0%",
          "padding": "5px",
          "placement": "node:out" //"node:out" or "node:center"
        },
        "value-box": {
          "text": "%t (%npv%)",
          decimals: 2,
      	 'font-size':14,
      	 'font-weight': "normal",
      	 placement: "out"
        },
        "border-width": 1,
        "border-color": "#cccccc",
        "line-style": "dotted"
      },
      "plotarea": {
        "margin": "0%",
        "padding": "0%"
      },
      "series": [
      <?php
      foreach ( $_GET as $asset_key => $asset_value ) {
      	
      	if ( stristr($asset_key, 'MISC__') != false ) {
      	$asset_key = strtolower($asset_key);
      	$misc_array = explode("__", $asset_key);
      	$asset_key = $misc_array[1];
      	}
      
      	if ( $asset_key != 'type' && $asset_key != 'leverage_added' && $asset_key != 'short_added' && $asset_value >= 0.01 ) {
      ?>
        {
          "values": [<?=strtoupper($asset_value)?>],
          "text": "<?=strtoupper($asset_key)?>"
        },
      <?php
      	}
      	
      }
      ?>
      ]
    };
 
    zingchart.render({
      id: 'balance_chart',
      data: balance_chart_config,
      height: 400,
      width: "100%"
    });
  </script>
  
  
  <p class="coin_info"><span class="yellow"> &nbsp; </p>
  
			<?php
					
					// Sort by most dominant first
					arsort($_GET);
					
				foreach ( $_GET as $key => $value ) {
					
      			if ( stristr($key, 'MISC__') != false ) {
      			$key = strtolower($key);
      			$misc_array = explode("__", $key);
      			$key = $misc_array[1];
      			}
      			
      
						if ( $key != 'type' && $key != 'leverage_added' && $key != 'short_added' && $value >= 0.01 ) {
				?>
			<p class="coin_info"><span class="yellow"><?=strtoupper($key)?>:</span> <?=$value?>%</p>
			
			<?php
						}
							
				}
			 ?>
  
  <p class="coin_info balloon_notation"><span class="yellow"><?=( $_GET['leverage_added'] == 1 ? '*Does <u>not</u> adjust for any type of leverage' : '' )?><?=(  $_GET['short_added'] == 1 ? ', or short deposit(s) gain / loss' : '' )?><?=( $_GET['leverage_added'] == 1 ? '.' : '' )?></span></p>
  
  <p class="coin_info balloon_notation"><span class="yellow">*All decimals are rounded to 2 places, and therefore may be slightly off up to 0.005%.</span></p>
  
  <p class="coin_info"><span class="yellow"> &nbsp; </p>
			 
<?php
}
// BALANCE CHARTS END


flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

?>
<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
// Runtime mode
$runtime_mode = 'json';

// FOR SPEED, $runtime_mode 'logs' only gets app config vars, some init.php, then EXITS in the logs library
require("config.php");

	
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
			if ( file_exists('cache/charts/spot_price_24hr_volume/lite/' . $_GET['days'] . '_day/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat') != 1
			|| $market_parse[2] != 'chart' && $market_parse[2] != 'both' ) {
			?>
			
			{"error": "no data"}
			
			<?php
			exit;
			}
			
		
		$chart_data = chart_data('cache/charts/spot_price_24hr_volume/lite/' . $_GET['days'] . '_day/'.$chart_asset.'/'.$key.'_chart_'.$charted_value.'.dat', $market_parse[1]);
		
		
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
      color: '<?=$app_config['charts_alerts']['charts_text']?>',
      fontSize: '10px',
      lineWidth: '1px',
      lineColor: '<?=$app_config['charts_alerts']['charts_line']?>',
     	},
 	  live: true,
 	  "adjust-layout": true,
 	  "alpha-area": 0.5,
 	  	height: 30
  },
  backgroundColor: "<?=$app_config['charts_alerts']['charts_background']?>",
  height: 420,
  x: 0, 
  y: 0,
  globals: {
  	fontSize: 20,
  	fontColor: "<?=$app_config['charts_alerts']['charts_text']?>"
  },
  crosshairX:{
    shared: true,
    exact: true,
    plotLabel:{
      backgroundColor: "<?=$app_config['charts_alerts']['charts_tooltip_background']?>",
      fontColor: "<?=$app_config['charts_alerts']['charts_tooltip_text']?>",
      text: "Spot Price: <?=$currency_symbol?>%v",
	 	fontSize: "20",
      fontFamily: "Open Sans",
      <?=$force_decimals?>
      
      y:0,
      "thousands-separator":",",
    },
    scaleLabel:{
    	alpha: 1.0,
      fontColor: "<?=$app_config['charts_alerts']['charts_tooltip_text']?>",
      fontSize: 20,
      fontFamily: "Open Sans",
      backgroundColor: "<?=$app_config['charts_alerts']['charts_tooltip_background']?>",
    }
  },
  crosshairY:{
    exact: true
  },
  title: {
    text: "<?=$chart_asset?> / <?=strtoupper($market_parse[1])?> @ <?=snake_case_to_name($market_parse[0])?> <?=( $_GET['charted_value'] != 'pairing' ? '(' . strtoupper($charted_value) . ' Value)' : '' )?>",
    fontColor: "<?=$app_config['charts_alerts']['charts_text']?>",
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
    fontColor: "<?=$app_config['charts_alerts']['charts_tooltip_text']?>",
	 fontSize: "20",
    backgroundColor: "<?=$app_config['charts_alerts']['charts_tooltip_background']?>",
    "thousands-separator":","
  },
  scaleY: {
    "format":"<?=$currency_symbol?>%v",
    "thousands-separator":",",
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$app_config['charts_alerts']['charts_line']?>"
    },
    item: {
      fontColor: "<?=$app_config['charts_alerts']['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "14",
    }
  },
  scaleX: {
    guide: {
      visible: true,
      lineStyle: 'solid',
      lineColor: "<?=$app_config['charts_alerts']['charts_line']?>"
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
      fontColor: "<?=$app_config['charts_alerts']['charts_text']?>",
      fontFamily: "Open Sans"
    }
  },
	series : [
		{
			values: [<?=$chart_data['spot']?>],
			lineColor: "<?=$app_config['charts_alerts']['charts_text']?>",
			lineWidth: 1,
			backgroundColor:"<?=$app_config['charts_alerts']['charts_text']?> <?=$app_config['charts_alerts']['charts_price_gradient']?>", /* background gradient on graphed price area in main chart (NOT the chart background) */
			alpha: 0.5,
				previewState: {
      		backgroundColor: "<?=$app_config['charts_alerts']['charts_price_gradient']?>" /* background color on graphed price area in preview below chart (NOT the preview area background) */
				}
		}
	],
	labels: [
	  {
	    x: 80,
	    y: 10,
	    id: '3D',
	    fontColor: "<?=($_GET['days'] == '3' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "3D"
	  },
	  {
	    x: 130,
	    y: 10,
	    id: '1W',
	    fontColor: "<?=($_GET['days'] == '7' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1W"
	  },
	  {
	    x: 180,
	    y: 10,
	    id: '1M',
	    fontColor: "<?=($_GET['days'] == '30' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1M"
	  },
	  {
	    x: 230,
	    y: 10,
	    id: '3M',
	    fontColor: "<?=($_GET['days'] == '90' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "3M"
	  },
	  {
	    x: 280,
	    y: 10,
	    id: '6M',
	    fontColor: "<?=($_GET['days'] == '180' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "6M"
	  },
	  {
	    x: 330,
	    y: 10,
	    id: '1Y',
	    fontColor: "<?=($_GET['days'] == '365' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "1Y"
	  },
	  {
	    x: 380,
	    y: 10,
	    id: '2Y',
	    fontColor: "<?=($_GET['days'] == '730' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "2Y"
	  },
	  {
	    x: 430,
	    y: 10,
	    id: '4Y',
	    fontColor: "<?=($_GET['days'] == '1460' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "4Y"
	  },
	  {
	    x: 480,
	    y: 10,
	    id: 'ALL',
	    fontColor: "<?=($_GET['days'] == 'all' ? $app_config['charts_alerts']['charts_text'] : $app_config['charts_alerts']['charts_link'] )?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "ALL"
	  },
	  {
	    x: 547,
	    y: 10,
	    id: 'RESET',
	    fontColor: "<?=$app_config['charts_alerts']['charts_link']?>",
	    fontSize: "21",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "RESET"
	  }
	]
},
        


{
  type: 'bar',
  height: 75,
  x: 0, 
  y: 400,
  backgroundColor: "<?=$app_config['charts_alerts']['charts_background']?>",
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
    fontColor:"<?=$app_config['charts_alerts']['charts_text']?>",
	 fontSize: "13",
    fontFamily: "Open Sans",
    offsetX: 106,
    offsetY: -2,
    align: 'left'
  },
  tooltip:{
    visible: false,
    text: "24 Hour Volume: <?=$currency_symbol?>%v",
    fontColor: "<?=$app_config['charts_alerts']['charts_tooltip_text']?>",
	 fontSize: "20",
    backgroundColor: "<?=$app_config['charts_alerts']['charts_tooltip_background']?>",
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
      backgroundColor: "<?=$app_config['charts_alerts']['charts_tooltip_background']?>",
      fontColor: "<?=$app_config['charts_alerts']['charts_tooltip_text']?>",
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
      lineColor: "<?=$app_config['charts_alerts']['charts_line']?>"
    },
    item: {
      fontColor: "<?=$app_config['charts_alerts']['charts_text']?>",
      fontFamily: "Open Sans",
      fontSize: "12",
    }
  },
	series : [
		{
			values: [<?=$chart_data['volume']?>],
			text: "24hr Volume",
			backgroundColor: "<?=$app_config['charts_alerts']['charts_text']?>",
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
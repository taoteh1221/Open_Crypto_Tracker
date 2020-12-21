<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

$analyzed_assets = array();

foreach ( $app_config['charts_alerts']['tracked_markets'] as $key => $value ) {

$asset = preg_replace("/-(.*)/i", "", $key);

$attributes = explode("||", $value);

	if ( !array_key_exists($asset, $analyzed_assets) ) {
	
		if ( $attributes[2] == 'chart' || $attributes[2] == 'both' ) {
			
		$analyzed_assets[$asset] = $key;
		
		$chart_file = $base_dir . '/cache/charts/spot_price_24hr_volume/lite/' . $_GET['time_period'] . '_days/'.strtoupper($asset).'/'.$key.'_chart_'.$default_btc_primary_currency_pairing.'.dat';
						
			if ( file_exists($chart_file) ) {
			$runtime_data['performance_stats'][strtoupper($asset)]['data'] = chart_data($chart_file, 'performance', $_GET['start_time']); // NO EARLIER THAN A CERTAIN TIMESTAMP
			}
		
		}
							
	}

}




// If no chart data available...

if ( sizeof($runtime_data['performance_stats']) < 1 ) {
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
     text: "No '<?=ucfirst($_GET['time_period'])?> day(s)' lite chart data for any assets yet, please check back in awhile.",
  	  fontColor: "black",
     backgroundColor: "#808080",
     fontSize: 20,
     textAlpha: .9,
     alpha: .6,
     bold: true
   },
  	backgroundColor: "#f2f2f2",
  	height: <?=($_GET['chart_height'] - 4)?>,
  	width: <?=( $_GET['chart_width'] ? ($_GET['chart_width'] - 4) : "'100%'" )?>,
  	x: 0, 
  	y: 0,
  	title: {
        text: "Asset Performance Comparison (<?=strtoupper($default_btc_primary_currency_pairing)?>)",
        adjustLayout: true,
    	  align: 'center',
    	  offsetX: 0,
    	  offsetY: 9
  	},
   series: [{
     values: []
   }]
        
}
			
<?php
exit;
}
		


// If chart data exists...

$loop = 0;
foreach ( $runtime_data['performance_stats'] as $chart_key => $chart_value ) {
  			
$percent_sample_newest = number_to_string( delimited_string_sample($chart_value['data']['percent'], ',', 'last') );

	// If percent value matches, and another (increasing) number to the end, to avoid overwriting keys (this data is only used as an array key anyway)
	if ( !array_key_exists($percent_sample_newest, $sorted_by_last_chart_data) ) {
	$sorted_by_last_chart_data[$percent_sample_newest] = array($chart_key, $chart_value);
	}
	else {
	$sorted_by_last_chart_data[$percent_sample_newest . $loop] = array($chart_key, $chart_value);
	$loop = $loop + 1;
	}

}
  		
  // Sort array keys by lowest numeric value to highest 
// (newest/last chart sensors data sorts lowest value to highest, for populating the 2 shared charts)
ksort($sorted_by_last_chart_data);
  
	foreach ( $sorted_by_last_chart_data as $chart_array ) {
			
	$rand_color = '#' . randomColor()['hex'];
		
					
				$chart_config = "{
			  text: '".$chart_array[0]."',
			  values: [".$chart_array[1]['data']['combined']."],
			  lineColor: '".$rand_color."',
				 marker: {
			 backgroundColor: '".$rand_color."',
			 borderColor: '".$rand_color."'
				 },
			  legendItem: {
				  fontColor: 'white',
			   fontSize: ".$_GET['menu_size'].",
			   fontFamily: 'Open Sans',
				backgroundColor: '".$rand_color."',
				borderRadius: '2px'
			  }
			},
			" . $chart_config;
			
		
	}
		
			

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
  		height: <?=($_GET['chart_height'] - 4)?>,
  		width: <?=( $_GET['chart_width'] ? ($_GET['chart_width'] - 4) : "'100%'" )?>,
      borderColor: '#f2f2f2',
      borderRadius: '8px',
      borderWidth: '2px',
      title: {
        text: "Asset Performance Comparison (<?=strtoupper($default_btc_primary_currency_pairing)?>)",
        adjustLayout: true,
    	  align: 'center',
    	  offsetX: 0,
    	  offsetY: 9
      },  
  		source: {
  		   text: "Select area to zoom in chart, or use zoom grab bars in preview area (X and Y axis zooming supported).",
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
    	  offsetX: -10,
    	  offsetY: -0,
        draggable: false,
        header: {
          text: 'Hide / Show',
    		 offsetX: -8,
    	    offsetY: -0,
      	 fontColor: "blue",
	 		 fontSize: "17",
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
          text: "<?=strtoupper($default_btc_primary_currency_pairing)?> Value Percentage Change"
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
          multiple: true,
      	text: " %t %v%",
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
      ]
    }
  ]
  
    }


			<?php
			

?>
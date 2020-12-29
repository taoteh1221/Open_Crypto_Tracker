<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

foreach ( $app_config['portfolio_assets'] as $key => $unused ) {

// Consolidate function calls for runtime speed improvement
$marketcap_data = marketcap_data($key, 'usd'); // For marketcap bar chart, we ALWAYS force using USD

//var_dump($marketcap_data);
		
	if ( $_GET['marketcap_type'] == 'circulating' && $marketcap_data['market_cap'] ) {
	$runtime_data['marketcap_data'][$key] = remove_number_format($marketcap_data['market_cap']);
	}
	elseif ( $_GET['marketcap_type'] == 'total' && $marketcap_data['market_cap_total'] ) {
	$runtime_data['marketcap_data'][$key] = ( remove_number_format($marketcap_data['market_cap_total']) ); 
	}
	// If circulating / total are same
	elseif ( $_GET['marketcap_type'] == 'total' && $marketcap_data['market_cap'] ) {
	$runtime_data['marketcap_data'][$key] = ( remove_number_format($marketcap_data['market_cap']) ); 
	}

}




// If no chart data available...

if ( sizeof($runtime_data['marketcap_data']) < 1 ) {
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
     text: "No marketcap data found for any assets, please check back in awhile.",
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
        text: "Marketcap Comparison (USD)",
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
foreach ( $runtime_data['marketcap_data'] as $marketcap_key => $marketcap_value ) {
  			
$marketcap_value = remove_number_format($marketcap_value);

	// If percent value matches, and another (increasing) number to the end, to avoid overwriting keys (this data is only used as an array key anyway)
	if ( !array_key_exists($marketcap_value, $sorted_by_marketcap_data) ) {
	$sorted_by_marketcap_data[$marketcap_value] = array($marketcap_key, $marketcap_value);
	}
	else {
	$sorted_by_marketcap_data[$marketcap_value . $loop] = array($marketcap_key, $marketcap_value);
	$loop = $loop + 1;
	}

}
  		
  // Sort array keys by lowest numeric value to highest 
// (newest/last chart sensors data sorts lowest value to highest, for populating the 2 shared charts)
ksort($sorted_by_marketcap_data);

//var_dump($sorted_by_marketcap_data);
  
	foreach ( $sorted_by_marketcap_data as $marketcap_array ) {
			
	$rand_color = '#' . randomColor()['hex'];
		
					
				$marketcap_config = "{
			  text: '".$marketcap_array[0]."',
			  backgroundColor: '".$rand_color."',
			  values: [".$marketcap_array[1]."],
			  legendItem: {
				  fontColor: 'white',
			   fontSize: ".$_GET['menu_size'].",
			   fontFamily: 'Open Sans',
				backgroundColor: '".$rand_color."',
				borderRadius: '2px'
			  }
			},
			" . $marketcap_config;
			
		
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
      type: 'bar',
  		height: <?=($_GET['chart_height'] - 4)?>,
  		width: <?=( $_GET['chart_width'] ? ($_GET['chart_width'] - 4) : "'100%'" )?>,
      borderColor: '#f2f2f2',
      borderRadius: '8px',
      borderWidth: '2px',
      title: {
        text: "USD <?=ucfirst($_GET['marketcap_type'])?> Marketcap Comparison (<?=ucfirst($_GET['marketcap_site'])?>.com)",
        adjustLayout: true,
    	  align: 'center',
    	  offsetX: 0,
    	  offsetY: 9
      },  
  		source: {
	 		height: '13px',
  		   text: "Select height area to zoom in chart, to see smaller values better (only Y axis zooming supported).",
    		fontColor:"#dd7c0d",
	      fontSize: "15",
    		fontFamily: "Open Sans",
    		offsetX: 140,
    		offsetY: -4,
    		align: 'left',
        	adjustLayout: true
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
        "alpha": 0.95,
        "border-radius-top-left": 6,
        "border-radius-top-right": 6,
    		'value-box': {
            text: '%t',
	 		 	fontSize: "12",
            placement: 'bottom'
    		},
    		marker:{
      		visible: false
    		},
    		tooltip: {
      	 backgroundColor: "white",
      	 fontColor: "black",
	 		 fontSize: "20",
      	 fontFamily: "Open Sans",
          borderRadius: '2px',
          borderWidth: '2px',
      	 text: "%t Marketcap (<?=$_GET['marketcap_type']?>): $%v",
    	 	 "thousands-separator":","
    		}
      },
      plotarea: {
        margin: 'dynamic',
        adjustLayout: true
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
      	lineColor: "#444444"
        },
        label: {
          text: "USD <?=ucfirst($_GET['marketcap_type'])?> Marketcap"
        },
    	zooming: true
      },
      crosshairY: {
    	  exact: true
      },
      tooltip: {
        visible: true
      },
  		backgroundColor: "#f2f2f2",
      series: [
        <?php echo $marketcap_config . "\n" ?>
      ],
	labels: [
			{
	    x: 55,
	    y: 6,
	    id: 'reset',
	    fontColor: "blue",
	    fontSize: "22",
	    fontFamily: "Open Sans",
	    lineStyle: "solid",
	    cursor: "hand",
	    text: "Reset Zoom"
	  		}
		]
		
    }
  ]
  
    }


			<?php
			

?>
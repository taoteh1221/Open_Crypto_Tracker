<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$chart_file = $ct['plug']->chart_cache('solana_nodes_count.dat', 'on-chain-stats');
						

// NO EARLIER THAN A CERTAIN TIMESTAMP
if ( file_exists($chart_file) ) {
     
$ct['plug_runtime_data']['on-chain-stats']['node_stats']['all_nodes'] = $plug['class']['on-chain-stats']->solana_node_count_chart($chart_file, 'all_nodes', $_GET['start_time']); 

$ct['plug_runtime_data']['on-chain-stats']['node_stats']['validators'] = $plug['class']['on-chain-stats']->solana_node_count_chart($chart_file, 'validators', $_GET['start_time']); 

$ct['plug_runtime_data']['on-chain-stats']['node_stats']['recently_offline_validators'] = $plug['class']['on-chain-stats']->solana_node_count_chart($chart_file, 'recently_offline_validators', $_GET['start_time']); 

}


// If no chart data available...

if (
!is_array($ct['plug_runtime_data']['on-chain-stats']['node_stats'])
|| is_array($ct['plug_runtime_data']['on-chain-stats']['node_stats']) && sizeof($ct['plug_runtime_data']['on-chain-stats']['node_stats']) < 1
) {
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
     text: "No light chart data for any nodes yet, please check back in awhile.",
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
        text: "Solana Node Count",
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

$sorted_by_last_chart_data = array();

$loop = 0;
foreach ( $ct['plug_runtime_data']['on-chain-stats']['node_stats'] as $chart_key => $chart_val ) {

$count_sample_newest = $ct['var']->num_to_str( $ct['var']->delimited_str_sample($chart_val['count'], ',', 'last') );

	// If percent value matches, and another (increasing) number to the end, to avoid overwriting keys (this data is only used as an array key anyway)
	if ( !array_key_exists($count_sample_newest, $sorted_by_last_chart_data) ) {
	$sorted_by_last_chart_data[$count_sample_newest] = array($chart_key, $chart_val);
	}
	else {
	$sorted_by_last_chart_data[$count_sample_newest . $loop] = array($chart_key, $chart_val);
	$loop = $loop + 1;
	}

}
  		
  // Sort array keys by lowest numeric value to highest 
// (newest/last chart sensors data sorts lowest value to highest, for populating the 2 shared charts)
ksort($sorted_by_last_chart_data);
  
	foreach ( $sorted_by_last_chart_data as $chart_array ) {
		
     $choose_rand = ( is_array($sorted_by_last_chart_data) ? sizeof($sorted_by_last_chart_data) : 0 );
    
	$rand_color = '#' . $ct['gen']->rand_color($choose_rand)['hex'];
		
					
				$chart_conf = "{
			  text: '" . $ct['gen']->key_to_name($chart_array[0]) . "',
			  values: [".$chart_array[1]['count']."],
			  visible: true,
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
			" . $chart_conf;
			
		
	}
		
			
		
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
        text: "Solana Node Count",
        adjustLayout: true,
    	  align: 'center',
    	  offsetX: 0,
    	  offsetY: 9
      },  
  		source: {
  		   text: "Select area to zoom in chart, or use zoom grab bars in preview area (vertical / horizontal axis zooming supported).",
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
    "thousands-separator": ",",
        guide: {
      	visible: true,
     		lineStyle: 'solid',
      	lineColor: "#444444"
        },
        label: {
          text: "Node Count Change"
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
      "thousands-separator": ",",
      	 backgroundColor: "white",
      	 fontColor: "black",
	 		 fontSize: "20",
      	 fontFamily: "Open Sans",
          borderRadius: '2px',
          borderWidth: '2px',
          multiple: true,
      	text: " %t: %v Online",
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
        <?php echo $chart_conf . "\n" ?>
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



<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


	// Have this script not load any code if system stats are not turned on, or key GET request corrupt
	if ( !$ct['sec']->admin_logged_in() || !is_numeric($_GET['key']) ) {
	exit;
	}


$key = $_GET['key'];

$x_coord = 55; // Start position (absolute) for light chart links
			
		
// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
if ( !file_exists('cache/charts/system/light/' . $_GET['days'] . '_days/system_stats.dat') ) {
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
      noData: {
         text: "No data exists for this '<?=$ct['gen']->light_chart_time_period($_GET['days'], 'long')?>' light chart yet, please check back in awhile.",
      	  fontColor: "#e8e8e8",
         backgroundColor: "#808080",
         fontSize: 16,
         textAlpha: .9,
         alpha: .6,
         bold: true
      },
      borderColor: '#cccccc',
      borderRadius: '2px',
      borderWidth: '1px',
      title: {
        text: 'System Chart #<?=$key?>',
        adjustLayout: true,
    	  align: 'right',
    	  offsetX: -20,
    	  offsetY: -2
      },  
  		source: {
  		   text: "Select area to zoom, or use (X / Y axis) zoom grab bars in preview.",
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
          text: 'Hide / Show',
    		 offsetX: -8,
    	    offsetY: -20,
      	 fontColor: "blue",
	 		 fontSize: "16",
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
    			fontSize: 16
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
      maxValue: <?=( $key == 1 ? $ct['conf']['power']['system_stats_first_chart_maximum_scale'] : $ct['conf']['power']['system_stats_second_chart_maximum_scale'] )?>,
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
	 		 fontSize: "16",
      	 fontFamily: "Open Sans",
          borderRadius: '2px',
          borderWidth: '2px',
          multiple: true
        },
    	  scaleLabel:{
   	  	 alpha: 1.0,
    	    fontColor: "black",
      	 fontSize: 16,
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
	    y: 7,
	    id: '<?=$light_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $light_chart_days ? 'black' : '#9b9b9b' )?>",
	    fontSize: "16",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$light_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits
		if ( isset($light_chart_text) ) {
		$x_coord = $x_coord + ( strlen($light_chart_text) * $ct['conf']['power']['light_chart_link_font_offset'] ); 
	     $x_coord = $x_coord + $ct['conf']['power']['light_chart_link_spacing'] - 7;
		}
	
	}
	?>
	]
    }
  ]
  
}


<?php
exit;
}
			
		
$chart_data = $ct['gen']->chart_data('cache/charts/system/light/' . $_GET['days'] . '_days/system_stats.dat', 'system');


$sorted_by_last_chart_data = array();

// Determine how many data sensors to include in first chart
$num_in_first_chart = 0;
$loop = 0;
foreach ( $chart_data as $chart_key => $chart_val ) {

// Check last value
$check_chart_val = $ct['var']->num_to_str( $ct['var']->delimited_str_sample($chart_val, ',', 'last') );
	
	if ( $chart_key != 'time' && $check_chart_val > 0.0000000000 ) {
		
	$check_chart_val_key = $ct['var']->num_to_str($check_chart_val * 100000000); // To RELIABLY sort integers AND decimals, via ksort()
	
		// If value matches, and another (increasing) number to the end, to avoid overwriting keys (this data is only used as an array key anyway)
		if ( !array_key_exists($check_chart_val_key, $sorted_by_last_chart_data) ) {
		$sorted_by_last_chart_data[$check_chart_val_key] = array($chart_key => $chart_val);
		}
		else {
		$sorted_by_last_chart_data[$check_chart_val_key . $loop] = array($chart_key => $chart_val);
		$loop = $loop + 1;
		}
		
		if ( $check_chart_val <= $ct['var']->num_to_str($ct['conf']['power']['system_stats_first_chart_maximum_scale']) ) {
		$num_in_first_chart = $num_in_first_chart + 1;
		//echo $check_chart_val . ' --- '; // DEBUGGING ONLY
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
		
		foreach ( $chart_array as $chart_key => $chart_val ) {
		
			if ( isset($chart_val) && trim($chart_val) != '' && $counted < $num_in_first_chart && $chart_key != 'time' ) {
			    
			$is_chart_data = true;
			
			$counted = $counted + 1;
			
            $choose_rand = ( is_array($sorted_by_last_chart_data) ? sizeof($sorted_by_last_chart_data) : 0 );
    
			$rand_color = '#' . $ct['gen']->rand_color($choose_rand)['hex'];
					
			$chart_conf = "{
			  text: '".$ct['gen']->key_to_name($chart_key)."',
			  values: [".$chart_val."],
			  lineColor: '".$rand_color."',
				 marker: {
			 backgroundColor: '".$rand_color."',
			 borderColor: '".$rand_color."'
				 },
			  legendItem: {
				  fontColor: 'white',
			   fontSize: 16,
			   fontFamily: 'Open Sans',
				backgroundColor: '".$rand_color."',
				borderRadius: '2px'
			  }
			},
			" . $chart_conf;
			
			}
	
		}
		
    $loop = $loop + 1;
	}

}
elseif ( $key == 2 ) {
	
	$loop = 1;
	$counted = 0;
	foreach ( $sorted_by_last_chart_data as $chart_array ) {
		
		foreach ( $chart_array as $chart_key => $chart_val ) {
		
			if ( isset($chart_val) && trim($chart_val) != '' && $counted >= $num_in_first_chart && $chart_key != 'time' ) {
			    
			$is_chart_data = true;
			
			$counted = $counted + 1;
			
			$choose_rand = ( is_array($sorted_by_last_chart_data) ? sizeof($sorted_by_last_chart_data) : 0 );
    
			$rand_color = '#' . $ct['gen']->rand_color($choose_rand)['hex'];
	
			$chart_conf = "{
			  text: '".$ct['gen']->key_to_name($chart_key)."',
			  values: [".$chart_val."],
			  lineColor: '".$rand_color."',
				 marker: {
			 backgroundColor: '".$rand_color."',
			 borderColor: '".$rand_color."'
				 },
			  legendItem: {
				  fontColor: 'white',
			   fontSize: 16,
			   fontFamily: 'Open Sans',
				backgroundColor: '".$rand_color."',
				borderRadius: '2px'
			  }
			},
			" . $chart_conf;
		  
			}
			elseif ( $chart_key != 'time' ) {
			$counted = $counted + 1;
			}
	
		}
		
    $loop = $loop + 1;
	}

}

$chart_conf = trim($chart_conf);
$chart_conf = rtrim($chart_conf,',');


// Chart data output
if ( $is_chart_data == true ) {
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
    	  offsetY: -2
      },  
  		source: {
  		   text: "Select area to zoom, or use (X / Y axis) zoom grab bars in preview.",
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
          text: 'Hide / Show',
    		 offsetX: -8,
    	    offsetY: -20,
      	 fontColor: "blue",
	 		 fontSize: "16",
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
    			fontSize: 16
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
      maxValue: <?=( $key == 1 ? $ct['conf']['power']['system_stats_first_chart_maximum_scale'] : $ct['conf']['power']['system_stats_second_chart_maximum_scale'] )?>,
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
	 		 fontSize: "16",
      	 fontFamily: "Open Sans",
          borderRadius: '2px',
          borderWidth: '2px',
          multiple: true
        },
    	  scaleLabel:{
   	  	 alpha: 1.0,
    	    fontColor: "black",
      	 fontSize: 16,
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
	<?php
	foreach ($ct['light_chart_day_intervals'] as $light_chart_days) {
	$light_chart_text = $ct['gen']->light_chart_time_period($light_chart_days, 'short');
	?>
		{
	    x: <?=$x_coord?>,
	    y: 7,
	    id: '<?=$light_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $light_chart_days ? 'black' : '#9b9b9b' )?>",
	    fontSize: "16",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$light_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits
		if ( isset($light_chart_text) ) {
		$x_coord = $x_coord + ( strlen($light_chart_text) * $ct['conf']['power']['light_chart_link_font_offset'] ); 
	     $x_coord = $x_coord + $ct['conf']['power']['light_chart_link_spacing'] - 7;
		}
	
	}
	?>
	]
    }
  ]
  
}


<?php
}
else {
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
      noData: {
         text: "No data exists for this '<?=$ct['gen']->light_chart_time_period($_GET['days'], 'long')?>' light chart yet, please check back in awhile.",
      	  fontColor: "#e8e8e8",
         backgroundColor: "#808080",
         fontSize: 16,
         textAlpha: .9,
         alpha: .6,
         bold: true
      },
      borderColor: '#cccccc',
      borderRadius: '2px',
      borderWidth: '1px',
      title: {
        text: 'System Chart #<?=$key?>',
        adjustLayout: true,
    	  align: 'right',
    	  offsetX: -20,
    	  offsetY: -2
      },  
  		source: {
          text: "Select area to zoom, or use zoom grab bars in preview (for X axis).",
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
          text: 'Hide / Show',
    		 offsetX: -8,
    	    offsetY: -20,
      	 fontColor: "blue",
	 		 fontSize: "16",
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
    			fontSize: 16
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
      maxValue: <?=( $key == 1 ? $ct['conf']['power']['system_stats_first_chart_maximum_scale'] : $ct['conf']['power']['system_stats_second_chart_maximum_scale'] )?>,
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
	 		 fontSize: "16",
      	 fontFamily: "Open Sans",
          borderRadius: '2px',
          borderWidth: '2px',
          multiple: true
        },
    	  scaleLabel:{
   	  	 alpha: 1.0,
    	    fontColor: "black",
      	 fontSize: 16,
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
	    y: 7,
	    id: '<?=$light_chart_days?>',
	    fontColor: "<?=($_GET['days'] == $light_chart_days ? 'black' : '#9b9b9b' )?>",
	    fontSize: "16",
	    fontFamily: "Open Sans",
	    cursor: "hand",
	    text: "<?=$light_chart_text?>"
	  	},
	<?php
	
		// Account for more / less digits
		if ( isset($light_chart_text) ) {
		$x_coord = $x_coord + ( strlen($light_chart_text) * $ct['conf']['power']['light_chart_link_font_offset'] ); 
	     $x_coord = $x_coord + $ct['conf']['power']['light_chart_link_spacing'] - 7;
		}
	
	}
	?>
	]
    }
  ]
  
}


<?php
// END Chart data output
unset($is_chart_data); // Reset
exit;
}
?>



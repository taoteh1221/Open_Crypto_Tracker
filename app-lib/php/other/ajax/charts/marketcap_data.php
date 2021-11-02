<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


if ( $_GET['mcap_compare_diff'] != 'none' ) {
	
// Consolidate function calls for runtime speed improvement
$mcap_compare = $ct_asset->mcap_data($_GET['mcap_compare_diff'], 'usd'); // For marketcap bar chart, we ALWAYS force using USD
	
		
	if ( $_GET['mcap_type'] == 'circulating' && $mcap_compare['market_cap'] ) {
	$mcap_compare_diff = $ct_var->rem_num_format($mcap_compare['market_cap']);
	}
	elseif ( $_GET['mcap_type'] == 'total' && $mcap_compare['market_cap_total'] ) {
	$mcap_compare_diff = ( $ct_var->rem_num_format($mcap_compare['market_cap_total']) ); 
	}
	// If circulating / total are same
	elseif ( $_GET['mcap_type'] == 'total' && $mcap_compare['market_cap'] ) {
	$mcap_compare_diff = ( $ct_var->rem_num_format($mcap_compare['market_cap']) ); 
	}
	
}


foreach ( $ct_conf['assets'] as $key => $unused ) {
		
// Consolidate function calls for runtime speed improvement
$mcap_data = $ct_asset->mcap_data($key, 'usd'); // For marketcap bar chart, we ALWAYS force using USD
	
	if ( $key != 'MISCASSETS' && isset($mcap_data['rank']) ) {
	
	
		if ( $_GET['mcap_compare_diff'] == 'none' ) {
		
			
			if ( $_GET['mcap_type'] == 'circulating' && $mcap_data['market_cap'] ) {
			$runtime_data['marketcap_data'][$key] = $ct_var->rem_num_format($mcap_data['market_cap']);
			}
			elseif ( $_GET['mcap_type'] == 'total' && $mcap_data['market_cap_total'] ) {
			$runtime_data['marketcap_data'][$key] = $ct_var->rem_num_format($mcap_data['market_cap_total']); 
			}
			// If circulating / total are same
			elseif ( $_GET['mcap_type'] == 'total' && $mcap_data['market_cap'] ) {
			$runtime_data['marketcap_data'][$key] = $ct_var->rem_num_format($mcap_data['market_cap']); 
			}
		
		$mcap_asset_compare = 'USD';
		$mcap_chart_val = '$%v';
		$scale_y_format = '$%v';
		
		}
		else {
		
			
			if ( $_GET['mcap_type'] == 'circulating' && $mcap_data['market_cap'] ) {
			$temp_mcap = $ct_var->rem_num_format($mcap_data['market_cap']);
			}
			elseif ( $_GET['mcap_type'] == 'total' && $mcap_data['market_cap_total'] ) {
			$temp_mcap = $ct_var->rem_num_format($mcap_data['market_cap_total']); 
			}
			// If circulating / total are same
			elseif ( $_GET['mcap_type'] == 'total' && $mcap_data['market_cap'] ) {
			$temp_mcap = $ct_var->rem_num_format($mcap_data['market_cap']); 
			}
			
		
		$mcap_asset_compare = $_GET['mcap_compare_diff'];
		$mcap_chart_val = '%v% (of ' . $_GET['mcap_compare_diff'] . ' cap)';
		$scale_y_format = '%v%';
			
		$mcap_diff_percent = $ct_var->num_to_str( 100 + ( ($temp_mcap - $mcap_compare_diff) / abs($mcap_compare_diff) * 100 ) );
			
			// Decimal amount
			if ( $mcap_diff_percent >= 1 ) {
			$mcap_diff_dec = 2;
			}
			else {
			$mcap_diff_dec = 5;
			}
			
		$runtime_data['marketcap_data'][$key] = $ct_var->num_pretty($mcap_diff_percent, $mcap_diff_dec);
		
		}
	
	//var_dump($mcap_data);
	
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
        text: "<?=$mcap_asset_compare?> <?=ucfirst($_GET['mcap_type'])?> Marketcap Comparison (<?=ucfirst($_GET['marketcap_site'])?>.com)",
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
foreach ( $runtime_data['marketcap_data'] as $mcap_key => $mcap_val ) {
  			
$mcap_val = $ct_var->rem_num_format($mcap_val);

	// If percent value matches, and another (increasing) number to the end, to avoid overwriting keys (this data is only used as an array key anyway)
	if ( !array_key_exists($mcap_val, $sorted_by_mcap_data) ) {
	$sorted_by_mcap_data[$mcap_val] = array($mcap_key, $mcap_val);
	}
	else {
	$sorted_by_mcap_data[$mcap_val . $loop] = array($mcap_key, $mcap_val);
	$loop = $loop + 1;
	}

}
  		
  // Sort array keys by lowest numeric value to highest 
// (newest/last chart sensors data sorts lowest value to highest, for populating the 2 shared charts)
ksort($sorted_by_mcap_data);

$plot_conf = explode('|', $_GET['plot_conf']);

//var_dump($sorted_by_mcap_data);
  
	foreach ( $sorted_by_mcap_data as $mcap_array ) {
		
		if ( in_array($mcap_array[0], $plot_conf) ) {
		$show_plot = 'visible: true,';
		}
		else {
		$show_plot = 'visible: false,';
		}
			
	$rand_color = '#' . $ct_gen->rand_color( sizeof($sorted_by_mcap_data) )['hex'];
		
					
				$mcap_conf = "{
			  text: '".$mcap_array[0]."',
			  backgroundColor: '".$rand_color."',
			  values: [".$mcap_array[1]."],
			  ".$show_plot."
			  legendItem: {
					fontColor: 'white',
			  		fontSize: ".$_GET['menu_size'].",
			  		fontFamily: 'Open Sans',
					backgroundColor: '".$rand_color."',
					borderRadius: '2px'
			  }
			},
			" . $mcap_conf;
			
		
	}
		
			

header('Content-type: text/html; charset=' . $ct_conf['dev']['charset_default']);
		
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
        text: "<?=$mcap_asset_compare?> <?=ucfirst($_GET['mcap_type'])?> Marketcap Comparison (<?=ucfirst($_GET['marketcap_site'])?>.com)",
        adjustLayout: true,
    	  align: 'center',
    	  offsetX: 0,
    	  offsetY: 9
      },  
  		source: {
	 		height: '13px',
  		   text: "Select height area to zoom in chart, to see smaller values better (only vertical axis zooming supported).",
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
      	 text: "%t Marketcap (<?=$_GET['mcap_type']?>): <?=$mcap_chart_val?>",
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
   	  "format":"<?=$scale_y_format?>",
    	  "thousands-separator":",",
        guide: {
      	visible: true,
     		lineStyle: 'solid',
      	lineColor: "#444444"
        },
        label: {
          text: "<?=$mcap_asset_compare?> <?=ucfirst($_GET['mcap_type'])?> Marketcap"
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
        <?php echo $mcap_conf . "\n" ?>
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
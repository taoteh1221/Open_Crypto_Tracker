<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


?>


 <h5 class="yellow tooltip_title">Portfolio Balance Stats</h5>
 
  <div id='balance_chart' class='chart_wrapper' style='min-width: 700px; background: white; border: 2px solid #918e8e;'></div>
  
  <script>
    var balance_chart_conf = {
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
      foreach ( $_GET as $asset_key => $asset_val ) {
      	
      	if ( stristr($asset_key, 'MISC__') != false ) {
      	$asset_key = strtolower($asset_key);
      	$misc_array = explode("__", $asset_key);
      	$asset_key = $misc_array[1];
      	}
      
      	if ( $asset_key != 'type' && $asset_key != 'leverage_added' && $asset_key != 'short_added' && $asset_val >= 0.01 ) {
      ?>
        {
          "values": [<?=strtoupper($asset_val)?>],
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
      data: balance_chart_conf,
      height: 400,
      width: "100%"
    });
  </script>
  
  
  <p> &nbsp; </p>
  
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
  
  <p class="coin_info balloon_notation red"><?=( $_GET['leverage_added'] == 1 ? '*Does <u>not</u> adjust for any type of leverage' : '' )?><?=(  $_GET['short_added'] == 1 ? ', or short deposit(s) gain / loss' : '' )?><?=( $_GET['leverage_added'] == 1 ? '.' : '' )?></p>
  
  <p class="coin_info balloon_notation yellow">*All decimals are rounded to 2 places, and therefore may be slightly off up to 0.005%.</p>
			 
			 
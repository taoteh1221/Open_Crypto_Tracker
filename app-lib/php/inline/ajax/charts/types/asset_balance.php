<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


header('Access-Control-Allow-Headers: *'); // Allow ALL headers
header('Access-Control-Allow-Origin: *'); // Allow ALL origins, since we don't load init.php here

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
          
      $orig_key = $asset_key;
      	
      	if ( stristr($asset_key, 'MISC__') != false ) {
      	$asset_key = strtolower($asset_key);
      	$misc_array = explode("__", $asset_key);
      	$asset_key = strtoupper($misc_array[1]);
      	}
      	elseif ( stristr($asset_key, 'ETHNFTS') != false ) {
      	$asset_key = 'ETH NFTs';
      	}
      	elseif ( stristr($asset_key, 'SOLNFTS') != false ) {
      	$asset_key = 'SOL NFTs';
      	}
      	else {
      	$asset_key = strtoupper($asset_key);
      	}
      
      	if ( $orig_key != 'mode' && $orig_key != 'type' && $orig_key != 'leverage_added' && $orig_key != 'short_added' && $asset_val >= 0.01 ) {
      ?>
        {
          "values": [<?=strtoupper($asset_val)?>],
          "text": "<?=$asset_key?>"
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
					
				foreach ( $_GET as $asset_key => $asset_val ) {
          
                $orig_key = $asset_key;
					
					
                  	if ( stristr($asset_key, 'MISC__') != false ) {
                  	$asset_key = strtolower($asset_key);
                  	$misc_array = explode("__", $asset_key);
                  	$asset_key = strtoupper($misc_array[1]);
                  	}
                  	elseif ( stristr($asset_key, 'ETHNFTS') != false ) {
                  	$asset_key = 'ETH NFTs';
                  	}
                  	elseif ( stristr($asset_key, 'SOLNFTS') != false ) {
                  	$asset_key = 'SOL NFTs';
                  	}
                  	else {
                  	$asset_key = strtoupper($asset_key);
                  	}
      			
      
						if ( $orig_key != 'mode' && $orig_key != 'type' && $orig_key != 'leverage_added' && $orig_key != 'short_added' && $asset_val >= 0.01 ) {
				?>
			<p class="coin_info"><span class="yellow"><?=$asset_key?>:</span> <?=$asset_val?>%</p>
			
			<?php
						}
							
				}
			 ?>
  
  <p class="coin_info balloon_notation bitcoin"><?=( $_GET['leverage_added'] == 1 ? '*Does <u>not</u> adjust for any type of leverage' : '' )?><?=(  $_GET['short_added'] == 1 ? ', or short deposit(s) gain / loss' : '' )?><?=( $_GET['leverage_added'] == 1 ? '.' : '' )?></p>
  
  <p class="coin_info balloon_notation bitcoin">*All decimals are rounded to 2 places, and therefore may be slightly off up to 0.005%.</p>
			 
			 
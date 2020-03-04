<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

 ?>
 <h5 class="align_center yellow" style="position: relative; white-space: nowrap;">Portfolio Balance Stats:</h5>
 
  <div id='balance_chart' class='chart_wrapper' style='min-width: 700px;'></div>
  
  <script>
    var balance_chart_config = {
      "type": "pie",
  		backgroundColor: "none",
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
					
						if ( $key != 'type' && $key != 'leverage_added' && $key != 'short_added' && $value >= 0.01 ) {
				?>
			<p class="coin_info"><span class="yellow"><?=strtoupper($key)?>:</span> <?=$value?>%</p>
			
			<?php
						}
							
				}
			 ?>
  
  <p class="coin_info balloon_notation"><span class="yellow"><?=( $_GET['leverage_added'] == 1 ? '*Does <u>not</u> adjust for any type of leverage' : '' )?><?=(  $_GET['short_added'] == 1 ? ', or short deposit(s) gain / loss' : '' )?><?=( $_GET['leverage_added'] == 1 ? '.' : '' )?></span></p>
  
  <p class="coin_info"><span class="yellow"> &nbsp; </p>
			 
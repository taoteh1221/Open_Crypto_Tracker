<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Solana Node stats START (requires price charts)
if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' ) {
     
$sol_epoch_info = $ct['api']->solana_rpc('getEpochInfo', array(), 5)['result']; // 5 MINUTE CACHE
     
?>
	
	
	<h4 class='yellow'>Solana Epoch Information (cached for 5 minutes):</h4>
	
   <div class='secondary_chart_wrapper sol_epoch_data'>

    <p>
    <b class='yellow'>Absolute Slot:</b> <?=$ct['var']->num_pretty($sol_epoch_info['absoluteSlot'], 0)?>
    </p>
	
    <p>
    <b class='yellow'>Block Height:</b> <?=$ct['var']->num_pretty($sol_epoch_info['blockHeight'], 0)?>
    </p>
	
    <p>
    <b class='yellow'>Epoch:</b> <?=$ct['var']->num_pretty($sol_epoch_info['epoch'], 0)?>
    </p>
	
    <p>
    <b class='yellow'>Slot Index:</b> <?=$ct['var']->num_pretty($sol_epoch_info['slotIndex'], 0)?>
    </p>
	
    <p>
    <b class='yellow'>Slots In Epoch:</b> <?=$ct['var']->num_pretty($sol_epoch_info['slotsInEpoch'], 0)?>
    </p>
	
    <p>
    <b class='yellow'>Transactions Since Genesis:</b> <?=$ct['var']->num_pretty($sol_epoch_info['transactionCount'], 0)?>
    </p>
    
   </div>

    
	
	<h4 class='yellow' style='margin-top: 2em; margin-bottom: 1em;'>Solana Node Count:</h4>
	
    <p>
    
    <?php
    
    $node_count_chart_defaults = explode("||", $plug['conf'][$this_plug]['node_count_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $node_count_chart_defaults[0] >= 400 && $node_count_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$node_count_chart_defaults[0] = 600;
    	}
    	
    	if ( $node_count_chart_defaults[1] >= 7 && $node_count_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$node_count_chart_defaults[1] = 15;
    	}
    
    ?>
    
    
	<div class='align_left clear_both' style='white-space: nowrap;'>
    
    Custom Start Date: <input type="text" id='solana_node_count_date' name='solana_node_count_date' class="datepicker" value='' placeholder="yyyy/mm/dd (optional)" style='width: 155px; display: inline;' /> 
		
			 &nbsp;&nbsp; 

    
    Chart Height: <select class='browser-default custom-select' id='solana_node_count_chart_height' name='solana_node_count_chart_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $node_count_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    Menu Size: <select class='browser-default custom-select' id='solana_node_count_menu_size' name='solana_node_count_menu_size'>
    <?php
    $count = 7;
    while ( $count <= 16 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $node_count_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 1;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    <input type='button' value='Update Solana Node Count Chart' onclick="
  
  new_date = new Date();
  
  timestamp_offset = 60 * new_date.getTimezoneOffset(); // Local time offset (browser data), in seconds
  
  var solana_node_count_chart_width = document.getElementById('solana_node_count_chart').offsetWidth;
  
    
  // Reset any user-adjusted zoom
  zingchart.exec('solana_node_count_chart', 'viewall', {
    graphid: 0
  });
  
  
  $('#solana_node_count_chart div.chart_reload div.chart_reload_msg').html('Loading Asset Performance Chart...');
  
	$('#solana_node_count_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('solana_node_count_chart', 'complete', function() {
  	
	$('#solana_node_count_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#solana_node_count_chart').css('height', document.getElementById('solana_node_count_chart_height').value + 'px');
	$('#solana_node_count_chart').css('background', '#f2f2f2');
	
	$('.datepicker').datepicker('option', 'defaultDate', -7 );
	
	});
	
	var to_timestamp_var = ( document.getElementById('solana_node_count_date').value ? document.getElementById('solana_node_count_date').value : '1970/1/1' );
	
	date_array = to_timestamp_var.split('/');
	
	date_timestamp = to_timestamp(date_array[0],date_array[1],date_array[2],0,0,0) + timestamp_offset;
  
  // 'resize' MUST run before 'load'
  zingchart.exec('solana_node_count_chart', 'resize', {
  width: '100%',
  height: document.getElementById('solana_node_count_chart_height').value
  });
  
  // 'load'
  zingchart.exec('solana_node_count_chart', 'load', {
  	dataurl: '<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=sol_nodes&start_time=' + date_timestamp + '&chart_width=' + solana_node_count_chart_width + '&chart_height=' + document.getElementById('solana_node_count_chart_height').value + '&menu_size=' + document.getElementById('solana_node_count_menu_size').value,
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="tooltip_style_control node_count_chart_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    
    </div>
    
    
    </p>
    
  
  <script>
	$('.datepicker').datepicker({
    dateFormat: 'yy/mm/dd',
    defaultDate: -7
	});
  </script>
  
  <style>
	.ui-datepicker .ui-datepicker-header {
		background: #808080;
	}
 </style>
 
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; display: flex; flex-flow: column wrap; overflow: hidden;' class='secondary_chart_wrapper' id='solana_node_count_chart'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading Asset Performance Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#solana_node_count_chart span.chart_loading").html(' &nbsp; <img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading Asset Performance Chart...');
	
  
zingchart.bind('solana_node_count_chart', 'load', function() {
$("#solana_node_count_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=sol_nodes&start_time=0&chart_height=<?=$node_count_chart_defaults[0]?>&menu_size=<?=$node_count_chart_defaults[1]?>", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('solana_node_count_chart', 'complete', function() {
	$("#solana_node_count_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
	$('#solana_node_count_chart').css('height', '<?=$node_count_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'solana_node_count_chart',
  	height: '<?=$node_count_chart_defaults[0]?>',
  	width: "100%",
  	data: json_data
	});

 
});


// Reset user-adjusted zoom
zingchart.bind('solana_node_count_chart', 'label_click', function(e){
		
  	zingchart.exec('solana_node_count_chart', 'viewall', {
   graphid: 0
  	});
		
});

    
  </script>


<!-- SOLANA NODES GEOLOCATION MAP  -->
    
    <h4 class='yellow' style='margin-top: 2em; margin-bottom: 1em;'>Solana Node GeoLocation:</h4>
    
    
    <?php
    if ( !file_exists( $ct['plug']->event_cache('solana_node_geolocation_cleanup.dat', $this_plug) ) ) {
    ?>
             	
             	<p style='font-weight: bold; margin: 1em !important;' class='red red_dotted'>
             	
             	It may take an hour or longer to show GeoLocation data, after enabling the On-Chain Stats plugin.
             	
             	</p>
             	
    <?php
    }
    else {
    $solana_node_geolocation_cleanup_timestamp = filemtime( $ct['plug']->event_cache('solana_node_geolocation_cleanup.dat', $this_plug) );
    $solana_node_geolocation_pretty_timestamp = date("F jS, g:ia", $solana_node_geolocation_cleanup_timestamp);
    }
    
    
    $node_geolocation_map_height_default = $plug['conf'][$this_plug]['node_geolocation_map_height_default'];
    
    	// Fallbacks
    	
    	if ( $node_geolocation_map_height_default >= 400 && $node_geolocation_map_height_default <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$node_geolocation_map_height_default = 500;
    	}
    
    ?>
    
    
	<div class='align_left clear_both' style='white-space: nowrap;'>
    
    Chart Height: <select class='browser-default custom-select' id='solana_node_geolocation_map_height' name='solana_node_geolocation_map_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $node_geolocation_map_height_default ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    Show Node Type: <select class='browser-default custom-select' id='solana_geolocation_filter' name='solana_geolocation_filter'>
    <option value='all'> All </option>
    <option value='rpc'> RPC </option>
    <option value='validators'> Validator </option>
    <option value='validators_without_epoch_votes'> Validator Without Epoch Votes </option>
    </select>  &nbsp;&nbsp; 
    
    Node Public Key: <input type='text' size='30' name='solana_address_filter' id='solana_address_filter' placeholder="(optional)" />
    
    <input type='button' value='Update Solana GeoLocation Map' onclick="
    
    // Remove old rendering
    geo_map_init['solana_map'].remove();

    // Adjust map height
	$('#solana_map').css('height', document.getElementById('solana_node_geolocation_map_height').value + 'px');
    
    // Reload map
    map_init(
             'solana_map',
             plugin_assets_path['on-chain-stats'] + '/plug-ajax.php?type=map&mode=geolocation&map_key=solana_map&filter=' + document.getElementById('solana_geolocation_filter').value+'&address=' + document.getElementById('solana_address_filter').value,
             '<?=$solana_node_geolocation_pretty_timestamp?>'
             );

    " /> 
    
    &nbsp; <img class="tooltip_style_control geolocation_filter" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    <div id="solana_map" class="secondary_chart_wrapper geolocation_map" style="position: relative; width: 100%; height: <?=$node_geolocation_map_height_default?>px; margin-top: 1.5em !important;"></div>

    <script>
    
    // Render map
    map_init(
             'solana_map',
             plugin_assets_path['on-chain-stats'] + '/plug-ajax.php?type=map&mode=geolocation&map_key=solana_map&filter=' + document.getElementById('solana_geolocation_filter').value+'&address=' + document.getElementById('solana_address_filter').value,
             '<?=$solana_node_geolocation_pretty_timestamp?>'
             );
		
    </script>
  

<?php
}
// Solana Node stats END
?>
	

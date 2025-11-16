<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Solana Node stats START (requires price charts)
if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' ) {
     
$sol_epoch_info = $ct['api']->solana_rpc('getEpochInfo', array(), 5)['result']; // 5 MINUTE CACHE

// Get on-chain Solana slot time average
$solana_slot_time = $plug['class'][$this_plug]->solana_performance('slot_time');
     
     if ( isset($solana_slot_time['slot_time_seconds']) && is_numeric($solana_slot_time['slot_time_seconds']) ) {
     
     $sol_epoch_slots_left = $ct['var']->num_to_str($sol_epoch_info['slotsInEpoch'] - $sol_epoch_info['slotIndex']);
     
     $sol_epoch_seconds_left = (float)$sol_epoch_slots_left * (float)$solana_slot_time;
     
     $sol_epoch_seconds_left = round($sol_epoch_seconds_left);
          
          // Days
          if ( $sol_epoch_seconds_left > 86400 ) {
          $sol_epoch_time_left = '(~' . round( ($sol_epoch_seconds_left / 86400) , 2) . ' days remaining)';
          }
          // Hours
          elseif ( $sol_epoch_seconds_left > 3600 ) {
          $sol_epoch_time_left = '(~' . round( ($sol_epoch_seconds_left / 3600) , 2) . ' hours remaining)';
          }
          // Minutes
          elseif ( $sol_epoch_seconds_left > 60 ) {
          $sol_epoch_time_left = '(~' . round( ($sol_epoch_seconds_left / 60) , 2) . ' minutes remaining)';
          }
          // Seconds
          else {
          $sol_epoch_time_left = '(~' . round($sol_epoch_seconds_left, 2) . ' seconds remaining)';
          }
     
     }
     
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
    <b class='yellow'>Epoch:</b> <?=$ct['var']->num_pretty($sol_epoch_info['epoch'], 0)?> <?=( isset($sol_epoch_time_left) ? $sol_epoch_time_left : '' )?>
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

    
	
	<h4 class='yellow' style='margin-top: 2em; margin-bottom: 1em;'>Solana TPS:</h4>
	
    <p>
    
    <?php
    
    $tps_chart_defaults = explode("||", $plug['conf'][$this_plug]['tps_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $tps_chart_defaults[0] >= 400 && $tps_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$tps_chart_defaults[0] = 600;
    	}
    	
    	if ( $tps_chart_defaults[1] >= 7 && $tps_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$tps_chart_defaults[1] = 15;
    	}
    
    ?>
    
    
	<div class='align_left clear_both' style='white-space: nowrap;'>
    
    Time Period: <select class='browser-default custom-select' id='solana_tps_period' name='solana_tps_period' onchange="
    
		if ( this.value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -7 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -this.value );
		}
    
    ">
	<?php
	foreach ($ct['light_chart_day_intervals'] as $light_chart_days) {
	?>
    <option value='<?=$light_chart_days?>' <?=( $light_chart_days == 'all' ? 'selected' : '' )?>> <?=$ct['gen']->light_chart_time_period($light_chart_days, 'long')?> </option>
	<?php
	}
	?>
    </select>  &nbsp;&nbsp; 
    
    
    Custom Start Date: <input type="text" id='solana_tps_date' name='solana_tps_date' class="datepicker" value='' placeholder="yyyy/mm/dd (optional)" style='width: 155px; display: inline;' /> 
		
			 &nbsp;&nbsp; 

    
    Chart Height: <select class='browser-default custom-select' id='solana_tps_chart_height' name='solana_tps_chart_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $tps_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    Menu Size: <select class='browser-default custom-select' id='solana_tps_menu_size' name='solana_tps_menu_size'>
    <?php
    $count = 7;
    while ( $count <= 16 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $tps_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 1;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    <input type='button' value='Update Solana TPS Chart' onclick="
  
  new_date = new Date();
  
  timestamp_offset = 60 * new_date.getTimezoneOffset(); // Local time offset (browser data), in seconds
  
  var solana_tps_chart_width = document.getElementById('solana_tps_chart').offsetWidth;
  
    
  // Reset any user-adjusted zoom
  zingchart.exec('solana_tps_chart', 'viewall', {
    graphid: 0
  });
  
  
  $('#solana_tps_chart div.chart_reload div.chart_reload_msg').html('Loading Solana TPS Chart...');
  
	$('#solana_tps_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('solana_tps_chart', 'complete', function() {
  	
	$('#solana_tps_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#solana_tps_chart').css('height', document.getElementById('solana_tps_chart_height').value + 'px');
	$('#solana_tps_chart').css('background', '#f2f2f2');
	
		if ( document.getElementById('solana_tps_period').value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -7 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -document.getElementById('solana_tps_period').value );
		}
	
	});
	
	var to_timestamp_var = ( document.getElementById('solana_tps_date').value ? document.getElementById('solana_tps_date').value : '1970/1/1' );
	
	date_array = to_timestamp_var.split('/');
	
	date_timestamp = to_timestamp(date_array[0],date_array[1],date_array[2],0,0,0) + timestamp_offset;
  
  // 'resize' MUST run before 'load'
  zingchart.exec('solana_tps_chart', 'resize', {
  width: '100%',
  height: document.getElementById('solana_tps_chart_height').value
  });
  
  // 'load'
  zingchart.exec('solana_tps_chart', 'load', {
  	dataurl: '<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=sol_tps&time_period=' + document.getElementById('solana_tps_period').value + '&start_time=' + date_timestamp + '&chart_width=' + solana_tps_chart_width + '&chart_height=' + document.getElementById('solana_tps_chart_height').value + '&menu_size=' + document.getElementById('solana_tps_menu_size').value,
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="tooltip_style_control tps_chart_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    
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
 
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; display: flex; flex-flow: column wrap; overflow: hidden;' class='secondary_chart_wrapper' id='solana_tps_chart'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading Solana TPS Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#solana_tps_chart span.chart_loading").html(' &nbsp; <img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading Solana TPS Chart...');
	
  
zingchart.bind('solana_tps_chart', 'load', function() {
$("#solana_tps_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
fix_zingchart_watermarks(); // Make sure watermarks are showing properly
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=sol_tps&time_period=" + document.getElementById('solana_tps_period').value + "&start_time=0&chart_height=<?=$tps_chart_defaults[0]?>&menu_size=<?=$tps_chart_defaults[1]?>", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('solana_tps_chart', 'complete', function() {
	$("#solana_tps_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
     fix_zingchart_watermarks(); // Make sure watermarks are showing properly
	$('#solana_tps_chart').css('height', '<?=$tps_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'solana_tps_chart',
  	height: '<?=$tps_chart_defaults[0]?>',
  	width: "100%",
  	data: json_data
	});

 
});


// Reset user-adjusted zoom
zingchart.bind('solana_tps_chart', 'label_click', function(e){
		
  	zingchart.exec('solana_tps_chart', 'viewall', {
   graphid: 0
  	});
		
});

    
  </script>

	
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
    
    Time Period: <select class='browser-default custom-select' id='solana_node_count_period' name='solana_node_count_period' onchange="
    
		if ( this.value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -7 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -this.value );
		}
    
    ">
	<?php
	foreach ($ct['light_chart_day_intervals'] as $light_chart_days) {
	?>
    <option value='<?=$light_chart_days?>' <?=( $light_chart_days == 'all' ? 'selected' : '' )?>> <?=$ct['gen']->light_chart_time_period($light_chart_days, 'long')?> </option>
	<?php
	}
	?>
    </select>  &nbsp;&nbsp; 
    
    
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
  
  
  $('#solana_node_count_chart div.chart_reload div.chart_reload_msg').html('Loading Solana Node Count Chart...');
  
	$('#solana_node_count_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('solana_node_count_chart', 'complete', function() {
  	
	$('#solana_node_count_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#solana_node_count_chart').css('height', document.getElementById('solana_node_count_chart_height').value + 'px');
	$('#solana_node_count_chart').css('background', '#f2f2f2');
	
		if ( document.getElementById('solana_node_count_period').value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -7 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -document.getElementById('solana_node_count_period').value );
		}
	
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
  	dataurl: '<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=sol_nodes&time_period=' + document.getElementById('solana_node_count_period').value + '&start_time=' + date_timestamp + '&chart_width=' + solana_node_count_chart_width + '&chart_height=' + document.getElementById('solana_node_count_chart_height').value + '&menu_size=' + document.getElementById('solana_node_count_menu_size').value,
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
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading Solana Node Count Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#solana_node_count_chart span.chart_loading").html(' &nbsp; <img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading Solana Node Count Chart...');
	
  
zingchart.bind('solana_node_count_chart', 'load', function() {
$("#solana_node_count_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
fix_zingchart_watermarks(); // Make sure watermarks are showing properly
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=sol_nodes&time_period=" + document.getElementById('solana_node_count_period').value + "&start_time=0&chart_height=<?=$node_count_chart_defaults[0]?>&menu_size=<?=$node_count_chart_defaults[1]?>", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('solana_node_count_chart', 'complete', function() {
	$("#solana_node_count_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
     fix_zingchart_watermarks(); // Make sure watermarks are showing properly
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
    
    <p class='bitcoin'>Geolocation is approximate. It may vary from actual physical location, due to internal networking behind the gateway, or other factors.</p>
    
    
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
    
    Node Type: <select class='browser-default custom-select' id='solana_node_type' name='solana_node_type'>
    <option value='all'> All </option>
    <option value='rpc'> RPC </option>
    <option value='validators'> Validator </option>
    <option value='validators_without_epoch_votes'> Validator Without Epoch Votes </option>
    </select>  &nbsp;&nbsp; 
    
    Filter By: <select class='browser-default custom-select' id='solana_secondary_filter_type' name='solana_secondary_filter_type'>
    <option value='country'> Country </option>
    <option value='city'> City </option>
    <option value='time_zone'> Time Zone </option>
    <option value='isp'> ISP </option>
    <option value='node_version'> Node Version </option>
    <option value='node_shred_version'> Node Shred Version </option>
    <option value='node_feature_set'> Node Feature Set </option>
    <option value='public_key'> Public Key </option>
    </select>  &nbsp;&nbsp; 
    
    <input type='text' size='20' name='solana_secondary_filter' id='solana_secondary_filter' placeholder="(optional)" />
    
    <input type='button' value='Update Solana GeoLocation Map' onclick="
    
    // Remove old rendering
    geo_map_init['solana_map'].remove();

    // Adjust map height
	$('#solana_map').css('height', document.getElementById('solana_node_geolocation_map_height').value + 'px');
    
    // Reload map
    map_init(
             'solana_map',
             plugin_assets_path['on-chain-stats'] + '/plug-ajax.php?type=map&mode=geolocation&map_key=solana_map&node_type=' + document.getElementById('solana_node_type').value+'&secondary_filter=' + document.getElementById('solana_secondary_filter').value+'&secondary_filter_type=' + document.getElementById('solana_secondary_filter_type').value,
             '<?=$solana_node_geolocation_pretty_timestamp?>'
             );

    " /> 
    
    &nbsp; <img class="tooltip_style_control geolocation_filter" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    <div id="solana_map" class="secondary_chart_wrapper geolocation_map leaflet_zindex_fix" style="width: 100%; height: <?=$node_geolocation_map_height_default?>px; margin-top: 1.5em !important;"></div>

    <script>
    
    // Render map
    map_init(
             'solana_map',
             plugin_assets_path['on-chain-stats'] + '/plug-ajax.php?type=map&mode=geolocation&map_key=solana_map&node_type=' + document.getElementById('solana_node_type').value+'&secondary_filter=' + document.getElementById('solana_secondary_filter').value+'&secondary_filter_type=' + document.getElementById('solana_secondary_filter_type').value,
             '<?=$solana_node_geolocation_pretty_timestamp?>'
             );
		
    </script>
  

<?php
}
// Solana Node stats END
?>
	

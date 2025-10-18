<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

?>
	
	
	<script>
	// We want ONLY WATCHED ASSETS SHOWN for privacy mode, so nobody easily
	// becomes interested in what we are NOT watching on the update page
	if ( localStorage.getItem(priv_toggle_storage) == 'on' ) {
	zingchart_privacy = '&privacy=on';
	}
	else {
	zingchart_privacy = '&privacy=off';
	}
	</script>
	
	
  	<?php
  	// Performance chart START (requires price charts)
	if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' ) {
	?>
	
	<h4 class='yellow'>Solana Node Count</h4>
	
    <p>
    
    <?php
    
    $solana_node_count_chart_defaults = explode("||", $plug['conf'][$this_plug]['solana_node_count_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $solana_node_count_chart_defaults[0] >= 400 && $solana_node_count_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$solana_node_count_chart_defaults[0] = 600;
    	}
    	
    	if ( $solana_node_count_chart_defaults[1] >= 7 && $solana_node_count_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$solana_node_count_chart_defaults[1] = 15;
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
    <option value='<?=$count?>' <?=( $count == $solana_node_count_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
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
    <option value='<?=$count?>' <?=( $count == $solana_node_count_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
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
  	dataurl: '<?=$ct['plug']->plug_dir(true)?>/plug-assets/ajax.php?type=chart&mode=sol_nodes&start_time=' + date_timestamp + '&chart_width=' + solana_node_count_chart_width + '&chart_height=' + document.getElementById('solana_node_count_chart_height').value + '&menu_size=' + document.getElementById('solana_node_count_menu_size').value + zingchart_privacy,
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="tooltip_style_control solana_node_count_chart_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    
    </div>
    
    
<script>


var solana_node_count_chart_defaults_content = '<h5 class="yellow tooltip_title">Settings For Solana Node Count Chart</h5>'

			+'<p class="coin_info extra_margins" style=" white-space: normal;">The "Custom Start Date" is OPTIONAL, for choosing a custom date in time for the Solana Node Count chart to begin. The Custom Start Date can only go back in time as far back as you have data stored for, as this feature only starts storing Solana node count data once your app server background task starts saving chart data for the first time. IF you have saved chart backups (in the Backup / Restore admin area), you can restore archived chart data on new installations.</p>'
			
			+'<p class="coin_info extra_margins" style=" white-space: normal;">Adjust the chart height and menu size, depending on your preferences. The defaults for these two settings can be changed in "Admin Area => Plugins => On-Chain Stats => Solana Node Count Chart Defaults".</p>';
		
		
		
			$('.solana_node_count_chart_defaults').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: solana_node_count_chart_defaults_content,
			css: balloon_css()
			});
			
		
		
		


</script> 
  
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
 
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; background: #808080; border: 2px solid #918e8e; display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='solana_node_count_chart'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading Asset Performance Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#solana_node_count_chart span.chart_loading").html(' &nbsp; <img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading Asset Performance Chart...');
	
  
zingchart.bind('solana_node_count_chart', 'load', function() {
$("#solana_node_count_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "<?=$ct['plug']->plug_dir(true)?>/plug-assets/ajax.php?type=chart&mode=sol_nodes&start_time=0&chart_height=<?=$solana_node_count_chart_defaults[0]?>&menu_size=<?=$solana_node_count_chart_defaults[1]?>" + zingchart_privacy, function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('solana_node_count_chart', 'complete', function() {
	$("#solana_node_count_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
	$('#solana_node_count_chart').css('height', '<?=$solana_node_count_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'solana_node_count_chart',
  	height: '<?=$solana_node_count_chart_defaults[0]?>',
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
  

  	<?php
	}
  	// Solana Node Count chart END
	?>
	

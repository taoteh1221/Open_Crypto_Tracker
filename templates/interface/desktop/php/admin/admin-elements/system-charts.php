<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Don't load any data if admin isn't logged in
if ( $ct_gen->admin_logged_in() ) {



// For system charts, we want the first $ct_conf['power']['light_chart_day_intervals'] value, not 'all'
$first_light_chart = $ct_conf['power']['light_chart_day_intervals'][0];
		
		
	// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
	if ( file_exists('cache/charts/system/light/'.$first_light_chart.'_days/system_stats.dat') != 1 ) {
	?>
			
			$("#sys_stats_chart_<?=$chart_mode?> span.chart_loading").html(' &nbsp; No light chart build / re-build found for: System Chart #<?=$chart_mode?>');
			
			$("#sys_stats_chart_<?=$chart_mode?> span.chart_loading").css({ "background-color": "#9b4b26" });
			
			$("#system_charts_error").show();
			
			$("#system_charts_error").html('<p>One or more charts could not be loaded.</p> <p>If you recently installed this app, OR re-configured your light charts structure, it may take awhile for fully updated charts to appear. "light charts" need to be built / re-built from archival chart data, so charts always load quickly regardless of time span...this may take a few days to begin to populate longer time period charts.</p> <p>If you are using the "Server Edition" of this app, please make sure you have a cron job (or scheduled task) running (see <a href="README.txt" target="_blank">README.txt</a> for how-to setup a cron job), or charts cannot be activated. Check app error logs too, for write errors (which would indicate improper cache directory permissions, or that disk quota / space is completely filled up).</p>');
			
			
	<?php
	}
	else {
	?>


var light_state_<?=$chart_mode?> = {
  current: '<?=$first_light_chart?>'
};
 

$("#sys_stats_chart_<?=$chart_mode?> span.chart_loading").html(' &nbsp; <img src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading <?=$first_light_chart?> days chart for System Chart #<?=$chart_mode?>...');
	
  
zingchart.bind('sys_stats_chart_<?=$chart_mode?>', 'load', function() {
$("#sys_stats_chart_<?=$chart_mode?> span").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "ajax.php?type=chart&mode=system&key=<?=$chart_mode?>&days=<?=$first_light_chart?>", function( json_data ) {
 
	zingchart.render({
  	id: 'sys_stats_chart_<?=$chart_mode?>',
  	width: '100%',
  	data: json_data
	});
 
});
 
 
zingchart.bind('sys_stats_chart_<?=$chart_mode?>', 'label_click', function(e){
	
  if(light_state_<?=$chart_mode?>.current === e.labelid){
    return;
  }
  
  // Reset any user-adjusted zoom
  zingchart.exec('sys_stats_chart_<?=$chart_mode?>', 'viewall', {
    graphid: 0
  });
  
  var cut = 0;
  switch(e.labelid) {
  	
  	<?php
	foreach ($ct_conf['power']['light_chart_day_intervals'] as $light_chart_days) {
	?>	
	
    case '<?=$light_chart_days?>':
    	<?php
    	if ( $light_chart_days == 'all' ) {
    	?>
      var days = '<?=$light_chart_days?>';
    	<?php
    	}
    	else {
    	?>
      var days = <?=$light_chart_days?>;
    	<?php
    	}
    	?>
    break;
    
	<?php
	}
	?>
	
    default: 
      var days = '<?=$first_light_chart?>';
    break;
    
  }
  
  
		if ( days == 'all' ) {
		light_chart_text = days.toUpperCase();
		}
		else if ( days == 7 ) {
		light_chart_text = '1 week';
		}
		else if ( days == 14 ) {
		light_chart_text = '2 week';
		}
		else if ( days == 30 ) {
		light_chart_text = '1 month';
		}
		else if ( days == 60 ) {
		light_chart_text = '2 month';
		}
		else if ( days == 90 ) {
		light_chart_text = '3 month';
		}
		else if ( days == 180 ) {
		light_chart_text = '6 month';
		}
		else if ( days == 365 ) {
		light_chart_text = '1 year';
		}
		else if ( days == 730 ) {
		light_chart_text = '2 year';
		}
		else if ( days == 1095 ) {
		light_chart_text = '3 year';
		}
		else if ( days == 1460 ) {
		light_chart_text = '4 year';
		}
		else {
		light_chart_text = days + ' day';
		}
		
  
  $("#sys_stats_chart_<?=$chart_mode?> div.chart_reload div.chart_reload_msg").html("Loading " + light_chart_text + " chart for System Chart #<?=$chart_mode?>...");
	$("#sys_stats_chart_<?=$chart_mode?> div.chart_reload").fadeIn(100); // 0.1 seconds
	
  zingchart.bind('sys_stats_chart_<?=$chart_mode?>', 'complete', function() {
	$( "#sys_stats_chart_<?=$chart_mode?> div.chart_reload" ).fadeOut(2500); // 2.5 seconds
	});
  
  zingchart.exec('sys_stats_chart_<?=$chart_mode?>', 'load', {
  	dataurl: "ajax.php?type=chart&mode=system&key=<?=$chart_mode?>&days=" + days,
    cache: {
        data: true
    }
  });
  
  light_state_<?=$chart_mode?>.current = e.labelid;
  
});


<?php
	}

}
	
$chart_mode = null; 
?>





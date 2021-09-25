<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Don't load any data if admin isn't logged in
if ( $pt_gen->admin_logged_in() ) {



// For system charts, we want the first $pt_conf['power']['lite_chart_day_intervals'] value, not 'all'
$first_lite_chart = $pt_conf['power']['lite_chart_day_intervals'][0];
		
		
	// Have this script send the UI alert messages, and not load any chart code (to not leave the page endlessly loading) if cache data is not present
	if ( file_exists('cache/charts/system/lite/'.$first_lite_chart.'_days/system_stats.dat') != 1 ) {
	?>
			
			$("#system_stats_chart_<?=$chart_mode?> span.chart_loading").html(' &nbsp; No lite chart build / re-build found for: System Chart #<?=$chart_mode?>');
			
			$("#system_stats_chart_<?=$chart_mode?> span.chart_loading").css({ "background-color": "#9b4b26" });
			
			$("#system_charts_error").show();
			
			$("#system_charts_error").html('<p>One or more charts could not be loaded.</p> <p>If you recently installed this app OR re-configured your lite charts structure, it may take awhile for fully updated charts to appear. "lite charts" need to be built / re-built from archival chart data, so charts always load quickly regardless of time span...this may take a few days to begin to populate longer time period charts.</p> <p>Please make sure you have a cron job running (see <a href="README.txt" target="_blank">README.txt</a> for how-to setup a cron job), or charts cannot be activated. Check app error logs too, for write errors (which would indicate improper cache directory permissions).</p>');
			
			
	<?php
	}
	else {
	?>


var lite_state_<?=$chart_mode?> = {
  current: '<?=$first_lite_chart?>'
};
 

$("#system_stats_chart_<?=$chart_mode?> span.chart_loading").html(' &nbsp; <img src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading <?=$first_lite_chart?> days chart for System Chart #<?=$chart_mode?>...');
	
  
zingchart.bind('system_stats_chart_<?=$chart_mode?>', 'load', function() {
$("#system_stats_chart_<?=$chart_mode?> span").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "ajax.php?type=chart&mode=system&key=<?=$chart_mode?>&days=<?=$first_lite_chart?>", function( json_data ) {
 
	zingchart.render({
  	id: 'system_stats_chart_<?=$chart_mode?>',
  	width: '100%',
  	data: json_data
	});
 
});
 
 
zingchart.bind('system_stats_chart_<?=$chart_mode?>', 'label_click', function(e){
	
  if(lite_state_<?=$chart_mode?>.current === e.labelid){
    return;
  }
  
  // Reset any user-adjusted zoom
  zingchart.exec('system_stats_chart_<?=$chart_mode?>', 'viewall', {
    graphid: 0
  });
  
  var cut = 0;
  switch(e.labelid) {
  	
  	<?php
	foreach ($pt_conf['power']['lite_chart_day_intervals'] as $lite_chart_days) {
	?>	
	
    case '<?=$lite_chart_days?>':
    	<?php
    	if ( $lite_chart_days == 'all' ) {
    	?>
      var days = '<?=$lite_chart_days?>';
    	<?php
    	}
    	else {
    	?>
      var days = <?=$lite_chart_days?>;
    	<?php
    	}
    	?>
    break;
    
	<?php
	}
	?>
	
    default: 
      var days = '<?=$first_lite_chart?>';
    break;
    
  }
  
  
		if ( days == 'all' ) {
		lite_chart_text = days.toUpperCase();
		}
		else if ( days == 7 ) {
		lite_chart_text = '1 week';
		}
		else if ( days == 14 ) {
		lite_chart_text = '2 week';
		}
		else if ( days == 30 ) {
		lite_chart_text = '1 month';
		}
		else if ( days == 60 ) {
		lite_chart_text = '2 month';
		}
		else if ( days == 90 ) {
		lite_chart_text = '3 month';
		}
		else if ( days == 180 ) {
		lite_chart_text = '6 month';
		}
		else if ( days == 365 ) {
		lite_chart_text = '1 year';
		}
		else if ( days == 730 ) {
		lite_chart_text = '2 year';
		}
		else if ( days == 1095 ) {
		lite_chart_text = '3 year';
		}
		else if ( days == 1460 ) {
		lite_chart_text = '4 year';
		}
		else {
		lite_chart_text = days + ' day';
		}
		
  
  $("#system_stats_chart_<?=$chart_mode?> div.chart_reload div.chart_reload_msg").html("Loading " + lite_chart_text + " chart for System Chart #<?=$chart_mode?>...");
	$("#system_stats_chart_<?=$chart_mode?> div.chart_reload").fadeIn(100); // 0.1 seconds
	
  zingchart.bind('system_stats_chart_<?=$chart_mode?>', 'complete', function() {
	$( "#system_stats_chart_<?=$chart_mode?> div.chart_reload" ).fadeOut(2500); // 2.5 seconds
	});
  
  zingchart.exec('system_stats_chart_<?=$chart_mode?>', 'load', {
  	dataurl: "ajax.php?type=chart&mode=system&key=<?=$chart_mode?>&days=" + days,
    cache: {
        data: true
    }
  });
  
  lite_state_<?=$chart_mode?>.current = e.labelid;
  
});


<?php
	}

}
	
$chart_mode = null; 
?>





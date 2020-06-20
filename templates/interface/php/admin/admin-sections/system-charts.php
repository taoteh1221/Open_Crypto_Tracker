<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='full_width_wrapper'>
	
				<h3 class='align_center'>System Charts</h3>
				
	
    <div style='margin-bottom: 30px;'><?php
    			
    		// System data
    		$system_load = $system_info['system_load'];
    		$system_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $system_load);
    		$system_load = preg_replace("/(.*)\(5 min avg\) /i", "", $system_load); // Use 15 minute average
    		
    		$system_temp = preg_replace("/° Celsius/i", "", $system_info['system_temp']);
         
			$system_free_space_mb = in_megabytes($system_info['free_partition_space'])['in_megs'];
         
			$portfolio_cache_size_mb = in_megabytes($system_info['portfolio_cache'])['in_megs'];
    		
    		$system_memory_total_mb = in_megabytes($system_info['memory_total'])['in_megs'];
    		
    		$system_memory_free_mb = in_megabytes($system_info['memory_free'])['in_megs'];
    		
  			// Percent difference (!MUST BE! absolute value)
         $memory_percent_free = abs( ($system_memory_free_mb - $system_memory_total_mb) / abs($system_memory_total_mb) * 100 );
         $memory_percent_free = round( 100 - $memory_percent_free, 2);
	
	
    		// Output
    		if ( isset($system_info['operating_system']) ) {
    		echo '<span class="bitcoin"><b>Operating System:</b></span> <br /><span class="blue"> '.$system_info['operating_system'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['model']) || isset($system_info['hardware']) ) {
    			
    			if ( isset($system_info['model']) ) {
    			echo '<span class="bitcoin"><b>Model:</b></span> <span class="blue"> '.$system_info['model'].( isset($system_info['hardware']) ? ' ('.$system_info['hardware'].')' : '' ).'</span> <br />';
    			}
    			else {
    			echo '<span class="bitcoin"><b>Hardware:</b></span> <span class="blue"> '.$system_info['hardware'].'</span> <br />';
    			}
    		
    		}
    		
    		if ( isset($system_info['model_name']) ) {
    		echo '<span class="bitcoin"><b>CPU:</b></span> <span class="blue"> '.$system_info['model_name'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['uptime']) ) {
    		echo '<span class="bitcoin"><b>Uptime:</b></span> <span class="'.( preg_match("/0 days, 0 hours/i", $system_info['uptime']) ? 'red' : 'green' ).'"> '.$system_info['uptime'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['system_load']) ) {
    		echo '<span class="bitcoin"><b>Load:</b></span> <span class="'.( $system_load > 2 ? 'red' : 'green' ).'"> '.$system_info['system_load'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['system_temp']) ) {
    		echo '<span class="bitcoin"><b>Temperature:</b></span> <span class="'.( $system_temp > 79 ? 'red' : 'green' ).'"> '.$system_info['system_temp'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['memory_used_megabytes']) ) {
    		echo '<span class="bitcoin"><b>Used Memory (*not* including buffers / cache):</b></span> <br /><span class="'.( $system_info['memory_used_percent'] > 91 ? 'red' : 'green' ).'"> '.round($system_info['memory_used_megabytes'] / 1000, 4).' Gigabytes <span class="black">('.number_format($system_info['memory_used_megabytes'], 2, '.', ',').' Megabytes / '.$system_info['memory_used_percent'].'%)</span></span> <br />';
    		}
    		
    		if ( isset($system_info['free_partition_space']) ) {
    		echo '<span class="bitcoin"><b>Free Disk Space:</b></span> <span class="'.( $system_free_space_mb < 500 ? 'red' : 'green' ).'"> '.round($system_free_space_mb / 1000000, 4).' Terabytes <span class="black">('.number_format($system_free_space_mb / 1000, 2, '.', ',').' Gigabytes)</span></span> <br />';
    		}
    		
    		if ( isset($system_info['portfolio_cache']) ) {
    		echo '<span class="bitcoin"><b>Portfolio Cache Size:</b></span> <span class="'.( $portfolio_cache_size_mb > 10000 ? 'red' : 'green' ).'"> '.round($portfolio_cache_size_mb / 1000, 4).' Gigabytes <span class="black">('.number_format($portfolio_cache_size_mb, 2, '.', ',').' Megabytes)</span></span> <br />';
    		}
    		
    		if ( isset($system_info['software']) ) {
    		echo '<span class="bitcoin"><b>Software:</b></span> <span class="blue"> '.$system_info['software'].'</span> <br />';
    		}
    		
    		
    		?></div>
	
	<p class='bitcoin' style='font-weight: bold;'>Charts may take awhile to update with the latest data.</p>	
    		
	
	<div class='red' id='system_charts_error'></div>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='system_stats_chart_1'>
	
	<span class='chart_loading' style='color: <?=$app_config['power_user']['charts_text']?>;'> &nbsp; Loading chart #1 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div style='display: inline;'></div></div>
	
	</div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart-js.php?type=system&key=1");
	});
	
	</script>
	
	
	<br/><br/><br/>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='system_stats_chart_2'>
	
	<span class='chart_loading' style='color: <?=$app_config['power_user']['charts_text']?>;'> &nbsp; Loading chart #2 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div style='display: inline;'></div></div>
	
	</div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart-js.php?type=system&key=2");
	});
	
	</script>
		
	
			    
			    
</div> <!-- full_width_wrapper END -->




		    
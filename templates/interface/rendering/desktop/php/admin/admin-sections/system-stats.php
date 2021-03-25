<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='full_width_wrapper'>
	
				
	
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
    		
    		$system_load_redline = ( $system_info['cpu_threads'] > 1 ? ($system_info['cpu_threads'] * 2) : 2 );
         
         
         if ( substr($system_info['uptime'], 0, 6) == '0 days' ) {
         $system_alerts['uptime'] = 'Low uptime';
         }
	
         if ( $system_load > $system_load_redline ) {
         $system_alerts['system_load'] = 'High CPU load';
         }
	
         if ( $system_temp > 79 ) {
         $system_alerts['system_temp'] = 'High temperature';
         }
	
         if ( $system_info['memory_used_percent'] > 91 ) {
         $system_alerts['memory_used_megabytes'] = 'High memory usage';
         }
	
         if ( $system_free_space_mb < 500 ) {
         $system_alerts['free_partition_space'] = 'High disk storage usage';
         }
	
         if ( $portfolio_cache_size_mb > 10000 ) {
         $system_alerts['portfolio_cache'] = 'High app cache disk storage usage';
         }
         
         
         // Red UI nav, with info bubble too
         if ( sizeof($system_alerts) > 0 ) {
         ?>
         <script>
         
         $('#system_stats_admin_link a').addClass("red_background");
         document.getElementById('system_stats_admin_link_info').style.display = 'inline';

			var system_stats_admin_link_info_content = '<h5 class="red tooltip_title">System Stats Alerts</h5>'
			
			<?php
			foreach ( $system_alerts as $alert_key => $alert_value ) {
			?>
			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;"><span class="red"><?=snake_case_to_name($alert_key)?>:</span> <?=$alert_value?></p>'
			<?php
			}
			?>
			
			+'';
		
		
			$('#system_stats_admin_link_info').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: system_stats_admin_link_info_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
			
  
         </script>
         <?php
         }
	
	
    		// Output
    		if ( isset($system_info['operating_system']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Operating System:</b></span> <span class="blue"> '.$system_info['operating_system'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['model']) || isset($system_info['hardware']) ) {
    			
    			if ( isset($system_info['model']) ) {
    			echo '<div class="sys_stats"><span class="bitcoin"><b>Model:</b></span> <span class="blue"> '.$system_info['model'].( isset($system_info['hardware']) ? ' ('.$system_info['hardware'].')' : '' ).'</span> </div>';
    			}
    			else {
    			echo '<div class="sys_stats"><span class="bitcoin"><b>Hardware:</b></span> <span class="blue"> '.$system_info['hardware'].'</span> </div>';
    			}
    		
    		}
    		
    		if ( isset($system_info['model_name']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>CPU:</b></span> <span class="blue"> '.$system_info['model_name'].'</span> ' . ( $system_info['cpu_threads'] > 0 ? '(' . $system_info['cpu_threads'] . ' threads)' : '' ) . ' </div>';
    		}
    		
    		if ( isset($system_info['uptime']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Uptime:</b></span> <span class="'.( isset($system_alerts['uptime']) ? 'red' : 'green' ).'"> '.$system_info['uptime'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['system_load']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Load:</b></span> <span class="'.( isset($system_alerts['system_load']) ? 'red' : 'green' ).'"> '.$system_info['system_load'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['system_temp']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Temperature:</b></span> <span class="'.( isset($system_alerts['system_temp']) ? 'red' : 'green' ).'"> '.$system_info['system_temp'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['memory_used_megabytes']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Used Memory (*not* including buffers / cache):</b></span> <span class="'.( isset($system_alerts['memory_used_megabytes']) ? 'red' : 'green' ).'"> '.round($system_info['memory_used_megabytes'] / 1000, 4).' Gigabytes <span class="black">('.number_format($system_info['memory_used_megabytes'], 2, '.', ',').' Megabytes / '.$system_info['memory_used_percent'].'%)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['free_partition_space']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Free Disk Space:</b></span> <span class="'.( isset($system_alerts['free_partition_space']) ? 'red' : 'green' ).'"> '.round($system_free_space_mb / 1000000, 4).' Terabytes <span class="black">('.number_format($system_free_space_mb / 1000, 2, '.', ',').' Gigabytes)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['portfolio_cache']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Portfolio Cache Size:</b></span> <span class="'.( isset($system_alerts['portfolio_cache']) ? 'red' : 'green' ).'"> '.round($portfolio_cache_size_mb / 1000, 4).' Gigabytes <span class="black">('.number_format($portfolio_cache_size_mb, 2, '.', ',').' Megabytes)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['software']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Software:</b></span> <span class="blue"> '.$system_info['software'].'</span> </div>';
    		}
    		
    		
    		?></div>
    		
    		
   <ul>
	
	<li class='bitcoin' style='font-weight: bold;'>System load is always (roughly) MULTIPLIED by the number of threads.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>See Admin Config POWER USER section, to adjust vertical axis scales.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>Charts may take awhile to update with the latest data.</li>	
   
   </ul>
	
	
	<?php
	$all_chart_rebuild_min_max = explode(',', $app_config['developer']['all_chart_rebuild_min_max']);
	?>
	
	<p class='sys_stats red' style='font-weight: bold;'>*The most recent days in the 'ALL' chart MAY ALWAYS show a spike on the cron runtime seconds (ON SLOWER MACHINES, from re-building the 'ALL' chart every <?=$all_chart_rebuild_min_max[0]?> to <?=$all_chart_rebuild_min_max[1]?> hours), until the 'ALL' chart re-builds slowly average out only showing their own runtime data for older days.</p>		
    		
	
	<div class='red' id='system_charts_error'></div>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='system_stats_chart_1'>
	
	<span class='chart_loading' style='color: <?=$app_config['power_user']['charts_text']?>;'> &nbsp; Loading chart #1 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_message'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 1;
	include('templates/interface/rendering/desktop/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
	
	
	<br/><br/><br/>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='system_stats_chart_2'>
	
	<span class='chart_loading' style='color: <?=$app_config['power_user']['charts_text']?>;'> &nbsp; Loading chart #2 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_message'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 2;
	include('templates/interface/rendering/desktop/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
		
	
			    
			    
</div> <!-- full_width_wrapper END -->




		    
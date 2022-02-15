<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>

<div class='full_width_wrapper'>
	
				
	
    <div style='margin-bottom: 30px;'>
    
    <?php
       
         
         // Red UI nav, with info bubble too
         if ( is_array($system_warnings) && sizeof($system_warnings) > 0 ) {
         ?>
         <script>
         
         $('#sys_stats_admin_link a').addClass("red_background");
         document.getElementById('sys_stats_admin_link_info').style.display = 'inline';

			var sys_stats_admin_link_info_content = '<h5 class="red tooltip_title">System Stats Alerts</h5>'
			
			<?php
			foreach ( $system_warnings as $alert_key => $alert_val ) {
			?>
			+'<p class="coin_info" style="max-width: 600px; white-space: normal;"><span class="red"><?=$ct_gen->key_to_name($alert_key)?>:</span> <?=$alert_val?></p>'
			<?php
			}
			?>
			
			+'';
		
		
			$('#sys_stats_admin_link_info').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: sys_stats_admin_link_info_content,
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
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Uptime:</b></span> <span class="'.( isset($system_warnings['uptime']) ? 'red' : 'green' ).'"> '.$system_info['uptime'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['system_load']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Load:</b></span> <span class="'.( isset($system_warnings['system_load']) ? 'red' : 'green' ).'"> '.$system_info['system_load'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['system_temp']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Temperature:</b></span> <span class="'.( isset($system_warnings['system_temp']) ? 'red' : 'green' ).'"> '.$system_info['system_temp'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['memory_used_megabytes']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Used Memory (*not* including buffers / cache):</b></span> <span class="'.( isset($system_warnings['memory_used_percent']) ? 'red' : 'green' ).'"> '.round($system_info['memory_used_megabytes'] / 1000, 4).' Gigabytes <span class="black">('.number_format($system_info['memory_used_megabytes'], 2, '.', ',').' Megabytes / '.$system_info['memory_used_percent'].'%)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['free_partition_space']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Free Disk Space:</b></span> <span class="'.( isset($system_warnings['free_partition_space']) ? 'red' : 'green' ).'"> '.round($system_free_space_mb / 1000000, 4).' Terabytes <span class="black">('.number_format($system_free_space_mb / 1000, 2, '.', ',').' Gigabytes)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['portfolio_cookies']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Portfolio Cookies Size:</b></span> <span class="'.( isset($system_warnings['portfolio_cookies_size']) ? 'red' : 'green' ).'"> '.$ct_var->num_pretty( ($system_info['portfolio_cookies'] / 1000) , 2).' Kilobytes</span></span> </div>';
    		}
    		
    		if ( isset($system_info['portfolio_cache']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Portfolio Cache Size:</b></span> <span class="'.( isset($system_warnings['portfolio_cache_size']) ? 'red' : 'green' ).'"> '.round($portfolio_cache_size_mb / 1000, 4).' Gigabytes <span class="black">('.number_format($portfolio_cache_size_mb, 2, '.', ',').' Megabytes)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['software']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Software:</b></span> <span class="blue"> '.$system_info['software'].'</span> </div>';
    		}
    		
    		
    		?>
    		
    		</div>
    		
    		
   <ul>
	
	<li class='bitcoin' style='font-weight: bold;'>Charts may take awhile to update with the latest data.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>See Admin Config POWER USER section, to adjust vertical axis scales.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>System load is always (roughly) MULTIPLIED by the number of threads.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>"CRON Core Runtime Seconds" DOES NOT INCLUDE plugin runtime (for stability of CORE runtime, in case <i>custom</i> plugins are buggy and crash).</li>	
   
   </ul>
	
	
	<?php
	$all_chart_rebuild_min_max = explode(',', $ct_conf['dev']['all_chart_rebuild_min_max']);
	?>
	
	<p class='sys_stats red' style='font-weight: bold;'>*The "Portfolio Cookies Size" telemetry data above <i>is not tracked in the system charts, because it's ONLY available in the user interface runtime (NOT the cron job runtime)</i>.</p>			
	
	<p class='sys_stats red' style='font-weight: bold;'>*The "CRON Core Runtime Seconds" telemetry data <i>may vary per time period chart</i> (10D / 2W / 1M / 1Y / etc etc), as time period charts are updated during CRON runtimes, and some time period charts (including asset price charts) can take longer to update than others. Additionally, recent "ALL" chart data may show higher CRON runtimes, and average out in older data.</p>		
    		
	
	<div class='red' id='system_charts_error'></div>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='sys_stats_chart_1'>
	
	<span class='chart_loading' style='color: <?=$ct_conf['power']['charts_text']?>;'> &nbsp; Loading chart #1 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 1;
	include('templates/interface/desktop/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
	
	
	<br/><br/><br/>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='sys_stats_chart_2'>
	
	<span class='chart_loading' style='color: <?=$ct_conf['power']['charts_text']?>;'> &nbsp; Loading chart #2 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 2;
	include('templates/interface/desktop/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
		
	
			    
			    
</div> <!-- full_width_wrapper END -->




		    
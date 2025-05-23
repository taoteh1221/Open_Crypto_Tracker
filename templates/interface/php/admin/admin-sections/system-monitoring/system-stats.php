<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

				
	
    <div style='margin-bottom: 30px;'>
    
    <?php
       
         
         // Red UI nav, with info bubble too
         if ( is_array($ct['system_warnings']) && sizeof($ct['system_warnings']) > 0 ) {
         ?>
         
         <script>
         
         $('.sys_stats_admin_link a', parent.document).addClass("red_background");
         
         $(".sys_stats_admin_link_info").css('display','inline');

			var sys_stats_admin_link_info_content = '<h5 class="red tooltip_title">System Stats Alerts</h5>'
			
			<?php
			foreach ( $ct['system_warnings'] as $alert_key => $alert_val ) {
			?>
			+'<p class="coin_info" style=" white-space: normal;"><span class="red"><?=$ct['gen']->key_to_name($alert_key)?>:</span> <?=$alert_val?></p>'
			<?php
			}
			?>
			
			+'';
		
		
			$('.sys_stats_admin_link_info', parent.document).balloon({
			html: true,
			position: "top",
  			classname: 'balloon-tooltips',
			contents: sys_stats_admin_link_info_content,
			css: balloon_css()
			});
			
  
         </script>
         
         <?php
         }
	
	
    		// Output
    		if ( isset($ct['system_info']['operating_system']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Operating System:</b></span> <span class="blue"> '.$ct['system_info']['operating_system'].'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['distro_name']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Distro:</b></span> <span class="blue"> '.$ct['system_info']['distro_name'].( isset($ct['system_info']['distro_version']) ? ' ' . $ct['system_info']['distro_version'] : '' ).'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['model']) || isset($ct['system_info']['hardware']) ) {
    			
    			if ( isset($ct['system_info']['model']) ) {
    			echo '<div class="sys_stats"><span class="bitcoin"><b>Model:</b></span> <span class="blue"> '.$ct['system_info']['model'].( isset($ct['system_info']['hardware']) ? ' ('.$ct['system_info']['hardware'].')' : '' ).'</span> </div>';
    			}
    			else {
    			echo '<div class="sys_stats"><span class="bitcoin"><b>Hardware:</b></span> <span class="blue"> '.$ct['system_info']['hardware'].'</span> </div>';
    			}
    		
    		}
    		
    		if ( isset($ct['system_info']['model_name']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>CPU:</b></span> <span class="blue"> '.$ct['system_info']['model_name'].'</span> ' . ( $ct['system_info']['cpu_threads'] > 0 ? '(' . $ct['system_info']['cpu_threads'] . ' threads / cores)' : '' ) . ' </div>';
    		}
    		elseif ( isset($ct['system_info']['cpu_threads']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>CPU:</b></span> <span class="blue"> '.$ct['system_info']['cpu_threads'].' threads / cores</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['software']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Server:</b></span> <span class="blue"> '.$ct['system_info']['software'].'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['portfolio_cookies']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Server Cookies Size:</b></span> <span class="'.( isset($ct['system_warnings']['portfolio_cookies_size']) ? 'red' : 'green' ).'"> '.$ct['var']->num_pretty( ($ct['system_info']['portfolio_cookies'] / 1000) , 2).' Kilobytes</span> <span class="black">(~'.round( abs( ($ct['system_info']['portfolio_cookies'] / 1000) / abs(8.00) * 100 ) , 2).'% of <i>average</i> server header size limit [8 kilobytes])</span></span> &nbsp;<img class="tooltip_style_control server_header_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" /> </div>';
    		}
    		
    		if ( isset($ct['system_info']['uptime']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>System Uptime:</b></span> <span class="'.( isset($ct['system_warnings']['uptime']) ? 'red' : 'green' ).'"> '.$ct['system_info']['uptime'].'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['system_load']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>System Load:</b></span> <span class="'.( isset($ct['system_warnings']['system_load']) ? 'red' : 'green' ).'"> '.$ct['system_info']['system_load'].'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['system_temp']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>System Temperature:</b></span> <span class="'.( isset($ct['system_warnings']['system_temp']) ? 'red' : 'green' ).'"> '.$ct['system_info']['system_temp'].' <span class="black">('.round( ($system_temp * 9 / 5 + 32), 2).'° Fahrenheit)</span></span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['memory_used_megabytes']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>USED Memory (*not* including buffers / cache):</b></span> <span class="'.( isset($ct['system_warnings']['memory_used_percent']) ? 'red' : 'green' ).'"> '.round($ct['system_info']['memory_used_megabytes'] / 1000, 4).' Gigabytes <span class="black">('.number_format($ct['system_info']['memory_used_megabytes'], 2, '.', ',').' Megabytes / '.$ct['system_info']['memory_used_percent'].'%)</span></span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['free_partition_space']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>FREE Disk Space:</b></span> <span class="'.( isset($ct['system_warnings']['free_partition_space']) ? 'red' : 'green' ).'"> '.round($system_free_space_mb / 1000000, 4).' Terabytes <span class="black">('.number_format($system_free_space_mb / 1000, 2, '.', ',').' Gigabytes)</span></span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['portfolio_cache']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Open Crypto Tracker Cache Size:</b></span> <span class="'.( isset($ct['system_warnings']['portfolio_cache_size']) ? 'red' : 'green' ).'"> '.round($portfolio_cache_size_mb / 1000, 4).' Gigabytes <span class="black">('.number_format($portfolio_cache_size_mb, 2, '.', ',').' Megabytes)</span></span> </div>';
    		}
    		
    		
    		?>
    
 
            <script>
            
            
            var server_header_defaults_content = '<h5 class="yellow tooltip_title">Average Server Header Size Limits</h5>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;">Web servers have a pre-set header size limit (which can be adjusted within it\'s own server configuration), which varies depending on the server software you are using.</p>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;"><span class="bitcoin">IF THIS APP GOES OVER THOSE HEADER SIZE LIMITS, IT WILL CRASH!</span></p>'
            
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;"><span class="bitcoin">STANDARD SERVER HEADER SIZE LIMITS (IN KILOBYTES)...</span><br />Apache: 8kb<br />NGINX: 4kb - 8kb<br />IIS: 8kb - 16kb<br />Tomcat: 8kb - 48kb</p>';
            		
            		
            		
            			$('.server_header_defaults').balloon({
            			html: true,
            			position: "bottom",
              			classname: 'balloon-tooltips',
            			contents: server_header_defaults_content,
            			css: balloon_css()
            			});
            			
            		
            		
            		
            
            
            </script> 

    		
    		</div>
    		
    		
   <ul>
	
	
	<li class='bitcoin' style='font-weight: bold;'>Charts may take awhile to update with the latest data.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>See Admin Config POWER USER section, to adjust vertical axis scales.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>System load is always (roughly) MULTIPLIED by the number of threads.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>"CRON Core Runtime Seconds" DOES NOT INCLUDE plugin runtime (for stability of CORE runtime, in case <i>custom</i> plugins are buggy and crash).</li>	
   
   </ul>
	
	
	<?php
	$all_chart_rebuild_min_max = explode(',', $ct['conf']['power']['light_chart_all_rebuild_min_max']);
	?>
	
	<p class='sys_stats red' style='font-weight: bold;'>*The "Server Cookies Size" telemetry data above <i>is not tracked in the system charts, because it's ONLY available in the user interface runtime (NOT the cron job runtime)</i>.</p>			
	
	<p class='sys_stats red' style='font-weight: bold;'>*The "CRON Core Runtime Seconds" telemetry data <i>may vary per time period chart</i> (10D / 2W / 1M / 1Y / etc etc), as time period charts are updated during CRON runtimes, and some time period charts (including asset price charts) can take longer to update than others. Additionally, recent "ALL" chart data may show higher CRON runtimes, and average out in older data.</p>		
    		
	
	<div class='red' id='system_charts_error'></div>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='sys_stats_chart_1'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading chart #1 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 1;
	include('templates/interface/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
	
	
	<br/><br/><br/>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='sys_stats_chart_2'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading chart #2 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 2;
	include('templates/interface/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
		
	
		    
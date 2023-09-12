<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


?>

	
<h2 class='bitcoin page_title'>News Feeds</h2>
	            

<div class='full_width_wrapper'>
			

			<p style='margin-top: 25px; margin-bottom: 15px;'><?=$ct['gen']->start_page_html('news')?></p>			
			<?php
			$news_feed_cache_min_max = explode(',', $ct['conf']['power']['news_feed_cache_min_max']);
			?>
	
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>Setting this page as the 'start page' (top left) will save your vertical scroll position during reloads.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>RSS feed data is cached (randomly) between <?=$news_feed_cache_min_max[0]?> / <?=$news_feed_cache_min_max[1]?> minutes for quicker load times.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>To see the date / time an entry was published, hover over it.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>Entries are sorted newest to oldest.</li>	
   
   </ul>
			


	<p style='margin-top: 25px;'><button class="modal_style_control show_feed_settings force_button_style">Select News Feeds</button></p>
	
	
	<div class='' id="show_feed_settings">
	
		
		<h4 style='display: inline;'>Select News Feeds</h4>
	
				<span style='z-index: 99999;' class='red countdown_notice_modal'></span>
	
	<br clear='all' />
	<br clear='all' />
	
	<p class='red'>*News feeds are not activated by default to increase page loading speed / responsiveness. It's recommended to avoid activating too many news feeds at the same time, to keep your page load times quick.</p>
	
	<p class='red'>Low memory devices (Raspberry Pi / Pine64 / etc) MAY CRASH #IF YOU SHOW TOO MANY NEWS FEEDS#.
	     
		<img class='tooltip_style_control' id='news_raspi_crash' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative; left: -5px;' /> </p>
		
	 <script>
	 
			var news_raspi_crash = '<h5 class="align_center red tooltip_title">Low Memory Devices Crashing</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">If your low memory device (Raspberry PI / Pine64 / etc) crashes when you select too many news feeds OR charts, you may need to restart your device, and then delete all cookies in your browser related to the web domain you run the app from (before using the app again).</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">For the more technically-inclined, try decreasing "MaxRequestWorkers" in Apache\'s prefork configuration file (10 maximum is the best for low memory devices, AND "MaxSpareServers" above it MUST BE SET EXACTLY THE SAME #OR YOUR SYSTEM MAY STILL CRASH#), to help stop the web server from crashing under heavier loads. <span class="red">ALWAYS BACKUP THE CURRENT SETTINGS FIRST, IN CASE IT DOESN\'T WORK.</span></p>'
			
			
			+'<p> </p>';

	
		
			$('#news_raspi_crash').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: news_raspi_crash,
			css: {
					fontSize: "<?=$set_font_size?>em",
					minWidth: "350px",
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
		 
	    </p>
	
	<p class='bitcoin'>You can enable "Use cookies to save data" on the Settings page <i>before activating your news feeds</i>, if you want them to stay activated between browser sessions.</p>
	
			
	<div> &nbsp; </div>
	
	<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
	<p><button class='force_button_style' onclick='
	$("#coin_amnts").submit();
	'>Update Selected News Feeds</button></p>
	
	<div> &nbsp; </div>
	
	<p><input type='checkbox' onclick='
	
		select_all(this, "activate_feeds");
		
		if ( this.checked == false ) {
		$("#show_feeds").val("");
		}
		
	' /> <b>Select / Unselect All</b> &nbsp;&nbsp; <span class='bitcoin'>(if "loading news feeds" notice freezes, check / uncheck this box, then click "Update Selected News Feeds")</span></p>
		
		<form id='activate_feeds' name='activate_feeds'>
		
	<div class='long_list_start list_start_black'> &nbsp; </div>
	<?php
	
	$zebra_stripe = 'long_list_odd';
	foreach ( $ct['conf']['news_feeds'] as $feed ) {
	
	// We avoid using array keys for end user config editing UX, BUT STILL UNIQUELY IDENTIFY EACH FEED
	$feed_id = $ct['gen']->digest($feed['title'], 5);
				
	?>
	
		<div class='<?=$zebra_stripe?> long_list <?=( $last_rendered != $show_asset ? 'activate_chart_sections' : '' )?>'>
			
				
				<input type='checkbox' value='<?=$feed_id?>' onchange='feed_toggle(this);' <?=( in_array("[".$feed_id."]", $sel_opt['show_feeds']) ? 'checked' : '' )?> /> <?=$feed['title']?>
	
	
			</div>
				
	<?php
	    
		 		if ( $zebra_stripe == 'long_list_odd' ) {
			 	$zebra_stripe = 'long_list_even';
			 	}
			 	else {
			 	$zebra_stripe = 'long_list_odd';
			 	}
		 	
	}
	    
	?>
	<div class='long_list_end list_end_black'> &nbsp; </div>
	
		</form>
	
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<p><button class='force_button_style' onclick='
		$("#coin_amnts").submit();
		'>Update Selected News Feeds</button></p>
		
	</div>
	
	
	<script>
	
	modal_windows.push('.show_feed_settings'); // Add to modal window tracking (for closing all dynaimically on app reloads)   
	
	$('.show_feed_settings').modaal({  
	content_source: '#show_feed_settings'
	});
  	
	</script>
	
	
	<?php
	
	if ( isset($sel_opt['show_feeds'][0]) && $sel_opt['show_feeds'][0] != '' ) {
	 
	 $chosen_feeds = array_map( array($ct['var'], 'strip_brackets') , $sel_opt['show_feeds']);
	 
	    if ( is_array($chosen_feeds) && sizeof($chosen_feeds) > 0 ) {
	    $batched_feeds_loops_max = ceil( sizeof($chosen_feeds) / $ct['conf']['power']['news_feed_batched_max'] );
	    }
	    else {
	    $batched_feeds_loops_max = 0;
	    }
	 
	 // Defaults before looping
	 $all_feeds_added = 0;
	 $batched_feeds_added = 0;
	 $batched_feeds_loops_added = 0;
	 $batched_feeds_keys = null;

    
    	// Already alphabetically sorted and pruned of stale entries in app init routines, so we just loop without filters
    	foreach($chosen_feeds as $chosen_feed_hash) {
    		
    		if ( $batched_feeds_loops_added < $batched_feeds_loops_max ) {
				
			$batched_feeds_added = $batched_feeds_added + 1;
			$batched_feeds_keys .= $chosen_feed_hash . ',';
			$all_feeds_added = $all_feeds_added + 1;
			
				if ( $batched_feeds_added >= $ct['conf']['power']['news_feed_batched_max'] || $all_feeds_added >= sizeof($chosen_feeds) ) {
				$batched_feeds_keys = rtrim($batched_feeds_keys,',');
				?>
		
					<div id='rss_feeds_<?=$batched_feeds_loops_added?>'>
					
					
						<fieldset class='subsection_fieldset'>
						
						<legend class='subsection_legend'> <strong>Batch-loading <?=$batched_feeds_added?> news feeds...</strong> </legend>
							<img class='' src="templates/interface/media/images/auto-preloaded/loader.gif" height='<?=round($set_ajax_loading_size * 50)?>' alt="" style='vertical-align: middle;' />
						</fieldset>
					
					</div>
					
					<script>
	
					// Load AFTER page load, for quick interface loading
					$(document).ready(function(){
						
						$("#rss_feeds_<?=$batched_feeds_loops_added?>").load("ajax.php?type=rss&feeds=<?=$batched_feeds_keys?>&theme=<?=$sel_opt['theme_selected']?>", function(responseTxt, statusTxt, xhr){
							
							if(statusTxt == "success") {
								
								<?php
								$feeds_array = explode(',', $batched_feeds_keys);
								foreach ($feeds_array as $feed_hash) {
								?>
								feeds_loaded.push("<?=$feed_hash?>");
								<?php
								}
								?>
							 
							feeds_loading_check();
							
							}
							else if(statusTxt == "error") {
								
							$("#rss_feeds_<?=$batched_feeds_loops_added?>").html("<fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong class='bitcoin'>ERROR Batch-loading <?=$batched_feeds_added?> news feeds...</strong> </legend><span class='red'>" + xhr.status + ": " + xhr.statusText + "</span></fieldset>");
								
								<?php
								$feeds_array = explode(',', $batched_feeds_keys);
								foreach ($feeds_array as $feed_hash) {
								?>
								feeds_loaded.push("<?=$feed_hash?>");
								<?php
								}
								?>
							 
							feeds_loading_check();
							
							}
						
						});
	
					});
						
					</script>
		
		
				<?php
				// Reset
				$batched_feeds_added = 0;
				$batched_feeds_keys = null;
				$batched_feeds_loops_added = $batched_feeds_loops_added + 1;
				}


    		}
    
    	}
		
		
	?>
	
	<?php
	}
	else {
	?>
	
	<div class='align_center' style='min-height: 100px;'>
	
		<p><img src='templates/interface/media/images/favicon.png' alt='' class='image_border' /></p>
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>Click the "Select News Feeds" button (top left) to add news feeds.</p>
	</div>
	
	<?php
	}
	?>
		    
	
</div> <!-- full_width_wrapper END -->



			
			
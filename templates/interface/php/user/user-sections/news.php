<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

	
<h2 class='bitcoin page_title'>News Feeds</h2>
	            

<div class='full_width_wrapper'>
			

	<p style='margin-top: 0.5em; margin-bottom: 2em;'>
	
	<?=$ct['gen']->start_page_html('news')?>
	
			&nbsp; &nbsp; <span class='blue' style='font-weight: bold;'>App Reload:</span> <select title='Auto-Refresh MAY NOT WORK properly on mobile devices (phone / laptop / tablet / etc), or inactive tabs.' class='browser-default custom-select' name='select_auto_refresh' id='select_auto_refresh' onchange='
			 reload_time = this.value;
			 auto_reload();
			 '>
				<option value='0'> Manually </option>
				<option value='300' <?=( $_COOKIE['coin_reload'] == '300' ? 'selected' : '' )?>> 5 Minutes </option>
				<option value='600' <?=( $_COOKIE['coin_reload'] == '600' ? 'selected' : '' )?>> 10 Minutes </option>
				<option value='900' <?=( $_COOKIE['coin_reload'] == '900' ? 'selected' : '' )?>> 15 Minutes </option>
				<option value='1800' <?=( $_COOKIE['coin_reload'] == '1800' ? 'selected' : '' )?>> 30 Minutes </option>
			</select> 
			
			&nbsp; <span id='reload_notice' class='red'></span>		
		
		
	         </p>			
	         
	         
			<?php
			$news_feed_cache_min_max = explode(',', $ct['conf']['news']['news_feed_cache_min_max']);
               // Cleanup
               $news_feed_cache_min_max = array_map('trim', $news_feed_cache_min_max);
			?>
	
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>Setting this page as the 'start page' (top left) will save your vertical scroll position during reloads.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>RSS feed data is cached (randomly) between <?=$news_feed_cache_min_max[0]?> / <?=$news_feed_cache_min_max[1]?> minutes for quicker load times.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>To see the date / time an entry was published, hover over it.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>Entries are sorted newest to oldest.</li>	
   
   </ul>
			


	<p style='margin: 25px;'><button class="modal_style_control show_feed_settings force_button_style">Select News Feeds</button></p>
	
	
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
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">For the more technically-inclined, try decreasing "MaxRequestWorkers" in Apache\'s prefork configuration file (10 maximum is the best for low memory devices, AND "MaxSpareServers" above it MUST BE SET EXACTLY THE SAME #OR YOUR SYSTEM MAY STILL CRASH#), to help stop the app server from crashing under heavier loads. <span class="red">ALWAYS BACKUP THE CURRENT SETTINGS FIRST, IN CASE IT DOESN\'T WORK.</span></p>'
			
			
			+'<p> </p>';

	
		
			$('#news_raspi_crash').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: news_raspi_crash,
			css: balloon_css()
			});
		
		 </script>
		 
	    </p>
	
	<div> &nbsp; </div>
	
	<p><input type='checkbox' onclick='
	
		select_all(this, "activate_feeds");
		
		if ( this.checked == false ) {
		localStorage.setItem(show_feeds_storage,  "");
		}
		
	' /> <b>Select / Unselect All</b> &nbsp;&nbsp; <span class='bitcoin'>(if "loading news feeds" notice freezes, check / uncheck this box)</span></p>
		
		<form id='activate_feeds' name='activate_feeds'>
		
	<div class='long_list_start list_start_black'> &nbsp; </div>
	<?php
	
	$zebra_stripe = 'long_list_odd';
	foreach ( $ct['conf']['news']['feeds'] as $feed ) {
	
	// We avoid using array keys for end user config editing UX, BUT STILL UNIQUELY IDENTIFY EACH FEED
	$feed_id = $ct['gen']->digest($feed['title'], 5);
				
	?>
	
		<div class='<?=$zebra_stripe?> long_list <?=( $last_rendered != $show_asset ? 'activate_chart_sections' : '' )?>'>
			
				
				<input type='checkbox' id='<?=$feed_id?>' value='<?=$feed_id?>' onchange='feed_toggle(this);' /> <?=$feed['title']?>
				
				<script>
				if ( str_search_count( localStorage.getItem(show_feeds_storage) , '[<?=$feed_id?>]') > 0 ) {
				document.getElementById('<?=$feed_id?>').checked = true;
				}
				</script>
	
	
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
		
	</div>
	
	
	<script>
	
	modal_windows.push('.show_feed_settings'); // Add to modal window tracking (for closing all dynaimically on app reloads)   
	
	$('.show_feed_settings').modaal({  
	content_source: '#show_feed_settings'
	});
  	
	
	if ( feeds_num > 0 ) {
	     
	var chosen_feeds = str_to_array( localStorage.getItem(show_feeds_storage) );
	     
	var batched_feeds_loops_max = Math.ceil(feeds_num / news_feed_batched_maximum);
	
	// Defaults before looping
	var all_feeds_added = 0;
	var batched_feeds_added = 0;
	var batched_feeds_loops_added = 0;
	var all_feeds_added = null;
  	
  	
         	chosen_feeds.forEach(function(feed_key) {
         	     
         	     
         		if ( batched_feeds_loops_added < batched_feeds_loops_max ) {
     				
     		batched_feeds_added = batched_feeds_added + 1;
     			
     		all_feeds_added = all_feeds_added + 1;
     			
     		batched_feeds_keys = batched_feeds_keys + feed_key + ',';
     		
     			
     				if ( batched_feeds_added >= news_feed_batched_maximum || all_feeds_added >= feeds_num ) {
     				     
     				batched_feeds_keys = batched_feeds_keys.replace(/,+$/, ''); // Remove comma at end
     		
     					document.write("<div id='rss_feeds_" + batched_feeds_loops_added + "'>");
     					
     					
     						document.write("<fieldset class='subsection_fieldset'>");
     						
     						document.write("<legend class='subsection_legend'> <strong>Batch-loading " + batched_feeds_added + " news feeds...</strong> </legend>");
     							document.write("<img class='' src='templates/interface/media/images/auto-preloaded/loader.gif' height='<?=round($set_ajax_loading_size * 50)?>' alt='' style='vertical-align: middle;' />");
     							
     						document.write("</fieldset>");
     					
     					document.write("</div>");
         	                 
         	                 
         	                 //console.log('batched_feeds_keys = ' + batched_feeds_keys);
     								
     				  var feeds_array = str_to_array(batched_feeds_keys, ',', false);
     				  
     				  
     						$("#rss_feeds_" + batched_feeds_loops_added).load("ajax.php?type=rss&feeds=" + batched_feeds_keys + "&theme=" + theme_selected, function(responseTxt, statusTxt, xhr){
     							
     							if(statusTxt == "success") {
     								
     								feeds_array.forEach(function(feed_hash) {
     								feeds_loaded.push(feed_hash);
         	                                   //console.log('feed_hash = ' + feed_hash);
     								});
     								
         	                              //console.log('feeds_array.length = ' + feeds_array.length);
     								
         	                              //console.log('feeds_loaded.length = ' + feeds_loaded.length);
     							 
     							feeds_loading_check();
     							
     							}
     							else if(statusTxt == "error") {
     								
     							$("#rss_feeds_" + batched_feeds_loops_added).html("<fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong class='bitcoin'>ERROR Batch-loading " + batched_feeds_added + " news feeds...</strong> </legend><span class='red'>" + xhr.status + ": " + xhr.statusText + "</span></fieldset>");
     								
     								feeds_array.forEach(function(feed_hash) {
     								feeds_loaded.push(feed_hash);
         	                                   //console.log('feed_hash = ' + feed_hash);
     								});
     								
         	                              //console.log('feeds_array.length = ' + feeds_array.length);
     								
         	                              //console.log('feeds_loaded.length = ' + feeds_loaded.length);
     							 
     							feeds_loading_check();
     							
     							}
     						
     						});
     						
     		
     				// Reset
     				batched_feeds_added = 0;
     				batched_feeds_loops_added = batched_feeds_loops_added + 1;
     				batched_feeds_keys = '';
     				}
     
     
         		}
         
         
         	});
		
		
     }
     else {
	
	document.write("<div class='align_center' style='min-height: 100px;'>");
	
		document.write("<p><img src='templates/interface/media/images/favicon.png' alt='' class='image_border' /></p>");
		document.write("<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>Click the \"Select News Feeds\" button (top left) to add news feeds.</p>");
		
	document.write("</div>");
	
	}
	
	</script>
	
	
	
</div> <!-- full_width_wrapper END -->



			
			
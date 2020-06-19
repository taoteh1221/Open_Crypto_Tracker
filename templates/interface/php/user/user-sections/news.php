<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>

			
			<h4 style='display: inline;'>News</h4>
				
				<span class='red countdown_notice'></span>
			

			<p style='margin-top: 15px; margin-bottom: 15px;'><?=start_page_html('news')?></p>			
			<?php
			$news_feeds_cache_min_max = explode(',', $app_config['power_user']['news_feeds_cache_min_max']);
			?>
			<p class='bitcoin' style='font-weight: bold;'>RSS feed data is cached (randomly) between <?=$news_feeds_cache_min_max[0]?> / <?=$news_feeds_cache_min_max[1]?> minutes for quicker load times. Entries are sorted newest to oldest. To see the date an entry was published, hover over it.</p>
			


	<p><button class="show_feed_settings force_button_style">Select News Feeds</button></p>
	
	
	<div id="show_feed_settings">
	
		
		<h4 style='display: inline;'>Select News Feeds</h4>
	
				<span style='z-index: 99999;' class='red countdown_notice'></span>
	
	<br clear='all' />
	<br clear='all' />
	
	<p class='red'>*News feeds are not activated by default to increase page loading speed / responsiveness. It's recommended to avoid activating too many news feeds at the same time, to keep your page load times quick. You can enable "Use cookies to save data" on the Settings page <i>before activating your news feeds</i>, if you want them to stay activated between browser sessions.</p>
	
			
	<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
	<p><button class='force_button_style' onclick='
	$(".show_feed_settings").modaal("close");
	$("#coin_amounts").submit();
	'>Update Selected News Feeds</button></p>
	
	<p><input type='checkbox' onclick='
	
		selectAll(this, "activate_feeds");
		
		if ( this.checked == false ) {
		$("#show_feeds").val("");
		}
		
	' /> Select / Unselect All &nbsp;&nbsp; <span class='bitcoin'>(if "loading news feeds" notice freezes, check / uncheck this box, then click "Update Selected News Feeds")</span></p>
		
		<form id='activate_feeds' name='activate_feeds'>
		
	<?php
	
	$zebra_stripe = 'long_list_odd';
	foreach ( $app_config['power_user']['news_feeds'] as $feed ) {
	
	// We avoid using array keys for end user config editing UX, BUT STILL UNIQUELY IDENTIFY EACH FEED
	$feed_id = get_digest($feed['title'], 10);
				
	?>
	
		<div class='<?=$zebra_stripe?> long_list <?=( $last_rendered != $show_asset ? 'activate_chart_sections' : '' )?>'>
			
				
				<input type='checkbox' value='<?=$feed_id?>' onchange='feed_toggle(this);' <?=( in_array("[".$feed_id."]", $show_feeds) ? 'checked' : '' )?> /> <?=$feed['title']?>
	
	
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
	<div class='long_list_end' style='border-top: 2px solid black;'> &nbsp; </div>
	
		</form>
	
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<p><button class='force_button_style' onclick='
		$(".show_feed_settings").modaal("close");
		$("#coin_amounts").submit();
		'>Update Selected News Feeds</button></p>
		
	</div>
	
	
	<script>
	
	$('.show_feed_settings').modaal({
		content_source: '#show_feed_settings'
	});
  	
	</script>
	
	
	<?php
	if ( $show_feeds[0] != '' ) {
		
		

	 $chosen_feeds = array_map('strip_brackets', $show_feeds);
    
    	// $app_config['power_user']['news_feeds'] was already alphabetically sorted in app init routines, so we loop with it to maintain alphabetical order
    	foreach($app_config['power_user']['news_feeds'] as $feed) {
    		
		// We avoid using array keys for end user config editing UX, BUT STILL UNIQUELY IDENTIFY EACH FEED
    	$feed_id = get_digest($feed["title"], 10);
    
    		if ( isset($feed["title"]) && in_array($feed_id, $chosen_feeds) ) {
    		?>
    		
	
	<div id='rss_feed_<?=$feed_id?>'>
	
	
	    <fieldset class='subsection_fieldset'>
	    
	    <legend class='subsection_legend'> <strong><?=$feed["title"]?></strong> </legend>
	        <img src="templates/interface/media/images/loader.gif" height='50' alt="" style='vertical-align: middle;' />
	    </fieldset>
	
	</div>
	
	<script>
	
	$("#rss_feed_<?=$feed_id?>").load("ajax.php?type=rss&feed=<?=$feed_id?>", function(responseTxt, statusTxt, xhr){
		
    if(statusTxt == "success") {
	 window.feeds_loaded.push("<?=$feed_id?>");
	 feeds_loading_check(window.feeds_loaded);
    }
    else if(statusTxt == "error") {
    $("#rss_feed_<?=$feed_id?>").html("Error: " + xhr.status + ": " + xhr.statusText);
	 window.feeds_loaded.push("<?=$feed_id?>");
	 feeds_loading_check(window.feeds_loaded);
    }
    
    
  	});
  	
	</script>
	
    		<?php
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
		    
	
</div> <!-- max_1200px_wrapper END -->



			
			
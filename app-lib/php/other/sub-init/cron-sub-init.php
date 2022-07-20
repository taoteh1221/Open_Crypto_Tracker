<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Only need below logic during cron runtime
//////////////////////////////////////////////////////////////////
if ( $runtime_mode == 'cron' ) {

// Reset feed fetch telemetry 
$_SESSION[$fetched_feeds] = false;
    
    
    // Only run if charts / alerts has run for the first time already (for better new install UX)
    if ( file_exists($base_dir . '/cache/events/charts-first-run.dat') ) {
    
    	// Re-cache RSS feeds for faster UI runtimes later
    	foreach($ct_conf['power']['news_feed'] as $feed_item) {
    	    
    		if ( isset($feed_item["url"]) && trim($feed_item["url"]) != '' ) {
    	 	$ct_api->rss($feed_item["url"], 'no_theme', 0, true);
    	 	}
    	 	
    	}
	
    	// News feeds - new posts email
    	if ( $ct_conf['comms']['news_feed_email_freq'] > 0 ) {
    	$ct_gen->news_feed_email($ct_conf['comms']['news_feed_email_freq']);
    	}
	
    }
	

}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY LOGIC
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>
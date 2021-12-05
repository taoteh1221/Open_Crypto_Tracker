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
	
	// News feeds - new posts email
	if ( $ct_conf['power']['news_feed_email_freq'] > 0 ) {
	$ct_gen->news_feed_email($ct_conf['power']['news_feed_email_freq']);
	}

}
//////////////////////////////////////////////////////////////////
// END CRON-ONLY LOGIC
//////////////////////////////////////////////////////////////////

 
 ?>
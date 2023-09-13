<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


// Remove any stale cache files
$loop = ( is_array($plug_conf[$this_plug]['reminders']) ? sizeof($plug_conf[$this_plug]['reminders']) : 0 );
while ( file_exists( $ct['plug']->event_cache('alert-' . $loop . '.dat') ) ) {
unlink( $ct['plug']->event_cache('alert-' . $loop . '.dat') );
$loop = $loop + 1;
}
$loop = null;


foreach ( $plug_conf[$this_plug]['reminders'] as $key => $val ) {
	
// Clear any previous loop's $run_reminder var
$run_reminder = false;
	
$recurring_reminder_cache_file = $ct['plug']->event_cache('alert-' . $key . '.dat');

// Remove any leading zeros in do-not-disturb time format
$plug_conf[$this_plug]['do_not_dist']['on'] = ltrim($plug_conf[$this_plug]['do_not_dist']['on'], "0");
$plug_conf[$this_plug]['do_not_dist']['off'] = ltrim($plug_conf[$this_plug]['do_not_dist']['off'], "0");

// MD5 fingerprint digest of current settings / data of this reminder
$digest = md5($val['days'] . $val['message']);

	
	// Get cache data, and see if we need to flag a cache reset due to config changes
	// (which triggers sending a reminder for UX-sake, as we reset everything for this reminder)
	if ( file_exists($recurring_reminder_cache_file) ) {
	
	$cached_digest = trim( file_get_contents($recurring_reminder_cache_file) );
	
		// If user changed the settings / data for this reminder, flag a reset
		if ( !$cached_digest || $digest != $cached_digest ) {
		$run_reminder = true;
		}
	
	}
	else {
	$run_reminder = true;
	}
	
	
// DEBUGGING ONLY
//$ct['cache']->save_file( $ct['plug']->event_cache('debugging-' . $key . '.dat') , $digest );


// Recurring reminder time in minutes
// With offset, to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
$in_minutes = round( $ct['var']->num_to_str(1440 * $val['days']) + $ct['dev']['tasks_time_offset'] );


// Offset -1 anything 20 minutes or higher, so recurring reminder is triggered at same EXACT cron job interval consistently 
// (example: every 2 days at 12:00pm...NOT same cron job interval + 1, like 12:20pm / 12:40pm / etc)
$in_minutes_offset = ( $in_minutes >= 20 ? ($in_minutes - 1) : $in_minutes );

	
	// If it's time to send a reminder...
	if ( $run_reminder || $ct['cache']->update_cache($recurring_reminder_cache_file, $in_minutes_offset) == true ) {
		
		
		// If 'do not disturb' enabled with valid time fomats in plug conf
		if (
    	$plug_class[$this_plug]->valid_time_format($plug_conf[$this_plug]['do_not_dist']['on'])
    	&& $plug_class[$this_plug]->valid_time_format($plug_conf[$this_plug]['do_not_dist']['off'])
	    ) {
		
		// Human-readable year-month-date for today, ADJUSTED FOR USER'S TIME ZONE OFFSET FROM APP CONFIG
		$offset_date = $ct['gen']->time_date_format($ct['conf']['gen']['loc_time_offset'], 'standard_date');
		
		// Time of day in decimals (as hours) for dnd on/off config settings
		$dnd_on_dec = $plug_class[$this_plug]->time_dec_hours($plug_conf[$this_plug]['do_not_dist']['on'], 'to');
		$dnd_off_dec = $plug_class[$this_plug]->time_dec_hours($plug_conf[$this_plug]['do_not_dist']['off'], 'to');
		
			
			// Time of day in hours:minutes for dnd on/off (IN UTC TIME), ADJUSTED FOR USER'S TIME ZONE OFFSET FROM APP CONFIG
			if ( $ct['conf']['gen']['loc_time_offset'] < 0 ) {
			$offset_dnd_on = $plug_class[$this_plug]->time_dec_hours( ( $dnd_on_dec + abs($ct['conf']['gen']['loc_time_offset']) ) , 'from');
			$offset_dnd_off = $plug_class[$this_plug]->time_dec_hours( ( $dnd_off_dec + abs($ct['conf']['gen']['loc_time_offset']) ) , 'from');
			}
			else {
			$offset_dnd_on = $plug_class[$this_plug]->time_dec_hours( ( $dnd_on_dec - $ct['conf']['gen']['loc_time_offset'] ) , 'from');
			$offset_dnd_off = $plug_class[$this_plug]->time_dec_hours( ( $dnd_off_dec - $ct['conf']['gen']['loc_time_offset'] ) , 'from');
			}
		
		
		// UTC timestamps for dnd on/off values, ADJUSTED FOR USER'S TIME ZONE OFFSET FROM APP CONFIG
		$dnd_on = strtotime($offset_date . ' ' . $offset_dnd_on); 
		$dnd_off = strtotime($offset_date . ' ' . $offset_dnd_off); 
		
		// UTC timestamp of current time right now
		$now_timestamp = time();
		
		
			if ( $now_timestamp >= $dnd_off && $now_timestamp < $dnd_on ) {
			$send_msg = true;
			}
			else {
			$send_msg = false;
			}
		
		
		}
		else {
		$send_msg = true;
		}
		
		
		// Send message, if checks pass
		if ( $send_msg ) {
		
		$format_msg = "This is a recurring ~" . round($val['days']) . " day reminder: " . $val['message'];

  		// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  					
  		// Minimize function calls
  		$text_msg = $ct['gen']->detect_unicode($format_msg); 
  					
  	 	$send_params = array(

  	        						'notifyme' => $format_msg,

  	        						'telegram' => $format_msg,
  	        						
  	        						'text' => array(
  	        										'message' => $text_msg['content'],
  	        										'charset' => $text_msg['charset']
  	        										),
  	        										
  	        						'email' => array(
  	        										'subject' => 'Your Recurring Reminder Message (sent every ~' . round($val['days']) . ' days)',
  	        										'message' => $format_msg
  	        										)
  	        										
  	        				 );
  	        						
   	       	
		// Send notifications
		@$ct['cache']->queue_notify($send_params);
	
		// Update the event tracking for this alert
		$ct['cache']->save_file($recurring_reminder_cache_file, $digest);
		
		$send_msg = false; // Reset
		
		}
		

	}
	// END sending reminder


}


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
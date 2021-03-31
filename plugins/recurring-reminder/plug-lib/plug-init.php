<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


foreach ( $plug_conf[$this_plug]['reminders'] as $key => $value ) {
	
	
	if ( preg_match("/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/", $plug_conf[$this_plug]['do_not_dist']['on'])
	&& preg_match("/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/", $plug_conf[$this_plug]['do_not_dist']['off']) ) {
	$do_not_dist = true;
	}


// Recurring reminder time in minutes
$in_minutes = round( $ocpt_var->num_to_str(1440 * $value['days']) );


// Offset -1 anything 20 minutes or higher, so recurring reminder is triggered at same EXACT cron job interval consistently 
// (example: every 2 days at 12:00pm...NOT same cron job interval + 1, like 12:20pm / 12:40pm / etc)
$in_minutes_offset = ( $in_minutes >= 20 ? ($in_minutes - 1) : $in_minutes );

	
	// If it's time to send a reminder...
	if ( update_cache( $ocpt_plug->event_cache('alert-' . $key . '.dat') , $in_minutes_offset ) == true ) {
		
		
		// If 'do not disturb' enabled
		if ( $do_not_dist ) {
		
		// Human-readable year-month-date for today (includes user conf 'loc_time_offset')
		$date_now = $ocpt_gen->time_date_format($ocpt_conf['gen']['loc_time_offset'], 'standard_date');
		
		// Timestamps for now, and 'do not disturb' on / off for today
		$now = time();
		$today_dnd_on = strtotime($date_now . ' ' . $plug_conf[$this_plug]['do_not_dist']['on']); 
		$today_dnd_off = strtotime($date_now . ' ' . $plug_conf[$this_plug]['do_not_dist']['off']); 
		
			if ( $now >= $today_dnd_off && $now < $today_dnd_on ) {
			$send_message = true;
			}
			else {
			$send_message = false;
			}
		
		}
		else {
		$send_message = true;
		}
		
		
		// Send message, if checks pass
		if ( $send_message ) {
		
		$format_message = "This is a recurring ~" . round($value['days']) . " day reminder: " . $value['message'];

  		// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  					
  		// Minimize function calls
  		$encoded_text_message = $ocpt_gen->charset_encode($format_message); // Unicode support included for text messages (emojis / asian characters / etc )
  					
  	 	$send_params = array(
  	        						'notifyme' => $format_message,
  	        						'telegram' => $format_message,
  	        						'text' => array(
  	        											'message' => $encoded_text_message['content_output'],
  	        											'charset' => $encoded_text_message['charset']
  	        												),
  	        						'email' => array(
  	        												'subject' => 'Your Recurring Reminder Message (sent every ~' . round($value['days']) . ' days)',
  	        												'message' => $format_message
  	        												)
  	        						);
  	        						
   	       	
		// Send notifications
		@$ocpt_cache->queue_notify($send_params);
	
		// Update the event tracking for this alert
		$ocpt_cache->save_file( $ocpt_plug->event_cache('alert-' . $key . '.dat') , $ocpt_gen->time_date_format(false, 'pretty_date_time') );
		
		$send_message = false; // Reset
		
		}
		

	}
	// END sending reminder


}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
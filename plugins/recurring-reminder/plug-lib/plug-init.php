<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


// If user blanked out a SINGLE recurring reminder alert value via the admin interface,
// we need to unset the blank value to have the app logic run smoothly
// (as we require at least one blank value IN THE INTERFACE WHEN SUBMITTING UPDATES, TO ASSURE THE ARRAY IS NOT EXCLUDED from the CACHED config)
if ( is_array($plug['conf'][$this_plug]['reminders']) && sizeof($plug['conf'][$this_plug]['reminders']) == 1 ) {
     
     // We are NOT assured key == 0, if it was updated via the admin interface
     foreach ( $plug['conf'][$this_plug]['reminders'] as $key => $val ) {
     
          if ( trim($val['message']) == '' ) {
          unset($plug['conf'][$this_plug]['reminders'][$key]);
          }
     
     }
     
}


// Remove any stale cache files
$loop = ( is_array($plug['conf'][$this_plug]['reminders']) ? sizeof($plug['conf'][$this_plug]['reminders']) : 0 );
while ( file_exists( $ct['plug']->event_cache('alert-' . $loop . '.dat') ) ) {
unlink( $ct['plug']->event_cache('alert-' . $loop . '.dat') );
$loop = $loop + 1;
}
$loop = null;


foreach ( $plug['conf'][$this_plug]['reminders'] as $key => $val ) {
	
// Clear any previous loop's $run_reminder var
$run_reminder = false;
	
$recurring_reminder_cache_file = $ct['plug']->event_cache('alert-' . $key . '.dat');

// Remove any leading zeros in do-not-disturb time format (safe, as we ALWAYS FORCE double-zero format: 00:00)
$plug['conf'][$this_plug]['do_not_disturb']['on'] = ltrim($plug['conf'][$this_plug]['do_not_disturb']['on'], "0");
$plug['conf'][$this_plug]['do_not_disturb']['off'] = ltrim($plug['conf'][$this_plug]['do_not_disturb']['off'], "0");

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


	
	// If it's time to send a reminder...
	if (
	$run_reminder
	|| $ct['cache']->update_cache($recurring_reminder_cache_file, $ct['var']->num_to_str(1440 * $val['days']), 'tasks_time_offset') == true
	) {
		
		
		// If 'do not disturb' enabled with valid time fomats in plug conf
		if (
    	     $plug['class'][$this_plug]->valid_time_format($plug['conf'][$this_plug]['do_not_disturb']['on'])
    	     && $plug['class'][$this_plug]->valid_time_format($plug['conf'][$this_plug]['do_not_disturb']['off'])
	     ) {
		
		// Human-readable year-month-date for today, ADJUSTED FOR USER'S TIME ZONE OFFSET FROM APP CONFIG
		$offset_date = $ct['gen']->time_date_format($ct['conf']['gen']['local_time_offset'], 'standard_date');
		
		// Time of day IN DECIMAL FORMAT (as hours) for dnd on/off config settings
		$dnd_on_dec = $plug['class'][$this_plug]->calc_hours($plug['conf'][$this_plug]['do_not_disturb']['on'], 'in_decimals');
		$dnd_off_dec = $plug['class'][$this_plug]->calc_hours($plug['conf'][$this_plug]['do_not_disturb']['off'], 'in_decimals');
		
			
			// Time of day IN TIME FORMAT for dnd on/off (IN UTC TIME), ADJUSTED FOR USER'S TIME ZONE OFFSET FROM APP CONFIG
			if ( $ct['conf']['gen']['local_time_offset'] < 0 ) {
			$offset_dnd_on = $plug['class'][$this_plug]->calc_hours( ( $dnd_on_dec + abs($ct['conf']['gen']['local_time_offset']) ) , 'in_time_format');
			$offset_dnd_off = $plug['class'][$this_plug]->calc_hours( ( $dnd_off_dec + abs($ct['conf']['gen']['local_time_offset']) ) , 'in_time_format');
			}
			else {
			$offset_dnd_on = $plug['class'][$this_plug]->calc_hours( ( $dnd_on_dec - $ct['conf']['gen']['local_time_offset'] ) , 'in_time_format');
			$offset_dnd_off = $plug['class'][$this_plug]->calc_hours( ( $dnd_off_dec - $ct['conf']['gen']['local_time_offset'] ) , 'in_time_format');
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
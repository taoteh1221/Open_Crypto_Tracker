<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/CRON_PLUGINS_README.txt ON CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// Remind yourself every X days (recurring)
$reminder_recur_days = 30.4167; // Decimals supported (30.4167 days is average length of 1 month)

// Reminder message
$reminder_message = "It's time to re-balance accounts.";


/////////////////////////////////////////////////////////////////

if ( update_cache_file($base_dir . '/cache/events/recurring-reminder-alert.dat', round( number_to_string(1440 * $reminder_recur_days) ) ) == true ) {

$reminder_message = "This is a recurring ~" . round($reminder_recur_days) . " day reminder: " . $reminder_message;

  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  				// Minimize function calls
  				$encoded_text_message = content_data_encoding($reminder_message);
  				
          	$send_params = array(
          								'notifyme' => $reminder_message,
          								'telegram' => $reminder_message,
          								'text' => array(
          														// Unicode support included for text messages (emojis / asian characters / etc )
          														'message' => $encoded_text_message['content_output'],
          														'charset' => $encoded_text_message['charset']
          														),
          								'email' => array(
          														'subject' => 'Your Recurring Reminder Message (sent every ~' . round($reminder_recur_days) . ' days)',
          														'message' => $reminder_message
          														)
          								);
          	
          	
          	
// Send notifications
@queue_notifications($send_params);

// Update the event tracking for this alert
store_file_contents($base_dir . '/cache/events/recurring-reminder-alert.dat', time_date_format(false, 'pretty_date_time') );

}

?>



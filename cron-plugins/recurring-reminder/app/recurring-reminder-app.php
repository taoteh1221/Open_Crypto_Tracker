<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/CRON-PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// Remind yourself every X days (recurring)
$reminder_recur_days = $app_config['cron_plugins'][$cron_plugin_name]['reminder_recur_days']; 

// Reminder message
$reminder_message = $app_config['cron_plugins'][$cron_plugin_name]['reminder_message'];


/////////////////////////////////////////////////////////////////

if ( update_cache_file($base_dir . '/cache/events/recurring-reminder-alert.dat', round( number_to_string(1440 * $reminder_recur_days) ) ) == true ) {

$reminder_message = "This is a recurring ~" . round($reminder_recur_days) . " day reminder: " . $reminder_message;

  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  				// Minimize function calls
  				$encoded_text_message = content_data_encoding($reminder_message); // Unicode support included for text messages (emojis / asian characters / etc )
  				
          	$send_params = array(
          								'notifyme' => $reminder_message,
          								'telegram' => $reminder_message,
          								'text' => array(
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



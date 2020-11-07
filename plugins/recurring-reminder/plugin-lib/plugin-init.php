<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


foreach ( $plugin_config[$this_plugin]['reminders'] as $key => $value ) {


	if ( update_cache_file($base_dir . '/cache/events/recurring-reminder-alert-' . $key . '.dat', round( number_to_string(1440 * $value['days']) ) ) == true ) {

	$format_message = "This is a recurring ~" . round($value['days']) . " day reminder: " . $value['message'];

  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  	// Minimize function calls
  	$encoded_text_message = content_data_encoding($format_message); // Unicode support included for text messages (emojis / asian characters / etc )
  				
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
	@queue_notifications($send_params);

	// Update the event tracking for this alert
	store_file_contents($base_dir . '/cache/events/recurring-reminder-alert-' . $key . '.dat', time_date_format(false, 'pretty_date_time') );

	}


}


?>



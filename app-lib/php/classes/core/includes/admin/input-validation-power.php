<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

        
$light_chart_day_intervals = array_map( "trim", explode(',', $_POST['power']['light_chart_day_intervals']) );
		
$light_chart_all_rebuild_min_max = array_map('trim', explode(',', $_POST['power']['light_chart_all_rebuild_min_max']) );
     	     
// Check system warnings

$val_config = array_map( "trim", explode("||", $_POST['power']['system_uptime_warning']) ); 

if ( !is_numeric($val_config[0]) || !is_numeric($val_config[1]) ) {
$ct['update_config_error'] .= '<br />"system_uptime_warning" seems INVALID (NOT numeric values): ' . $_POST['power']['system_uptime_warning'];
}


$val_config = array_map( "trim", explode("||", $_POST['power']['system_load_warning']) ); 

if ( !is_numeric($val_config[0]) || !is_numeric($val_config[1]) ) {
$ct['update_config_error'] .= '<br />"system_load_warning" seems INVALID (NOT numeric values): ' . $_POST['power']['system_load_warning'];
}


$val_config = array_map( "trim", explode("||", $_POST['power']['system_temperature_warning']) ); 

if ( !is_numeric($val_config[0]) || !is_numeric($val_config[1]) ) {
$ct['update_config_error'] .= '<br />"system_temperature_warning" seems INVALID (NOT numeric values): ' . $_POST['power']['system_temperature_warning'];
}


$val_config = array_map( "trim", explode("||", $_POST['power']['memory_used_percent_warning']) ); 

if ( !is_numeric($val_config[0]) || !is_numeric($val_config[1]) ) {
$ct['update_config_error'] .= '<br />"memory_used_percent_warning" seems INVALID (NOT numeric values): ' . $_POST['power']['memory_used_percent_warning'];
}


$val_config = array_map( "trim", explode("||", $_POST['power']['free_partition_space_warning']) ); 

if ( !is_numeric($val_config[0]) || !is_numeric($val_config[1]) ) {
$ct['update_config_error'] .= '<br />"free_partition_space_warning" seems INVALID (NOT numeric values): ' . $_POST['power']['free_partition_space_warning'];
}


$val_config = array_map( "trim", explode("||", $_POST['power']['portfolio_cache_warning']) ); 

if ( !is_numeric($val_config[0]) || !is_numeric($val_config[1]) ) {
$ct['update_config_error'] .= '<br />"portfolio_cache_warning" seems INVALID (NOT numeric values): ' . $_POST['power']['portfolio_cache_warning'];
}


$val_config = array_map( "trim", explode("||", $_POST['power']['cookies_size_warning']) ); 

if ( !is_numeric($val_config[0]) || !is_numeric($val_config[1]) ) {
$ct['update_config_error'] .= '<br />"cookies_size_warning" seems INVALID (NOT numeric values): ' . $_POST['power']['cookies_size_warning'];
}
  
  
// Make sure light chart day intervals is set
if ( isset($_POST['power']['light_chart_day_intervals']) && trim($_POST['power']['light_chart_day_intervals']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart Day Intervals" MUST be filled in';
}
else {

     foreach ( $light_chart_day_intervals as $days ) {
     
         if ( $days == 0 || !$ct['var']->whole_int($days) ) {
         $ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart Day Intervals" MUST be whole numbers greater than zero ("'.$days.'" is invalid)';
         }
     
     }
     
}


// Make sure min / max 'all' light chart rebuild time is set properly
if ( isset($_POST['power']['light_chart_all_rebuild_min_max']) && trim($_POST['power']['light_chart_all_rebuild_min_max']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart All Rebuild Min Max" MUST be filled in';
}
else if (
!isset($light_chart_all_rebuild_min_max[0]) || !$ct['var']->whole_int($light_chart_all_rebuild_min_max[0]) || $light_chart_all_rebuild_min_max[0] < 3 || $light_chart_all_rebuild_min_max[0] > 12 
|| !isset($light_chart_all_rebuild_min_max[1]) || !$ct['var']->whole_int($light_chart_all_rebuild_min_max[1]) || $light_chart_all_rebuild_min_max[1] < 3 || $light_chart_all_rebuild_min_max[1] > 12
|| $light_chart_all_rebuild_min_max[0] > $light_chart_all_rebuild_min_max[1]
) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart All Rebuild Min Max" values MUST be between 3 and 12 (LARGER number last)';
}
     	 
  
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
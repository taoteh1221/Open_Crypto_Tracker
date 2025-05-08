<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

     	     
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
     	 
  
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
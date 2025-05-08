<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



class ct_plug {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function plug_dir($http=false, $passed_plug=false) {
       
   global $ct, $this_plug;
   
      
      if ( isset($this_plug) && $this_plug != '' ) {
      $set_plug = $this_plug;
      }
      elseif ( $passed_plug ) {
      $set_plug = $passed_plug;
      }

   
      if ( $http == true ) {
      return 'plugins/' . $set_plug;
      }
      else {
      return $ct['base_dir'] . '/plugins/' . $set_plug;
      }
    
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function var_cache($file=false, $passed_plug=false) {
      
   global $ct, $this_plug;
   
      
      if ( isset($this_plug) && $this_plug != '' ) {
      $set_plug = $this_plug;
      }
      elseif ( $passed_plug ) {
      $set_plug = $passed_plug;
      }

   
      // This plugin's vars cache directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/vars/plugin_vars/'.$set_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/vars/plugin_vars/'.$set_plug.'/');
      }
      
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/vars/plugin_vars/'.$set_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/vars/plugin_vars/'.$set_plug.'/' . $file;
      }
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function state_cache($file=false, $passed_plug=false) {
      
   global $ct, $this_plug;
   
      
      if ( isset($this_plug) && $this_plug != '' ) {
      $set_plug = $this_plug;
      }
      elseif ( $passed_plug ) {
      $set_plug = $passed_plug;
      }

   
      // This plugin's vars cache directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/vars/state-tracking/plugin_state/'.$set_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/vars/state-tracking/plugin_state/'.$set_plug.'/');
      }
      
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/vars/state-tracking/plugin_state/'.$set_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/vars/state-tracking/plugin_state/'.$set_plug.'/' . $file;
      }
      
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function event_cache($file=false, $passed_plug=false) {
      
   global $ct, $this_plug;
   
      
      if ( isset($this_plug) && $this_plug != '' ) {
      $set_plug = $this_plug;
      }
      elseif ( $passed_plug ) {
      $set_plug = $passed_plug;
      }

   
      // This plugin's events cache directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/plugin_events/'.$set_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/events/plugin_events/'.$set_plug.'/');
      }
      
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/events/plugin_events/'.$set_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/events/plugin_events/'.$set_plug.'/' . $file;
      }
      
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function alert_cache($file=false, $passed_plug=false) {
      
   global $ct, $this_plug;
   
      
      if ( isset($this_plug) && $this_plug != '' ) {
      $set_plug = $this_plug;
      }
      elseif ( $passed_plug ) {
      $set_plug = $passed_plug;
      }

   
      // This plugin's events cache directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/alerts/plugin_alerts/'.$set_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/alerts/plugin_alerts/'.$set_plug.'/');
      }
      
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/alerts/plugin_alerts/'.$set_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/alerts/plugin_alerts/'.$set_plug.'/' . $file;
      }
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function chart_cache($file=false, $passed_plug=false) {
      
   global $ct, $this_plug;
   
      
      if ( isset($this_plug) && $this_plug != '' ) {
      $set_plug = $this_plug;
      }
      elseif ( $passed_plug ) {
      $set_plug = $passed_plug;
      }

   
      // This plugin's events chart directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/charts/plugin_charts/'.$set_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/charts/plugin_charts/'.$set_plug.'/');
      }
      
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/charts/plugin_charts/'.$set_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/charts/plugin_charts/'.$set_plug.'/' . $file;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
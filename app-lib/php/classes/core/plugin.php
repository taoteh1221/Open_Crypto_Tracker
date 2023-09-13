<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



class ct_plug {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function plug_dir($http=false) {
       
   global $ct, $this_plug;
   
      if ( $http == true ) {
      return 'plugins/' . $this_plug;
      }
      else {
      return $ct['base_dir'] . '/plugins/' . $this_plug;
      }
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function var_cache($file=false) {
      
   global $ct, $this_plug;
   
      // This plugin's vars cache directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/vars/plugin_vars/'.$this_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/vars/plugin_vars/'.$this_plug.'/');
      }
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/vars/plugin_vars/'.$this_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/vars/plugin_vars/'.$this_plug.'/' . $file;
      }
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function event_cache($file=false) {
      
   global $ct, $this_plug;
         
      // This plugin's events cache directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/events/plugin_events/'.$this_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/events/plugin_events/'.$this_plug.'/');
      }
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/events/plugin_events/'.$this_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/events/plugin_events/'.$this_plug.'/' . $file;
      }
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function alert_cache($file=false) {
      
   global $ct, $this_plug;
         
      // This plugin's events cache directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/alerts/plugin_alerts/'.$this_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/alerts/plugin_alerts/'.$this_plug.'/');
      }
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/alerts/plugin_alerts/'.$this_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/alerts/plugin_alerts/'.$this_plug.'/' . $file;
      }
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function chart_cache($file=false) {
      
   global $ct, $this_plug;
         
      // This plugin's events chart directory
      if ( $ct['gen']->dir_struct($ct['base_dir'] . '/cache/charts/plugin_charts/'.$this_plug.'/') != true ) {
      $ct['gen']->log('system_error', 'Could not create directory: /cache/charts/plugin_charts/'.$this_plug.'/');
      }
      
      if ( $file == false ) {
      return $ct['base_dir'] . '/cache/charts/plugin_charts/'.$this_plug;
      }
      else {
      return $ct['base_dir'] . '/cache/charts/plugin_charts/'.$this_plug.'/' . $file;
      }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
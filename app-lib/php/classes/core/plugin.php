<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



class oct_plug {
	
// Class variables / arrays
var $oct_var1;
var $oct_var2;
var $oct_var3;
var $oct_array1 = array();
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function var_cache($file) {
      
   global $base_dir, $oct_gen, $this_plug;
   
      // This plugin's vars cache directory
      if ( $oct_gen->dir_struct($base_dir . '/cache/vars/plugin_vars/'.$this_plug.'/') != true ) {
      $oct_gen->log('system_error', 'Could not create directory: /cache/vars/plugin_vars/'.$this_plug.'/');
      }
      
   return $base_dir . '/cache/vars/plugin_vars/'.$this_plug.'/' . $file;
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function event_cache($file) {
      
   global $base_dir, $oct_gen, $this_plug;
         
      // This plugin's events cache directory
      if ( $oct_gen->dir_struct($base_dir . '/cache/events/plugin_events/'.$this_plug.'/') != true ) {
      $oct_gen->log('system_error', 'Could not create directory: /cache/events/plugin_events/'.$this_plug.'/');
      }
      
   return $base_dir . '/cache/events/plugin_events/'.$this_plug.'/' . $file;
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function alert_cache($file) {
      
   global $base_dir, $oct_gen, $this_plug;
         
      // This plugin's events cache directory
      if ( $oct_gen->dir_struct($base_dir . '/cache/alerts/plugin_alerts/'.$this_plug.'/') != true ) {
      $oct_gen->log('system_error', 'Could not create directory: /cache/alerts/plugin_alerts/'.$this_plug.'/');
      }
      
   return $base_dir . '/cache/alerts/plugin_alerts/'.$this_plug.'/' . $file;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   

}


?>
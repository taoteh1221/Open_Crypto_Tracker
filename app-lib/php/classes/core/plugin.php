<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



class pt_plug {
	
// Class variables / arrays
var $pt_var1;
var $pt_var2;
var $pt_var3;
var $pt_array1 = array();
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function var_cache($file) {
      
   global $base_dir, $pt_gen, $this_plug;
   
      // This plugin's vars cache directory
      if ( $pt_gen->dir_struct($base_dir . '/cache/vars/plugin_vars/'.$this_plug.'/') != true ) {
      $pt_gen->log('system_error', 'Could not create directory: /cache/vars/plugin_vars/'.$this_plug.'/');
      }
      
   return $base_dir . '/cache/vars/plugin_vars/'.$this_plug.'/' . $file;
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function event_cache($file) {
      
   global $base_dir, $pt_gen, $this_plug;
         
         // This plugin's events cache directory
         if ( $pt_gen->dir_struct($base_dir . '/cache/events/plugin_events/'.$this_plug.'/') != true ) {
         $pt_gen->log('system_error', 'Could not create directory: /cache/events/plugin_events/'.$this_plug.'/');
         }
      
   return $base_dir . '/cache/events/plugin_events/'.$this_plug.'/' . $file;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   

}


?>
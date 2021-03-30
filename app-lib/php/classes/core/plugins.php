<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



class ocpt_plug {
	
// Class variables / arrays
var $ocpt_var1;
var $ocpt_var2;
var $ocpt_var3;
var $ocpt_array1 = array();
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function var_cache($file) {
      
   global $base_dir, $ocpt_gen, $this_plug;
   
      // This plugin's vars cache directory
      if ( $ocpt_gen->dir_structure($base_dir . '/cache/vars/plugin_vars/'.$this_plug.'/') != true ) {
      $ocpt_gen->app_logging('system_error', 'Could not create directory: /cache/vars/plugin_vars/'.$this_plug.'/');
      }
      
   return $base_dir . '/cache/vars/plugin_vars/'.$this_plug.'/' . $file;
   
   }

   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function event_cache($file) {
      
   global $base_dir, $ocpt_gen, $this_plug;
         
         // This plugin's events cache directory
         if ( $ocpt_gen->dir_structure($base_dir . '/cache/events/plugin_events/'.$this_plug.'/') != true ) {
         $ocpt_gen->app_logging('system_error', 'Could not create directory: /cache/events/plugin_events/'.$this_plug.'/');
         }
      
   return $base_dir . '/cache/events/plugin_events/'.$this_plug.'/' . $file;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   

}


?>
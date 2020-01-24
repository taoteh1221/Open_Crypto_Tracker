<?php

 // Needed for class compatibility (along with initial instance in app's init.php file)
global $smtp_vars;
		
$cfg_log_file   = $smtp_vars['cfg_log_file'];
$cfg_log_file_debugging   = $smtp_vars['cfg_log_file_debugging'];
$cfg_server   = $smtp_vars['cfg_server'];
$cfg_port     =  $smtp_vars['cfg_port'];
$cfg_secure   = $smtp_vars['cfg_secure'];
$cfg_username = $smtp_vars['cfg_username'];
$cfg_password = $smtp_vars['cfg_password'];
$cfg_debug_mode = $smtp_vars['cfg_debug_mode'];  // DFD Cryptocoin Values debug mode setting
$cfg_strict_ssl = $smtp_vars['cfg_strict_ssl'];  // DFD Cryptocoin Values strict SSL setting
$cfg_app_version = $smtp_vars['cfg_app_version']; // DFD Cryptocoin Values version

?>
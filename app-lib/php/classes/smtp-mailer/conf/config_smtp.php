<?php

 // Needed for class compatibility (along with initial instance in app's post-init.php file)
global $smtp_vars;
		
$cfg_log_file   = $smtp_vars['cfg_log_file'];
$cfg_server   = $smtp_vars['cfg_server'];
$cfg_port     =  $smtp_vars['cfg_port'];
$cfg_secure   = $smtp_vars['cfg_secure'];
$cfg_username = $smtp_vars['cfg_username'];
$cfg_password = $smtp_vars['cfg_password'];

?>
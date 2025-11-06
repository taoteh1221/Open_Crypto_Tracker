<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// MAY HELP, SINCE WE USE SAMESITE=STRICT COOKIES (ESPECIALLY ON SERVERS WITH DOMAIN REDIRECTS)
if ( !preg_match("/plugins\.php/i", $_SERVER['REQUEST_URI']) ) {
header("Location: plugins.php");
exit;
}


// Runtime mode
$runtime_mode = 'ui';

$is_plugin = true;


require("app-lib/php/init.php");
	

// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || !is_array($stored_admin_login) ) {
require("templates/interface/php/admin/admin-login/register.php");
exit;
}
else {
require("templates/interface/php/wrap/header.php");
}


foreach ( $plug['activated']['ui'] as $plugin_key => $plugin_init ) {
            		
$this_plug = $plugin_key;
     
     if ( $plug['conf'][$this_plug]['ui_location'] == 'nav_menu' ) {
     ?>

		<div id='plugin_<?=$this_plug?>' class='tabdiv'>
	
<h2 class='bitcoin page_title'><?=$plug['conf'][$this_plug]['ui_name']?></h2>


<div class='full_width_wrapper'>
			
			
	   <div class='align_left' style='margin-top: 0.5em; margin-bottom: 2em;'>
	
	
	         <div style='display: inline;'><?=$ct['gen']->start_page_html('plugin_' . $this_plug)?></div>
			
			&nbsp; &nbsp; <span class='blue' style='font-weight: bold;'>App Reload:</span> <select title='Auto-Refresh MAY NOT WORK properly on mobile devices (phone / laptop / tablet / etc), or inactive tabs.' class='browser-default custom-select select_auto_refresh' name='select_auto_refresh' onchange='
			 auto_reload(this);
			 '>
				<option value='0'> Manually </option>
				<option value='300'> 5 Minutes </option>
				<option value='600'> 10 Minutes </option>
				<option value='900'> 15 Minutes </option>
				<option value='1800'> 30 Minutes </option>
			</select> 
			
			&nbsp; <span class='reload_notice red'></span>		
		
		
	   </div>
					
                            

              	<?php
            	// This plugin's plug-init.php file (runs the plugin)
            	include($plugin_init);
               ?>

		</div>
		
		
</div>

     <?php
     }

// Reset $this_plug at end of loop
unset($this_plug); 
            	
}


require("templates/interface/php/wrap/footer.php");

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
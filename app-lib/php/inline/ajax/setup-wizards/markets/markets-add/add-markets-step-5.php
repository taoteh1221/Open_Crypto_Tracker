<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>



<script>

disable_nav_save_buttons = false; // Allow nav save buttons to work again

console.log('disable_nav_save_buttons = ' + disable_nav_save_buttons);

</script>


<h3 class='bitcoin input_margins'>STEP #5: Add Asset Markets Results</h3>

	
	<div style='min-height: 1em;'></div>
	 
	 
	 <?php
	 if ( $ct['update_config_success'] != null ) {
	 ?>
	 
	 <script>
	 
	 <?php
      $reload_function_name = 'secondary_refresh_iframes';
	 require("templates/interface/php/wrap/wrap-elements/admin-refresh.php");
	 ?>
	 
	 </script>
	 
	 <div class='green green_dotted' style='font-weight: bold;'><?=$ct['update_config_success']?></div>
	 <div style='min-height: 1em;'></div>
	 
	 <?php
	 }
	 elseif ( $ct['update_config_error'] != null ) {
	 ?>
	 <div class='red red_dotted' style='font-weight: bold;'><?=$ct['update_config_error']?></div>
	 <div style='min-height: 1em;'></div>
	 <?php
	 }
	 ?>
	 
	 
<?php

require("templates/interface/php/admin/admin-sections/asset-tracking/portfolio-assets.php");


// DEBUGGING...
if ( $ct['conf']['power']['debug_mode'] == 'wizard_steps_io' ) {
   
$ct['gen']->array_debugging($_POST, true);

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
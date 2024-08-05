<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

?>
	     
          <h3 class='bitcoin input_margins'>STEP #1: ADD or REMOVE Asset Markets</h3>
    		
	     
     	<input type='radio' name='update_markets_mode' value='add' checked /> Add NEW Asset Markets<br /><br />
	     
     	<input type='radio' name='update_markets_mode' value='remove' /> Remove EXISTING Asset Markets<br /><br />
     	
     	
     	<button class='force_button_style input_margins' onclick='
     	
     	ct_ajax_load("type=" + $("input[name=update_markets_mode]:radio:checked").val() + "_markets&step=2", "#update_markets_ajax", $("input[name=update_markets_mode]:radio:checked").val() + " markets mode", false, true); // Secured
     	
     	'> Continue </button>


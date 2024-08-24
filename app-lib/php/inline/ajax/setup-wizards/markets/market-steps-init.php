<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

?>
	     
          <h3 class='bitcoin input_margins'>STEP #1: <span class='green'>ADD</span> / <span class='red'>REMOVE</span> Asset Markets</h3>
    		
	     
     	<input type='radio' name='update_markets_mode' value='add'  <?=( !isset($_POST['update_markets_mode']) || $_POST['update_markets_mode'] == 'add' ? 'checked' : '' )?> /> ADD NEW Asset Markets<br /><br />
	     
     	<input type='radio' name='update_markets_mode' value='remove' <?=( $_POST['update_markets_mode'] == 'remove' ? 'checked' : '' )?> /> REMOVE EXISTING Asset Markets<br /><br />
     	
     	
     	<button class='force_button_style input_margins' onclick='
     	    
     	
     	var markets_mode = {
     	                          "update_markets_mode": $("input[name=update_markets_mode]:radio:checked").val(),
     	                          
     	                          };
     	
     	ct_ajax_load("type=" + $("input[name=update_markets_mode]:radio:checked").val() + "_markets&step=2", "#update_markets_ajax", $("input[name=update_markets_mode]:radio:checked").val() + " markets mode", markets_mode, true); // Secured
     	
     	'> Continue </button>


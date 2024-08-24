<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

?>
	
<h3 class='bitcoin input_margins'>STEP #2: Remove ASSET / MARKET</h3>
    		
	     
<input type='radio' name='remove_markets_mode' value='markets' <?=( !isset($_POST['remove_markets_mode']) || $_POST['remove_markets_mode'] == 'markets' ? 'checked' : '' )?> /> Remove INDIVIDUAL MARKETS<br /><br />
	     
<input type='radio' name='remove_markets_mode' value='asset' <?=( $_POST['remove_markets_mode'] == 'asset' ? 'checked' : '' )?> /> Remove ENTIRE ASSET<br /><br />
     	
     	<button class='force_button_style input_margins' onclick='
     	
     	var remove_markets_mode = {
     	                          "remove_markets_mode": $("input[name=remove_markets_mode]:radio:checked").val(),
     	                          };
     	
     	
     	ct_ajax_load("type=remove_markets&step=3", "#update_markets_ajax", "remove " + $("input[name=remove_markets_mode]:radio:checked").val(), remove_markets_mode, true); // Secured
     	
     	'> Continue </button>


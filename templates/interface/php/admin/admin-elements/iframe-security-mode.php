<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Mirror URI GET params from original request
$loop_count = 0;
foreach( $_GET as $key => $val ) {
$uri_params .= ( $loop_count > 0 ? '&' : '?' ) . $key . '=' . $val;
$loop_count = $loop_count + 1;
}


?>


	<!-- medium_security_check key START -->
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN MEDIUM SECURITY ADMIN MODE. Please click 'View Settings' below to continue.
	
	</p>


	<div style='margin: 25px;'>
	
	<form name='medium_security_check' id='medium_security_check' action='admin.php<?=$uri_params?>' method='post'>
	
	<input type='hidden' name='medium_security_nonce' value='<?=$ct['sec']->admin_nonce('medium_security_mode')?>' />
	
	<?=$ct['gen']->input_2fa('strict')?>
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='medium_security_check_button' class='force_button_style' onclick='
	document.getElementById("medium_security_check_button").disable = true;
    $("#medium_security_check").submit(); // Triggers "app reloading" sequence
	document.getElementById("medium_security_check_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
	'>View Settings</button>
	
	</div>
				
	<!-- medium_security_check key END -->

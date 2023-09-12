<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */

// Mirror URI GET params from original request
$loop_count = 0;
foreach( $_GET as $key => $val ) {
$uri_params .= ( $loop_count > 0 ? '&' : '?' ) . $key . '=' . $val;
$loop_count = $loop_count + 1;
}

?>


	<!-- enhanced_security_check key START -->
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN ENHANCED SECURITY ADMIN MODE. Please click 'View Settings' below to continue.
	
	</p>


	<div style='margin: 25px;'>
	
	<form name='enhanced_security_check' id='enhanced_security_check' action='admin.php<?=$uri_params?>' method='post'>
	
	<input type='hidden' name='enhanced_security_nonce' value='<?=$ct['gen']->admin_hashed_nonce('enhanced_security_mode')?>' />
	
	<?=$ct['gen']->input_2fa()?>
	
	</form>
	
	<!-- Submit button must be OUTSIDE form tags here, or it runs improperly -->
	<button id='enhanced_security_check_button' class='force_button_style' onclick='
	document.getElementById("enhanced_security_check_button").disable = true;
    $("#enhanced_security_check").submit(); // Triggers "app reloading" sequence
	document.getElementById("enhanced_security_check_button").innerHTML = ajax_placeholder(15, "center", "Submitting...");
	'>View Settings</button>
	
	</div>
				
	<!-- enhanced_security_check key END -->

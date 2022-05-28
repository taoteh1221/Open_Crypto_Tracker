<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


// Runtime mode
$runtime_mode = 'ui';

$is_admin = true;

require("config.php");
	

// If an activated password reset is in progress or no admin login has been set yet, prompt user to create an admin user / pass
if ( $password_reset_approved || !is_array($stored_admin_login) ) {
require("templates/interface/desktop/php/admin/admin-login/register.php");
exit;
}
// If logged in
elseif ( $ct_gen->admin_logged_in() ) {
require("templates/interface/desktop/php/header.php");
}
// If NOT logged in
else {
require("templates/interface/desktop/php/admin/admin-login/login.php");
exit;
}


?>

<div class='full_width_wrapper align_center'>

	<div id='admin_wrapper' class='align_center' style='margin: auto;'>
	
	<?php
	if ( isset($_GET['plugin']) ) {
    $this_plug = $_GET['plugin'];
	require("templates/interface/desktop/php/admin/admin-elements/plugin-admin-page.php");
	unset($this_plug);
	}
	else {
	require("templates/interface/desktop/php/admin/admin-elements/main-admin-page.php");
	}
	?>

	</div> <!-- wrapper END -->
	
</div> <!-- admin index full_width_wrapper END -->


<br clear="all" />


<?php
require("templates/interface/desktop/php/footer.php");

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
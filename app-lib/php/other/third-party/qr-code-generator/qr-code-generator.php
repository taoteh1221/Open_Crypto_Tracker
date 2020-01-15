<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

?>

<p>If you need to safely / quickly copy an address to yours or someone else's phone with a QR Code scanner app. 
<br /><br />NOTE: Whitespace, carriage returns, HTML, and non-alphanumeric characters are not allowed.</p>

<form method='post' action='<?=start_page('tools')?>'>

<input type='text' name='qr-string' placeholder="Enter address to convert to QR code here..." value='<?=trim($_POST['qr-string'])?>' style='width: 100%;' />

<br /><br /><input type='submit' value='Generate QR Code Address' />

</form>

<?php

if ( !function_exists("imagepng") ) {
echo "<p class='red'>GD for PHP (version ID ".PHP_VERSION_ID.") is not installed yet. GD is required to run this application.</p>";
} 
elseif ( trim($_POST['qr-string']) != '' ) {
?>

<p style='font-weight: bold;'>Generated QR Code Address:</p>
<p><image src='templates/interface/media/images/qr-code-image.php?data=<?=urlencode(trim($_POST['qr-string']))?>' border='0' /></p>
<p class='red' style='font-weight: bold;'>--ALWAYS-- VERIFY YOUR ADDRESS COPIED OVER CORRECTLY</p>

<?php
}
?>
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( isset($_POST['sec']['interface_login']) && $_POST['sec']['interface_login'] != '' ) {
     
$is_interface_login = true;
        
$interface_login_check = explode("||", $_POST['sec']['interface_login']);
   
$htaccess_username_check = $interface_login_check[0];
$htaccess_password_check = $interface_login_check[1];
  
// true == Only check on minimum required length (and whitespace check [that we ALWAYS require])
$valid_username_check = $ct['gen']->valid_username($htaccess_username_check, true);
  
// Password must be exactly 8 characters long for good htaccess security (htaccess only checks the first 8 characters for a match)
$password_strength_check = $ct['sec']->pass_strength($htaccess_password_check, 8, 8);
  
}
        
  
// Make sure interface login params are set properly
if ( $is_interface_login && sizeof($interface_login_check) < 2 ) {
$ct['update_config_error'] = 'Interface Login formatting is NOT valid (format MUST be: username||password)';
}
elseif ( $is_interface_login && $valid_username_check != 'valid' ) {
$ct['update_config_error'] = 'Interface Login USERNAME requirements NOT met  (' . $valid_username_check . ')';
}
elseif ( $is_interface_login && $password_strength_check != 'valid' ) {
$ct['update_config_error'] = 'Interface Login PASSWORD requirements NOT met  (' . $password_strength_check . ')';
}
        
        
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
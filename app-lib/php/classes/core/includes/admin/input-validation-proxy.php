<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( isset($_POST['proxy']['proxy_login']) && $_POST['proxy']['proxy_login'] != '' && !preg_match('/\s/', $_POST['proxy']['proxy_login']) ) {
$is_proxy_login = true;
$proxy_login_check = explode("||", $_POST['proxy']['proxy_login']);
}
        

if ( preg_match('/\s/', $_POST['proxy']['proxy_login']) ) {
$ct['update_config_error'] .= 'WHITESPACE is not allowed in the Proxy LOGIN';
}
// Make sure proxy login params are set properly
elseif ( $is_proxy_login && sizeof($proxy_login_check) < 2 ) {
$ct['update_config_error'] .= 'Proxy LOGIN formatting is NOT valid (format MUST be: username||password)';
}
elseif ( isset($_POST['proxy']['allow_proxies']) && $_POST['proxy']['allow_proxies'] == 'on' && is_array($_POST['proxy']['proxy_list']) ) {

    foreach ( $_POST['proxy']['proxy_list'] as $proxy ) {
         
    $proxy = trim($proxy);
    
    $proxy_check = explode(":", $proxy);
         
         if ( sizeof($_POST['proxy']['proxy_list']) == 1 && trim($proxy) == '' ) {
     	          // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
         }
         elseif ( sizeof($proxy_check) < 2 ) {
         $ct['update_config_error'] .= '<br />Proxy LIST formatting is NOT valid (format MUST be: ip_address:port_number [in submission: "'.$proxy.'"])';
         }
         else {
   
             if ( $proxy_checked ) {
             sleep(2); // Don't want to hit the testing server too hard too quick on consecutive requests
             }
                   
                   $check_proxy = $ct['gen']->connect_test($proxy, 'proxy');
                   
             if ( $check_proxy['status'] != 'ok' ) {
             $ct['update_config_error'] .= '<br />Proxy TEST failed for submission: "'.$proxy.'" ('.$check_proxy['status'].')';
             }
   
         $proxy_checked = true;
         
         }
    
    }

}


$_POST['proxy']['anti_proxy_servers'] = array_map( "trim", $_POST['proxy']['anti_proxy_servers']); 

foreach ( $_POST['proxy']['anti_proxy_servers'] as $domain ) {

     if ( !$ct['gen']->valid_domain($domain) ) {
     $ct['update_config_error'] .= '<br />"anti_proxy_servers" seems INVALID (NOT a domain): ' . $domain;
     }

}
        
        
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
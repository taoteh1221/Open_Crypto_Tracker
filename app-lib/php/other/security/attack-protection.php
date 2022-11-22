<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Sanitize any user inputs VERY EARLY (for security / compatibility)
foreach ( $_GET as $scan_get_key => $unused ) {
$_GET[$scan_get_key] = $ct_gen->sanitize_requests('get', $scan_get_key, $_GET[$scan_get_key]);
}
foreach ( $_POST as $scan_post_key => $unused ) {
$_POST[$scan_post_key] = $ct_gen->sanitize_requests('post', $scan_post_key, $_POST[$scan_post_key]);
}


// If user is logging out (run immediately after setting PRIMARY vars, for quick runtime)
if ( $_GET['logout'] == 1 && $ct_gen->admin_hashed_nonce('logout') != false && $_GET['admin_hashed_nonce'] == $ct_gen->admin_hashed_nonce('logout') ) {
	
// Try to avoid edge-case bug where sessions don't delete, using our hardened function logic
$ct_gen->hardy_sess_clear(); 

// Delete admin login cookie
unset($_COOKIE['admin_auth_' . $ct_gen->id()]);
$ct_gen->store_cookie('admin_auth_' . $ct_gen->id(), '', time()-3600); // Delete

header("Location: index.php");
exit;

}


// A bit of DOS attack mitigation for bogus / bot login attempts
// Speed up runtime SIGNIFICANTLY by checking EARLY for a bad / non-existent captcha code, and rendering the related form again...
// A BIT STATEMENT-INTENSIVE ON PURPOSE, AS IT KEEPS RUNTIME SPEED MUCH HIGHER
if ( $_POST['admin_submit_register'] || $_POST['admin_submit_login'] || $_POST['admin_submit_reset'] ) {


	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) != strtolower($_SESSION['captcha_code']) ) {
	
	    
	    // WE RUN SECURITY CHECKS WITHIN THE REGISTRATION PAGE, SO NOT MUCH CHECKS ARE IN THIS INIT SECTION
		if ( $_POST['admin_submit_register'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/register.php");
		exit;
		}
		elseif ( $_POST['admin_submit_login'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/login.php");
		exit;
		}
		elseif ( $_POST['admin_submit_reset'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/reset.php");
		exit;
		}
	
	
	}
	

}


// CSRF attack protection for downloads EXCEPT backup downloads (which require the nonce 
// in the filename [which we do already], since backup links are created during cron runtimes)
if ( $runtime_mode == 'download' && !isset($_GET['backup']) && $_GET['token'] != $ct_gen->nonce_digest('download') ) {
$ct_gen->log('security_error', 'aborted, security token mis-match/stale from ' . $_SERVER['REMOTE_ADDR'] . ', for request: ' . $_SERVER['REQUEST_URI']);
$ct_cache->error_log();
echo "Aborted, security token mis-match/stale.";
exit;
}

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
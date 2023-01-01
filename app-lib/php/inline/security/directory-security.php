<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


if ( $force_exit != 1 ) {
	
usleep(100000); // Wait 0.1 seconds after possible directory creation
    
    ///////////////////////////////////////////
    
    // Recreate /.htaccess for optional password access restriction / mod rewrite etc
    if ( !file_exists($base_dir . '/.htaccess') ) {
    $ct_cache->save_file($base_dir . '/.htaccess', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') ); 
    sleep(1);
    }
    
    // Recreate /.user.ini for optional php-fpm php.ini control
    if ( !file_exists($base_dir . '/.user.ini') ) {
    $ct_cache->save_file($base_dir . '/.user.ini', $ct_cache->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-user-ini.template') ); 
    sleep(1);
    }
    
    
    ///////////////////////////////////////////
    
    // Recreate /cache/.htaccess to restrict web snooping of cache contents, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/.htaccess') ) {
    $ct_cache->save_file($base_dir . '/cache/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    sleep(1);
    }
    
    // Recreate /cache/index.php to restrict web snooping of backup contents, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/index.php') ) {
    $ct_cache->save_file($base_dir . '/cache/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    sleep(1);
    }
    
    // Recreate /cache/htaccess_security_check.dat to test htaccess activation, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/htaccess_security_check.dat') ) {
    $ct_cache->save_file($base_dir . '/cache/htaccess_security_check.dat', file_get_contents($base_dir . '/templates/back-end/access_test.template')); 
    sleep(1);
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/.htaccess to restrict web snooping of backup contents, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/.htaccess') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template')); 
    sleep(1);
    }
    
    // Recreate /cache/secured/index.php to restrict web snooping of backup contents, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/index.php') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    sleep(1);
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/activation/.htaccess to restrict web snooping of cache contents, if the activation directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/activation/.htaccess') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/activation/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    sleep(1);
    }
    
    // Recreate /cache/secured/activation/index.php to restrict web snooping of backup contents, if the activation directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/activation/index.php') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/activation/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    sleep(1);
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/external_data/.htaccess to restrict web snooping of cache contents, if the apis directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/external_data/.htaccess') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/external_data/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    sleep(1);
    }
    
    // Recreate /cache/secured/external_data/index.php to restrict web snooping of backup contents, if the apis directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/external_data/index.php') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/external_data/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    sleep(1);
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/backups/.htaccess to restrict web snooping of cache contents, if the backups directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/backups/.htaccess') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/backups/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    sleep(1);
    }
    
    // Recreate /cache/secured/backups/index.php to restrict web snooping of backup contents, if the backups directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/backups/index.php') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/backups/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    sleep(1);
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/messages/.htaccess to restrict web snooping of cache contents, if the messages directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/messages/.htaccess') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/messages/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    sleep(1);
    }
    
    // Recreate /cache/secured/messages/index.php to restrict web snooping of backup contents, if the messages directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/messages/index.php') ) {
    $ct_cache->save_file($base_dir . '/cache/secured/messages/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    sleep(1);
    }
    
    ///////////////////////////////////////////
    
    // Recreate /plugins/.htaccess to restrict web snooping of plugins contents, if the plugins directory was deleted / recreated
    // DIFFERENT FILENAME TEMPLATE (deny-all-htaccess-plugins.template) FOR SOME ACCESS EXCEPTIONS!!!
    if ( !file_exists($base_dir . '/plugins/.htaccess') ) {
    $ct_cache->save_file($base_dir . '/plugins/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess-plugins.template') ); 
    sleep(1);
    }
    
    // Recreate /plugins/index.php to restrict web snooping of plugins contents, if the plugins directory was deleted / recreated
    if ( !file_exists($base_dir . '/plugins/index.php') ) {
    $ct_cache->save_file($base_dir . '/plugins/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    sleep(1);
    }
    
    // Recreate /plugins/htaccess_security_check.dat to test htaccess activation, if the plugins directory was deleted / recreated
    if ( !file_exists($base_dir . '/plugins/htaccess_security_check.dat') ) {
    $ct_cache->save_file($base_dir . '/plugins/htaccess_security_check.dat', file_get_contents($base_dir . '/templates/back-end/access_test.template')); 
    sleep(1);
    }
    
    ///////////////////////////////////////////


}

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>
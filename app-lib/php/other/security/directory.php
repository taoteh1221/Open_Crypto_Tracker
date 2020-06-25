<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


if ( $force_exit != 1 ) {

    
    ///////////////////////////////////////////
    
    // Recreate /.htaccess for optional password access restriction / mod rewrite etc
    if ( !file_exists($base_dir . '/.htaccess') ) {
    store_file_contents($base_dir . '/.htaccess', htaccess_directory_defaults() ); 
    }
    
    // Recreate /.user.ini for optional php-fpm php.ini control
    if ( !file_exists($base_dir . '/.user.ini') ) {
    store_file_contents($base_dir . '/.user.ini', user_ini_defaults() ); 
    }
    
    
    ///////////////////////////////////////////
    
    // Recreate /cache/.htaccess to restrict web snooping of cache contents, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/.htaccess') ) {
    store_file_contents($base_dir . '/cache/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    }
    
    // Recreate /cache/index.php to restrict web snooping of backup contents, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/index.php') ) {
    store_file_contents($base_dir . '/cache/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    }
    
    // Recreate /cache/htaccess_security_check.dat to test htaccess activation, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/htaccess_security_check.dat') ) {
    store_file_contents($base_dir . '/cache/htaccess_security_check.dat', file_get_contents($base_dir . '/templates/back-end/access_test.template')); 
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/.htaccess to restrict web snooping of backup contents, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/.htaccess') ) {
    store_file_contents($base_dir . '/cache/secured/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template')); 
    }
    
    // Recreate /cache/secured/index.php to restrict web snooping of backup contents, if the cache directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/index.php') ) {
    store_file_contents($base_dir . '/cache/secured/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/activation/.htaccess to restrict web snooping of cache contents, if the activation directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/activation/.htaccess') ) {
    store_file_contents($base_dir . '/cache/secured/activation/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    }
    
    // Recreate /cache/secured/activation/index.php to restrict web snooping of backup contents, if the activation directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/activation/index.php') ) {
    store_file_contents($base_dir . '/cache/secured/activation/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/external_api/.htaccess to restrict web snooping of cache contents, if the apis directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/external_api/.htaccess') ) {
    store_file_contents($base_dir . '/cache/secured/external_api/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    }
    
    // Recreate /cache/secured/external_api/index.php to restrict web snooping of backup contents, if the apis directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/external_api/index.php') ) {
    store_file_contents($base_dir . '/cache/secured/external_api/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/backups/.htaccess to restrict web snooping of cache contents, if the backups directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/backups/.htaccess') ) {
    store_file_contents($base_dir . '/cache/secured/backups/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    }
    
    // Recreate /cache/secured/backups/index.php to restrict web snooping of backup contents, if the backups directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/backups/index.php') ) {
    store_file_contents($base_dir . '/cache/secured/backups/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cache/secured/messages/.htaccess to restrict web snooping of cache contents, if the messages directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/messages/.htaccess') ) {
    store_file_contents($base_dir . '/cache/secured/messages/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    }
    
    // Recreate /cache/secured/messages/index.php to restrict web snooping of backup contents, if the messages directory was deleted / recreated
    if ( !file_exists($base_dir . '/cache/secured/messages/index.php') ) {
    store_file_contents($base_dir . '/cache/secured/messages/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    }
    
    ///////////////////////////////////////////
    
    // Recreate /cron-plugins/.htaccess to restrict web snooping of plugins contents, if the plugins directory was deleted / recreated
    if ( !file_exists($base_dir . '/cron-plugins/.htaccess') ) {
    store_file_contents($base_dir . '/cron-plugins/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
    }
    
    // Recreate /cron-plugins/index.php to restrict web snooping of plugins contents, if the plugins directory was deleted / recreated
    if ( !file_exists($base_dir . '/cron-plugins/index.php') ) {
    store_file_contents($base_dir . '/cron-plugins/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
    }
    
    // Recreate /cron-plugins/htaccess_security_check.dat to test htaccess activation, if the plugins directory was deleted / recreated
    if ( !file_exists($base_dir . '/cron-plugins/htaccess_security_check.dat') ) {
    store_file_contents($base_dir . '/cron-plugins/htaccess_security_check.dat', file_get_contents($base_dir . '/templates/back-end/access_test.template')); 
    }
    
    ///////////////////////////////////////////


}

 
?>
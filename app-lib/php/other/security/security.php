<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Recreate /cache/.htaccess to restrict web snooping of cache contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/.htaccess') ) {
store_file_contents($base_dir . '/cache/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
}

// Recreate /cache/index.php to restrict web snooping of backup contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/index.php') ) {
store_file_contents($base_dir . '/cache/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
}

// Recreate /cache/secured/.htaccess to restrict web snooping of backup contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/.htaccess') ) {
store_file_contents($base_dir . '/cache/secured/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template')); 
}

// Recreate /cache/secured/index.php to restrict web snooping of backup contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/index.php') ) {
store_file_contents($base_dir . '/cache/secured/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
}

// Recreate /cache/secured/backups/.htaccess to restrict web snooping of cache contents, if the backups directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/backups/.htaccess') ) {
store_file_contents($base_dir . '/cache/secured/backups/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
}

// Recreate /cache/secured/backups/index.php to restrict web snooping of backup contents, if the backups directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/backups/index.php') ) {
store_file_contents($base_dir . '/cache/secured/backups/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
}

// Recreate /cache/secured/messages/.htaccess to restrict web snooping of cache contents, if the messages directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/messages/.htaccess') ) {
store_file_contents($base_dir . '/cache/secured/messages/.htaccess', file_get_contents($base_dir . '/templates/back-end/deny-all-htaccess.template') ); 
}

// Recreate /cache/secured/backups/index.php to restrict web snooping of backup contents, if the messages directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/secured/messages/index.php') ) {
store_file_contents($base_dir . '/cache/secured/messages/index.php', file_get_contents($base_dir . '/templates/back-end/403-directory-index.template')); 
}





 
?>
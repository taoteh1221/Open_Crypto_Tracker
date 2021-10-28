<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


require_once($base_dir . '/app-lib/php/other/sub-init/minimized-sub-init.php');


// CSRF attack protection (REQUIRED #POST# VAR 'submit_check')
if ( $_POST['submit_check'] != 1 ) {
$ct_gen->log('security_error', 'Missing "submit_check" POST data (-possible- CSRF attack) for request: ' . $_SERVER['REQUEST_URI']);
$ct_cache->error_logs();
exit;
}


$file = tempnam(sys_get_temp_dir(), 'temp');
$fp = fopen($file, 'w');

fwrite($fp, $_COOKIE['notes']);
fclose($fp);

$ct_gen->file_download($file, 'Trading-Notes.txt'); // Download file (by default deletes after download, then exits)
exit;


?>
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Test mode (retrieves current block height)    
$solana_block_height = $ct['api']->solana_rpc('getBlockHeight', false, 0, $_POST['ext_apis']['solana_rpc_server'])['result'];
	
	
if (
!isset($solana_block_height)
|| isset($solana_block_height) && !is_int($solana_block_height)
|| isset($solana_block_height) && $solana_block_height < 1
) {
$ct['update_config_error'] .= 'Solana RPC Server "' . $_POST['ext_apis']['solana_rpc_server'] . '" query test FAILED (make sure you entered the RPC endpoint address correctly)';
}
  
  
// Make sure Twilio number is set properly
if ( isset($_POST['ext_apis']['twilio_number']) && $_POST['ext_apis']['twilio_number'] != '' && !preg_match("/^\\d+$/", $_POST['ext_apis']['twilio_number']) ) {
$ct['update_config_error'] = 'Twilio Number formatting is NOT valid: ' . $_POST['ext_apis']['twilio_number'] . ' (format MUST be ONLY NUMBERS)';
}
        

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
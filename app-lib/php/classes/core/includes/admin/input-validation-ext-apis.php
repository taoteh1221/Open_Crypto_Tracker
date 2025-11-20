<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Test mode (retrieves current BITCOIN block height)    
if ( trim($_POST['ext_apis']['bitcoin_rpc_server']) != '' ) {
     
$bitcoin_block_height_test = $ct['api']->blockchain_rpc('bitcoin', 'getblockcount', false, 0, $_POST['ext_apis']['bitcoin_rpc_server']);

var_dump($bitcoin_block_height_test);

	
     if (
     !isset($bitcoin_block_height_test['result'])
     || isset($bitcoin_block_height_test['result']) && !is_int($bitcoin_block_height_test['result'])
     || isset($bitcoin_block_height_test['result']) && $bitcoin_block_height_test['result'] < 1
     ) {
     $ct['update_config_error'] .= '<br />Bitcoin RPC Server "' . $_POST['ext_apis']['bitcoin_rpc_server'] . '" query test FAILED (make sure you entered the RPC endpoint address correctly)';
     }
     
     
}
else {
$ct['update_config_error'] .= '<br />Bitcoin RPC Server address is BLANK';
}


// Test mode (retrieves current SOLANA block height)    
if ( trim($_POST['ext_apis']['solana_rpc_server']) != '' ) {
     
$solana_block_height_test = $ct['api']->blockchain_rpc('solana', 'getBlockHeight', false, 0, $_POST['ext_apis']['solana_rpc_server']);


     if (
     !isset($solana_block_height_test['result'])
     || isset($solana_block_height_test['result']) && !is_int($solana_block_height_test['result'])
     || isset($solana_block_height_test['result']) && $solana_block_height_test['result'] < 1
     ) {
     $ct['update_config_error'] .= '<br />Solana RPC Server "' . $_POST['ext_apis']['solana_rpc_server'] . '" query test FAILED (make sure you entered the RPC endpoint address correctly)';
     }
  
  
}
else {
$ct['update_config_error'] .= '<br />Solana RPC Server address is BLANK';
}
	
  
// Make sure Twilio number is set properly
if ( isset($_POST['ext_apis']['twilio_number']) && $_POST['ext_apis']['twilio_number'] != '' && !preg_match("/^\\d+$/", $_POST['ext_apis']['twilio_number']) ) {
$ct['update_config_error'] .= '<br />Twilio Number formatting is NOT valid: ' . $_POST['ext_apis']['twilio_number'] . ' (MUST be ONLY NUMBERS)';
}
        

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


// WE ONLY WANT ANY WHITESPACE USED IN INTERFACING TO RUN IN 'UI' RUNTIME MODE!!
if ( $runtime_mode == 'ui' ) {
?>

<link rel="stylesheet" href="<?=$ct['plug']->plug_dir(true)?>/plug-assets/style.css" type="text/css" />
	

    <div class="container">

        
        
	</div>
    <!-- .container END -->
		

<?php
}
elseif ( $runtime_mode == 'webhook' ) {
     

     if ( !isset($webhook_params[0]) ) {
     $result = array('error' => "No blockchain network specified, please include AT LEAST ONE forwardslash-delimited parameter designating the service being used (ethereum / solana / etc) like so: /" . $ct['int_webhook_base_endpoint'] . $webhook_key . "/solana/PARAM2/PARAM3/ETC");
     echo json_encode($result, JSON_PRETTY_PRINT);
     }
     elseif ( $webhook_params[0] == 'ethereum' ) {
     echo $plug['class'][$this_plug]->ethereum_data($test_params);
     }
     elseif ( $webhook_params[0] == 'solana' ) {
     echo $plug['class'][$this_plug]->solana_data($test_params);
     }
     else {
     $result = array('error' => "No blockchain network match for: " . $webhook_params[0]);
     echo json_encode($result, JSON_PRETTY_PRINT);
     }
     

}


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>
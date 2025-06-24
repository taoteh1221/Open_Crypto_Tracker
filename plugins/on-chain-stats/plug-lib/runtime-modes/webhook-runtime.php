<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( !isset($plug['webhook'][$this_plug]['params'][0]) ) {
$result = array('error' => "No blockchain network specified, please include AT LEAST ONE forwardslash-delimited parameter designating the service being used (ethereum / solana / etc) like so: /" . $ct['int_webhook_base_endpoint'] . $plug['webhook'][$this_plug]['key'] . "/solana/PARAM2/PARAM3/ETC");
echo json_encode($result, JSON_PRETTY_PRINT);
}
elseif ( $plug['webhook'][$this_plug]['params'][0] == 'ethereum' ) {
echo $plug['class'][$this_plug]->ethereum_data($test_params);
}
elseif ( $plug['webhook'][$this_plug]['params'][0] == 'solana' ) {
echo $plug['class'][$this_plug]->solana_data($test_params);
}
else {
$result = array('error' => "No blockchain network match for: " . $plug['webhook'][$this_plug]['params'][0]);
echo json_encode($result, JSON_PRETTY_PRINT);
}     


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>